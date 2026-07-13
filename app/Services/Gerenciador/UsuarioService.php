<?php


declare(strict_types=1);

namespace App\Services\Gerenciador;

use App\Http\DTO\Gerenciador\UsuarioDTO;
use App\Models\Gerenciador\Usuario;
use App\Repositorios\Gerenciador\Interfaces\IUsuarioRepositorio;
use App\Services\Gerenciador\Interfaces\IUsuarioService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\DTO\Gerenciador\UsuarioLoginDTO;
use Illuminate\Support\Facades\Cache;

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
                perfil_id: $dados->perfil_id,
                str_url_foto_perfil: '/https://images8.alphacoders.com/838/thumb-1920-838931.jpg'

            );


            return $this->usuario_repositorio->criarNovo($usuario);
        });
    }




    public function autenticar(UsuarioLoginDTO $dados): Usuario
    {
        throw_unless(
            Auth::attempt($dados->credenciais(), $dados->lembrar),
            new \Exception('Credenciais Inválidas')
        );


        $usuario = Auth::user();
        return $usuario;
    }



    public function desautenticar(): void
    {

        $usuario_id = Auth::id();

        Auth::logout();

        if ($usuario_id !== null) {

            Cache::forget(key: 'permissoes_usuario_' . $usuario_id);
        }
    }


    public function buscarPorId(int $id, bool $exception = false): Usuario
    {
        $usuario = $this->usuario_repositorio->buscarPorId(id: $id);

        throw_unless(
            $usuario,
            new \Exception('Usuário não encontrado.'),
        );

        return $usuario;
    }
}
