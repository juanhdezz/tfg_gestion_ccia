<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LibroAsignatura;
use App\Models\Libro;
use App\Models\Asignatura;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LibroAsignaturaController extends Controller
{
    /**
     * Muestra un listado de los libros solicitados
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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
        
        // Obtener todos los estados posibles para el filtro
        $estados = [
            'Pendiente Aceptación',
            'Aprobado',
            'Denegado',
            'Recibido'
        ];

        // Verificar si el usuario actual es de dirección
        $esDirector = true;//Auth::user()->esDirectorDepartamento();

        return view('libros.index', compact('librosAsignatura', 'estados', 'esDirector'));
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
        
        // Para el formulario de solicitud completo, necesitamos estos datos adicionales
        // Si los necesitases para otros tipos de solicitud (proyectos, grupos, etc.):
        // $proyectos = Proyecto::orderBy('nombre')->get();
        // $gruposInvestigacion = GrupoInvestigacion::orderBy('nombre')->get();
        
        return view('libros.create', compact('asignaturas'));
    }

    /**
     * Almacena una nueva solicitud de libro en la base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'editorial' => 'required|string|max:255',
            'isbn' => 'required|string|max:20',
            'precio' => 'required|numeric|min:0',
            'num_ejemplares' => 'required|integer|min:1',
            'id_asignatura' => 'required|exists:asignatura,id_asignatura',
            'justificacion' => 'required|string',
            'tipo_solicitud' => 'required|in:asignatura',
        ]);

        try {
            // Primero creamos o actualizamos el libro
            $libro = Libro::firstOrCreate(
                ['isbn' => $request->isbn],
                [
                    'titulo' => $request->titulo,
                    'autor' => $request->autor,
                    'editorial' => $request->editorial,
                    'year' => $request->year ?? date('Y'),
                ]
            );

            // Luego creamos la solicitud de libro para asignatura
            $libroAsignatura = new LibroAsignatura();
            $libroAsignatura->id_libro = $libro->id_libro;
            $libroAsignatura->id_usuario = Auth::id();
            $libroAsignatura->id_asignatura = $request->id_asignatura;
            $libroAsignatura->precio = $request->precio;
            $libroAsignatura->num_ejemplares = $request->num_ejemplares;
            $libroAsignatura->estado = 'Pendiente Aceptación';
            $libroAsignatura->justificacion = $request->justificacion;
            $libroAsignatura->observaciones = $request->observaciones;
            $libroAsignatura->fecha_solicitud = Carbon::now();
            $libroAsignatura->save();

            return redirect()->route('libros.index')
                ->with('success', 'Solicitud de libro creada correctamente. Estado: Pendiente Aceptación');

        } catch (\Exception $e) {
            // Registra el error para poder depurarlo
            Log::error('Error al guardar la solicitud de libro: ' . $e->getMessage());
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
public function aprobar(Request $request, $id_libro, $id_usuario, $fecha_solicitud)
{
    try {
        // Convertir la fecha a un formato adecuado para la consulta
        $fecha = Carbon::parse($fecha_solicitud)->format('Y-m-d');

        // Convertir a tipos adecuados
        $id_libro = (int) $id_libro;
        $id_usuario = (int) $id_usuario;
        $fecha = Carbon::parse($fecha_solicitud)->format('Y-m-d');
        
        // Debug log para verificar los valores
        Log::debug('Intentando aprobar solicitud', [
            'id_libro' => $id_libro,
            'id_usuario' => $id_usuario,
            'fecha' => $fecha
        ]);
        
        // Actualizar directamente, sin realizar first() antes
        $actualizado = LibroAsignatura::where('id_libro', $id_libro)
            ->where('id_usuario', $id_usuario)
            ->whereDate('fecha_solicitud', $fecha)
            ->update([
                'estado' => 'Aceptado',
                'fecha_aceptado_denegado' => Carbon::now(),
                'fecha_pedido' => Carbon::now()
            ]);

        if ($actualizado === 0) {
            // Si no se actualizó ningún registro
            Log::warning('Intento de aprobar una solicitud inexistente', [
                'id_libro' => $id_libro,
                'id_usuario' => $id_usuario,
                'fecha_solicitud' => $fecha_solicitud
            ]);
            
            return redirect()->route('libros.index')
                ->with('error', 'No se encontró la solicitud para aprobar');
        }

        return redirect()->route('libros.index')
            ->with('success', 'Solicitud de libro aprobada correctamente');
            
    } catch (\Exception $e) {
        Log::error('Error al aprobar la solicitud: ' . $e->getMessage());
        
        // Redirigir a la vista de error
        return view('error.error', [
            'errorMessage' => 'No se pudo aprobar la solicitud de libro.',
            'exception' => $e,
            'id_libro' => $id_libro,
            'id_usuario' => $id_usuario,
            'fecha_solicitud' => $fecha_solicitud,
            'fecha' => $fecha
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
 * @return \Illuminate\Http\Response
 */
public function denegar(Request $request, $id_libro, $id_usuario, $fecha_solicitud)
{
    try {
        // Convertir la fecha a un formato adecuado para la consulta
        $fecha = Carbon::parse($fecha_solicitud)->format('Y-m-d');
        
        // Actualizar directamente
        $actualizado = LibroAsignatura::where('id_libro', $id_libro)
            ->where('id_usuario', $id_usuario)
            ->whereDate('fecha_solicitud', $fecha)
            ->update([
                'estado' => 'Denegado',
                'fecha_aceptado_denegado' => Carbon::now(),
                'observaciones' => $request->observaciones
            ]);

        if ($actualizado === 0) {
            return redirect()->route('libros.index')
                ->with('error', 'No se encontró la solicitud para denegar');
        }

        return redirect()->route('libros.index')
            ->with('success', 'Solicitud de libro denegada correctamente');
            
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
 * @return \Illuminate\Http\Response
 */
public function recibir(Request $request, $id_libro, $id_usuario, $fecha_solicitud)
{
    try {
        // Convertir la fecha a un formato adecuado para la consulta
        $fecha = Carbon::parse($fecha_solicitud)->format('Y-m-d');
        
        // Actualizar directamente
        $actualizado = LibroAsignatura::where('id_libro', $id_libro)
            ->where('id_usuario', $id_usuario)
            ->whereDate('fecha_solicitud', $fecha)
            ->update([
                'estado' => 'Recibido',
                'fecha_recepcion' => Carbon::now()
            ]);

        if ($actualizado === 0) {
            return redirect()->route('libros.index')
                ->with('error', 'No se encontró la solicitud para marcar como recibida');
        }

        return redirect()->route('libros.index')
            ->with('success', 'Libro marcado como recibido correctamente');
            
    } catch (\Exception $e) {
        Log::error('Error al marcar libro como recibido: ' . $e->getMessage());
        
        return view('error.error', [
            'errorMessage' => 'No se pudo marcar el libro como recibido.'
        ]);
    }
}
}