<?php

namespace App\Http\Controllers;

use App\Models\Plazo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PlazoController extends Controller
{
    /**
     * Muestra un listado de los plazos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $busqueda = $request->get('search');
        $filtro = $request->get('filtro', 'todos');

        $query = Plazo::query();
        
        // Aplicar filtro de búsqueda
        $query->buscar($busqueda);
        
        // Aplicar filtro de estado
        switch ($filtro) {
            case 'activos':
                $query->activos();
                break;
            case 'finalizados':
                $query->finalizados();
                break;
            case 'pendientes':
                $query->pendientes();
                break;
        }
        
        // Ordenar por fecha_inicio descendente
        $plazos = $query->orderBy('fecha_inicio', 'desc')->get();
        
        // Verificar si se debe mostrar la vista de plazos
        // $fechaInicio = Carbon::create(2025, 1, 1);
        // $fechaFin = Carbon::create(2025, 2, 1);
        // $hoy = Carbon::now();
        // $mostrarVista = $hoy->between($fechaInicio, $fechaFin);
        
        return view('plazos.index', compact('plazos', 'filtro', 'busqueda'));
    }

    /**
     * Muestra el formulario para crear un nuevo plazo.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('plazos.create');
    }

    /**
     * Almacena un nuevo plazo en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_plazo' => 'required|string|max:128',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
        ]);

        Plazo::create($validated);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Plazo creado',
            'text' => 'El plazo ha sido creado exitosamente',
        ]);

        return redirect()->route('plazos.index');
    }

    /**
     * Muestra el plazo especificado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $plazo = Plazo::findOrFail($id);
        return view('plazos.show', compact('plazo'));
    }

    /**
     * Muestra el formulario para editar el plazo especificado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $plazo = Plazo::findOrFail($id);
        return view('plazos.edit', compact('plazo'));
    }

    /**
     * Actualiza el plazo especificado en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $plazo = Plazo::findOrFail($id);

        $validated = $request->validate([
            'nombre_plazo' => 'required|string|max:128',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
        ]);

        $plazo->update($validated);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Plazo actualizado',
            'text' => 'El plazo ha sido actualizado exitosamente',
        ]);

        return redirect()->route('plazos.index');
    }

    /**
     * Elimina el plazo especificado de la base de datos.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plazo = Plazo::findOrFail($id);
        $plazo->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Plazo eliminado',
            'text' => 'El plazo ha sido eliminado exitosamente',
        ]);

        return redirect()->route('plazos.index');
    }
    
    /**
     * Muestra los plazos activos.
     *
     * @return \Illuminate\Http\Response
     */
    public function plazosActivos()
    {
        $plazos = Plazo::activos()->orderBy('fecha_fin')->get();
        return view('plazos.activos', compact('plazos'));
    }
    
    /**
     * API: Obtiene todos los plazos
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        $plazos = Plazo::orderBy('fecha_inicio', 'desc')->get()->map(function ($plazo) {
            return [
                'id' => $plazo->id_plazo,
                'nombre' => $plazo->nombre_plazo,
                'fecha_inicio' => $plazo->fecha_inicio->format('Y-m-d'),
                'fecha_fin' => $plazo->fecha_fin->format('Y-m-d'),
                'descripcion' => $plazo->descripcion,
                'estado' => $plazo->estado,
                'dias_restantes' => $plazo->diasRestantes(),
                'porcentaje' => $plazo->porcentajeTranscurrido()
            ];
        });
        
        return response()->json($plazos);
    }
    
    /**
     * API: Obtiene los plazos activos
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiActivos()
    {
        $plazos = Plazo::activos()->orderBy('fecha_fin')->get()->map(function ($plazo) {
            return [
                'id' => $plazo->id_plazo,
                'nombre' => $plazo->nombre_plazo,
                'fecha_inicio' => $plazo->fecha_inicio->format('Y-m-d'),
                'fecha_fin' => $plazo->fecha_fin->format('Y-m-d'),
                'descripcion' => $plazo->descripcion,
                'dias_restantes' => $plazo->diasRestantes(),
                'porcentaje' => $plazo->porcentajeTranscurrido()
            ];
        });
        
        return response()->json($plazos);
    }
}