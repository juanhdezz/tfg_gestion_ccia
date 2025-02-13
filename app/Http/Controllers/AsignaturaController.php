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
        return redirect()->route('asignaturas.index')->with('success', 'Asignatura created successfully');
    }

    public function edit($id)
    {
        $asignatura = Asignatura::with('titulacion')->find($id);
        if (is_null($asignatura)) {
            return redirect()->route('asignaturas.index')->with('error', 'Asignatura not found');
        }
        return view('asignaturas.edit', compact('asignatura'));
    }

    public function update(Request $request, $id)
    {
        $asignatura = Asignatura::find($id);
        if (is_null($asignatura)) {
            return redirect()->route('asignaturas.index')->with('error', 'Asignatura not found');
        }
        $asignatura->update($request->all());
        return redirect()->route('asignaturas.index')->with('success', 'Asignatura updated successfully');
    }

    public function destroy($id)
    {
        $asignatura = Asignatura::find($id);
        if (is_null($asignatura)) {
            return redirect()->route('asignaturas.index')->with('error', 'Asignatura not found');
        }
        $asignatura->delete();
        return redirect()->route('asignaturas.index')->with('success', 'Asignatura deleted successfully');
    }
}
