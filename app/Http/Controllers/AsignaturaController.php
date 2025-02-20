<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use Illuminate\Http\Request;
use App\Models\Titulacion;

class AsignaturaController extends Controller
{
    public function index()
    {
        // Cargar las asignaturas con su titulacion asociada
        $asignaturas = Asignatura::with('titulacion')->get();
        return view('asignaturas.index', compact('asignaturas'));
    }

    public function show($id)
    {
        // Cargar la asignatura con su titulacion
        $asignatura = Asignatura::with('titulacion')->find($id);
        if (is_null($asignatura)) {
            return redirect()->route('asignaturas.index')->with('error', 'Asignatura not found');
        }
        return view('asignaturas.show', compact('asignatura'));
    }

    public function create()
{
    $titulaciones = Titulacion::all(); // Obtener todas las titulaciones
    return view('asignaturas.create', compact('titulaciones'));
}


    public function store(Request $request)
    {
        $asignatura = Asignatura::create($request->all());
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Asignatura eliminada',
            'text' => 'La asignatura ha sido creada exitosamente' 
        ]);
        return redirect()->route('asignaturas.index')->with('success', 'Asignatura created successfully');
    }

    public function edit($id)
    {
        $titulaciones = Titulacion::all(); // Obtener todas las titulaciones
        $asignatura = Asignatura::with('titulacion')->find($id);
        if (is_null($asignatura)) {
            return redirect()->route('asignaturas.index')->with('error', 'Asignatura not found');
        }
        return view('asignaturas.edit', compact('asignatura'), compact('titulaciones'));
    }

    public function update(Request $request, $id)
    {
        $asignatura = Asignatura::find($id);
        if (is_null($asignatura)) {
            return redirect()->route('asignaturas.index')->with('error', 'Asignatura not found');
        }
        $asignatura->update($request->all());
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Asignatura actualizada',
            'text' => 'La asignatura ha sido eliminada exitosamente' 
        ]);
        return redirect()->route('asignaturas.index')->with('success', 'Asignatura updated successfully');
    }

    public function destroy($id)
    {
        $asignatura = Asignatura::find($id);
        if (is_null($asignatura)) {
            return redirect()->route('asignaturas.index')->with('error', 'Asignatura not found');
        }
        $asignatura->delete();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Asignatura eliminada',
            'text' => 'La asignatura ha sido eliminada exitosamente' 
        ]);
        return redirect()->route('asignaturas.index')->with('success', 'Asignatura deleted successfully');
    }

    public function grupos()
    {

        // Cargar las asignaturas con su titulacion asociada
        $asignaturas = Asignatura::with('titulacion')->get();
        return view('asignaturas.grupos', compact('asignaturas'));
    }

    public function updateGrupos(Request $request, $asignatura)
{
    $asignatura = Asignatura::findOrFail($asignatura);
    
    // Validamos los datos
    $validated = $request->validate([
        'grupos_teoria' => 'nullable|integer',
        'grupos_practicas' => 'nullable|integer',
        'fraccionable' => 'nullable'
    ]);

    // Actualizamos solo el campo que se ha enviado
    if ($request->has('grupos_teoria')) {
        $asignatura->grupos_teoria = $request->grupos_teoria;
    }
    if ($request->has('grupos_practicas')) {
        $asignatura->grupos_practicas = $request->grupos_practicas;
    }
    if ($request->has('fraccionable')) {
        $asignatura->fraccionable = $request->has('fraccionable');
    }

    $asignatura->save();
    
    return redirect()->route('asignaturas.grupos')->with('success', 'Grupos actualizados correctamente');
}
}
