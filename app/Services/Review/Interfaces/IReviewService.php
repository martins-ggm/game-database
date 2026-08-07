<?php



namespace App\Services\Review\Interfaces;

use App\Http\DTO\Review\ReviewDTO;
use App\Models\Review\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;


interface IReviewService
{
    public function criar(ReviewDTO $dados): Review;
    public function editar(ReviewDTO $dados): Review;
    public function remover(int $id, int $usuarioID): void;
    public function buscarReviews(int $jogoID): Collection;
    public function buscarReviewUsuario(int $jogoID, int $usuarioID): ?Review;
    public function totalReviewDoUsuario(int $usuarioID): int;
    public function ReviewsDoUsuario(int $usuarioID, ?int $quantidade = null): Collection | LengthAwarePaginator;
}
