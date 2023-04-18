<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RequisicaoVeiculos;
use App\Models\Condutores;

class DetalhesRequisicaoVeiculos extends Model
{
    protected $table = 'Detalhes_Requisicao_Veiculos';

    protected $fillable = [
        'id_requisicao',  'item', 'categoria_veiculo', 'id_condutor', 'data_retirada', 'prazo_locacao', 'data_devolucao',
        'cidade_retirada', 'estado_retirada', 'local_rodagem', 'km_mensal', 'limite_cartao_combustivel', 'id_voucher',
        'status_veiculo'
    ];

    protected $hidden = [];

    public $timestamps = false;

public function requisicao()
    {
        return $this->HasOne(RequisicaoVeiculos::class, 'id', 'id_requisicao'); 
    }

    public function condutor()
    {
        return $this->HasOne(Condutores::class, 'id_usuario', 'id_condutor');
    }

    public function cidade_ret() {
        return $this->HasOne(Cidades::class, 'id', 'cidade_retirada');
    }

    public function voucher()
    {
        return $this->belongsTo(VouchersLocadoras::class, 'id_voucher', 'id');
    }

    public function getCategoriaVeiculoAttribute($value)
    {
        

        switch ($this->attributes['categoria_veiculo']) {
            case 1:
                return $categoria = ['id' => $this->attributes['categoria_veiculo'], 'descricao' => 'Desginado - Diretor Geral'];
                break;
            case 2:
                return $categoria = ['id' => $this->attributes['categoria_veiculo'], 'descricao' => 'Desginado - Demais Diretores'];
                break;
            case 3:
                return $categoria = ['id' => $this->attributes['categoria_veiculo'], 'descricao' => 'Designado - Gerente'];          
                break;
            case 4:
                return $categoria = ['id' => $this->attributes['categoria_veiculo'], 'descricao' => 'Transporte de Pessoas - (Ex.: Uno)'];
                break;
            case 5:
                return $categoria = ['id' => $this->attributes['categoria_veiculo'], 'descricao' => 'Transporte de Cargas sem Baú - (Ex.: Strada)'];        
            case 6:
                return $categoria = ['id' => $this->attributes['categoria_veiculo'], 'descricao' => 'Transporte de Cargas com Baú - (Ex.: Fiorino)'];
                break;
            case 7:
                return $categoria = ['id' => $this->attributes['categoria_veiculo'], 'descricao' => 'Operacional - Especificar nas Observações'];
                break;
        }
    }

 
}