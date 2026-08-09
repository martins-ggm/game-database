<?php

use App\Http\Controllers\Catalogo\DesenvolvedoraController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permissao', 'auditoria'])->group(function () {
    Route::get('/desenvolvedora/buscar', [DesenvolvedoraController::class, 'buscar'])->name('catalogo.desenvolvedora.buscar');
    Route::post('/desenvolvedora/criar', [DesenvolvedoraController::class, 'criar'])->name('catalogo.desenvolvedora.criar');
    Route::post('/desenvolvedora/editar/{id}', [DesenvolvedoraController::class, 'editar'])->name('catalogo.desenvolvedora.editar');
    Route::post('/desenvolvedora/remover/{id}', [DesenvolvedoraController::class, 'remover'])->name('catalogo.desenvolvedora.remover');
});
