<?php

use App\Http\Controllers\Catalogo\DesenvolvedoraController;
use App\Http\Controllers\Catalogo\GeneroController;
use App\Http\Controllers\Catalogo\JogoController;
use App\Http\Controllers\Catalogo\PlataformaController;
use Illuminate\Support\Facades\Route;

// ---- Buscas públicas ----
Route::get('/catalogo/jogos/buscaSimples', [JogoController::class, 'buscaSimples'])->name('catalogo.jogo.buscaSimples');

// ---- Buscas autenticadas (admin) ----
Route::middleware(['auth', 'permissao'])->group(function () {
    Route::get('/jogo/buscar', [JogoController::class, 'buscar'])->name('catalogo.jogo.buscar');
    Route::get('/plataforma/buscar', [PlataformaController::class, 'buscar'])->name('catalogo.plataforma.buscar');
    Route::get('/desenvolvedora/buscar', [DesenvolvedoraController::class, 'buscar'])->name('catalogo.desenvolvedora.buscar');
    Route::get('/genero/buscar', [GeneroController::class, 'buscar'])->name('catalogo.genero.buscar');
});
