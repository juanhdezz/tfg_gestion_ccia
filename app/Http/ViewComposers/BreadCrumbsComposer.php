<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Route;

class BreadcrumbsComposer
{
    public function compose(View $view)
    {
        // Solo agregar breadcrumbs si no han sido definidos ya
        if (!$view->offsetExists('breadcrumbs')) {
            $route = Route::currentRouteName();
            $breadcrumbs = [];
            
            // Define breadcrumbs basados en la ruta actual
            switch ($route) {
                case 'dashboard':
                    $breadcrumbs = [
                        ['name' => 'Dashboard', 'url' => route('dashboard')]
                    ];
                    break;
                
                case 'departamento':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')]
                    ];
                    break;
                    
                case 'asignaturas.index':
                    $breadcrumbs = [
                        ['name' => 'Asignaturas', 'url' => route('asignaturas.index')]
                    ];
                    break;
                    
                case 'asignaturas.create':
                    $breadcrumbs = [
                        ['name' => 'Asignaturas', 'url' => route('asignaturas.index')],
                        ['name' => 'Nueva asignatura', 'url' => route('asignaturas.create')]
                    ];
                    break;
                    
                case 'asignaturas.edit':
                    // Para rutas con parámetros, puedes acceder a ellos a través del request
                    $id = request()->route('asignatura');
                    $asignatura = \App\Models\Asignatura::find($id);
                    $nombre = $asignatura ? $asignatura->nombre_asignatura : 'Editar';
                    
                    $breadcrumbs = [
                        ['name' => 'Asignaturas', 'url' => route('asignaturas.index')],
                        ['name' => $nombre, 'url' => route('asignaturas.show', $id)],
                        ['name' => 'Editar', 'url' => route('asignaturas.edit', $id)]
                    ];
                    break;
                
                case 'usuario_asignatura.index':
                    $breadcrumbs = [
                        ['name' => 'Asignaciones', 'url' => route('usuario_asignatura.index')]
                    ];
                    break;
                
                case 'usuario_asignatura.create':
                    $breadcrumbs = [
                        ['name' => 'Asignaciones', 'url' => route('usuario_asignatura.index')],
                        ['name' => 'Nueva asignación', 'url' => route('usuario_asignatura.create')]
                    ];
                    break;
                
                case 'usuario_asignatura.edit':
                    $id_asignatura = request()->route('id_asignatura');
                    $id_usuario = request()->route('id_usuario');
                    $tipo = request()->route('tipo');
                    $grupo = request()->route('grupo');
                    
                    $breadcrumbs = [
                        ['name' => 'Asignaciones', 'url' => route('usuario_asignatura.index')],
                        ['name' => 'Editar', 'url' => route('usuario_asignatura.edit', [$id_asignatura, $id_usuario, $tipo, $grupo])]
                    ];
                    break;
                
                
                    
                // Añade más casos según sea necesario
                    
                default:
                    // Breadcrumb por defecto, puede estar vacío o tener un valor predeterminado
                    $breadcrumbs = [];
                    break;
            }
            
            $view->with('breadcrumbs', $breadcrumbs);
        }
    }
}