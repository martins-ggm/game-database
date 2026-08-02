<?php

use App\Http\Controllers\Gerenciador\UsuarioController;
use Illuminate\Support\Facades\Route;

// Cadastro público (auto-registro de usuário)
Route::get('/usuario/criar', [UsuarioController::class, 'criar'])->name('gerenciador.usuario.criar');
Route::post('/usuario/incluir', [UsuarioController::class, 'incluir'])->name('gerenciador.usuario.incluir');

