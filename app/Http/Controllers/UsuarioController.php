<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Exports\ListaUsuariosExport;
use Maatwebsite\Excel\Facades\Excel;

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
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $usuario = Usuario::create($request->all());
        return redirect()->route('usuarios.index')->with('success', 'Usuario created successfully');
    }

    public function edit($id)
    {
        $usuario = Usuario::find($id);
        if (is_null($usuario)) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario not found');
        }
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::find($id);
        if (is_null($usuario)) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario not found');
        }
        $usuario->update($request->all());
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