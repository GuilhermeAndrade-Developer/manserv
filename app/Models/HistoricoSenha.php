<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricoSenha extends Model
{
    protected $table = 'Historico_Senhas';

    protected $fillable = [
        'data_expiracao', 'senha', 'id_usuario'
    ];

    protected $hidden = ['id', 'id_usuario'];

    public $timestamps = false;
}
