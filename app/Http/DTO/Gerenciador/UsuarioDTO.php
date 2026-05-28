<?php


declare(strict_types=1);

namespace App\Http\DTO\Gerenciador;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsuarioDTO
{


    public function __construct(

        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?string $password_confirmation = null,
        public ?int $perfil_id = null

    ) {}

    public static function fromRequest(Request $request, bool $bool_validar_novo = false): self
    {

        $dto = new self(
            name: $request->name,
            email: $request->email,
            password: $request->password,
            password_confirmation: $request->password_confirmation,
            perfil_id: $request->perfil_id ? (int) $request->perfil_id : null
        );

        if ($bool_validar_novo) {

            $dto->validarNovo();
        }

        return $dto;
    }


    public function validarNovo(): void
    {


        Validator::make($this->todosAtributos(), [

            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [

            'name.required'      => 'O nome é obrigatório.',
            'email.required'     => 'O e-mail é obrigatório.',
            'email.email'        => 'Informe um e-mail válido.',
            'password.required'  => 'A senha é obrigatória.',
            'password.min'       => 'A senha deve ter ao menos 6 caracteres.',
            'password.confirmed' => 'A confirmação de senha não confere.',



        ])->validate();
    }

    public function todosAtributos(): array
    {

        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'perfil_id' => $this->perfil_id
        ];
    }
}
