<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistItens extends Model
{
    protected $table = 'Checklist_Itens';

    protected $fillable = [
         'id_modelo', 'descricao','respostas','data_cadastro','data_final','status'
    ];

    protected $hidden = [ 'id' ];

    public $timestamps = false;

    public function modelo()
    {
        return $this->hasOne(ChecklistModelos::class, 'id', 'id_modelo');
    }

}
