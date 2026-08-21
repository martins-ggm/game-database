<?php

namespace App\Http\Resources\Catalogo\Plataforma;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

/**
 * Contexto: plataforma aninhada dentro de outra entidade (ex: as plataformas
 * de um jogo) e dropdowns. Só identidade — sem 'lancamento' e 'criado_em'.
 *
 * Herda com Arr::only conforme o guidelines 3.13.4: o toArray do pai só lê
 * colunas da própria plataforma, então não há query extra escondida.
 */
class PlataformaSelectResource extends PlataformaResource
{
    public function toArray($request): array
    {
        return Arr::only(parent::toArray($request), ['id', 'nome']);
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
