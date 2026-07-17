<?php

namespace App\Http\Controllers\Gerenciador;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Catalogo\Interfaces\IPlataformaService;
use App\Services\Catalogo\PlataformaService;

class DashboardController extends Controller
{

    public function __construct(protected IPlataformaService $plataforma_service) {}


    public function visualizar(): View
    {
        $totalPlataformas = $this->plataforma_service->contarTodas();

        return view(view: 'gerenciador.dashboard', data: compact('totalPlataformas'));
    }
}
