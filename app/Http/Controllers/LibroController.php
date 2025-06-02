<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario; // Modelo para usuarios
use App\Models\LibroAsignatura;
use App\Models\Libro;
use App\Models\Asignatura;
use App\Models\LibroProyecto;
use App\Models\LibroOtro;
use App\Models\LibroGrupo; // Modelo para libros con cargo a grupo de investigación
use App\Models\LibroPosgrado; // Modelo para libros con cargo a posgrado
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Proyecto;
use App\Models\Grupo; // Modelo para grupos de investigación
use App\Models\Posgrado; // Modelo para programas de posgrado
use App\Mail\Notification;
use Illuminate\Support\Facades\Mail;
use App\Traits\SolicitudLibroTrait; // Trait para lógica común de solicitudes de libros
use App\Models\BaseModel; // Modelo base para manejar conexiones dinámicas



class LibroController extends Controller
{
    /**
     * Muestra un listado de los libros solicitados
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Consulta existente para LibroAsignatura
        $query = LibroAsignatura::with(['libro', 'usuario', 'asignatura']);
        
        // Filtrar por término de búsqueda
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('libro', function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('autor', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            })->orWhereHas('asignatura', function($q) use ($search) {
                $q->where('nombre_asignatura', 'like', "%{$search}%");
            })->orWhereHas('usuario', function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%");
            });
        }
        
        // Filtrar por estado
        if ($request->has('estado') && !empty($request->estado)) {
            $query->where('estado', $request->estado);
        }
        
        // Ordenar por fecha de solicitud, de más reciente a más antigua
        $query->orderBy('fecha_solicitud', 'desc');
        
        // Paginar los resultados (10 por página)
        $librosAsignatura = $query->paginate(10)->appends(request()->query());
        
        // Consulta para LibroProyecto
        $queryProyecto = LibroProyecto::with(['libro', 'proyecto', 'usuario']);
        
        // Filtrar por término de búsqueda para LibroProyecto
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $queryProyecto->whereHas('libro', function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('autor', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            })->orWhereHas('proyecto', function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%");
            })->orWhereHas('usuario', function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%");
            });
        }
        
        // Filtrar por estado para LibroProyecto
        if ($request->has('estado') && !empty($request->estado)) {
            $queryProyecto->where('estado', $request->estado);
        }
        
        // Ordenar y paginar
        $queryProyecto->orderBy('fecha_solicitud', 'desc');
        $librosProyecto = $queryProyecto->paginate(10)->appends(request()->query());
        
        // Consulta para LibroOtro
        $queryOtro = LibroOtro::with(['libro', 'usuario']);
        
        // Filtrar por término de búsqueda para LibroOtro
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $queryOtro->whereHas('libro', function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('autor', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            })->orWhereHas('usuario', function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%");
            });
        }
        
        // Filtrar por estado para LibroOtro
        if ($request->has('estado') && !empty($request->estado)) {
            $queryOtro->where('estado', $request->estado);
        }
        
        // Ordenar y paginar
        $queryOtro->orderBy('fecha_solicitud', 'desc');
        $librosOtros = $queryOtro->paginate(10)->appends(request()->query());
        
        // NUEVO: Consulta para LibroGrupo (Grupo de Investigación)
        $queryGrupo = LibroGrupo::with(['libro', 'usuario', 'grupo']);
        
        // Filtrar por término de búsqueda para LibroGrupo
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $queryGrupo->whereHas('libro', function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('autor', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            })->orWhereHas('usuario', function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%");
            });
        }
        
        // Filtrar por estado para LibroGrupo
        if ($request->has('estado') && !empty($request->estado)) {
            $queryGrupo->where('estado', $request->estado);
        }
        
        // Ordenar y paginar
        $queryGrupo->orderBy('fecha_solicitud', 'desc');
        $librosGrupo = $queryGrupo->paginate(10)->appends(request()->query());
        
        // NUEVO: Consulta para LibroPosgrado
        $queryPosgrado = LibroPosgrado::with(['libro', 'usuario']);
        
        // Filtrar por término de búsqueda para LibroPosgrado
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $queryPosgrado->whereHas('libro', function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('autor', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            })->orWhereHas('usuario', function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%");
            });
        }
        
        // Filtrar por estado para LibroPosgrado
        if ($request->has('estado') && !empty($request->estado)) {
            $queryPosgrado->where('estado', $request->estado);
        }
        
        // Ordenar y paginar
        $queryPosgrado->orderBy('fecha_solicitud', 'desc');
        $librosPosgrado = $queryPosgrado->paginate(10)->appends(request()->query());
          // Obtener todos los estados posibles para el filtro
        $estados = [
            'Pendiente Aceptación',
            'Pedido',
            'Aceptado',
            'Denegado',
            'Recibido',
            'Biblioteca',
            'Agotado/Descatalogado'
        ];

        // Verificar si el usuario actual es de dirección
        $esDirector = true; //Auth::user()->esDirectorDepartamento();

        return view('libros.index', compact(
            'librosAsignatura', 
            'librosProyecto', 
            'librosOtros', 
            'librosGrupo', 
            'librosPosgrado', 
            'estados', 
            'esDirector'
        ));
    }

/**
 * Muestra el formulario para crear una nueva solicitud de libro
 *
 * @return \Illuminate\Http\Response
 */
public function create()
{
    // Obtener todas las asignaturas para el formulario
    $asignaturas = Asignatura::orderBy('nombre_asignatura')->get();
    
    // Para el formulario de solicitud completo
    $proyectos = Proyecto::orderBy('titulo')->get();
    
    // Cargar los grupos de investigación
    $grupos = Grupo::orderBy('nombre_grupo')->get();
    
    // Cargar los programas de posgrado
    $posgrados = Posgrado::orderBy('nombre')->get();
    
    return view('libros.create', compact('asignaturas', 'proyectos', 'grupos', 'posgrados'));
}

    /**
     * Almacena una nueva solicitud de libro en la base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function store(Request $request)
{
    // Validación básica de libro (común para todos los tipos)
    $validatedLibro = $request->validate([
        'titulo' => 'required|string|max:255',
        'autor' => 'required|string|max:255',
        'editorial' => 'required|string|max:255',
        'isbn' => 'required|string|max:20',
        'precio' => 'required|numeric|min:0',
        'num_ejemplares' => 'required|integer|min:1',
        'tipo_solicitud' => 'required|in:asignatura,proyecto,investigacion,posgrado,otros,grupo',
        'justificacion' => 'required|string',
    ]);
    
    try {
        // Determinar la conexión de base de datos (solo para asignaturas)
        $conexion = 'mysql'; // Por defecto
        if ($request->tipo_solicitud === 'asignatura' && $request->curso_academico === 'mysql_proximo') {
            $conexion = 'mysql_proximo';
        }
        
        // Usar DB facade para garantizar que se use la conexión correcta
        $libroData = [
            'isbn' => $request->isbn,
            'titulo' => $request->titulo,
            'autor' => $request->autor,
            'editorial' => $request->editorial,
            'edicion' => $request->edicion ?? null,
            'year' => $request->year ?? date('Y'),
            'num_paginas' => $request->num_paginas ?? null,
            'portada' => $request->portada ?? null,
            'website' => $request->website ?? null,
        ];
        
        // Buscar libro existente en la conexión correcta
        $libroExistente = DB::connection($conexion)
            ->table('libro')
            ->where('isbn', $request->isbn)
            ->first();
        
        if ($libroExistente) {
            $idLibro = $libroExistente->id_libro;
        } else {
            // Insertar nuevo libro en la conexión correcta
            $idLibro = DB::connection($conexion)
                ->table('libro')
                ->insertGetId($libroData);
        }
        
        // Crear el objeto libro para usar en las relaciones
        $libro = new Libro();
        $libro->setConnection($conexion);
        $libro->id_libro = $idLibro;
        $libro->fill($libroData);
        $libro->exists = true; // Marcar como existente para evitar intentos de guardado
        
        // Procesar según el tipo de solicitud
        switch ($request->tipo_solicitud) {
            case 'asignatura':
                // Validación adicional para asignatura
                $request->validate([
                    'id_asignatura' => 'required|exists:asignatura,id_asignatura'
                ]);
                
                // Crear solicitud para asignatura en la BD correspondiente
                $solicitud = new LibroAsignatura();
                $solicitud->setConnection($conexion);
                $solicitud->id_libro = $libro->id_libro;
                $solicitud->id_usuario = Auth::id();
                $solicitud->id_asignatura = $request->id_asignatura;
                $solicitud->precio = $request->precio;
                $solicitud->num_ejemplares = $request->num_ejemplares;
                $solicitud->estado = 'Pendiente Aceptación';
                $solicitud->justificacion = $request->justificacion;
                $solicitud->observaciones = $request->observaciones;
                $solicitud->fecha_solicitud = Carbon::now();
                $solicitud->save();
                
                $tipo = 'asignatura';
                $bdInfo = $conexion === 'mysql_proximo' ? ' (próximo curso académico)' : ' (curso académico actual)';
                break;
                
            case 'proyecto':
                // Validación adicional para proyecto
                $request->validate([
                    'id_proyecto' => 'required|exists:proyecto,id_proyecto',
                ]);
                
                // Crear solicitud para proyecto (siempre en BD principal)
                $solicitud = new LibroProyecto();
                $solicitud->id_libro = $libro->id_libro;
                $solicitud->id_usuario = Auth::id();
                $solicitud->id_proyecto = $request->id_proyecto;
                $solicitud->precio = $request->precio;
                $solicitud->num_ejemplares = $request->num_ejemplares;
                $solicitud->estado = 'Pendiente Aceptación';
                $solicitud->justificacion = $request->justificacion;
                $solicitud->observaciones = $request->observaciones;
                $solicitud->fecha_solicitud = Carbon::now();
                $solicitud->save();
                
                $tipo = 'proyecto';
                $bdInfo = '';
                break;
            
            case 'grupo':
                // Validación adicional para grupo de investigación
                $request->validate([
                    'id_grupo' => 'required',
                ]);
                
                // Crear solicitud para grupo de investigación (siempre en BD principal)
                $solicitud = new LibroGrupo();
                $solicitud->id_libro = $libro->id_libro;
                $solicitud->id_usuario = Auth::id();
                $solicitud->id_grupo = $request->id_grupo;
                $solicitud->precio = $request->precio;
                $solicitud->num_ejemplares = $request->num_ejemplares;
                $solicitud->estado = 'Pendiente Aceptación';
                $solicitud->justificacion = $request->justificacion;
                $solicitud->observaciones = $request->observaciones;
                $solicitud->fecha_solicitud = Carbon::now();
                $solicitud->save();
                
                $tipo = 'grupo';
                $bdInfo = '';
                break;
            
            case 'posgrado':
                // Validación adicional para posgrado
                $request->validate([
                    'id_posgrado' => 'required',
                ]);
                
                // Crear solicitud para posgrado (siempre en BD principal)
                $solicitud = new LibroPosgrado();
                $solicitud->id_libro = $libro->id_libro;
                $solicitud->id_usuario = Auth::id();
                $solicitud->id_posgrado = $request->id_posgrado;
                $solicitud->precio = $request->precio;
                $solicitud->num_ejemplares = $request->num_ejemplares;
                $solicitud->estado = 'Pendiente Aceptación';
                $solicitud->justificacion = $request->justificacion;
                $solicitud->observaciones = $request->observaciones;
                $solicitud->fecha_solicitud = Carbon::now();
                $solicitud->save();
                
                $tipo = 'posgrado';
                $bdInfo = '';
                break;
            
            case 'otros':
                // Validación adicional para otros
                $request->validate([
                    'descripcion_otros' => 'required|string',
                ]);
                
                // Crear solicitud para otros (siempre en BD principal)
                $solicitud = new LibroOtro();
                $solicitud->id_libro = $libro->id_libro;
                $solicitud->id_usuario = Auth::id();
                $solicitud->descripcion_otros = $request->descripcion_otros; // Agregar este campo que faltaba
                $solicitud->precio = $request->precio;
                $solicitud->num_ejemplares = $request->num_ejemplares;
                $solicitud->estado = 'Pendiente Aceptación';
                $solicitud->justificacion = $request->justificacion;
                $solicitud->observaciones = $request->observaciones;
                $solicitud->fecha_solicitud = Carbon::now();
                $solicitud->save();
                
                $tipo = 'otros';
                $bdInfo = '';
                break;
                
            default:
                return back()->withInput()->withErrors(['error' => 'Tipo de solicitud no válido']);
        }
        
        return redirect()->route('libros.index')
            ->with('success', "Solicitud de libro con cargo a {$tipo} creada correctamente{$bdInfo}. Estado: Pendiente Aceptación");
            
    } catch (\Exception $e) {
        // Registra el error para poder depurarlo
        Log::error('Error al guardar la solicitud de libro: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return back()->withInput()->withErrors(['error' => 'Ha ocurrido un error al guardar la solicitud: ' . $e->getMessage()]);
    }
}

    /**
     * Aprobar una solicitud de libro
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id_libro
     * @param  int  $id_usuario
     * @param  string  $fecha_solicitud
     * @return \Illuminate\Http\Response
     */
    public function aprobar(Request $request, $id_libro, $id_usuario, $fecha_solicitud, $tipo = null)
{
    try {
        $fecha = Carbon::parse($fecha_solicitud)->format('Y-m-d');
        $id_libro = (int) $id_libro;
        $id_usuario = (int) $id_usuario;

        Log::debug('Intentando aprobar solicitud', [
            'id_libro' => $id_libro,
            'id_usuario' => $id_usuario,
            'fecha' => $fecha,
            'tipo' => $tipo
        ]);

        $actualizado = 0;
        $tipoSolicitud = '';

        if ($tipo === 'proyecto' || ($request->has('tipo') && $request->tipo === 'proyecto')) {
            $actualizado = LibroProyecto::where('id_libro', $id_libro)
                ->where('id_usuario', $id_usuario)
                ->whereDate('fecha_solicitud', $fecha)
                ->update([
                    'estado' => 'Aceptado',
                    'fecha_aceptado_denegado' => Carbon::now(),
                    'fecha_pedido' => Carbon::now()
                ]);
            $tipoSolicitud = 'proyecto';
        } elseif ($tipo === 'grupo' || ($request->has('tipo') && $request->tipo === 'grupo')) {
            $actualizado = LibroGrupo::where('id_libro', $id_libro)
                ->where('id_usuario', $id_usuario)
                ->whereDate('fecha_solicitud', $fecha)
                ->update([
                    'estado' => 'Aceptado',
                    'fecha_aceptado_denegado' => Carbon::now(),
                    'fecha_pedido' => Carbon::now()
                ]);
            $tipoSolicitud = 'grupo';
        } elseif ($tipo === 'posgrado' || ($request->has('tipo') && $request->tipo === 'posgrado')) {
            $actualizado = LibroPosgrado::where('id_libro', $id_libro)
                ->where('id_usuario', $id_usuario)
                ->whereDate('fecha_solicitud', $fecha)
                ->update([
                    'estado' => 'Aceptado',
                    'fecha_aceptado_denegado' => Carbon::now(),
                    'fecha_pedido' => Carbon::now()
                ]);
            $tipoSolicitud = 'posgrado';
        } elseif ($tipo === 'otros' || ($request->has('tipo') && $request->tipo === 'otros')) {
            $actualizado = LibroOtro::where('id_libro', $id_libro)
                ->where('id_usuario', $id_usuario)
                ->whereDate('fecha_solicitud', $fecha)
                ->update([
                    'estado' => 'Aceptado',
                    'fecha_aceptado_denegado' => Carbon::now(),
                    'fecha_pedido' => Carbon::now()
                ]);
            $tipoSolicitud = 'otros';
        } else {
            $actualizado = LibroAsignatura::where('id_libro', $id_libro)
                ->where('id_usuario', $id_usuario)
                ->whereDate('fecha_solicitud', $fecha)
                ->update([
                    'estado' => 'Aceptado',
                    'fecha_aceptado_denegado' => Carbon::now(),
                    'fecha_pedido' => Carbon::now()
                ]);
            $tipoSolicitud = 'asignatura';
        }

        if ($actualizado === 0) {
            if ($tipoSolicitud !== 'proyecto') {
                $actualizado = LibroProyecto::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Aceptado',
                        'fecha_aceptado_denegado' => Carbon::now(),
                        'fecha_pedido' => Carbon::now()
                    ]);
                if ($actualizado > 0) {
                    $tipoSolicitud = 'proyecto';
                }
            }

            if ($actualizado === 0 && $tipoSolicitud !== 'grupo') {
                $actualizado = LibroGrupo::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Aceptado',
                        'fecha_aceptado_denegado' => Carbon::now(),
                        'fecha_pedido' => Carbon::now()
                    ]);
                if ($actualizado > 0) {
                    $tipoSolicitud = 'grupo';
                }
            }

            if ($actualizado === 0 && $tipoSolicitud !== 'posgrado') {
                $actualizado = LibroPosgrado::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Aceptado',
                        'fecha_aceptado_denegado' => Carbon::now(),
                        'fecha_pedido' => Carbon::now()
                    ]);
                if ($actualizado > 0) {
                    $tipoSolicitud = 'posgrado';
                }
            }

            if ($actualizado === 0 && $tipoSolicitud !== 'otros') {
                $actualizado = LibroOtro::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Aceptado',
                        'fecha_aceptado_denegado' => Carbon::now(),
                        'fecha_pedido' => Carbon::now()
                    ]);
                if ($actualizado > 0) {
                    $tipoSolicitud = 'otros';
                }
            }

            if ($actualizado === 0 && $tipoSolicitud !== 'asignatura') {
                $actualizado = LibroAsignatura::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Aceptado',
                        'fecha_aceptado_denegado' => Carbon::now(),
                        'fecha_pedido' => Carbon::now()
                    ]);
                if ($actualizado > 0) {
                    $tipoSolicitud = 'asignatura';
                }
            }

            if ($actualizado === 0) {
                Log::warning('Intento de aprobar una solicitud inexistente', [
                    'id_libro' => $id_libro,
                    'id_usuario' => $id_usuario,
                    'fecha_solicitud' => $fecha_solicitud
                ]);

                return redirect()->route('libros.index')
                    ->with('error', 'No se encontró la solicitud para aprobar');
            }
        }

        $usuario = Usuario::find($id_usuario);

        if ($usuario) {
            $libro = Libro::find($id_libro);

            try {
                Mail::to('jhernandezsanchezagesta@gmail.com')->send(new Notification($usuario, $libro, 'Aceptado'));
                Log::info("Correo de aprobación enviado exitosamente a {$usuario->email} para libro con ID: {$id_libro}");
            } catch (\Exception $e) {
                Log::error("Error al enviar correo de aprobación a {$usuario->email}: " . $e->getMessage());
            }
        }

        return redirect()->route('libros.index')
            ->with('success', "Solicitud de libro con cargo a {$tipoSolicitud} aprobada correctamente");

    } catch (\Exception $e) {
        Log::error('Error al aprobar la solicitud: ' . $e->getMessage());

        return view('error.error', [
            'errorMessage' => 'No se pudo aprobar la solicitud de libro.',
        ]);
    }
}


    /**
     * Denegar una solicitud de libro
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id_libro
     * @param  int  $id_usuario
     * @param  string  $fecha_solicitud
     * @param  string  $tipo
     * @return \Illuminate\Http\Response
     */
    public function denegar(Request $request, $id_libro, $id_usuario, $fecha_solicitud, $tipo = null)
    {
        try {
            // Convertir la fecha a un formato adecuado para la consulta
            $fecha = Carbon::parse($fecha_solicitud)->format('Y-m-d');
            
            // Convertir a tipos adecuados
            $id_libro = (int) $id_libro;
            $id_usuario = (int) $id_usuario;
            
            // Debug log para verificar los valores
            Log::debug('Intentando denegar solicitud', [
                'id_libro' => $id_libro,
                'id_usuario' => $id_usuario,
                'fecha' => $fecha,
                'tipo' => $tipo
            ]);
            
            // Intentar actualizar según el tipo
            $actualizado = 0;
            $tipoSolicitud = '';

            // Si se especifica el tipo, intentar actualizar ese tipo primero
            if ($tipo === 'proyecto' || ($request->has('tipo') && $request->tipo === 'proyecto')) {
                $actualizado = LibroProyecto::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Denegado',
                        'fecha_aceptado_denegado' => Carbon::now(),
                        'observaciones' => $request->observaciones
                    ]);
                    
                $tipoSolicitud = 'proyecto';
            } elseif ($tipo === 'grupo' || ($request->has('tipo') && $request->tipo === 'grupo')) {
                $actualizado = LibroGrupo::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Denegado',
                        'fecha_aceptado_denegado' => Carbon::now(),
                        'observaciones' => $request->observaciones
                    ]);
                    
                $tipoSolicitud = 'grupo';
            } elseif ($tipo === 'posgrado' || ($request->has('tipo') && $request->tipo === 'posgrado')) {
                $actualizado = LibroPosgrado::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Denegado',
                        'fecha_aceptado_denegado' => Carbon::now(),
                        'observaciones' => $request->observaciones
                    ]);
                    
                $tipoSolicitud = 'posgrado';
            } elseif ($tipo === 'otros' || ($request->has('tipo') && $request->tipo === 'otros')) {
                $actualizado = LibroOtro::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Denegado',
                        'fecha_aceptado_denegado' => Carbon::now(),
                        'observaciones' => $request->observaciones
                    ]);
                    
                $tipoSolicitud = 'otros fondos';
            } else {
                // Si no se especifica tipo o es 'asignatura', intentar primero con asignatura
                $actualizado = LibroAsignatura::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Denegado',
                        'fecha_aceptado_denegado' => Carbon::now(),
                        'observaciones' => $request->observaciones
                    ]);
                
                $tipoSolicitud = 'asignatura';
            }

            // Si no se actualizó ningún registro, intentar con los otros tipos
            if ($actualizado === 0) {
                // Probar con todos los tipos restantes en un orden específico
                if ($tipoSolicitud !== 'proyecto') {
                    $actualizado = LibroProyecto::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Denegado',
                            'fecha_aceptado_denegado' => Carbon::now(),
                            'observaciones' => $request->observaciones
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'proyecto';
                    }
                }
                
                if ($actualizado === 0 && $tipoSolicitud !== 'grupo') {
                    $actualizado = LibroGrupo::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Denegado',
                            'fecha_aceptado_denegado' => Carbon::now(),
                            'observaciones' => $request->observaciones
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'grupo';
                    }
                }
                
                if ($actualizado === 0 && $tipoSolicitud !== 'posgrado') {
                    $actualizado = LibroPosgrado::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Denegado',
                            'fecha_aceptado_denegado' => Carbon::now(),
                            'observaciones' => $request->observaciones
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'posgrado';
                    }
                }
                
                if ($actualizado === 0 && $tipoSolicitud !== 'otros') {
                    $actualizado = LibroOtro::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Denegado',
                            'fecha_aceptado_denegado' => Carbon::now(),
                            'observaciones' => $request->observaciones
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'otros';
                    }
                }
                
                if ($actualizado === 0 && $tipoSolicitud !== 'asignatura') {
                    $actualizado = LibroAsignatura::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Denegado',
                            'fecha_aceptado_denegado' => Carbon::now(),
                            'observaciones' => $request->observaciones
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'asignatura';
                    }
                }
                
                if ($actualizado === 0) {
                    Log::warning('Intento de denegar una solicitud inexistente', [
                        'id_libro' => $id_libro,
                        'id_usuario' => $id_usuario,
                        'fecha_solicitud' => $fecha_solicitud
                    ]);
                    
                    return redirect()->route('libros.index')
                        ->with('error', 'No se encontró la solicitud para denegar');
                }
            }

            return redirect()->route('libros.index')
                ->with('success', "Solicitud de libro con cargo a {$tipoSolicitud} denegada correctamente");
                
        } catch (\Exception $e) {
            Log::error('Error al denegar la solicitud: ' . $e->getMessage());
            
            return view('error.error', [
                'errorMessage' => 'No se pudo denegar la solicitud de libro.'
            ]);
        }
    }

    /**
     * Marcar un libro como recibido
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id_libro
     * @param  int  $id_usuario
     * @param  string  $fecha_solicitud
     * @param  string  $tipo
     * @return \Illuminate\Http\Response
     */
    public function recibir(Request $request, $id_libro, $id_usuario, $fecha_solicitud, $tipo = null)
    {
        try {
            // Convertir la fecha a un formato adecuado para la consulta
            $fecha = Carbon::parse($fecha_solicitud)->format('Y-m-d');
            
            // Convertir a tipos adecuados
            $id_libro = (int) $id_libro;
            $id_usuario = (int) $id_usuario;
            
            // Debug log para verificar los valores
            Log::debug('Intentando marcar como recibido', [
                'id_libro' => $id_libro,
                'id_usuario' => $id_usuario,
                'fecha' => $fecha,
                'tipo' => $tipo
            ]);
            
            // Intentar actualizar según el tipo
            $actualizado = 0;
            $tipoSolicitud = '';

            // Si se especifica el tipo, intentar actualizar ese tipo primero
            if ($tipo === 'proyecto' || ($request->has('tipo') && $request->tipo === 'proyecto')) {
                $actualizado = LibroProyecto::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Recibido',
                        'fecha_recepcion' => Carbon::now()
                    ]);
                    
                $tipoSolicitud = 'proyecto';
            } elseif ($tipo === 'grupo' || ($request->has('tipo') && $request->tipo === 'grupo')) {
                $actualizado = LibroGrupo::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Recibido',
                        'fecha_recepcion' => Carbon::now()
                    ]);
                    
                $tipoSolicitud = 'grupo';
            } elseif ($tipo === 'posgrado' || ($request->has('tipo') && $request->tipo === 'posgrado')) {
                $actualizado = LibroPosgrado::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Recibido',
                        'fecha_recepcion' => Carbon::now()
                    ]);
                
                $tipoSolicitud = 'posgrado';
            } elseif ($tipo === 'otros' || ($request->has('tipo') && $request->tipo === 'otros')) {
                $actualizado = LibroOtro::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Recibido',
                        'fecha_recepcion' => Carbon::now()
                    ]);
                
                $tipoSolicitud = 'otros fondos';
            } else {
                // Si no se especifica tipo o es 'asignatura', intentar primero con asignatura
                $actualizado = LibroAsignatura::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Recibido',
                        'fecha_recepcion' => Carbon::now()
                    ]);
                
                $tipoSolicitud = 'asignatura';
            }

            // Si no se actualizó ningún registro, intentar con los otros tipos
            if ($actualizado === 0) {
                // Probar con todos los tipos restantes en un orden específico
                if ($tipoSolicitud !== 'proyecto') {
                    $actualizado = LibroProyecto::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Recibido',
                            'fecha_recepcion' => Carbon::now()
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'proyecto';
                    }
                }
                
                if ($actualizado === 0 && $tipoSolicitud !== 'grupo') {
                    $actualizado = LibroGrupo::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Recibido',
                            'fecha_recepcion' => Carbon::now()
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'grupo de investigación';
                    }
                }
                
                if ($actualizado === 0 && $tipoSolicitud !== 'posgrado') {
                    $actualizado = LibroPosgrado::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Recibido',
                            'fecha_recepcion' => Carbon::now()
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'posgrado';
                    }
                }
                
                if ($actualizado === 0 && $tipoSolicitud !== 'otros fondos') {
                    $actualizado = LibroOtro::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Recibido',
                            'fecha_recepcion' => Carbon::now()
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'otros fondos';
                    }
                }
                
                if ($actualizado === 0 && $tipoSolicitud !== 'asignatura') {
                    $actualizado = LibroAsignatura::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Recibido',
                            'fecha_recepcion' => Carbon::now()
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'asignatura';
                    }
                }
                
                if ($actualizado === 0) {
                    Log::warning('Intento de marcar como recibida una solicitud inexistente', [
                        'id_libro' => $id_libro,
                        'id_usuario' => $id_usuario,
                        'fecha_solicitud' => $fecha_solicitud
                    ]);
                    
                    return redirect()->route('libros.index')
                        ->with('error', 'No se encontró la solicitud para marcar como recibida');
                }
            }

            return redirect()->route('libros.index')
                ->with('success', "Libro con cargo a {$tipoSolicitud} marcado como recibido correctamente");
                
        } catch (\Exception $e) {
            Log::error('Error al marcar libro como recibido: ' . $e->getMessage());
            
            return view('error.error', [
                'errorMessage' => 'No se pudo marcar el libro como recibido.'
            ]);
        }
    }

    /**
     * Marcar un libro como disponible en biblioteca
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id_libro
     * @param  int  $id_usuario
     * @param  string  $fecha_solicitud
     * @param  string  $tipo
     * @return \Illuminate\Http\Response
     */
    public function marcarComoBiblioteca(Request $request, $id_libro, $id_usuario, $fecha_solicitud, $tipo = null)
    {
        try {
            $fecha = Carbon::parse($fecha_solicitud)->format('Y-m-d');
            $id_libro = (int) $id_libro;
            $id_usuario = (int) $id_usuario;
            
            Log::debug('Intentando marcar como biblioteca', [
                'id_libro' => $id_libro,
                'id_usuario' => $id_usuario,
                'fecha' => $fecha,
                'tipo' => $tipo
            ]);
            
            $actualizado = 0;
            $tipoSolicitud = '';            // Si se especifica el tipo, intentar actualizar ese tipo primero
            if ($tipo === 'proyecto' || ($request->has('tipo') && $request->tipo === 'proyecto')) {
                $actualizado = LibroProyecto::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Biblioteca'
                    ]);
                $tipoSolicitud = 'proyecto';
            } elseif ($tipo === 'grupo' || ($request->has('tipo') && $request->tipo === 'grupo')) {
                $actualizado = LibroGrupo::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Biblioteca'
                    ]);
                $tipoSolicitud = 'grupo';
            } elseif ($tipo === 'posgrado' || ($request->has('tipo') && $request->tipo === 'posgrado')) {
                $actualizado = LibroPosgrado::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Biblioteca'
                    ]);
                $tipoSolicitud = 'posgrado';
            } elseif ($tipo === 'otros' || ($request->has('tipo') && $request->tipo === 'otros')) {
                $actualizado = LibroOtro::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Biblioteca'
                    ]);
                $tipoSolicitud = 'otros fondos';
            } else {
                // Si no se especifica tipo, intentar primero con asignatura
                $actualizado = LibroAsignatura::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Biblioteca'
                    ]);
                $tipoSolicitud = 'asignatura';
            }            // Si no se actualizó ningún registro, intentar con los otros tipos
            if ($actualizado === 0) {
                if ($tipoSolicitud !== 'proyecto') {
                    $actualizado = LibroProyecto::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Biblioteca'
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'proyecto';
                    }
                }
                
                if ($actualizado === 0 && $tipoSolicitud !== 'grupo') {
                    $actualizado = LibroGrupo::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Biblioteca'
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'grupo de investigación';
                    }
                }
                
                if ($actualizado === 0 && $tipoSolicitud !== 'posgrado') {
                    $actualizado = LibroPosgrado::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Biblioteca'
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'posgrado';
                    }
                }
                
                if ($actualizado === 0 && $tipoSolicitud !== 'otros fondos') {
                    $actualizado = LibroOtro::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Biblioteca'
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'otros fondos';
                    }
                }
                
                if ($actualizado === 0 && $tipoSolicitud !== 'asignatura') {
                    $actualizado = LibroAsignatura::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Biblioteca'
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'asignatura';
                    }
                }
                
                if ($actualizado === 0) {
                    Log::warning('Intento de marcar como biblioteca una solicitud inexistente', [
                        'id_libro' => $id_libro,
                        'id_usuario' => $id_usuario,
                        'fecha_solicitud' => $fecha_solicitud
                    ]);
                    
                    return redirect()->route('libros.index')
                        ->with('error', 'No se encontró la solicitud para marcar como disponible en biblioteca');
                }
            }

            // Enviar correo de notificación
            $usuario = Usuario::find($id_usuario);

            if ($usuario) {
                $libro = Libro::find($id_libro);

                try {
                    Mail::to('jhernandezsanchezagesta@gmail.com')->send(new Notification($usuario, $libro, 'Biblioteca'));
                    Log::info("Correo de notificación biblioteca enviado exitosamente a {$usuario->email} para libro con ID: {$id_libro}");
                } catch (\Exception $e) {
                    Log::error("Error al enviar correo de notificación biblioteca a {$usuario->email}: " . $e->getMessage());
                }
            }

            return redirect()->route('libros.index')
                ->with('success', "Libro con cargo a {$tipoSolicitud} marcado como disponible en biblioteca");
                
        } catch (\Exception $e) {
            Log::error('Error al marcar libro como biblioteca: ' . $e->getMessage());
            
            return view('error.error', [
                'errorMessage' => 'No se pudo marcar el libro como disponible en biblioteca.'
            ]);
        }
    }

    /**
     * Marcar un libro como agotado/descatalogado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id_libro
     * @param  int  $id_usuario
     * @param  string  $fecha_solicitud
     * @param  string  $tipo
     * @return \Illuminate\Http\Response
     */
    public function marcarComoAgotado(Request $request, $id_libro, $id_usuario, $fecha_solicitud, $tipo = null)
    {
        try {
            $fecha = Carbon::parse($fecha_solicitud)->format('Y-m-d');
            $id_libro = (int) $id_libro;
            $id_usuario = (int) $id_usuario;
            
            Log::debug('Intentando marcar como agotado/descatalogado', [
                'id_libro' => $id_libro,
                'id_usuario' => $id_usuario,
                'fecha' => $fecha,
                'tipo' => $tipo
            ]);
            
            $actualizado = 0;
            $tipoSolicitud = '';

            // Si se especifica el tipo, intentar actualizar ese tipo primero
            if ($tipo === 'proyecto' || ($request->has('tipo') && $request->tipo === 'proyecto')) {
                $actualizado = LibroProyecto::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Agotado/Descatalogado',
                        'fecha_aceptado_denegado' => Carbon::now(),
                        'observaciones' => $request->observaciones
                    ]);
                $tipoSolicitud = 'proyecto';
            } elseif ($tipo === 'grupo' || ($request->has('tipo') && $request->tipo === 'grupo')) {
                $actualizado = LibroGrupo::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Agotado/Descatalogado',
                        'fecha_aceptado_denegado' => Carbon::now(),
                        'observaciones' => $request->observaciones
                    ]);
                $tipoSolicitud = 'grupo';
            } elseif ($tipo === 'posgrado' || ($request->has('tipo') && $request->tipo === 'posgrado')) {
                $actualizado = LibroPosgrado::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Agotado/Descatalogado',
                        'fecha_aceptado_denegado' => Carbon::now(),
                        'observaciones' => $request->observaciones
                    ]);
                $tipoSolicitud = 'posgrado';
            } elseif ($tipo === 'otros' || ($request->has('tipo') && $request->tipo === 'otros')) {
                $actualizado = LibroOtro::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Agotado/Descatalogado',
                        'fecha_aceptado_denegado' => Carbon::now(),
                        'observaciones' => $request->observaciones
                    ]);
                $tipoSolicitud = 'otros fondos';
            } else {
                // Si no se especifica tipo, intentar primero con asignatura
                $actualizado = LibroAsignatura::where('id_libro', $id_libro)
                    ->where('id_usuario', $id_usuario)
                    ->whereDate('fecha_solicitud', $fecha)
                    ->update([
                        'estado' => 'Agotado/Descatalogado',
                        'fecha_aceptado_denegado' => Carbon::now(),
                        'observaciones' => $request->observaciones
                    ]);
                $tipoSolicitud = 'asignatura';
            }

            // Si no se actualizó ningún registro, intentar con los otros tipos
            if ($actualizado === 0) {
                if ($tipoSolicitud !== 'proyecto') {
                    $actualizado = LibroProyecto::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Agotado/Descatalogado',
                            'fecha_aceptado_denegado' => Carbon::now(),
                            'observaciones' => $request->observaciones
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'proyecto';
                    }
                }
                
                if ($actualizado === 0 && $tipoSolicitud !== 'grupo') {
                    $actualizado = LibroGrupo::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Agotado/Descatalogado',
                            'fecha_aceptado_denegado' => Carbon::now(),
                            'observaciones' => $request->observaciones
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'grupo';
                    }
                }
                
                if ($actualizado === 0 && $tipoSolicitud !== 'posgrado') {
                    $actualizado = LibroPosgrado::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Agotado/Descatalogado',
                            'fecha_aceptado_denegado' => Carbon::now(),
                            'observaciones' => $request->observaciones
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'posgrado';
                    }
                }
                
                if ($actualizado === 0 && $tipoSolicitud !== 'otros fondos') {
                    $actualizado = LibroOtro::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Agotado/Descatalogado',
                            'fecha_aceptado_denegado' => Carbon::now(),
                            'observaciones' => $request->observaciones
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'otros fondos';
                    }
                }
                
                if ($actualizado === 0 && $tipoSolicitud !== 'asignatura') {
                    $actualizado = LibroAsignatura::where('id_libro', $id_libro)
                        ->where('id_usuario', $id_usuario)
                        ->whereDate('fecha_solicitud', $fecha)
                        ->update([
                            'estado' => 'Agotado/Descatalogado',
                            'fecha_aceptado_denegado' => Carbon::now(),
                            'observaciones' => $request->observaciones
                        ]);
                        
                    if ($actualizado > 0) {
                        $tipoSolicitud = 'asignatura';
                    }
                }
                
                if ($actualizado === 0) {
                    Log::warning('Intento de marcar como agotado/descatalogado una solicitud inexistente', [
                        'id_libro' => $id_libro,
                        'id_usuario' => $id_usuario,
                        'fecha_solicitud' => $fecha_solicitud
                    ]);
                    
                    return redirect()->route('libros.index')
                        ->with('error', 'No se encontró la solicitud para marcar como agotado/descatalogado');
                }
            }

            return redirect()->route('libros.index')
                ->with('success', "Libro con cargo a {$tipoSolicitud} marcado como agotado/descatalogado");
                
        } catch (\Exception $e) {
            Log::error('Error al marcar libro como agotado/descatalogado: ' . $e->getMessage());
            
            return view('error.error', [
                'errorMessage' => 'No se pudo marcar el libro como agotado/descatalogado.'
            ]);
        }
    }

    /**
 * Genera un listado imprimible de libros filtrado por estado
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function imprimir(Request $request)
{
    // Obtener el filtro de estado
    $estado = $request->estado;
    $search = $request->search;
    
    // Consulta para LibroAsignatura
    $queryAsignatura = LibroAsignatura::with(['libro', 'usuario', 'asignatura']);
    
    // Consulta para LibroProyecto
    $queryProyecto = LibroProyecto::with(['libro', 'usuario', 'proyecto']);
    
    // Consulta para LibroGrupo
    $queryGrupo = LibroGrupo::with(['libro', 'usuario', 'grupo']);
    
    // Consulta para LibroPosgrado
    $queryPosgrado = LibroPosgrado::with(['libro', 'usuario']);
    
    // Consulta para LibroOtro
    $queryOtro = LibroOtro::with(['libro', 'usuario']);
    
    // Aplicar filtro de estado si está presente
    if ($estado) {
        $queryAsignatura->where('estado', $estado);
        $queryProyecto->where('estado', $estado);
        $queryGrupo->where('estado', $estado);
        $queryPosgrado->where('estado', $estado);
        $queryOtro->where('estado', $estado);
    }
    
    // Aplicar filtro de búsqueda si está presente
    if ($search) {
        $search = "%{$search}%";
        
        $queryAsignatura->where(function($q) use ($search) {
            $q->whereHas('libro', function($q) use ($search) {
                $q->where('titulo', 'like', $search)
                  ->orWhere('autor', 'like', $search)
                  ->orWhere('isbn', 'like', $search);
            })->orWhereHas('asignatura', function($q) use ($search) {
                $q->where('nombre_asignatura', 'like', $search);
            })->orWhereHas('usuario', function($q) use ($search) {
                $q->where('nombre', 'like', $search)
                  ->orWhere('apellidos', 'like', $search);
            });
        });
        
        // Aplicar de manera similar a los demás queries...
        // queryProyecto, queryGrupo, queryPosgrado, queryOtro
    }
    
    // Ordenar por fecha de solicitud, de más reciente a más antigua
    $queryAsignatura->orderBy('fecha_solicitud', 'desc');
    $queryProyecto->orderBy('fecha_solicitud', 'desc');
    $queryGrupo->orderBy('fecha_solicitud', 'desc');
    $queryPosgrado->orderBy('fecha_solicitud', 'desc');
    $queryOtro->orderBy('fecha_solicitud', 'desc');
    
    // Obtener resultados sin paginación
    $librosAsignatura = $queryAsignatura->get();
    $librosProyecto = $queryProyecto->get();
    $librosGrupo = $queryGrupo->get();
    $librosPosgrado = $queryPosgrado->get();
    $librosOtros = $queryOtro->get();
    
    // Título del listado según el filtro
    $titulo = $estado 
        ? "Listado de libros en estado: $estado" 
        : "Listado completo de libros";
    
    if ($search) {
        $titulo .= " (Búsqueda: $search)";
    }
    
    // Fecha actual para el encabezado del informe
    $fechaActual = Carbon::now()->format('d/m/Y H:i');
    
    return view('libros.imprimir', compact(
        'librosAsignatura',
        'librosProyecto',
        'librosGrupo',
        'librosPosgrado',
        'librosOtros',
        'estado',
        'search',
        'titulo',
        'fechaActual'
    ));
}
}