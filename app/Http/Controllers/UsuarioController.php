<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Exports\ListaUsuariosExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Despacho;
use Spatie\Permission\Models\Role;


class UsuarioController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');

    $usuarios = Usuario::with(['despacho'])
        ->when($search, function ($query, $search) {
            return $query->where('nombre', 'LIKE', "%{$search}%")
                         ->orWhere('apellidos', 'LIKE', "%{$search}%");
        })
        ->get();

    return view('usuarios.index', compact('usuarios', 'search'));
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
    $roles = Role::all(); // Obtener todos los roles
    // Obtener todas las titulaciones para el desplegable
    $titulaciones = \App\Models\Titulacion::orderBy('nombre_titulacion')->get();

    // Pasar los despachos a la vista
    return view('usuarios.create', compact('despachos','titulaciones','roles'),compact('roles'));
    }

    public function store(Request $request)
{    

    // Encriptar la contraseña
    $data = $request->all();
    $data['passwd'] = bcrypt($request->passwd);

    // Crear el usuario
    $usuario = Usuario::create($data);
    $usuario->syncRoles($request->roles); // Asignar los roles al usuario
    session()->flash('swal', [
        'icon' => 'success',
        'title' => 'Usuario añadido',
        'text' => 'El usuario ha sido añadido exitosamente' 
    ]);

    return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente');
}

    public function edit($id)
    {
        $usuario = Usuario::find($id);
        $despachos = Despacho::all();
        $roles = Role::all(); // Obtener todos los roles disponibles
        if (is_null($usuario)) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario not found');
        }
        return view('usuarios.edit', compact('usuario', 'despachos', 'roles'));
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

        // Asignar los roles seleccionados
    if ($request->has('roles')) {
        $usuario->syncRoles($request->roles); // Sincronizar roles (remueve los anteriores y asigna los nuevos)
    } else {
        $usuario->syncRoles([]); // Si no se selecciona ninguno, se eliminan los roles existentes
    }
    session()->flash('swal', [
        'icon' => 'success',
        'title' => 'Usuario actualizado',
        'text' => 'El usuario ha sido actualizado exitosamente' 
    ]);
        return redirect()->route('usuarios.index')->with('success', 'Usuario updated successfully');
    }

    public function destroy($id)
    {
        $usuario = Usuario::find($id);
        if (is_null($usuario)) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario not found');
        }
        $usuario->delete();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario eliminado',
            'text' => 'El usuario ha sido eliminado exitosamente' 
        ]);
        return redirect()->route('usuarios.index')->with('success', 'Usuario deleted successfully');
    }

    public function export()
    {
        return Excel::download(new ListaUsuariosExport, 'usuarios.xlsx');
    }
}