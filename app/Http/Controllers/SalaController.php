<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Sala;
use App\Models\ReservaSala;

class SalaController extends Controller
{
    /**
     * Muestra una lista de todas las salas
     */
    public function index(Request $request)
    {
        // Obtener filtros de búsqueda
        $search = $request->input('search');
        
        $query = Sala::query();
        
        // Aplicar filtro de búsqueda si existe
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('localizacion', 'like', "%{$search}%");
            });
        }
        
        // Ordenar por nombre
        $salas = $query->orderBy('nombre')->paginate(10);
        
        return view('salas.index', compact('salas', 'search'));
    }

    /**
     * Muestra el formulario para crear una nueva sala
     */
    public function create()
    {
        return view('salas.create');
    }

    /**
     * Almacena una nueva sala en la base de datos
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:sala,nombre',
            'localizacion' => 'required|string|max:255',
            'dias_anticipacion_reserva' => 'nullable|integer|min:1|max:365',
        ], [
            'nombre.required' => 'El nombre de la sala es obligatorio',
            'nombre.unique' => 'Ya existe una sala con este nombre',
            'localizacion.required' => 'La localización es obligatoria',
            'dias_anticipacion_reserva.integer' => 'Los días de anticipación deben ser un número entero',
            'dias_anticipacion_reserva.min' => 'Los días de anticipación deben ser al menos 1',
            'dias_anticipacion_reserva.max' => 'Los días de anticipación no pueden ser más de 365',
        ]);

        if ($validator->fails()) {
            return redirect()->route('salas.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Crear la sala
            $sala = new Sala();
            $sala->nombre = $request->nombre;
            $sala->localizacion = $request->localizacion;
            $sala->dias_anticipacion_reserva = $request->dias_anticipacion_reserva;
            
            $sala->save();
            
            // Preparar mensaje para SweetAlert
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Sala creada',
                'text' => 'La sala ha sido creada exitosamente'
            ]);
            
            return redirect()->route('salas.index')
                ->with('success', 'Sala creada exitosamente');
                
        } catch (\Exception $e) {
            // Log del error para debugging
            Log::error('Error al crear sala: ' . $e->getMessage());
            
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo crear la sala: ' . $e->getMessage()
            ]);
            
            return redirect()->route('salas.create')
                ->withInput()
                ->with('error', 'No se pudo crear la sala');
        }
    }    /**
     * Muestra la información de una sala específica
     */
    public function show($id_sala)
    {
        $sala = Sala::findOrFail($id_sala);
        
        // Obtener estadísticas de reservas de esta sala
        $estadisticas = [
            'total' => ReservaSala::where('id_sala', $id_sala)->count(),
            'aprobadas' => ReservaSala::where('id_sala', $id_sala)->where('estado', 'aprobada')->count(),
            'pendientes' => ReservaSala::where('id_sala', $id_sala)->where('estado', 'pendiente')->count(),
            'rechazadas' => ReservaSala::where('id_sala', $id_sala)->where('estado', 'rechazada')->count(),
        ];
        
        return view('salas.show', compact('sala', 'estadisticas'));
    }

    /**
     * Muestra el formulario para editar una sala existente
     */
    public function edit($id_sala)
    {
        $sala = Sala::findOrFail($id_sala);
        
        return view('salas.edit', compact('sala'));
    }

    /**
     * Actualiza una sala específica en la base de datos
     */
    public function update(Request $request, $id_sala)
    {
        $sala = Sala::findOrFail($id_sala);
        
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:sala,nombre,' . $id_sala . ',id_sala',
            'localizacion' => 'required|string|max:255',
            'dias_anticipacion_reserva' => 'nullable|integer|min:1|max:365',
        ], [
            'nombre.required' => 'El nombre de la sala es obligatorio',
            'nombre.unique' => 'Ya existe una sala con este nombre',
            'localizacion.required' => 'La localización es obligatoria',
            'dias_anticipacion_reserva.integer' => 'Los días de anticipación deben ser un número entero',
            'dias_anticipacion_reserva.min' => 'Los días de anticipación deben ser al menos 1',
            'dias_anticipacion_reserva.max' => 'Los días de anticipación no pueden ser más de 365',
        ]);

        if ($validator->fails()) {
            return redirect()->route('salas.edit', $id_sala)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Actualizar la sala
            $sala->nombre = $request->nombre;
            $sala->localizacion = $request->localizacion;
            $sala->dias_anticipacion_reserva = $request->dias_anticipacion_reserva;
            
            $sala->save();
            
            // Preparar mensaje para SweetAlert
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Sala actualizada',
                'text' => 'La sala ha sido actualizada exitosamente'
            ]);
            
            return redirect()->route('salas.index')
                ->with('success', 'Sala actualizada exitosamente');
                
        } catch (\Exception $e) {
            // Log del error para debugging
            Log::error('Error al actualizar sala: ' . $e->getMessage());
            
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo actualizar la sala: ' . $e->getMessage()
            ]);
            
            return redirect()->route('salas.edit', $id_sala)
                ->withInput()
                ->with('error', 'No se pudo actualizar la sala');
        }
    }

    /**
     * Elimina una sala específica de la base de datos
     */
    public function destroy($id_sala)
    {
        try {
            $sala = Sala::findOrFail($id_sala);
            
            // Verificar si tiene reservas asociadas
            $tieneReservas = ReservaSala::where('id_sala', $id_sala)->exists();
            
            if ($tieneReservas) {
                session()->flash('swal', [
                    'icon' => 'warning',
                    'title' => 'No se puede eliminar',
                    'text' => 'La sala tiene reservas asociadas y no puede ser eliminada'
                ]);
                
                return redirect()->route('salas.index')
                    ->with('warning', 'La sala tiene reservas asociadas y no puede ser eliminada');
            }
            
            $nombreSala = $sala->nombre;
            $sala->delete();
            
            // Preparar mensaje para SweetAlert
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Sala eliminada',
                'text' => "La sala '{$nombreSala}' ha sido eliminada exitosamente"
            ]);
            
            return redirect()->route('salas.index')
                ->with('success', "Sala '{$nombreSala}' eliminada exitosamente");
                
        } catch (\Exception $e) {
            // Log del error para debugging
            Log::error('Error al eliminar sala: ' . $e->getMessage());
            
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo eliminar la sala: ' . $e->getMessage()
            ]);
            
            return redirect()->route('salas.index')
                ->with('error', 'No se pudo eliminar la sala');
        }
    }
}
