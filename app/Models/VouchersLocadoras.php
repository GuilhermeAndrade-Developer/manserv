<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DetalhesRequisicaoVeiculo;

class VouchersLocadoras extends Model
{
    protected $table = 'Vouchers_Locadoras';

    protected $fillable = [
        'numero',
        'locadora',
        'id_ut_cc',
        'data_retirada',
        'hora_retirada',
        'cidade_retirada',
        'endereco_retirada',
        'tel_loja_retirada',
        'email_loja_retirada',
        'ref_retirada',
        'data_devolucao',
        'hora_devolucao',
        'cidade_devolucao',
        'endereco_devolucao',
        'tel_loja_devolucao',
        'email_loja_devolucao',
        'ref_devolucao',
        'grupo_veiculo',
        'id_locadora',
        'duracao',
        'id_condutor',
        'estado_retirada',
        'estado_devolucao', 
        'data_cadastro'
    ];

    public function detalhes_requisicao() 
    {
        return $this->HasOne(DetalhesRequisicaoVeiculo::class, 'id_voucher', 'id');
    }

    public function veiculo()
    {
        return $this->HasOne(Veiculos::class, 'id_contrato_locadora', 'id');
    }

    public function cidade_ret() {
        return $this->HasOne(Cidades::class, 'id', 'cidade_retirada');
    }

    public function cidade_dev() {
        return $this->HasOne(Cidades::class, 'id', 'cidade_devolucao');
    }

    public $timestamps = false;
}