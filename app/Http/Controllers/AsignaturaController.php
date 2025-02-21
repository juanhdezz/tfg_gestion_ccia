<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use Illuminate\Http\Request;
use App\Models\Titulacion;
use App\Models\GrupoTeoriaPractica;

class AsignaturaController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');

    $asignaturas = Asignatura::with(['titulacion', 'grupos'])
        ->when($search, function ($query, $search) {
            return $query->where('nombre_asignatura', 'LIKE', "%{$search}%");
        })
        ->get();

    // Calcular los totales de grupos de teoría y práctica por asignatura
    foreach ($asignaturas as $asignatura) {
        $asignatura->total_grupos_teoria = $asignatura->grupos->whereNotNull('grupo_teoria')->unique('grupo_teoria')->count();
        $asignatura->total_grupos_practica = $asignatura->grupos->whereNotNull('grupo_practica')->count();
    }

    return view('asignaturas.index', compact('asignaturas'));
}
    


    public function show($id)
{
    // Cargar la asignatura con los grupos de teoría y práctica y la titulacion asociada
    $asignatura = Asignatura::with(['grupos', 'titulacion'])->find($id);

    if (is_null($asignatura)) {
        return redirect()->route('asignaturas.index')->with('error', 'Asignatura no encontrada');
    }

    // Devolvemos la vista con los datos de la asignatura y su titulacion
    return view('asignaturas.show', compact('asignatura'));
}


    public function create()
{
    $titulaciones = Titulacion::all(); // Obtener todas las titulaciones
    return view('asignaturas.create', compact('titulaciones'));
}


public function store(Request $request)
{
    // Validamos los datos del formulario para la asignatura
    $validated = $request->validate([
        'nombre_asignatura' => 'required|string|max:255',
        'id_titulacion' => 'required|exists:titulaciones,id_titulacion',
        'grupos_teoria' => 'nullable|array',
        'grupos_practicas' => 'nullable|array',
        'fraccionable' => 'nullable|boolean',
        // Otros campos que sean necesarios
    ]);

    // Creamos la asignatura
    $asignatura = Asignatura::create($request->all());

    // Si se han enviado los grupos de teoría
    if ($request->has('grupos_teoria')) {
        foreach ($request->grupos_teoria as $grupo_teoria) {
            // Creamos un grupo de teoría asociado a la asignatura
            $asignatura->grupos()->create([
                'grupo_teoria' => $grupo_teoria,
                'grupo_practica' => null, // No es un grupo de práctica
            ]);
        }
    }

    // Si se han enviado los grupos de prácticas
    if ($request->has('grupos_practicas')) {
        foreach ($request->grupos_practicas as $grupo_practica) {
            // Creamos un grupo de práctica asociado a la asignatura
            $asignatura->grupos()->create([
                'grupo_teoria' => null, // No es un grupo de teoría
                'grupo_practica' => $grupo_practica,
            ]);
        }
    }

    // Flash message y redirección
    session()->flash('swal', [
        'icon' => 'success',
        'title' => 'Asignatura creada',
        'text' => 'La asignatura ha sido creada exitosamente',
    ]);

    return redirect()->route('asignaturas.index')->with('success', 'Asignatura created successfully');
}


public function edit($id)
{
    $titulaciones = Titulacion::all(); // Obtener todas las titulaciones
    $asignatura = Asignatura::with(['grupos', 'titulacion'])->find($id);

    if (is_null($asignatura)) {
        return redirect()->route('asignaturas.index')->with('error', 'Asignatura no encontrada');
    }

    // Devolvemos la vista con la asignatura, titulaciones y los grupos asociados
    return view('asignaturas.edit', compact('asignatura', 'titulaciones'));
}


    public function update(Request $request, $id)
{
    $asignatura = Asignatura::find($id);
    if (is_null($asignatura)) {
        return redirect()->route('asignaturas.index')->with('error', 'Asignatura no encontrada');
    }

    // Validamos los datos
    $validated = $request->validate([
        'nombre_asignatura' => 'required|string|max:255',
        'id_titulacion' => 'required|exists:titulaciones,id_titulacion',
        'grupos_teoria' => 'nullable|array',
        'grupos_practicas' => 'nullable|array',
        // Otros campos que sean necesarios
    ]);

    // Actualizamos los datos de la asignatura
    $asignatura->update($request->all());

    // Eliminar los grupos existentes para la asignatura (si es necesario)
    $asignatura->grupos()->delete();

    // Guardar los nuevos grupos de teoría
    if ($request->has('grupos_teoria')) {
        foreach ($request->grupos_teoria as $grupo_teoria) {
            $asignatura->grupos()->create([
                'grupo_teoria' => $grupo_teoria,
                'grupo_practica' => null,
            ]);
        }
    }

    // Guardar los nuevos grupos de práctica
    if ($request->has('grupos_practicas')) {
        foreach ($request->grupos_practicas as $grupo_practica) {
            $asignatura->grupos()->create([
                'grupo_teoria' => null,
                'grupo_practica' => $grupo_practica,
            ]);
        }
    }

    session()->flash('swal', [
        'icon' => 'success',
        'title' => 'Asignatura actualizada',
        'text' => 'La asignatura ha sido actualizada correctamente',
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

    public function grupos(Request $request)
{
    $search = $request->input('search');

    $asignaturas = Asignatura::with(['titulacion', 'grupos'])
        ->when($search, function ($query, $search) {
            return $query->where('nombre_asignatura', 'LIKE', "%{$search}%");
        })
        ->get();

    return view('asignaturas.grupos', compact('asignaturas'));
}

public function updateGrupos(Request $request, $asignatura_id)
{
    $asignatura = Asignatura::findOrFail($asignatura_id);

    // Actualizar fraccionable
    if ($request->has('fraccionable')) {
        $asignatura->fraccionable = $request->boolean('fraccionable');
        $asignatura->save();
        return redirect()->route('asignaturas.grupos')
            ->with('success', 'Estado fraccionable actualizado correctamente');
    }

    // Eliminar grupo de teoría y sus prácticas asociadas
    if ($request->has('eliminar_grupo_teoria')) {
        $numeroGrupoTeoria = $request->eliminar_grupo_teoria;
        $asignatura->grupos()
            ->where('grupo_teoria', $numeroGrupoTeoria)
            ->delete();
        return redirect()->route('asignaturas.grupos')
            ->with('success', 'Grupo de teoría y sus prácticas eliminados correctamente');
    }

    // Eliminar grupo de práctica específico
    if ($request->has('eliminar_grupo_practica')) {
        $grupoId = $request->eliminar_grupo_practica;
        $grupo = GrupoTeoriaPractica::find($grupoId);
        if ($grupo && $grupo->id_asignatura == $asignatura_id) {
            $grupo->delete();
            return redirect()->route('asignaturas.grupos')
                ->with('success', 'Grupo de práctica eliminado correctamente');
        }
    }

    // Crear nuevo grupo de teoría
    if ($request->has('nuevo_grupo_teoria')) {
        // Encontrar el último número de grupo de teoría
        $ultimoGrupo = $asignatura->grupos()
            ->whereNotNull('grupo_teoria')
            ->max('grupo_teoria');
        
        $nuevoNumeroGrupo = ($ultimoGrupo ?? 0) + 1;
        
        // Crear el grupo de teoría con un grupo de práctica inicial
        $asignatura->grupos()->create([
            'grupo_teoria' => $nuevoNumeroGrupo,
            'grupo_practica' => 1, // Primer grupo de práctica
        ]);

        return redirect()->route('asignaturas.grupos')
            ->with('success', 'Nuevo grupo de teoría añadido correctamente');
    }

    // Añadir nuevo grupo de práctica a un grupo de teoría existente
    if ($request->has('nuevo_grupo_practica')) {
        $grupoTeoria = $request->nuevo_grupo_practica;
        
        // Encontrar el último número de grupo de práctica para este grupo de teoría
        $ultimoGrupoPractica = $asignatura->grupos()
            ->where('grupo_teoria', $grupoTeoria)
            ->max('grupo_practica');
        
        $asignatura->grupos()->create([
            'grupo_teoria' => $grupoTeoria,
            'grupo_practica' => ($ultimoGrupoPractica ?? 0) + 1,
        ]);

        return redirect()->route('asignaturas.grupos')
            ->with('success', 'Nuevo grupo de práctica añadido correctamente');
    }

    // Actualizar grupos existentes
    if ($request->has('grupos_teoria')) {
        foreach ($request->grupos_teoria as $numGrupoTeoria => $datos) {
            // Actualizar número de grupo de teoría si ha cambiado
            if (isset($datos['numero']) && $datos['numero'] != $numGrupoTeoria) {
                $asignatura->grupos()
                    ->where('grupo_teoria', $numGrupoTeoria)
                    ->update(['grupo_teoria' => $datos['numero']]);
            }

            // Actualizar grupos de práctica
            if (isset($datos['practicas'])) {
                $gruposPractica = $asignatura->grupos()
                    ->where('grupo_teoria', $datos['numero'] ?? $numGrupoTeoria)
                    ->whereNotNull('grupo_practica')
                    ->orderBy('grupo_practica')
                    ->get();

                foreach ($gruposPractica as $index => $grupo) {
                    if (isset($datos['practicas'][$index])) {
                        $grupo->update([
                            'grupo_practica' => $datos['practicas'][$index]
                        ]);
                    }
                }
            }
        }

        return redirect()->route('asignaturas.grupos')
            ->with('success', 'Grupos actualizados correctamente');
    }

    return redirect()->route('asignaturas.grupos')
        ->with('error', 'No se recibieron datos para actualizar');
}
}
