<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cidades extends Model
{
    protected $table = 'Cidades';

    protected $fillable = [
        'estado', 'uf', 'nome'
    ];

    protected $hidden = [];

    public $timestamps = false;
}
