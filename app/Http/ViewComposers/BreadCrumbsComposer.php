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

                case 'usuarios.gestion-orden':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Usuarios', 'url' => route('usuarios.index')],
                        ['name' => 'Gestión de Numero de orden', 'url' => route('usuarios.gestion-orden')]
                    ];
                    break;

                case 'usuarios.gestion-categorias':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Usuarios', 'url' => route('usuarios.index')],
                        ['name' => 'Gestión de Categorías Docentes', 'url' => route('usuarios.gestion-categorias')]
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
                        ['name' => 'Gestión de Tutorías', 'url' => route('tutorias.gestion')],
                        ['name' => 'Modificar Tutorías', 'url' => route('tutorias.index')]
                    ];
                    break;
                
                case 'tutorias.ver':
                    $id = request()->route('id');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Tutorías', 'url' => route('tutorias.gestion')],
                        ['name' => 'Ver Tutoría', 'url' => route('tutorias.ver', $id)]
                    ];
                    break;
                
                case 'tutorias.gestion':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Tutorías', 'url' => route('tutorias.gestion')]
                    ];
                    break;

                case 'tutorias.plazos':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Tutorías', 'url' => route('tutorias.gestion')],
                        ['name' => 'Plazos de Tutorías', 'url' => route('tutorias.plazos')]
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

                case 'despachos.index':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Despachos', 'url' => route('despachos.index')]
                    ];
                    break;
                
                case 'despachos.create':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Despachos', 'url' => route('despachos.index')],
                        ['name' => 'Nuevo despacho', 'url' => route('despachos.create')]
                    ];
                    break;
                
                case 'despachos.edit':
                    $id = request()->route('id');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Despachos', 'url' => route('despachos.index')],
                        ['name' => 'Editar despacho', 'url' => route('despachos.edit', $id)]
                    ];
                    break;

                case 'despachos.show':
                    $id = request()->route('id');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Despachos', 'url' => route('despachos.index')],
                        ['name' => 'Ver despacho', 'url' => route('despachos.show', $id)]
                    ];
                    break;
                
                case 'reserva_salas.index':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Reservas de Salas', 'url' => route('reserva_salas.index')]
                    ];
                    break;
                
                case 'reserva_salas.create':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Reservas de Salas', 'url' => route('reserva_salas.index')],
                        ['name' => 'Nueva reserva', 'url' => route('reserva_salas.create')]
                    ];
                    break;

                case 'reserva_salas.edit':
                    $id = request()->route('id_sala');
                    $fecha = request()->route('fecha');
                    $hora_inicio = request()->route('hora_inicio');
                    $estado = request()->route('estado');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Reservas de Salas', 'url' => route('reserva_salas.index')],
                        ['name' => 'Editar reserva', 'url' => route('reserva_salas.edit', [
                            'id_sala' => $id,
                            'fecha' => $fecha,
                            'hora_inicio' => $hora_inicio,
                            'estado' => $estado
                        ])]
                    ];
                    break;                
                    
                case 'reserva_salas.show':
                    $id_sala = request()->route('id_sala');
                    $fecha = request()->route('fecha');
                    $hora_inicio = request()->route('hora_inicio');
                    $estado = request()->route('estado');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Reservas de Salas', 'url' => route('reserva_salas.index')],
                        ['name' => 'Ver reserva', 'url' => route('reserva_salas.show', [
                            'id_sala' => $id_sala,
                            'fecha' => $fecha,
                            'hora_inicio' => $hora_inicio,
                            'estado' => $estado
                        ])]
                    ];
                    break;

                case 'salas.index':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Reservas de Salas', 'url' => route('reserva_salas.index')],
                        ['name' => 'Gestión de Salas', 'url' => route('salas.index')]
                    ];
                    break;

                case 'salas.create':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Reservas de Salas', 'url' => route('reserva_salas.index')],
                        ['name' => 'Gestión de Salas', 'url' => route('salas.index')],
                        ['name' => 'Nueva sala', 'url' => route('salas.create')]
                    ];
                    break;

                case 'salas.edit':
                    $id = request()->route('id_sala');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Reservas de Salas', 'url' => route('reserva_salas.index')],
                        ['name' => 'Gestión de Salas', 'url' => route('salas.index')],
                        ['name' => 'Editar sala', 'url' => route('salas.edit', $id)]
                    ];
                    break;
                
                case 'salas.show':
                    $id = request()->route('id_sala');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Reservas de Salas', 'url' => route('reserva_salas.index')],
                        ['name' => 'Gestión de Salas', 'url' => route('salas.index')],
                        ['name' => 'Ver sala', 'url' => route('salas.show', $id)]
                    ];
                    break;

                case 'reserva_salas.pendientes':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Reservas de Salas', 'url' => route('reserva_salas.index')],
                        ['name' => 'Reservas pendientes', 'url' => route('reserva_salas.pendientes')]
                    ];
                    break;

                case 'reserva_salas.calendario':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Reservas de Salas', 'url' => route('reserva_salas.index')],
                        ['name' => 'Calendario', 'url' => route('reserva_salas.calendario')]
                    ];
                    break;

                case 'libros.index':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Libros', 'url' => route('libros.index')]
                    ];
                    break;
                
                case 'libros.create':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Libros', 'url' => route('libros.index')],
                        ['name' => 'Nuevo libro', 'url' => route('libros.create')]
                    ];
                    break;

                case 'proyectos.index':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Proyectos', 'url' => route('proyectos.index')]
                    ];
                    break;

                case 'proyectos.create':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Proyectos', 'url' => route('proyectos.index')],
                        ['name' => 'Nuevo proyecto', 'url' => route('proyectos.create')]
                    ];
                    break;

                case 'proyectos.edit':
                    $proyecto = request()->route('proyecto');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Proyectos', 'url' => route('proyectos.index')],
                        ['name' => 'Editar proyecto', 'url' => route('proyectos.edit', $proyecto)]
                    ];
                    break;

                case 'proyectos.show':
                    $proyecto = request()->route('proyecto');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Proyectos', 'url' => route('proyectos.index')],
                        ['name' => 'Ver proyecto', 'url' => route('proyectos.show', $proyecto)]
                    ];
                    break;
                
                case 'configuracion_ordenacion.index':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Configuración de Ordenación', 'url' => route('configuracion_ordenacion.index')]
                    ];
                    break;
                
                case 'configuracion_ordenacion.create':
                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Configuración de Ordenación', 'url' => route('configuracion_ordenacion.index')],
                        ['name' => 'Nueva configuración', 'url' => route('configuracion_ordenacion.create')]
                    ];
                    break;
                
                case 'configuracion_ordenacion.edit':
                    $id = request()->route('id');

                    $breadcrumbs = [
                        ['name' => 'Departamento', 'url' => route('departamento')],
                        ['name' => 'Gestión de Configuración de Ordenación', 'url' => route('configuracion_ordenacion.index')],
                        ['name' => 'Editar configuración', 'url' => route('configuracion_ordenacion.edit', $id)]
                    ];
                    break;

                case 'ordenacion.index':
                    $breadcrumbs = [
                        ['name' => 'Ordenación Docente', 'url' => route('ordenacion.index')]
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
