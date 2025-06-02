<?php

namespace App\Http\Controllers;

use App\Models\Tutoria;
use App\Models\Despacho;
use App\Models\Plazo; // Añadimos el modelo Plazo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class TutoriaController extends Controller
{    /**
     * Verifica si estamos dentro del plazo para editar tutorías
     * 
     * @param int $cuatrimestre El cuatrimestre (1 o 2)
     * @param bool $proximoCurso Indica si se refiere al próximo curso o al actual
     * @return bool
     */
    private function dentroDePlazo($cuatrimestre, $proximoCurso = false)
    {
        // Determinar la conexión actual (si estamos en el próximo curso)
        $estaEnProximoCurso = Session::get('db_connection') === 'mysql_proximo';
        
        // Si estamos en la BD del próximo curso pero queremos validar el plazo del curso actual, o viceversa
        if ($estaEnProximoCurso !== $proximoCurso) {
            return false; // No permitimos editar tutorías de un curso diferente al de la BD actual
        }
        
        $fechaActual = Carbon::now();
        $nombrePlazoBase = "CAMBIAR TUTORIAS " . 
                          ($cuatrimestre == 1 ? "PRIMER" : "SEGUNDO") . 
                          " CUATRIMESTRE";
                          
        if ($proximoCurso) {
            $nombrePlazo = $nombrePlazoBase . " CURSO SIGUIENTE";
        } else {
            $nombrePlazo = $nombrePlazoBase;
        }
        
        // IMPORTANTE: Los plazos del curso siguiente están en la BD del curso actual
        // Siempre buscar en la conexión 'mysql' (curso actual)
        $conexionOriginal = config('database.default');
        config(['database.default' => 'mysql']);
        
        try {
            // Buscar el plazo correspondiente
            $plazo = Plazo::where('nombre_plazo', 'LIKE', "%$nombrePlazo%")->first();
            
            if (!$plazo) {
                config(['database.default' => $conexionOriginal]);
                return false; // No existe el plazo
            }
            
            $fechaInicio = Carbon::parse($plazo->fecha_inicio);
            $fechaFin = Carbon::parse($plazo->fecha_fin);
            
            // Verificar si la fecha actual está dentro del rango del plazo
            $resultado = $fechaActual->between($fechaInicio, $fechaFin);
            
            config(['database.default' => $conexionOriginal]);
            return $resultado;
            
        } catch (\Exception $e) {
            config(['database.default' => $conexionOriginal]);
            return false;
        }
    }    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Determinar el cuatrimestre actual basado en la fecha
        $mes = Carbon::now()->month;
        $cuatrimestreActual = ($mes >= 9 || $mes <= 2) ? 1 : 2;

        // Obtener el cuatrimestre seleccionado (o usar el actual)
        $cuatrimestreSeleccionado = $request->input('cuatrimestre', $cuatrimestreActual);

        // Verificar si estamos en próximo curso según la conexión actual
        $estaEnProximoCurso = Session::get('db_connection') === 'mysql_proximo';
        
        // Verificar si estamos dentro del plazo para editar tutorías
        $dentroDePlazo = $this->dentroDePlazo($cuatrimestreSeleccionado, $estaEnProximoCurso);

        // Si no está dentro del plazo, redirigir al panel de gestión
        if (!$dentroDePlazo) {
            return redirect()->route('tutorias.gestion');
        }

        // Obtener el despacho seleccionado (o usar el del usuario actual si existe)
        $despachoSeleccionado = $request->input('despacho', Auth::user()->id_despacho ?? null);

        $tutorias = collect(); // Inicializar como colección vacía

        // Obtener todos los despachos (para el selector)
        $despachos = Despacho::all();

        // Definir las horas del día (de 08:00 a 21:30 en intervalos de 30 min)
        $horas = [];
        $horaInicio = 8 * 60; // 8:00 en minutos
        $horaFin = 21 * 60 + 30; // 21:30 en minutos

        for ($minutos = $horaInicio; $minutos < $horaFin; $minutos += 30) {
            $inicio = sprintf('%02d:%02d', floor($minutos / 60), $minutos % 60);
            $fin = sprintf('%02d:%02d', floor(($minutos + 30) / 60), ($minutos + 30) % 60);

            $horas[] = [
                'inicio' => $inicio,
                'fin' => $fin
            ];        }

        // Definir los días de la semana
        $diasSemana = [
            'Lunes' => 'Lunes',
            'Martes' => 'Martes', 
            'Miércoles' => 'Miércoles',
            'Jueves' => 'Jueves',
            'Viernes' => 'Viernes'
        ];// Si hay un despacho seleccionado, cargar las tutorías existentes
        if ($despachoSeleccionado) {
            $tutorias = Tutoria::where('id_despacho', $despachoSeleccionado)
                ->where('cuatrimestre', $cuatrimestreSeleccionado)
                ->get();
                
            // Formatear las tutorías para que coincidan con el formato de la vista
            foreach ($tutorias as $tutoria) {
                // Asegurar formato HH:MM para las horas
                if (strlen($tutoria->inicio) > 5) {
                    $tutoria->inicio = substr($tutoria->inicio, 0, 5);
                }
                if (strlen($tutoria->fin) > 5) {
                    $tutoria->fin = substr($tutoria->fin, 0, 5);
                }
                // Normalizar el nombre del día
                $tutoria->dia = ucfirst(strtolower(trim($tutoria->dia)));
            }
        }

        // Calcular horas totales de las tutorías existentes
        $horasTotales = 0;
        if ($tutorias->count() > 0) {
            foreach ($tutorias as $tutoria) {
                // Cada slot de tutoría es de 30 minutos (0.5 horas)
                $horasTotales += 0.5;
            }
        }

        return view('tutorias.index', compact(
            'tutorias',
            'despachos',
            'horas',
            'diasSemana',
            'cuatrimestreActual',
            'cuatrimestreSeleccionado',
            'despachoSeleccionado',
            'horasTotales', // Nueva variable para mostrar las horas totales
            'dentroDePlazo', // Nueva variable para la vista
            'estaEnProximoCurso' // También enviamos esta información a la vista
        ));
    }

    /**
     * Actualizar todas las tutorías de una vez
     */
    public function actualizar(Request $request)
    {
        $idDespacho = $request->input('id_despacho');
        $cuatrimestre = $request->input('cuatrimestre');
        $tutoriasData = $request->input('tutorias', []);

        // Verificar si estamos en próximo curso según la conexión actual
        $estaEnProximoCurso = Session::get('db_connection') === 'mysql_proximo';
        
        // Verificar si estamos dentro del plazo para editar tutorías
        $dentroDePlazo = $this->dentroDePlazo($cuatrimestre, $estaEnProximoCurso);        if (!$dentroDePlazo) {
            return redirect()->back()->with('error', 'No se pueden modificar las tutorías fuera del plazo establecido');
        }

        // Contar las horas totales seleccionadas (cada slot son 30 minutos = 0.5 horas)
        $horasSeleccionadas = 0;
        foreach ($tutoriasData as $dia => $horarios) {
            foreach ($horarios as $inicio => $fines) {
                foreach ($fines as $fin => $seleccionada) {
                    if ($seleccionada == '1') {
                        $horasSeleccionadas += 0.5;
                    }
                }
            }
        }

        // Validar que no exceda las 6 horas (12 slots de 30 minutos)
        if ($horasSeleccionadas > 6) {
            return redirect()->back()->with('error', 'No puede seleccionar más de 6 horas de tutorías. Ha seleccionado ' . $horasSeleccionadas . ' horas.');
        }

        // Validar que tenga exactamente 6 horas
        if ($horasSeleccionadas != 6) {
            return redirect()->back()->with('error', 'Debe seleccionar exactamente 6 horas de tutorías. Ha seleccionado ' . $horasSeleccionadas . ' horas.');
        }

        // Primero, eliminar todas las tutorías existentes para este despacho y cuatrimestre
        Tutoria::where('id_despacho', $idDespacho)
            ->where('cuatrimestre', $cuatrimestre)
            ->delete();

        // Luego, crear las nuevas tutorías seleccionadas
        foreach ($tutoriasData as $dia => $horarios) {
            foreach ($horarios as $inicio => $fines) {
                foreach ($fines as $fin => $seleccionada) {
                    if ($seleccionada == '1') {
                        Tutoria::create([
                            'id_usuario' => Auth::id(),
                            'id_despacho' => $idDespacho,
                            'cuatrimestre' => $cuatrimestre,
                            'dia' => $dia,
                            'inicio' => $inicio,
                            'fin' => $fin
                        ]);
                    }
                }
            }
        }

        // Redirigir a la vista de visualización
        return redirect()->route('tutorias.ver', [
            'despacho' => $idDespacho,
            'cuatrimestre' => $cuatrimestre
        ])->with('success', 'Horario de tutorías actualizado correctamente');
    }

    /**
     * Ver las tutorías guardadas (vista de solo lectura)
     */
    public function verTutorias(Request $request)
    {
        // Determinar el cuatrimestre actual basado en la fecha
        $mes = Carbon::now()->month;
        $cuatrimestreActual = ($mes >= 9 || $mes <= 2) ? 1 : 2;

        // Obtener el cuatrimestre seleccionado (o usar el actual)
        $cuatrimestreSeleccionado = $request->input('cuatrimestre', $cuatrimestreActual);

        // Obtener el despacho seleccionado (o usar el del usuario actual si existe)
        $despachoSeleccionado = $request->input('despacho', Auth::user()->id_despacho ?? null);

        // Verificar si estamos en próximo curso según la conexión actual
        $estaEnProximoCurso = Session::get('db_connection') === 'mysql_proximo';
        
        // Verificar si estamos dentro del plazo para editar tutorías
        $dentroDePlazo = $this->dentroDePlazo($cuatrimestreSeleccionado, $estaEnProximoCurso);

        // Obtener tutorias para el despacho y cuatrimestre seleccionados
        $tutorias = Tutoria::where('id_despacho', $despachoSeleccionado)
            ->where('cuatrimestre', $cuatrimestreSeleccionado)
            ->get();

        // Formatear las horas y normalizar los nombres de los días
        foreach ($tutorias as $tutoria) {
            // Si la hora tiene formato HH:MM:SS, quitar los segundos
            if (strlen($tutoria->inicio) > 5) {
                $tutoria->inicio = substr($tutoria->inicio, 0, 5);
            }
            if (strlen($tutoria->fin) > 5) {
                $tutoria->fin = substr($tutoria->fin, 0, 5);
            }

            // Normalizar el nombre del día
            $tutoria->dia = ucfirst(strtolower(trim($tutoria->dia)));
        }

        // Obtener todos los despachos (para el selector)
        $despachos = Despacho::all();

        // Definir las horas del día (de 08:00 a 21:30 en intervalos de 30 min)
        $horas = [];
        $horaInicio = 8 * 60; // 8:00 en minutos
        $horaFin = 21 * 60 + 30; // 21:30 en minutos

        for ($minutos = $horaInicio; $minutos < $horaFin; $minutos += 30) {
            $inicio = sprintf('%02d:%02d', floor($minutos / 60), $minutos % 60);
            $fin = sprintf('%02d:%02d', floor(($minutos + 30) / 60), ($minutos + 30) % 60);

            $horas[] = [
                'inicio' => $inicio,
                'fin' => $fin
            ];
        }

        // Definir los días de la semana
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        return view('tutorias.ver', compact(
            'tutorias',
            'despachos',
            'horas',
            'diasSemana',
            'cuatrimestreActual',
            'cuatrimestreSeleccionado',
            'despachoSeleccionado',
            'dentroDePlazo', // Nueva variable para la vista
            'estaEnProximoCurso' // También enviamos esta información a la vista
        ));
    }

    /**
     * Información sobre los plazos de tutorías
     */
    public function plazos()
    {
        // Obtener todos los plazos relacionados con tutorías
        $plazos = Plazo::where('nombre_plazo', 'LIKE', '%TUTORIAS%')->get();
        
        // Formatear las fechas para mejor legibilidad
        foreach ($plazos as $plazo) {
            $plazo->fecha_inicio_formateada = Carbon::parse($plazo->fecha_inicio)->format('d/m/Y');
            $plazo->fecha_fin_formateada = Carbon::parse($plazo->fecha_fin)->format('d/m/Y');
            
            // Verificar si el plazo está activo actualmente
            $fechaActual = Carbon::now();
            $fechaInicio = Carbon::parse($plazo->fecha_inicio);
            $fechaFin = Carbon::parse($plazo->fecha_fin);
            
            $plazo->activo = $fechaActual->between($fechaInicio, $fechaFin);
        }
        
        return view('tutorias.plazos', compact('plazos'));
    }    /**
     * Vista principal de gestión de tutorías organizadas por curso académico y semestre
     */
    public function gestion()
    {
        // Calcular cursos académicos basándose en la fecha actual
        $cursoActual = $this->calcularCursoAcademicoActual();
        $cursoSiguiente = $this->calcularCursoAcademicoSiguiente();
        
        // Crear estructura de cursos y semestres
        $cursosDisponibles = [
            [
                'conexion' => 'mysql',
                'nombre_completo' => $cursoActual['nombre_completo'],
                'nombre_corto' => $cursoActual['nombre_corto'],
                'semestres' => [
                    [
                        'numero' => 1,
                        'nombre' => 'Primer Semestre',
                        'plazo_nombre' => 'CAMBIAR TUTORIAS PRIMER CUATRIMESTRE',
                        'activo' => $this->dentroDePlazo(1, false),
                        'ruta' => route('tutorias.index', ['cuatrimestre' => 1])
                    ],
                    [
                        'numero' => 2,
                        'nombre' => 'Segundo Semestre', 
                        'plazo_nombre' => 'CAMBIAR TUTORIAS SEGUNDO CUATRIMESTRE',
                        'activo' => $this->dentroDePlazo(2, false),
                        'ruta' => route('tutorias.index', ['cuatrimestre' => 2])
                    ]
                ]
            ],
            [
                'conexion' => 'mysql_proximo',
                'nombre_completo' => $cursoSiguiente['nombre_completo'],
                'nombre_corto' => $cursoSiguiente['nombre_corto'],
                'semestres' => [
                    [
                        'numero' => 1,
                        'nombre' => 'Primer Semestre',
                        'plazo_nombre' => 'CAMBIAR TUTORIAS PRIMER CUATRIMESTRE CURSO SIGUIENTE',
                        'activo' => $this->dentroDePlazo(1, true),
                        'ruta' => route('tutorias.index', ['cuatrimestre' => 1])
                    ],
                    [
                        'numero' => 2,
                        'nombre' => 'Segundo Semestre',
                        'plazo_nombre' => 'CAMBIAR TUTORIAS SEGUNDO CUATRIMESTRE CURSO SIGUIENTE', 
                        'activo' => $this->dentroDePlazo(2, true),
                        'ruta' => route('tutorias.index', ['cuatrimestre' => 2])
                    ]
                ]
            ]
        ];

        // Obtener información adicional sobre plazos
        $plazosInfo = $this->obtenerInformacionPlazos();
        
        return view('tutorias.gestion', compact('cursosDisponibles', 'plazosInfo'));
    }    /**
     * Calcular el curso académico actual basándose en la fecha
     */
    private function calcularCursoAcademicoActual()
    {
        $fechaActual = Carbon::now();
        $mes = $fechaActual->month;
        $anio = $fechaActual->year;
        
        // Si estamos entre enero y agosto, el curso académico comenzó el año anterior
        if ($mes >= 1 && $mes <= 8) {
            $anioInicio = $anio - 1;
            $anioFin = $anio;
        } else {
            // Si estamos entre septiembre y diciembre, el curso académico comenzó este año
            $anioInicio = $anio;
            $anioFin = $anio + 1;
        }
        
        $anioCorto = substr($anioInicio, -2);
        $anioSiguienteCorto = substr($anioFin, -2);
        
        return [
            'nombre_completo' => "Curso " . $anioInicio . "/" . $anioFin,
            'nombre_corto' => $anioCorto . "/" . $anioSiguienteCorto,
            'anio_inicio' => $anioInicio,
            'anio_fin' => $anioFin
        ];
    }

    /**
     * Calcular el curso académico siguiente
     */
    private function calcularCursoAcademicoSiguiente()
    {
        $cursoActual = $this->calcularCursoAcademicoActual();
        $anioInicio = $cursoActual['anio_inicio'] + 1;
        $anioFin = $cursoActual['anio_fin'] + 1;
        
        $anioCorto = substr($anioInicio, -2);
        $anioSiguienteCorto = substr($anioFin, -2);
        
        return [
            'nombre_completo' => "Curso " . $anioInicio . "/" . $anioFin,
            'nombre_corto' => $anioCorto . "/" . $anioSiguienteCorto,
            'anio_inicio' => $anioInicio,
            'anio_fin' => $anioFin
        ];
    }    /**
     * Obtener información sobre los plazos de tutorías
     */
    private function obtenerInformacionPlazos()
    {
        $plazos = [];
        
        // Guardar la conexión original
        $conexionOriginal = config('database.default');
        
        // IMPORTANTE: Todos los plazos (incluidos los del curso siguiente) están en la BD del curso actual
        config(['database.default' => 'mysql']);
        
        try {
            $plazosEncontrados = Plazo::where('nombre_plazo', 'LIKE', '%TUTORIAS%')
                ->where('nombre_plazo', 'LIKE', '%CUATRIMESTRE%')
                ->get();
                
            foreach ($plazosEncontrados as $plazo) {
                $fechaActual = Carbon::now();
                $fechaInicio = Carbon::parse($plazo->fecha_inicio);
                $fechaFin = Carbon::parse($plazo->fecha_fin);
                
                // Determinar a qué conexión/curso pertenece según el nombre del plazo
                $conexion = str_contains($plazo->nombre_plazo, 'CURSO SIGUIENTE') ? 'mysql_proximo' : 'mysql';
                
                $plazos[] = [
                    'conexion' => $conexion,
                    'nombre' => $plazo->nombre_plazo,
                    'fecha_inicio' => $fechaInicio->format('d/m/Y'),
                    'fecha_fin' => $fechaFin->format('d/m/Y'),
                    'activo' => $fechaActual->between($fechaInicio, $fechaFin),
                    'dias_restantes' => $fechaActual->lt($fechaInicio) ? 
                        $fechaActual->diffInDays($fechaInicio) : 
                        ($fechaActual->lte($fechaFin) ? $fechaActual->diffInDays($fechaFin) : 0)
                ];
            }
        } catch (\Exception $e) {
            // Ignorar errores de conexión
        }
        
        // Restaurar conexión original
        config(['database.default' => $conexionOriginal]);
        
        return $plazos;
    }
}