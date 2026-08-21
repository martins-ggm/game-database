<?php

namespace App\Http\Resources\Catalogo\Jogo;

use App\Http\Resources\Catalogo\Empresa\EmpresaSelectResource;
use App\Http\Resources\Catalogo\Genero\GeneroSelectResource;
use App\Http\Resources\Catalogo\Plataforma\PlataformaSelectResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class JogoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'   => $this->id,
            'nome' => $this->nome,
            'lancamento' => $this->lancamento?->format('Y-m-d'),
            'descricao' => $this->descricao,
            'imagem_grande' => $this->capa(),
            'imagem_pequena' => $this->capa(false),

            // Aninhados via whenLoaded: sem eager load a chave some, em vez de
            // disparar uma query escondida por linha. Quem consome estes campos
            // (a tabela do admin) vem do buscarPaginado, que carrega as três.
            'desenvolvedora' => $this->whenLoaded(
                'desenvolvedoras',
                fn() => $this->desenvolvedora ? EmpresaSelectResource::criar($this->desenvolvedora) : null
            ),

            'plataformas' => $this->whenLoaded(
                'plataformas',
                fn() => PlataformaSelectResource::criar($this->plataformas)
            ),

            'generos' => $this->whenLoaded(
                'generos',
                fn() => GeneroSelectResource::criar($this->generos)
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
