<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Http\DTO\Catalogo\PlataformaDTO;
use Illuminate\Http\JsonResponse;

class PlataformaController extends Controller
{

    public function novo(): View
    {


        return View(view: 'catalogo.plataformas');
    }


    public function criar(Request $request): JsonResponse{

        $dto = PlataformaDTO::fromRequest(request: $request, bool_validar_novo: true);
        




    }
}
