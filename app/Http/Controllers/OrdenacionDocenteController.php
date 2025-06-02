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
use App\Models\Ordenacion\ConfiguracionOrdenacion;
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
            $esTurnoUsuario = $this->esTurnoDelUsuario(Auth::id(), $numeroTurno);            // Obtenemos el perfil del usuario con sus titulaciones relacionadas
            $perfil = Perfil::with('titulaciones')->find(Auth::id());
              // Si no existe el perfil, lo creamos automáticamente
            if (!$perfil) {
                $perfil = Perfil::create([
                    'id_usuario' => Auth::id(),
                    'palabras_clave' => '',
                    'sin_palabras_clave' => 0,
                    'teoria' => 1,
                    'practicas' => 1,
                    'pasar_turno' => 0
                ]);
                
                // Asignar todas las titulaciones al perfil automáticamente
                $todasLasTitulaciones = Titulacion::pluck('id_titulacion');
                $perfil->titulaciones()->sync($todasLasTitulaciones);
                
                // Cargar las titulaciones después de crear el perfil
                $perfil = Perfil::with('titulaciones')->find(Auth::id());
            }
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

            // Si es administrador, agregar información de gestión
            if (Auth::user()->hasRole('admin')) {
                $data['info_admin'] = $this->obtenerInformacionAdmin();
                $data['profesores_cursos_anteriores'] = $this->obtenerProfesoresCursosAnteriores();
            }            //$fase = 1; // Para simular y ver que tal las vistas, se puede cambiar a 2 o 3
            
             // Según la fase, renderizamos distintas vistas
    if ($fase == -1) {
        return $this->mostrarProcesoInactivo($data);
    } elseif ($fase == 1) {
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
    }    /**
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
     * Muestra la vista cuando el proceso está inactivo (fase -1)
     */
    protected function mostrarProcesoInactivo($data)
    {
        try {
            return view('ordenacion.proceso_inactivo', $data);
        } catch (\Exception $e) {
            Log::error('Error en mostrarProcesoInactivo: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return view('error.error', [
                'titulo' => 'Error al mostrar el estado del proceso',
                'mensaje' => 'Se ha producido un error al cargar la información del proceso de ordenación docente.',
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
    try {
        $dbSiguiente = $this->getDbSiguiente();        // Obtener perfil del usuario con sus titulaciones
        $perfil = Perfil::with('titulaciones')->find($idUsuario);
          // Si no existe el perfil, devolver array vacío
        if (!$perfil) {
            Log::warning("No se encontró perfil para el usuario ID {$idUsuario} en obtenerAsignaturasDisponiblesFase3");
            return [];
        }
        
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
          // Filtrar por titulaciones usando la relación cargada
        if ($perfil->titulaciones && $perfil->titulaciones->isNotEmpty()) {
            $titulacionesIds = $perfil->titulaciones->pluck('id_titulacion')->toArray();
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
        ->orderByRaw("CASE WHEN titulacion.nombre_titulacion LIKE 'Master%' THEN 1 ELSE 0 END")
        ->orderBy('asignatura.nombre_asignatura')
        ->get();
        
        // Procesar los resultados para determinar disponibilidad
        foreach ($asignaturas as &$asignatura) {
            // Obtenemos las asignaciones ya existentes para esta asignatura
            // A diferencia de la Fase 2, no excluimos asignaciones de turnos superiores
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
        Log::error('Error en obtenerAsignaturasDisponiblesFase3: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        return []; // Devolver un array vacío en caso de error
    }
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
            ->findOrFail($idUsuario);            // Verificar si el usuario tiene categoría docente asignada
if (!$usuario->categoriaDocente) {
    Log::warning("Usuario con ID {$idUsuario} no tiene categoría docente asignada. Asignando categoría simulada para continuar.");
    // Crear un objeto simulado con valores predeterminados
    $categoriaPredeterminada = new \stdClass();
    $categoriaPredeterminada->creditos_docencia = 24; // Valor predeterminado (ajustar según necesidades)
    $categoriaPredeterminada->nombre = "Categoría simulada";
    $categoriaPredeterminada->nombre_categoria = "Categoría no asignada";
    
    // Asignar el objeto simulado
    $usuario->categoriaDocente = $categoriaPredeterminada;
}
              $creditosDocencia = $usuario->categoriaDocente->creditos_docencia;
        
        // Obtener límites configurables basados en porcentajes
        $limitesDocentes = $this->calcularLimitesDocentes($creditosDocencia);
        
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
          // 1.3 Aplicar la restricción: Docencia Posgrado sin TFM <= límite menor
        if (($creditosDocenciaPosgrado - $creditosDocenciaTFM) > $limitesDocentes['menor']) {
            $creditosPosgrado = $limitesDocentes['menor'] + $creditosDocenciaTFM;
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
          if ($redLimitadas > $limitesDocentes['mayor']) {
            $totalCompensacion = ($totalCompensacion - $redLimitadas) + $limitesDocentes['mayor'];
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
            $dbSiguiente = $this->getDbSiguiente();            // Obtener perfil del usuario
            $perfil = Perfil::with('titulaciones')->findOrFail($idUsuario);
            
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
            ->orderByRaw("CASE WHEN titulacion.nombre_titulacion LIKE 'Master%' THEN 1 ELSE 0 END")
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
            $perfil = Perfil::with('titulaciones')->findOrNew(Auth::id());
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
          $perfil = Perfil::with('titulaciones')->findOrNew(Auth::id());
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
            $perfil = Perfil::with('titulaciones')->findOrFail(Auth::id());
            
            if ($perfil->pasar_turno) {
                $puedeAvanzar = true;
            } else {
                // Verificar si al usuario le faltan los créditos configurados o menos para completar su carga
                // Obtener la categoría del usuario y sus créditos de docencia
                $usuario = Usuario::with('categoriaDocente')
                    ->findOrFail(Auth::id());

                    // Verificar si el usuario tiene categoría docente asignada
// Verificar si el usuario tiene categoría docente asignada
if (!$usuario->categoriaDocente) {
    Log::warning("Usuario con ID ".Auth::id()." no tiene categoría docente asignada. Asignando categoría simulada para continuar.");
    // Crear un objeto simulado con valores predeterminados
    $categoriaPredeterminada = new \stdClass();
    $categoriaPredeterminada->creditos_docencia = 24; // Valor predeterminado
    $categoriaPredeterminada->nombre = "Categoría simulada";
    
    // Asignar el objeto simulado
    $usuario->categoriaDocente = $categoriaPredeterminada;
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
          $usuario = Usuario::with('categoriaDocente')
    ->findOrFail(Auth::id());

// Verificar si el usuario tiene categoría docente asignada
if (!$usuario->categoriaDocente) {
    Log::warning("Usuario con ID ".Auth::id()." no tiene categoría docente asignada. Asignando categoría simulada para continuar.");
    // Crear un objeto simulado con valores predeterminados
    $categoriaPredeterminada = new \stdClass();
    $categoriaPredeterminada->creditos_docencia = 24; // Valor predeterminado
    $categoriaPredeterminada->nombre = "Categoría simulada";
    $categoriaPredeterminada->nombre_categoria = "Categoría no asignada";
    
    // Asignar el objeto simulado
    $usuario->categoriaDocente = $categoriaPredeterminada;
}
        
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

// Reemplazar la constante con llamada al método del modelo
protected function getCreditosMenosPermitidos()
{
    return ConfiguracionOrdenacion::getCreditosMenosPermitidos();
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

/**
 * Calcula los límites de docencia basados en porcentajes configurables
 * 
 * @param float $creditosDocencia Créditos de docencia del usuario
 * @return array Límites calculados [menor, mayor]
 */
protected function calcularLimitesDocentes($creditosDocencia)
{
    // Usar el método del modelo ConfiguracionOrdenacion para mantener consistencia
    return ConfiguracionOrdenacion::calcularLimitesDocentes($creditosDocencia);
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

/**
     * Obtiene los datos necesarios para la validación JavaScript ValidaTurnoFase2
     */
    public function obtenerDatosValidacion()
    {
        try {
            $idUsuario = Auth::id();
            $turno = Turno::first();
              // Obtener créditos de la categoría del usuario
            $usuario = Usuario::find($idUsuario);
            $creditosCategoria = $usuario && $usuario->categoriaDocente 
                ? $usuario->categoriaDocente->creditos_docencia 
                : 24; // Valor predeterminado
            
            // Obtener créditos impartidos actuales
            $creditosImpartidos = $this->calcularCreditosImpartidos($idUsuario);
            $creditosT = $creditosImpartidos['total'];
            $creditosConPI = $creditosImpartidos['con_pi'];
            $creditosSinPI = $creditosImpartidos['sin_pi'];
            
            // Calcular docencia total (incluyendo posgrado y TFM)
            $docenciaTotal = $this->calcularDocenciaTotal($idUsuario);
            
            // Obtener constante CREDITOS_MENOS
            $creditosMenos = $this->getCreditosMenosPermitidos();
            
            // Calcular créditos presenciales faltantes
            $ccFaltaPresencial = $this->calcularCreditosFaltantesPresenciales($idUsuario);
            
            // Texto de docencia para mostrar
            $txtDocencia = $this->generarTextoDocencia($creditosImpartidos);
            
            return response()->json([
                'creditosT' => $creditosT,
                'credCategoría' => $creditosCategoria,
                'creditosConPI' => $creditosConPI,
                'creditosSinPI' => $creditosSinPI,
                'docenciaTotal' => $docenciaTotal,
                'creditosMenos' => $creditosMenos,
                'turno' => $turno->turno,
                'txtDocencia' => $txtDocencia,
                'ccFaltaPresencial' => $ccFaltaPresencial
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en obtenerDatosValidacion: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener datos de validación'], 500);
        }
    }
    
    /**
     * Calcula los créditos impartidos del usuario
     */
    protected function calcularCreditosImpartidos($idUsuario)
    {
        $dbSiguiente = $this->getDbSiguiente();
        
        // Obtener todas las asignaciones del usuario
        $asignaciones = DB::table($dbSiguiente . '.ordenacion_usuario_asignatura as oua')
            ->join($dbSiguiente . '.asignatura as a', 'oua.id_asignatura', '=', 'a.id_asignatura')
            ->join($dbSiguiente . '.titulacion as t', 'a.id_titulacion', '=', 't.id_titulacion')
            ->where('oua.id_usuario', $idUsuario)
            ->select('oua.creditos', 'a.tipo', 't.id_titulacion')
            ->get();
            
        $creditosTotal = 0;
        $creditosConPI = 0; // Créditos con Proyectos de Investigación
        $creditosSinPI = 0; // Créditos sin Proyectos de Investigación
        
        foreach ($asignaciones as $asignacion) {
            $creditosTotal += $asignacion->creditos;
            
            // Identificar si es Proyecto Fin de Carrera
            if (strpos(strtoupper($asignacion->tipo), 'PROYECTO') !== false) {
                $creditosConPI += $asignacion->creditos;
            } else {
                $creditosSinPI += $asignacion->creditos;
                $creditosConPI += $asignacion->creditos; // También cuenta para el total con PI
            }
        }
        
        return [
            'total' => $creditosTotal,
            'con_pi' => $creditosConPI,
            'sin_pi' => $creditosSinPI
        ];
    }
    
    /**
     * Calcula la docencia total incluyendo posgrado y TFM
     */
    protected function calcularDocenciaTotal($idUsuario)
    {
        $dbSiguiente = $this->getDbSiguiente();
        
        // Docencia en grado/1º ciclo
        $docenciaGrado = DB::table($dbSiguiente . '.ordenacion_usuario_asignatura as oua')
            ->join($dbSiguiente . '.asignatura as a', 'oua.id_asignatura', '=', 'a.id_asignatura')
            ->join($dbSiguiente . '.titulacion as t', 'a.id_titulacion', '=', 't.id_titulacion')
            ->where('oua.id_usuario', $idUsuario)
            ->whereNotIn('t.id_titulacion', ['99999', '1003', '1004']) // Excluir másteres
            ->sum('oua.creditos');
            
        // Docencia en posgrado (doctorado)
        $docenciaPosgrado = DocenciaPosgrado::where('id_usuario', $idUsuario)->sum('creditos');
        
        // Docencia en máster profesionalizantes
        $docenciaMaster = DB::table($dbSiguiente . '.ordenacion_usuario_asignatura as oua')
            ->join($dbSiguiente . '.asignatura as a', 'oua.id_asignatura', '=', 'a.id_asignatura')
            ->where('oua.id_usuario', $idUsuario)
            ->whereIn('a.id_titulacion', ['99999', '1003', '1004'])
            ->sum('oua.creditos');
            
        return $docenciaGrado + $docenciaPosgrado + $docenciaMaster;
    }
    
    /**
     * Calcula los créditos presenciales faltantes
     */
    protected function calcularCreditosFaltantesPresenciales($idUsuario)
    {
        // Implementar lógica específica para calcular créditos presenciales faltantes
        // Esto requiere determinar qué asignaturas son presenciales vs no presenciales
        return 0; // Por ahora retornamos 0, se debe implementar según los requisitos específicos
    }
    
    /**
     * Genera el texto de docencia para mostrar en la validación
     */
    protected function generarTextoDocencia($creditosImpartidos)
    {
        return "Créditos totales: " . $creditosImpartidos['total'] . 
               "\nCréditos con PI: " . $creditosImpartidos['con_pi'] . 
               "\nCréditos sin PI: " . $creditosImpartidos['sin_pi'];
    }

    /**
     * Función AsignaDocenciaCursoAnterior - Asigna la docencia del curso anterior
     * Traducción fiel de la función del sistema monolítico
     */
    protected function asignaDocenciaCursoAnterior($idUsuario, $turno)
    {
        try {
            $dbActual = $this->getDbActual();
            $dbSiguiente = $this->getDbSiguiente();
            
            // Actualizar el estado del turno en la tabla turno
            DB::table($dbSiguiente . '.turno')
                ->where('turno', $turno)
                ->update(['estado' => '2']);
            
            // Comprobar que el usuario no tenga ya ninguna asignatura asignada en 2ª fase
            $existeAsignacion = DB::table($dbSiguiente . '.ordenacion_usuario_asignatura')
                ->where('id_usuario', $idUsuario)
                ->where('en_primera_fase', '0')
                ->exists();
                
            if (!$existeAsignacion) {
                // Obtener asignaturas impartidas el año pasado
                $asignacionesAnteriores = DB::table($dbActual . '.ordenacion_usuario_asignatura')
                    ->where('id_usuario', $idUsuario)
                    ->get();
                    
                $error = 0; // Variable para controlar si se puede asignar toda la docencia del curso pasado
                
                foreach ($asignacionesAnteriores as $asignacion) {
                    // Verificar si la asignatura está activa
                    $asignaturaActual = DB::table($dbSiguiente . '.asignatura')
                        ->join($dbSiguiente . '.titulacion', 'asignatura.id_titulacion', '=', 'titulacion.id_titulacion')
                        ->where('asignatura.id_asignatura', $asignacion->id_asignatura)
                        ->where('titulacion.estado', 'Activa')
                        ->first();
                        
                    if ($asignaturaActual) {
                        // Obtener créditos según el tipo
                        $creditos = 0;
                        if ($asignacion->tipo == 'Teoría') {
                            $creditos = $asignaturaActual->creditos_teoria;
                        } elseif ($asignacion->tipo == 'Prácticas') {
                            $creditos = $asignaturaActual->creditos_practicas;
                        }
                        
                        $insertar = 0; // Flag para ver si esa asignación se puede hacer
                        
                        // Verificar si la asignatura está libre
                        $sumaCreditos = DB::table($dbSiguiente . '.ordenacion_usuario_asignatura')
                            ->where('id_asignatura', $asignacion->id_asignatura)
                            ->where('tipo', $asignacion->tipo)
                            ->where('grupo', $asignacion->grupo)
                            ->sum('creditos');
                            
                        if ($sumaCreditos === null || $sumaCreditos == 0) {
                            // No hay ninguna asignación a esa asignatura con las mismas características
                            $insertar = 1;
                        } else {
                            // Existen asignaciones. Comprobar si sobran créditos
                            $restoCreditos = $creditos - $sumaCreditos;
                            if ($restoCreditos >= $asignacion->creditos) {
                                $insertar = 1;
                            } else {
                                $error = 1; // No se puede asignar esta asignatura
                            }
                        }
                        
                        if ($insertar == 1) {
                            // Obtener número de grupos según el tipo
                            $nGrupos = 0;
                            if ($asignacion->tipo == 'Teoría') {
                                $nGrupos = $asignaturaActual->grupos_teoria;
                            } else {
                                $nGrupos = $asignaturaActual->grupos_practicas;
                            }
                            
                            $antiguedad = $asignacion->antiguedad + 1; // Incrementar la antigüedad
                            
                            // Si el número de grupos es mayor o igual que el grupo que tenía
                            if ($nGrupos >= $asignacion->grupo) {
                                // Insertar la asignación
                                DB::table($dbSiguiente . '.ordenacion_usuario_asignatura')->insert([
                                    'tipo' => $asignacion->tipo,
                                    'grupo' => $asignacion->grupo,
                                    'id_asignatura' => $asignacion->id_asignatura,
                                    'id_usuario' => $idUsuario,
                                    'creditos' => $asignacion->creditos,
                                    'antiguedad' => $antiguedad,
                                    'en_primera_fase' => 0
                                ]);
                                
                                // Registrar en el log
                                $this->registrarLog('Asignación automática curso anterior', 
                                    $asignacion->id_asignatura, 
                                    $asignacion->tipo, 
                                    $asignacion->grupo, 
                                    $asignacion->creditos);
                            }
                        }
                    }
                }
                
                return $error;
            }
            
            return 0; // Ya tiene asignaciones de 2ª fase
            
        } catch (\Exception $e) {
            Log::error('Error en asignaDocenciaCursoAnterior: ' . $e->getMessage());
            return 1; // Error
        }
    }
      /**
     * Función MuestraReducciones - Implementación completa del sistema monolítico
     * Muestra todas las compensaciones docentes con restricciones y límites
     */
    public function muestraReducciones($bd = 'Siguiente')
    {
        
            $idUsuario = Auth::id();
            $dbSiguiente = $this->getDbSiguiente();
            $dbActual = $this->getDbActual();
            
            $bd = ($bd == 'Siguiente') ? $dbSiguiente : $dbActual;
            
            // Obtener los límites de compensaciones
            $limites = [];
            
            // Verificamos si la tabla compensacion_limite existe
            try {
                $limitesQuery = DB::table($bd . '.compensacion_limite')->get();
                foreach ($limitesQuery as $limite) {
                    $limites[$limite->concepto] = $limite->limite_creditos;
                }
            } catch (\Exception $e) {
                Log::warning('No se encontró la tabla compensacion_limite: ' . $e->getMessage());
                // Valores predeterminados si no existe la tabla
                $limites = [
                    'Tesis' => 6,
                    'Proyectos' => 6,
                    'Investigación' => 9,
                    'Gestion+Investigacion+A.Especiales' => 12
                ];
            }
        
        $totalCompensacion = 0;
        
        // Obtener los créditos de docencia del usuario
        $usuario = DB::table($bd . '.miembro')
            ->join($bd . '.categoria', 'miembro.id_categoria', '=', 'categoria.id_categoria')
            ->where('miembro.id_usuario', $idUsuario)
            ->select('categoria.creditos_docencia')
            ->first();
              $creditosDocencia = $usuario->creditos_docencia ?? 24;
        
        // Calcular límites configurables basados en porcentajes
        $limitesDocentes = $this->calcularLimitesDocentes($creditosDocencia);
        
        $resultado = [
            'total_compensacion' => 0,
            'creditos_posgrado' => 0,
            'max_cargo' => 0,
            'representacion_sindical' => 0,
            'creditos_proyectos' => 0,
            'creditos_tesis' => 0,
            'creditos_sexenios' => 0,
            'creditos_otros' => 0,
            'creditos_investigacion' => 0,
            'creditos_especiales' => 0,
            'tablas_html' => '',
            'restricciones' => []
        ];
          // 1. DOCENCIA EN POSGRADO
        $tablaPosgrado = $this->generarTablaPosgrado($bd, $idUsuario, $limitesDocentes, $resultado);
        
        // 2. COMPENSACIONES POR CARGO (GESTIÓN UNIVERSITARIA)
        $tablaCargos = $this->generarTablaCargos($bd, $idUsuario, $resultado);
        
        // 3. COMPENSACIONES POR REPRESENTACIÓN SINDICAL
        $tablaRepresentacion = $this->generarTablaRepresentacion($bd, $idUsuario, $resultado);
        
        // 4. COMPENSACIONES POR PROYECTOS
        $tablaProyectos = $this->generarTablaProyectos($bd, $idUsuario, $limites, $resultado);
        
        // 5. COMPENSACIONES POR TESIS
        $tablaTesis = $this->generarTablaTesis($bd, $idUsuario, $limites, $resultado);
        
        // 6. COMPENSACIONES POR SEXENIOS
        $tablaSexenios = $this->generarTablaSexenios($bd, $idUsuario, $resultado);
        
        // 7. COMPENSACIONES POR OTROS CONCEPTOS
        $tablaOtros = $this->generarTablaOtros($bd, $idUsuario, $resultado);
          // 8. APLICAR RESTRICCIONES GLOBALES
        $this->aplicarRestriccionesGlobales($bd, $idUsuario, $limites, $limitesDocentes, $resultado);
        
        // Combinar todas las tablas
        $resultado['tablas_html'] = $tablaPosgrado . 
            '<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-600" style="margin: 20px auto; max-width: 75%;">' .
            '<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">' .
            '<tr><th colspan="3" class="px-6 py-3 text-center">Compensaciones Docentes</th></tr>' .
            '</thead><tbody>' .
            $tablaCargos . $tablaRepresentacion . $tablaProyectos . $tablaTesis . $tablaSexenios . $tablaOtros;
            
        // Mensaje si no hay compensaciones
        if ($resultado['total_compensacion'] == 0 || ($resultado['total_compensacion'] == $resultado['creditos_posgrado'])) {
            $resultado['tablas_html'] .= '<tr><td colspan="3" class="px-6 py-4 text-center">' .
                '<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4">'.
                'Actualmente no cuenta con ningún tipo de compensación docente'.
                '</div></td></tr>';
        }
        
        // Agregar restricciones al final de la tabla
        foreach ($resultado['restricciones'] as $restriccion) {
            $resultado['tablas_html'] .= '<tr><td colspan="3" class="px-6 py-4 text-right font-bold bg-red-50 dark:bg-red-900">' . $restriccion . '</td></tr>';
        }
        
        $resultado['tablas_html'] .= '</tbody></table>';
        
        return $resultado;
    }
      /**
     * Genera la tabla de docencia en posgrado
     */
    private function generarTablaPosgrado($bd, $idUsuario, $limitesDocentes, &$resultado)
    {
        try {
            // Docencia en máster profesionalizantes ya escogida
            $creditosDocenciaMaster = DB::table($bd . '.ordenacion_usuario_asignatura')
                ->join($bd . '.asignatura', 'ordenacion_usuario_asignatura.id_asignatura', '=', 'asignatura.id_asignatura')
                ->where('ordenacion_usuario_asignatura.id_usuario', $idUsuario)
                ->whereIn('asignatura.id_titulacion', ['99999', '1003', '1004'])
                ->sum('ordenacion_usuario_asignatura.creditos');
                
            // Docencia en posgrado (doctorado)
            try {
                $docentePosgrado = DB::table($bd . '.docencia_posgrado')
                    ->join($bd . '.posgrado', 'docencia_posgrado.id_posgrado', '=', 'posgrado.id_posgrado')
                    ->where('docencia_posgrado.id_usuario', $idUsuario)
                    ->select('posgrado.nombre', 'posgrado.codigo', 'docencia_posgrado.creditos')
                    ->get();
            } catch (\Exception $e) {
                // Si hay error, asumimos que no hay registros
                $docentePosgrado = collect([]);
                Log::warning('Error al obtener docencia de posgrado: ' . $e->getMessage());
            }
                
            if ($docentePosgrado->count() > 0 || $creditosDocenciaMaster > 0) {
                $tabla = '<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-600" style="margin: 20px auto; max-width: 75%;">';
                $tabla .= '<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">';
                $tabla .= '<tr><th colspan="3" class="px-6 py-3 text-center">Docencia en Posgrado</th></tr>';
                $tabla .= '<tr><th class="px-6 py-3">Posgrado</th><th class="px-6 py-3">Código</th><th class="px-6 py-3">Créditos</th></tr>';
                $tabla .= '</thead><tbody>';
                
                $cont = 0;
                $creditosDocenciaPosgrado = 0;
                $creditosDocenciaTFM = 0;
                
                foreach ($docentePosgrado as $docencia) {
                    $color = ($cont % 2 == 0) ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700';
                    $tabla .= "<tr class='$color border-b dark:border-gray-600'>";
                    $tabla .= "<td class='px-6 py-4'>{$docencia->nombre}</td>";
                    $tabla .= "<td class='px-6 py-4'>{$docencia->codigo}</td>";
                    $tabla .= "<td class='px-6 py-4'>{$docencia->creditos}</td>";
                    $tabla .= "</tr>";
                    
                    $creditosDocenciaPosgrado += $docencia->creditos;
                    if ($docencia->codigo == 'TFM') {
                        $creditosDocenciaTFM += $docencia->creditos;
                    }
                    $cont++;
                }
                
                if ($creditosDocenciaMaster) {
                    $color = ($cont % 2 == 0) ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700';
                    $tabla .= "<tr class='$color border-b dark:border-gray-600'>";
                    $tabla .= "<td colspan='2' class='px-6 py-4'>Docencia escogida en Máster Profesionalizantes</td>";
                    $tabla .= "<td class='px-6 py-4'>$creditosDocenciaMaster</td>";
                    $tabla .= "</tr>";
                    $creditosDocenciaPosgrado += $creditosDocenciaMaster;
                }
                  // Aplicar restricción del 25%
                if (($creditosDocenciaPosgrado - $creditosDocenciaTFM) > $limitesDocentes['menor']) {
                    $resultado['creditos_posgrado'] = $limitesDocentes['menor'] + $creditosDocenciaTFM;
                } else {
                    $resultado['creditos_posgrado'] = $creditosDocenciaPosgrado;
                }
                  $tabla .= "<tr class='bg-blue-50 dark:bg-blue-900'>";
                $tabla .= "<td colspan='2' class='px-6 py-4 text-right font-bold'>Restricción: (Docencia Posgrado sin TFM <= {$limitesDocentes['menor']} créd.) Total</td>";
                $tabla .= "<td class='px-6 py-4 font-bold'>{$resultado['creditos_posgrado']}</td>";
                $tabla .= "</tr>";
                
                $tabla .= '</tbody></table>';
                return $tabla;
            }
        } catch (\Exception $e) {
            Log::error('Error en generarTablaPosgrado: ' . $e->getMessage());
            return '';
        }
        
        return '';
    }
    
    /**
     * Genera la tabla de compensaciones por cargo de gestión universitaria
     */
    private function generarTablaCargos($bd, $idUsuario, &$resultado)
    {
        $cargos = DB::table($bd . '.compensacion_cargo')
            ->join($bd . '.cargo', 'compensacion_cargo.id_cargo', '=', 'cargo.id_cargo')
            ->where('compensacion_cargo.id_usuario', $idUsuario)
            ->where('cargo.tipo', 'Gestión Universitaria')
            ->select('compensacion_cargo.creditos_compensacion', 'cargo.nombre_cargo', 'cargo.comision')
            ->get();
            
        if ($cargos->count() > 0) {
            $tabla = '<tr><th colspan="3" class="px-6 py-3 text-center bg-gray-100 dark:bg-gray-600">Compensaciones por Cargo</th></tr>';
            $tabla .= '<tr class="bg-gray-50 dark:bg-gray-700"><th class="px-6 py-3">Nombre</th><th class="px-6 py-3">Comisión</th><th class="px-6 py-3">Créditos</th></tr>';
            
            $cont = 0;
            foreach ($cargos as $cargo) {
                $color = ($cont % 2 == 0) ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700';
                if ($cargo->creditos_compensacion > $resultado['max_cargo']) {
                    $resultado['max_cargo'] = $cargo->creditos_compensacion;
                }
                
                $tabla .= "<tr class='$color border-b dark:border-gray-600'>";
                $tabla .= "<td class='px-6 py-4'>{$cargo->nombre_cargo}</td>";
                $tabla .= "<td class='px-6 py-4'>{$cargo->comision}</td>";
                $tabla .= "<td class='px-6 py-4'>{$cargo->creditos_compensacion}</td>";
                $tabla .= "</tr>";
                $cont++;
            }
            
            $restriccion = "Restricción: No acumulables";
            if ($cargos->count() > 1) {
                $restriccion .= " (Se selecciona el cargo con más créditos)";
            }
            
            $tabla .= "<tr class='bg-blue-50 dark:bg-blue-900'>";
            $tabla .= "<td colspan='2' class='px-6 py-4 text-right font-bold'>$restriccion Total</td>";
            $tabla .= "<td class='px-6 py-4 font-bold'>{$resultado['max_cargo']}</td>";
            $tabla .= "</tr>";
            
            $resultado['total_compensacion'] += $resultado['max_cargo'];
            return $tabla;
        }
        
        return '';
    }
    
    /**
     * Genera la tabla de compensaciones por representación sindical
     */
    private function generarTablaRepresentacion($bd, $idUsuario, &$resultado)
    {
        $representacion = DB::table($bd . '.compensacion_cargo')
            ->join($bd . '.cargo', 'compensacion_cargo.id_cargo', '=', 'cargo.id_cargo')
            ->where('compensacion_cargo.id_usuario', $idUsuario)
            ->where('cargo.tipo', '<>', 'Gestión Universitaria')
            ->select('compensacion_cargo.creditos_compensacion', 'cargo.nombre_cargo', 'cargo.comision')
            ->get();
            
        if ($representacion->count() > 0) {
            $tabla = '';
            $cont = 0;
            
            foreach ($representacion as $cargo) {
                $color = ($cont % 2 == 0) ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700';
                $resultado['representacion_sindical'] += $cargo->creditos_compensacion;
                
                $tabla .= "<tr class='$color border-b dark:border-gray-600'>";
                $tabla .= "<td class='px-6 py-4'>{$cargo->nombre_cargo}</td>";
                $tabla .= "<td class='px-6 py-4'>{$cargo->comision}</td>";
                $tabla .= "<td class='px-6 py-4'>{$cargo->creditos_compensacion}</td>";
                $tabla .= "</tr>";
                $cont++;
            }
            
            $resultado['total_compensacion'] += $resultado['representacion_sindical'];
            return $tabla;
        }
        
        return '';
    }
      /**
     * Genera la tabla de compensaciones por proyectos
     */
    private function generarTablaProyectos($bd, $idUsuario, $limites, &$resultado)
    {
        
            // Primero verificamos si la tabla existe
            $tableExists = DB::select("SHOW TABLES LIKE '{$bd}.compensacion_proyecto'");
            if (empty($tableExists)) {
                return ''; // Si la tabla no existe, retornamos una cadena vacía
            }
            
            $proyectos = DB::table($bd . '.compensacion_proyecto')
                ->join($bd . '.proyecto', 'compensacion_proyecto.id_proyecto', '=', 'proyecto.id_proyecto')
                ->where('compensacion_proyecto.id_usuario', $idUsuario)
                ->select('compensacion_proyecto.creditos_compensacion', 'proyecto.titulo', 'proyecto.codigo')
                ->get();
                
            if ($proyectos->count() > 0) {
            $tabla = '<tr><th colspan="3" class="px-6 py-3 text-center bg-gray-100 dark:bg-gray-600">Compensaciones por Proyecto</th></tr>';
            $tabla .= '<tr class="bg-gray-50 dark:bg-gray-700"><th class="px-6 py-3">Título</th><th class="px-6 py-3">Código</th><th class="px-6 py-3">Créditos</th></tr>';
            
            $cont = 0;
            foreach ($proyectos as $proyecto) {
                $color = ($cont % 2 == 0) ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700';
                
                $tabla .= "<tr class='$color border-b dark:border-gray-600'>";
                $tabla .= "<td class='px-6 py-4'>{$proyecto->titulo}</td>";
                $tabla .= "<td class='px-6 py-4'>{$proyecto->codigo}</td>";
                $tabla .= "<td class='px-6 py-4'>{$proyecto->creditos_compensacion}</td>";
                $tabla .= "</tr>";
                
                $resultado['creditos_proyectos'] += $proyecto->creditos_compensacion;
                $cont++;
            }
            
            // Aplicar límite de proyectos
            if (isset($limites['Proyectos']) && $resultado['creditos_proyectos'] > $limites['Proyectos']) {
                $resultado['creditos_proyectos'] = $limites['Proyectos'];
            }
            
            $tabla .= "<tr class='bg-blue-50 dark:bg-blue-900'>";
            $tabla .= "<td colspan='2' class='px-6 py-4 text-right font-bold'>Restricción: Límite {$limites['Proyectos']} créd. Total</td>";
            $tabla .= "<td class='px-6 py-4 font-bold'>{$resultado['creditos_proyectos']}</td>";
            $tabla .= "</tr>";
            
            $resultado['total_compensacion'] += $resultado['creditos_proyectos'];
            return $tabla;
        }
        
        return '';
    }
      /**
     * Genera la tabla de compensaciones por tesis
     */
    private function generarTablaTesis($bd, $idUsuario, $limites, &$resultado)
    {
        try {
            // Verificamos si las tablas existen
            $tableExists = DB::select("SHOW TABLES LIKE '{$bd}.compensacion_tesis'");
            if (empty($tableExists)) {
                return ''; // Si la tabla no existe, retornamos una cadena vacía
            }
            
            $tesis = DB::table($bd . '.compensacion_tesis')
                ->join($bd . '.tesis_dpto', 'compensacion_tesis.id_tesis', '=', 'tesis_dpto.id_tesis')
                ->where('compensacion_tesis.id_usuario', $idUsuario)
                ->select('compensacion_tesis.creditos_compensacion', 'tesis_dpto.doctorando', 'tesis_dpto.fecha_lectura')
                ->get();
            
            if ($tesis->count() > 0) {
                $tabla = '<tr><th colspan="3" class="px-6 py-3 text-center bg-gray-100 dark:bg-gray-600">Compensaciones por Tesis</th></tr>';
                $tabla .= '<tr class="bg-gray-50 dark:bg-gray-700"><th class="px-6 py-3">Doctorando</th><th class="px-6 py-3">Fecha de Lectura</th><th class="px-6 py-3">Créditos</th></tr>';
                
                $cont = 0;
                foreach ($tesis as $tesisItem) {
                    $color = ($cont % 2 == 0) ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700';
                    $fechaFormateada = date('d/m/Y', strtotime($tesisItem->fecha_lectura));
                    
                    $tabla .= "<tr class='$color border-b dark:border-gray-600'>";
                    $tabla .= "<td class='px-6 py-4'>{$tesisItem->doctorando}</td>";
                    $tabla .= "<td class='px-6 py-4'>$fechaFormateada</td>";
                    $tabla .= "<td class='px-6 py-4'>{$tesisItem->creditos_compensacion}</td>";
                    $tabla .= "</tr>";
                    
                    $resultado['creditos_tesis'] += $tesisItem->creditos_compensacion;
                    $cont++;
                }
                
                // Aplicar límite de tesis
                if (isset($limites['Tesis']) && $resultado['creditos_tesis'] > $limites['Tesis']) {
                    $resultado['creditos_tesis'] = $limites['Tesis'];
                }
                
                $tabla .= "<tr class='bg-blue-50 dark:bg-blue-900'>";
                $tabla .= "<td colspan='2' class='px-6 py-4 text-right font-bold'>Restricción: Límite {$limites['Tesis']} créd. Total</td>";
                $tabla .= "<td class='px-6 py-4 font-bold'>{$resultado['creditos_tesis']}</td>";
                $tabla .= "</tr>";
                
                $resultado['total_compensacion'] += $resultado['creditos_tesis'];
                return $tabla;
            }
        } catch (\Exception $e) {
            Log::error('Error en generarTablaTesis: ' . $e->getMessage());
            return '';
        }
        
        return '';
    }
    
    /**
     * Genera la tabla de compensaciones por sexenios
     */
    private function generarTablaSexenios($bd, $idUsuario, &$resultado)
    {
        try {
            $tableExists = DB::select("SHOW TABLES LIKE '{$bd}.compensacion_sexenio'");
            if (empty($tableExists)) {
                return '';
            }
            
            $sexenio = DB::table($bd . '.compensacion_sexenio')
                ->where('id_usuario', $idUsuario)
                ->first();
                
            if ($sexenio) {
                $resultado['creditos_sexenios'] = $sexenio->creditos_compensacion;
                $resultado['total_compensacion'] += $resultado['creditos_sexenios'];
                
                $tabla = '<tr><th colspan="3" class="px-6 py-3 text-center bg-gray-100 dark:bg-gray-600">Compensaciones por Evaluaciones Positivas</th></tr>';
                $tabla .= '<tr class="bg-gray-50 dark:bg-gray-700"><th colspan="2" class="px-6 py-3">Concepto</th><th class="px-6 py-3">Créditos</th></tr>';
                $tabla .= '<tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-600">';
                $tabla .= '<td colspan="2" class="px-6 py-4">Créditos de compensación por tramos reconocidos</td>';
                $tabla .= "<td class='px-6 py-4'>{$resultado['creditos_sexenios']}</td>";
                $tabla .= '</tr>';
                
                return $tabla;
            }
        } catch (\Exception $e) {
            Log::error('Error en generarTablaSexenios: ' . $e->getMessage());
            return '';
        }
        
        return '';
    }
    
    /**
     * Genera la tabla de compensaciones por otros conceptos
     */
    private function generarTablaOtros($bd, $idUsuario, &$resultado)
    {
        try {
            $tableExists = DB::select("SHOW TABLES LIKE '{$bd}.compensacion_otros'");
            if (empty($tableExists)) {
                return '';
            }
            
            $otros = DB::table($bd . '.compensacion_otros')
                ->join($bd . '.compensacion_otros_concepto', 'compensacion_otros.id_concepto', '=', 'compensacion_otros_concepto.id_concepto')
                ->where('compensacion_otros.id_usuario', $idUsuario)
                ->where('compensacion_otros_concepto.tipo', '<>', 'Posgrado')
                ->where('compensacion_otros_concepto.tipo', '<>', 'Evaluación')
                ->select(DB::raw('SUM(compensacion_otros.creditos_compensacion) as cred_comp'), 'compensacion_otros_concepto.nombre_concepto')
                ->groupBy('compensacion_otros.id_concepto', 'compensacion_otros_concepto.nombre_concepto')
                ->get();
                
            if ($otros->count() > 0) {
                $tabla = '<tr><th colspan="3" class="px-6 py-3 text-center bg-gray-100 dark:bg-gray-600">Compensaciones por otros conceptos</th></tr>';
                $tabla .= '<tr class="bg-gray-50 dark:bg-gray-700"><th colspan="2" class="px-6 py-3">Concepto</th><th class="px-6 py-3">Créditos</th></tr>';
                
                $cont = 0;
                foreach ($otros as $otro) {
                    $color = ($cont % 2 == 0) ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700';
                    $creditosCompensacion = round($otro->cred_comp * 1000) / 1000;
                    
                    $tabla .= "<tr class='$color border-b dark:border-gray-600'>";
                    $tabla .= "<td colspan='2' class='px-6 py-4'>{$otro->nombre_concepto}</td>";
                    $tabla .= "<td class='px-6 py-4'>$creditosCompensacion</td>";
                    $tabla .= "</tr>";
                    
                    $resultado['creditos_otros'] += $creditosCompensacion;
                    $cont++;
                }
                
                $tabla .= "<tr class='bg-blue-50 dark:bg-blue-900'>";
                $tabla .= "<td colspan='2' class='px-6 py-4 text-right font-bold'>Total</td>";
                $tabla .= "<td class='px-6 py-4 font-bold'>{$resultado['creditos_otros']}</td>";
                $tabla .= "</tr>";
                
                $resultado['total_compensacion'] += $resultado['creditos_otros'];
                return $tabla;
            }
        } catch (\Exception $e) {
            Log::error('Error en generarTablaOtros: ' . $e->getMessage());
            return '';
        }
        
        return '';
    }
      /**
     * Aplica las restricciones globales del sistema
     */
    private function aplicarRestriccionesGlobales($bd, $idUsuario, $limites, $limitesDocentes, &$resultado)
    {
        // Calcular créditos de investigación
        $resultado['creditos_investigacion'] = $resultado['creditos_tesis'] + $resultado['creditos_proyectos'] + $resultado['creditos_sexenios'];
        
        // Restricción de investigación
        if (isset($limites['Investigación']) && $resultado['creditos_investigacion'] > $limites['Investigación']) {
            $resultado['total_compensacion'] = ($resultado['total_compensacion'] - $resultado['creditos_investigacion']) + $limites['Investigación'];
            $creditosInvestigacion = $limites['Investigación'];
            $resultado['restricciones'][] = "Restricción: Créditos por Investigación (Proyectos + Sexenios + Tesis) <= {$limites['Investigación']} -> Se trunca a {$limites['Investigación']}";
        }
        
        // Obtener créditos de acciones especiales
        $creditosEspeciales = DB::table($bd . '.compensacion_otros')
            ->join($bd . '.compensacion_otros_concepto', 'compensacion_otros.id_concepto', '=', 'compensacion_otros_concepto.id_concepto')
            ->where('compensacion_otros.id_usuario', $idUsuario)
            ->where('compensacion_otros_concepto.tipo', 'Acciones Especiales')
            ->sum('compensacion_otros.creditos_compensacion');
            
        $resultado['creditos_especiales'] = round($creditosEspeciales * 1000) / 1000;
        
        $creditosIngles = 0; // Si es necesario, implementar cálculo específico
        $creditosParaElLimite12 = $resultado['max_cargo'] + $resultado['creditos_investigacion'] + $creditosIngles + $resultado['creditos_especiales'];
        
        // Restricción de gestión + investigación + acciones especiales
        if (isset($limites['Gestion+Investigacion+A.Especiales']) && $creditosParaElLimite12 > $limites['Gestion+Investigacion+A.Especiales']) {
            $resultado['total_compensacion'] = ($resultado['total_compensacion'] - $creditosParaElLimite12) + $limites['Gestion+Investigacion+A.Especiales'];
            $creditosParaElLimite12 = $limites['Gestion+Investigacion+A.Especiales'];
            $resultado['restricciones'][] = "Restricción: (Gestión ({$resultado['max_cargo']}) + Investigación ({$resultado['creditos_investigacion']}) + (Inglés,Intercambio, Práct. Empresa ({$resultado['creditos_especiales']}))) <= {$limites['Gestion+Investigacion+A.Especiales']} -> Se trunca a {$limites['Gestion+Investigacion+A.Especiales']}";
        }
        
        // Restricción del 50% de la docencia
        $cOtros2 = DB::table($bd . '.compensacion_otros')
            ->join($bd . '.compensacion_otros_concepto', 'compensacion_otros.id_concepto', '=', 'compensacion_otros_concepto.id_concepto')
            ->where('compensacion_otros.id_usuario', $idUsuario)
            ->whereHas('concepto', function($query) {
                $query->where('tipo', '<>', 'Acciones Especiales')
                      ->where('tipo', '<>', 'Posgrado')
                      ->where('tipo', '<>', 'Departamento');
            })
            ->sum('compensacion_otros.creditos_compensacion');
          $redLimitadas = $creditosParaElLimite12 + $resultado['representacion_sindical'] + round($cOtros2 * 1000) / 1000;
        
        if ($redLimitadas > $limitesDocentes['mayor']) {
            $resultado['restricciones'][] = "Restricción: La suma de compensaciones no debe superar el 50% de la docencia ({$limitesDocentes['mayor']} Crt.) -> Se trunca a {$limitesDocentes['mayor']}";
            $resultado['total_compensacion'] = ($resultado['total_compensacion'] - $redLimitadas) + $limitesDocentes['mayor'];
        }
    }

    //==================== MÉTODOS ADMINISTRATIVOS ====================

    /**
     * Avanza al siguiente turno (solo administradores)
     */
    public function avanzarTurno(Request $request)
    {
        try {
            // Verificar que el usuario sea administrador
            if (!Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permisos para realizar esta acción.'
                ], 403);
            }

            $turno = Turno::first();
            if (!$turno) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró información del turno.'
                ]);
            }

            $turnoAnterior = $turno->turno;
            $turno->turno = $turno->turno + 1;
            
            if (!$turno->save()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar el nuevo turno.'
                ]);
            }

            // Registrar la acción en el log
            $this->registrarLog('Admin: Avanzar Turno', null, null, null, null, 
                'Turno cambiado de ' . $turnoAnterior . ' a ' . $turno->turno . ' por admin');

            return response()->json([
                'success' => true,
                'message' => "Turno avanzado exitosamente de {$turnoAnterior} a {$turno->turno}.",
                'nuevo_turno' => $turno->turno
            ]);

        } catch (\Exception $e) {
            Log::error('Error en avanzarTurno (admin): ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.'
            ], 500);
        }
    }

    /**
     * Cambia la fase del sistema (solo administradores)
     */
    public function cambiarFase(Request $request)
    {
        try {
            // Verificar que el usuario sea administrador
            if (!Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permisos para realizar esta acción.'
                ], 403);
            }

            $request->validate([
                'nueva_fase' => 'required|integer|in:-1,1,2,3'
            ]);

            $turno = Turno::first();
            if (!$turno) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró información del turno.'
                ]);
            }

            $faseAnterior = $turno->fase;
            $turno->fase = $request->nueva_fase;
            
            if (!$turno->save()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar la nueva fase.'
                ]);
            }

            // Registrar la acción en el log
            $this->registrarLog('Admin: Cambiar Fase', null, null, null, null, 
                'Fase cambiada de ' . $faseAnterior . ' a ' . $request->nueva_fase . ' por admin');

            $faseNombre = [
                -1 => 'Proceso Inactivo',
                1 => 'Primera Fase',
                2 => 'Segunda Fase',
                3 => 'Tercera Fase'
            ];

            return response()->json([
                'success' => true,
                'message' => "Fase cambiada exitosamente a {$faseNombre[$request->nueva_fase]}.",
                'nueva_fase' => $request->nueva_fase,
                'nueva_fase_nombre' => $faseNombre[$request->nueva_fase]
            ]);

        } catch (\Exception $e) {
            Log::error('Error en cambiarFase (admin): ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.'
            ], 500);
        }
    }

    /**
     * Exporta datos del sistema (solo administradores)
     */
    public function exportarDatos(Request $request)
    {
        try {
            // Verificar que el usuario sea administrador
            if (!Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permisos para realizar esta acción.'
                ], 403);
            }

            $request->validate([
                'tipo_exportacion' => 'required|string|in:asignaciones,profesores,turnos,completo'
            ]);

            $tipoExportacion = $request->tipo_exportacion;
            $fechaHora = now()->format('Y-m-d_H-i-s');
            $nombreArchivo = "ordenacion_docente_{$tipoExportacion}_{$fechaHora}.csv";

            // Generar datos según el tipo de exportación
            $datos = [];
            $headers = [];

            switch ($tipoExportacion) {
                case 'asignaciones':
                    $headers = ['Profesor', 'Asignatura', 'Titulación', 'Tipo', 'Grupo', 'Créditos'];
                    $asignaciones = DB::table('ordenacion_usuario_asignatura as oua')
                        ->join('usuario as u', 'oua.id_usuario', '=', 'u.id_usuario')
                        ->join('asignatura as a', 'oua.id_asignatura', '=', 'a.id_asignatura')
                        ->leftJoin('titulacion as t', 'a.id_titulacion', '=', 't.id_titulacion')                        ->select(
                            DB::raw("CONCAT(u.apellidos, ', ', u.nombre) as profesor"),
                            'a.nombre_asignatura',
                            DB::raw("COALESCE(t.nombre_titulacion, 'Sin titulación') as titulacion"),
                            'oua.tipo',
                            'oua.grupo',
                            'oua.creditos'
                        )
                        ->orderBy('u.apellidos', 'u.nombre')
                        ->get();
                    
                    foreach ($asignaciones as $asignacion) {
                        $datos[] = [
                            $asignacion->profesor,
                            $asignacion->nombre_asignatura,
                            $asignacion->titulacion,
                            $asignacion->tipo,
                            $asignacion->grupo,
                            $asignacion->creditos
                        ];
                    }
                    break;

                case 'profesores':
                    $headers = ['Apellidos', 'Nombres', 'Email', 'Tiene Perfil', 'Créditos Asignados'];
                    $profesores = Usuario::whereHas('roles', function($query) {
                            $query->where('name', 'profesor');
                        })
                        ->with('perfil')
                        ->get();
                    
                    foreach ($profesores as $profesor) {
                        $creditosAsignados = OrdenacionUsuarioAsignatura::where('id_usuario', $profesor->id_usuario)
                            ->sum('creditos');
                          $datos[] = [
                            $profesor->apellidos,
                            $profesor->nombre,
                            $profesor->email,
                            $profesor->perfil ? 'Sí' : 'No',
                            $creditosAsignados
                        ];
                    }
                    break;

                case 'turnos':
                    $headers = ['Fecha', 'Usuario', 'Acción', 'Detalles'];
                    // Aquí deberías tener una tabla de logs, por simplicidad uso un ejemplo
                    $datos[] = ['Sistema no implementado completamente', '', '', ''];
                    break;

                case 'completo':
                    return response()->json([
                        'success' => false,
                        'message' => 'Exportación completa no implementada aún.'
                    ]);
            }

            // Crear contenido CSV
            $csvContent = "\xEF\xBB\xBF"; // BOM para UTF-8
            $csvContent .= implode(',', $headers) . "\n";
            
            foreach ($datos as $fila) {
                $csvContent .= '"' . implode('","', $fila) . '"' . "\n";
            }

            // Registrar la acción en el log
            $this->registrarLog('Admin: Exportar Datos', null, null, null, null, 
                "Exportación tipo: {$tipoExportacion}, registros: " . count($datos));

            return response($csvContent, 200, [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => "attachment; filename=\"{$nombreArchivo}\"",
            ]);

        } catch (\Exception $e) {
            Log::error('Error en exportarDatos (admin): ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.'
            ], 500);
        }
    }

    /**
     * Reinicia el sistema de ordenación (solo administradores)
     */
    public function reiniciarSistema(Request $request)
    {
        try {
            // Verificar que el usuario sea administrador
            if (!Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permisos para realizar esta acción.'
                ], 403);
            }

            $request->validate([
                'confirmacion' => 'required|string|in:REINICIAR_SISTEMA'
            ]);

            DB::beginTransaction();

            // Registrar la acción antes de hacer cambios
            $this->registrarLog('Admin: Reiniciar Sistema', null, null, null, null, 
                'Sistema reiniciado por administrador');

            // Eliminar todas las asignaciones actuales
            $asignacionesEliminadas = OrdenacionUsuarioAsignatura::count();
            OrdenacionUsuarioAsignatura::truncate();

            // Resetear el turno a la fase -1 (inactivo) y turno 1
            $turno = Turno::first();
            if ($turno) {
                $turno->fase = -1;
                $turno->turno = 1;
                $turno->estado = 'inactivo';
                $turno->save();
            }

            // Resetear preferencias de pasar turno de todos los profesores
            DB::table('ordenacion_perfil')->update(['pasar_turno' => 0]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Sistema reiniciado exitosamente. Se eliminaron {$asignacionesEliminadas} asignaciones.",
                'detalles' => [
                    'asignaciones_eliminadas' => $asignacionesEliminadas,
                    'fase_actual' => -1,
                    'turno_actual' => 1
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en reiniciarSistema (admin): ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.'
            ], 500);
        }
    }

    /**
     * Obtiene información administrativa del sistema para mostrar en el panel de admin
     */
    protected function obtenerInformacionAdmin()
    {
        try {
            $info = [];
            
            // Estadísticas generales del sistema
            $info['total_profesores'] = Usuario::whereHas('roles', function($query) {
                $query->where('name', 'profesor');
            })->count();
            
            $info['total_asignaciones'] = OrdenacionUsuarioAsignatura::count();
            
            $info['profesores_con_perfil'] = Perfil::count();
            
            $info['asignaturas_activas'] = Asignatura::where('estado', 'Activa')->count();
            
            // Estadísticas por fases
            $info['asignaciones_fase1'] = OrdenacionUsuarioAsignatura::where('en_primera_fase', 1)->count();
            $info['asignaciones_fase2'] = OrdenacionUsuarioAsignatura::where('en_primera_fase', 0)->count();
            
            // Créditos totales asignados
            $info['creditos_totales'] = OrdenacionUsuarioAsignatura::sum('creditos');
            
            // Profesores que han configurado pasar turno
            $info['profesores_pasar_turno'] = Perfil::where('pasar_turno', 1)->count();
            
            // Última actividad del sistema (logs recientes)
            $ultimaActividad = DB::table($this->getDbSiguiente() . '.CCIA_LOG_ORDENACION')
                ->orderBy('acceso', 'desc')
                ->first();
            
            $info['ultima_actividad'] = $ultimaActividad ? $ultimaActividad->acceso : 'Sin actividad registrada';
              // Estado actual del turno
            $turno = Turno::first();
            $info['turno_actual'] = $turno;
            $info['fase_actual'] = $turno ? $turno->fase : 'No configurado';
            $info['estado_turno'] = $turno ? $turno->estado : 'No configurado';
            
            // Usuario actual en turno (si existe)
            $info['usuario_actual'] = null;
            if ($turno && $turno->id_usuario) {
                $info['usuario_actual'] = Usuario::find($turno->id_usuario);
            }
            
            // Recomendaciones para el admin
            $info['recomendaciones'] = [];
            
            if ($info['profesores_con_perfil'] < $info['total_profesores']) {
                $faltantes = $info['total_profesores'] - $info['profesores_con_perfil'];
                $info['recomendaciones'][] = "Hay {$faltantes} profesores sin perfil configurado";
            }
            
            if ($info['total_asignaciones'] == 0 && $turno && $turno->fase > 0) {
                $info['recomendaciones'][] = "El sistema está activo pero no hay asignaciones registradas";
            }
            
            if ($turno && $turno->fase == -1) {
                $info['recomendaciones'][] = "El proceso está inactivo. Considere cambiar a fase 1 para iniciar la ordenación";
            }
            
            return $info;
            
        } catch (\Exception $e) {
            Log::error('Error en obtenerInformacionAdmin: ' . $e->getMessage());
            return [
                'error' => 'Error al obtener información administrativa',
                'detalles' => config('app.debug') ? $e->getMessage() : null
            ];
        }
    }

    /**
     * Obtiene información de profesores y asignaturas de cursos anteriores para administradores
     */
    protected function obtenerProfesoresCursosAnteriores()
    {
        try {
            $dbActual = $this->getDbActual();
            $info = [];
            
            // Total de asignaciones del curso anterior
            $info['total_asignaciones'] = DB::table($dbActual . '.ordenacion_usuario_asignatura')->count();
              // Profesores con más asignaciones en el curso anterior
            $profesoresConMasAsignaciones = DB::table($dbActual . '.ordenacion_usuario_asignatura as oua')
                ->join($dbActual . '.usuario as u', 'oua.id_usuario', '=', 'u.id_usuario')
                ->select('u.nombre', 'u.apellidos', DB::raw('COUNT(*) as total_asignaciones'), 
                         DB::raw('SUM(oua.creditos) as total_creditos'))
                ->groupBy('oua.id_usuario', 'u.nombre', 'u.apellidos')
                ->orderBy('total_asignaciones', 'desc')
                ->limit(5)
                ->get();
            
            $info['profesores_top'] = $profesoresConMasAsignaciones;
            
            // Asignaturas más solicitadas del curso anterior
            $asignaturasMasSolicitadas = DB::table($dbActual . '.ordenacion_usuario_asignatura as oua')
                ->join($dbActual . '.asignatura as a', 'oua.id_asignatura', '=', 'a.id_asignatura')
                ->join($dbActual . '.titulacion as t', 'a.id_titulacion', '=', 't.id_titulacion')
                ->select('a.nombre_asignatura', 't.nombre_titulacion', 
                         DB::raw('COUNT(DISTINCT oua.id_usuario) as num_profesores'))
                ->groupBy('a.id_asignatura', 'a.nombre_asignatura', 't.nombre_titulacion')
                ->orderBy('num_profesores', 'desc')
                ->limit(10)
                ->get();
            
            $info['asignaturas_mas_solicitadas'] = $asignaturasMasSolicitadas;
            
            // Estadísticas de tipos de asignación
            $estadisticasTipos = DB::table($dbActual . '.ordenacion_usuario_asignatura')
                ->select('tipo', DB::raw('COUNT(*) as total'), DB::raw('SUM(creditos) as creditos_total'))
                ->groupBy('tipo')
                ->get();
            
            $info['estadisticas_tipos'] = $estadisticasTipos;
            
            // Profesores sin asignaciones en el curso anterior
            $profesoresSinAsignaciones = DB::table($dbActual . '.usuario as u')
                ->leftJoin($dbActual . '.ordenacion_usuario_asignatura as oua', 'u.id_usuario', '=', 'oua.id_usuario')
                ->join($dbActual . '.roles_usuario as ru', 'u.id_usuario', '=', 'ru.id_usuario')
                ->join($dbActual . '.roles as r', 'ru.id_rol', '=', 'r.id_rol')
                ->where('r.nombre', 'profesor')
                ->whereNull('oua.id_usuario')
                ->select('u.nombres', 'u.apellidos', 'u.email')
                ->get();
            
            $info['profesores_sin_asignaciones'] = $profesoresSinAsignaciones;
            $info['total_profesores_sin_asignaciones'] = $profesoresSinAsignaciones->count();
            
            // Distribución por titulaciones
            $distribucionTitulaciones = DB::table($dbActual . '.ordenacion_usuario_asignatura as oua')
                ->join($dbActual . '.asignatura as a', 'oua.id_asignatura', '=', 'a.id_asignatura')
                ->join($dbActual . '.titulacion as t', 'a.id_titulacion', '=', 't.id_titulacion')
                ->select('t.nombre_titulacion', 
                         DB::raw('COUNT(*) as num_asignaciones'),
                         DB::raw('SUM(oua.creditos) as creditos_total'))
                ->groupBy('t.id_titulacion', 't.nombre_titulacion')
                ->orderBy('num_asignaciones', 'desc')
                ->get();
            
            $info['distribucion_titulaciones'] = $distribucionTitulaciones;
            
            return $info;
            
        } catch (\Exception $e) {
            Log::error('Error en obtenerProfesoresCursosAnteriores: ' . $e->getMessage());
            return [
                'error' => 'Error al obtener información de cursos anteriores',
                'detalles' => config('app.debug') ? $e->getMessage() : null,
                'total_asignaciones' => 0
            ];
        }
    }
}