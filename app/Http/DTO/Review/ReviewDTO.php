<?php


namespace App\Http\DTO\Review;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ReviewDTO
{

    public function __construct(

        public ?int $id = null,
        public ?int $jogo_id = null,
        public ?int $usuario_id = null,
        public ?float $nota = null,
        public ?string $review = null,


    ) {}

    public static function fromRequest(Request $request, bool $validar_novo): self
    {

        $dto = new self(
            id: $request->id ? (int) $request->id : Null,
            jogo_id: $request->jogo_id,
            usuario_id: $request->usuario_id,
            nota: $request->nota,
            review: $request->review,

        );

        if ($validar_novo) {

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
                'jogo_id' => $this->jogo_id,
                'usuario_id' => $this->usuario_id,
                'nota' => $this->nota,
                'review' => $this->review,

            ],
            [
                'jogo_id' => ['required', 'integer'],
                'usuario_id' => ['required', 'integer'],
                'nota' => ['required', 'numeric'],
                'review' => ['nullable', 'string'],

            ]
        );
    }

    public function validarEditar(): void
    {

        Validator::make(
            [
                'id' => $this->id,
                'jogo_id' => $this->jogo_id,
                'usuario_id' => $this->usuario_id,
                'nota' => $this->nota,
                'review' => $this->review,

            ],
            [
                'id' => ['required', 'integer'],
                'jogo_id' => ['required', 'integer'],
                'usuario_id' => ['required', 'integer'],
                'nota' => ['required', 'numeric'],
                'review' => ['nullable', 'string'],

            ]
        );
    }
}
