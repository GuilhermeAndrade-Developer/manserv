<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistsImagens extends Model
{
    protected $table = 'Checklist_Imagens';

    protected $fillable = [
        'id_checklist', 'id_chklst_item', 'item', 'nome','path'
    ];

    protected $hidden = [ 'id' ];

    public $timestamps = false;

}
