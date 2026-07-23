<?php

namespace App\Http\Controllers\Gerenciador;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Catalogo\Interfaces\IDesenvolvedoraService;
use App\Services\Catalogo\Interfaces\IGeneroService;
use App\Services\Catalogo\Interfaces\IJogoService;
use App\Services\Catalogo\Interfaces\IPlataformaService;

class AdminController extends Controller
{

    public function __construct(
        protected IPlataformaService $plataforma_service,
        protected IGeneroService $generoService,
        protected IJogoService $jogoService,
        protected IDesenvolvedoraService $desenvolvedoraService
    ) {}


    public function visualizar(): View
    {
        $totalDesenvolvedoras = $this->desenvolvedoraService->contarTodas();
        $totalJogos = $this->jogoService->contarTodos();
        $totalGeneros = $this->generoService->contarTodos();
        $totalPlataformas = $this->plataforma_service->contarTodas();

        return view(view: 'gerenciador.admin', data: compact('totalPlataformas', 'totalDesenvolvedoras', 'totalJogos', 'totalGeneros'));
    }
}
