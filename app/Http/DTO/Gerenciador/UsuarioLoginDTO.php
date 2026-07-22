<?php

declare(strict_types=1);


namespace App\Http\DTO\Gerenciador;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class UsuarioLoginDTO
{

    public function __construct(

        public ?string $email = null,
        public ?string $password = null,
        public bool $lembrar = false

    ) {}


    public static function fromRequest(Request $request, bool $bool_validar_login = false): self
    {


        $dto = new self(

            email: $request->email,
            password: $request->password,
            lembrar: (bool) $request->lembrar,


        );
        if ($bool_validar_login) {

            $dto->validarLogin();
        }

        return $dto;
    }



    public function validarLogin(): void
    {

        Validator::make($this->todosAtributos(), [


            'email' => ['required', 'string'],
            'password' => ['required', 'string'],

        ], [
            'email.required'    => 'O e-mail é obrigatório.',
            'email.email'       => 'Informe um e-mail válido.',
            'password.required' => 'A senha é obrigatória.',
        ])->validate();
    }





    public function todosAtributos(): array
    {


        return [

            'email' => $this->email,
            'password' => $this->password,
            'lembrar' => $this->lembrar

        ];
    }

    public function credenciais(): array
    {


        return [
            'email' => $this->email,
            'password' => $this->password
        ];
    }
}
