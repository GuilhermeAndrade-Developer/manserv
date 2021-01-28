<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentacaoVeiculos extends Model
{
    protected $table = 'Documentacao_Veiculos';

    protected $fillable = [
        'id_veiculo', 'renavam', 'chassis', 'categoria'
    ];

    protected $hidden = ['id'];

    public $timestamps = false;
}
