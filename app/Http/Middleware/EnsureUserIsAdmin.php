<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica si el usuario está autenticado y tiene alguno de los roles permitidos
        if (Auth::check() && (Auth::user()->hasRole('admin') || 
                              Auth::user()->hasRole('secretario') || 
                              Auth::user()->hasRole('subdirectorDocente'))) {
            return $next($request);
        }

        // Si no tiene ninguno de los roles, redirige a una página de error o al inicio
        return redirect('/')->with('error', 'No tienes permiso para acceder a esta página.');
    }
}
