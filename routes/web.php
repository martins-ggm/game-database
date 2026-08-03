<?php

use App\Http\Controllers\Catalogo\JogoController;
use App\Http\Controllers\Colecao\ColecaoController;
use App\Http\Controllers\Gerenciador\AdminController;
use App\Http\Controllers\Gerenciador\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'visualizar'])->name('gerenciador.dashboard.visualizar');

// Catálogo público (jogos por categoria)
Route::get('/catalogo', [JogoController::class, 'catalogo'])->name('catalogo.jogos');

// Visualização pública de jogo
Route::get('/jogo/visualizar/{id}', [JogoController::class, 'visualizar'])->name('catalogo.jogo.visualizar');

// ---- Arquivos de rota ----
require __DIR__ . '/busca.php';            // pesquisas (GET) — públicas e autenticadas
require __DIR__ . '/usuario.php';          // login, perfil, edição de usuário
require __DIR__ . '/cadastroPublica.php';  // auto-registro de usuário (público)
require __DIR__ . '/cadastro.php';         // cadastro de catálogo (admin)

// ---- Telas admin restantes ----
Route::middleware(['auth', 'permissao'])->group(function () {
    Route::get('/admin', [AdminController::class, 'visualizar'])->name('gerenciador.admin.visualizar');
});
