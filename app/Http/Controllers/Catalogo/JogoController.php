<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\DTO\Catalogo\JogoDTO;
use App\Http\Resources\Catalogo\Jogo\JogoResource;
use App\Services\Catalogo\Interfaces\IDesenvolvedoraService;
use App\Services\Catalogo\Interfaces\IGeneroService;
use App\Services\Catalogo\Interfaces\IJogoService;
use App\Services\Catalogo\Interfaces\IPlataformaService;
use App\Services\Colecao\Interfaces\IColecaoService;
use App\Services\Colecao\Interfaces\ISituacaoService;
use App\Services\Review\Interfaces\IReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;




class JogoController extends Controller
{

    public function __construct(
        protected IJogoService $jogoService,
        protected IPlataformaService $plataformaService,
        protected IGeneroService $generoService,
        protected IDesenvolvedoraService $desenvolvedoraservice,
        protected ISituacaoService $situacaoService,
        protected IColecaoService $colecao,
        protected IReviewService $reviewService
    ) {}



    public function novo(): View
    {
        $plataformas = $this->plataformaService->buscarTodas();
        $generos = $this->generoService->buscarTodos();
        $desenvolvedoras = $this->desenvolvedoraservice->buscarTodas();
        $jogos = $this->jogoService->buscarTodos();


        return view(view: 'catalogo.jogos.jogos', data: compact('plataformas', 'generos', 'desenvolvedoras', 'jogos'));
    }


    public function criar(Request $request): JsonResponse
    {

        $dto = JogoDTO::fromRequest($request, true);
        $jogo = $this->jogoService->criar($dto);

        return response()->json(['mensagem' => 'Jogo Cadastrado com sucesso!', 'jogo' => JogoResource::criar($jogo)], status: 200);
    }


    public function remover(Request $request): JsonResponse
    {

        $this->jogoService->remover($request->id);

        return response()->json(['mensagem' => 'Jogo removido com sucesso!'], status: 200);
    }

    public function editar(Request $request): JsonResponse
    {

        $dto = JogoDTO::fromRequest($request, validarNovo: false);

        $jogo = $this->jogoService->editar($dto);

        return response()->json(['mensagem' => 'Jogo atualizado com sucesso!', 'jogo' => JogoResource::criar($jogo)], status: 200);
    }

    public function buscar(Request $request): JsonResponse
    {

        $jogos = $this->jogoService->buscarPorNome($request->nome);
        return response()->json(['jogos' => JogoResource::criar($jogos)], status: 200);
    }

    public function visualizar(Request $request): View
    {

        $reviewUsuario = auth()->check() ? $this->reviewService->buscarReviewUsuario($request->id, auth()->id()) : null;
        $reviews =  $this->reviewService->buscarReviews($request->id);
        $situacao =  auth()->check() ? $this->colecao->buscarSituacao($request->id, auth()->id()) : null;
        $situacoes = $this->situacaoService->listarSituacoes();
        $jogo = $this->jogoService->buscarPorId($request->id);

        return View('catalogo.jogos.visualizar', compact('jogo', 'situacoes', 'situacao', 'reviewUsuario', 'reviews'));
    }

    public function buscaSimples(Request $request): JsonResponse
    {

        $jogos = $this->jogoService->buscaPorNomeSimplificado($request->nome);

        return response()->json(['jogos' => JogoResource::criar($jogos)], status: 200);
    }

    public function catalogo(): View
    {

        $generos = $this->generoService->todosComJogos();

        return View('catalogo.jogos.catalogo', compact('generos'));
    }
}
