<?php

use App\Http\Controllers\Colecao\ColecaoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::post('/colecao/adicionar', [ColecaoController::class, 'adicionarNaColecao'])->name('colecao.adicionar');
});
