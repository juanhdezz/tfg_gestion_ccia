<?php
namespace App\Http\Controllers;

use App\Models\Asignatura;
use Illuminate\Http\Request;
use App\Models\Titulacion;
use App\Models\GrupoTeoriaPractica;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\Usuario;

class AsignaturaController extends Controller
{    public function index(Request $request)
    {
        $search = $request->input('search');
        $asignaturas = Asignatura::with(['titulacion', 'coordinador'])
            ->when($search, function ($query, $search) {
                return $query->where('nombre_asignatura', 'LIKE', "%{$search}%");
            })
            ->where('estado', 'Activa')
            ->join('titulacion', 'asignatura.id_titulacion', '=', 'titulacion.id_titulacion')
            ->orderByRaw("CASE WHEN titulacion.nombre_titulacion LIKE 'Máster%' THEN 1 ELSE 0 END")
            ->orderBy('titulacion.nombre_titulacion')
            ->orderBy('asignatura.nombre_asignatura')
            ->select('asignatura.*', 'titulacion.nombre_titulacion')
            ->get();

        $asignaturasExtintas = Asignatura::with(['titulacion', 'coordinador'])
            ->when($search, function ($query, $search) {
                return $query->where('nombre_asignatura', 'LIKE', "%{$search}%");
            })
            ->where('estado',"!=" ,'Activa')
            ->join('titulacion', 'asignatura.id_titulacion', '=', 'titulacion.id_titulacion')
            ->orderByRaw("CASE WHEN titulacion.nombre_titulacion LIKE 'Máster%' THEN 1 ELSE 0 END")
            ->orderBy('titulacion.nombre_titulacion')
            ->orderBy('asignatura.nombre_asignatura')
            ->select('asignatura.*', 'titulacion.nombre_titulacion')
            ->get();

        return view('asignaturas.index', compact('asignaturas', 'asignaturasExtintas'));
    }
      public function show($id)
    {
        // Cargar la asignatura con la titulación y coordinador asociados
        $asignatura = Asignatura::with(['titulacion', 'coordinador'])->find($id);
        if (is_null($asignatura)) {
            return redirect()->route('asignaturas.index')->with('error', 'Asignatura no encontrada');
        }

        // Obtener la distribución de grupos de práctica por grupo de teoría
        $distribucionGrupos = GrupoTeoriaPractica::where('id_asignatura', $id)
            ->select('grupo_teoria', DB::raw('count(*) as total_practicas'))
            ->groupBy('grupo_teoria')
            ->orderBy('grupo_teoria')
            ->get();

        // Devolvemos la vista con los datos
        return view('asignaturas.show', compact('asignatura', 'distribucionGrupos'));
    }    public function create()
    {
        $titulaciones = Titulacion::orderByRaw("CASE WHEN nombre_titulacion LIKE 'Máster%' THEN 1 ELSE 0 END")
            ->orderBy('nombre_titulacion')
            ->get();
        $usuarios = Usuario::orderBy('apellidos', 'asc')->orderBy('nombre', 'asc')->get();
        return view('asignaturas.create', compact('titulaciones', 'usuarios'));
    }

    public function store(Request $request)
{
    try {        // Validación de datos con mensajes personalizados
        $validated = $request->validate([
            'id_asignatura' => 'required|string|max:8|unique:asignatura',
            'id_titulacion' => 'required|exists:titulacion,id_titulacion',
            'id_coordinador' => 'nullable|exists:usuario,id_usuario',
            'nombre_asignatura' => 'required|string|max:128',
            'siglas_asignatura' => 'required|string|max:8',
            'grupos_teoria' => 'required|integer|min:1',
            'grupos_practicas' => 'required|integer|min:0',
            'curso' => 'nullable|integer',
            'cuatrimestre' => 'nullable|in:Primero,Segundo,Anual',
            'creditos_teoria' => 'nullable|numeric|min:0',
            'creditos_practicas' => 'nullable|numeric|min:0',
            'ects_teoria' => 'nullable|numeric|min:0',
            'ects_practicas' => 'nullable|numeric|min:0',
        ], [
            'id_titulacion.exists' => 'La titulación seleccionada no existe en la base de datos',
            'id_coordinador.exists' => 'El coordinador seleccionado no existe en la base de datos',
            'id_asignatura.unique' => 'El ID de asignatura ya está en uso',
            'grupos_teoria.min' => 'Debe especificar al menos un grupo de teoría'
        ]);

        // Crear la asignatura con datos validados
        $asignatura = new Asignatura();
        $asignatura->id_asignatura = $request->id_asignatura;
        $asignatura->id_titulacion = $request->id_titulacion;
        $asignatura->nombre_asignatura = $request->nombre_asignatura;
        $asignatura->siglas_asignatura = $request->siglas_asignatura;
        $asignatura->especialidad = $request->especialidad;
        $asignatura->id_coordinador = $request->id_coordinador;
        $asignatura->curso = $request->curso;
        $asignatura->cuatrimestre = $request->cuatrimestre;
        $asignatura->creditos_teoria = $request->creditos_teoria;
        $asignatura->creditos_practicas = $request->creditos_practicas;
        $asignatura->ects_teoria = $request->ects_teoria;
        $asignatura->ects_practicas = $request->ects_practicas;
        $asignatura->grupos_teoria = $request->grupos_teoria;
        $asignatura->grupos_practicas = $request->grupos_practicas;
        $asignatura->web_asignatura = $request->web_asignatura;
        $asignatura->tipo = $request->tipo ?: 'Asignatura';
        $asignatura->fraccionable = $request->has('fraccionable') ? 1 : 0;
        $asignatura->estado = $request->estado ?: 'Activa';
        
        // Guardar asignatura
        $asignatura->save();

        // Distribuir automáticamente los grupos de práctica entre los grupos de teoría
        $this->distribuirGruposPractica($asignatura);

        // Flash message y redirección
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Asignatura creada',
            'text' => 'La asignatura ha sido creada exitosamente',
        ]);

        return redirect()->route('asignaturas.index')
                         ->with('success', 'Asignatura creada correctamente');
                         
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Captura errores de validación y los devuelve al formulario
        return redirect()->back()->withErrors($e->validator)->withInput();
        
    } catch (\Illuminate\Database\QueryException $e) {
        // Errores de base de datos (claves foráneas, restricciones, etc.)
        $errorCode = $e->errorInfo[1] ?? 0;
        $errorInfo = $e->getMessage();
        
        // Error específico para foreign key
        if ($errorCode == 1452) { // Error de clave foránea de MySQL
            return redirect()->back()
                ->with('error', 'Error de relación: Asegúrate de que la titulación exista en la base de datos.')
                ->withInput();
        }
        
        // Error para clave duplicada
        if ($errorCode == 1062) { // Error de duplicidad
            return redirect()->back()
                ->with('error', 'Ya existe una asignatura con ese ID.')
                ->withInput();
        }
        
        // Cualquier otro error de base de datos
        return redirect()->back()
            ->with('error', "Error de base de datos: $errorInfo")
            ->withInput();
            
    } catch (\Exception $e) {
        // Cualquier otra excepción
        return redirect()->back()
            ->with('error', 'Error inesperado: ' . $e->getMessage())
            ->withInput();
    }
}    public function edit($id)
    {
        $titulaciones = Titulacion::orderByRaw("CASE WHEN nombre_titulacion LIKE 'Máster%' THEN 1 ELSE 0 END")
            ->orderBy('nombre_titulacion')
            ->get();
        $usuarios = Usuario::orderBy('apellidos', 'asc')->orderBy('nombre', 'asc')->get();
        $asignatura = Asignatura::with('coordinador')->find($id);
        
        if (is_null($asignatura)) {
            return redirect()->route('asignaturas.index')->with('error', 'Asignatura no encontrada');
        }

        // Obtener la distribución actual de grupos de práctica por grupo de teoría
        $distribucionGrupos = GrupoTeoriaPractica::where('id_asignatura', $id)
            ->select('grupo_teoria', DB::raw('GROUP_CONCAT(grupo_practica ORDER BY grupo_practica) as practicas'))
            ->groupBy('grupo_teoria')
            ->orderBy('grupo_teoria')
            ->get()
            ->keyBy('grupo_teoria');        return view('asignaturas.edit', compact('asignatura', 'titulaciones', 'distribucionGrupos', 'usuarios'));
    }

    public function update(Request $request, $id)
    {
        $asignatura = Asignatura::find($id);
        if (is_null($asignatura)) {
            return redirect()->route('asignaturas.index')->with('error', 'Asignatura no encontrada');
        }        // Validamos los datos
        $validated = $request->validate([
            'id_titulacion' => 'required|exists:titulacion,id_titulacion',
            'id_coordinador' => 'nullable|exists:usuario,id_usuario',
            'nombre_asignatura' => 'required|string|max:128',
            'siglas_asignatura' => 'required|string|max:8',
            'grupos_teoria' => 'required|integer|min:1',
            'grupos_practicas' => 'required|integer|min:0',
            // Otros campos según sea necesario
        ]);

        // Guardamos grupos actuales para comparar después
        $gruposTeoriaAnterior = $asignatura->grupos_teoria;
        $gruposPracticaAnterior = $asignatura->grupos_practicas;

        // Actualizamos los datos de la asignatura
        $asignatura->update($request->all());

        // Si cambiaron los grupos, necesitamos reorganizar
        if ($gruposTeoriaAnterior != $asignatura->grupos_teoria || 
            $gruposPracticaAnterior != $asignatura->grupos_practicas) {
            
            // Ajustar los grupos según los nuevos valores
            $this->ajustarGruposAsignatura($asignatura, $gruposTeoriaAnterior, $gruposPracticaAnterior);
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Asignatura actualizada',
            'text' => 'La asignatura ha sido actualizada correctamente',
        ]);

        return redirect()->route('asignaturas.index')->with('success', 'Asignatura actualizada exitosamente');
    }    public function destroy($id)
    {
        $asignatura = Asignatura::find($id);
        if (is_null($asignatura)) {
            return redirect()->route('asignaturas.index')->with('error', 'Asignatura no encontrada');
        }

        // Eliminar primero los registros de grupo_teoria_practica
        GrupoTeoriaPractica::where('id_asignatura', $id)->delete();
        
        // Ahora eliminar la asignatura
        $asignatura->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Asignatura eliminada',
            'text' => 'La asignatura ha sido eliminada exitosamente' 
        ]);

        return redirect()->route('asignaturas.index')->with('success', 'Asignatura eliminada exitosamente');
    }    public function grupos(Request $request)
    {
        $search = $request->input('search');        $asignaturas = Asignatura::with(['titulacion', 'coordinador'])
            ->when($search, function ($query, $search) {
                return $query->where('nombre_asignatura', 'LIKE', "%{$search}%");
            })
            ->where('estado', 'Activa')
            ->join('titulacion', 'asignatura.id_titulacion', '=', 'titulacion.id_titulacion')
            ->orderByRaw("CASE WHEN titulacion.nombre_titulacion LIKE 'Máster%' THEN 1 ELSE 0 END")
            ->orderBy('asignatura.nombre_asignatura')
            ->select('asignatura.*', 'titulacion.nombre_titulacion')
            ->get();

        // Para cada asignatura, obtener la distribución de grupos
        foreach ($asignaturas as $asignatura) {
            $asignatura->distribucion_grupos = GrupoTeoriaPractica::where('id_asignatura', $asignatura->id_asignatura)
                ->select('grupo_teoria', DB::raw('count(*) as total_practicas'))
                ->groupBy('grupo_teoria')
                ->orderBy('grupo_teoria')
                ->get();
        }

        return view('asignaturas.grupos', compact('asignaturas'));
    }

public function reasignarGrupos(Request $request, $id)
{
    $asignatura = Asignatura::findOrFail($id);
    
    // Validar que los grupos vengan en el formato esperado
    $validated = $request->validate([
        'grupos' => 'required|array',
        'grupos.*' => 'array'
    ]);

    try {
        // Eliminar todas las asignaciones actuales para esta asignatura
        GrupoTeoriaPractica::where('id_asignatura', $id)->delete();

        // Total de grupos de práctica para verificar
        $totalGruposPractica = 0;

        // Crear las nuevas asignaciones según los datos recibidos
        foreach ($request->grupos as $grupoTeoria => $gruposPractica) {
            foreach ($gruposPractica as $numPractica) {
                GrupoTeoriaPractica::create([
                    'id_asignatura' => $id,
                    'grupo_teoria' => $grupoTeoria,
                    'grupo_practica' => $numPractica
                ]);
                
                $totalGruposPractica++;
            }
        }

        // Verificar que la cantidad de grupos coincida con lo definido en la asignatura
        if ($totalGruposPractica != $asignatura->grupos_practicas) {
            // Loguear advertencia pero permitir que continúe (opcional)
            Log::warning("Se asignaron {$totalGruposPractica} grupos de práctica pero la asignatura tiene definidos {$asignatura->grupos_practicas}");
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Distribución actualizada',
            'text' => 'La distribución de grupos ha sido actualizada correctamente'
        ]);

        return redirect()->route('asignaturas.grupos')
            ->with('success', 'Distribución de grupos actualizada correctamente');

    } catch (\Exception $e) {
        Log::error("Error al reasignar grupos: " . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Error al actualizar la distribución de grupos: ' . $e->getMessage());
    }
}

    /**
     * Métodos de utilidad para gestionar grupos
     */

    /**
     * Distribuye automáticamente los grupos de práctica entre los grupos de teoría
     * al crear una nueva asignatura.
     */
    private function distribuirGruposPractica(Asignatura $asignatura)
    {
        $gruposTeoria = $asignatura->grupos_teoria;
        $gruposPractica = $asignatura->grupos_practicas;
        
        if ($gruposTeoria <= 0 || $gruposPractica <= 0) {
            return;
        }

        // Calcular distribución base y cuántos grupos tienen una práctica adicional
        $practicasPorGrupo = intdiv($gruposPractica, $gruposTeoria);
        $gruposConPracticaExtra = $gruposPractica % $gruposTeoria;

        // Crear las asignaciones en la tabla grupo_teoria_practica
        for ($i = 1; $i <= $gruposTeoria; $i++) {
            $practicasParaEsteGrupo = $practicasPorGrupo;
            
            // Si este grupo debe tener una práctica adicional
            if ($i <= $gruposConPracticaExtra) {
                $practicasParaEsteGrupo++;
            }

            // Crear las asignaciones para este grupo de teoría
            for ($j = 1; $j <= $practicasParaEsteGrupo; $j++) {
                GrupoTeoriaPractica::create([
                    'id_asignatura' => $asignatura->id_asignatura,
                    'grupo_teoria' => $i,
                    'grupo_practica' => $j
                ]);
            }
        }
    }

    /**
 * Cambia el estado de fraccionable de una asignatura.
 * 
 * @param  \Illuminate\Http\Request  $request
 * @param  string  $id ID de la asignatura
 * @return \Illuminate\Http\Response
 */
public function toggleFraccionable(Request $request, $id)
{
    $asignatura = Asignatura::findOrFail($id);
    
    // Cambiar el estado de fraccionable
    $asignatura->fraccionable = !$asignatura->fraccionable;
    $asignatura->save();
    
    return redirect()->back()
        ->with('success', 'El estado de fraccionable ha sido actualizado correctamente');
}

    /**
     * Ajusta los grupos cuando se actualiza una asignatura y cambian
     * el número de grupos de teoría o práctica.
     */
    private function ajustarGruposAsignatura(Asignatura $asignatura, $gruposTeoriaAnterior, $gruposPracticaAnterior)
    {
        // Eliminar todas las asignaciones actuales
        GrupoTeoriaPractica::where('id_asignatura', $asignatura->id_asignatura)->delete();
        
        // Redistribuir los grupos desde cero
        $this->distribuirGruposPractica($asignatura);
    }

    /**
 * Establece una equivalencia entre dos asignaturas
 * Modificado para soportar IDs alfanuméricos correctamente
 */
public function establecerEquivalencia(Request $request)
{
    try {
        // Validar datos
        $validated = $request->validate([
            'asignatura_id' => 'required|exists:asignatura,id_asignatura',
            'equivalente_id' => 'required|exists:asignatura,id_asignatura|different:asignatura_id',
        ]);
        
        
        
        $asignatura = Asignatura::where('id_asignatura', $request->asignatura_id)->first();
        if (!$asignatura) {
            return redirect()->back()->with('error', 'Asignatura no encontrada');
        }
        
        $equivalente = Asignatura::where('id_asignatura', $request->equivalente_id)->first();
        if (!$equivalente) {
            return redirect()->back()->with('error', 'Asignatura equivalente no encontrada');
        }
        
        // Verificar si la equivalencia ya existe
        $existeEquivalencia = DB::table('asignaturas_equivalentes')
            ->where('asignatura_id', $asignatura->id_asignatura)
            ->where('equivalente_id', $equivalente->id_asignatura)
            ->exists();
        
        if (!$existeEquivalencia) {
            // Insertar equivalencia directamente en la tabla pivot para evitar problemas de conversión de tipos
            DB::table('asignaturas_equivalentes')->insert([
                'asignatura_id' => $asignatura->id_asignatura,
                'equivalente_id' => $equivalente->id_asignatura
            ]);
            
            // Añadir también la relación inversa para facilitar consultas
            $existeInversa = DB::table('asignaturas_equivalentes')
                ->where('asignatura_id', $equivalente->id_asignatura)
                ->where('equivalente_id', $asignatura->id_asignatura)
                ->exists();
                
            if (!$existeInversa) {
                DB::table('asignaturas_equivalentes')->insert([
                    'asignatura_id' => $equivalente->id_asignatura,
                    'equivalente_id' => $asignatura->id_asignatura
                ]);
            }
            
            return redirect()->back()->with('success', 'Equivalencia establecida correctamente entre ' . 
                $asignatura->siglas_asignatura . ' y ' . $equivalente->siglas_asignatura);
        }
        
        return redirect()->back()->with('info', 'La equivalencia ya existía previamente');
    } 
    catch (\Exception $e) {
        
        
        return redirect()->back()->with('error', 'Error al establecer la equivalencia: ' . $e->getMessage());
    }
}
    
    /**
 * Elimina una equivalencia entre dos asignaturas
 * Modificado para soportar IDs alfanuméricos correctamente
 */
public function eliminarEquivalencia(Request $request)
{
    try {
        $request->validate([
            'asignatura_id' => 'required|exists:asignatura,id_asignatura',
            'equivalente_id' => 'required|exists:asignatura,id_asignatura',
        ]);
        
        // Eliminar directamente de la tabla pivot para evitar problemas de conversión
        DB::table('asignaturas_equivalentes')
            ->where('asignatura_id', $request->asignatura_id)
            ->where('equivalente_id', $request->equivalente_id)
            ->delete();
        
        // Eliminar también la relación inversa
        DB::table('asignaturas_equivalentes')
            ->where('asignatura_id', $request->equivalente_id)
            ->where('equivalente_id', $request->asignatura_id)
            ->delete();
            
        return redirect()->back()->with('success', 'Equivalencia eliminada correctamente');
    } 
    catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error al eliminar la equivalencia: ' . $e->getMessage());
    }
}
    public function mostrarFormularioEquivalencias($id)
    {
        $asignatura = Asignatura::findOrFail($id);
        $asignaturas = Asignatura::where('id_asignatura', '!=', $id)
                        ->orderBy('nombre_asignatura')
                        ->get();
        $equivalenciasActuales = $asignatura->todasLasEquivalencias();
        
        return view('asignaturas.equivalencias', compact('asignatura', 'asignaturas', 'equivalenciasActuales'));
    }    public function listarEquivalencias()
    {
        // Consulta para obtener todas las equivalencias únicas
        $equivalencias = DB::table('asignaturas_equivalentes')
            ->join('asignatura as a1', 'asignatura_id', '=', 'a1.id_asignatura')
            ->join('asignatura as a2', 'equivalente_id', '=', 'a2.id_asignatura')
            ->join('titulacion as t1', 'a1.id_titulacion', '=', 't1.id_titulacion')
            ->select('a1.id_asignatura', 'a1.nombre_asignatura as asignatura1', 
                    'a2.id_asignatura as id_equivalente', 'a2.nombre_asignatura as asignatura2',
                    't1.nombre_titulacion')
            ->orderByRaw("CASE WHEN t1.nombre_titulacion LIKE 'Máster%' THEN 1 ELSE 0 END")
            ->orderBy('a1.nombre_asignatura')
            ->get();
        
        return view('asignaturas.lista-equivalencias', compact('equivalencias'));
    }

    /**
 * Inicializa la tabla grupo_teoria_practica con los datos existentes en asignatura.
 * Este método debe ejecutarse una sola vez después de crear la tabla.
 */
public function inicializarGruposTeoriaPractica()
{
    // Obtener todas las asignaturas activas con un enfoque más directo
    $asignaturas = DB::table('asignatura')
                    ->where('estado', '!=', 'Extinta')
                    ->get();
                    
    $totalAsignaturas = $asignaturas->count();
    $asignaturasActualizadas = 0;
    $errores = [];
    $asignaturasConError = [];

    // Iniciar transacción
    DB::beginTransaction();
    try {
        foreach ($asignaturas as $asignatura) {
            $idAsignatura = $asignatura->id_asignatura;
            
            // Verificar si ya tiene entradas en grupo_teoria_practica - usando consulta directa
            $existenGrupos = DB::table('grupo_teoria_practica')
                                ->where('id_asignatura', $idAsignatura)
                                ->exists();
            
            // Si ya tiene grupos asignados, no hacer nada con esta asignatura
            if ($existenGrupos) {
                continue;
            }
            
            $gruposTeoria = (int)$asignatura->grupos_teoria;
            $gruposPractica = (int)$asignatura->grupos_practicas;
            
            // Saltar asignaturas sin grupos de teoría o práctica
            if ($gruposTeoria <= 0 || $gruposPractica <= 0) {
                continue;
            }
            
            try {
                // Calcular distribución base y cuántos grupos tienen una práctica adicional
                $practicasPorGrupo = intdiv($gruposPractica, $gruposTeoria);
                $gruposConPracticaExtra = $gruposPractica % $gruposTeoria;
                
                // Crear las asignaciones en la tabla grupo_teoria_practica
                for ($i = 1; $i <= $gruposTeoria; $i++) {
                    $practicasParaEsteGrupo = $practicasPorGrupo;
                    
                    // Si este grupo debe tener una práctica adicional
                    if ($i <= $gruposConPracticaExtra) {
                        $practicasParaEsteGrupo++;
                    }
                    
                    // Crear las asignaciones para este grupo de teoría
                    for ($j = 1; $j <= $practicasParaEsteGrupo; $j++) {
                        try {
                            // Insertar directamente sin el modelo para evitar problemas de tipo
                            DB::table('grupo_teoria_practica')->insert([
                                'id_asignatura' => $idAsignatura,
                                'grupo_teoria' => $i,
                                'grupo_practica' => $j
                            ]);
                        } catch (\Exception $e) {
                            $errores[] = "Error al insertar grupo para asignatura '{$idAsignatura}': " . $e->getMessage();
                            throw $e; // Relanzar para que se maneje en el bloque catch principal
                        }
                    }
                }
                
                $asignaturasActualizadas++;
            } catch (\Exception $e) {
                $errores[] = "Error procesando la asignatura '{$idAsignatura}': " . $e->getMessage();
                $asignaturasConError[] = $idAsignatura;
            }
        }
        
        if (count($errores) > 0) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Migración fallida. Se encontraron errores.",
                'asignaturas_con_error' => $asignaturasConError,
                'errores' => $errores
            ], 500);
        }
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => "Migración completada. Se han actualizado $asignaturasActualizadas de $totalAsignaturas asignaturas.",
            'errores' => $errores
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        return response()->json([
            'success' => false,
            'message' => 'Error durante la migración: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
}

/**
 * Verifica que las asignaturas en la tabla principal existan y sean válidas para migración.
 * Útil para identificar problemas antes de intentar la migración real.
 */
public function verificarAsignaturasMigracion()
{
    // Obtenemos las asignaturas directamente de la base de datos
    $asignaturas = DB::table('asignatura')->get();
    $totalAsignaturas = $asignaturas->count();
    $asignaturasValidas = 0;
    $problemas = [];
    
    foreach ($asignaturas as $asignatura) {
        $id = $asignatura->id_asignatura;
        $problemaAsignatura = false;
        
        // Debug adicional para ver realmente qué tipo de dato y valor tenemos
        $tipo = gettype($id);
        $representacionHex = bin2hex($id);
        
        // Verificar si tiene grupos de teoría y práctica válidos
        if ((int)$asignatura->grupos_teoria <= 0) {
            $problemas[] = [
                'id' => $id,
                'error' => 'No tiene grupos de teoría definidos',
                'grupos_teoria' => $asignatura->grupos_teoria,
                'tipo_dato' => $tipo,
                'hex' => $representacionHex
            ];
            $problemaAsignatura = true;
        }
        
        if ((int)$asignatura->grupos_practicas <= 0) {
            $problemas[] = [
                'id' => $id,
                'error' => 'No tiene grupos de práctica definidos',
                'grupos_practicas' => $asignatura->grupos_practicas,
                'tipo_dato' => $tipo,
                'hex' => $representacionHex
            ];
            $problemaAsignatura = true;
        }
        
        // Verificar si ya tiene entradas en grupo_teoria_practica
        $existenGrupos = DB::table('grupo_teoria_practica')
                            ->where('id_asignatura', $id)
                            ->exists();
                            
        if ($existenGrupos) {
            $problemas[] = [
                'id' => $id,
                'error' => 'Ya tiene grupos asignados en grupo_teoria_practica',
                'tipo_dato' => $tipo,
                'hex' => $representacionHex
            ];
            $problemaAsignatura = true;
        }
        
        if (!$problemaAsignatura) {
            $asignaturasValidas++;
        }
    }
    
    return response()->json([
        'total_asignaturas' => $totalAsignaturas,
        'asignaturas_validas' => $asignaturasValidas,
        'problemas' => $problemas,
        'porcentaje_validas' => $totalAsignaturas > 0 ? ($asignaturasValidas / $totalAsignaturas) * 100 : 0
    ]);
}
}