<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VeiculosHistoricos extends Model
{
    protected $table = 'Historico_Veiculos';
    
    protected $fillable = [
        'id_veiculo', 'id_condutor', 'id_ut_cc', 'km', 'data_retirada', 'data_devolucao'
    ];

    public function condutor()
    {
        return $this->hasOne(Condutores::class, 'id', 'id_condutor');
    }

    public function ut()
    {
        return $this->hasOne(UT::class, 'id', 'id_ut_cc');
    }

}
