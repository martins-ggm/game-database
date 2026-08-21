<?php

namespace App\Http\Resources\Catalogo\Genero;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

/**
 * Contexto: gênero aninhado dentro de outra entidade (ex: os gêneros de um
 * jogo) e dropdowns. Só identidade — sem 'criado_em', que não interessa a
 * quem está exibindo a etiqueta.
 *
 * Aqui a herança do guidelines 3.13.4 é segura: o toArray do pai só lê colunas
 * do próprio gênero, então o Arr::only não paga nenhuma query extra.
 */
class GeneroSelectResource extends GeneroResource
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
