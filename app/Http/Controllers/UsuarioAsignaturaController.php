<?php

namespace App\Http\Controllers;

use App\Models\UsuarioAsignatura;
use App\Models\Usuario;
use App\Models\Asignatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioAsignaturaController extends Controller
{
    public function index()
    {
        $asignaciones = UsuarioAsignatura::with(['usuario', 'asignatura'])->get();
        return view('usuario_asignatura.index', compact('asignaciones'));
    }

    public function create()
    {
        $usuarios = Usuario::all();
        $asignaturas = Asignatura::all();
        return view('usuario_asignatura.create', compact('usuarios', 'asignaturas'));
    }

    public function store(Request $request)
    {
        UsuarioAsignatura::create($request->all());

        return redirect()->route('usuario_asignatura.index')->with('success', 'Asignación creada correctamente.');
    }

    public function edit($id_asignatura, $id_usuario, $tipo, $grupo)
{
    // Obtener la asignación específica
    $asignacion = UsuarioAsignatura::where('id_asignatura', $id_asignatura)
                                  ->where('id_usuario', $id_usuario)
                                  ->where('tipo', $tipo)
                                  ->where('grupo', $grupo)
                                  ->firstOrFail();
    
    $usuarios = Usuario::all();
    $asignaturas = Asignatura::all();

    return view('usuario_asignatura.edit', compact('asignacion', 'usuarios', 'asignaturas'));
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
                     
    return redirect()->route('usuario_asignatura.index')->with('success', 'Asignación eliminada correctamente.');
}


}
