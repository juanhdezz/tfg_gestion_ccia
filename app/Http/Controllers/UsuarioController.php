<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Exports\ListaUsuariosExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Despacho;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all();
        return view('usuarios.index', compact('usuarios'));
    }

    public function show($id)
    {
        $usuario = Usuario::find($id);
        if (is_null($usuario)) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario not found');
        }
        return view('usuarios.show', compact('usuario'));
    }

    public function create()
    {
        // Obtener todos los despachos
    $despachos = Despacho::all();

    // Pasar los despachos a la vista
    return view('usuarios.create', compact('despachos'));
    }

    public function store(Request $request)
{    

    // Encriptar la contraseña
    $data = $request->all();
    $data['passwd'] = bcrypt($request->passwd);

    // Crear el usuario
    $usuario = Usuario::create($data);

    return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente');
}

    public function edit($id)
    {
        $usuario = Usuario::find($id);
        $despachos = Despacho::all();
        if (is_null($usuario)) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario not found');
        }
        return view('usuarios.edit', compact('usuario'),compact('despachos'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::find($id);
        if (is_null($usuario)) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario not found');
        }
        
        $usuario->update($request->all());
        if ($request->filled('passwd')) {
            $usuario->update([
                'passwd' => bcrypt($request->passwd),
            ]);
        }
        return redirect()->route('usuarios.index')->with('success', 'Usuario updated successfully');
    }

    public function destroy($id)
    {
        $usuario = Usuario::find($id);
        if (is_null($usuario)) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario not found');
        }
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario deleted successfully');
    }

    public function export()
    {
        return Excel::download(new ListaUsuariosExport, 'usuarios.xlsx');
    }
}