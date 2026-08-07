<?php

namespace App\Http\Controllers\Gerenciador;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Http\DTO\Gerenciador\UsuarioDTO;
use App\Http\DTO\Gerenciador\UsuarioLoginDTO;
use App\Http\Resources\Gerenciador\Usuario\UsuarioResource;
use App\Services\Colecao\Interfaces\IColecaoService;
use App\Services\Gerenciador\Interfaces\IUsuarioService;
use App\Services\Review\Interfaces\IReviewService;
use Illuminate\Http\JsonResponse;

class UsuarioController extends Controller
{


    public function __construct(
        protected IUsuarioService $usuario_service,
        protected IColecaoService $colecaoService,
        protected IReviewService $reviewService
    ) {}

    public function criar(): View
    {

        return view(view: 'auth.criarUsuario');
    }


    public function incluir(Request $request): JsonResponse
    {

        $dto = UsuarioDTO::fromRequest(request: $request, bool_validar_novo: true);
        $usuario = $this->usuario_service->criar(dados: $dto);

        return response()->json(
            data: ['mensagem' => 'Salvo com sucesso.', 'usuario' => UsuarioResource::criar($usuario), 'redirect' => '/login'],
            status: 200
        );
    }

    public function login(): View
    {

        return view(view: 'auth.login');
    }

    public function autenticar(Request $request)
    {

        $dto = UsuarioLoginDTO::fromRequest(request: $request, bool_validar_login: false);
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
        $ultimosJogos = $this->colecaoService->ultimosAdicionados($usuario_id, 10);
        $usuario = $this->usuario_service->buscarPorId($usuario_id);
        $totalJogos = $this->colecaoService->contarTodasDoUsuario($usuario_id);
        $totalReviews = $this->reviewService->totalReviewDoUsuario($usuario_id);
        $reviewsRecentes = $this->reviewService->ReviewsDoUsuario($usuario_id, 6);

        return view(view: 'gerenciador.perfil', data: compact(
            'usuario',
            'ultimosJogos',
            'totalJogos',
            'totalReviews',
            'reviewsRecentes'
        ));
    }


    public function logout(Request $request): JsonResponse
    {


        $this->usuario_service->desautenticar();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(
            data: ['mensagem' => 'Até logo!', 'redirect' => route('home')],
            status: 200

        );
    }

    public function editar(Request $request): JsonResponse
    {

        $dto = UsuarioDTO::fromRequest($request);

        abort_unless(auth()->id() === $dto->id, 403, 'Ação não autorizada.');

        $usuario = $this->usuario_service->editar($dto);

        return response()->json([
            'mensagem' => 'Usuario atualizado com sucesso!',
            'usuario' => UsuarioResource::criar($usuario)
        ], status: 200);
    }
}
