<?php

use App\Http\Controllers\Catalogo\JogoController;
use Illuminate\Support\Facades\Route;

// Busca pública (dropdown do dashboard)
Route::get('/catalogo/jogos/buscaSimples', [JogoController::class, 'buscaSimples'])->name('catalogo.jogo.buscaSimples');

// Ações admin
Route::middleware(['auth', 'permissao', 'auditoria'])->group(function () {
    Route::get('/jogo/buscar', [JogoController::class, 'buscar'])->name('catalogo.jogo.buscar');
    Route::post('/jogo/criar', [JogoController::class, 'criar'])->name('catalogo.jogo.criar');
    Route::post('/jogo/editar/{id}', [JogoController::class, 'editar'])->name('catalogo.jogo.editar');
    Route::post('/jogo/remover/{id}', [JogoController::class, 'remover'])->name('catalogo.jogo.remover');
});
