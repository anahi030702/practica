<?php

use App\Http\Controllers\BusquedaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Busqueda;

Route::get('/', function () {
    return Inertia::render('Peliculas');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/peliculas', [BusquedaController::class, 'guardarBusqueda']);
Route::get('/peliculas/orden', [BusquedaController::class, 'ordenarColumna']);
Route::get('/peliculas/all', [BusquedaController::class, 'getAllBusquedas']);
Route::get('/peliculas/busqueda', [BusquedaController::class, 'busquedaPorColumna']);


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
