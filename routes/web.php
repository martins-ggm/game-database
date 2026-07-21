<?php

use App\Http\Controllers\Catalogo\DesenvolvedoraController;
use App\Http\Controllers\Catalogo\GeneroController;
use App\Http\Controllers\Catalogo\JogoController;
use App\Http\Controllers\Gerenciador\AdminController;
use App\Http\Controllers\Gerenciador\DashboardController;
use App\Http\Controllers\Gerenciador\PerfilController;
use App\Http\Controllers\Gerenciador\UsuarioController;
use App\Http\Controllers\Catalogo\PlataformaController;
use App\Models\Catalogo\Desenvolvedora;
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




// Telas Autenticadas:

Route::middleware('auth')->group(function () {

    Route::get('/perfil/{id}', [UsuarioController::class, 'visualizarPerfil'])->name('gerenciador.usuario.perfil');
    Route::get('/dashboard', [DashboardController::class, 'visualizar'])->name('gerenciador.dashboard.visualizar');


    // Telas admin:

    Route::middleware('permissao')->group(function () {

        Route::get('/admin', [AdminController::class, 'visualizar'])->middleware(['permissao'])->name('gerenciador.admin.visualizar');

        // Jogo

        Route::get('/jogo/novo', [JogoController::class, 'novo'])->name('catalogo.jogo.novo');
        Route::post('/jogo/criar', [JogoController::class, 'criar'])->name('catalogo.jogo.criar');
        Route::post('/jogo/remover/{id}', [JogoController::class, 'remover'])->name('catalogo.jogo.remover');
        Route::post('/jogo/editar/{id}', [JogoController::class, 'editar'])->name('catalogo.jogo.editar');

        // plataforma

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
        Route::get('/genero/buscar', [GeneroController::class, 'buscar'])->name('catalogo.genero.buscar');
    });
});
