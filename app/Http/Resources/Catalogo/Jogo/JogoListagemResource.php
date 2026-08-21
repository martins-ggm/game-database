<?php

namespace App\Http\Resources\Catalogo\Jogo;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Contexto: listagens de jogo que só mostram capa, nome e ano — o dropdown de
 * busca do dashboard e a grade do catálogo. Só os quatro campos exibidos.
 *
 * Quem precisa da ficha completa (descrição, gêneros, plataformas,
 * desenvolvedora), como a tabela do admin, usa o JogoResource.
 *
 * NÃO estende JogoResource de propósito — e isso contraria o guidelines 3.13.4,
 * que manda contextos enxutos herdarem e restringirem com Arr::only. O motivo:
 * o toArray() do pai monta 'desenvolvedora', 'plataformas' e 'generos', e essas
 * três relações não vêm carregadas na busca. Cada linha dispara 3 lazy loads
 * ANTES do Arr::only ter chance de descartar os campos — medido em 31 queries
 * para 10 linhas. Herdar aqui pagaria o N+1 inteiro para jogar o resultado fora.
 *
 * Se um dia a busca passar a exibir gênero ou desenvolvedora, o caminho é
 * carregar a relação no repositório e usar whenLoaded() aqui — não herdar.
 */
class JogoListagemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'nome'           => $this->nome,
            'lancamento'     => $this->lancamento?->format('Y-m-d'),
            'imagem_pequena' => $this->capa(false),
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
