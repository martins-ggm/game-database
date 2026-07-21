<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\DTO\Catalogo\JogoDTO;
use App\Http\Resources\Catalogo\Jogo\JogoResource;
use App\Repositorios\Catalogo\Interfaces\IDesenvolvedoraRepositorio;
use App\Repositorios\Catalogo\Interfaces\IGeneroRepositorio;
use App\Services\Catalogo\Interfaces\IDesenvolvedoraService;
use App\Services\Catalogo\Interfaces\IGeneroService;
use App\Services\Catalogo\Interfaces\IJogoService;
use App\Services\Catalogo\Interfaces\IPlataformaService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;




class JogoController extends Controller
{

    public function __construct(
        protected IJogoService $jogoService,
        protected IPlataformaService $plataformaService,
        protected IGeneroService $igeneroService,
        protected IDesenvolvedoraService $idesenvolvedoraservice
    ) {}



    public function novo(): View
    {





        return view(view: 'catalogo.jogos');
    }


    public function criar(Request $request): JsonResponse
    {

        $dto = JogoDTO::fromRequest($request, true);
        $jogo = $this->jogoService->criar($dto);

        return response()->json(['mensagem' => 'Jogo Cadastrado com sucesso!', 'jogo' => JogoResource::criar($jogo)], status: 200);
    }
}
