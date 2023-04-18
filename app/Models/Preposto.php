<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preposto extends Model
{
    protected $table = 'Preposto';

    protected $fillable = [
        'id', 'cpf', 'nome', 'celular', 'email', 'status', 'data_cadastro', 'data_final',
    ];

    protected $hidden = [ '' ];

    public $timestamps = false;

    public function usuario() {
        return $this->hasOne(Usuarios::class, 'id', 'id_usuario');
    }

    public function locadora() {
        return $this->hasOne(Locadoras::class, 'cnpj', 'id');
    }
}