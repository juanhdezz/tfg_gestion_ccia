<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\LibroAsignatura;
use App\Models\Usuario;
use App\Models\Asignatura;
use App\Models\UsuarioAsignatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class LibroController extends Controller
{
    /**
     * Muestra un listado de los libros.
     */
    public function index(Request $request)
    {
        $query = Libro::query();
        
        // Filtrado por título
        if ($request->has('titulo') && !empty($request->titulo)) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }
        
        // Filtrado por autor
        if ($request->has('autor') && !empty($request->autor)) {
            $query->where('autor', 'like', '%' . $request->autor . '%');
        }
        
        // Filtrado por ISBN
        if ($request->has('isbn') && !empty($request->isbn)) {
            $query->where('isbn', 'like', '%' . $request->isbn . '%');
        }

        // Filtrado por año
        if ($request->has('year') && !empty($request->year)) {
            $query->where('year', $request->year);
        }
        
        // Ordenamiento
        $orderBy = $request->input('order_by', 'titulo');
        $orderDirection = $request->input('order_direction', 'asc');
        
        $libros = $query->orderBy($orderBy, $orderDirection)
                        ->paginate(10)
                        ->withQueryString();
        
        return view('libros.index', compact('libros'));
    }

    /**
     * Muestra el formulario para crear un nuevo libro.
     */
    public function create()
    {
        return view('libros.create');
    }

    /**
     * Almacena un nuevo libro en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|max:200',
            'isbn' => 'nullable|max:30',
            'num_paginas' => 'nullable|integer|min:1',
            'autor' => 'required|max:200',
            'editorial' => 'nullable|max:50',
            'edicion' => 'nullable|max:16',
            'year' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'portada' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'website' => 'nullable|url|max:256',
        ]);

        try {
            DB::beginTransaction();
            
            $data = $request->except('portada');
            
            // Manejar subida de imagen de portada
            if ($request->hasFile('portada')) {
                $portadaPath = $request->file('portada')->store('portadas', 'public');
                $data['portada'] = $portadaPath;
            }
            
            $libro = Libro::create($data);
            
            DB::commit();
            
            return redirect()->route('libros.show', $libro->id_libro)
                             ->with('success', 'Libro creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                         ->with('error', 'Error al crear el libro: ' . $e->getMessage());
        }
    }

    /**
     * Muestra la información detallada de un libro.
     */
    public function show(Libro $libro)
    {
        // Obtener solo las solicitudes de libros para asignaturas asociadas a este libro
        $solicitudesAsignatura = $libro->solicitudesAsignatura;
        
        // Verificar si el usuario actual tiene permisos para ver las solicitudes
        $puedeVerSolicitudes = true;
        
        return view('libros.show', compact(
            'libro', 
            'solicitudesAsignatura',
            'puedeVerSolicitudes'
        ));
    }

    /**
     * Muestra el formulario para editar un libro.
     */
    public function edit(Libro $libro)
    {
        return view('libros.edit', compact('libro'));
    }

    /**
     * Actualiza un libro específico.
     */
    public function update(Request $request, Libro $libro)
    {
        $request->validate([
            'titulo' => 'required|max:200',
            'isbn' => 'nullable|max:30',
            'num_paginas' => 'nullable|integer|min:1',
            'autor' => 'required|max:200',
            'editorial' => 'nullable|max:50',
            'edicion' => 'nullable|max:16',
            'year' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'portada' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'website' => 'nullable|url|max:256',
        ]);

        try {
            DB::beginTransaction();
            
            $data = $request->except('portada');
            
            // Manejar actualización de imagen de portada
            if ($request->hasFile('portada')) {
                // Eliminar la portada anterior si existe
                if ($libro->portada && Storage::disk('public')->exists($libro->portada)) {
                    Storage::disk('public')->delete($libro->portada);
                }
                
                $portadaPath = $request->file('portada')->store('portadas', 'public');
                $data['portada'] = $portadaPath;
            }
            
            $libro->update($data);
            
            DB::commit();
            
            return redirect()->route('libros.show', $libro->id_libro)
                             ->with('success', 'Libro actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                         ->with('error', 'Error al actualizar el libro: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un libro específico.
     */
    public function destroy(Libro $libro)
    {
        try {
            DB::beginTransaction();
            
            // Verificar si hay solicitudes asociadas a este libro
            $tieneSolicitudes = $libro->solicitudesAsignatura()->exists();
            
            if ($tieneSolicitudes) {
                return back()->with('error', 'No se puede eliminar el libro porque tiene solicitudes asociadas.');
            }
            
            // Eliminar la portada si existe
            if ($libro->portada && Storage::disk('public')->exists($libro->portada)) {
                Storage::disk('public')->delete($libro->portada);
            }
            
            $libro->delete();
            
            DB::commit();
            
            return redirect()->route('libros.index')
                             ->with('success', 'Libro eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar el libro: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario para solicitar un libro para asignatura.
     */
    public function solicitarForm(Libro $libro)
    {
        $usuario = Auth::user();
        
        // Obtener las asignaturas que imparte el usuario actual
        // Usando el modelo UsuarioAsignatura para verificar la relación
        $asignaturas = Asignatura::join('usuario_asignatura', 'asignatura.id_asignatura', '=', 'usuario_asignatura.id_asignatura')
            ->where('usuario_asignatura.id_usuario', $usuario->id_usuario)
            ->select('asignatura.*', 'usuario_asignatura.tipo', 'usuario_asignatura.grupo', 'usuario_asignatura.creditos')
            ->get();
        
        // Si el usuario no imparte ninguna asignatura, mostrar un mensaje de error
        if ($asignaturas->isEmpty()) {
            return back()->with('error', 'No puedes solicitar libros para asignaturas porque no impartes ninguna asignatura en el departamento.');
        }
        
        return view('libros.solicitar_asignatura', compact('libro', 'usuario', 'asignaturas'));
    }

    /**
     * Almacena una nueva solicitud de libro para asignatura.
     */
    public function solicitarStore(Request $request)
    {
        $request->validate([
            'id_libro' => 'required|exists:libro,id_libro',
            'id_asignatura' => 'required|exists:asignatura,id_asignatura',
            'precio' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'num_ejemplares' => 'required|integer|min:1',
            'justificacion' => 'required|string|max:1000',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();
            
            $usuario = Auth::user();
            
            // Verificar que el usuario imparte la asignatura seleccionada
            $imparteAsignatura = UsuarioAsignatura::where('id_usuario', $usuario->id_usuario)
                ->where('id_asignatura', $request->id_asignatura)
                ->exists();
            
            if (!$imparteAsignatura) {
                return back()->withInput()->with('error', 'No puedes solicitar libros para una asignatura que no impartes.');
            }
            
            // Obtener información de la asignatura
            $asignatura = Asignatura::find($request->id_asignatura);
            
            // Crear la solicitud de libro para asignatura
            $solicitud = new LibroAsignatura();
            $solicitud->id_libro = $request->id_libro;
            $solicitud->id_usuario = $usuario->id_usuario;
            $solicitud->id_asignatura = $request->id_asignatura;
            $solicitud->precio = $request->precio;
            $solicitud->num_ejemplares = $request->num_ejemplares;
            $solicitud->estado = 'Pendiente Aceptación';
            $solicitud->observaciones = $request->observaciones;
            $solicitud->fecha_solicitud = now();
            $solicitud->justificacion = $request->justificacion;
            $solicitud->save();
            
            // Enviar notificación al director del departamento
            // Aquí irá el código para notificar al director cuando implementes las notificaciones
            
            DB::commit();
            
            return redirect()->route('libros.show', $request->id_libro)
                ->with('success', 'Solicitud de libro para la asignatura "' . $asignatura->nombre_asignatura . '" registrada correctamente. Pendiente de aprobación por la dirección del departamento.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al registrar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Lista todas las solicitudes de libros para asignaturas del usuario actual.
     */
    public function misSolicitudesAsignatura()
    {
        $usuario = Auth::user();
        
        $solicitudes = LibroAsignatura::where('id_usuario', $usuario->id_usuario)
            ->join('libro', 'libro_asignatura.id_libro', '=', 'libro.id_libro')
            ->join('asignatura', 'libro_asignatura.id_asignatura', '=', 'asignatura.id_asignatura')
            ->select(
                'libro_asignatura.*', 
                'libro.titulo', 
                'libro.autor', 
                'asignatura.nombre_asignatura',
                'asignatura.titulacion'
            )
            ->orderBy('libro_asignatura.fecha_solicitud', 'desc')
            ->paginate(10);
        
        return view('libros.mis_solicitudes_asignatura', compact('solicitudes'));
    }

    /**
     * Lista todas las solicitudes de libros para asignaturas pendientes de aprobación
     * (Solo para directores de departamento)
     */
    public function solicitudesPendientesAsignatura()
    {
        // Verificar que el usuario actual es director de departamento
        $usuario = Auth::user();
        
        
        $solicitudes = LibroAsignatura::where('estado', 'Pendiente Aceptación')
            ->join('libro', 'libro_asignatura.id_libro', '=', 'libro.id_libro')
            ->join('usuario', 'libro_asignatura.id_usuario', '=', 'usuario.id_usuario')
            ->join('asignatura', 'libro_asignatura.id_asignatura', '=', 'asignatura.id_asignatura')
            ->select(
                'libro_asignatura.*', 
                'libro.titulo', 
                'libro.autor', 
                'usuario.nombre', 
                'usuario.apellidos',
                'asignatura.nombre_asignatura',
                'asignatura.titulacion'
            )
            ->orderBy('libro_asignatura.fecha_solicitud', 'asc')
            ->paginate(10);
        
        return view('libros.solicitudes_pendientes_asignatura', compact('solicitudes'));
    }

    /**
     * Aprueba una solicitud de libro para asignatura.
     */
    public function aprobarSolicitudAsignatura($idLibro, $idUsuario, $fechaSolicitud)
    {
        // Verificar que el usuario actual es director de departamento
        $usuario = Auth::user();
        
        
        try {
            DB::beginTransaction();
            
            // Buscar la solicitud
            $solicitud = LibroAsignatura::where('id_libro', $idLibro)
                ->where('id_usuario', $idUsuario)
                ->where('fecha_solicitud', $fechaSolicitud)
                ->firstOrFail();
            
            // Verificar que la solicitud está pendiente
            if ($solicitud->estado !== 'Pendiente Aceptación') {
                return back()->with('error', 'La solicitud no está en estado pendiente de aceptación.');
            }
            
            // Actualizar estado de la solicitud
            $solicitud->estado = 'Aceptado';
            $solicitud->fecha_aceptacion = now();
            $solicitud->id_aceptador = $usuario->id_usuario;
            
            // Agregar observación de aprobación si se proporciona
            if (request()->has('observaciones') && !empty(request()->observaciones)) {
                $solicitud->observaciones = trim($solicitud->observaciones . "\n\n" . now()->format('d/m/Y H:i') . ' - Aprobada: ' . request()->observaciones);
            } else {
                $solicitud->observaciones = trim($solicitud->observaciones . "\n\n" . now()->format('d/m/Y H:i') . ' - Solicitud aprobada por la dirección del departamento.');
            }
            
            $solicitud->save();
            
            // Enviar notificación al solicitante
            // Aquí irá el código para notificar al solicitante cuando implementes las notificaciones
            
            DB::commit();
            
            return back()->with('success', 'Solicitud aprobada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al aprobar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Rechaza una solicitud de libro para asignatura.
     */
    public function rechazarSolicitudAsignatura($idLibro, $idUsuario, $fechaSolicitud)
    {
        // Verificar que el usuario actual es director de departamento
        $usuario = Auth::user();
        
        
        try {
            DB::beginTransaction();
            
            // Buscar la solicitud
            $solicitud = LibroAsignatura::where('id_libro', $idLibro)
                ->where('id_usuario', $idUsuario)
                ->where('fecha_solicitud', $fechaSolicitud)
                ->firstOrFail();
            
            // Verificar que la solicitud está pendiente
            if ($solicitud->estado !== 'Pendiente Aceptación') {
                return back()->with('error', 'La solicitud no está en estado pendiente de aceptación.');
            }
            
            // Actualizar estado de la solicitud
            $solicitud->estado = 'Denegado';
            $solicitud->fecha_rechazo = now();
            $solicitud->id_rechazador = $usuario->id_usuario;
            
            // Agregar motivo de rechazo
            if (request()->has('motivo_rechazo') && !empty(request()->motivo_rechazo)) {
                $solicitud->observaciones = trim($solicitud->observaciones . "\n\n" . now()->format('d/m/Y H:i') . ' - Denegada: ' . request()->motivo_rechazo);
            } else {
                $solicitud->observaciones = trim($solicitud->observaciones . "\n\n" . now()->format('d/m/Y H:i') . ' - Solicitud denegada por la dirección del departamento.');
            }
            
            $solicitud->save();
            
            // Enviar notificación al solicitante
            // Aquí irá el código para notificar al solicitante cuando implementes las notificaciones
            
            DB::commit();
            
            return back()->with('success', 'Solicitud rechazada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al rechazar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Marca una solicitud como pedida.
     */
    public function marcarPedidaSolicitudAsignatura($idLibro, $idUsuario, $fechaSolicitud)
    {
        // Verificar que el usuario tiene permisos para marcar como pedida
        $usuario = Auth::user();
        
        
        try {
            DB::beginTransaction();
            
            // Buscar la solicitud
            $solicitud = LibroAsignatura::where('id_libro', $idLibro)
                ->where('id_usuario', $idUsuario)
                ->where('fecha_solicitud', $fechaSolicitud)
                ->firstOrFail();
            
            // Verificar que la solicitud está aceptada
            if ($solicitud->estado !== 'Aceptado') {
                return back()->with('error', 'La solicitud debe estar aceptada para marcarla como pedida.');
            }
            
            // Actualizar estado de la solicitud
            $solicitud->estado = 'Pedido';
            $solicitud->fecha_pedido = now();
            $solicitud->id_pedidor = $usuario->id_usuario;
            
            // Agregar observación
            if (request()->has('observaciones') && !empty(request()->observaciones)) {
                $solicitud->observaciones = trim($solicitud->observaciones . "\n\n" . now()->format('d/m/Y H:i') . ' - Pedido: ' . request()->observaciones);
            } else {
                $solicitud->observaciones = trim($solicitud->observaciones . "\n\n" . now()->format('d/m/Y H:i') . ' - Libro pedido.');
            }
            
            $solicitud->save();
            
            // Enviar notificación al solicitante
            // Aquí irá el código para notificar al solicitante cuando implementes las notificaciones
            
            DB::commit();
            
            return back()->with('success', 'Solicitud marcada como pedida correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al marcar la solicitud como pedida: ' . $e->getMessage());
        }
    }

    /**
     * Marca una solicitud como recibida.
     */
    public function marcarRecibidaSolicitudAsignatura($idLibro, $idUsuario, $fechaSolicitud)
    {
        // Verificar que el usuario tiene permisos para marcar como recibida
        $usuario = Auth::user();
        
        
        try {
            DB::beginTransaction();
            
            // Buscar la solicitud
            $solicitud = LibroAsignatura::where('id_libro', $idLibro)
                ->where('id_usuario', $idUsuario)
                ->where('fecha_solicitud', $fechaSolicitud)
                ->firstOrFail();
            
            // Verificar que la solicitud está pedida
            if ($solicitud->estado !== 'Pedido') {
                return back()->with('error', 'La solicitud debe estar en estado "Pedido" para marcarla como recibida.');
            }
            
            // Actualizar estado de la solicitud
            $solicitud->estado = 'Recibido';
            $solicitud->fecha_recepcion = now();
            $solicitud->id_receptor = $usuario->id_usuario;
            
            // Agregar observación
            if (request()->has('observaciones') && !empty(request()->observaciones)) {
                $solicitud->observaciones = trim($solicitud->observaciones . "\n\n" . now()->format('d/m/Y H:i') . ' - Recibido: ' . request()->observaciones);
            } else {
                $solicitud->observaciones = trim($solicitud->observaciones . "\n\n" . now()->format('d/m/Y H:i') . ' - Libro recibido en el departamento.');
            }
            
            $solicitud->save();
            
            // Enviar notificación al solicitante
            // Aquí irá el código para notificar al solicitante cuando implementes las notificaciones
            
            DB::commit();
            
            return back()->with('success', 'Solicitud marcada como recibida correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al marcar la solicitud como recibida: ' . $e->getMessage());
        }
    }

    /**
     * Marca una solicitud como enviada a biblioteca.
     */
    public function marcarBibliotecaSolicitudAsignatura($idLibro, $idUsuario, $fechaSolicitud)
    {
        // Verificar que el usuario tiene permisos para marcar como enviada a biblioteca
        $usuario = Auth::user();
        
        
        try {
            DB::beginTransaction();
            
            // Buscar la solicitud
            $solicitud = LibroAsignatura::where('id_libro', $idLibro)
                ->where('id_usuario', $idUsuario)
                ->where('fecha_solicitud', $fechaSolicitud)
                ->firstOrFail();
            
            // Verificar que la solicitud está recibida
            if ($solicitud->estado !== 'Recibido') {
                return back()->with('error', 'La solicitud debe estar en estado "Recibido" para marcarla como enviada a biblioteca.');
            }
            
            // Actualizar estado de la solicitud
            $solicitud->estado = 'Biblioteca';
            $solicitud->fecha_biblioteca = now();
            $solicitud->id_bibliotecario = $usuario->id_usuario;
            
            // Agregar observación
            if (request()->has('observaciones') && !empty(request()->observaciones)) {
                $solicitud->observaciones = trim($solicitud->observaciones . "\n\n" . now()->format('d/m/Y H:i') . ' - Biblioteca: ' . request()->observaciones);
            } else {
                $solicitud->observaciones = trim($solicitud->observaciones . "\n\n" . now()->format('d/m/Y H:i') . ' - Libro enviado a biblioteca para catalogación.');
            }
            
            $solicitud->save();
            
            // Enviar notificación al solicitante
            // Aquí irá el código para notificar al solicitante cuando implementes las notificaciones
            
            DB::commit();
            
            return back()->with('success', 'Solicitud marcada como enviada a biblioteca correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al marcar la solicitud como enviada a biblioteca: ' . $e->getMessage());
        }
    }

    /**
     * Marca una solicitud como agotada/descatalogada.
     */
    public function marcarAgotadaSolicitudAsignatura($idLibro, $idUsuario, $fechaSolicitud)
    {
        // Verificar que el usuario tiene permisos para marcar como agotada
        $usuario = Auth::user();
        
        
        try {
            DB::beginTransaction();
            
            // Buscar la solicitud
            $solicitud = LibroAsignatura::where('id_libro', $idLibro)
                ->where('id_usuario', $idUsuario)
                ->where('fecha_solicitud', $fechaSolicitud)
                ->firstOrFail();
            
            // Verificar que la solicitud está en un estado válido para marcarla como agotada
            if ($solicitud->estado !== 'Aceptado' && $solicitud->estado !== 'Pedido') {
                return back()->with('error', 'La solicitud debe estar en estado "Aceptado" o "Pedido" para marcarla como agotada/descatalogada.');
            }
            
            // Actualizar estado de la solicitud
            $solicitud->estado = 'Agotado/Descatalogado';
            $solicitud->fecha_agotado = now();
            $solicitud->id_agotador = $usuario->id_usuario;
            
            // Agregar observación
            if (request()->has('observaciones') && !empty(request()->observaciones)) {
                $solicitud->observaciones = trim($solicitud->observaciones . "\n\n" . now()->format('d/m/Y H:i') . ' - Agotado/Descatalogado: ' . request()->observaciones);
            } else {
                $solicitud->observaciones = trim($solicitud->observaciones . "\n\n" . now()->format('d/m/Y H:i') . ' - Libro agotado o descatalogado.');
            }
            
            $solicitud->save();
            
            // Enviar notificación al solicitante
            // Aquí irá el código para notificar al solicitante cuando implementes las notificaciones
            
            DB::commit();
            
            return back()->with('success', 'Solicitud marcada como agotada/descatalogada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al marcar la solicitud como agotada/descatalogada: ' . $e->getMessage());
        }
    }
}