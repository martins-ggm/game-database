<?php


declare(strict_types=1);

namespace App\Http\DTO\Gerenciador;

use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsuarioDTO
{


    public function __construct(

        public ?int $id = null,
        public ?string $nome = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?string $password_confirmation = null,
        public ?int $perfil_id = null,
        public ?UploadedFile $imagem = null

    ) {}

    public static function fromRequest(Request $request, bool $bool_validar_novo = false): self
    {

        $dto = new self(
            id: $request->id ? (int) $request->id : null,
            nome: $request->nome,
            email: $request->email,
            password: $request->password,
            password_confirmation: $request->password_confirmation,
            perfil_id: $request->perfil_id ? (int) $request->perfil_id : null,
            imagem: $request->file('imagem')
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


        Validator::make($this->todosAtributos(), [

            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'imagem' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048']
        ], [

            'nome.required'      => 'O nome é obrigatório.',
            'email.required'     => 'O e-mail é obrigatório.',
            'email.email'        => 'Informe um e-mail válido.',
            'password.required'  => 'A senha é obrigatória.',
            'password.min'       => 'A senha deve ter ao menos 6 caracteres.',
            'password.confirmed' => 'A confirmação de senha não confere.',
            'imagem.mimes' => 'Formato invalido de imagem, aceitos: jpeg, png, jpg e webp',
            'imagem.max' => 'A imagem supera o tamanho máximo permitido, máximo: 2mb'

        ])->validate();
    }

    public function todosAtributos(): array
    {

        return [
            'nome' => $this->nome,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'perfil_id' => $this->perfil_id,
            'imagem' => $this->imagem
        ];
    }


    public function validarEditar(): void
    {

        Validator::make(['nome' => $this->nome, 'imagem' => $this->imagem, 'id' => $this->id], [
            'id' => ['required', 'int'],
            'nome' => ['required', 'string', 'max:255'],
            'imagem' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048']
        ])->validate();
    }
}
