<?php

namespace App\Http\Controllers\Gerenciador;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Http\DTO\Gerenciador\UsuarioDTO;
use App\Http\Resources\Gerenciador\Usuario\UsuarioResource;
use App\Services\Gerenciador\Interfaces\IUsuarioService;
use Illuminate\Http\JsonResponse;

class UsuarioController extends Controller
{


    public function __construct(protected IUsuarioService $usuario_service) {}

    public function criar(Request $request): View
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
}
