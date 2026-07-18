<?php

namespace App\Http\Controllers\Gerenciador;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Http\DTO\Gerenciador\UsuarioDTO;
use App\Http\DTO\Gerenciador\UsuarioLoginDTO;
use App\Http\Resources\Gerenciador\Usuario\UsuarioResource;
use App\Services\Gerenciador\Interfaces\IUsuarioService;
use BcMath\Number;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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

    public function login(): View
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
                'redirect' => '/dashboard',
            ],
            status: 200,

        );
    }

    public function visualizarPerfil(int $usuario_id): View
    {

        $usuario = $this->usuario_service->buscarPorId($usuario_id);
        return view(view: 'gerenciador.perfil', data: compact('usuario'));
    }


    public function logout(Request $request): JsonResponse
    {


        $this->usuario_service->desautenticar();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(
            data: ['mensagem' => 'Até logo!', 'redirect' => route('gerenciador.usuario.login')],
            status: 200

        );
    }
}
