<?php

namespace App\Http\Resources\Gerenciador\Usuario;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class UsuarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [

                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'perfil_id' => $this->perfil_id,
                'criado_em' => $this->created_at?->format('d/m/Y H:i')

            ];
    }

    public static function criar($dados): array|JsonResource|AnonymousResourceCollection
    {



        if ($dados instanceof LengthAwarePaginator) {


            return [

                'data' => static::collection($dados->items()),
                'current_page' => $dados->currentPage(),
                'last_page' => $dados->lastPage(),
                'per_page' => $dados->perPage(),
                'total' => $dados->total(),
                'from' => $dados->firstItem(),
                'to' => $dados->lastItem(),
                'has_more_pages' => $dados->hasMorePages()

            ];
        }
        if ($dados instanceof Collection) {

            return  static::collection($dados);
        }

        return new static($dados);
    }
}
