<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OcorrenciasVeiculos extends Model
{
    protected $table = 'Ocorrencias_Veiculos';

    protected $fillable = [
        'id_veiculo', 'tipo_ocorrencia'
    ];

    protected $visible =  ['id', 'id_veiculo', 'tipo_ocorrencia'];

    public $timestamps = false;

    public function ocorrencia()
    {
        return $this->hasOne(Ocorrencias::class, 'id_ocorrenciaVeiculos','id');
    }

    public function veiculo()
    {
        return $this->hasOne(Veiculos::class, 'id','id_veiculo');
    }
}
