<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProyectoController extends Controller
{

    /**
     * Mostrar listado de proyectos con filtros básicos
     */
    public function index(Request $request)
    {
        $query = Proyecto::query();
        
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
        
        $proyectos = $query->with('responsable')
                          ->orderBy($orderBy, $orderDirection)
                          ->paginate(10)
                          ->withQueryString();
        
        // Obtener tipos de financiación para el filtro
        $tiposFinanciacion = DB::table('proyecto')
                               ->select('financiacion')
                               ->whereNotNull('financiacion')
                               ->distinct()
                               ->pluck('financiacion');
        
        return view('proyectos.index', compact('proyectos', 'tiposFinanciacion'));
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
public function miembros(Proyecto $proyecto)
{
    // Cargar los usuarios relacionados con este proyecto
    $miembros = $proyecto->usuarios()
                        ->orderBy('apellidos')
                        ->orderBy('nombre')
                        ->get();
    
    // Obtener usuarios que podrían añadirse al proyecto (que no son ya miembros)
    $usuariosDisponibles = Usuario::where('activo', 1)
                                ->whereNotIn('id_usuario', $miembros->pluck('id_usuario'))
                                ->orderBy('apellidos')
                                ->orderBy('nombre')
                                ->get();
    
    return view('proyectos.miembros', compact('proyecto', 'miembros', 'usuariosDisponibles'));
}
}