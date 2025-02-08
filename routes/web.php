<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsuarioController;

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
});



Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/departamento', function(){
        return view('departamento');
    })->name('departamento');
    
});


require __DIR__.'/auth.php';
