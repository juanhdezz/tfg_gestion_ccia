<?php

namespace App\Http\Controllers;

use App\Models\Tutoria;
use App\Models\Despacho;
use App\Models\Plazo; // Añadimos el modelo Plazo
use App\Models\Usuario;
use App\Models\Miembro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class TutoriaController extends Controller
{
    /**
     * Calcular las horas de tutorías permitidas para un usuario
     * 
     * @param int|null $userId ID del usuario (si es null, usa el usuario autenticado)
     * @return float Número de horas permitidas
     */
    private function calcularHorasTutoriasPermitidas($userId = null)
    {
        $userId = $userId ?: Auth::id();
        
        // Buscar si el usuario tiene registro en la tabla miembro
        $miembro = Miembro::with('categoriaDocente')
            ->where('id_usuario', $userId)
            ->first();
        
        if ($miembro && $miembro->categoriaDocente && $miembro->categoriaDocente->creditos_docencia) {
            // Calcular horas como créditos_docencia / 3, con un máximo de 6 horas
            $horasCalculadas = $miembro->categoriaDocente->creditos_docencia / 3;
            $horasLimitadas = min($horasCalculadas, 6.0);
            
            // Redondear a números enteros o x.5
            $horasRedondeadas = round($horasLimitadas * 2) / 2;
            
            return $horasRedondeadas;
        }
        
        // Si no tiene registro en miembro o categoría docente, usar 6 horas por defecto
        return 6.0;
    }

    /**
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
     */    public function index(Request $request)
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

        // Verificar si el usuario es administrador
        $esAdmin = Auth::user()->hasRole('admin');
        
        $tutorias = collect(); // Inicializar como colección vacía
        $despachos = collect(); // Para usuarios normales
        $miembros = collect(); // Para administradores
        $despachoSeleccionado = null;
        $miembroSeleccionado = null;        if ($esAdmin) {
            // Para administradores: obtener todos los usuarios con despacho asignado
            $miembros = Usuario::with('despacho')
                ->whereNotNull('id_despacho')
                ->orderBy('apellidos')
                ->orderBy('nombre')
                ->get();
            
            // Obtener el usuario seleccionado
            $miembroSeleccionado = $request->input('miembro');
            
            if ($miembroSeleccionado) {
                $usuario = Usuario::find($miembroSeleccionado);
                if ($usuario && $usuario->id_despacho) {
                    $despachoSeleccionado = $usuario->id_despacho;
                }
            }
        } else {
            // Para usuarios normales: obtener todos los despachos
            $despachos = Despacho::all();
            // Obtener el despacho seleccionado (o usar el del usuario actual si existe)
            $despachoSeleccionado = $request->input('despacho', Auth::user()->id_despacho ?? null);
        }

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
        $diasSemana = [
            'Lunes' => 'Lunes',
            'Martes' => 'Martes', 
            'Miércoles' => 'Miércoles',
            'Jueves' => 'Jueves',
            'Viernes' => 'Viernes'
        ];        // Si hay un despacho seleccionado, cargar las tutorías existentes
        if ($despachoSeleccionado) {
            $tutorias = Tutoria::where('id_despacho', $despachoSeleccionado)
                ->where('cuatrimestre', $cuatrimestreSeleccionado)
                ->get();

            // Si no hay tutorías para este cuatrimestre, intentar copiar de otro cuatrimestre
            if ($tutorias->isEmpty()) {
                $this->copiarTutoriasDeOtroCuatrimestre($despachoSeleccionado, $cuatrimestreSeleccionado, $estaEnProximoCurso);
                
                // Recargar las tutorías después de la copia
                $tutorias = Tutoria::where('id_despacho', $despachoSeleccionado)
                    ->where('cuatrimestre', $cuatrimestreSeleccionado)
                    ->get();
            }
                
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
        }        // Calcular horas totales de las tutorías existentes
        $horasTotales = 0;
        if ($tutorias->count() > 0) {
            foreach ($tutorias as $tutoria) {
                // Cada slot de tutoría es de 30 minutos (0.5 horas)
                $horasTotales += 0.5;
            }
        }

        // Calcular las horas máximas permitidas para el usuario actual o seleccionado
        $userIdParaCalculo = $esAdmin && $miembroSeleccionado ? $miembroSeleccionado : Auth::id();
        $horasMaximasPermitidas = $this->calcularHorasTutoriasPermitidas($userIdParaCalculo);

        return view('tutorias.index', compact(
            'tutorias',
            'despachos',
            'miembros',
            'horas',
            'diasSemana',
            'cuatrimestreSeleccionado',
            'despachoSeleccionado',
            'miembroSeleccionado',
            'horasTotales',
            'horasMaximasPermitidas',
            'estaEnProximoCurso',
            'esAdmin'
        ));
    }

    /**
     * Actualizar todas las tutorías de una vez
     */    public function actualizar(Request $request)
    {
        $idDespacho = $request->input('id_despacho');
        $cuatrimestre = $request->input('cuatrimestre');
        $tutoriasData = $request->input('tutorias', []);        // Para administradores, obtener el ID de despacho desde el usuario seleccionado
        if (Auth::user()->hasRole('admin') && $request->has('miembro')) {
            $usuario = Usuario::find($request->input('miembro'));
            if ($usuario && $usuario->id_despacho) {
                $idDespacho = $usuario->id_despacho;
            }
        }

        // Verificar si estamos en próximo curso según la conexión actual
        $estaEnProximoCurso = Session::get('db_connection') === 'mysql_proximo';
        
        // Verificar si estamos dentro del plazo para editar tutorías
        $dentroDePlazo = $this->dentroDePlazo($cuatrimestre, $estaEnProximoCurso);

        if (!$dentroDePlazo) {
            return redirect()->back()->with('error', 'No se pueden modificar las tutorías fuera del plazo establecido');
        }        // Contar las horas totales seleccionadas (cada slot son 30 minutos = 0.5 horas)
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

        // Calcular las horas máximas permitidas para el usuario actual o seleccionado
        $userIdParaCalculo = Auth::user()->hasRole('admin') && $request->has('miembro') ? 
            $request->input('miembro') : Auth::id();
        $horasMaximasPermitidas = $this->calcularHorasTutoriasPermitidas($userIdParaCalculo);

        // Validar que no exceda las horas máximas permitidas
        if ($horasSeleccionadas > $horasMaximasPermitidas) {
            return redirect()->back()->with('error', 'No puede seleccionar más de ' . $horasMaximasPermitidas . ' horas de tutorías. Ha seleccionado ' . $horasSeleccionadas . ' horas.');
        }

        // Validar que tenga exactamente las horas permitidas
        if ($horasSeleccionadas != $horasMaximasPermitidas) {
            return redirect()->back()->with('error', 'Debe seleccionar exactamente ' . $horasMaximasPermitidas . ' horas de tutorías. Ha seleccionado ' . $horasSeleccionadas . ' horas.');
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

        // Preparar parámetros para la redirección
        $parametros = [
            'despacho' => $idDespacho,
            'cuatrimestre' => $cuatrimestre
        ];
        
        // Si es admin, agregar el parámetro del miembro
        if (Auth::user()->hasRole('admin') && $request->has('miembro')) {
            $parametros['miembro'] = $request->input('miembro');
        }

        // Redirigir a la vista de visualización
        return redirect()->route('tutorias.ver', $parametros)
            ->with('success', 'Horario de tutorías actualizado correctamente');
    }

    /**
     * Ver las tutorías guardadas (vista de solo lectura)
     */    public function verTutorias(Request $request)
    {
        // Determinar el cuatrimestre actual basado en la fecha
        $mes = Carbon::now()->month;
        $cuatrimestreActual = ($mes >= 9 || $mes <= 2) ? 1 : 2;

        // Obtener el cuatrimestre seleccionado (o usar el actual)
        $cuatrimestreSeleccionado = $request->input('cuatrimestre', $cuatrimestreActual);

        // Verificar si el usuario es administrador
        $esAdmin = Auth::user()->hasRole('admin');
        
        $despachos = collect();
        $miembros = collect();
        $despachoSeleccionado = null;
        $miembroSeleccionado = null;        if ($esAdmin) {
            // Para administradores: obtener todos los usuarios con despacho asignado
            $miembros = Usuario::with('despacho')
                ->whereNotNull('id_despacho')
                ->orderBy('apellidos')
                ->orderBy('nombre')
                ->get();
            
            // Obtener el usuario seleccionado
            $miembroSeleccionado = $request->input('miembro');
            
            if ($miembroSeleccionado) {
                $usuario = Usuario::find($miembroSeleccionado);
                if ($usuario && $usuario->id_despacho) {
                    $despachoSeleccionado = $usuario->id_despacho;
                }
            } else {
                // Si no hay miembro seleccionado pero hay despacho (para redirecciones)
                $despachoSeleccionado = $request->input('despacho');
            }
        } else {
            // Para usuarios normales
            $despachos = Despacho::all();
            $despachoSeleccionado = $request->input('despacho', Auth::user()->id_despacho ?? null);
        }

        // Verificar si estamos en próximo curso según la conexión actual
        $estaEnProximoCurso = Session::get('db_connection') === 'mysql_proximo';
        
        // Verificar si estamos dentro del plazo para editar tutorías
        $dentroDePlazo = $this->dentroDePlazo($cuatrimestreSeleccionado, $estaEnProximoCurso);        // Obtener tutorias para el despacho y cuatrimestre seleccionados
        $tutorias = collect();
        if ($despachoSeleccionado) {
            $tutorias = Tutoria::where('id_despacho', $despachoSeleccionado)
                ->where('cuatrimestre', $cuatrimestreSeleccionado)
                ->get();

            // Si no hay tutorías para este cuatrimestre, intentar copiar de otro cuatrimestre
            if ($tutorias->isEmpty()) {
                $this->copiarTutoriasDeOtroCuatrimestre($despachoSeleccionado, $cuatrimestreSeleccionado, $estaEnProximoCurso);
                
                // Recargar las tutorías después de la copia
                $tutorias = Tutoria::where('id_despacho', $despachoSeleccionado)
                    ->where('cuatrimestre', $cuatrimestreSeleccionado)
                    ->get();
            }

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
        }

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
        }        // Definir los días de la semana
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        // Calcular las horas máximas permitidas para el usuario actual o seleccionado
        $userIdParaCalculo = $esAdmin && $miembroSeleccionado ? $miembroSeleccionado : Auth::id();
        $horasMaximasPermitidas = $this->calcularHorasTutoriasPermitidas($userIdParaCalculo);

        // Calcular horas totales de las tutorías existentes
        $horasTotales = 0;
        if ($tutorias->count() > 0) {
            foreach ($tutorias as $tutoria) {
                // Cada slot de tutoría es de 30 minutos (0.5 horas)
                $horasTotales += 0.5;
            }
        }

        return view('tutorias.ver', compact(
            'tutorias',
            'despachos',
            'miembros',
            'horas',
            'diasSemana',
            'cuatrimestreActual',            'cuatrimestreSeleccionado',
            'despachoSeleccionado',
            'miembroSeleccionado',
            'horasMaximasPermitidas',
            'horasTotales',
            'dentroDePlazo',
            'estaEnProximoCurso',
            'esAdmin'
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

    /**
     * Copiar tutorías de otro cuatrimestre cuando no existan para el cuatrimestre actual
     * 
     * @param int $idDespacho El ID del despacho
     * @param int $cuatrimestreActual El cuatrimestre que no tiene tutorías
     * @param bool $estaEnProximoCurso Si estamos en el próximo curso
     */
    private function copiarTutoriasDeOtroCuatrimestre($idDespacho, $cuatrimestreActual, $estaEnProximoCurso)
    {
        // Prioridad de búsqueda:
        // 1. Mismo curso, otro cuatrimestre
        // 2. Curso anterior/siguiente, mismo cuatrimestre
        // 3. Curso anterior/siguiente, otro cuatrimestre

        $conexionActual = config('database.default');
        $tutoriasParaCopiar = null;
        $origenCopiado = '';

        try {
            // 1. Buscar en el mismo curso, otro cuatrimestre
            $otroCuatrimestre = $cuatrimestreActual == 1 ? 2 : 1;
            $tutoriasParaCopiar = Tutoria::where('id_despacho', $idDespacho)
                ->where('cuatrimestre', $otroCuatrimestre)
                ->get();

            if ($tutoriasParaCopiar->isNotEmpty()) {
                $origenCopiado = "mismo curso, cuatrimestre $otroCuatrimestre";
            } else {
                // 2. Buscar en el otro curso, mismo cuatrimestre
                $otraConexion = $estaEnProximoCurso ? 'mysql' : 'mysql_proximo';
                
                // Cambiar temporalmente la conexión
                config(['database.default' => $otraConexion]);
                
                $tutoriasParaCopiar = Tutoria::where('id_despacho', $idDespacho)
                    ->where('cuatrimestre', $cuatrimestreActual)
                    ->get();

                if ($tutoriasParaCopiar->isNotEmpty()) {
                    $nombreCurso = $estaEnProximoCurso ? 'curso anterior' : 'próximo curso';
                    $origenCopiado = "$nombreCurso, cuatrimestre $cuatrimestreActual";
                } else {
                    // 3. Buscar en el otro curso, otro cuatrimestre
                    $tutoriasParaCopiar = Tutoria::where('id_despacho', $idDespacho)
                        ->where('cuatrimestre', $otroCuatrimestre)
                        ->get();

                    if ($tutoriasParaCopiar->isNotEmpty()) {
                        $nombreCurso = $estaEnProximoCurso ? 'curso anterior' : 'próximo curso';
                        $origenCopiado = "$nombreCurso, cuatrimestre $otroCuatrimestre";
                    }
                }

                // Restaurar la conexión original
                config(['database.default' => $conexionActual]);
            }

            // Si encontramos tutorías para copiar, crear las nuevas
            if ($tutoriasParaCopiar && $tutoriasParaCopiar->isNotEmpty()) {
                foreach ($tutoriasParaCopiar as $tutoriaOriginal) {
                    Tutoria::create([
                        'id_usuario' => $tutoriaOriginal->id_usuario,
                        'id_despacho' => $idDespacho,
                        'cuatrimestre' => $cuatrimestreActual,
                        'dia' => $tutoriaOriginal->dia,
                        'inicio' => $tutoriaOriginal->inicio,
                        'fin' => $tutoriaOriginal->fin
                    ]);
                }

                // Guardar información de la copia en la sesión para mostrar al usuario
                Session::flash('info', "Se han copiado automáticamente las tutorías del $origenCopiado. Puede modificarlas según sus necesidades.");
            }

        } catch (\Exception $e) {
            // Si hay cualquier error, simplemente no hacer nada
            // El usuario verá que no hay tutorías y podrá configurarlas manualmente
            config(['database.default' => $conexionActual]);
        }
    }
}