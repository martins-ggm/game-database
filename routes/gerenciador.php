<?php

use App\Http\Controllers\Gerenciador\AdminController;
use Illuminate\Support\Facades\Route;

// Ações administrativas (restritas a admin)
Route::middleware(['auth', 'permissao'])->group(function () {
    Route::get('/admin/auditoria', [AdminController::class, 'auditoria'])->name('gerenciador.admin.auditoria');
});
