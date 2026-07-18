<?php


namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\DTO\Catalogo\DesenvolvedoraDTO;
use App\Http\Resources\Catalogo\Desenvolvedora\DesenvolvedoraResource;
use App\Services\Catalogo\Interfaces\IDesenvolvedoraService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DesenvolvedoraController extends Controller
{

    public function __construct(protected IDesenvolvedoraService $desenvolvedoraService) {}



    public function novo(): View
    {

        $desenvolvedoras = $this->desenvolvedoraService->buscarTodas();

        return View('catalogo.desenvolvedoras', data: compact('desenvolvedoras'));
    }

    public function criar(Request $request): JsonResponse
    {

        $dto = DesenvolvedoraDTO::fromRequest(request: $request, validarNovo: true);

        $desenvolvedora = $this->desenvolvedoraService->criar(dados: $dto);

        return response()->json(data: ['mensagem' => 'Salvo com sucesso.', 'desenvolvedora' => DesenvolvedoraResource::criar($desenvolvedora)], status: 200);
    }


    public function remover(Request $request): JsonResponse
    {

        $this->desenvolvedoraService->remover(id: $request->id);

        return response()->json(['mensagem' => 'Removido com sucesso!'], status: 200);
    }
}
