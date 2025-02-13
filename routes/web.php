<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AsignaturaController;


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



});



Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/departamento', function(){
        return view('departamento');
    })->name('departamento');
    
});


require __DIR__.'/auth.php';
