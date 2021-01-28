<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaVeiculos extends Model
{
    protected $table = 'Categoria_Veiculos';

    protected $fillable = [
         'id', 'nome'
    ];

    protected $hidden = [ '' ];

    public $timestamps = false;
   
}
