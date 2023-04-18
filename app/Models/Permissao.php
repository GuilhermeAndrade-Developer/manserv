<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permissao extends Model
{
    protected $table = 'Permissao';

    protected $fillable = ['regra', 'perfil', 'acesso'];

    protected $hidden = [];

    public $timestamps = false;

    public function regras()
    {
        return $this->hasOne(Regra::class, 'id', 'regra');
    }

    public function perfil()
    {
        return $this->hasOne(Perfil::class, 'id', 'perfil');
    }

}
