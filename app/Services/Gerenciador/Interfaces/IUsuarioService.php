<?php

namespace App\Services\Gerenciador\Interfaces;

use App\Http\DTO\Gerenciador\UsuarioDTO;
use App\Http\DTO\Gerenciador\UsuarioLoginDTO;
use App\Models\Gerenciador\Usuario;



interface IUsuarioService
{


    public function criar(UsuarioDTO $dados): Usuario;

    public function autenticar(UsuarioLoginDTO $dados): Usuario;
}




