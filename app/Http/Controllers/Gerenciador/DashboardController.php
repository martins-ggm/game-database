<?php

namespace App\Http\Controllers\Gerenciador;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Catalogo\Interfaces\IDesenvolvedoraService;
use App\Services\Catalogo\Interfaces\IGeneroService;
use App\Services\Catalogo\Interfaces\IJogoService;
use App\Services\Catalogo\Interfaces\IPlataformaService;

class DashboardController extends Controller
{

    public function __construct(
        protected IPlataformaService $plataforma_service,
        protected IDesenvolvedoraService $desenvolvedoraService,
        protected IGeneroService $generoService,
        protected IJogoService $jogoService
    ) {}



    public function visualizar(): View
    {

        $totalDesenvolvedoras = $this->desenvolvedoraService->contarTodas();
        $totalPlataformas = $this->plataforma_service->contarTodas();
        $totalGeneros = $this->generoService->contarTodos();
        $totalJogos = $this->jogoService->contarTodos();

        return view(view: 'gerenciador.dashboard', data: compact('totalPlataformas', 'totalDesenvolvedoras', 'totalGeneros', 'totalJogos'));
    }
}
