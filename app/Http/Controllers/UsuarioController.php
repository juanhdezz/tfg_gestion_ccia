<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\CategoriaDocente;
use App\Models\Miembro;
use App\Models\Grupo;
use Illuminate\Http\Request;
use App\Exports\ListaUsuariosExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Despacho;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Helpers\ImpersonationHelper;




class UsuarioController extends Controller
{    public function index(Request $request)
{
    // Debug temporal CORREGIDO - agregar dentro del método
        Log::info('Debug usuario actual', [
            'auth_id' => Auth::id(),
            'auth_name' => Auth::user() ? Auth::user()->nombre : 'No autenticado',
            'session_impersonate' => Session::get('impersonate_user_id'),
            'has_impersonate_session' => Session::has('impersonate_user_id')
        ]);
    
    $search = $request->input('search');
    $categoriaFiltro = $request->input('categoria');
    $grupoFiltro = $request->input('grupo');    $usuarios = Usuario::with([
            'despacho', 
            'miembros' => function ($query) {
                $query->with(['categoriaDocente', 'grupo'])
                      ->whereHas('categoriaDocente')
                      ->whereHas('grupo');
            }
        ])
        ->when($search, function ($query, $search) {
            return $query->where('nombre', 'LIKE', "%{$search}%")
                         ->orWhere('apellidos', 'LIKE', "%{$search}%");
        })
        ->when($categoriaFiltro, function ($query, $categoriaFiltro) {
            return $query->whereHas('miembros', function ($q) use ($categoriaFiltro) {
                $q->where('id_categoria', $categoriaFiltro)
                  ->whereHas('categoriaDocente');
            });
        })
        ->when($grupoFiltro, function ($query, $grupoFiltro) {
            return $query->whereHas('miembros', function ($q) use ($grupoFiltro) {
                $q->where('id_grupo', $grupoFiltro)
                  ->whereHas('grupo');
            });
        })
        ->get();

    $categorias = CategoriaDocente::all();
    $grupos = Grupo::all();

    return view('usuarios.index', compact('usuarios', 'search', 'categorias', 'grupos', 'categoriaFiltro', 'grupoFiltro'));
}public function show($id)
    {
        $usuario = Usuario::with([
            'despacho',
            'miembros' => function ($query) {
                $query->with(['categoriaDocente', 'grupo'])
                      ->orderBy('numero_orden');
            }
        ])->find($id);
        
        if (is_null($usuario)) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado');
        }
        
        return view('usuarios.show', compact('usuario'));
    }public function create()
    {
        // Obtener todos los despachos
    $despachos = Despacho::all();
    $roles = Role::all(); // Obtener todos los roles
    $categorias = CategoriaDocente::all(); // Obtener todas las categorías
    $grupos = Grupo::all(); // Obtener todos los grupos
    // Obtener todas las titulaciones para el desplegable
    $titulaciones = \App\Models\Titulacion::orderBy('nombre_titulacion')->get();

    // Pasar los despachos a la vista
    return view('usuarios.create', compact('despachos','titulaciones','roles','categorias','grupos'));
    }

public function store(Request $request)
{    
    // Log de depuración - inicio del método
    Log::info('=== INICIO store() ===', [
        'method' => $request->method(),
        'url' => $request->fullUrl(),
        'all_data' => $request->except(['passwd']),
        'has_categoria' => $request->filled('id_categoria'),
        'has_grupo' => $request->filled('id_grupo'),
        'timestamp' => now()
    ]);    // Validación de datos
    try {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuario,correo',
            'login' => 'required|string|unique:usuario,login',
            'dni_pasaporte' => 'required|string|unique:usuario,dni_pasaporte',
            'passwd' => 'required|string|min:6',
            'id_categoria' => 'nullable|exists:categoria,id_categoria',
            'id_grupo' => 'nullable|exists:grupo,id_grupo',
            'numero_orden' => 'nullable|integer|min:1'
        ]);
        
        Log::info('Validación pasada correctamente');
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Error de validación:', [
            'errors' => $e->errors(),
            'input' => $request->except(['passwd'])
        ]);
        throw $e;
    }    try {
        Log::info('Iniciando creación de usuario...');
        
        // Preparar datos del usuario (excluir campos específicos del miembro)
        $userData = $request->except(['id_categoria', 'id_grupo', 'numero_orden']);
        $userData['passwd'] = bcrypt($request->passwd);
        
        Log::info('Datos del usuario preparados', [
            'user_data_keys' => array_keys($userData)
        ]);

        // Crear el usuario usando transacción para garantizar consistencia
        DB::beginTransaction();
        Log::info('Transacción iniciada');
        
        $usuario = Usuario::create($userData);
        Log::info('Usuario creado', ['usuario_id' => $usuario->id_usuario]);
        
        if ($request->filled('roles')) {
            $usuario->syncRoles($request->roles);
            Log::info('Roles asignados', ['roles' => $request->roles]);
        }
        
        // Crear registro de miembro si se proporcionaron categoría Y grupo
        if ($request->filled('id_categoria') && $request->filled('id_grupo')) {
            Log::info('Creando registro de miembro...', [
                'categoria' => $request->id_categoria,
                'grupo' => $request->id_grupo
            ]);
            
            // Determinar el número de orden si no se proporcionó
            $numeroOrden = $request->numero_orden;
            if (!$numeroOrden) {
                // Obtener el siguiente número de orden para esta categoría y grupo
                $maxOrden = Miembro::where('id_categoria', $request->id_categoria)
                                  ->where('id_grupo', $request->id_grupo)
                                  ->max('numero_orden');
                $numeroOrden = ($maxOrden ?? 0) + 1;
                Log::info('Número de orden calculado automáticamente', ['numero_orden' => $numeroOrden]);
            }
              // Crear el registro de miembro
            $miembroData = [
                'id_usuario' => $usuario->id_usuario,
                'id_categoria' => $request->id_categoria,
                'id_grupo' => $request->id_grupo,
                'numero_orden' => $numeroOrden,
                'fecha_entrada' => now()->format('Y-m-d') // Solo la fecha, no timestamp completo
            ];
            
            Log::info('Datos del miembro a crear', $miembroData);
            
            Miembro::create($miembroData);
            
            Log::info('Miembro creado automáticamente', [
                'usuario_id' => $usuario->id_usuario,
                'categoria_id' => $request->id_categoria,
                'grupo_id' => $request->id_grupo,
                'numero_orden' => $numeroOrden
            ]);
        } else {
            Log::info('No se creó miembro - faltan categoría o grupo', [
                'has_categoria' => $request->filled('id_categoria'),
                'has_grupo' => $request->filled('id_grupo')
            ]);
        }
        
        DB::commit();
        Log::info('Transacción confirmada - usuario creado exitosamente');
        
        $mensaje = 'El usuario ha sido creado exitosamente';
        if ($request->filled('id_categoria') && $request->filled('id_grupo')) {
            $mensaje .= ' y se ha asignado automáticamente a la categoría y grupo seleccionados';
        }
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario creado',
            'text' => $mensaje
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente');
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al crear usuario: ' . $e->getMessage(), [
            'request_data' => $request->except(['passwd']),
            'stack_trace' => $e->getTraceAsString()
        ]);
        
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
        $usuario = Usuario::with(['miembros.categoriaDocente', 'miembros.grupo'])->find($id);
        $despachos = Despacho::all();
        $roles = Role::all(); // Obtener todos los roles disponibles
        $categorias = CategoriaDocente::all(); // Obtener todas las categorías
        $grupos = Grupo::all(); // Obtener todos los grupos
        if (is_null($usuario)) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado');
        }
        return view('usuarios.edit', compact('usuario', 'despachos', 'roles', 'categorias', 'grupos'));
    }
    
public function update(Request $request, $id)
{
    $usuario = Usuario::find($id);
    if (is_null($usuario)) {
        return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado');
    }

    // Log de depuración - inicio del método
    Log::info('=== INICIO update() ===', [
        'user_id' => $id,
        'method' => $request->method(),
        'all_data' => $request->except(['passwd']),
        'has_categoria' => $request->filled('id_categoria'),
        'has_grupo' => $request->filled('id_grupo'),
        'timestamp' => now()
    ]);

    // Validación de datos
    try {
        $validationRules = [
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuario,correo,' . $id . ',id_usuario',
            'login' => 'required|string|unique:usuario,login,' . $id . ',id_usuario',
            'dni_pasaporte' => 'required|string|unique:usuario,dni_pasaporte,' . $id . ',id_usuario',
            'id_categoria' => 'nullable|exists:categoria,id_categoria',
            'id_grupo' => 'nullable|exists:grupo,id_grupo',
            'numero_orden' => 'nullable|integer|min:1'
        ];

        // Solo validar contraseña si se proporciona
        if ($request->filled('passwd')) {
            $validationRules['passwd'] = 'string|min:6|confirmed';
        }

        $request->validate($validationRules);
        
        Log::info('Validación pasada correctamente');
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Error de validación:', [
            'errors' => $e->errors(),
            'input' => $request->except(['passwd'])
        ]);
        throw $e;
    }

    try {
        Log::info('Iniciando actualización de usuario...');
        
        // Usar transacción para garantizar consistencia
        DB::beginTransaction();
        Log::info('Transacción iniciada');

        // Preparar datos del usuario (excluir campos específicos del miembro)
        $userData = $request->except(['passwd', 'passwd_confirmation', 'id_categoria', 'id_grupo', 'numero_orden', 'web']);
        
        // Solo actualizar contraseña si se proporciona
        if ($request->filled('passwd')) {
            $userData['passwd'] = bcrypt($request->passwd);
            Log::info('Contraseña actualizada');
        }
        
        $usuario->update($userData);
        Log::info('Datos básicos del usuario actualizados');

        // Asignar los roles seleccionados
        if ($request->has('roles')) {
            $usuario->syncRoles($request->roles);
            Log::info('Roles asignados', ['roles' => $request->roles]);
        } else {
            $usuario->syncRoles([]);
            Log::info('Roles limpiados - ningún rol asignado');
        }

        // Gestionar membresía académica
        $this->gestionarMembresia($usuario, $request);

        DB::commit();
        Log::info('Transacción confirmada - usuario actualizado exitosamente');
        
        $mensaje = 'El usuario ha sido actualizado exitosamente';
        if ($request->filled('id_categoria') && $request->filled('id_grupo')) {
            $mensaje .= ' y se ha actualizado su asignación académica';
        }
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario actualizado',
            'text' => $mensaje
        ]);
        
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente');
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al actualizar usuario: ' . $e->getMessage(), [
            'user_id' => $id,
            'request_data' => $request->except(['passwd']),
            'stack_trace' => $e->getTraceAsString()
        ]);
        
        session()->flash('swal', [
            'icon' => 'error',
            'title' => 'Error al actualizar usuario',
            'text' => 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.' 
        ]);
        
        return back()->withInput()->with('error', 'Error al actualizar el usuario.');
    }
}

/**
 * Gestionar la membresía académica del usuario
 */
private function gestionarMembresia($usuario, $request)
{
    Log::info('=== Gestionando membresía académica ===', [
        'usuario_id' => $usuario->id_usuario,
        'categoria_nueva' => $request->id_categoria,
        'grupo_nuevo' => $request->id_grupo
    ]);

    // CORRECCIÓN: Buscar el miembro de forma más específica
    $miembroActual = Miembro::where('id_usuario', $usuario->id_usuario)->first();
    
    Log::info('Miembro actual encontrado', [
        'miembro_existe' => $miembroActual ? true : false,
        'miembro_id' => $miembroActual ? $miembroActual->id_usuario : null,
        'categoria_actual' => $miembroActual ? $miembroActual->id_categoria : null,
        'grupo_actual' => $miembroActual ? $miembroActual->id_grupo : null
    ]);
    
    // Caso 1: No hay categoría ni grupo - eliminar membresía si existe
    if (!$request->filled('id_categoria') && !$request->filled('id_grupo')) {
        if ($miembroActual) {
            Log::info('Eliminando membresía existente - sin categoría ni grupo');
            $miembroActual->delete();
        }
        return;
    }

    // Caso 2: Falta categoría o grupo - error si se intenta crear/actualizar
    if (!$request->filled('id_categoria') || !$request->filled('id_grupo')) {
        Log::warning('Membresía incompleta - se requieren tanto categoría como grupo');
        return; // No hacer nada si falta alguno de los dos
    }

    // Caso 3: Hay categoría y grupo - crear o actualizar membresía
    $categoriaId = $request->id_categoria;
    $grupoId = $request->id_grupo;
    $numeroOrden = $request->numero_orden;
    $web = $request->web;

    // Determinar el número de orden si no se proporcionó
    if (!$numeroOrden) {
        $maxOrden = Miembro::where('id_categoria', $categoriaId)
                          ->where('id_grupo', $grupoId)
                          ->when($miembroActual, function($query) use ($miembroActual) {
                              return $query->where('id_usuario', '!=', $miembroActual->id_usuario);
                          })
                          ->max('numero_orden');
        $numeroOrden = ($maxOrden ?? 0) + 1;
        Log::info('Número de orden calculado automáticamente', ['numero_orden' => $numeroOrden]);
    }

    if ($miembroActual) {
        // CORRECCIÓN: Actualizar usando where específico con validación adicional
        Log::info('Actualizando miembro existente', [
            'miembro_id' => $miembroActual->id_usuario,
            'datos_nuevos' => [
                'id_categoria' => $categoriaId,
                'id_grupo' => $grupoId,
                'numero_orden' => $numeroOrden,
                'web' => $web
            ]
        ]);
        
        // Usar actualización con WHERE específico para evitar afectar otros registros
        $affected = Miembro::where('id_usuario', $miembroActual->id_usuario)
                           ->where('id_usuario', $usuario->id_usuario) // Doble verificación
                           ->update([
                               'id_categoria' => $categoriaId,
                               'id_grupo' => $grupoId,
                               'numero_orden' => $numeroOrden,
                               'web' => $web
                           ]);
        
        Log::info('Miembro actualizado', [
            'registros_afectados' => $affected,
            'miembro_id' => $miembroActual->id_usuario
        ]);
        
        if ($affected === 0) {
            Log::warning('No se actualizó ningún registro - posible problema');
        }
        
    } else {
        // Crear nuevo miembro
        Log::info('Creando nuevo miembro');
        
        $miembroData = [
            'id_usuario' => $usuario->id_usuario,
            'id_categoria' => $categoriaId,
            'id_grupo' => $grupoId,
            'numero_orden' => $numeroOrden,
            'web' => $web,
            'fecha_entrada' => now()->format('Y-m-d')
        ];
        
        Log::info('Datos del nuevo miembro', $miembroData);
        
        $nuevoMiembro = Miembro::create($miembroData);
        
        Log::info('Miembro creado exitosamente', [
            'nuevo_miembro_id' => $nuevoMiembro->id_usuario
        ]);
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
        Log::error('Error en checkUniqueness: ' . $e->getMessage());        return response()->json(['exists' => false, 'error' => 'Error del servidor']);
    }
}

    /**
     * Mostrar página de gestión de categorías de usuarios
     */    public function gestionCategorias(Request $request)
    {
        $grupoId = $request->input('grupo');
        $categoriaId = $request->input('categoria');
        
        // Cargar usuarios con sus relaciones asegurando que existan
        $query = Usuario::with([
            'miembros' => function ($query) {
                $query->with(['categoriaDocente', 'grupo'])
                      ->whereHas('categoriaDocente')
                      ->whereHas('grupo');
            }
        ]);
        
        if ($grupoId) {
            $query->whereHas('miembros', function ($q) use ($grupoId) {
                $q->where('id_grupo', $grupoId)
                  ->whereHas('grupo');
            });
        }
        
        if ($categoriaId) {
            $query->whereHas('miembros', function ($q) use ($categoriaId) {
                $q->where('id_categoria', $categoriaId)
                  ->whereHas('categoriaDocente');
            });
        }
        
        $usuarios = $query->get();
        $categorias = CategoriaDocente::orderBy('orden')->get();
        $grupos = Grupo::orderBy('nombre_grupo')->get();
        
        return view('usuarios.gestion-categorias', compact('usuarios', 'categorias', 'grupos', 'grupoId', 'categoriaId'));
    }

    /**
     * Asignar categoría a un usuario en un grupo específico
     */
    public function asignarCategoria(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:usuario,id_usuario',
            'id_grupo' => 'required|exists:grupo,id_grupo',
            'id_categoria' => 'required|exists:categoria,id_categoria',
            'numero_orden' => 'nullable|integer|min:1',
            'tramos_investigacion' => 'nullable|integer|min:0',
            'anio_ultimo_tramo' => 'nullable|integer|min:1990|max:' . date('Y'),
            'fecha_entrada' => 'nullable|date',
            'n_orden_becario' => 'nullable|integer|min:1',
            'web' => 'nullable|url'
        ]);

        try {
            // Verificar si ya existe esta combinación
            $existeMiembro = Miembro::where('id_usuario', $request->id_usuario)
                                   ->where('id_grupo', $request->id_grupo)
                                   ->where('id_categoria', $request->id_categoria)
                                   ->exists();

            if ($existeMiembro) {
                return response()->json([
                    'success' => false,
                    'message' => 'El usuario ya tiene esta categoría en este grupo'
                ], 400);
            }

            // Si no se especifica número de orden, asignar el siguiente disponible
            if (!$request->numero_orden) {
                $ultimoOrden = Miembro::where('id_grupo', $request->id_grupo)
                                    ->where('id_categoria', $request->id_categoria)
                                    ->max('numero_orden');
                $request->merge(['numero_orden' => ($ultimoOrden ?? 0) + 1]);
            }

            Miembro::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Categoría asignada exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al asignar categoría: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar la categoría'
            ], 500);
        }
    }

    /**
     * Remover categoría de un usuario
     */
    public function removerCategoria(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:usuario,id_usuario',
            'id_grupo' => 'required|exists:grupo,id_grupo',
            'id_categoria' => 'required|exists:categoria,id_categoria'
        ]);

        try {
            $miembro = Miembro::where('id_usuario', $request->id_usuario)
                             ->where('id_grupo', $request->id_grupo)
                             ->where('id_categoria', $request->id_categoria)
                             ->first();

            if (!$miembro) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la asignación a remover'
                ], 404);
            }

            $miembro->delete();

            return response()->json([
                'success' => true,
                'message' => 'Categoría removida exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al remover categoría: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al remover la categoría'
            ], 500);
        }
    }    /**
     * Mostrar página de gestión del orden de selección docente
     */
    public function gestionOrdenSeleccion(Request $request)
    {
        // Obtener todos los miembros ordenados por numero_orden
        $miembros = Miembro::with(['usuario', 'categoriaDocente', 'grupo'])
                          ->orderBy('numero_orden')
                          ->get();
        
        return view('usuarios.gestion-orden', compact('miembros'));
    }    /**
     * Actualizar el orden de selección docente
     */
    public function actualizarOrdenSeleccion(Request $request)
    {
        $request->validate([
            'miembros' => 'required|array',
            'miembros.*.id_usuario' => 'required|exists:usuario,id_usuario',
            'miembros.*.numero_orden' => 'required|integer|min:1'
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Verificar que no haya números de orden duplicados
                $numerosOrden = array_column($request->miembros, 'numero_orden');
                if (count($numerosOrden) !== count(array_unique($numerosOrden))) {
                    throw new \Exception('No se permiten números de orden duplicados');
                }

                // Actualizar cada miembro
                foreach ($request->miembros as $miembroData) {
                    Miembro::where('id_usuario', $miembroData['id_usuario'])
                          ->update(['numero_orden' => $miembroData['numero_orden']]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Orden actualizado exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al actualizar orden: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener miembros por grupo y categoría (AJAX)
     */    public function getMiembrosPorGrupoCategoria(Request $request)
    {
        $grupoId = $request->input('grupo');
        $categoriaId = $request->input('categoria');
        
        $query = Miembro::with(['usuario', 'categoriaDocente']);
        
        if ($grupoId) {
            $query->where('id_grupo', $grupoId);
        }
        
        if ($categoriaId) {
            $query->where('id_categoria', $categoriaId);
        }
        
        $miembros = $query->orderBy('numero_orden')->get();
        
        return response()->json([
            'success' => true,
            'miembros' => $miembros
        ]);
    }

    /**
     * Ver información detallada de las categorías de un usuario
     */
    public function verCategorias($id)
    {
        $usuario = Usuario::with(['miembros.categoriaDocente', 'miembros.grupo'])->findOrFail($id);
        
        return view('usuarios.ver-categorias', compact('usuario'));
    }

    /**
     * Muestra el selector de grupo para gestión de orden
     *
     * @return \Illuminate\View\View
     */
    public function seleccionarGrupoOrden()
    {
        $grupos = Grupo::orderBy('nombre_grupo')->get();
        $categorias = CategoriaDocente::visibles()->ordenadoPorOrden()->get();

        return view('usuarios.seleccionar-grupo-orden', compact('grupos', 'categorias'));
    }

    public function actualizarUsuario(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $usuario_id = $request->input('usuario_id');
            $categoria_nueva = $request->input('categoria_nueva');
            $grupo_nuevo = $request->input('grupo_nuevo');
            
            Log::info('=== Gestionando membresía académica ===', [
                'usuario_id' => $usuario_id,
                'categoria_nueva' => $categoria_nueva,
                'grupo_nuevo' => $grupo_nuevo
            ]);
            
            // Verificar si el usuario ya es miembro
            $miembro_actual = DB::table('miembro')
                ->where('id_usuario', $usuario_id)
                ->first();
            
            if ($miembro_actual) {
                Log::info('Miembro actual encontrado', [
                    'miembro_existe' => true,
                    'id_usuario' => $miembro_actual->id_usuario,
                    'categoria_actual' => $miembro_actual->id_categoria,
                    'grupo_actual' => $miembro_actual->id_grupo
                ]);
                
                // Obtener el siguiente número de orden para la nueva categoría
                $siguiente_orden = $this->obtenerSiguienteNumeroOrden($categoria_nueva);
                
                // Actualizar el miembro existente
                $datos_actualizacion = [
                    'id_categoria' => $categoria_nueva,
                    'id_grupo' => $grupo_nuevo,
                    'numero_orden' => $siguiente_orden,
                    'web' => null
                ];
                
                Log::info('Actualizando miembro existente', [
                    'id_usuario' => $miembro_actual->id_usuario,
                    'datos_nuevos' => $datos_actualizacion
                ]);
                
                DB::table('miembro')
                    ->where('id_usuario', $usuario_id) // Cambio aquí: usar id_usuario en lugar de id_usuario
                    ->update($datos_actualizacion);
                
                Log::info('Miembro actualizado correctamente', [
                    'id_usuario' => $usuario_id
                ]);
                
            } else {
                Log::info('Usuario no es miembro, creando nueva membresía');
                
                // Obtener el siguiente número de orden para la nueva categoría
                $siguiente_orden = $this->obtenerSiguienteNumeroOrden($categoria_nueva);
                
                // Crear nuevo miembro
                $datos_nuevo_miembro = [
                    'id_usuario' => $usuario_id,
                    'id_categoria' => $categoria_nueva,
                    'id_grupo' => $grupo_nuevo,
                    'numero_orden' => $siguiente_orden,
                    'web' => null
                ];
                
                Log::info('Creando nuevo miembro', [
                    'datos' => $datos_nuevo_miembro
                ]);
                
                DB::table('miembro')->insert($datos_nuevo_miembro);
                
                Log::info('Nuevo miembro creado correctamente', [
                    'id_usuario' => $usuario_id
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado correctamente',
                'data' => [
                    'usuario_id' => $usuario_id,
                    'categoria' => $categoria_nueva,
                    'grupo' => $grupo_nuevo,
                    'accion' => $miembro_actual ? 'actualizado' : 'creado'
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error al actualizar usuario: ' . $e->getMessage(), [
                'usuario_id' => $usuario_id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }
    private function obtenerSiguienteNumeroOrden($categoria_id)
    {
        try {
            $max_orden = DB::table('miembro')
                ->where('id_categoria', $categoria_id)
                ->max('numero_orden');
            
            $siguiente_orden = ($max_orden ?? 0) + 1;
            
            Log::info('Número de orden calculado', [
                'categoria_id' => $categoria_id,
                'max_orden_actual' => $max_orden,
                'siguiente_orden' => $siguiente_orden
            ]);
            
            return $siguiente_orden;
            
        } catch (\Exception $e) {
            Log::error('Error al obtener número de orden: ' . $e->getMessage());
            return 1; // Valor por defecto
        }
    }
}