<?php

use App\Http\Controllers\Review\ReviewController;
use Illuminate\Support\Facades\Route;






Route::get('reviews/{id}', [ReviewController::class, 'reviewsUsuario'])->name('review.usuario');

Route::middleware(['auth', 'auditoria'])->group(function () {

    Route::post('review/criar', [ReviewController::class, 'criar'])->name('review.criar');
    Route::post('review/editar/{id}', [ReviewController::class, 'editar'])->name('review.editar');
    Route::post('review/remover/{id}', [ReviewController::class, 'remover'])->name('review.remover');
});
