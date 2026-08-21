<?php


namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\DTO\Catalogo\EmpresaDTO;
use App\Http\Resources\Catalogo\Empresa\EmpresaResource;
use App\Services\Catalogo\Interfaces\IEmpresaService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EmpresaController extends Controller
{

    public function __construct(protected IEmpresaService $empresaService) {}



    public function novo(): View
    {

        $empresas = $this->empresaService->buscarTodas();

        return View('catalogo.empresas', data: compact('empresas'));
    }

    public function criar(Request $request): JsonResponse
    {

        $dto = EmpresaDTO::fromRequest(request: $request, validarNovo: true);

        $empresa = $this->empresaService->criar(dados: $dto);

        return response()->json(data: ['mensagem' => 'Salvo com sucesso.', 'empresa' => EmpresaResource::criar($empresa)], status: 200);
    }


    public function remover(Request $request): JsonResponse
    {

        $this->empresaService->remover(id: $request->id);

        return response()->json(['mensagem' => 'Removido com sucesso!'], status: 200);
    }

    public function Editar(Request $request): JsonResponse
    {

        $dto = EmpresaDTO::fromRequest($request, false);

        $empresa = $this->empresaService->editar($dto);

        return Response()->json(['mensagem' => 'Empresa atualizada com sucesso!', 'empresa' => EmpresaResource::criar($empresa)],  status: 200);
    }



    public function buscar(Request $request): JsonResponse
    {

        $empresas = $this->empresaService->buscar($request->nome);

        return response()->json(['empresas' => EmpresaResource::criar($empresas)], status: 200);
    }
}
