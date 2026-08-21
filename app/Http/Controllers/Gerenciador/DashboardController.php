<?php

namespace App\Http\Controllers\Gerenciador;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Catalogo\Interfaces\IEmpresaService;
use App\Services\Catalogo\Interfaces\IGeneroService;
use App\Services\Catalogo\Interfaces\IJogoService;
use App\Services\Catalogo\Interfaces\IPlataformaService;

class DashboardController extends Controller
{

    public function __construct(
        protected IPlataformaService $plataforma_service,
        protected IEmpresaService $empresaService,
        protected IGeneroService $generoService,
        protected IJogoService $jogoService
    ) {}



    public function visualizar(): View
    {

        $totalEmpresas = $this->empresaService->contarTodas();
        $totalPlataformas = $this->plataforma_service->contarTodas();
        $totalGeneros = $this->generoService->contarTodos();
        $totalJogos = $this->jogoService->contarTodos();
        $emAlta = $this->jogoService->emAlta(quantidade: 4, dias: 30);

        return view(view: 'gerenciador.dashboard', data: compact(
            'totalPlataformas',
            'totalEmpresas',
            'totalGeneros',
            'totalJogos',
            'emAlta'
        ));
    }
}
