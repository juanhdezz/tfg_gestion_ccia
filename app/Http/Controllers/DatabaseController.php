<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DatabaseController extends Controller
{
    public function cambiarBaseDatos(Request $request)
    {
        $connection = $request->input('connection');
        $context = $request->input('context', 'general'); // Contexto del cambio
        
        // Validar que la conexión sea válida
        if (in_array($connection, ['mysql', 'mysql_proximo'])) {
            // Si estamos cambiando desde la gestión de tutorías, guardar la conexión original
            if ($context === 'tutorias' && !Session::has('db_connection_original')) {
                Session::put('db_connection_original', Session::get('db_connection', 'mysql'));
            }
            
            Session::put('db_connection', $connection);
        }
        
        return redirect()->back();
    }

    /**
     * Restaurar la conexión original de base de datos
     */
    public function restaurarBaseDatos()
    {
        if (Session::has('db_connection_original')) {
            $conexionOriginal = Session::get('db_connection_original');
            Session::put('db_connection', $conexionOriginal);
            Session::forget('db_connection_original');
        }
        
        return redirect()->route('dashboard');
    }
}