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

                case 'usuarios.index':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Usuarios', 'url' => route('usuarios.index')]
                    ];
                    break;

                case 'usuarios.create':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Usuarios', 'url' => route('usuarios.index')],
                        ['name' => 'Nuevo Usuario', 'url' => route('usuarios.create')]
                    ];
                    break;

                case 'usuarios.edit':
                    $id = request()->route('id');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Usuarios', 'url' => route('usuarios.index')],
                        ['name' => 'Editar Usuario', 'url' => route('usuarios.edit', $id)]
                    ];
                    break;

                case 'usuarios.show':
                    $id = request()->route('id');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Usuarios', 'url' => route('usuarios.index')],
                        ['name' => 'Ver Usuario', 'url' => route('usuarios.show', $id)]
                    ];
                    break;

                case 'asignaturas.index':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Asignaturas', 'url' => route('asignaturas.index')]
                    ];
                    break;

                case 'asignaturas.create':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Asignaturas', 'url' => route('asignaturas.index')],
                        ['name' => 'Nueva asignatura', 'url' => route('asignaturas.create')]
                    ];
                    break;
                
                case 'asignaturas.edit':
                    $id = request()->route('id');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Asignaturas', 'url' => route('asignaturas.index')],
                        ['name' => 'Editar asignatura', 'url' => route('asignaturas.edit', $id)]
                    ];
                    break;
                
                case 'asignaturas.show':
                    $id = request()->route('id');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Asignaturas', 'url' => route('asignaturas.index')],
                        ['name' => 'Ver asignatura', 'url' => route('asignaturas.show', $id)]
                    ];
                    break;
                
                case 'asignaturas.grupos':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Asignaturas', 'url' => route('asignaturas.index')],
                        ['name' => 'Grupos de asignatura', 'url' => route('asignaturas.grupos')]
                    ];
                    break;
                
                case 'asignaturas.mostrar-formulario-equivalencias':
                    $id = request()->route('id');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Asignaturas', 'url' => route('asignaturas.index')],
                        ['name' => 'Equivalencias', 'url' => route('asignaturas.mostrar-formulario-equivalencias', $id)]
                    ];
                    break;
                
                case 'asignaturas.establecer-equivalencia':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Asignaturas', 'url' => route('asignaturas.index')],
                        ['name' => 'Establecer equivalencia', 'url' => route('asignaturas.establecer-equivalencia')]
                    ];
                    break;



                case 'usuario_asignatura.index':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Asignaciones', 'url' => route('usuario_asignatura.index')]
                    ];
                    break;

                case 'usuario_asignatura.create':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Asignaciones', 'url' => route('usuario_asignatura.index')],
                        ['name' => 'Nueva asignación', 'url' => route('usuario_asignatura.create')]
                    ];
                    break;

                case 'usuario_asignatura.edit':
                    $id_asignatura = request()->route('id_asignatura');
                    $id_usuario = request()->route('id_usuario');
                    $tipo = request()->route('tipo');
                    $grupo = request()->route('grupo');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Asignaciones', 'url' => route('usuario_asignatura.index')],
                        ['name' => 'Editar', 'url' => route('usuario_asignatura.edit', [$id_asignatura, $id_usuario, $tipo, $grupo])]
                    ];
                    break;

                case 'tutorias.index':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Modificar Tutorías', 'url' => route('tutorias.index')]
                    ];
                    break;
                
                case 'tutorias.ver':
                    $id = request()->route('id');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Ver Tutoría', 'url' => route('tutorias.ver', $id)]
                    ];
                    break;
                
                case 'plazos.index':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Plazos', 'url' => route('plazos.index')]
                    ];
                    break;
                
                case 'plazos.create':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Plazos', 'url' => route('plazos.index')],
                        ['name' => 'Nuevo plazo', 'url' => route('plazos.create')]
                    ];
                    break;

                case 'plazos.edit':
                    $id = request()->route('id');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Plazos', 'url' => route('plazos.index')],
                        ['name' => 'Editar plazo', 'url' => route('plazos.edit', $id)]
                    ];
                    break;
                
                case 'plazos.show':
                    $id = request()->route('id');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Plazos', 'url' => route('plazos.index')],
                        ['name' => 'Ver plazo', 'url' => route('plazos.show', $id)]
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
