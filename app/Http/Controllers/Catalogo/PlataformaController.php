<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Http\DTO\Catalogo\PlataformaDTO;
use App\Http\Resources\Catalogo\Plataforma\PlataformaResource;
use App\Services\Catalogo\Interfaces\IPlataformaService;
use Illuminate\Http\JsonResponse;

class PlataformaController extends Controller
{

    public function __construct(protected IPlataformaService $plataforma_service) {}


    public function novo(): View
    {

        $plataformas = $this->plataforma_service->buscarTodas();

        return View(view: 'catalogo.plataformas', data: compact('plataformas'));
    }

    public function criar(Request $request): JsonResponse
    {

        $dto = PlataformaDTO::fromRequest(request: $request, bool_validar_novo: true);
        $plataforma = $this->plataforma_service->criar(dados: $dto);

        return response()->json(
            data: ['mensagem' => 'Salvo com sucesso.', 'plataforma' => PlataformaResource::criar($plataforma)],
            status: 200
        );
    }

    public function remover(int $id): JsonResponse
    {

        $this->plataforma_service->remover(id: $id);

        return response()->json(data: ['mensagem' => 'Removido com sucesso'], status: 200);
    }

    public function editar(Request $request): JsonResponse
    {

        $dto = PlataformaDTO::fromRequest(request: $request, bool_validar_novo: false);
        $plataforma = $this->plataforma_service->editar(dados: $dto);

        return response()->json(data: ['mensagem' => 'Alterado com sucesso!', 'plataforma' => PlataformaResource::criar($plataforma)], status: 200);
    }
}
