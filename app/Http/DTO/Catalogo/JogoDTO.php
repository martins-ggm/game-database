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
        public ?string $lancamento,
        public ?array $generos,
        public ?array $plataformas,
        public ?UploadedFile $imagem,
        public ?string $descricao
    ) {}

    public static function fromRequest(Request $request, bool $validarNovo): self
    {

        $dto = new self(
            id: $request->id ? (int) $request->id : null,
            nome: $request->nome,
            desenvolvedora: $request->desenvolvedora,
            lancamento: $request->lancamento,
            generos: $request->generos,
            plataformas: $request->plataformas,
            imagem: $request->file('imagem'),
            descricao: $request->descricao
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
                'lancamento' => $this->lancamento,
                'generos' => $this->generos,
                'plataformas' => $this->plataformas,
                'imagem' => $this->imagem,
                'descricao' => $this->descricao
            ],
            [
                'nome' => ['required', 'string', 'max:255'],
                'desenvolvedora' => ['required', 'integer'],
                'lancamento' => ['nullable', 'date'],

                'generos' => ['required', 'array'],
                'generos.*' => ['integer'],

                'plataformas' =>  ['required', 'array'],
                'plataformas.*' => ['integer'],

                'imagem' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],

                'descricao' => ['nullable', 'string']
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
                'lancamento' => $this->lancamento,
                'generos' => $this->generos,
                'plataformas' => $this->plataformas,
                'imagem' => $this->imagem,
                'descricao' => $this->descricao

            ],
            [
                'id' =>  ['required', 'integer'],
                'nome' => ['required', 'string', 'max:255'],
                'desenvolvedora' => ['required', 'integer'],
                'lancamento' => ['nullable', 'date'],

                'generos' => ['required', 'array'],
                'generos.*' => ['integer'],

                'plataformas' =>  ['required', 'array'],
                'plataformas.*' => ['integer'],

                'imagem' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],

                'descricao' => ['nullable', 'string']
            ]
        )->validate();
    }
}
