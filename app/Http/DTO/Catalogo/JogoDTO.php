<?php

namespace App\Http\DTO\Catalogo;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class JogoDTO
{


    public function __construct(
        public ?int $id,
        public ?string $nome,
        public ?int $desenvolvedora,
        public ?array $generos,
        public ?array $plataformas
    ) {}

    public static function fromRequest(Request $request, bool $validarNovo): self
    {

        $dto = new self(
            id: $request->id ? (int) $request->id : null,
            nome: $request->nome,
            desenvolvedora: $request->desenvolvedora,
            generos: $request->generos,
            plataformas: $request->plataformas
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

        Validator::make(
            [
                'nome' => $this->nome,
                'desenvolvedora' => $this->desenvolvedora,
                'generos' => $this->generos,
                'plataformas' => $this->plataformas
            ],
            [
                'nome' => ['required', 'string', 'max:255'],
                'desenvolvedora' => ['required', 'integer'],

                'generos' => ['required', 'array'],
                'generos.*' => ['integer'],

                'plataformas' =>  ['required', 'array'],
                'plataformas.*' => ['integer']
            ]
        )->validate();
    }


    public function validarEditar(): void
    {

        Validator::make(
            [
                'id' => $this->id,
                'nome' => $this->nome,
                'desenvolvedora' => $this->desenvolvedora,
                'generos' => $this->generos,
                'plataformas' => $this->plataformas
            ],
            [
                'id' =>  ['required', 'integer'],
                'nome' => ['required', 'string', 'max:255'],
                'desenvolvedora' => ['required', 'integer'],

                'generos' => ['required', 'array'],
                'generos.*' => ['integer'],

                'plataformas' =>  ['required', 'array'],
                'plataformas.*' => ['integer']
            ]
        )->validate();
    }
}
