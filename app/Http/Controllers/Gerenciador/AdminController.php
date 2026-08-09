<?php

namespace App\Http\Controllers\Gerenciador;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Http\Resources\Gerenciador\AuditoriaResource;
use App\Services\Catalogo\Interfaces\IDesenvolvedoraService;
use App\Services\Catalogo\Interfaces\IGeneroService;
use App\Services\Catalogo\Interfaces\IJogoService;
use App\Services\Catalogo\Interfaces\IPlataformaService;
use App\Services\Gerenciador\Interfaces\IAuditoriaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function __construct(
        protected IPlataformaService $plataforma_service,
        protected IGeneroService $generoService,
        protected IJogoService $jogoService,
        protected IDesenvolvedoraService $desenvolvedoraService,
        protected IAuditoriaService $auditoriaService
    ) {}


    public function visualizar(): View
    {
        $totalDesenvolvedoras = $this->desenvolvedoraService->contarTodas();
        $totalJogos = $this->jogoService->contarTodos();
        $totalGeneros = $this->generoService->contarTodos();
        $totalPlataformas = $this->plataforma_service->contarTodas();
        $atividadeRecente = $this->auditoriaService->atividadeRecente(quantidade: 5);

        return view(view: 'gerenciador.admin', data: compact(
            'totalPlataformas',
            'totalDesenvolvedoras',
            'totalJogos',
            'totalGeneros',
            'atividadeRecente'
        ));
    }


    public function auditoria(Request $request): View | JsonResponse
    {

        $auditorias = $this->auditoriaService->trazerTodas();

        if ($request->wantsJson()) {
            return response()->json(['auditorias' => AuditoriaResource::criar($auditorias)]);
        }


        return view('gerenciador.auditoria', compact('auditorias'));
    }
}
