<?php

namespace App\Http\Controllers;

use App\Models\Despacho;
use App\Models\Centro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DespachoController extends Controller
{
    /**
     * Muestra el listado de despachos
     */
    public function index(Request $request)
    {
        // Recuperar el término de búsqueda
        $search = $request->input('search');

        // Consulta base
        $query = Despacho::with('centro');

        // Aplicar filtro de búsqueda si existe
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nombre_despacho', 'LIKE', "%{$search}%")
                  ->orWhere('siglas_despacho', 'LIKE', "%{$search}%")
                  ->orWhere('telefono_despacho', 'LIKE', "%{$search}%")
                  ->orWhereHas('centro', function($q) use ($search) {
                      $q->where('nombre_centro', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Obtener resultados paginados
        $despachos = $query->orderBy('nombre_despacho')->paginate(10);

        return view('despachos.index', compact('despachos', 'search'));
    }

    /**
     * Muestra el formulario para crear un nuevo despacho
     */
    public function create()
    {
        // Cargar los centros para el select
        $centros = Centro::orderBy('nombre_centro')->get();
        
        return view('despachos.create', compact('centros'));
    }

    /**
     * Almacena un nuevo despacho en la base de datos
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'id_centro' => 'required|exists:centro,id_centro',
            'nombre_despacho' => 'required|string|max:100',
            'siglas_despacho' => 'nullable|string|max:20',
            'telefono_despacho' => 'nullable|string|max:20',
            'numero_puestos' => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('despachos.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Crear el nuevo despacho
        $despacho = Despacho::create($request->all());

        // Preparar mensaje para SweetAlert
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Despacho creado',
            'text' => "El despacho {$despacho->nombre_despacho} ha sido creado exitosamente"
        ]);

        return redirect()->route('despachos.index')
            ->with('success', 'Despacho creado exitosamente');
    }

    /**
     * Muestra la información de un despacho específico
     */
    public function show($id)
    {
        $despacho = Despacho::with('centro')->findOrFail($id);
        
        return view('despachos.show', compact('despacho'));
    }

    /**
     * Muestra el formulario para editar un despacho existente
     */
    public function edit($id)
    {
        $despacho = Despacho::findOrFail($id);
        $centros = Centro::orderBy('nombre_centro')->get();
        
        return view('despachos.edit', compact('despacho', 'centros'));
    }

    /**
     * Actualiza un despacho específico en la base de datos
     */
    public function update(Request $request, $id)
    {
        $despacho = Despacho::findOrFail($id);
        
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'id_centro' => 'required|exists:centro,id_centro',
            'nombre_despacho' => 'required|string|max:100',
            'siglas_despacho' => 'nullable|string|max:20',
            'telefono_despacho' => 'nullable|string|max:20',
            'numero_puestos' => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('despachos.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        // Actualizar el despacho
        $despacho->update($request->all());

        // Preparar mensaje para SweetAlert
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Despacho actualizado',
            'text' => "El despacho {$despacho->nombre_despacho} ha sido actualizado exitosamente"
        ]);

        return redirect()->route('despachos.index')
            ->with('success', 'Despacho actualizado exitosamente');
    }

    /**
 * Elimina un despacho específico de la base de datos
 */
public function destroy($id)
{
    $despacho = Despacho::findOrFail($id);
    $nombre = $despacho->nombre_despacho;

    try {
        // Verificar si el despacho tiene usuarios asignados
        if ($despacho->usuarios()->exists()) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'No se puede eliminar',
                'text' => "El despacho {$nombre} tiene usuarios asignados y no puede ser eliminado"
            ]);
            
            return redirect()->route('despachos.index')
                ->with('error', 'El despacho tiene usuarios asignados y no puede ser eliminado');
        }

        // Eliminar el despacho
        $despacho->delete();
        
        // Preparar mensaje para SweetAlert
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Despacho eliminado',
            'text' => "El despacho {$nombre} ha sido eliminado exitosamente"
        ]);
        
        return redirect()->route('despachos.index')
            ->with('success', 'Despacho eliminado exitosamente');
            
    } catch (\Exception $e) {
        // Log del error para debugging
        Log::error('Error al eliminar despacho: ' . $e->getMessage());
        
        session()->flash('swal', [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'No se pudo eliminar el despacho: ' . $e->getMessage()
        ]);
        
        return redirect()->route('despachos.index')
            ->with('error', 'No se pudo eliminar el despacho');
    }
}

    /**
     * Muestra un listado de los usuarios asignados a un despacho específico
     */
    public function usuariosAsignados($id)
    {
        $despacho = Despacho::with('usuarios')->findOrFail($id);
        
        return view('despachos.usuariosAsignados', compact('despacho'));
    }

    /**
     * Genera un reporte o exporta la lista de despachos
     */
    public function exportar(Request $request)
    {
        // Lógica para exportar los despachos a PDF, Excel, etc.
        // Ajusta esto según tus necesidades específicas
        
        return redirect()->route('despachos.index')
            ->with('success', 'Reporte generado exitosamente');
    }
}