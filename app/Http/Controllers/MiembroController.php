<?php

namespace App\Http\Controllers;

use App\Models\Miembro;
use App\Models\Usuario;
use App\Models\CategoriaDocente;
use App\Models\Grupo;
use Illuminate\Http\Request;

class MiembroController extends Controller
{
    /**
     * Mostrar una lista de todos los miembros.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $miembros = Miembro::with(['usuario', 'grupo', 'categoriaDocente'])
                          ->ordenadoPorNumero()
                          ->get();
        
        return view('miembros.index', compact('miembros'));
    }

    /**
     * Mostrar miembros de un grupo específico.
     *
     * @param int $grupoId
     * @return \Illuminate\Http\Response
     */
    public function porGrupo($grupoId)
    {
        $grupo = Grupo::findOrFail($grupoId);
        $miembros = $grupo->miembrosOrdenados();
        
        return view('miembros.por-grupo', compact('grupo', 'miembros'));
    }

    /**
     * Mostrar miembros de una categoría específica.
     *
     * @param int $categoriaId
     * @return \Illuminate\Http\Response
     */
    public function porCategoria($categoriaId)
    {
        $categoria = CategoriaDocente::findOrFail($categoriaId);
        $miembros = $categoria->miembrosOrdenados();
        
        return view('miembros.por-categoria', compact('categoria', 'miembros'));
    }

    /**
     * Mostrar el formulario para crear un nuevo miembro.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usuarios = Usuario::orderBy('apellidos')->orderBy('nombre')->get();
        $grupos = Grupo::orderBy('nombre_grupo')->get();
        $categorias = CategoriaDocente::all();
        
        return view('miembros.create', compact('usuarios', 'grupos', 'categorias'));
    }

    /**
     * Almacenar un nuevo miembro en la base de datos.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:usuario,id_usuario',
            'id_grupo' => 'required|exists:grupo,id_grupo',
            'id_categoria' => 'required|exists:categoria,id_categoria',
            'numero_orden' => 'nullable|integer|min:1',
            'tramos_investigacion' => 'nullable|integer|min:0',
            'anio_ultimo_tramo' => 'nullable|integer|min:1990|max:' . date('Y'),
            'fecha_entrada' => 'nullable|date',
            'n_orden_becario' => 'nullable|integer|min:1',
            'web' => 'nullable|url'
        ]);

        // Verificar si el miembro ya existe
        $existeMiembro = Miembro::where('id_usuario', $request->id_usuario)
                               ->where('id_grupo', $request->id_grupo)
                               ->where('id_categoria', $request->id_categoria)
                               ->exists();

        if ($existeMiembro) {
            return redirect()->back()
                           ->withErrors(['error' => 'Este usuario ya es miembro de este grupo con esta categoría.'])
                           ->withInput();
        }

        Miembro::create($request->all());

        return redirect()->route('miembros.index')
                        ->with('success', 'Miembro creado exitosamente.');
    }

    /**
     * Mostrar un miembro específico.
     *
     * @param int $userId
     * @param int $groupId
     * @param int $categoryId
     * @return \Illuminate\Http\Response
     */
    public function show($userId, $groupId, $categoryId)
    {
        $miembro = Miembro::where('id_usuario', $userId)
                         ->where('id_grupo', $groupId)
                         ->where('id_categoria', $categoryId)
                         ->with(['usuario', 'grupo', 'categoriaDocente'])
                         ->firstOrFail();

        return view('miembros.show', compact('miembro'));
    }

    /**
     * Mostrar el formulario para editar un miembro.
     *
     * @param int $userId
     * @param int $groupId
     * @param int $categoryId
     * @return \Illuminate\Http\Response
     */
    public function edit($userId, $groupId, $categoryId)
    {
        $miembro = Miembro::where('id_usuario', $userId)
                         ->where('id_grupo', $groupId)
                         ->where('id_categoria', $categoryId)
                         ->with(['usuario', 'grupo', 'categoriaDocente'])
                         ->firstOrFail();

        $usuarios = Usuario::orderBy('apellidos')->orderBy('nombre')->get();
        $grupos = Grupo::orderBy('nombre_grupo')->get();
        $categorias = CategoriaDocente::all();

        return view('miembros.edit', compact('miembro', 'usuarios', 'grupos', 'categorias'));
    }

    /**
     * Actualizar un miembro en la base de datos.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $userId
     * @param int $groupId
     * @param int $categoryId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $userId, $groupId, $categoryId)
    {
        $request->validate([
            'numero_orden' => 'nullable|integer|min:1',
            'tramos_investigacion' => 'nullable|integer|min:0',
            'anio_ultimo_tramo' => 'nullable|integer|min:1990|max:' . date('Y'),
            'fecha_entrada' => 'nullable|date',
            'n_orden_becario' => 'nullable|integer|min:1',
            'web' => 'nullable|url'
        ]);

        $miembro = Miembro::where('id_usuario', $userId)
                         ->where('id_grupo', $groupId)
                         ->where('id_categoria', $categoryId)
                         ->firstOrFail();

        $miembro->update($request->only([
            'numero_orden',
            'tramos_investigacion',
            'anio_ultimo_tramo',
            'fecha_entrada',
            'n_orden_becario',
            'web'
        ]));

        return redirect()->route('miembros.show', [$userId, $groupId, $categoryId])
                        ->with('success', 'Miembro actualizado exitosamente.');
    }

    /**
     * Eliminar un miembro de la base de datos.
     *
     * @param int $userId
     * @param int $groupId
     * @param int $categoryId
     * @return \Illuminate\Http\Response
     */
    public function destroy($userId, $groupId, $categoryId)
    {
        $miembro = Miembro::where('id_usuario', $userId)
                         ->where('id_grupo', $groupId)
                         ->where('id_categoria', $categoryId)
                         ->firstOrFail();

        $miembro->delete();

        return redirect()->route('miembros.index')
                        ->with('success', 'Miembro eliminado exitosamente.');
    }

    /**
     * Actualizar el orden de los miembros de un grupo.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $grupoId
     * @return \Illuminate\Http\Response
     */
    public function actualizarOrden(Request $request, $grupoId)
    {
        $request->validate([
            'miembros' => 'required|array',
            'miembros.*.id_usuario' => 'required|exists:usuario,id_usuario',
            'miembros.*.id_categoria' => 'required|exists:categoria,id_categoria',
            'miembros.*.numero_orden' => 'required|integer|min:1'
        ]);

        foreach ($request->miembros as $miembroData) {
            Miembro::where('id_usuario', $miembroData['id_usuario'])
                  ->where('id_grupo', $grupoId)
                  ->where('id_categoria', $miembroData['id_categoria'])
                  ->update(['numero_orden' => $miembroData['numero_orden']]);
        }

        return redirect()->route('miembros.por-grupo', $grupoId)
                        ->with('success', 'Orden de miembros actualizado exitosamente.');
    }
}
