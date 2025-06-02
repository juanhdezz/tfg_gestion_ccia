<?php

namespace App\Http\Controllers;

use App\Models\UsuarioAsignatura;
use App\Models\Usuario;
use App\Models\Asignatura;
use App\Models\GrupoTeoriaPractica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Titulacion;

class UsuarioAsignaturaController extends Controller
{
    public function index(Request $request)
{
    // Obtener el término de búsqueda desde la solicitud
    $search = $request->input('search');

    // Obtener todas las asignaciones con sus relaciones
    $asignaciones = UsuarioAsignatura::with(['usuario', 'asignatura.titulacion'])->get();
      // Obtener todas las titulaciones con sus asignaturas y grupos ordenadas correctamente
    $titulaciones = Titulacion::with(['asignaturas' => function($query) use ($search) {
        $query->where('estado', '!=', 'Extinta')
              ->when($search, function($query) use ($search) {
                  // Búsqueda en nombre de asignatura o código
                  return $query->where('nombre_asignatura', 'LIKE', "%{$search}%")
                              ->orWhere('id_asignatura', 'LIKE', "%{$search}%");
              })
              ->join('titulacion', 'asignatura.id_titulacion', '=', 'titulacion.id_titulacion')
              ->orderByRaw("CASE WHEN titulacion.nombre_titulacion LIKE 'Master%' THEN 1 ELSE 0 END")
              ->orderBy('asignatura.nombre_asignatura')
              ->select('asignatura.*');
    }, 'asignaturas.grupos'])
    ->when($search, function($query) use ($search) {
        // Filtrar titulaciones que tienen al menos una asignatura que coincide con la búsqueda
        return $query->whereHas('asignaturas', function($query) use ($search) {
            return $query->where('nombre_asignatura', 'LIKE', "%{$search}%")
                        ->orWhere('id_asignatura', 'LIKE', "%{$search}%");
        });
    })
    ->get();
    
    return view('usuario_asignatura.index', compact('titulaciones', 'asignaciones', 'search'));
}

public function create($id_asignatura = null, $tipo = null, $grupo = null)
{
    // Obtener usuarios ordenados por apellido
    $usuarios = Usuario::orderBy('apellidos')->orderBy('nombre')->get();
    
    // Obtener asignaturas con sus grupos ordenadas según el patrón: grados primero, luego másteres, alfabéticamente dentro de cada grupo
    $asignaturas = Asignatura::with('grupos')
                   ->where('estado', '!=', 'Extinta')
                   ->join('titulacion', 'asignatura.id_titulacion', '=', 'titulacion.id_titulacion')
                   ->orderByRaw("CASE WHEN titulacion.nombre_titulacion LIKE 'Master%' THEN 1 ELSE 0 END")
                   ->orderBy('asignatura.nombre_asignatura')
                   ->select('asignatura.*')
                   ->get();
    
    // Si se proporcionaron parámetros, preparar preselección
    $preseleccion = null;
    if ($id_asignatura) {
        $preseleccion = [
            'id_asignatura' => $id_asignatura,
            'tipo' => $tipo,
            'grupo' => $grupo,
            // No incluimos id_usuario porque no lo tenemos aún
        ];
    }
    
    return view('usuario_asignatura.create', compact('usuarios', 'asignaturas', 'preseleccion'));
}

    public function store(Request $request)
    {
        UsuarioAsignatura::create($request->all());

        return redirect()->route('usuario_asignatura.index')->with('success', 'Asignación creada correctamente.');
    }

    public function edit($id_asignatura, $id_usuario, $tipo, $grupo)
{
    // Obtener la asignación específica con la relación de asignatura y sus grupos
    $asignacion = UsuarioAsignatura::with('asignatura.grupos')
                                  ->where('id_asignatura', $id_asignatura)
                                  ->where('id_usuario', $id_usuario)
                                  ->where('tipo', $tipo)
                                  ->where('grupo', $grupo)
                                  ->firstOrFail();
    
    // Cargar los usuarios y asignaturas para los selectores
    $usuarios = Usuario::all();
    $asignaturas = Asignatura::all();
    
    // Obtener los grupos únicos de esta asignatura para el selector
    $grupos = GrupoTeoriaPractica::where('id_asignatura', $id_asignatura)
                                ->get()
                                ->unique('grupo_teoria')
                                ->values();
    
    return view('usuario_asignatura.edit', compact('asignacion', 'usuarios', 'asignaturas', 'grupos'));
}

public function update(Request $request, $id_asignatura, $id_usuario, $tipo, $grupo)
{
    // Validación
    $request->validate([
        'id_usuario' => 'required|exists:usuario,id_usuario',
        'tipo' => 'required|in:Teoría,Prácticas',
        'grupo' => 'required|string',
        'creditos' => 'required|numeric|min:0',
        'antiguedad' => 'required|integer|min:0',
    ]);
    
    // Comenzamos una transacción
    DB::beginTransaction();
    
    try {
        // Obtener la asignación actual (sin usar firstOrFail que podría causar errores)
        $asignacion = DB::table('usuario_asignatura')
                        ->where('id_asignatura', $id_asignatura)
                        ->where('id_usuario', $id_usuario)
                        ->where('tipo', $tipo)
                        ->where('grupo', $grupo)
                        ->first();
        
        if (!$asignacion) {
            return redirect()->route('usuario_asignatura.index')
                            ->with('error', 'No se encontró la asignación especificada.');
        }
        
        // Si cambiaron datos clave, eliminamos la antigua y creamos una nueva
        if ($request->id_usuario != $id_usuario || 
            $request->tipo != $tipo || 
            $request->grupo != $grupo) {
            
            // Eliminar la asignación antigua
            DB::table('usuario_asignatura')
                ->where('id_asignatura', $id_asignatura)
                ->where('id_usuario', $id_usuario)
                ->where('tipo', $tipo)
                ->where('grupo', $grupo)
                ->delete();
            
            // Crear la nueva asignación
            DB::table('usuario_asignatura')->insert([
                'id_asignatura' => $id_asignatura,
                'id_usuario' => $request->id_usuario,
                'tipo' => $request->tipo,
                'grupo' => $request->grupo,
                'creditos' => $request->creditos,
                'antiguedad' => $request->antiguedad,
                'en_primera_fase' => $request->has('en_primera_fase') ? 1 : 0,
            ]);
        } else {
            // Solo actualizar datos no clave
            DB::table('usuario_asignatura')
                ->where('id_asignatura', $id_asignatura)
                ->where('id_usuario', $id_usuario)
                ->where('tipo', $tipo)
                ->where('grupo', $grupo)
                ->update([
                    'creditos' => $request->creditos,
                    'antiguedad' => $request->antiguedad,
                    'en_primera_fase' => $request->has('en_primera_fase') ? 1 : 0,
                ]);
        }
        
        DB::commit();
        return redirect()->route('usuario_asignatura.index')
                        ->with('success', 'Asignación actualizada correctamente');
    } catch (\Exception $e) {
        DB::rollBack();
        
        // Depuración
        //\Log::error('Error al actualizar asignación: ' . $e->getMessage());
        //\Log::error($e->getTraceAsString());
        
        return redirect()->route('usuario_asignatura.index')
                        ->with('error', 'Error al actualizar: ' . $e->getMessage());
    }
}

public function destroy($id_asignatura, $id_usuario, $tipo, $grupo)
{
    // Consulta explícita sin usar compact
    UsuarioAsignatura::where('id_asignatura', $id_asignatura)
                     ->where('id_usuario', $id_usuario)
                     ->where('tipo', $tipo)
                     ->where('grupo', $grupo)
                     ->delete();
    // Preparar mensaje para SweetAlert
    session()->flash('swal', [
        'icon' => 'success',
        'title' => 'Asignación eliminada',
        'text' => "Se ha eliminado la asignación del grupo {$grupo} de {$tipo}"
    ]);
                     
    return redirect()->route('usuario_asignatura.index')->with('success', 'Asignación eliminada correctamente.');
}


}
