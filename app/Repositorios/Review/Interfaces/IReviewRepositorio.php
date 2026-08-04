<?php


namespace App\Repositorios\Review\Interfaces;

use App\Models\Review\Review;
use Illuminate\Database\Eloquent\Collection;

interface IReviewRepositorio
{

    public function criar(Review $review): Review;
    public function editar(Review $review): Review;
    public function buscarPorID(int $id): ?Review;
    public function remover(Review $review): void;
    public function buscarReviews(int $jogoID): Collection;
    public function buscarReviewUsuario(int $jogoID, int $usuarioID): ?Review;

}
