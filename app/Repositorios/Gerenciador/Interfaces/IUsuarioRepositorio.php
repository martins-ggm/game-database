<?php

namespace App\Repositorios\Gerenciador\Interfaces;

use App\Models\Gerenciador\Usuario;



interface IUsuarioRepositorio
{



    public function criarNovo(Usuario $usuario): Usuario;



    public function buscarPorId(int $id): ?Usuario;
}
