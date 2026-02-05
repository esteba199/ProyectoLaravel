<?php

use App\Http\Controllers\PerfilController;
use App\Http\Controllers\CitaController;
use Illuminate\Support\Facades\Route;

// Ruta pública
Route::get('/', function () {
    return view('welcome');
});

// Ruta del panel de control - Requiere autenticación Y verificación de email
Route::get('/panel', function () {
    return view('panel');
})->middleware(['auth', 'verified'])->name('panel');

// Rutas protegidas - Requieren autenticación Y verificación de email
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Rutas de perfil
    Route::prefix('perfil')->name('perfil.')->group(function () {
        Route::get('/', [PerfilController::class, 'editar'])->name('editar');
        Route::patch('/', [PerfilController::class, 'actualizar'])->name('actualizar');
        Route::post('/ubicacion', [PerfilController::class, 'actualizarUbicacion'])->name('actualizar-ubicacion');
    });
    
    // Rutas de citas médicas (CRUD completo)
    Route::resource('citas', CitaController::class);
    
    // Ruta adicional para actualizar solo el estado
    Route::patch('/citas/{cita}/estado', [CitaController::class, 'updateEstado'])
        ->name('citas.actualizar-estado');
});

// Incluir las rutas de autenticación de Breeze
require __DIR__.'/auth.php';