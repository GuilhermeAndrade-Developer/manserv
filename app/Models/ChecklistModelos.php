<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistModelos extends Model
{
    protected $table = 'Checklist_Modelos';

    protected $fillable = [
        'nome', 'descricao', 'data_cadastro', 'data_final', 'status'
    ];

    protected $hidden = [ 'id' ];

    public $timestamps = false;

    public function itens()
    {
        return $this->hasMany(ChecklistItens::class, 'id_modelo', 'id');
    }

}