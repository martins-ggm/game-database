<?php

namespace App\Models\Gerenciador;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{

    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'sg_usuarios';

    const DELETED_AT = 'removido_em';


    protected $fillable = [

        'nome',
        'email',
        'password',
        'perfil_id',
        'url_imagem_pequena',
        'url_imagem_grande'

    ];

    protected $hidden = [

        'password',
        'remember_token'

    ];

    protected $casts = [
        'password' => 'hashed',
        'admin' => 'boolean',
    ];



    public function perfil()
    {

        return $this->belongsTo(Perfil::class);
    }


    public static function criar(

        string $nome,
        string $email,
        string $password,
        ?int $perfil_id = null,
        ?string $imagemGrande = null,
        ?string $imagemPequena = null

    ): self {

        $usuario = new self();
        $usuario->nome = $nome;
        $usuario->email = $email;
        $usuario->password = $password;
        $usuario->perfil_id = $perfil_id;
        $usuario->url_imagem_pequena = $imagemPequena;
        $usuario->url_imagem_grande = $imagemGrande;

        return $usuario;
    }

    public function editar(String $nome): self
    {

        $this->nome = $nome;

        return $this;
    }
}
