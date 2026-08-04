<?php

use App\Http\Controllers\Catalogo\GeneroController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permissao'])->group(function () {
    Route::get('/genero/buscar', [GeneroController::class, 'buscar'])->name('catalogo.genero.buscar');
    Route::post('/genero/criar', [GeneroController::class, 'criar'])->name('catalogo.genero.criar');
    Route::post('/genero/editar/{id}', [GeneroController::class, 'editar'])->name('catalogo.genero.editar');
    Route::post('/genero/remover/{id}', [GeneroController::class, 'remover'])->name('catalogo.genero.remover');
});
