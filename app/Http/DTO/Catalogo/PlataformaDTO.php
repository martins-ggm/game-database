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
        public ?string $lancamento = null

    ) {}

    public static function fromRequest(Request $request, bool $bool_validar_novo = false): self
    {


        $dto = new self(

            id: $request->id ? (int) $request->id : null,
            nome: $request->nome,
            lancamento: $request->lancamento

        );

        if ($bool_validar_novo) {

            $dto->validarNovo();
        } else {

            $dto->validarEditar();
        }

        return $dto;
    }



    public function validarNovo(): void
    {

        Validator::make(['nome' => $this->nome, 'lancamento' => $this->lancamento], [

            'nome' => ['required', 'string', 'max:255'],
            'lancamento' => ['required', 'date']

        ],)->validate();
    }


    public function validarEditar(): void
    {

        Validator::make(['nome' => $this->nome, 'lancamento' => $this->lancamento, 'id' => $this->id], [

            'id' => ['required', 'integer'],
            'nome' => ['required', 'string', 'max:255'],
            'lancamento' => ['required', 'date']

        ])->validate();
    }
}
