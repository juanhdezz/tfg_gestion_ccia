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
        // Verifica si el usuario está autenticado y tiene el rol "admin"
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return $next($request);
        }

        // Si no tiene el rol, redirige a una página de error o al inicio
        return redirect('/')->with('error', 'No tienes permiso para acceder a esta página.');
    }
}
