<?php

namespace App\Http\Controllers\Gerenciador;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Catalogo\Interfaces\IPlataformaService;

class AdminController extends Controller
{

    public function __construct(private IPlataformaService $plataforma_service) {}


    public function visualizar(): View
    {

        $totalPlataformas = $this->plataforma_service->contarTodas();

        return view(view: 'gerenciador.admin', data: compact('totalPlataformas'));
    }
}
