<?php

namespace App\Http\Resources\Gerenciador\Usuario;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

/**
 * Contexto: usuário aninhado dentro de outra entidade — o autor de uma review,
 * o responsável por um registro de auditoria. É a "etiqueta de usuário":
 * identidade e avatar, nada mais.
 *
 * Deixa 'email' e 'perfil_id' de fora de propósito. Esses aninhados aparecem em
 * respostas públicas (as reviews de um jogo são visíveis pra qualquer visitante),
 * e o e-mail de terceiros não tem por que trafegar até lá.
 *
 * Herda com Arr::only conforme o guidelines 3.13.4 — o toArray do pai só lê
 * colunas do próprio usuário, então não há query extra escondida.
 */
class UsuarioSelectResource extends UsuarioResource
{
    public function toArray($request): array
    {
        return Arr::only(parent::toArray($request), ['id', 'nome', 'imagem_pequena']);
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
