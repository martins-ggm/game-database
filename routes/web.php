<?php

use App\Http\Controllers\Gerenciador\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});





// Rotas gerenciador ----------->>>


// Cadastro
Route::get('/usuario/criar', [UsuarioController::class, 'criar'])->name('gerenciador.usuario.criar');
Route::post('/usuario/incluir', [UsuarioController::class, 'incluir'])->name('gerenciador.usuario.incluir');


// Login
Route::get('/usuario/acessar', [UsuarioController::class, 'acessar'])->name('gerenciador.usuario.acessar');
Route::post('/usuario/autenticar', [UsuarioController::class, 'autenticar'])->name('gerenciador.usuario.autenticar');

