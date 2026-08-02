<?php

use App\Http\Controllers\Catalogo\DesenvolvedoraController;
use App\Http\Controllers\Catalogo\GeneroController;
use App\Http\Controllers\Catalogo\JogoController;
use App\Http\Controllers\Catalogo\PlataformaController;
use App\Http\Controllers\Colecao\ColecaoController;
use Illuminate\Support\Facades\Route;

// Cadastro / edição / remoção de itens do catálogo (admin-only).
Route::middleware('auth')->group(function () {
    Route::get('/colecao/visualizar/{id}', [ColecaoController::class, 'visualizar'])->name('colecao.visualizar');


    // Coleção
    Route::post('/colecao/adicionar', [ColecaoController::class, 'adicionarNaColecao'])->name('colecao.adicionar');

    Route::middleware('permissao')->group(function () {
        Route::get('/jogo/novo', [JogoController::class, 'novo'])->name('catalogo.jogo.novo');
        Route::post('/jogo/criar', [JogoController::class, 'criar'])->name('catalogo.jogo.criar');
        Route::post('/jogo/remover/{id}', [JogoController::class, 'remover'])->name('catalogo.jogo.remover');
        Route::post('/jogo/editar/{id}', [JogoController::class, 'editar'])->name('catalogo.jogo.editar');

        // Plataforma
        Route::get('/plataforma/novo', [PlataformaController::class, 'novo'])->name('catalogo.plataforma.novo');
        Route::post('/plataforma/criar', [PlataformaController::class, 'criar'])->name('catalogo.plataforma.criar');
        Route::post('/plataforma/remover/{id}', [PlataformaController::class, 'remover'])->name('catalogo.plataforma.remover');
        Route::post('/plataforma/editar/{id}', [PlataformaController::class, 'editar'])->name('catalogo.plataforma.editar');

        // Desenvolvedora
        Route::get('/desenvolvedora/novo', [DesenvolvedoraController::class, 'novo'])->name('catalogo.desenvolvedora.novo');
        Route::post('/desenvolvedora/criar', [DesenvolvedoraController::class, 'criar'])->name('catalogo.desenvolvedora.criar');
        Route::post('/desenvolvedora/remover/{id}', [DesenvolvedoraController::class, 'remover'])->name('catalogo.desenvolvedora.remover');
        Route::post('/desenvolvedora/editar/{id}', [DesenvolvedoraController::class, 'editar'])->name('catalogo.desenvolvedora.editar');

        // Genero
        Route::get('/genero/novo', [GeneroController::class, 'novo'])->name('catalogo.genero.novo');
        Route::post('/genero/criar', [GeneroController::class, 'criar'])->name('catalogo.genero.criar');
        Route::post('/genero/remover/{id}', [GeneroController::class, 'remover'])->name('catalogo.genero.remover');
        Route::post('/genero/editar/{id}', [GeneroController::class, 'editar'])->name('catalogo.genero.editar');
    });
    // Jogo



});
