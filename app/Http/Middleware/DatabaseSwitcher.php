<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;


class DatabaseSwitcher
{
    public function handle(Request $request, Closure $next)
    {
        // Obtener la conexión de la sesión o usar la predeterminada
        $connection = Session::get('db_connection', 'mysql');
        
        // Establecer la conexión como la predeterminada
        Config::set('database.default', $connection);
        
        return $next($request);
    }
}
