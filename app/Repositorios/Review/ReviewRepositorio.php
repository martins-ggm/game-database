<?php


namespace App\Repositorios\Review;

use App\Models\Review\Review;
use App\Repositorios\Review\Interfaces\IReviewRepositorio;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ReviewRepositorio implements IReviewRepositorio
{

    public function __construct(protected Review $modelo) {}



    public function criar(Review $review): Review
    {
        throw_if($this->modelo
            ->newQuery()
            ->where('jogo_id', $review->jogo_id)
            ->where('usuario_id', $review->usuario_id)->exists(), new \Exception('Review para o jogo já existente.'));

        $review->save();
        return $review;
    }

    public function editar(Review $review): Review
    {

        $review->save();
        return $review;
    }

    public function buscarPorID(int $id): ?Review
    {
        return $this->modelo->newQuery()->find($id);
    }


    public function remover(Review $review): void
    {
        $review->delete();
    }

    public function buscarReviewUsuario(int $jogoid, int $usuarioID): ?Review
    {

        return $this->modelo->newQuery()
            ->where('jogo_id', $jogoid)
            ->where('usuario_id', $usuarioID)
            ->with('usuario')
            ->first();
    }


    public function buscarReviews(int $jogoID): Collection
    {
        return $this->modelo->newQuery()->where('jogo_id', $jogoID)->with('usuario')->get();
    }

    public function totalReviewDoUsuario(int $usuarioID): int
    {

        return $this->modelo->newQuery()->where('usuario_id', $usuarioID)->count();
    }

    public function ReviewsDoUsuario(int $usuarioID, ?int $quantidade = null): Collection | LengthAwarePaginator
    {

        $query = $this->modelo->newQuery()
            ->where('usuario_id', $usuarioID)
            ->with('jogo','usuario')
            ->orderBy('created_at', 'desc')
            ->when($quantidade, fn($query) => $query->take($quantidade));

        return $quantidade ? $query->get() : $query->paginate();
    }
}
