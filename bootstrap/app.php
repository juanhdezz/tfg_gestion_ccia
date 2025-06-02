<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\QueryException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {        // Alias para middleware personalizado
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'impersonate' => \App\Http\Middleware\ImpersonateMiddleware::class,
        ]);
        
        // Añadir DatabaseSwitcher al middleware web
        $middleware->web([
            \App\Http\Middleware\DatabaseSwitcher::class,
            \App\Http\Middleware\RestoreDatabaseConnection::class,
            //\App\Http\Middleware\ImpersonateMiddleware::class,
        ]);

        // Asegurar que el middleware de impersonación se ejecute ANTES que el de autenticación
        $middleware->priority([
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\ImpersonateMiddleware::class,
            \Illuminate\Auth\Middleware\Authenticate::class,
            \App\Http\Middleware\DatabaseSwitcher::class,
            \App\Http\Middleware\RestoreDatabaseConnection::class,
        ]);

        // Añadir el middleware de impersonación específicamente después de StartSession
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\ImpersonateMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        // Manejar errores de base de datos de forma simple
        $exceptions->render(function (QueryException $e, $request) {
            $errorMessage = $e->getMessage();
            
            // Detectar si es una tabla que no existe
            if (preg_match("/Table '.*\.(.*)' doesn't exist/", $errorMessage, $matches)) {
                $tableName = $matches[1];
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => "La tabla '{$tableName}' no existe en la base de datos",
                        'table' => $tableName,
                        'solution' => 'Ejecuta el script de migración SQL proporcionado'
                    ], 500);
                }

                return response()->view('errors.missing-table', [
                    'tableName' => $tableName,
                    'fullError' => config('app.debug') ? $errorMessage : null
                ], 500);
            }

            // Detectar si es una columna que no existe
            if (preg_match("/Unknown column '(.*)' in 'field list'/", $errorMessage, $matches)) {
                $columnName = $matches[1];
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => "La columna '{$columnName}' no existe",
                        'column' => $columnName,
                        'solution' => 'Verifica la estructura de la base de datos'
                    ], 500);
                }

                return response()->view('errors.missing-column', [
                    'columnName' => $columnName,
                    'fullError' => config('app.debug') ? $errorMessage : null
                ], 500);
            }

            // Para otros errores de base de datos
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Error de base de datos',
                    'message' => config('app.debug') ? $errorMessage : 'Error interno del servidor'
                ], 500);
            }

            return response()->view('errors.database-general', [
                'error' => 'Error de base de datos',
                'fullError' => config('app.debug') ? $errorMessage : null
            ], 500);
        });
        
    })
    ->create();