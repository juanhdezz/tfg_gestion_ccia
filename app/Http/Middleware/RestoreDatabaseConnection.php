<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class RestoreDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Lista de rutas que pertenecen al contexto de tutorías
        $tutoriasRoutes = [
            'tutorias.gestion',
            'tutorias.index', 
            'tutorias.ver',
            'tutorias.plazos',
            'tutorias.actualizar'
        ];
        
        // Si no estamos en una ruta de tutorías y tenemos una conexión original guardada
        if (!in_array($request->route()->getName(), $tutoriasRoutes) && Session::has('db_connection_original')) {
            // Solo restaurar si no estamos hacbiando la base de datos explícitamente
            if ($request->route()->getName() !== 'cambiar.base.datos') {
                $conexionOriginal = Session::get('db_connection_original');
                Session::put('db_connection', $conexionOriginal);
                Session::forget('db_connection_original');
            }
        }
        
        return $next($request);
    }
}
