<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalheVeiculo extends Model
{
    protected $table = 'Detalhes_Veiculo';

    protected $fillable = ['veiculo_id', 'condutor_id', 'tipo', 'valor_inicial', 'valor_final', 'cadastro','foto','atualizado'];

    protected $visible =  ['id','veiculo_id', 'condutor_id', 'tipo', 'valor_inicial', 'valor_final', 'cadastro','foto', 'atualizado'];

    public $timestamps = false;


public function veiculo(){
    return $this->hasOne(Veiculos::class, 'id', 'veiculo_id'); 
}

}