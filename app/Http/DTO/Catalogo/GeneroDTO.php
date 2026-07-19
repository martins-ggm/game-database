<?php

namespace App\Http\DTO\Catalogo;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;



class GeneroDTO
{



    public function __construct(public ?int $id, public ?string $nome) {}


    public function fromRequest(Request $request, Bool $validarNovo): self
    {

        $dto = new Self(

            id: $request->id ? (int) $request->id : null,
            nome: $request->nome

        );

        if ($validarNovo) {

            $dto->validarNovo();
        } else {

            $dto->validarEditar();
        }

        return $dto;
    }




    public function validarNovo(): void
    {

        Validator::make(['nome' => $this->nome], ['nome' => ['required', 'string']])->validate();
    }

    public function validarEditar(): void
    {

        Validator::make(['nome' => $this->nome, 'id' => $this->id], ['nome' => ['required', 'string'], 'id' => ['required', 'integer']])->validate();
    }
}
