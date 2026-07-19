<?php

namespace App\Http\Controllers\Catalogo;

use App\Http\Controllers\Controller;
use App\Http\DTO\Catalogo\GeneroDTO;
use App\Http\Resources\Catalogo\Genero\GeneroResource;
use App\Services\Catalogo\Interfaces\IGeneroService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class GeneroController extends Controller
{

    public function __construct(protected IGeneroService $generoService) {}






    public function criar(Request $request): JsonResponse
    {

        $dto = GeneroDTO::fromRequest(request: $request, validarNovo: true);

        $genero = $this->generoService->criar($dto);

        return Response()->json(['mensagem' => 'cadastrado com sucesso!', 'genero' => GeneroResource::criar($genero)], status: 200);
    }


    public function remover(Request $request): JsonResponse
    {


        $this->generoService->remover(id: $request->id);

        return Response()->json(['mensagem' => 'Removido com sucesso!'], status: 200);
    }

    public function editar(Request $request): JsonResponse
    {


        $dto = GeneroDTO::fromRequest(request: $request, validarNovo: false);
        $genero = $this->generoService->editar($dto);

        return response()->json(['mensagem' => 'genero atualizado com sucesso!', 'genero' => GeneroResource::criar($genero)], status: 200);
    }
}
