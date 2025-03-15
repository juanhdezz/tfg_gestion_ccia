<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DatabaseController extends Controller
{
    public function cambiarBaseDatos(Request $request)
    {
        $connection = $request->input('connection');
        
        // Validar que la conexión sea válida
        if (in_array($connection, ['mysql', 'mysql_proximo'])) {
            Session::put('db_connection', $connection);
        }
        
        return redirect()->back();
    }
}