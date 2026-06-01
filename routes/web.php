<?php

use App\Http\Controllers\Gerenciador\AdminController;
use App\Http\Controllers\Gerenciador\DashboardController;
use App\Http\Controllers\Gerenciador\PerfilController;
use App\Http\Controllers\Gerenciador\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rotas gerenciador ----------->>>


// Usuarios
Route::get('/usuario/criar', [UsuarioController::class, 'criar'])->name('gerenciador.usuario.criar');
Route::post('/usuario/incluir', [UsuarioController::class, 'incluir'])->name('gerenciador.usuario.incluir');

// Autenticação
Route::get('/login', [UsuarioController::class, 'login'])->name('gerenciador.usuario.login');
Route::post('/usuario/autenticar', [UsuarioController::class, 'autenticar'])->name('gerenciador.usuario.autenticar');
Route::post('/logout', [UsuarioController::class, 'logout'])->middleware(['auth'])->name('gerenciador.usuario.logout');


  // Telas Gerais:

Route::middleware('auth')->group(function () {

    Route::get('/perfil/{id}', [UsuarioController::class, 'visualizarPerfil'])->name('gerenciador.usuario.perfil');
    Route::get('/dashboard', [DashboardController::class, 'visualizar'])->name('gerenciador.dashboard.visualizar');
    Route::get('/admin', [AdminController::class, 'visualizar'])->middleware(['permissao'])->name('gerenciador.admin.visualizar');



});


