<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UT;
use App\Models\Veiculos;
use App\Models\Condutores;

class Checklists extends Model
{
    protected $table = 'Checklists';

    protected $fillable = [
        'placa', 'kilometragem', 'data_chklist', 'tipo_chklist', 'id_condutor', 'checklist','observacao', 'id_ut_cc', 'id_veiculo'
    ];

    protected $hidden = [];

    public $timestamps = false;

    public function veiculo() {
        return $this->hasOne(Veiculos::class, 'placa', 'placa');
    }

    public function condutor()
    {
        return $this->hasOne(Condutores::class, 'id', 'id_condutor');
    }

    public function ut()
    {
        return $this->hasOne(UT::class, 'id', 'id_ut_cc');
    }

    public function imagens()
    {
        return $this->hasOne(ChecklistImages::class, 'id_checklist', 'id');
    }

    public function itens()
    {
        return $this->hasOne(ChecklistItens::class, 'id_checklist', 'id');
    }
}
