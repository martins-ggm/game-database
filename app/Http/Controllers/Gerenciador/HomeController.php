<?php

namespace App\Http\Controllers\Gerenciador;

use App\Http\Controllers\Controller;
use App\Http\Resources\Gerenciador\PatchNote\PatchNoteResource;
use App\Services\Catalogo\Interfaces\IJogoService;
use App\Services\Gerenciador\Interfaces\IPatchNoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{

    public function __construct(
        protected IPatchNoteService $patchNoteService,
        protected IJogoService $jogoService
        
        ) {}


    public function index(Request $request): View|JsonResponse
    {

        
        $patchNotes = $this->patchNoteService->listar();

        $jogosEmDestaque = $this->jogoService->emAlta(quantidade: 4, dias: 15);

        if ($request->wantsJson()) {
            return response()->json(PatchNoteResource::criar($patchNotes));
        }

        return view('home', compact('patchNotes', 'jogosEmDestaque'));
    }
}
