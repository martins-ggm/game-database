<?php

namespace App\Http\DTO\Catalogo;


use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class JogoDTO
{


    public function __construct(
        public ?int $id,
        public ?string $nome,
        public ?int $desenvolvedora,
        public ?array $generos,
        public ?array $plataformas,
        public ?UploadedFile $imagem
    ) {}

    public static function fromRequest(Request $request, bool $validarNovo): self
    {

        $dto = new self(
            id: $request->id ? (int) $request->id : null,
            nome: $request->nome,
            desenvolvedora: $request->desenvolvedora,
            generos: $request->generos,
            plataformas: $request->plataformas,
            imagem: $request->file('imagem')
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
                'plataformas' => $this->plataformas,
                'imagem' => $this->imagem
            ],
            [
                'nome' => ['required', 'string', 'max:255'],
                'desenvolvedora' => ['required', 'integer'],

                'generos' => ['required', 'array'],
                'generos.*' => ['integer'],

                'plataformas' =>  ['required', 'array'],
                'plataformas.*' => ['integer'],

                'imagem' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048']
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
                'plataformas' => $this->plataformas,
                'imagem' => $this->imagem
            ],
            [
                'id' =>  ['required', 'integer'],
                'nome' => ['required', 'string', 'max:255'],
                'desenvolvedora' => ['required', 'integer'],

                'generos' => ['required', 'array'],
                'generos.*' => ['integer'],

                'plataformas' =>  ['required', 'array'],
                'plataformas.*' => ['integer'],

                'imagem' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048']
            ]
        )->validate();
    }
}
