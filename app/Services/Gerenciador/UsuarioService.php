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
use App\Services\Imagem\Interfaces\IImagemService;
use Illuminate\Support\Facades\Cache;
use Throwable;

class UsuarioService implements IUsuarioService
{


    public function __construct(
        protected IUsuarioRepositorio $usuario_repositorio,
        protected IImagemService $imagemService
    ) {}



    public function criar(UsuarioDTO $dados): Usuario
    {

        $caminhos = $dados->imagem ? $this->imagemService->salvarPerfil($dados->imagem) : ['grande' => null, 'pequena' => null];

        try {
            return DB::transaction(function () use ($dados, $caminhos) {

                $usuario = Usuario::criar(

                    nome: $dados->nome,
                    email: $dados->email,
                    password: $dados->password,
                    perfil_id: $dados->perfil_id,
                    imagemPequena: $caminhos['pequena'],
                    imagemGrande: $caminhos['grande']

                );

                return $this->usuario_repositorio->criarNovo($usuario);
            });
        } catch (Throwable $e) {

            $this->imagemService->remover($caminhos);
            throw $e;
        }
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

    public function editar(UsuarioDTO $dados): Usuario
    {

        $usuario = $this->usuario_repositorio->buscarPorId($dados->id);
        throw_unless($usuario, new \Exception('Usuário não encontrado'));
        $caminhosAntigos = [$usuario->url_imagem_pequena, $usuario->url_imagem_grande];
        $caminhosNovos = $dados->imagem ? $this->imagemService->salvarPerfil($dados->imagem) : null;

        try {

            $perfilAtualizado = DB::transaction(function () use ($dados, $caminhosNovos, $usuario) {

                $usuario->editar($dados->nome);

                if ($caminhosNovos) {
                    $usuario->url_imagem_pequena = $caminhosNovos['pequena'];
                    $usuario->url_imagem_grande = $caminhosNovos['grande'];
                }

                return $this->usuario_repositorio->editar($usuario);
            });
        } catch (\Throwable $e) {

            if ($caminhosNovos) {
                $this->imagemService->remover($caminhosNovos);
            }

            throw $e;
        }
        if ($caminhosNovos) {
            $this->imagemService->remover($caminhosAntigos);
        }

        return $perfilAtualizado;
    }
}
