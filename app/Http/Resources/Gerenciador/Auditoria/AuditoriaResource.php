<?php

namespace App\Http\Resources\Gerenciador\Auditoria;

use App\Http\Resources\Gerenciador\Usuario\UsuarioSelectResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class AuditoriaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'usuario_id' => $this->usuario_id,
            'rota' => $this->rota,
            'alvo_id' => $this->alvo_id,
            'horario' => $this->created_at?->format('d/m/Y H:i'),

            // eager-load com ->with('usuario') — atividadeRecente e trazerTodas já fazem
            'usuario' => $this->whenLoaded(
                'usuario',
                fn() => UsuarioSelectResource::criar($this->usuario)
            ),
        ];
    }

    public static function criar($dados): array | JsonResource |AnonymousResourceCollection
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
