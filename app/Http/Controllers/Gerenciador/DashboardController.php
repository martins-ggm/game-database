<?php

namespace App\Http\Controllers\Gerenciador;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Catalogo\Interfaces\IDesenvolvedoraService;
use App\Services\Catalogo\Interfaces\IGeneroService;
use App\Services\Catalogo\Interfaces\IPlataformaService;

class DashboardController extends Controller
{

    public function __construct(protected IPlataformaService $plataforma_service, 
    protected IDesenvolvedoraService $desenvolvedoraService,
    protected IGeneroService $generoService) {}



    public function visualizar(): View
    {
       
        $totalDesenvolvedoras = $this->desenvolvedoraService->contarTodas();
        $totalPlataformas = $this->plataforma_service->contarTodas();
         $totalGeneros = $this->generoService->contarTodos();

        return view(view: 'gerenciador.dashboard', data: compact('totalPlataformas', 'totalDesenvolvedoras', 'totalGeneros'));
    }
}
