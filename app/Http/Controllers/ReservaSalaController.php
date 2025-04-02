<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ReservaSala;
use App\Models\Sala;
use App\Models\Motivo;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class ReservaSalaController extends Controller
{
    /**
     * Muestra una lista de todas las reservas de salas
     */
    public function index(Request $request)
    {
        // Filtros
        $filtroFecha = $request->input('fecha');
        $filtroSala = $request->input('id_sala');
        $filtroUsuario = $request->input('id_usuario');
        $filtroEstado = $request->input('estado');

        $query = ReservaSala::with(['sala', 'usuario', 'motivo'])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'asc');
        
        // Aplicar filtros si existen
        if ($filtroFecha) {
            $query->whereDate('fecha', $filtroFecha);
        }
        
        if ($filtroSala) {
            $query->where('id_sala', $filtroSala);
        }
        
        if ($filtroUsuario) {
            $query->where('id_usuario', $filtroUsuario);
        }
        
        if ($filtroEstado) {
            $query->where('estado', $filtroEstado);
        }
        
        $reservas = $query->paginate(10);
        $salas = Sala::orderBy('nombre')->get();
        $usuarios = Usuario::orderBy('apellidos')->get();
        
        return view('reserva_salas.index', compact('reservas', 'salas', 'usuarios', 
            'filtroFecha', 'filtroSala', 'filtroUsuario', 'filtroEstado'));
    }

    /**
     * Muestra el formulario para crear una nueva reserva de sala
     */
    public function create()
    {
        $salas = Sala::orderBy('nombre')->get();
        $motivos = Motivo::orderBy('descripcion')->get();
        $usuarios = Usuario::orderBy('apellidos')->get();
        
        // Si el usuario actual no es administrador, filtrar motivos según su rol
        // (esto es un ejemplo, ajusta según la lógica de tu aplicación)
        /*
        $usuario = Auth::user();
        if (!$usuario->esAdmin()) {
            if ($usuario->esCoordinadorProyectos()) {
                $motivos = $motivos->filter(function($motivo) {
                    return $motivo->esParaTodos() || $motivo->esParaCoordinadorProyectos();
                });
            } elseif ($usuario->esDireccion()) {
                $motivos = $motivos->filter(function($motivo) {
                    return $motivo->esParaTodos() || $motivo->esParaDireccion();
                });
            } else {
                $motivos = $motivos->filter(function($motivo) {
                    return $motivo->esParaTodos();
                });
            }
        }
        */
        
        return view('reserva_salas.create', compact('salas', 'motivos', 'usuarios'));
    }

    /**
     * Almacena una nueva reserva de sala en la base de datos
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        // $validator = Validator::make($request->all(), [
        //     'id_sala' => 'required|exists:sala,id_sala',
        //     'id_usuario' => 'required|exists:usuario,id_usuario',
        //     'id_motivo' => 'required|exists:motivo,id_motivo',
        //     'hora_inicio' => 'required',
        //     'hora_fin' => 'required|after:hora_inicio',
        //     'observaciones' => 'nullable|string',
        //     'estado' => 'nullable|in:Validada,Pendiente Validación,Rechazada,Cancelada',
        // ]);

        // if ($validator->fails()) {
        //     Log::warning('Validación fallida', $validator->errors()->toArray());
        //     return redirect()->route('departamento')
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        // Verificar disponibilidad de la sala
        $sala = Sala::findOrFail($request->id_sala);
        $fecha = $request->fecha;
        $horaInicio = $request->hora_inicio;
        $horaFin = $request->hora_fin;
        
        $reservasExistentes = ReservaSala::where('id_sala', $sala->id_sala)
            ->where('fecha', $fecha)
            ->where('estado', '!=', 'Cancelada')
            ->where('estado', '!=', 'Rechazada')
            ->where(function($query) use ($horaInicio, $horaFin) {
                $query->whereBetween('hora_inicio', [$horaInicio, $horaFin])
                    ->orWhereBetween('hora_fin', [$horaInicio, $horaFin])
                    ->orWhere(function($q) use ($horaInicio, $horaFin) {
                        $q->where('hora_inicio', '<=', $horaInicio)
                          ->where('hora_fin', '>=', $horaFin);
                    });
            })
            ->exists();
            
        if ($reservasExistentes) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Sala no disponible',
                'text' => 'La sala ya está reservada para el horario seleccionado'
            ]);
            
            return redirect()->route('reserva_salas.create')
                ->withInput()
                ->with('error', 'La sala ya está reservada para el horario seleccionado');
        }
        
        // Verificar días de anticipación para la reserva
        if ($sala->dias_anticipacion_reserva) {
            $fechaLimite = Carbon::now()->addDays($sala->dias_anticipacion_reserva);
            $fechaReserva = Carbon::parse($fecha);
            
            if ($fechaReserva->greaterThan($fechaLimite)) {
                session()->flash('swal', [
                    'icon' => 'error',
                    'title' => 'Fecha no válida',
                    'text' => "Solo se puede reservar con {$sala->dias_anticipacion_reserva} días de anticipación"
                ]);
                
                return redirect()->route('reserva_salas.create')
                    ->withInput()
                    ->with('error', "Solo se puede reservar con {$sala->dias_anticipacion_reserva} días de anticipación");
            }
        }

        try {
            // Crear la reserva
            $reserva = new ReservaSala();
            $reserva->id_sala = $request->id_sala;
            $reserva->id_usuario = $request->id_usuario;
            $reserva->id_motivo = $request->id_motivo;
            $reserva->fecha = $request->fecha;
            $reserva->hora_inicio = $request->hora_inicio;
            $reserva->hora_fin = $request->hora_fin;
            $reserva->observaciones = $request->observaciones;
            $reserva->fecha_realizada = Carbon::now();
            $reserva->estado = $request->estado ?? 'Validada';
            $reserva->save();
            
            // Preparar mensaje para SweetAlert
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Reserva creada',
                'text' => 'La reserva ha sido creada exitosamente'
            ]);
            
            return redirect()->route('reserva_salas.index')
                ->with('success', 'Reserva creada exitosamente');
                
        } catch (\Exception $e) {
            // Log del error para debugging
            Log::error('Error al crear reserva: ' . $e->getMessage());
            
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo crear la reserva: ' . $e->getMessage()
            ]);
            
            return redirect()->route('reserva_salas.create')
                ->withInput()
                ->with('error', 'No se pudo crear la reserva');
        }
    }

    /**
     * Muestra la información de una reserva de sala específica
     */
    public function show($id_sala, $fecha, $hora_inicio, $estado)
    {
        $reserva = ReservaSala::with(['sala', 'usuario', 'motivo'])
            ->where('id_sala', $id_sala)
            ->where('fecha', $fecha)
            ->where('hora_inicio', $hora_inicio)
            ->where('estado', $estado)
            ->firstOrFail();
        
        return view('reserva_salas.show', compact('reserva'));
    }

    /**
     * Muestra el formulario para editar una reserva de sala existente
     */
    public function edit($id_sala, $fecha, $hora_inicio, $estado)
    {
        $reserva = ReservaSala::with(['sala', 'usuario', 'motivo'])
            ->where('id_sala', $id_sala)
            ->where('fecha', $fecha)
            ->where('hora_inicio', $hora_inicio)
            ->where('estado', $estado)
            ->firstOrFail();
            
        $salas = Sala::orderBy('nombre')->get();
        $motivos = Motivo::orderBy('descripcion')->get();
        $usuarios = Usuario::orderBy('apellidos')->get();
        
        return view('reserva_salas.edit', compact('reserva', 'salas', 'motivos', 'usuarios'));
    }

    /**
     * Actualiza una reserva de sala específica en la base de datos
     */
    public function update(Request $request, $id_sala, $fecha, $hora_inicio, $estado)
    {
        $reserva = ReservaSala::where('id_sala', $id_sala)
            ->where('fecha', $fecha)
            ->where('hora_inicio', $hora_inicio)
            ->where('estado', $estado)
            ->firstOrFail();
        
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'id_sala' => 'required|exists:sala,id_sala',
            'id_usuario' => 'required|exists:usuario,id_usuario',
            'id_motivo' => 'required|exists:motivo,id_motivo',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'observaciones' => 'nullable|string',
            'estado' => 'nullable|in:Validada,Pendiente Validación,Rechazada,Cancelada',
        ]);

        if ($validator->fails()) {
            return redirect()->route('reserva_salas.edit', [
                    'id_sala' => $id_sala,
                    'fecha' => $fecha,
                    'hora_inicio' => $hora_inicio,
                    'estado' => $estado
                ])
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar disponibilidad de la sala (si cambia sala, fecha u hora)
        if ($request->id_sala != $reserva->id_sala || 
            $request->fecha != $reserva->fecha || 
            $request->hora_inicio != $reserva->hora_inicio ||
            $request->hora_fin != $reserva->hora_fin) {
            
            $reservasExistentes = ReservaSala::where('id_sala', $request->id_sala)
                ->where('fecha', $request->fecha)
                ->where('estado', '!=', 'Cancelada')
                ->where('estado', '!=', 'Rechazada')
                ->where(function($query) use ($reserva, $request) {
                    $query->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                        ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                        ->orWhere(function($q) use ($request) {
                            $q->where('hora_inicio', '<=', $request->hora_inicio)
                              ->where('hora_fin', '>=', $request->hora_fin);
                        });
                })
                ->where(function($query) use ($reserva) {
                    $query->where('id_sala', '!=', $reserva->id_sala)
                        ->orWhere('fecha', '!=', $reserva->fecha)
                        ->orWhere('hora_inicio', '!=', $reserva->hora_inicio)
                        ->orWhere('estado', '!=', $reserva->estado);
                })
                ->exists();
                
            if ($reservasExistentes) {
                session()->flash('swal', [
                    'icon' => 'error',
                    'title' => 'Sala no disponible',
                    'text' => 'La sala ya está reservada para el horario seleccionado'
                ]);
                
                return redirect()->route('reserva_salas.edit', [
                        'id_sala' => $id_sala,
                        'fecha' => $fecha,
                        'hora_inicio' => $hora_inicio,
                        'estado' => $estado
                    ])
                    ->withInput()
                    ->with('error', 'La sala ya está reservada para el horario seleccionado');
            }
        }

        try {
            // Debido a que tenemos una clave primaria compuesta, eliminamos y creamos de nuevo
            DB::beginTransaction();
            
            // Borrar la reserva existente
            $reserva->delete();
            
            // Crear la nueva reserva con los datos actualizados
            $nuevaReserva = new ReservaSala();
            $nuevaReserva->id_sala = $request->id_sala;
            $nuevaReserva->id_usuario = $request->id_usuario;
            $nuevaReserva->id_motivo = $request->id_motivo;
            $nuevaReserva->fecha = $request->fecha;
            $nuevaReserva->hora_inicio = $request->hora_inicio;
            $nuevaReserva->hora_fin = $request->hora_fin;
            $nuevaReserva->observaciones = $request->observaciones;
            $nuevaReserva->fecha_realizada = $reserva->fecha_realizada;
            $nuevaReserva->estado = $request->estado ?? 'Validada';
            $nuevaReserva->save();
            
            DB::commit();
            
            // Preparar mensaje para SweetAlert
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Reserva actualizada',
                'text' => 'La reserva ha sido actualizada exitosamente'
            ]);
            
            return redirect()->route('reserva_salas.index')
                ->with('success', 'Reserva actualizada exitosamente');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            // Log del error para debugging
            Log::error('Error al actualizar reserva: ' . $e->getMessage());
            
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo actualizar la reserva: ' . $e->getMessage()
            ]);
            
            return redirect()->route('reserva_salas.edit', [
                    'id_sala' => $id_sala,
                    'fecha' => $fecha,
                    'hora_inicio' => $hora_inicio,
                    'estado' => $estado
                ])
                ->withInput()
                ->with('error', 'No se pudo actualizar la reserva');
        }
    }

   /**
 * Elimina una reserva de sala específica de la base de datos
 */
public function destroy($id_sala, $fecha, $hora_inicio, $estado)
{
    try {
        // Eliminar la reserva directamente mediante una consulta
        $deleted = ReservaSala::where('id_sala', $id_sala)
            ->where('fecha', $fecha)
            ->where('hora_inicio', $hora_inicio)
            ->where('estado', $estado)
            ->delete();
        
        if (!$deleted) {
            throw new \Exception('No se encontró la reserva para eliminar');
        }
        
        // Preparar mensaje para SweetAlert
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Reserva eliminada',
            'text' => 'La reserva ha sido eliminada exitosamente'
        ]);
        
        return redirect()->route('reserva_salas.index')
            ->with('success', 'Reserva eliminada exitosamente');
            
    } catch (\Exception $e) {
        // Log del error para debugging
        Log::error('Error al eliminar reserva: ' . $e->getMessage());
        
        session()->flash('swal', [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'No se pudo eliminar la reserva: ' . $e->getMessage()
        ]);
        
        return redirect()->route('reserva_salas.index')
            ->with('error', 'No se pudo eliminar la reserva');
    }
}

    /**
     * Cambia el estado de una reserva (cancelar, rechazar, validar)
     */
    /**
 * Cambia el estado de una reserva (cancelar, rechazar, validar)
 */
public function cambiarEstado(Request $request, $id_sala, $fecha, $hora_inicio, $estado)
{
    $nuevoEstado = $request->input('nuevo_estado');
    
    if (!in_array($nuevoEstado, ['Validada', 'Pendiente Validación', 'Rechazada', 'Cancelada'])) {
        session()->flash('swal', [
            'icon' => 'error',
            'title' => 'Estado no válido',
            'text' => 'El estado solicitado no es válido'
        ]);
        
        return redirect()->route('reserva_salas.index')
            ->with('error', 'Estado no válido');
    }
    
    try {
        // Obtener la reserva
        $reserva = ReservaSala::where('id_sala', $id_sala)
            ->where('fecha', $fecha)
            ->where('hora_inicio', $hora_inicio)
            ->where('estado', $estado)
            ->first();
            
        if (!$reserva) {
            throw new \Exception('No se encontró la reserva para cambiar el estado');
        }
        
        // Debido a que tenemos una clave primaria compuesta, primero hacemos una copia
        $datosReserva = [
            'id_sala' => $reserva->id_sala,
            'id_usuario' => $reserva->id_usuario,
            'id_motivo' => $reserva->id_motivo,
            'fecha' => $reserva->fecha,
            'hora_inicio' => $reserva->hora_inicio,
            'hora_fin' => $reserva->hora_fin,
            'observaciones' => $reserva->observaciones,
            'fecha_realizada' => $reserva->fecha_realizada,
            'estado' => $nuevoEstado
        ];
        
        // Transacción para eliminar y crear nuevamente
        DB::beginTransaction();
            
        // Eliminar la reserva anterior
        ReservaSala::where('id_sala', $id_sala)
            ->where('fecha', $fecha)
            ->where('hora_inicio', $hora_inicio)
            ->where('estado', $estado)
            ->delete();
        
        // Crear la nueva reserva con el estado actualizado
        ReservaSala::create($datosReserva);
        
        DB::commit();
        
        // Preparar mensaje para SweetAlert
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Estado actualizado',
            'text' => 'El estado de la reserva ha sido actualizado exitosamente'
        ]);
        
        return redirect()->route('reserva_salas.index')
            ->with('success', 'Estado de la reserva actualizado exitosamente');
            
    } catch (\Exception $e) {
        DB::rollback();
        
        // Log del error para debugging
        Log::error('Error al cambiar estado de reserva: ' . $e->getMessage());
        
        session()->flash('swal', [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'No se pudo cambiar el estado de la reserva: ' . $e->getMessage()
        ]);
        
        return redirect()->route('reserva_salas.index')
            ->with('error', 'No se pudo cambiar el estado de la reserva');
    }
}

    /**
     * Muestra el calendario de reservas
     */
    public function calendario(Request $request)
{
    Carbon::setLocale('es');
    
    // Obtener salas
    $salas = Sala::orderBy('nombre')->get();
    
    // Obtener sala seleccionada o la primera por defecto
    $salaSeleccionada = $request->input('id_sala', $salas->first()->id_sala ?? null);
    $salaInfo = Sala::find($salaSeleccionada);
    
    // Determinar la semana actual o la seleccionada
    $semana = $request->input('semana', date('Y-W'));
    
    // Corregir el formato del valor de semana para extraer año y número de semana
    if (preg_match('/^(\d{4})-W?(\d{1,2})$/', $semana, $matches)) {
        $year = (int)$matches[1];
        $week = (int)$matches[2];
    } else {
        // Si el formato no es válido, usar la semana actual
        $year = (int)date('Y');
        $week = (int)date('W');
    }
    
    // Crear array de días (lunes a viernes) para la semana seleccionada
    $dias = [];
    $fechas = [];
    $hoy = Carbon::today()->format('Y-m-d');
    
    // El primer día de la semana es lunes (usar enteros para los parámetros)
    $primerDia = Carbon::now()->setISODate($year, $week, 1);
    
    for ($i = 0; $i < 5; $i++) { // 5 días (lunes a viernes)
        $fecha = $primerDia->copy()->addDays($i);
        $fechaFormato = $fecha->format('Y-m-d');
        
        $dias[] = [
            'nombre' => ucfirst($fecha->dayName), // Capitalizar el nombre del día
            'fecha' => $fecha->format('d/m'),
            'fecha_completa' => $fechaFormato,
            'esHoy' => $fechaFormato === $hoy
        ];
        
        $fechas[] = $fechaFormato;
    }
    
    // Crear array de intervalos de media hora desde las 8:00 hasta las 22:00
    $intervalos = [];
    for ($h = 8; $h < 22; $h++) {
        $horaCompleta = sprintf('%02d:00', $h);
        $mediaHora = sprintf('%02d:30', $h);
        
        $intervalos[] = [
            'hora' => $horaCompleta,
            'valor' => $horaCompleta,
            'esComienzo' => true
        ];
        
        $intervalos[] = [
            'hora' => $mediaHora,
            'valor' => $mediaHora,
            'esComienzo' => false
        ];
    }
    
    // Obtener reservas para esta sala y semana
    $reservasSemana = ReservaSala::with(['usuario', 'motivo'])
        ->where('id_sala', $salaSeleccionada)
        ->whereIn('fecha', $fechas)
        ->where('estado', '!=', 'Cancelada') 
        ->orderBy('fecha')
        ->orderBy('hora_inicio')
        ->get();
    
    // Organizar las reservas por día y hora
    $reservas = [];
    foreach ($reservasSemana as $reserva) {
        // Determinar el índice del día (0 = lunes, 1 = martes, etc.)
        $indiceDia = array_search($reserva->fecha->format('Y-m-d'), $fechas);
        
        if ($indiceDia !== false) {
            // Determinar la hora de inicio más cercana a los intervalos definidos
            $horaInicioReserva = $reserva->hora_inicio->format('H:i');
            
            // Redondear a la media hora más cercana
            $minutos = (int)$reserva->hora_inicio->format('i');
            $horaInicio = $reserva->hora_inicio->format('H') . ':' . ($minutos < 30 ? '00' : '30');
            
            // Añadir la reserva al array en la posición correspondiente
            if (!isset($reservas[$indiceDia][$horaInicio])) {
                $reservas[$indiceDia][$horaInicio] = [];
            }
            
            $reservas[$indiceDia][$horaInicio][] = $reserva;
        }
    }
    
    // Ajustar el formato de semana para devolverlo a la vista
    $semana = $year . '-W' . str_pad($week, 2, '0', STR_PAD_LEFT);
    
    return view('reserva_salas.calendario', compact(
        'salas', 
        'salaSeleccionada', 
        'salaInfo',
        'semana', 
        'dias', 
        'intervalos', 
        'reservas'
    ));
}

    /**
     * Obtiene los eventos de reservas para el calendario en formato JSON
     */
    public function obtenerEventosCalendario(Request $request)
    {
        $idSala = $request->input('id_sala');
        $inicio = $request->input('start'); // Fecha de inicio del calendario
        $fin = $request->input('end'); // Fecha de fin del calendario
        
        $query = ReservaSala::with(['usuario', 'motivo'])
            ->whereBetween('fecha', [$inicio, $fin])
            ->where('estado', '!=', 'Cancelada')
            ->where('estado', '!=', 'Rechazada');
            
        if ($idSala) {
            $query->where('id_sala', $idSala);
        }
        
        $reservas = $query->get();
        
        $eventos = [];
        foreach ($reservas as $reserva) {
            // Combinar fecha con hora para crear datetime
            $fechaInicio = Carbon::parse($reserva->fecha->format('Y-m-d') . ' ' . $reserva->hora_inicio->format('H:i:s'));
            $fechaFin = Carbon::parse($reserva->fecha->format('Y-m-d') . ' ' . $reserva->hora_fin->format('H:i:s'));
            
            // Crear colores según el estado
            $backgroundColor = '#28a745'; // Verde para validada
            if ($reserva->estado === 'Pendiente Validación') {
                $backgroundColor = '#ffc107'; // Amarillo para pendiente
            }
            
            $eventos[] = [
                'id' => $reserva->id_sala . '_' . $reserva->fecha->format('Y-m-d') . '_' . 
                       $reserva->hora_inicio->format('H:i:s') . '_' . $reserva->estado,
                'title' => ($idSala ? '' : '[' . $reserva->sala->nombre . '] ') . 
                          $reserva->usuario->nombre_completo . ' - ' . $reserva->motivo->descripcion,
                'start' => $fechaInicio->toIso8601String(),
                'end' => $fechaFin->toIso8601String(),
                'backgroundColor' => $backgroundColor,
                'borderColor' => $backgroundColor,
                'url' => route('reserva_salas.show', [
                    'id_sala' => $reserva->id_sala,
                    'fecha' => $reserva->fecha->format('Y-m-d'),
                    'hora_inicio' => $reserva->hora_inicio->format('H:i:s'),
                    'estado' => $reserva->estado
                ])
            ];
        }
        
        return response()->json($eventos);
    }

/**
 * Verifica si una sala está disponible para un horario específico
 */
public function verificarDisponibilidad(Request $request)
{
    $idSala = $request->input('id_sala');
    $fecha = $request->input('fecha');
    $horaInicio = $request->input('hora_inicio');
    $horaFin = $request->input('hora_fin');
    
    // Verificar si hay reservas que se solapen con el horario solicitado
    $reservasExistentes = ReservaSala::where('id_sala', $idSala)
        ->where('fecha', $fecha)
        ->where('estado', '!=', 'Cancelada')
        ->where('estado', '!=', 'Rechazada')
        ->where(function($query) use ($horaInicio, $horaFin) {
            // Verificar solapamiento de horarios:
            // 1. Si la hora de inicio está dentro de una reserva existente
            // 2. Si la hora de fin está dentro de una reserva existente
            // 3. Si el horario solicitado engloba completamente una reserva existente
            $query->where(function($q) use ($horaInicio, $horaFin) {
                $q->whereTime('hora_inicio', '<=', $horaInicio)
                  ->whereTime('hora_fin', '>', $horaInicio);
            })->orWhere(function($q) use ($horaInicio, $horaFin) {
                $q->whereTime('hora_inicio', '<', $horaFin)
                  ->whereTime('hora_fin', '>=', $horaFin);
            })->orWhere(function($q) use ($horaInicio, $horaFin) {
                $q->whereTime('hora_inicio', '>=', $horaInicio)
                  ->whereTime('hora_fin', '<=', $horaFin);
            });
        })
        ->first();
    
    if ($reservasExistentes) {
        // Si hay una reserva existente, devolver información
        $reservaActual = "Sala reservada de " . $reservasExistentes->hora_inicio->format('H:i') . 
                        " a " . $reservasExistentes->hora_fin->format('H:i');
        
        if ($reservasExistentes->id_usuario == Auth::id()) {
            $reservaActual .= " (por ti)";
        } else {
            $reservaActual .= " (por otro usuario)";
        }
        
        return response()->json([
            'disponible' => false,
            'reserva_actual' => $reservaActual
        ]);
    }
    
    // Si no hay reservas que se solapen, la sala está disponible
    return response()->json(['disponible' => true]);
}
}