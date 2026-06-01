<?php



declare(strict_types=1);


namespace App\Repositorios\Gerenciador;

use App\models\Gerenciador\Usuario;
use App\Repositorios\Gerenciador\Interfaces\IUsuarioRepositorio;


class UsuarioRepositorio implements IUsuarioRepositorio
{



    public function __construct(protected Usuario $modelo) {}


    public function criarNovo(Usuario $usuario): Usuario
    {


        throw_if(
            $this->modelo->newQuery()->where('email', $usuario->email)->exists(),
            new \Exception('E-mail já cadastrado')
        );


        $usuario->save();

        return $usuario;
    }




    public function buscarPorId(int $id): ?Usuario
    {
        return $this->modelo->newQuery()->with('perfil')->find($id);
    }
}
