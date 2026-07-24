<?php

namespace App\Http\Resources\Catalogo\Jogo;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class JogoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'   => $this->id,
            'nome' => $this->nome,
            'imagem_grande' => $this->url_imagem_grande ? Storage::url($this->url_imagem_grande) : null,
            'imagem_pequena' => $this->url_imagem_pequena ? Storage::url($this->url_imagem_pequena) : null,

            'desenvolvedora' => $this->desenvolvedora ? [
                'id'   => $this->desenvolvedora->id,
                'nome' => $this->desenvolvedora->nome,
            ] : null,

            'plataformas' => $this->plataformas->map(fn($plataforma) => [
                'id'   => $plataforma->id,
                'nome' => $plataforma->nome,
            ])->values(),

            'generos' => $this->generos->map(fn($genero) => [
                'id'   => $genero->id,
                'nome' => $genero->nome,
            ])->values(),
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
