<?php



namespace App\Http\Controllers\Colecao;

use App\Http\Controllers\Controller;
use App\Repositorios\Catalogo\Interfaces\IJogoRepositorio;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ColecaoController extends controller
{

    public function __construct() {}


    public function visualizar(Request $request): View {

        


        return View('colecao.visualizar');





    }






}
