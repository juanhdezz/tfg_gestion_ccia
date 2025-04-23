<?php

/**
 * Archivo de rutas web.php para Laravel 11
 * Organizado por grupos funcionales y optimizado para evitar solapamientos y conflictos
 */

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\UsuarioAsignaturaController;
use App\Http\Controllers\TutoriaController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\DespachoController;
use App\Http\Controllers\ReservaSalaController;
use App\Http\Controllers\PlazoController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\LibroAsignaturaController;
use App\Http\Controllers\ProyectoController;

/**
 * ====================================
 * RUTAS PÚBLICAS Y DE AUTENTICACIÓN
 * ====================================
 * Rutas accesibles sin autenticación
 */

// Página principal - Redirecciona al dashboard si está autenticado o al login
Route::get('/', function () {
    return Auth::check() ? view('dashboard') : view('auth.login');
});

// Ruta de error global
Route::get('/error', function () {
    return view('error.error');
})->name('error.error');

// Rutas de autenticación de Laravel
require __DIR__ . '/auth.php';

/**
 * ====================================
 * RUTAS PROTEGIDAS - USUARIO AUTENTICADO
 * ====================================
 * Rutas accesibles para cualquier usuario autenticado
 */
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    /**
     * PERFIL DE USUARIO
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    /**
     * DEPARTAMENTO
     */
    Route::get('/departamento', function () {
        return view('departamento');
    })->name('departamento');
    
    /**
     * DESPACHOS
     */
    Route::get('despachos', [DespachoController::class, 'index'])->name('despachos.index');
    Route::get('despachos/create', [DespachoController::class, 'create'])->name('despachos.create');
    Route::post('despachos', [DespachoController::class, 'store'])->name('despachos.store');
    Route::get('despachos/{id}', [DespachoController::class, 'show'])->name('despachos.show');
    Route::get('despachos/{id}/edit', [DespachoController::class, 'edit'])->name('despachos.edit');
    Route::put('despachos/{id}', [DespachoController::class, 'update'])->name('despachos.update');
    Route::patch('despachos/{id}', [DespachoController::class, 'update'])->name('despachos.update');
    Route::delete('despachos/{id}', [DespachoController::class, 'destroy'])->name('despachos.destroy');
    Route::get('despachos/{id}/usuarios', [DespachoController::class, 'usuariosAsignados'])
        ->name('despachos.usuarios');
    Route::get('despachos-exportar', [DespachoController::class, 'exportar'])
        ->name('despachos.exportar');
    Route::get('despachos/{id}/usuarios', [DespachoController::class, 'usuariosAsignados'])
        ->name('despachos.usuariosAsignados');
    
    /**
     * TUTORÍAS
     */
    Route::prefix('tutorias')->name('tutorias.')->group(function () {
        Route::get('/', [TutoriaController::class, 'index'])->name('index');
        Route::post('/', [TutoriaController::class, 'store'])->name('store');
        Route::delete('/{tutoria}', [TutoriaController::class, 'destroy'])->name('destroy');
        Route::post('/actualizar', [TutoriaController::class, 'actualizar'])->name('actualizar');
        Route::get('/ver', [TutoriaController::class, 'verTutorias'])->name('ver');
    });
    
    /**
     * CAMBIO DE BASE DE DATOS
     */
    Route::post('/cambiar-base-datos', [DatabaseController::class, 'cambiarBaseDatos'])->name('cambiar.base.datos');
    
    /**
     * REASIGNACIÓN DE GRUPOS
     */
    Route::patch('/asignaturas/{id}/reasignar-grupos', [AsignaturaController::class, 'reasignarGrupos'])
        ->name('asignaturas.reasignar-grupos');
});

/**
 * ====================================
 * RUTAS PARA ADMINISTRADORES
 * ====================================
 * Rutas protegidas que requieren rol de administrador
 */
Route::middleware(['auth', 'admin'])->group(function () {
    // Panel de administración
    Route::get('/admin', function () {
        return "Bienvenido admin"; 
    })->name('admin');
    
    /**
     * GESTIÓN DE USUARIOS
     */
    Route::prefix('gestion-usuarios')->name('usuarios.')->group(function () {
        Route::get('/', [UsuarioController::class, 'index'])->name('index');
        Route::get('/create', [UsuarioController::class, 'create'])->name('create');
        Route::post('/', [UsuarioController::class, 'store'])->name('store');
        Route::get('/exportar-usuarios', [UsuarioController::class, 'export'])->name('export');
        Route::get('/{id}', [UsuarioController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UsuarioController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UsuarioController::class, 'update'])->name('update');
        Route::delete('/{id}', [UsuarioController::class, 'destroy'])->name('destroy');
        
    });
    
    /**
     * GESTIÓN DE ASIGNATURAS
     */
    Route::prefix('gestion-asignaturas')->name('asignaturas.')->group(function () {
        
        // CRUD principal
        Route::get('/', [AsignaturaController::class, 'index'])->name('index');
        Route::get('/create', [AsignaturaController::class, 'create'])->name('create');
        Route::post('/', [AsignaturaController::class, 'store'])->name('store');
        Route::get('/grupos', [AsignaturaController::class, 'grupos'])->name('grupos');
        Route::get('/{id}', [AsignaturaController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AsignaturaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AsignaturaController::class, 'update'])->name('update');
        Route::delete('/{id}', [AsignaturaController::class, 'destroy'])->name('destroy');
        
        // Gestión de grupos
        
        Route::patch('/grupos/{asignatura}', [AsignaturaController::class, 'updateGrupos'])->name('updateGrupos');
        Route::patch('/{id}/reasignar-grupos', [AsignaturaController::class, 'reasignarGrupos'])->name('reasignarGrupos');
        
        // Inicialización y verificación
        Route::get('/inicializar-grupos', [AsignaturaController::class, 'inicializarGruposTeoriaPractica'])
            ->name('inicializar-grupos')->prefix('admin/asignaturas');
        Route::get('/verificar-migracion', [AsignaturaController::class, 'verificarAsignaturasMigracion'])
            ->name('verificar-migracion')->prefix('admin/asignaturas');
    });
    
    /**
     * EQUIVALENCIAS DE ASIGNATURAS
     */
    Route::prefix('asignaturas')->name('asignaturas.')->group(function () {
        Route::post('/equivalencias', [AsignaturaController::class, 'establecerEquivalencia'])
            ->name('establecer-equivalencia');
        Route::get('/{id}/equivalencias', [AsignaturaController::class, 'mostrarFormularioEquivalencias'])
            ->name('mostrar-formulario-equivalencias');
        Route::delete('/equivalencias', [AsignaturaController::class, 'eliminarEquivalencia'])
            ->name('eliminar-equivalencia');
        // Ruta comentada para listar equivalencias
        // Route::get('/equivalencias', [AsignaturaController::class, 'listarEquivalencias'])
        //     ->name('listar-equivalencias');
    });
    
    /**
     * GESTIÓN DE USUARIO-ASIGNATURA
     */
    Route::prefix('usuario_asignatura')->name('usuario_asignatura.')->group(function () {
        Route::get('/', [UsuarioAsignaturaController::class, 'index'])->name('index');
        Route::get('/create/{id_asignatura?}/{tipo?}/{grupo?}', [UsuarioAsignaturaController::class, 'create'])
            ->name('create');
        Route::post('/', [UsuarioAsignaturaController::class, 'store'])->name('store');
        Route::get('/{id_asignatura}/{id_usuario}/{tipo}/{grupo}/edit', [UsuarioAsignaturaController::class, 'edit'])
            ->name('edit');
        Route::put('/{id_asignatura}/{id_usuario}/{tipo}/{grupo}', [UsuarioAsignaturaController::class, 'update'])
            ->name('update');
        Route::post('/update_ajax', [UsuarioAsignaturaController::class, 'updateAjax'])->name('update_ajax');
        Route::delete('/{id_asignatura}/{id_usuario}/{tipo}/{grupo}', [UsuarioAsignaturaController::class, 'destroy'])
            ->name('destroy');
    });
    
    /**
     * GESTIÓN DE PLAZOS
     */
    Route::prefix('plazos')->name('plazos.')->group(function () {
        Route::get('/', [PlazoController::class, 'index'])->name('index');
        Route::get('/create', [PlazoController::class, 'create'])->name('create');
        Route::post('/', [PlazoController::class, 'store'])->name('store');
        Route::get('/{id}', [PlazoController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PlazoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PlazoController::class, 'update'])->name('update');
        Route::delete('/{id}', [PlazoController::class, 'destroy'])->name('destroy');
    });
    
    /**
     * RESERVA DE SALAS
     */
    Route::prefix('reserva-salas')->name('reserva_salas.')->group(function () {
        // CRUD principal
        Route::get('/', [ReservaSalaController::class, 'index'])->name('index');
        Route::get('/create', [ReservaSalaController::class, 'create'])->name('create');
        Route::post('/', [ReservaSalaController::class, 'store'])->name('store');
        Route::get('/{id_sala}/{fecha}/{hora_inicio}/{estado}', [ReservaSalaController::class, 'show'])
            ->name('show');
        Route::get('/{id_sala}/{fecha}/{hora_inicio}/{estado}/edit', [ReservaSalaController::class, 'edit'])
            ->name('edit');
        Route::patch('/{id_sala}/{fecha}/{hora_inicio}/{estado}', [ReservaSalaController::class, 'update'])
            ->name('update');
        Route::delete('/{id_sala}/{fecha}/{hora_inicio}/{estado}', [ReservaSalaController::class, 'destroy'])
            ->name('destroy');
        
        // Gestión de estados y verificaciones
        Route::patch('/{id_sala}/{fecha}/{hora_inicio}/{estado}/cambiar-estado', [ReservaSalaController::class, 'cambiarEstado'])
            ->name('cambiar-estado');
        Route::post('/verificar-disponibilidad', [ReservaSalaController::class, 'verificarDisponibilidad'])
            ->name('verificar-disponibilidad');
        Route::get('/pendientes', [ReservaSalaController::class, 'reservasPendientes'])
            ->name('pendientes');
        Route::post('/procesar/{id_sala}/{fecha}/{hora_inicio}/{estado}', [ReservaSalaController::class, 'procesarValidacion'])
            ->name('procesar');
        
        // Calendario de reservas
        Route::get('/calendario', [ReservaSalaController::class, 'calendario'])->name('calendario');
        Route::get('/calendario/eventos', [ReservaSalaController::class, 'obtenerEventosCalendario'])
            ->name('obtener-eventos-calendario');
    });
    
    /**
     * GESTIÓN DE LIBROS
     */
    Route::prefix('libros')->name('libros.')->group(function () {
        Route::get('/', [LibroController::class, 'index'])->name('index');
        Route::get('/crear', [LibroController::class, 'create'])->name('create');
        Route::post('/', [LibroController::class, 'store'])->name('store');
        Route::post('/{id_libro}/{id_usuario}/{fecha_solicitud}/aprobar', [LibroController::class, 'aprobar'])
            ->name('aprobar');
        Route::post('/{id_libro}/{id_usuario}/{fecha_solicitud}/denegar', [LibroController::class, 'denegar'])
            ->name('denegar');
        Route::post('/{id_libro}/{id_usuario}/{fecha_solicitud}/recibir', [LibroController::class, 'recibir'])
            ->name('recibir');
    });
    
    /**
     * GESTIÓN DE PROYECTOS
     */
    // Rutas de recurso para CRUD completo
    Route::get('proyectos', [ProyectoController::class, 'index'])->name('proyectos.index');
    Route::get('proyectos/create', [ProyectoController::class, 'create'])->name('proyectos.create');
    Route::post('proyectos', [ProyectoController::class, 'store'])->name('proyectos.store');
    Route::get('proyectos/{proyecto}', [ProyectoController::class, 'show'])->name('proyectos.show');
    Route::get('proyectos/{proyecto}/edit', [ProyectoController::class, 'edit'])->name('proyectos.edit');
    Route::put('proyectos/{proyecto}', [ProyectoController::class, 'update'])->name('proyectos.update');
    Route::delete('proyectos/{proyecto}', [ProyectoController::class, 'destroy'])->name('proyectos.destroy');
    
    // Rutas adicionales de proyectos
    Route::patch('proyectos/{proyecto}/cambiar-estado', [ProyectoController::class, 'cambiarEstado'])
        ->name('proyectos.cambiarEstado');
    Route::get('proyectos/{proyecto}/miembros', [ProyectoController::class, 'miembros'])
        ->name('proyectos.miembros');
});