<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Locadoras;
use App\Models\VouchersLocadoras;
use App\Models\VeiculosHistoricos;
use App\Models\Modelos;
use App\Models\Status;
use App\Models\ChecklistVeiculos;
use App\Models\OcorrenciasVeiculos;
use App\Models\DetalheVeiculo;

class Veiculos extends Model
{
    protected $table = 'Veiculos';

    protected $fillable = [
        "id",
        "id_ut_cc",
        "placa",
        "id_categoria",
        "id_tracao",
        "id_especie",
        "combustivel",
        "ano_modelo", 
        "id_modelo",
        "id_grupo",
        "proprio_alugado",
        "renavam",
        "meio_pagamento", 
        "nome_meio_pagamento",
        "numero_meio_pagamento", 
        "limite_meio_pagamento", 
        "fornecedor_meio_pagamento",
        "vencimento_meio_pagamento",
        "cartao_combustivel", 
        "nome_cartao_combustivel", 
        "numero_cartao_combustivel", 
        "limite_cartao_combustivel", 
        "fornecedor_cartao_combustivel", 
        "vencimento_cartao_combustivel", 
        "rastreador",
        "nome_rastreador", 
        "numero_rastreador", 
        "fornecedor_rastreador",
        "vencimento_rastreador", 
        "seguro",
        "cobertura_seguro", 
        "numero_apolice", 
        "seguradora",
        "vencimento_seguro",
        "mes_licenciamento",
        "id_contrato_locadora",
        "id_locadora",
        "status"
    ];

    public $timestamps = false;

    public function detalhe()
    {
        return $this->hasMany(DetalheVeiculo::class, 'veiculo_id', 'id'); 
    }

    public function ocorrencias()
    {
        return $this->hasMany(OcorrenciasVeiculos::class, 'id_veiculo', 'id'); 
    }
   
    public function checklists()
    {
        return $this->hasMany(ChecklistVeiculos::class, 'id_veiculo', 'id'); 
    } 
    
    public function modelo()
    {
        return $this->hasOne(Modelos::class, 'id', 'id_modelo'); 
    }

    public function voucher()
    {
        return $this->hasOne(VouchersLocadoras::class, 'id', 'id_contrato_locadora');
    }

    public function locadora()
    {
        return $this->hasOne(Locadoras::class, 'id', 'id_locadora');
    }

    public function categoria()
    {
        return $this->hasOne(CategoriaVeiculos::class, 'id', 'id_categoria');
    }

    public function ut()
    {
        return $this->hasOne(UT::class, 'id', 'id_ut_cc');
    }
}