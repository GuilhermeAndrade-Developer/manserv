<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ocorrencias extends Model
{
    protected $table = 'Ocorrencias';

    protected $fillable = [
        'id_ocorrenciaVeiculos', 'data_cadastro', 'foto_link', 'descricao'
    ];

    protected $visible =  ['id','id_ocorrenciaVeiculos', 'data_cadastro', 'foto_link','descricao'];

    public $timestamps = false;

    public function ocorrenciaVeiculo()
    {
        return $this->hasOne(OcorrenciasVeiculos::class, 'id','id_ocorrenciaVeiculos');
    }
}
