<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValorCombustivel extends Model
{
    protected $table = 'Valor_Combustivel';

    protected $fillable = [
        'categoria', 'descricao_categoria', 'capacidade', 'descricao_capacidade', 'local', 'descricao_local',
        'autonomia', 'km', 'descricao_km', 'tipo_combustivel', 'descricao_tipo_comb', 'valor', 'estado',
        'valido_de', 'valido_ate'
    ];

    public $timestamps = false;

    public function estado()
    {
        return $this->hasOne(Estados::class, 'id', 'estado');
    }
}
