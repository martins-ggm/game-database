<?php

namespace App\Http\Controllers\Gerenciador;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Http\DTO\Gerenciador\UsuarioDTO;
use App\Http\DTO\Gerenciador\UsuarioLoginDTO;
use App\Http\Resources\Gerenciador\Usuario\UsuarioResource;
use App\Services\Gerenciador\Interfaces\IUsuarioService;
use Illuminate\Http\JsonResponse;

class UsuarioController extends Controller
{


    public function __construct(protected IUsuarioService $usuario_service) {}

    public function criar(): View
    {

        return view(view: 'auth.criarUsuario');
    }


    public function incluir(Request $request): JsonResponse
    {

        $dto = UsuarioDTO::fromRequest(request: $request, bool_validar_novo: true);
        $usuario = $this->usuario_service->criar(dados: $dto);

        return response()->json(
            data: ['mensagem' => 'Salvo com sucesso.', 'usuario' => UsuarioResource::criar($usuario)],
            status: 200
        );
    }

    public function acessar(): View
    {


        return view(view: 'auth.login');
    }

    public function autenticar(Request $request)
    {

        $dto = UsuarioLoginDTO::fromRequest(request: $request, bool_validar_login: true);
        $usuario = $this->usuario_service->autenticar(dados: $dto);

        $request->session()->regenerate();

        return response()->json(
            data: [
                'mensagem' => 'Bem-vindo de volta!',
                'usuario' => UsuarioResource::criar($usuario),
                'redirect' => '/',
            ],
            status: 200,

        );
    }
}
