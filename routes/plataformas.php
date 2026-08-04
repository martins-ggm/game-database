<?php

use App\Http\Controllers\Catalogo\PlataformaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permissao'])->group(function () {
    Route::get('/plataforma/buscar', [PlataformaController::class, 'buscar'])->name('catalogo.plataforma.buscar');
    Route::post('/plataforma/criar', [PlataformaController::class, 'criar'])->name('catalogo.plataforma.criar');
    Route::post('/plataforma/editar/{id}', [PlataformaController::class, 'editar'])->name('catalogo.plataforma.editar');
    Route::post('/plataforma/remover/{id}', [PlataformaController::class, 'remover'])->name('catalogo.plataforma.remover');
});
