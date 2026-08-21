<?php

use App\Http\Controllers\Catalogo\EmpresaController;
use App\Http\Controllers\Catalogo\GeneroController;
use App\Http\Controllers\Catalogo\JogoController;
use App\Http\Controllers\Catalogo\PlataformaController;
use App\Http\Controllers\Colecao\ColecaoController;
use App\Http\Controllers\Gerenciador\AdminController;
use App\Http\Controllers\Gerenciador\DashboardController;
use App\Http\Controllers\Gerenciador\HomeController;
use App\Http\Controllers\Gerenciador\UsuarioController;
use Illuminate\Support\Facades\Route;

/*
 | Navegação de telas — só rotas que devolvem view.
 | As ações (criar/editar/remover/buscar) ficam nos arquivos de cada entidade.
 */

// ===== Públicas =====
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'visualizar'])->name('gerenciador.dashboard.visualizar');
Route::get('/catalogo', [JogoController::class, 'catalogo'])->name('catalogo.jogos');
Route::get('/jogo/visualizar/{id}', [JogoController::class, 'visualizar'])->name('catalogo.jogo.visualizar');
Route::get('/perfil/{id}', [UsuarioController::class, 'visualizarPerfil'])->name('gerenciador.usuario.perfil');

// Autenticação

Route::get('/login', [UsuarioController::class, 'login'])->name('gerenciador.usuario.login');
Route::get('/usuario/criar', [UsuarioController::class, 'criar'])->name('gerenciador.usuario.criar');

// ===== Autenticadas =====
Route::middleware('auth')->group(function () {
    Route::get('/colecao/visualizar/{id}', [ColecaoController::class, 'visualizar'])->name('colecao.visualizar');
});

// ===== Admin =====
Route::middleware(['auth', 'permissao'])->group(function () {
    Route::get('/admin', [AdminController::class, 'visualizar'])->name('gerenciador.admin.visualizar');
    Route::get('/jogo/novo', [JogoController::class, 'novo'])->name('catalogo.jogo.novo');
    Route::get('/plataforma/novo', [PlataformaController::class, 'novo'])->name('catalogo.plataforma.novo');
    Route::get('/empresa/novo', [EmpresaController::class, 'novo'])->name('catalogo.empresa.novo');
    Route::get('/genero/novo', [GeneroController::class, 'novo'])->name('catalogo.genero.novo');
});
