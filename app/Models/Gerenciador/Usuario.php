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


        'name',
        'email',
        'password',
        'perfil_id',
        'str_url_foto_perfil',




    ];

    protected $hidden = [

        'password',
        'remember_token'
    ];

    protected $casts = ['password' => 'hashed'];



    public function perfil()
    {

        return $this->belongsTo(Perfil::class);
    }


    public static function criar(

        string $name,
        string $email,
        string $password,
        ?int $perfil_id = null,
        ?string $str_url_foto_perfil = null,



    ): self {

        $usuario = new self();
        $usuario->name = $name;
        $usuario->email = $email;
        $usuario->password = $password;
        $usuario->perfil_id = $perfil_id;
        $usuario->str_url_foto_perfil = $str_url_foto_perfil;

        return $usuario;
    }
}
