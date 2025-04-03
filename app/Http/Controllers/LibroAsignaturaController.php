<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\LibroAsignatura;
use App\Models\Usuario;
use App\Models\Asignatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LibroAsignaturaController extends Controller
{
    /**
     * Muestra todas las solicitudes de libros para asignaturas.
     */
    public function index(Request $request)
    {
        $query = LibroAsignatura::query()
            ->join('libro', 'libro_asignatura.id_libro', '=', 'libro.id_libro')
            ->join('usuario', 'libro_asignatura.id_usuario', '=', 'usuario.id_usuario')
            ->join('asignatura', 'libro_asignatura.id_asignatura', '=', 'asignatura.id_asignatura')
            ->select('libro_asignatura.*', 'libro.titulo', 'libro.autor', 'usuario.nombre', 'usuario.apellidos', 'asignatura.nombre_asignatura');
        
        // Filtrado por estado
        if ($request->has('estado') && !empty($request->estado)) {
            $query->where('libro_asignatura.estado', $request->estado);
        }
        
        // Filtrado por fecha de solicitud
        if ($request->has('fecha_desde') && !empty($request->fecha_desde)) {
            $query->where('libro_asignatura.fecha_solicitud', '>=', $request->fecha_desde);
        }
        
        if ($request->has('fecha_hasta') && !empty($request->fecha_hasta)) {
            $query->where('libro_asignatura.fecha_solicitud', '<=', $request->fecha_hasta);
        }
        
        // Filtrado por usuario
        if ($request->has('usuario') && !empty($request->usuario)) {
            $query->where(function($q) use ($request) {
                $q->where('usuario.nombre', 'like', '%' . $request->usuario . '%')
                  ->orWhere('usuario.apellidos', 'like', '%' . $request->usuario . '%');
            });
        }
        
        // Filtrado por asignatura
        if ($request->has('asignatura') && !empty($request->asignatura)) {
            $query->where('asignatura.nombre_asignatura', 'like', '%' . $request->asignatura . '%');
        }
        
        // Ordenamiento
        $orderBy = $request->input('order_by', 'fecha_solicitud');
        $orderDirection = $request->input('order_direction', 'desc');
        
        $solicitudes = $query->orderBy($orderBy, $orderDirection)
                             ->paginate(15)
                             ->withQueryString();
        
        return view('libro_asignatura.index', compact('solicitudes'));
    }

    /**
     * Almacena una nueva solicitud de libro para asignatura.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_libro' => 'required|exists:libro,id_libro',
            'id_asignatura' => 'required|exists:asignatura,id_asignatura',
            'precio' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'num_ejemplares' => 'required|integer|min:1',
            'justificacion' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            
            // Crear la solicitud de libro
            LibroAsignatura::create([
                'id_libro' => $request->id_libro,
                'id_usuario' => Auth::id(),
                'id_asignatura' => $request->id_asignatura,
                'precio' => $request->precio,
                'num_ejemplares' => $request->num_ejemplares,
                'estado' => 'Pendiente Aceptación',
                'observaciones' => $request->observaciones,
                'fecha_solicitud' => now(),
                'justificacion' => $request->justificacion,
            ]);
            
            DB::commit();
            
            return redirect()->route('libros.show', $request->id_libro)
                             ->with('success', 'Solicitud de libro para asignatura registrada correctamente. Pendiente de aprobación.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                         ->with('error', 'Error al registrar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el detalle de una solicitud de libro para asignatura.
     */
    public function show($idLibro, $idUsuario, $fechaSolicitud)
    {
        $solicitud = LibroAsignatura::where('id_libro', $idLibro)
                                    ->where('id_usuario', $idUsuario)
                                    ->where('fecha_solicitud', $fechaSolicitud)
                                    ->firstOrFail();
        
        $libro = Libro::find($idLibro);
        $usuario = Usuario::find($idUsuario);
        $asignatura = Asignatura::find($solicitud->id_asignatura);
        
        return view('libro_asignatura.show', compact('solicitud', 'libro', 'usuario', 'asignatura'));
    }

    /**
     * Aprueba una solicitud de libro para asignatura.
     */
    public function aprobar(Request $request, $idLibro, $idUsuario, $fechaSolicitud)
    {
        $solicitud = LibroAsignatura::where('id_libro', $idLibro)
                                    ->where('id_usuario', $idUsuario)
                                    ->where('fecha_solicitud', $fechaSolicitud)
                                    ->firstOrFail();
        
        // Verificar que el usuario actual puede aprobar la solicitud
        if (!$solicitud->puedeSerAprobadaPor(Auth::id())) {
            return back()->with('error', 'No tienes permisos para aprobar esta solicitud.');
        }
        
        try {
            DB::beginTransaction();
            
            $solicitud->aceptar($request->observaciones);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Solicitud aprobada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al aprobar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Rechaza una solicitud de libro para asignatura.
     */
    public function rechazar(Request $request, $idLibro, $idUsuario, $fechaSolicitud)
    {
        $solicitud = LibroAsignatura::where('id_libro', $idLibro)
                                    ->where('id_usuario', $idUsuario)
                                    ->where('fecha_solicitud', $fechaSolicitud)
                                    ->firstOrFail();
        
        // Verificar que el usuario actual puede rechazar la solicitud
        if (!$solicitud->puedeSerAprobadaPor(Auth::id())) {
            return back()->with('error', 'No tienes permisos para rechazar esta solicitud.');
        }
        
        try {
            DB::beginTransaction();
            
            $solicitud->denegar($request->observaciones);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Solicitud rechazada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al rechazar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Marca una solicitud como pedida.
     */
    public function marcarPedida(Request $request, $idLibro, $idUsuario, $fechaSolicitud)
    {
        $solicitud = LibroAsignatura::where('id_libro', $idLibro)
                                    ->where('id_usuario', $idUsuario)
                                    ->where('fecha_solicitud', $fechaSolicitud)
                                    ->firstOrFail();
        
        // Verificar que la solicitud está aprobada
        if (!$solicitud->estaAceptada()) {
            return back()->with('error', 'La solicitud debe estar aprobada para marcarla como pedida.');
        }
        
        try {
            DB::beginTransaction();
            
            $solicitud->marcarComoPedida($request->observaciones);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Solicitud marcada como pedida correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al marcar la solicitud como pedida: ' . $e->getMessage());
        }
    }

    /**
     * Marca una solicitud como recibida.
     */
    public function marcarRecibida(Request $request, $idLibro, $idUsuario, $fechaSolicitud)
    {
        $solicitud = LibroAsignatura::where('id_libro', $idLibro)
                                    ->where('id_usuario', $idUsuario)
                                    ->where('fecha_solicitud', $fechaSolicitud)
                                    ->firstOrFail();
        
        // Verificar que la solicitud está pedida
        if (!$solicitud->estaPedida()) {
            return back()->with('error', 'La solicitud debe estar en estado "Pedido" para marcarla como recibida.');
        }
        
        try {
            DB::beginTransaction();
            
            $solicitud->marcarComoRecibida($request->observaciones);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Solicitud marcada como recibida correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al marcar la solicitud como recibida: ' . $e->getMessage());
        }
    }

    /**
     * Marca una solicitud como enviada a biblioteca.
     */
    public function marcarBiblioteca(Request $request, $idLibro, $idUsuario, $fechaSolicitud)
    {
        $solicitud = LibroAsignatura::where('id_libro', $idLibro)
                                    ->where('id_usuario', $idUsuario)
                                    ->where('fecha_solicitud', $fechaSolicitud)
                                    ->firstOrFail();
        
        // Verificar que la solicitud está recibida
        if (!$solicitud->estaRecibida()) {
            return back()->with('error', 'La solicitud debe estar en estado "Recibido" para marcarla como enviada a biblioteca.');
        }
        
        try {
            DB::beginTransaction();
            
            $solicitud->marcarComoEnBiblioteca($request->observaciones);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Solicitud marcada como enviada a biblioteca correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al marcar la solicitud como enviada a biblioteca: ' . $e->getMessage());
        }
    }

    /**
     * Marca una solicitud como agotada/descatalogada.
     */
    public function marcarAgotada(Request $request, $idLibro, $idUsuario, $fechaSolicitud)
    {
        $solicitud = LibroAsignatura::where('id_libro', $idLibro)
                                    ->where('id_usuario', $idUsuario)
                                    ->where('fecha_solicitud', $fechaSolicitud)
                                    ->firstOrFail();
        
        // Verificar que la solicitud está en un estado válido para marcarla como agotada
        if (!$solicitud->estaPedida() && !$solicitud->estaAceptada()) {
            return back()->with('error', 'La solicitud debe estar en estado "Pedido" o "Aceptado" para marcarla como agotada/descatalogada.');
        }
        
        try {
            DB::beginTransaction();
            
            $solicitud->marcarComoAgotadaODescatalogada($request->observaciones);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Solicitud marcada como agotada/descatalogada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al marcar la solicitud como agotada/descatalogada: ' . $e->getMessage());
        }
    }
}