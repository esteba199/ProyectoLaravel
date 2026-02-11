<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CitaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (\Illuminate\Http\Request $request, \App\Services\WeatherService $weatherService) {
    $user = $request->user();
    $clima = $weatherService->getCurrentWeather($user->latitud, $user->longitud);
    $climaAdverso = $weatherService->isAdverse($clima);
    
    return view('dashboard', compact('clima', 'climaAdverso'));
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para Citas
    Route::resource('citas', CitaController::class);
    Route::patch('/citas/{cita}/estado', [CitaController::class, 'updateEstado'])->name('citas.updateEstado');
});

require __DIR__.'/auth.php';

