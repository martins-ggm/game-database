<?php

namespace App\Services\Review;

use App\Http\DTO\Review\ReviewDTO;
use App\Models\Review\Review;
use App\Repositorios\Catalogo\Interfaces\IJogoRepositorio;
use App\Repositorios\Gerenciador\Interfaces\IUsuarioRepositorio;
use App\Repositorios\Review\Interfaces\IReviewRepositorio;
use App\Services\Review\Interfaces\IReviewService;
use Illuminate\Database\Eloquent\Collection;

class ReviewService implements IReviewService
{

    public function __construct(
        protected IReviewRepositorio $reviewRepositorio,
        protected IUsuarioRepositorio $usuarioRepositorio,
        protected IJogoRepositorio $jogoRepositorio
    ) {}



    public function criar(ReviewDTO $dados): Review
    {

        throw_unless(
            $this->usuarioRepositorio->buscarPorId($dados->usuario_id)->exists(),
            new \Exception('Usuário não encontrado')
        );
        throw_unless(
            $this->jogoRepositorio->buscarPorId($dados->jogo_id)->exists(),
            new \Exception('Jogo não encontrado')
        );

        $review = Review::criar($dados->jogo_id, $dados->usuario_id, $dados->nota, $dados->review);

        return  $this->reviewRepositorio->criar($review);
    }

    public function editar(ReviewDTO $dados): Review
    {

        $review = $this->reviewRepositorio->buscarPorID($dados->id);
        throw_unless($review, new \Exception('Review não econtrada'));

        $review->editar($dados->nota, $dados->review);

        return $this->reviewRepositorio->editar($review);
    }

    public function remover(int $id, int $usuarioID): void
    {

        $review = $this->reviewRepositorio->buscarReviewDoUsuario($id, $usuarioID);

        throw_unless($review, new \Exception('Review não encontrada'));

        $this->reviewRepositorio->remover($review);
    }

    public function buscarReviews(int $jogoID): Collection
    {
        return $this->reviewRepositorio->buscarReviews($jogoID);
    }

    public function buscarReviewUsuario(int $jogoID, int $usuarioID): ?Review
    {
        return $this->reviewRepositorio->buscarReviewUsuario($jogoID, $usuarioID);
    }
}
