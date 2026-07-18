<?php

namespace App\Http\Controllers\Gerenciador;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Catalogo\Interfaces\IDesenvolvedoraService;
use App\Services\Catalogo\Interfaces\IPlataformaService;
use App\Services\Catalogo\PlataformaService;

class DashboardController extends Controller
{

    public function __construct(protected IPlataformaService $plataforma_service, protected IDesenvolvedoraService $desenvolvedoraService) {}



    public function visualizar(): View
    {
        $totalDesenvolvedoras = $this->desenvolvedoraService->contarTodas();
        $totalPlataformas = $this->plataforma_service->contarTodas();

        return view(view: 'gerenciador.dashboard', data: compact('totalPlataformas', 'totalDesenvolvedoras'));
    }
}
