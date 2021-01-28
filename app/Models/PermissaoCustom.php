<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissaoCustom extends Model
{
    protected $table = 'PermissaoCustom';

    protected $fillable = ['usuario_id', 'regra', 'acesso'];

    protected $hidden = [];

    public $timestamps = false;

    public function regras()
    {
        return $this->hasOne(Regra::class, 'id', 'regra');
    }

    public function usuario()
    {
        return $this->hasOne(Usuarios::class, 'id', 'usuario_id');
    }
}
