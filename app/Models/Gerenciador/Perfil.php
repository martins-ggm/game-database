<?php

namespace App\Models\Gerenciador;

use App\Models\Gerenciador\Usuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perfil extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'perfil';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'str_name',
    ];

    /**
     * Permissões associadas ao perfil.
     */
    public function permissoes(): BelongsToMany
    {
        return $this->belongsToMany(Permissao::class, 'perfil_permissao', 'perfil_id', 'permissao_id')
            ->withTimestamps();
    }

    /**
     * Usuários vinculados ao perfil.
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'perfil_id');
    }
}