<?php


namespace App\Http\Controllers\Catalogo;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DesenvolvedoraController extends Controller
{



    public function novo(): View
    {
        return View('catalogo.desenvolvedoras');
    }
}
