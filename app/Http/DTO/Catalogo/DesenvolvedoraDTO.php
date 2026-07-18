<?php

declare(strict_types=1);

namespace App\Http\DTO\Catalogo;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class DesenvolvedoraDTO
{


    public function __construct(public ?int $id = null, public ?string $nome = null) {}


    public static function fromRequest(Request $request, bool $validarNovo): self
    {

        $dto = new self(

            id: $request->id ? (int) $request->id : null,
            nome: $request->nome



        );

        if ($validarNovo) {

         $dto->validarNovo();

        }

        return $dto;
    }

    public function validarNovo(): void
    {

        Validator::make(['nome' => $this->nome], ['nome' => ['required', 'string', 'max:255']])->validate();
    }
}
