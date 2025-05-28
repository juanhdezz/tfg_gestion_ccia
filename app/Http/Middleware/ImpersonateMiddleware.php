<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Usuario;

class ImpersonateMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $route = $request->route() ? $request->route()->getName() : null;
        
        Log::debug('ImpersonateMiddleware ejecutándose', [
            'route' => $route,
            'has_session' => Session::has('impersonate_user_id'),
            'session_user_id' => Session::get('impersonate_user_id'),
            'current_auth_id' => Auth::id()
        ]);

        // NO aplicar middleware en rutas de impersonación
        if (in_array($route, ['impersonate.start', 'impersonate.stop'])) {
            Log::debug('Saltando middleware para ruta de impersonación', ['route' => $route]);
            return $next($request);
        }

        // Solo aplicar si hay una sesión de impersonación activa
        if (Session::has('impersonate_user_id')) {
            $impersonatedUserId = Session::get('impersonate_user_id');
            $impersonatedUser = Usuario::find($impersonatedUserId);
            
            if ($impersonatedUser && Auth::id() != $impersonatedUserId) {
                Log::info('Aplicando impersonación', [
                    'original_user_id' => Session::get('original_user_id'),
                    'impersonated_user_id' => $impersonatedUserId,
                    'impersonated_user_name' => $impersonatedUser->nombre . ' ' . $impersonatedUser->apellidos,
                    'before_auth_id' => Auth::id()
                ]);
                
                // Cambiar temporalmente el usuario autenticado
                Auth::setUser($impersonatedUser);
                
                Log::info('Usuario cambiado', [
                    'after_auth_id' => Auth::id(),
                    'auth_user_name' => Auth::user()->nombre . ' ' . Auth::user()->apellidos
                ]);
            } else if (!$impersonatedUser) {
                // Si el usuario impersonado no existe, limpiar la sesión
                Log::warning('Usuario impersonado no encontrado, limpiando sesión');
                Session::forget(['impersonate_user_id', 'original_user_id', 'impersonate_start_time']);
            }
        }
        
        return $next($request);
    }
}