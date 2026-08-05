<?php

namespace App\Http\Controllers\Gerenciador;

use App\Http\Controllers\Controller;
use App\Services\Gerenciador\Interfaces\IPatchNoteService;
use Illuminate\View\View;

class HomeController extends Controller
{

    public function __construct(protected IPatchNoteService $patchNoteService) {}


    public function index(): View
    {
        $patchNotes = $this->patchNoteService->listar();

        return view('home', compact('patchNotes'));
    }
}
