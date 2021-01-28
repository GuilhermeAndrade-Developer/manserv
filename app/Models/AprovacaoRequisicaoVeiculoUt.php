<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AprovacaoRequisicaoVeiculoUt extends Model
{
    protected $table = 'Aprovacao_Requisicao_Veiculo_UT';

    protected $fillable = [
        'id_num_requisicao', 'data_aprovacao', 'id_ut_cc', 'tipo_gestor', 'id_gestor', 'observacao','status'
    ];

    protected $hidden = ['id'];

    public $timestamps = false;

    public function gestor() {
        return $this->hasOne(GestoresUt::class, 'id_gestor', 'id_gestor');
    }
    public function requisicao() {
        return $this->hasOne(RequisicaoVeiculos::class, 'id', 'id_num_requisicao');
    }
}
