<?php



namespace App\Http\Controllers\Colecao;

use App\Http\Controllers\Controller;
use App\Services\Colecao\Interfaces\IColecaoService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ColecaoController extends controller
{

    public function __construct(protected IColecaoService $colecaoService) {}


    public function visualizar(Request $request): View
    {

        $colecao = $this->colecaoService->buscarColecao($request->id);

        return View('colecao.visualizar', compact('colecao'));
    }

    public function adicionarNaColecao(Request $request): JsonResponse
    {
        $this->colecaoService->adicionarNaColecao($request->jogoID, auth()->id(), $request->situacaoID);
        return response()->json(['mensagem' => 'Adicionado com sucesso!'], status: 200);
    }
}
