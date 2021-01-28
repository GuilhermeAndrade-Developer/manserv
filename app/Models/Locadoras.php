<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locadoras extends Model
{
    protected $table = 'Locadoras';

    protected $fillable = [
        'cnpj', 'razao_social', 'nome_fantasia', 'cidade', 'estado'
    ];

    protected $hidden = ['id'];

    public $timestamps = false;
}
