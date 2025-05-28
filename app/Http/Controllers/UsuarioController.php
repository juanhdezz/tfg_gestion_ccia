<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Exports\ListaUsuariosExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Despacho;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Helpers\ImpersonationHelper;




class UsuarioController extends Controller
{
    public function index(Request $request)
{
    // Debug temporal CORREGIDO - agregar dentro del método
        Log::info('Debug usuario actual', [
            'auth_id' => Auth::id(),
            'auth_name' => Auth::user() ? Auth::user()->nombre : 'No autenticado',
            'session_impersonate' => Session::get('impersonate_user_id'),
            'has_impersonate_session' => Session::has('impersonate_user_id')
        ]);
    
    $search = $request->input('search');

    $usuarios = Usuario::with(['despacho'])
        ->when($search, function ($query, $search) {
            return $query->where('nombre', 'LIKE', "%{$search}%")
                         ->orWhere('apellidos', 'LIKE', "%{$search}%");
        })
        ->get();

    return view('usuarios.index', compact('usuarios', 'search'));
}    public function show($id)
    {
        $usuario = Usuario::find($id);
        if (is_null($usuario)) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado');
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
    

    try {
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
        
    } catch (\Exception $e) {
        Log::error('Error al crear usuario: ' . $e->getMessage());
        
        session()->flash('swal', [
            'icon' => 'error',
            'title' => 'Error al crear usuario',
            'text' => 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.' 
        ]);
        
        return back()->withInput()->with('error', 'Error al crear el usuario. Por favor, verifique los datos.');
    }
}  

public function edit($id)
    {
        $usuario = Usuario::find($id);
        $despachos = Despacho::all();
        $roles = Role::all(); // Obtener todos los roles disponibles
        if (is_null($usuario)) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado');
        }
        return view('usuarios.edit', compact('usuario', 'despachos', 'roles'));
    }    
    
public function update(Request $request, $id)
{
    $usuario = Usuario::find($id);
    if (is_null($usuario)) {
        return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado');
    }
            

    try {
        $data = $request->except(['passwd', 'passwd_confirmation']);
        
        // Solo actualizar contraseña si se proporciona
        if ($request->filled('passwd')) {
            $data['passwd'] = bcrypt($request->passwd);
        }
        
        $usuario->update($data);

        // Asignar los roles seleccionados
        if ($request->has('roles')) {
            $usuario->syncRoles($request->roles);
        } else {
            $usuario->syncRoles([]);
        }
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario actualizado',
            'text' => 'El usuario ha sido actualizado exitosamente' 
        ]);
        
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente');
        
    } catch (\Exception $e) {
        Log::error('Error al actualizar usuario: ' . $e->getMessage());
        
        session()->flash('swal', [
            'icon' => 'error',
            'title' => 'Error al actualizar usuario',
            'text' => 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.' 
        ]);
        
        return back()->withInput()->with('error', 'Error al actualizar el usuario.');
    }
}

    public function destroy($id)
    {
        $usuario = Usuario::find($id);
        if (is_null($usuario)) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado');
        }
        $usuario->delete();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario eliminado',
            'text' => 'El usuario ha sido eliminado exitosamente' 
        ]);
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente');
    }

    public function export()
    {
        return Excel::download(new ListaUsuariosExport, 'usuarios.xlsx');
    }

public function checkUniqueness(Request $request)
{
    try {
        $field = $request->input('field');
        $value = $request->input('value');
        $userId = $request->input('user_id'); // Para excluir en ediciones
        
        // Validar que el campo y valor existan
        if (!$field || !$value) {
            return response()->json(['exists' => false, 'error' => 'Campo o valor faltante']);
        }
        
        // Validar que el campo sea uno de los permitidos
        $allowedFields = ['login', 'correo', 'dni_pasaporte'];
        if (!in_array($field, $allowedFields)) {
            return response()->json(['exists' => false, 'error' => 'Campo no permitido']);
        }
        
        $query = Usuario::where($field, $value);
        
        // Si estamos editando, excluir el usuario actual
        if ($userId) {
            $query->where('id_usuario', '!=', $userId);
        }
        
        $exists = $query->exists();
        
        return response()->json([
            'exists' => $exists,
            'field' => $field,
            'value' => $value
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error en checkUniqueness: ' . $e->getMessage());
        return response()->json(['exists' => false, 'error' => 'Error del servidor']);
    }
}
}