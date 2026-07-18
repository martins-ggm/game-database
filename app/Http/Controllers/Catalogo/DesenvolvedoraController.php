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

    public function __construct(protected IDesenvolvedoraService $desenvolderoraservice) {}



    public function novo(): View
    {
        return View('catalogo.desenvolvedoras');
    }

    public function criar(Request $request): JsonResponse
    {

        $dto = DesenvolvedoraDTO::fromRequest(request: $request, validarNovo: true);

        $desenvolvedora = $this->desenvolderoraservice->criar(dados: $dto);

        return response()->json(data: ['mensagem' => 'Salvo com sucesso.', 'Desenvolvedora' => DesenvolvedoraResource::criar($desenvolvedora)], status: 200);
    }
}
