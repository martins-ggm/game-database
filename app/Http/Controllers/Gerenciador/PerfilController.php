<?php

namespace App\Http\Controllers\Gerenciador;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

class PerfilController extends Controller
{
    public function visualizar(): View
    {
        return view(view: 'gerenciador.perfil');
    }
}
