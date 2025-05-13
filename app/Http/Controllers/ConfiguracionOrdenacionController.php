<?php

namespace App\Http\Controllers;

use App\Models\Ordenacion\ConfiguracionOrdenacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ConfiguracionOrdenacionController extends Controller
{
    /**
     * Muestra el listado de configuraciones de ordenación docente
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $configuraciones = ConfiguracionOrdenacion::orderBy('clave')->get();
            $titulo = 'Configuración de Ordenación Docente';
            
            return view('configuracion_ordenacion.index', compact('configuraciones', 'titulo'));
        } catch (\Exception $e) {
            Log::error('Error en ConfiguracionOrdenacionController::index: ' . $e->getMessage());
            
            $titulo = 'Error al cargar las configuraciones';
            $mensaje = 'Se ha producido un error al cargar las configuraciones de ordenación docente.';
            $detalles = config('app.debug') ? $e->getMessage() : null;
            
            return view('error.error', compact('titulo', 'mensaje', 'detalles'));
        }
    }

    /**
     * Muestra el formulario para crear una nueva configuración
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $titulo = 'Nueva Configuración de Ordenación Docente';
        return view('configuracion_ordenacion.create', compact('titulo'));
    }

    /**
     * Almacena una nueva configuración en la base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'clave' => 'required|string|max:100|unique:configuracion_ordenacion,clave',
            'valor' => 'required|string|max:255',
            'descripcion' => 'nullable|string'
        ], [
            'clave.required' => 'La clave es obligatoria',
            'clave.unique' => 'Esta clave ya existe en el sistema',
            'valor.required' => 'El valor es obligatorio'
        ]);
        
        try {
            DB::beginTransaction();
            ConfiguracionOrdenacion::create($validatedData);
            DB::commit();
            
            return redirect()->route('configuracion_ordenacion.index')
                ->with('success', 'Configuración creada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ConfiguracionOrdenacionController::store: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la configuración: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario para editar una configuración existente
     *
     * @param  int  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            $configuracion = ConfiguracionOrdenacion::findOrFail($id);
            $titulo = 'Editar Configuración: ' . $configuracion->clave;
            
            return view('configuracion_ordenacion.edit', compact('configuracion', 'titulo'));
        } catch (\Exception $e) {
            Log::error('Error en ConfiguracionOrdenacionController::edit: ' . $e->getMessage());
            
            return redirect()->route('configuracion_ordenacion.index')
                ->with('error', 'La configuración solicitada no existe o no se pudo cargar');
        }
    }

    /**
     * Actualiza la configuración especificada
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $configuracion = ConfiguracionOrdenacion::findOrFail($id);
            
            $validatedData = $request->validate([
                'clave' => [
                    'required', 
                    'string', 
                    'max:100',
                    Rule::unique('configuracion_ordenacion')->ignore($id)
                ],
                'valor' => 'required|string|max:255',
                'descripcion' => 'nullable|string'
            ], [
                'clave.required' => 'La clave es obligatoria',
                'clave.unique' => 'Esta clave ya existe en el sistema',
                'valor.required' => 'El valor es obligatorio'
            ]);
            
            DB::beginTransaction();
            
            // Usar los datos validados directamente para la actualización
            $configuracion->update($validatedData);
            
            DB::commit();
            
            return redirect()->route('configuracion_ordenacion.index')
                ->with('success', 'Configuración actualizada correctamente');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('configuracion_ordenacion.index')
                ->with('error', 'La configuración solicitada no existe');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ConfiguracionOrdenacionController::update: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la configuración: ' . $e->getMessage());
        }
    }

    /**
     * Elimina la configuración especificada
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $configuracion = ConfiguracionOrdenacion::findOrFail($id);
            $clave = $configuracion->clave;
            
            DB::beginTransaction();
            $configuracion->delete();
            DB::commit();
            
            return redirect()->route('configuracion_ordenacion.index')
                ->with('success', "Configuración '$clave' eliminada correctamente");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ConfiguracionOrdenacionController::destroy: ' . $e->getMessage());
            
            return redirect()->route('configuracion_ordenacion.index')
                ->with('error', 'Error al eliminar la configuración: ' . $e->getMessage());
        }
    }

    /**
     * Restaura los valores predeterminados de las configuraciones
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restoreDefaults()
    {
        try {
            DB::beginTransaction();
            
            $defaults = [
                'creditos_menos_permitidos' => [
                    'valor' => '0.5',
                    'descripcion' => 'Créditos por debajo de la carga que permiten pasar turno'
                ],
                'porcentaje_limite_menor' => [
                    'valor' => '25',
                    'descripcion' => 'Porcentaje límite menor para compensaciones (tradicionalmente 25%)'
                ],
                'porcentaje_limite_mayor' => [
                    'valor' => '50',
                    'descripcion' => 'Porcentaje límite mayor para compensaciones (tradicionalmente 50%)'
                ],
                'identificador_tfm' => [
                    'valor' => '9999601',
                    'descripcion' => 'Texto usado para identificar asignaturas de TFM'
                ],
            ];
            
            foreach ($defaults as $clave => $datos) {
                ConfiguracionOrdenacion::updateOrCreate(
                    ['clave' => $clave],
                    [
                        'valor' => $datos['valor'],
                        'descripcion' => $datos['descripcion']
                    ]
                );
            }
            
            DB::commit();
            
            return redirect()->route('configuracion_ordenacion.index')
                ->with('success', 'Configuraciones restauradas a valores predeterminados');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ConfiguracionOrdenacionController::restoreDefaults: ' . $e->getMessage());
            
            return redirect()->route('configuracion_ordenacion.index')
                ->with('error', 'Error al restaurar valores predeterminados: ' . $e->getMessage());
        }
    }
}