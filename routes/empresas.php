<?php

use App\Http\Controllers\Catalogo\EmpresaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permissao', 'auditoria'])->group(function () {
    Route::get('/empresa/buscar', [EmpresaController::class, 'buscar'])->name('catalogo.empresa.buscar');
    Route::post('/empresa/criar', [EmpresaController::class, 'criar'])->name('catalogo.empresa.criar');
    Route::post('/empresa/editar/{id}', [EmpresaController::class, 'editar'])->name('catalogo.empresa.editar');
    Route::post('/empresa/remover/{id}', [EmpresaController::class, 'remover'])->name('catalogo.empresa.remover');
});
