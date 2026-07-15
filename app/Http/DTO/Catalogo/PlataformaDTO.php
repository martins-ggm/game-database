<?php


declare(strict_types=1);

namespace App\Http\DTO\Catalogo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PlataformaDTO
{

    public function __construct(

        public ?int $id = null,
        public ?string $nome = null,

    ) {}

    public static function fromRequest(Request $request, bool $bool_validar_novo = false): self
    {


        $dto = new self(

            id: $request->id,
            nome: $request->nome




        );

        if ($bool_validar_novo) {


            $dto->validarNovo();
        }

        return $dto;
    }



    public function validarNovo(): void
    {

        Validator::make(['nome' => $this->nome], [

            'nome' => ['required', 'string', 'max:255'],

        ],)->validate();
    }
}
