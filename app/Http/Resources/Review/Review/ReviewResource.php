<?php

namespace App\Http\Resources\Review\Review;

use App\Http\Resources\Catalogo\Jogo\JogoListagemResource;
use App\Http\Resources\Gerenciador\Usuario\UsuarioSelectResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class ReviewResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'nota'      => $this->nota,
            'review'    => $this->review,
            'criado_em' => $this->created_at?->format('d/m/Y H:i'),

            // autor da review — eager-load com ->with('usuario')
            'usuario' => $this->whenLoaded(
                'usuario',
                fn() => UsuarioSelectResource::criar($this->usuario)
            ),

            // jogo avaliado — útil ao listar as reviews de um usuário; eager-load com ->with('jogo')
            'jogo' => $this->whenLoaded(
                'jogo',
                fn() => JogoListagemResource::criar($this->jogo)
            ),
        ];
    }

    public static function criar($dados): array|JsonResource|AnonymousResourceCollection
    {
        if ($dados instanceof LengthAwarePaginator) {
            return [
                'data'           => static::collection($dados->items()),
                'current_page'   => $dados->currentPage(),
                'last_page'      => $dados->lastPage(),
                'per_page'       => $dados->perPage(),
                'total'          => $dados->total(),
                'from'           => $dados->firstItem(),
                'to'             => $dados->lastItem(),
                'has_more_pages' => $dados->hasMorePages(),
            ];
        }

        if ($dados instanceof Collection) {
            return static::collection($dados);
        }

        return new static($dados);
    }
}
