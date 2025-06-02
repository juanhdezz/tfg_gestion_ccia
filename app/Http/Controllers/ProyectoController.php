<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Plazo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class ProyectoController extends Controller
{
    private function proyectoTieneCompensaciones(Proyecto $proyecto)
{
    return $proyecto->compensaciones()->exists();
}

    /**
 * Verifica si estamos dentro del plazo para modificar compensaciones de proyectos
 */
private function dentroDePlazoCompensaciones()
{
    $fechaActual = Carbon::now();
    $nombrePlazo = "MODIFICAR COMPENSACIONES PROYECTOS";
    
    // Los plazos están en la BD del curso actual
    $conexionOriginal = config('database.default');
    config(['database.default' => 'mysql']);
    
    try {
        $plazo = Plazo::where('nombre_plazo', 'LIKE', "%$nombrePlazo%")->first();
        
        if (!$plazo) {
            config(['database.default' => $conexionOriginal]);
            return false;
        }
        
        $fechaInicio = Carbon::parse($plazo->fecha_inicio);
        $fechaFin = Carbon::parse($plazo->fecha_fin);
        
        $resultado = $fechaActual->between($fechaInicio, $fechaFin);
        
        config(['database.default' => $conexionOriginal]);
        return $resultado;
        
    } catch (\Exception $e) {
        config(['database.default' => $conexionOriginal]);
        return false;
    }
}

    /**
     * Mostrar listado de proyectos con filtros básicos
     */
    public function index(Request $request)
{
    $query = Proyecto::query();
    
    // Verificar el rol del usuario
    $usuario = Auth::user();
    $esAdminOGestor = $usuario->hasAnyRole(['admin', 'secretario', 'subdirectorDocente']);
    
    // Si no es admin/secretario/subdirectorDocente, solo mostrar SUS proyectos
    if (!$esAdminOGestor) {
        $query->where('id_responsable', $usuario->id_usuario);
    }
    
    // Filtros de búsqueda básicos
    if ($request->filled('buscar')) {
        $buscar = $request->buscar;
        $query->where(function($q) use ($buscar) {
            $q->where('titulo', 'like', '%' . $buscar . '%')
              ->orWhere('codigo', 'like', '%' . $buscar . '%')
              ->orWhere('nombre_corto', 'like', '%' . $buscar . '%');
        });
    }
    
    // Filtro de estado (activo/inactivo)
    if ($request->filled('estado')) {
        $query->where('activo', $request->estado == 'activo' ? 1 : 0);
    }
    
    // Filtro por tipo de financiación
    if ($request->filled('financiacion')) {
        $query->where('financiacion', $request->financiacion);
    }
    
    // Ordenamiento
    $orderBy = $request->input('order_by', 'fecha_inicio');
    $orderDirection = $request->input('order_direction', 'desc');
    
    // Cargar las compensaciones junto con los proyectos para evitar consultas N+1
    $proyectos = $query->with(['responsable', 'compensaciones'])
                      ->orderBy($orderBy, $orderDirection)
                      ->paginate(10)
                      ->withQueryString();

    // Obtener tipos de financiación para el filtro
    $tiposFinanciacion = DB::table('proyecto')
                           ->select('financiacion')
                           ->whereNotNull('financiacion')
                           ->distinct()
                           ->pluck('financiacion');

    // Verificar si estamos dentro del plazo para compensaciones
    $dentroDePlazoCompensaciones = $this->dentroDePlazoCompensaciones();

    // Verificar si el usuario puede asignar compensaciones
    $puedeAsignarCompensaciones = $esAdminOGestor && $dentroDePlazoCompensaciones;

    return view('proyectos.index', compact(
        'proyectos', 
        'tiposFinanciacion', 
        'puedeAsignarCompensaciones',
        'esAdminOGestor'
    ));
}

    /**
     * Mostrar formulario para crear un nuevo proyecto
     */
    public function create()
    {
        // Obtener usuarios que pueden ser responsables
        $responsables = Usuario::all();
        
        return view('proyectos.create', compact('responsables'));
    }

    /**
     * Almacenar un nuevo proyecto
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:32|unique:proyecto,codigo',
            'titulo' => 'required|string|max:512',
            'nombre_corto' => 'required|string|max:128|unique:proyecto,nombre_corto',
            'id_responsable' => 'required|exists:usuario,id_usuario',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'activo' => 'boolean',
            'web' => 'nullable|url|max:256',
            'financiacion' => 'nullable|string|max:16',
            'creditos_compensacion_proyecto' => 'nullable|numeric|min:0|max:100'
        ]);
        
        try {
            $proyecto = Proyecto::create($validated);
            
            return redirect()->route('proyectos.show', $proyecto)
                             ->with('success', 'Proyecto creado correctamente.');
                             
        } catch (\Exception $e) {
            return back()->withInput()
                         ->with('error', 'Error al crear el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de un proyecto específico
     */
    public function show(Proyecto $proyecto)
    {
        // Cargar la relación con el responsable
        $proyecto->load('responsable');
        
        return view('proyectos.show', compact('proyecto'));
    }

    /**
     * Mostrar formulario para editar un proyecto
     * 
     * @param int $id ID del proyecto a editar
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Obtener el proyecto por su ID
        $proyecto = Proyecto::findOrFail($id);
        
        // Obtener usuarios que pueden ser responsables
        $responsables = Usuario::where('id_usuario',  $proyecto->id_responsable)
                              ->orderBy('apellidos')
                              ->orderBy('nombre')
                              ->get();
        
        return view('proyectos.edit', compact('proyecto', 'responsables'));
    }

    /**
     * Actualizar un proyecto específico
     */
    public function update(Request $request, Proyecto $proyecto)
    {
        $validated = $request->validate([
            'codigo' => [
                'required',
                'string',
                'max:32',
                Rule::unique('proyecto')->ignore($proyecto->id_proyecto, 'id_proyecto')
            ],
            'titulo' => 'required|string|max:512',
            'nombre_corto' => [
                'required',
                'string',
                'max:128',
                Rule::unique('proyecto')->ignore($proyecto->id_proyecto, 'id_proyecto')
            ],
            'id_responsable' => 'required|exists:usuario,id_usuario',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'activo' => 'boolean',
            'web' => 'nullable|url|max:256',
            'financiacion' => 'nullable|string|max:16',
            'creditos_compensacion_proyecto' => 'nullable|numeric|min:0|max:100'
        ]);
        
        try {
            $proyecto->update($validated);
            
            return redirect()->route('proyectos.show', $proyecto)
                             ->with('success', 'Proyecto actualizado correctamente.');
                             
        } catch (\Exception $e) {
            return back()->withInput()
                         ->with('error', 'Error al actualizar el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar un proyecto específico
     */
    public function destroy(Proyecto $proyecto)
    {
        try {
            $proyecto->delete();
            
            return redirect()->route('proyectos.index')
                            ->with('success', 'Proyecto eliminado correctamente.');
                            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Buscar proyectos (para uso en AJAX/Select2)
     */
    public function buscar(Request $request)
    {
        $term = $request->input('q', '');
        
        $query = Proyecto::query();
        
        if (!empty($term)) {
            $query->where(function($q) use ($term) {
                $q->where('titulo', 'like', '%' . $term . '%')
                  ->orWhere('nombre_corto', 'like', '%' . $term . '%')
                  ->orWhere('codigo', 'like', '%' . $term . '%');
            });
        }
        
        // Por defecto, mostrar solo proyectos activos
        if (!$request->has('mostrar_inactivos')) {
            $query->where('activo', 1);
        }
        
        $proyectos = $query->with('responsable')
                          ->orderBy('fecha_inicio', 'desc')
                          ->limit(15)
                          ->get()
                          ->map(function($proyecto) {
                              return [
                                  'id' => $proyecto->id_proyecto,
                                  'text' => $proyecto->nombre_corto . ' - ' . $proyecto->codigo,
                                  'responsable' => $proyecto->responsable ? $proyecto->responsable->nombre . ' ' . $proyecto->responsable->apellidos : 'Sin responsable'
                              ];
                          });
        
        return response()->json($proyectos);
    }

    /**
     * Cambiar el estado (activo/inactivo) de un proyecto
     */
    public function cambiarEstado(Proyecto $proyecto)
    {
        try {
            $proyecto->update([
                'activo' => !$proyecto->activo
            ]);
            
            $estado = $proyecto->activo ? 'activado' : 'desactivado';
            
            return redirect()->back()
                            ->with('success', "El proyecto ha sido {$estado} correctamente.");
                            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cambiar el estado del proyecto: ' . $e->getMessage());
        }
    }

    /**
 * Mostrar y gestionar los miembros de un proyecto
 */
// public function miembros(Proyecto $proyecto)
// {
//     // Cargar los usuarios relacionados con este proyecto
//     $miembros = $proyecto->usuarios()
//                         ->orderBy('apellidos')
//                         ->orderBy('nombre')
//                         ->get();
    
//     // Obtener usuarios que podrían añadirse al proyecto (que no son ya miembros)
//     $usuariosDisponibles = Usuario::where('activo', 1)
//                                 ->whereNotIn('id_usuario', $miembros->pluck('id_usuario'))
//                                 ->orderBy('apellidos')
//                                 ->orderBy('nombre')
//                                 ->get();
    
//     return view('proyectos.miembros', compact('proyecto', 'miembros', 'usuariosDisponibles'));
// }

/**
 * Asigna compensación al responsable del proyecto
 */
public function asignarCompensacion(Request $request, Proyecto $proyecto)
{
    // Verificar permisos y plazo
    if (!Auth::user()->hasAnyRole(['admin', 'secretario', 'subdirectorDocente'])) {
        return redirect()->back()->with('error', 'No tienes permisos para esta acción.');
    }
    
    if (!$this->dentroDePlazoCompensaciones()) {
        return redirect()->back()->with('error', 'No se pueden modificar compensaciones fuera del plazo establecido.');
    }
    
    // Solo compensar al responsable del proyecto
    if (!$proyecto->id_responsable) {
        return redirect()->back()->with('error', 'El proyecto no tiene responsable asignado.');
    }
    
    try {
        // Verificar si ya existe una compensación para el responsable
        $compensacion = $proyecto->compensaciones()
                                 ->where('id_usuario', $proyecto->id_responsable)
                                 ->first();
        
        if ($compensacion) {
            return redirect()->back()->with('info', 'El responsable ya tiene compensación asignada.');
        }
        
        // Crear compensación para el responsable con los créditos del proyecto
        $creditosProyecto = $proyecto->creditos_compensacion_proyecto ?? 0;
        
        if ($creditosProyecto <= 0) {
            return redirect()->back()->with('error', 'El proyecto no tiene créditos de compensación definidos.');
        }
        
        $proyecto->compensaciones()->create([
            'id_usuario' => $proyecto->id_responsable,
            'creditos_compensacion' => $creditosProyecto
        ]);
        
        return redirect()->back()
                         ->with('success', 'Compensación asignada al responsable correctamente.');
                         
    } catch (\Exception $e) {
        return redirect()->back()
                         ->with('error', 'Error al asignar la compensación: ' . $e->getMessage());
    }
}
   /**
 * Mostrar formulario para asignar compensaciones a un proyecto
 */
public function mostrarCompensaciones(Proyecto $proyecto)
{
    // Verificar permisos y plazo
    if (!Auth::user()->hasAnyRole(['admin', 'secretario', 'subdirectorDocente'])) {
        abort(403, 'No tienes permisos para acceder a esta funcionalidad.');
    }
    
    if (!$this->dentroDePlazoCompensaciones()) {
        return redirect()->route('proyectos.index')
                        ->with('error', 'No se pueden modificar compensaciones fuera del plazo establecido.');
    }
    
    // Obtener usuarios que ya tienen compensaciones en este proyecto
    $usuariosConCompensaciones = Usuario::whereHas('compensacionesProyecto', function($query) use ($proyecto) {
        $query->where('id_proyecto', $proyecto->id_proyecto);
    })->with(['compensacionesProyecto' => function($query) use ($proyecto) {
        $query->where('id_proyecto', $proyecto->id_proyecto);
    }])->orderBy('apellidos')->orderBy('nombre')->get();
    
    // Obtener todos los usuarios activos para el selector
    $usuariosDisponibles = Usuario::where('activo', 1)
                                ->orderBy('apellidos')
                                ->orderBy('nombre')
                                ->get();
    
    return view('proyectos.compensaciones', compact('proyecto', 'usuariosConCompensaciones', 'usuariosDisponibles'));
}

/**
 * Mostrar y gestionar los miembros de un proyecto
 */
public function miembros(Proyecto $proyecto)
{
    // Si no existe la tabla usuario_proyecto, redirigir o mostrar mensaje
    return redirect()->route('proyectos.index')
                    ->with('error', 'La funcionalidad de miembros de proyecto no está disponible.');
}

}