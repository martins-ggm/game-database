<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;




class JogoController extends Controller
{


    public function novo(): View
    {


        return view(view: 'catalogo.jogos');
    }
}
