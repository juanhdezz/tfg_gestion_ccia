<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\UsuarioAsignaturaController;


Route::get('/', function () {
    if (Auth::check()) {
        return view('dashboard');
    }
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas para usuarios autenticados que ademas sean administradores
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return "Bienvenido admin" ; // Vista del panel de administración
    })->name('admin');
    Route::get('/gestion-usuarios/create',[UsuarioController::class, 'create'])->name('usuarios.create');
    Route::get('/gestion-usuarios',[UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/gestion-usuarios/{id}',[UsuarioController::class, 'show'])->name('usuarios.show');
    
    Route::post('/gestion-usuarios',[UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/gestion-usuarios/{id}/edit',[UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/gestion-usuarios/{id}',[UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/gestion-usuarios/{id}',[UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    Route::get('/exportar-usuarios', [UsuarioController::class, 'export'])->name('usuarios.export');

    Route::get('/gestion-asignaturas/grupos',[AsignaturaController::class, 'grupos']
    )->name('asignaturas.grupos');
    
    //Ruta para actualizar los grupos de una asignatura mediante la vista de grupos
    Route::patch('/gestion-asignaturas/grupos/{asignatura}', [AsignaturaController::class, 'updateGrupos'])
    ->name('asignaturas.updateGrupos');

    Route::get('/gestion-asignaturas/{id}/edit',[AsignaturaController::class, 'edit'])->name('asignaturas.edit');
    Route::put('/gestion-asignaturas/{id}',[AsignaturaController::class, 'update'])->name('asignaturas.update');
    Route::get('/gestion-asignaturas/create',[AsignaturaController::class, 'create'])->name('asignaturas.create');
    Route::get('/gestion-asignaturas',[AsignaturaController::class, 'index'])->name('asignaturas.index');
    Route::get('/gestion-asignaturas/{id}',[AsignaturaController::class, 'show'])->name('asignaturas.show');
    
    Route::post('/gestion-asignaturas',[AsignaturaController::class, 'store'])->name('asignaturas.store');
    
    Route::delete('/gestion-asignaturas/{id}',[AsignaturaController::class, 'destroy'])->name('asignaturas.destroy');

    // Ruta para mostrar el formulario de equivalencias
Route::get('/asignaturas/{id}/equivalencias', [AsignaturaController::class, 'mostrarFormularioEquivalencias'])
->name('asignaturas.mostrar-formulario-equivalencias');

// Ruta para procesar la creación de equivalencias
Route::post('/asignaturas/equivalencias', [AsignaturaController::class, 'establecerEquivalencia'])
->name('asignaturas.establecer-equivalencia');

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




});



Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/departamento', function(){
        return view('departamento');
    })->name('departamento');
    
});


require __DIR__.'/auth.php';
