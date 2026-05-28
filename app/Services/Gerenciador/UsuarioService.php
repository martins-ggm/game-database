<?php


declare(strict_types=1);

namespace App\Services\Gerenciador;

use App\Http\DTO\Gerenciador\UsuarioDTO;
use App\Models\Gerenciador\Usuario;
use App\Repositorios\Gerenciador\Interfaces\IUsuarioRepositorio;
use App\Services\Gerenciador\Interfaces\IUsuarioService;
use Illuminate\Support\Facades\DB;

class UsuarioService implements IUsuarioService
{


    public function __construct(
        protected IUsuarioRepositorio $usuario_repositorio
    ) {}



    public function criar(UsuarioDTO $dados): Usuario
    {
        return DB::transaction(function () use ($dados) {


            $usuario = Usuario::criar(

                name: $dados->name,
                email: $dados->email,
                password: $dados->password,
                perfil_id: $dados->perfil_id

            );


            return $this->usuario_repositorio->criarNovo($usuario);
        });
    }
}
