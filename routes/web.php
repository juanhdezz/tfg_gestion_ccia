<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\UsuarioAsignaturaController;
use App\Http\Controllers\TutoriaController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\DespachoController;
use App\Http\Controllers\ReservaSalaController;
use App\Http\Controllers\PlazoController;
use App\Mail\Notification;
use Illuminate\Support\Facades\Mail;
use App\Models\ReservaSala;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\ProyectoController;



Route::get('/', function () {
    if (Auth::check()) {
        return view('dashboard');
    }
    return view('auth.login');
});

//Ruta de error
Route::get('/error', function () {
    return view('error.error');
})->name('error.error');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas para usuarios autenticados que ademas sean administradores
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return "Bienvenido admin"; // Vista del panel de administración
    })->name('admin');
    Route::get('/gestion-usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::get('/gestion-usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/gestion-usuarios/{id}', [UsuarioController::class, 'show'])->name('usuarios.show');

    Route::post('/gestion-usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/gestion-usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/gestion-usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/gestion-usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    Route::get('/exportar-usuarios', [UsuarioController::class, 'export'])->name('usuarios.export');
    Route::post('/gestion-asignaturas', [AsignaturaController::class, 'store'])->name('asignaturas.store');
    Route::get(
        '/gestion-asignaturas/grupos',
        [AsignaturaController::class, 'grupos']
    )->name('asignaturas.grupos');

    //Ruta para actualizar los grupos de una asignatura mediante la vista de grupos
    Route::patch('/gestion-asignaturas/grupos/{asignatura}', [AsignaturaController::class, 'updateGrupos'])
        ->name('asignaturas.updateGrupos');

    Route::get('/gestion-asignaturas/{id}/edit', [AsignaturaController::class, 'edit'])->name('asignaturas.edit');
    Route::put('/gestion-asignaturas/{id}', [AsignaturaController::class, 'update'])->name('asignaturas.update');
    Route::get('/gestion-asignaturas/create', [AsignaturaController::class, 'create'])->name('asignaturas.create');
    Route::get('/gestion-asignaturas', [AsignaturaController::class, 'index'])->name('asignaturas.index');
    Route::get('/gestion-asignaturas/{id}', [AsignaturaController::class, 'show'])->name('asignaturas.show');

    //Route::post('/gestion-asignaturas',[AsignaturaController::class, 'store'])->name('asignaturas.store');

    Route::delete('/gestion-asignaturas/{id}', [AsignaturaController::class, 'destroy'])->name('asignaturas.destroy');

    // Ruta para procesar la creación de equivalencias
    Route::post('/asignaturas/equivalencias', [AsignaturaController::class, 'establecerEquivalencia'])
        ->name('asignaturas.establecer-equivalencia');

    // Ruta para mostrar el formulario de equivalencias
    Route::get('/asignaturas/{id}/equivalencias', [AsignaturaController::class, 'mostrarFormularioEquivalencias'])
        ->name('asignaturas.mostrar-formulario-equivalencias');



    // Ruta para eliminar una equivalencia
    Route::delete('/asignaturas/equivalencias', [AsignaturaController::class, 'eliminarEquivalencia'])
        ->name('asignaturas.eliminar-equivalencia');

    // Opcionalmente, puedes añadir una ruta para ver todas las equivalencias
    //Route::get('/asignaturas/equivalencias', [AsignaturaController::class, 'listarEquivalencias'])
    //->name('asignaturas.listar-equivalencias');

    Route::get('/usuario_asignatura', [UsuarioAsignaturaController::class, 'index'])->name('usuario_asignatura.index');
    Route::get('/usuario_asignatura/create/{id_asignatura?}/{tipo?}/{grupo?}', [UsuarioAsignaturaController::class, 'create'])
        ->name('usuario_asignatura.create');
    Route::post('/usuario_asignatura', [UsuarioAsignaturaController::class, 'store'])->name('usuario_asignatura.store');
    // Rutas para editar y actualizar
    Route::get('/usuario-asignatura/{id_asignatura}/{id_usuario}/{tipo}/{grupo}/edit', [UsuarioAsignaturaController::class, 'edit'])
        ->name('usuario_asignatura.edit');
    Route::put('/usuario-asignatura/{id_asignatura}/{id_usuario}/{tipo}/{grupo}', [UsuarioAsignaturaController::class, 'update'])
        ->name('usuario_asignatura.update');
    Route::post('/usuario_asignatura/update_ajax', [UsuarioAsignaturaController::class, 'updateAjax'])->name('usuario_asignatura.update_ajax');

    // Ruta para eliminar una asignación
    Route::delete('/usuario_asignatura/{id_asignatura}/{id_usuario}/{tipo}/{grupo}', [UsuarioAsignaturaController::class, 'destroy'])
        ->name('usuario_asignatura.destroy');

    Route::patch('/asignaturas/{id}/reasignar-grupos', [AsignaturaController::class, 'reasignarGrupos'])
        ->name('asignaturas.reasignarGrupos');

    Route::get('/admin/asignaturas/inicializar-grupos', [AsignaturaController::class, 'inicializarGruposTeoriaPractica'])
        ->name('asignaturas.inicializar-grupos');
    Route::get('/admin/asignaturas/verificar-migracion', [AsignaturaController::class, 'verificarAsignaturasMigracion'])
        ->name('asignaturas.verificar-migracion');

    Route::get('/plazos', [App\Http\Controllers\PlazoController::class, 'index'])->name('plazos.index');
    Route::get('/plazos/create', [App\Http\Controllers\PlazoController::class, 'create'])->name('plazos.create');
    Route::post('/plazos', [App\Http\Controllers\PlazoController::class, 'store'])->name('plazos.store');
    Route::get('/plazos/{id}/edit', [App\Http\Controllers\PlazoController::class, 'edit'])->name('plazos.edit');
    Route::get('/plazos/{id}', [App\Http\Controllers\PlazoController::class, 'show'])->name('plazos.show');
    Route::put('/plazos/{id}', [App\Http\Controllers\PlazoController::class, 'update'])->name('plazos.update');
    Route::delete('/plazos/{id}', [App\Http\Controllers\PlazoController::class, 'destroy'])->name('plazos.destroy');

    // Ruta para reservas de sala
    Route::get('reserva-salas/create', [ReservaSalaController::class, 'create'])
        ->name('reserva_salas.create');

    Route::post('reserva-salas', [ReservaSalaController::class, 'store'])
        ->name('reserva_salas.store');

    Route::get('reserva-salas', [ReservaSalaController::class, 'index'])
        ->name('reserva_salas.index');

    Route::get('reserva-salas/{id_sala}/{fecha}/{hora_inicio}/{estado}', [ReservaSalaController::class, 'show'])
        ->name('reserva_salas.show');

    Route::get('reserva-salas/{id_sala}/{fecha}/{hora_inicio}/{estado}/edit', [ReservaSalaController::class, 'edit'])
        ->name('reserva_salas.edit');

    Route::patch('reserva-salas/{id_sala}/{fecha}/{hora_inicio}/{estado}', [ReservaSalaController::class, 'update'])
        ->name('reserva_salas.update');

    Route::delete('reserva-salas/{id_sala}/{fecha}/{hora_inicio}/{estado}', [ReservaSalaController::class, 'destroy'])
        ->name('reserva_salas.destroy');

    Route::patch('reserva-salas/{id_sala}/{fecha}/{hora_inicio}/{estado}/cambiar-estado', [ReservaSalaController::class, 'cambiarEstado'])
        ->name('reserva_salas.cambiar-estado');
    //Ruta para el calendsriorio de reservas
    Route::get('/calendario-reservas', [App\Http\Controllers\ReservaSalaController::class, 'calendario'])->name('reserva_salas.calendario');

    // Ruta para obtener los eventos del calendario en formato JSON
    Route::get('reserva-salas/calendario/eventos', [ReservaSalaController::class, 'obtenerEventosCalendario'])
        ->name('reserva_salas.obtener-eventos-calendario');

    Route::post('reserva-salas/verificar-disponibilidad', [ReservaSalaController::class, 'verificarDisponibilidad'])
        ->name('reserva_salas.verificar-disponibilidad');

    Route::get('reserva_salas/pendientes', [ReservaSalaController::class, 'reservasPendientes'])
        ->name('reserva_salas.pendientes');
    Route::post('reserva_salas/procesar/{id_sala}/{fecha}/{hora_inicio}/{estado}', [ReservaSalaController::class, 'procesarValidacion'])
        ->name('reserva_salas.procesar');

        Route::get('/libros', 'App\Http\Controllers\LibroAsignaturaController@index')->name('libros.index');
        Route::get('/libros/crear', 'App\Http\Controllers\LibroAsignaturaController@create')->name('libros.create');
        Route::post('/libros', 'App\Http\Controllers\LibroAsignaturaController@store')->name('libros.store');
        Route::post('/libros/{id_libro}/{id_usuario}/{fecha_solicitud}/aprobar', 'App\Http\Controllers\LibroAsignaturaController@aprobar')->name('libros.aprobar');
        Route::post('/libros/{id_libro}/{id_usuario}/{fecha_solicitud}/denegar', 'App\Http\Controllers\LibroAsignaturaController@denegar')->name('libros.denegar');
        Route::post('/libros/{id_libro}/{id_usuario}/{fecha_solicitud}/recibir', 'App\Http\Controllers\LibroAsignaturaController@recibir')->name('libros.recibir');

    // Route::get('proyectos', [App\Http\Controllers\ProyectoController::class, 'index'])->name('proyectos.index');
    // Route::get('proyectos/create', [App\Http\Controllers\ProyectoController::class, 'create'])->name('proyectos.create');
    // Route::post('proyectos', [App\Http\Controllers\ProyectoController::class, 'store'])->name('proyectos.store');
    // Route::get('proyectos/{id}', [App\Http\Controllers\ProyectoController::class, 'show'])->name('proyectos.show');
    // Route::get('proyectos/{id}/edit', [App\Http\Controllers\ProyectoController::class, 'edit'])->name('proyectos.edit');
    // Route::put('proyectos/{id}', [App\Http\Controllers\ProyectoController::class, 'update'])->name('proyectos.update');
    // Route::delete('proyectos/{id}', [App\Http\Controllers\ProyectoController::class, 'destroy'])->name('proyectos.destroy');
    // Route::patch('proyectos/{proyecto}/cambiar-estado', [ProyectoController::class, 'cambiarEstado'])->name('proyectos.cambiarEstado');

    // Rutas de proyectos CRUD
Route::resource('proyectos', ProyectoController::class);

// Rutas adicionales para proyectos
Route::patch('proyectos/{proyecto}/cambiar-estado', [ProyectoController::class, 'cambiarEstado'])->name('proyectos.cambiarEstado');
Route::get('proyectos/{proyecto}/miembros', [ProyectoController::class, 'miembros'])->name('proyectos.miembros');
});



Route::middleware(['auth'])->group(function () {
    // Rutas para despachos
    Route::resource('despachos', DespachoController::class);

    // Ruta opcional para ver usuarios asignados a un despacho
    Route::get('despachos/{id}/usuarios', [DespachoController::class, 'usuariosAsignados'])
        ->name('despachos.usuarios');

    // Ruta opcional para exportar despachos
    Route::get('despachos-exportar', [DespachoController::class, 'exportar'])
        ->name('despachos.exportar');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/departamento', function () {
        return view('departamento');
    })->name('departamento');
    Route::get('/tutorias', [App\Http\Controllers\TutoriaController::class, 'index'])->name('tutorias.index');
    Route::post('/tutorias', [App\Http\Controllers\TutoriaController::class, 'store'])->name('tutorias.store');
    Route::delete('/tutorias/{tutoria}', [App\Http\Controllers\TutoriaController::class, 'destroy'])->name('tutorias.destroy');
    Route::post('/tutorias/actualizar', [TutoriaController::class, 'actualizar'])->name('tutorias.actualizar');
    // Añadir esta ruta
    Route::get('/tutorias/ver', [TutoriaController::class, 'verTutorias'])->name('tutorias.ver');
    // routes/web.php

    Route::post('/cambiar-base-datos', [DatabaseController::class, 'cambiarBaseDatos'])->name('cambiar.base.datos');
});

// Ruta para reasignar grupos de práctica entre grupos de teoría
Route::patch('/asignaturas/{id}/reasignar-grupos', [App\Http\Controllers\AsignaturaController::class, 'reasignarGrupos'])
    ->name('asignaturas.reasignar-grupos')
    ->middleware(['auth']);






require __DIR__ . '/auth.php';
