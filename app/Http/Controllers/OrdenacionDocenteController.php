<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Usuario;
use App\Models\Plazo;
use App\Models\Asignatura;
use App\Models\Titulacion;
use App\Models\Ordenacion\OrdenacionUsuarioAsignatura;
use App\Models\Ordenacion\Turno;
use App\Models\Ordenacion\Perfil;
use App\Models\Ordenacion\PerfilTitulacion;
use App\Models\Ordenacion\DocenciaPosgrado;
use App\Models\Ordenacion\CompensacionCargo;
use App\Models\Ordenacion\CompensacionProyecto;
use App\Models\Ordenacion\CompensacionTesis;
use App\Models\Ordenacion\CompensacionSexenio;
use App\Models\Ordenacion\CompensacionOtros;
use App\Models\Ordenacion\CompensacionLimite;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OrdenacionDocenteController extends Controller
{
    // Constante que determina cuántos créditos por debajo se puede pasar turno
    //const CREDITOS_MENOS = 0.5;
    
    /**
     * Muestra la pantalla principal de asignaciones
     */
    public function index()
    {
        // Obtenemos la configuración de la base de datos
        $dbActual = $this->getDbActual();
        $dbSiguiente = $this->getDbSiguiente();
        
        try {
            // Obtenemos datos del turno actual
            $turno = Turno::first();
            
            $fase = $turno->fase;
            $numeroTurno = $turno->turno;
            $cursosConPreferencia = $turno->cursos_con_preferencia;
            $estado = $turno->estado;
            
            // Obtenemos información del año académico
            $fechaInicioCurso = Plazo::where('nombre_plazo', 'CURSO ACADEMICO ACTUAL')
                ->first()->fecha_inicio;
                
            $anio = Carbon::parse($fechaInicioCurso)->year;
            $cursoActual = "Curso " . $anio . "/" . ($anio + 1);
            $cursoSiguiente = "Curso " . ($anio + 1) . "/" . ($anio + 2);

            // Obtenemos las compensaciones del usuario
            $creditos_compensacion = $this->obtenerReducciones(Auth::id());
            
            // Comprobamos si estamos en el turno del usuario
            $esTurnoUsuario = $this->esTurnoDelUsuario(Auth::id(), $numeroTurno);
            
            // Obtenemos el perfil del usuario
            $perfil = Perfil::findOrFail(Auth::id());
            
            $data = [
                'fase' => $fase,
                'turno' => $numeroTurno,
                'cursos_con_preferencia' => $cursosConPreferencia,
                'estado' => $estado,
                'curso_actual' => $cursoActual,
                'curso_siguiente' => $cursoSiguiente,
                'creditos_compensacion' => $creditos_compensacion,
                'es_turno_usuario' => $esTurnoUsuario,
                'perfil' => $perfil,
                'titulaciones' => Titulacion::orderBy('nombre_titulacion')->get(),
            ];
            
             // Según la fase, renderizamos distintas vistas
    if ($fase == 1) {
        return $this->mostrarFase1($data);
    } elseif ($fase == 2) {
        return $this->mostrarFase2($data);
    } elseif ($fase == 3) {
        return $this->mostrarFase3($data);
    } else {
        // Fase no reconocida
        return view('error.error', [
            'titulo' => 'Fase no válida',
            'mensaje' => 'La fase actual de ordenación docente no es válida.',
            'detalles' => "Fase: $fase"
        ]);
    }
        } catch (\Exception $e) {
            Log::error('Error en OrdenacionDocenteController::index: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Devolver una respuesta de error amigable
            return view('error.error', [
                'titulo' => 'Error en el sistema de ordenación docente',
                'mensaje' => 'Se ha producido un error al cargar la página de ordenación docente. Por favor, contacte con el administrador.',
                'detalles' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    /**
     * Muestra la vista para la primera fase
     */
    protected function mostrarFase1($data)
    {
        try {
            // Obtener asignaciones actuales y previas
            $data['asignaciones_actuales'] = $this->obtenerAsignacionesActuales(Auth::id());
            $data['asignaciones_previas'] = $this->obtenerAsignacionesPrevias(Auth::id(), $data['cursos_con_preferencia']);
            
            return view('ordenacion.fase1', $data);
        } catch (\Exception $e) {
            Log::error('Error en mostrarFase1: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return view('error.error', [
                'titulo' => 'Error al mostrar la fase 1',
                'mensaje' => 'Se ha producido un error al cargar la fase 1 de ordenación docente.',
                'detalles' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    /**
     * Muestra la vista para la segunda fase
     */
    protected function mostrarFase2($data)
    {
        try {
            $data['asignaciones_actuales'] = $this->obtenerAsignacionesActuales(Auth::id());
            
            if ($data['es_turno_usuario']) {
                // Si es el turno del usuario, también necesitamos las asignaturas disponibles
                $data['asignaturas_disponibles'] = $this->obtenerAsignaturasDisponibles(Auth::id());
                
                return view('ordenacion.turno', $data);
            } else {
                return view('ordenacion.fase2', $data);
            }
        } catch (\Exception $e) {
            Log::error('Error en mostrarFase2: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return view('error.error', [
                'titulo' => 'Error al mostrar la fase 2',
                'mensaje' => 'Se ha producido un error al cargar la fase 2 de ordenación docente.',
                'detalles' => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

/**
 * Muestra la vista para la tercera fase (similar a fase 2 pero sin eliminar asignaciones)
 */
protected function mostrarFase3($data)
{
    try {
        $data['asignaciones_actuales'] = $this->obtenerAsignacionesActuales(Auth::id());
        
        if ($data['es_turno_usuario']) {
            // Si es el turno del usuario, también necesitamos las asignaturas disponibles
            // En la fase 3, no se eliminan las asignaciones de turnos superiores
            $data['asignaturas_disponibles'] = $this->obtenerAsignaturasDisponiblesFase3(Auth::id());
            
            return view('ordenacion.turno_fase3', $data);
        } else {
            return view('ordenacion.fase3', $data);
        }
    } catch (\Exception $e) {
        Log::error('Error en mostrarFase3: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        
        return view('error.error', [
            'titulo' => 'Error al mostrar la fase 3',
            'mensaje' => 'Se ha producido un error al cargar la fase 3 de ordenación docente.',
            'detalles' => config('app.debug') ? $e->getMessage() : null
        ]);
    }
}

/**
 * Obtiene las asignaturas disponibles para la fase 3, sin eliminar las asignaciones de turnos superiores
 */
protected function obtenerAsignaturasDisponiblesFase3($idUsuario)
{
    // Similar a obtenerAsignaturasDisponibles pero sin excluir asignaciones de turnos superiores
    // Código similar al método original pero adaptado para la fase 3
}

    /**
     * Obtiene las reducciones/compensaciones del usuario
     */
    protected function obtenerReducciones($idUsuario)
    {
        $dbSiguiente = $this->getDbSiguiente();
        
        // Obtener los límites de compensaciones
        $limites = CompensacionLimite::pluck('limite_creditos', 'concepto')
            ->toArray();
        
        // Obtener los créditos de docencia del usuario
        $usuario = Usuario::with('categoriaDocente')
            ->findOrFail($idUsuario);

            // Verificar si el usuario tiene categoría docente asignada
if (!$usuario->categoriaDocente) {
    Log::error("Usuario con ID {$idUsuario} no tiene categoría docente asignada");
    return 0; // O un valor predeterminado que tenga sentido en tu aplicación
}
            
        $creditosDocencia = $usuario->categoriaDocente->creditos_docencia;
        
       $porcentajesDocencia = $this->getPorcentajesDocencia();
$docencia25 = $creditosDocencia * $porcentajesDocencia['porcentaje_menor'];
$docencia50 = $creditosDocencia * $porcentajesDocencia['porcentaje_mayor'];
        
        $totalCompensacion = 0;
        
        // 1. Compensaciones por docencia en posgrado
        $creditosPosgrado = 0;
        $creditosDocenciaPosgrado = 0;
        $creditosDocenciaTFM = 0;
        
        // 1.1 Docencia en posgrado
        $docentePosgrado = DocenciaPosgrado::with('posgrado')
            ->where('id_usuario', $idUsuario)
            ->get();
            
        foreach ($docentePosgrado as $docencia) {
            $creditosDocenciaPosgrado += $docencia->creditos;
            
            // Sumamos separado los créditos de TFM
            if ($docencia->posgrado && $docencia->posgrado->codigo == $this->getConfiguracion('identificador_tfm')) {
                $creditosDocenciaTFM += $docencia->creditos;
            }
        }
        
        // 1.2 Docencia escogida en Máster Profesionalizantes
        $creditosDocenciaMaster = OrdenacionUsuarioAsignatura::whereHas('asignatura', function($query) {
                $query->whereIn('id_titulacion', ['99999', '1003', '1004']);
            })
            ->where('id_usuario', $idUsuario)
            ->sum('creditos');
            
        $creditosDocenciaPosgrado += $creditosDocenciaMaster;
        
        // 1.3 Aplicar la restricción: Docencia Posgrado sin TFM <= $docencia25 créditos
        if (($creditosDocenciaPosgrado - $creditosDocenciaTFM) > $docencia25) {
            $creditosPosgrado = $docencia25 + $creditosDocenciaTFM;
        } else {
            $creditosPosgrado = $creditosDocenciaPosgrado;
        }
        
        // 2. Compensaciones por cargo
        $maxCargo = 0;
        $representacionSindical = 0;
        
        // 2.1 Cargos de gestión universitaria (no acumulables, se toma el más alto)
        $compensacionesCargo = CompensacionCargo::with('cargo')
            ->where('id_usuario', $idUsuario)
            ->whereHas('cargo', function($query) {
                $query->where('tipo', 'Gestión Universitaria');
            })
            ->get();
            
        foreach ($compensacionesCargo as $cargo) {
            if ($cargo->creditos_compensacion > $maxCargo) {
                $maxCargo = $cargo->creditos_compensacion;
            }
        }
        
        $totalCompensacion += $maxCargo;
        
        // 2.2 Cargos de representación sindical (acumulables)
        $compensacionesRepresentacion = CompensacionCargo::with('cargo')
            ->where('id_usuario', $idUsuario)
            ->whereHas('cargo', function($query) {
                $query->where('tipo', '<>', 'Gestión Universitaria');
            })
            ->get();
            
        foreach ($compensacionesRepresentacion as $cargo) {
            $representacionSindical += $cargo->creditos_compensacion;
        }
        
        $totalCompensacion += $representacionSindical;
        
        // 3. Compensaciones por proyectos (con límite)
        $compensacionesProyecto = CompensacionProyecto::with('proyecto')
            ->where('id_usuario', $idUsuario)
            ->get();
            
        $creditosProyectos = 0;
        
        foreach ($compensacionesProyecto as $proyecto) {
            $creditosProyectos += $proyecto->creditos_compensacion;
        }
        
        // Aplicar el límite de créditos por proyectos
        if (isset($limites['Proyectos']) && $creditosProyectos > $limites['Proyectos']) {
            $creditosProyectos = $limites['Proyectos'];
        }
        
        $totalCompensacion += $creditosProyectos;
        
        // 4. Compensaciones por tesis (con límite)
        $compensacionesTesis = CompensacionTesis::with('tesis')
            ->where('id_usuario', $idUsuario)
            ->get();
            
        $creditosTesis = 0;
        
        foreach ($compensacionesTesis as $tesis) {
            $creditosTesis += $tesis->creditos_compensacion;
        }
        
        // Aplicar el límite de créditos por tesis
        if (isset($limites['Tesis']) && $creditosTesis > $limites['Tesis']) {
            $creditosTesis = $limites['Tesis'];
        }
        
        $totalCompensacion += $creditosTesis;
        
        // 5. Compensaciones por sexenios
        $creditosSexenios = 0;
        $compensacionSexenio = CompensacionSexenio::where('id_usuario', $idUsuario)->first();
        
        if ($compensacionSexenio) {
            $creditosSexenios = $compensacionSexenio->creditos_compensacion;
            $totalCompensacion += $creditosSexenios;
        }
        
        // 6. Compensaciones por otros conceptos (excepto Posgrado y Evaluación)
        $creditosOtros = 0;
        $compensacionesOtros = CompensacionOtros::with('concepto')
            ->where('id_usuario', $idUsuario)
            ->whereHas('concepto', function($query) {
                $query->where('tipo', '<>', 'Posgrado')
                      ->where('tipo', '<>', 'Evaluación');
            })
            ->get();
            
        foreach ($compensacionesOtros as $compensacion) {
            $creditosOtros += $compensacion->creditos_compensacion;
        }
        
        $totalCompensacion += $creditosOtros;
        
        // 7. Aplicar restricciones globales
        
        // 7.1 Créditos de investigación (tesis + proyectos + sexenios)
        $creditosInvestigacion = $creditosTesis + $creditosProyectos + $creditosSexenios;
        
        if (isset($limites['Investigación']) && $creditosInvestigacion > $limites['Investigación']) {
            $totalCompensacion = ($totalCompensacion - $creditosInvestigacion) + $limites['Investigación'];
            $creditosInvestigacion = $limites['Investigación'];
        }
        
        // 7.2 Acciones especiales
        $creditosEspeciales = CompensacionOtros::with('concepto')
            ->where('id_usuario', $idUsuario)
            ->whereHas('concepto', function($query) {
                $query->where('tipo', 'Acciones Especiales');
            })
            ->sum('creditos_compensacion');
        
        // 7.3 Computar límites para Gestión + Investigación + A.Especiales
        $creditosIngles = 0; // Si es necesario, implementar el cálculo específico
        $creditosParaLimite12 = $maxCargo + $creditosInvestigacion + $creditosIngles + $creditosEspeciales;
        
        if (isset($limites['Gestion+Investigacion+A.Especiales']) && $creditosParaLimite12 > $limites['Gestion+Investigacion+A.Especiales']) {
            $totalCompensacion = ($totalCompensacion - $creditosParaLimite12) + $limites['Gestion+Investigacion+A.Especiales'];
            $creditosParaLimite12 = $limites['Gestion+Investigacion+A.Especiales'];
        }
        
        // 7.4 Comprobar la restricción del 50% de la docencia
        $creditosOtros2 = CompensacionOtros::with('concepto')
            ->where('id_usuario', $idUsuario)
            ->whereHas('concepto', function($query) {
                $query->where('tipo', '<>', 'Acciones Especiales')
                      ->where('tipo', '<>', 'Posgrado')
                      ->where('tipo', '<>', 'Departamento');
            })
            ->sum('creditos_compensacion');
        
        $redLimitadas = $creditosParaLimite12 + $representacionSindical + $creditosOtros2;
        
        if ($redLimitadas > $docencia50) {
            $totalCompensacion = ($totalCompensacion - $redLimitadas) + $docencia50;
        }
        
        // Devolvemos los créditos de compensación totales
        // Nota: no incluimos los créditos de posgrado aquí, se suman por separado en el controlador
        return $totalCompensacion;
    }
    
    /**
     * Verifica si es el turno del usuario
     */
    protected function esTurnoDelUsuario($idUsuario, $turnoActual)
{
    try {
        // Lógica real para determinar si es el turno del usuario
        $orden = DB::table('miembro')
            ->where('id_usuario', $idUsuario)
            ->value('numero_orden');
                
        return $orden == $turnoActual;
    } catch (\Exception $e) {
        Log::error('Error en esTurnoDelUsuario: ' . $e->getMessage());
        return false; // Por defecto, asumimos que no es el turno del usuario
    }
}
    
    /**
     * Obtiene las asignaciones actuales del usuario
     */
    protected function obtenerAsignacionesActuales($idUsuario)
    {
        try {
            $dbSiguiente = $this->getDbSiguiente();
            
            return OrdenacionUsuarioAsignatura::with(['asignatura.titulacion'])
                ->where('id_usuario', $idUsuario)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error en obtenerAsignacionesActuales: ' . $e->getMessage());
            return collect([]); // Devolver una colección vacía en caso de error
        }
    }
    
    /**
     * Obtiene las asignaciones previas que puede mantener
     */
    protected function obtenerAsignacionesPrevias($idUsuario, $cursosConPreferencia)
    {
        try {
            $dbActual = $this->getDbActual();
            $dbSiguiente = $this->getDbSiguiente();
            
            // Consulta para obtener las asignaturas del curso anterior que llevan menos 
            // de cursosConPreferencia impartiéndose
            $asignaciones = DB::table($dbActual.'.ordenacion_usuario_asignatura')
                ->join($dbActual.'.asignatura', 'asignatura.id_asignatura', '=', 'ordenacion_usuario_asignatura.id_asignatura')
                ->leftJoin($dbActual.'.titulacion', 'asignatura.id_titulacion', '=', 'titulacion.id_titulacion')
                ->where('ordenacion_usuario_asignatura.id_usuario', $idUsuario)
                ->where('ordenacion_usuario_asignatura.antiguedad', '<', $cursosConPreferencia)
                ->where('asignatura.tipo', 'Asignatura')
                ->whereNotExists(function($query) use ($dbSiguiente, $idUsuario, $dbActual) {
                    $query->select(DB::raw(1))
                        ->from($dbSiguiente.'.ordenacion_usuario_asignatura as ua1')
                        ->whereRaw($dbActual.'.ordenacion_usuario_asignatura.id_asignatura = ua1.id_asignatura')
                        ->whereRaw($dbActual.'.ordenacion_usuario_asignatura.tipo = ua1.tipo')
                        ->whereRaw($dbActual.'.ordenacion_usuario_asignatura.grupo = ua1.grupo')
                        ->where('ua1.id_usuario', $idUsuario);
                })
                ->select(
                    'ordenacion_usuario_asignatura.id_asignatura',
                    'ordenacion_usuario_asignatura.antiguedad',
                    'asignatura.nombre_asignatura',
                    'asignatura.curso',
                    'asignatura.cuatrimestre',
                    'titulacion.nombre_titulacion',
                    'ordenacion_usuario_asignatura.tipo',
                    'ordenacion_usuario_asignatura.grupo',
                    'ordenacion_usuario_asignatura.creditos'
                )
                ->orderBy('id_asignatura')
                ->orderBy('tipo')
                ->orderBy('grupo')
                ->get();
                
            // Comprobar si la asignatura sigue existiendo en el curso siguiente
            foreach ($asignaciones as &$asignacion) {
                $existeAsignatura = DB::table($dbSiguiente.'.asignatura')
                    ->where('id_asignatura', $asignacion->id_asignatura)
                    ->where('estado', 'Activa')
                    ->exists();
                    
                $asignacion->existe = $existeAsignatura;
                
                // Verificar si puede haber problemas por reducción en el número de grupos
                if ($existeAsignatura) {
                    $gruposInfo = DB::table($dbActual.'.asignatura as ccia')
                        ->join($dbSiguiente.'.asignatura as sig', 'ccia.id_asignatura', '=', 'sig.id_asignatura')
                        ->where('ccia.id_asignatura', $asignacion->id_asignatura)
                        ->select(
                            'ccia.grupos_teoria as ngrupos_t',
                            'ccia.grupos_practicas as ngrupos_p',
                            'sig.grupos_teoria as sig_ngrupos_t',
                            'sig.grupos_practicas as sig_ngrupos_p'
                        )
                        ->first();
                        
                    if ($asignacion->tipo == 'Teoría') {
                        $asignacion->grupos_actual = $gruposInfo->ngrupos_t;
                        $asignacion->grupos_siguiente = $gruposInfo->sig_ngrupos_t;
                    } else {
                        $asignacion->grupos_actual = $gruposInfo->ngrupos_p;
                        $asignacion->grupos_siguiente = $gruposInfo->sig_ngrupos_p;
                    }
                    
                    // Verificar riesgos de no poder mantener la asignatura
                    $asignacion->posible_no_fase2 = false;
                    $asignacion->posible_perdida = false;
                    
                    if ($asignacion->grupos_siguiente < $asignacion->grupos_actual) {
                        // Verificar si hay riesgo de que la asignatura no llegue a segunda fase
                        $numReservas = DB::table($dbActual.'.ordenacion_usuario_asignatura')
                            ->where('id_asignatura', $asignacion->id_asignatura)
                            ->where('tipo', $asignacion->tipo)
                            ->count();
                            
                        if ($numReservas > $asignacion->grupos_siguiente) {
                            $asignacion->posible_no_fase2 = true;
                        }
                        
                        // Verificar si hay riesgo de que el usuario no pueda reservarlo
                        $ordenUsuario = DB::table('miembro')
                            ->where('id_usuario', $idUsuario)
                            ->value('numero_orden');
                            
                        $numReservasPrioritarias = DB::table($dbActual.'.ordenacion_usuario_asignatura')
                            ->join($dbSiguiente.'.miembro', 'ordenacion_usuario_asignatura.id_usuario', '=', 'miembro.id_usuario')
                            ->where('ordenacion_usuario_asignatura.id_asignatura', $asignacion->id_asignatura)
                            ->where('ordenacion_usuario_asignatura.tipo', $asignacion->tipo)
                            ->where('miembro.numero_orden', '<', $ordenUsuario)
                            ->count();
                            
                        if ($numReservasPrioritarias >= $asignacion->grupos_siguiente) {
                            $asignacion->posible_perdida = true;
                        } else {
                            // Verificar el caso de múltiples grupos del mismo usuario
                            $numReservasUsuario = DB::table($dbActual.'.ordenacion_usuario_asignatura')
                                ->where('id_usuario', $idUsuario)
                                ->where('id_asignatura', $asignacion->id_asignatura)
                                ->where('tipo', $asignacion->tipo)
                                ->where('grupo', '<', $asignacion->grupo)
                                ->count();
                                
                            if ($numReservasPrioritarias + $numReservasUsuario >= $asignacion->grupos_siguiente) {
                                $asignacion->posible_perdida = true;
                            }
                        }
                    }
                }
            }
            
            return $asignaciones;
        } catch (\Exception $e) {
            Log::error('Error en obtenerAsignacionesPrevias: ' . $e->getMessage());
            return collect([]); // Devolver una colección vacía en caso de error
        }
    }
    
    /**
     * Obtiene las asignaturas disponibles según el perfil del usuario
     */
    protected function obtenerAsignaturasDisponibles($idUsuario)
    {
        try {
            $dbSiguiente = $this->getDbSiguiente();
        
            // Obtener perfil del usuario
            $perfil = Perfil::findOrFail($idUsuario);
            
            // Construir la consulta según el perfil
            $query = DB::table($dbSiguiente.'.asignatura')
                ->join($dbSiguiente.'.titulacion', 'asignatura.id_titulacion', '=', 'titulacion.id_titulacion')
                ->where('asignatura.estado', 'Activa');
                
            // Filtrar por palabras clave
            if (!$perfil->sin_palabras_clave && !empty($perfil->palabras_clave)) {
                $palabrasClave = explode(' ', $perfil->palabras_clave);
                $query->where(function($q) use ($palabrasClave) {
                    foreach ($palabrasClave as $palabra) {
                        $q->orWhere('asignatura.nombre_asignatura', 'like', "%$palabra%");
                    }
                });
            }
            
            // Filtrar por titulaciones
            $titulacionesIds = PerfilTitulacion::where('id_usuario', $idUsuario)
                ->pluck('id_titulacion')
                ->toArray();
                
            if (!empty($titulacionesIds)) {
                $query->whereIn('asignatura.id_titulacion', $titulacionesIds);
            }
            
            // Obtener los resultados
            $asignaturas = $query->select(
                'asignatura.nombre_asignatura',
                'asignatura.fraccionable',
                'asignatura.id_asignatura',
                'asignatura.creditos_teoria',
                'asignatura.creditos_practicas',
                'asignatura.grupos_teoria',
                'asignatura.grupos_practicas',
                'asignatura.curso',
                'asignatura.cuatrimestre',
                'titulacion.nombre_titulacion'
            )
            ->orderBy('titulacion.nombre_titulacion')
            ->orderBy('asignatura.nombre_asignatura')
            ->get();
            
            // Procesar los resultados para determinar disponibilidad
            foreach ($asignaturas as &$asignatura) {
                // Obtenemos las asignaciones ya existentes para esta asignatura
                $asignaciones = OrdenacionUsuarioAsignatura::where('id_asignatura', $asignatura->id_asignatura)
                    ->get()
                    ->groupBy(function($item) {
                        return $item->tipo . $item->grupo;
                    });
                    
                $asignatura->grupos_teoria_disponibles = [];
                $asignatura->grupos_practicas_disponibles = [];
                
                // Verificar disponibilidad de grupos de teoría
                if ($perfil->teoria) {
                    for ($i = 1; $i <= $asignatura->grupos_teoria; $i++) {
                        $index = "Teoría" . $i;
                        $creditosAsignados = isset($asignaciones[$index]) ? 
                            $asignaciones[$index]->sum('creditos') : 0;
                        $creditosDisponibles = $asignatura->creditos_teoria - $creditosAsignados;
                        
                        if ($creditosDisponibles > 0) {
                            $asignatura->grupos_teoria_disponibles[] = [
                                'grupo' => $i,
                                'creditos_disponibles' => $creditosDisponibles
                            ];
                        }
                    }
                }
                
                // Verificar disponibilidad de grupos de prácticas
                if ($perfil->practicas) {
                    for ($i = 1; $i <= $asignatura->grupos_practicas; $i++) {
                        $index = "Prácticas" . $i;
                        $creditosAsignados = isset($asignaciones[$index]) ? 
                            $asignaciones[$index]->sum('creditos') : 0;
                        $creditosDisponibles = $asignatura->creditos_practicas - $creditosAsignados;
                        
                        if ($creditosDisponibles > 0) {
                            $asignatura->grupos_practicas_disponibles[] = [
                                'grupo' => $i,
                                'creditos_disponibles' => $creditosDisponibles
                            ];
                        }
                    }
                }
                
                // Información de profesor anterior
                $profesoresAnteriores = DB::table($this->getDbActual().'.ordenacion_usuario_asignatura')
                    ->join($this->getDbActual().'.usuario', 'ordenacion_usuario_asignatura.id_usuario', '=', 'usuario.id_usuario')
                    ->join($this->getDbSiguiente().'.miembro', 'ordenacion_usuario_asignatura.id_usuario', '=', 'miembro.id_usuario')
                    ->where('ordenacion_usuario_asignatura.id_asignatura', $asignatura->id_asignatura)
                    ->select(
                        'ordenacion_usuario_asignatura.tipo',
                        'ordenacion_usuario_asignatura.grupo',
                        'usuario.nombre',
                        'usuario.apellidos',
                        'miembro.numero_orden'
                    )
                    ->get()
                    ->groupBy(function($item) {
                        return $item->tipo . $item->grupo;
                    });
                    
                $asignatura->profesores_anteriores = $profesoresAnteriores;
            }
            
            return $asignaturas;
        } catch (\Exception $e) {
            Log::error('Error en obtenerAsignaturasDisponibles: ' . $e->getMessage());
            return []; // Devolver un array vacío en caso de error
        }
    }
    
    /**
     * Maneja la solicitud de mantener asignaturas en la primera fase
     */
    public function mantenerAsignacion(Request $request)
    {
        $request->validate([
            'asignaturas' => 'required|array',
            'asignaturas.*' => 'string'
        ]);
        
        $dbActual = $this->getDbActual();
        $dbSiguiente = $this->getDbSiguiente();
        
        foreach ($request->asignaturas as $asignatura) {
            // Formato esperado: id_asignatura_tipo_grupo
            list($idAsignatura, $tipo, $grupo) = explode('_', $asignatura);
            
            // Obtener la información de la asignación del curso actual
            $asignacionActual = DB::table($dbActual.'.ordenacion_usuario_asignatura')
                ->where('id_usuario', Auth::id())
                ->where('id_asignatura', $idAsignatura)
                ->where('tipo', $tipo)
                ->where('grupo', $grupo)
                ->first();
                
            if ($asignacionActual) {
                // Crear asignación para el curso siguiente
                OrdenacionUsuarioAsignatura::create([
                    'id_usuario' => Auth::id(),
                    'id_asignatura' => $idAsignatura,
                    'tipo' => $tipo,
                    'grupo' => $grupo,
                    'creditos' => $asignacionActual->creditos,
                    'antiguedad' => $asignacionActual->antiguedad + 1,
                    'en_primera_fase' => 1
                ]);
                
                $this->registrarLog(
                    'Mantener', 
                    $idAsignatura, 
                    $tipo, 
                    $grupo, 
                    $asignacionActual->creditos
                );
            }
        }
        
        return redirect()->route('ordenacion.index')
            ->with('success', 'Asignaturas reservadas correctamente');
    }
    
    /**
     * Elimina una asignación
     */
    public function eliminarAsignacion(Request $request)
    {
        $request->validate([
            'id_asignatura' => 'required',
            'tipo' => 'required',
            'grupo' => 'required',
            'creditos' => 'required|numeric'
        ]);
        
        $borrado = OrdenacionUsuarioAsignatura::where('id_usuario', Auth::id())
            ->where('id_asignatura', $request->id_asignatura)
            ->where('tipo', $request->tipo)
            ->where('grupo', $request->grupo)
            ->delete();
            
        if ($borrado) {
            $this->registrarLog(
                'Borrar', 
                $request->id_asignatura, 
                $request->tipo, 
                $request->grupo, 
                0
            );
            
            return redirect()->route('ordenacion.index')
                ->with('success', 'Asignación eliminada correctamente');
        }
        
        return redirect()->route('ordenacion.index')
            ->with('error', 'No se pudo eliminar la asignación');
    }
    
    /**
     * Cambiar grupo de una asignación
     */
    public function cambiarGrupo(Request $request)
    {
        $request->validate([
            'id_asignatura' => 'required',
            'tipo' => 'required',
            'grupo_original' => 'required|numeric',
            'grupo' => 'required|numeric|different:grupo_original'
        ]);
        
        DB::beginTransaction();
        
        try {
            // Obtener la asignación actual
            $asignacion = OrdenacionUsuarioAsignatura::where('id_usuario', Auth::id())
                ->where('id_asignatura', $request->id_asignatura)
                ->where('tipo', $request->tipo)
                ->where('grupo', $request->grupo_original)
                ->first();
                
            if (!$asignacion) {
                return redirect()->route('ordenacion.index')
                    ->with('error', 'No se encontró la asignación');
            }
            
            // Verificar que el nuevo grupo no esté ya asignado a alguien con menor número de orden
            $ordenUsuario = DB::table('miembro')
                ->where('id_usuario', Auth::id())
                ->value('numero_orden');
                
            $grupoOcupado = OrdenacionUsuarioAsignatura::whereHas('usuario.miembro', function($query) use ($ordenUsuario) {
                    $query->where('numero_orden', '<', $ordenUsuario);
                })
                ->where('id_asignatura', $request->id_asignatura)
                ->where('tipo', $request->tipo)
                ->where('grupo', $request->grupo)
                ->exists();
                
            if ($grupoOcupado) {
                return redirect()->route('ordenacion.index')
                    ->with('error', 'El grupo solicitado ya está asignado a un profesor con mayor prioridad');
            }
            
            // Crear nueva asignación con el nuevo grupo
            $nuevaAsignacion = $asignacion->replicate();
            $nuevaAsignacion->grupo = $request->grupo;
            $nuevaAsignacion->save();
            
            // Eliminar asignación original
            $asignacion->delete();
            
            DB::commit();
            
            $this->registrarLog(
                'Cambiar Grupo', 
                $request->id_asignatura, 
                $request->tipo, 
                $request->grupo, 
                $nuevaAsignacion->creditos,
                'Grupo anterior: ' . $request->grupo_original
            );
            
            return redirect()->route('ordenacion.index')
                ->with('success', 'Grupo cambiado correctamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('ordenacion.index')
                ->with('error', 'Error al cambiar el grupo: ' . $e->getMessage());
        }
    }
    
    /**
     * Asigna nuevas asignaturas durante el turno del usuario
     */
    public function asignarAsignaturas(Request $request)
    {
        // Validar que sea el turno del usuario
        $turno = Turno::first();
        $esTurnoUsuario = $this->esTurnoDelUsuario(Auth::id(), $turno->turno);
        
        if (!$esTurnoUsuario) {
            return redirect()->route('ordenacion.index')
                ->with('error', 'No es su turno para asignar asignaturas');
        }
        
        $request->validate([
            'asignaturas' => 'required|array',
            'asignaturas.*' => 'string'
        ]);
        
        DB::beginTransaction();
        
        try {
            foreach ($request->asignaturas as $asignatura) {
                // Formato esperado: Cr_id_asignatura_grupo_tipo
                preg_match('/Cr_(.+)_(\d+)_(.+)/', $asignatura, $matches);
                
                if (count($matches) == 4) {
                    $idAsignatura = $matches[1];
                    $grupo = $matches[2];
                    $tipo = $matches[3];
                    
                    // Obtener los créditos según el tipo
                    $asignaturaInfo = Asignatura::findOrFail($idAsignatura);
                    $creditos = ($tipo == 'Teoría') ? 
                        $asignaturaInfo->creditos_teoria : 
                        $asignaturaInfo->creditos_practicas;
                    
                    // Para asignaturas fraccionables, obtener los créditos del formulario
                    if ($asignaturaInfo->fraccionable == 'Fraccionable') {
                        $creditosInput = $request->input("Cr_{$idAsignatura}_{$grupo}_{$tipo}");
                        $creditosMax = $request->input("Cr_{$idAsignatura}_{$grupo}_{$tipo}MAX");
                        
                        if (!empty($creditosInput) && is_numeric($creditosInput) && $creditosInput <= $creditosMax) {
                            $creditos = $creditosInput;
                        }
                    }
                    
                    // Crear la asignación
                    OrdenacionUsuarioAsignatura::create([
                        'id_usuario' => Auth::id(),
                        'id_asignatura' => $idAsignatura,
                        'tipo' => $tipo,
                        'grupo' => $grupo,
                        'creditos' => $creditos,
                        'antiguedad' => 1,
                        'en_primera_fase' => 0
                    ]);
                    
                    $this->registrarLog(
                        'Asignar', 
                        $idAsignatura, 
                        $tipo, 
                        $grupo, 
                        $creditos
                    );
                }
            }
            
            DB::commit();
            
            return redirect()->route('ordenacion.index')
                ->with('success', 'Asignaturas asignadas correctamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('ordenacion.index')
                ->with('error', 'Error al asignar asignaturas: ' . $e->getMessage());
        }
    }
    
    /**
     * Actualiza el perfil académico del usuario
     */
    public function actualizarPerfil(Request $request)
    {
        $request->validate([
            'palabras_clave' => 'nullable|string|max:255',
            'sin_palabras_clave' => 'nullable|boolean',
            'teoria' => 'nullable|boolean',
            'practicas' => 'nullable|boolean',
            'titulaciones' => 'nullable|array',
            'titulaciones.*' => 'exists:titulacion,id_titulacion'
        ]);
        
        DB::beginTransaction();
        
        try {
            // Actualizar perfil
            $perfil = Perfil::findOrNew(Auth::id());
            $perfil->id_usuario = Auth::id();
            $perfil->palabras_clave = $request->palabras_clave ?? '';
            $perfil->sin_palabras_clave = $request->has('sin_palabras_clave') ? 1 : 0;
            $perfil->teoria = $request->has('teoria') ? 1 : 0;
            $perfil->practicas = $request->has('practicas') ? 1 : 0;
            $perfil->save();
            
            // Actualizar titulaciones del perfil
            PerfilTitulacion::where('id_usuario', Auth::id())->delete();
            
            if ($request->has('titulaciones')) {
                foreach ($request->titulaciones as $idTitulacion) {
                    PerfilTitulacion::create([
                        'id_usuario' => Auth::id(),
                        'id_titulacion' => $idTitulacion
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('ordenacion.index')
                ->with('success', 'Perfil actualizado correctamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('ordenacion.index')
                ->with('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }
    
    /**
     * Actualiza la preferencia de pasar turno
     */
    public function actualizarPasarTurno(Request $request)
    {
        $request->validate([
            'pasar_turno' => 'nullable|boolean'
        ]);
        
        $perfil = Perfil::findOrNew(Auth::id());
        $perfil->id_usuario = Auth::id();
        $perfil->pasar_turno = $request->has('pasar_turno') ? 1 : 0;
        $perfil->save();
        
        $this->registrarLog('Cambiar estado pasar_turno a ' . ($request->has('pasar_turno') ? 1 : 0));
        
        return redirect()->route('ordenacion.index')
            ->with('success', 'Preferencia actualizada correctamente');
    }
    
    /**
     * Pasa al siguiente turno
     */
    public function pasarTurno()
    {
        try {
            // Obtener el turno actual
            $turno = Turno::first();
        
        if (!$turno) {
            Log::error('Error en pasarTurno: No se encontró ningún registro en la tabla de turnos');
            return redirect()->route('ordenacion.index')
                ->with('error', 'No se encontró información sobre el turno actual');
        }
        
        // Verificar si es el turno del usuario actual
        if ($this->esTurnoDelUsuario(Auth::id(), $turno->turno)) {
            // Verificar si el usuario cumple con los requisitos para pasar turno
            $puedeAvanzar = false;
            
            // Obtener el perfil del usuario para verificar preferencias
            $perfil = Perfil::findOrFail(Auth::id());
            
            if ($perfil->pasar_turno) {
                $puedeAvanzar = true;
            } else {
                // Verificar si al usuario le faltan los créditos configurados o menos para completar su carga
                // Obtener la categoría del usuario y sus créditos de docencia
                $usuario = Usuario::with('categoriaDocente')
                    ->findOrFail(Auth::id());

                    // Verificar si el usuario tiene categoría docente asignada
if (!$usuario->categoriaDocente) {
    Log::error("Usuario con ID  no tiene categoría docente asignada");
    return 0; // O un valor predeterminado que tenga sentido en tu aplicación
}
                $creditosDocencia = $usuario->categoriaDocente->creditos_docencia;
                
                // Obtener los créditos de compensación
                $creditosCompensacion = $this->obtenerReducciones(Auth::id());
                
                // Calcular los créditos que debe impartir tras la compensación
                $creditosAImpartir = $creditosDocencia - $creditosCompensacion;
                
                // Obtener los créditos ya asignados
                $creditosAsignados = OrdenacionUsuarioAsignatura::where('id_usuario', Auth::id())
                    ->sum('creditos');
                
                // Verificar si la diferencia es menor o igual al valor configurable
                $creditosFaltantes = $creditosAImpartir - $creditosAsignados;
                $creditosMenosPermitidos = $this->getCreditosMenosPermitidos();
                
                if ($creditosFaltantes <= $creditosMenosPermitidos && $creditosFaltantes >= 0) {
                    $puedeAvanzar = true;
                }
                }
                
                if ($puedeAvanzar) {
                    // Avanzar al siguiente turno
                    $turnoAnterior = $turno->turno;
                    $turno->turno = $turno->turno + 1;
                    
                    // Guardar el cambio con manejo de errores
                    if (!$turno->save()) {
                        Log::error('Error en pasarTurno: No se pudo guardar el nuevo valor del turno');
                        return redirect()->route('ordenacion.index')
                            ->with('error', 'No se pudo actualizar el turno');
                    }
                    
                    // Registrar la acción en el log
                    $this->registrarLog('Pasar Turno', null, null, null, null, 
                        'Turno cambiado de ' . $turnoAnterior . ' a ' . $turno->turno);
                    
                    return redirect()->route('ordenacion.index')
                        ->with('success', 'Se ha pasado al siguiente turno correctamente');
                } else {
                    return redirect()->route('ordenacion.index')
    ->with('error', 'No puede pasar turno porque le faltan más de ' . $this->getCreditosMenosPermitidos() . ' créditos para completar su carga docente');
                }
            }
            
            return redirect()->route('ordenacion.index')
                ->with('error', 'No tiene permiso para realizar esta acción porque no es su turno');
                
        } catch (\Exception $e) {
            Log::error('Error en pasarTurno: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return redirect()->route('ordenacion.index')
                ->with('error', 'Ha ocurrido un error al procesar la solicitud: ' . $e->getMessage());
        }
    }
    
    /**
     * Registra un log de acción en la tabla de log de ordenación
     */
    protected function registrarLog($accion, $idAsignatura = null, $tipo = null, $grupo = null, $creditos = null, $detalles = null)
    {
        $dbSiguiente = $this->getDbSiguiente();
        
        DB::table($dbSiguiente.'.CCIA_LOG_ORDENACION')->insert([
            'id_usuario' => Auth::id(),
            'acceso' => now(),
            'accion' => $accion,
            'id_asignatura' => $idAsignatura,
            'tipo' => $tipo,
            'grupo' => $grupo,
            'creditos' => $creditos,
            'detalles' => $detalles
        ]);
    }
    
    /**
     * Devuelve el nombre de la base de datos actual
     */
    protected function getDbActual()
    {
        return config('database.connections.mysql.database');
    }
    
    /**
     * Devuelve el nombre de la base de datos del curso siguiente
     */
    protected function getDbSiguiente()
    {
        return config('database.connections.mysql_proximo.database');
    }

    /**
     * Muestra un resumen completo de la ordenación docente del usuario
     */
    public function resumen()
    {
        // Obtenemos la configuración de la base de datos
        $dbSiguiente = $this->getDbSiguiente();
        
        // Obtenemos información del año académico
        $fechaInicioCurso = Plazo::where('nombre_plazo', 'CURSO ACADEMICO ACTUAL')
            ->first()->fecha_inicio;
        $anio = Carbon::parse($fechaInicioCurso)->year;
        $cursoSiguiente = "Curso " . ($anio + 1) . "/" . ($anio + 2);

        // Obtenemos las compensaciones del usuario
        $creditos_compensacion = $this->obtenerReducciones(Auth::id());
        
        // Obtener el usuario con su categoría docente
        $usuario = Usuario::with('categoriaDocente')
            ->findOrFail(Auth::id());
        
        // Obtener las asignaciones del usuario para el próximo curso
        $asignaciones = OrdenacionUsuarioAsignatura::where('id_usuario', Auth::id())
            ->with(['asignatura.titulacion'])
            ->get();
        
        return view('ordenacion.resumen', [
            'usuario' => $usuario,
            'asignaciones' => $asignaciones,
            'creditos_compensacion' => $creditos_compensacion,
            'curso_siguiente' => $cursoSiguiente,
        ]);
    }

// Método para obtener parámetros de configuración
protected function getConfiguracion($clave, $valorPredeterminado = null)
{
    try {
        $config = DB::table('configuracion_ordenacion')
            ->where('clave', $clave)
            ->first();
            
        return $config ? $config->valor : $valorPredeterminado;
    } catch (\Exception $e) {
        Log::error('Error al obtener configuración ' . $clave . ': ' . $e->getMessage());
        return $valorPredeterminado;
    }
}

// Reemplazar la constante con llamada al método
protected function getCreditosMenosPermitidos()
{
    return floatval($this->getConfiguracion('creditos_menos_permitidos', 0.5));
}

/**
 * Obtiene los porcentajes de docencia configurados
 * 
 * @return array Porcentajes de docencia [porcentaje_menor, porcentaje_mayor]
 */
protected function getPorcentajesDocencia()
{
    $porcentajeMenor = floatval($this->getConfiguracion('porcentaje_limite_menor', 25)) / 100;
    $porcentajeMayor = floatval($this->getConfiguracion('porcentaje_limite_mayor', 50)) / 100;
    
    return [
        'porcentaje_menor' => $porcentajeMenor,
        'porcentaje_mayor' => $porcentajeMayor
    ];
}

// Método mejorado para identificar TFMs
protected function esTFM($posgrado)
{
    if (!$posgrado) return false;
    
    $identificadorTFM = $this->getConfiguracion('identificador_tfm', 'TFM');
    
    return (
        strpos(strtoupper($posgrado->nombre), 'TRABAJO FIN') !== false ||
        strpos(strtoupper($posgrado->codigo), $identificadorTFM) !== false
    );
}
}