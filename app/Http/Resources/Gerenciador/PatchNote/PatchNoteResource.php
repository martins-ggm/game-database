<?php

namespace App\Http\Resources\Gerenciador\PatchNote;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class PatchNoteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'versao'     => $this->versao,
            'titulo'     => $this->titulo,
            'mudancas'   => $this->mudancas, // [{ "tipo": "novo", "texto": "..." }, ...]
            'lancado_em' => $this->lancado_em?->format('Y-m-d'),
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
