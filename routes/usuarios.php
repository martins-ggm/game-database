<?php

use App\Http\Controllers\Gerenciador\UsuarioController;
use Illuminate\Support\Facades\Route;

// Públicas (registro + login)
Route::post('/usuario/incluir', [UsuarioController::class, 'incluir'])->name('gerenciador.usuario.incluir');
Route::post('/usuario/autenticar', [UsuarioController::class, 'autenticar'])->name('gerenciador.usuario.autenticar');

// Autenticadas
Route::middleware('auth')->group(function () {
    Route::post('/logout', [UsuarioController::class, 'logout'])->name('gerenciador.usuario.logout');
    Route::post('/usuario/atualizar/{id}', [UsuarioController::class, 'editar'])->name('gerenciador.usuario.atualizar');
});
