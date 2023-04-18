<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisicaoVeiculos extends Model
{
    protected $table = 'Requisicao_Veiculos';

    protected $fillable = [
        'numero', 'id_ut_cc', 'previsto_fpv', 'id_requisitante', 'id_usuario_abertura', 'data_requisicao','status',
        'tipo','id_relacionada','observacao'
    ];

    protected $hidden = [];

    public $timestamps = false;

    public function usuario()
    {
        return $this->hasOne(Usuarios::class, 'id', 'id_requisitante');
    }

    public function voucher()
    {
        return $this->HasOne(VouchersLocadoras::class, 'id_numero_requisicao', 'id'); 
    }

    public function detalhesVeiculos()
    {
        return $this->hasMany(DetalhesRequisicaoVeiculos::class, 'id_requisicao', 'id');
    }

    public function ut()
    {
        return $this->hasOne(UT::class, 'id', 'id_ut_cc');
    }

    public function aprovacaoRequisicao()
    {
        return $this->hasMany(AprovacaoRequisicaoVeiculoUt::class, 'id_num_requisicao', 'id');
    }

    public function gestores() {
        return $this->hasMany(GestoresUt::class, 'id_ut_cc', 'id_ut_cc');
    }

    public function status($query, $status)
    {
        return $this->attributes['status'] = tipoGestorStatus($this->getAttribute('status'));
    }

    public function getPrevistoFpvAttribute($value)
    {
        return $this->attributes['previsto_fpv'] = ($value) == 'S' ? 'Sim' : 'Não';
    }
}
