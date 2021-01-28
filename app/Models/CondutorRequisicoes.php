<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CondutorRequisicoes extends Model
{
    protected $table = 'Condutor_Requisicoes';

    protected $fillable = [
        'id_requisitante', 'id_numero_requisicao', 'id_condutor_requisicao'
    ];

    protected $hidden = ['id'];

    public $timestamps = false;
}
