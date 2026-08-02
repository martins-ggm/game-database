<?php

use App\Http\Controllers\Gerenciador\UsuarioController;
use Illuminate\Support\Facades\Route;

// Públicas
Route::get('/perfil/{id}', [UsuarioController::class, 'visualizarPerfil'])->name('gerenciador.usuario.perfil');

// Autenticação
Route::get('/login', [UsuarioController::class, 'login'])->name('gerenciador.usuario.login');
Route::post('/usuario/autenticar', [UsuarioController::class, 'autenticar'])->name('gerenciador.usuario.autenticar');
Route::post('/logout', [UsuarioController::class, 'logout'])->middleware('auth')->name('gerenciador.usuario.logout');

// Autenticadas
Route::middleware('auth')->group(function () {
    Route::post('/usuario/atualizar/{id}', [UsuarioController::class, 'editar'])->name('gerenciador.usuario.atualizar');
});
