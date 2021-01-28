<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelos extends Model
{
    protected $table = 'Modelos';

    protected $fillable = [
        'nome', 'id_marca', 'categoria'
    ];

    public $timestamps = false;


    public function marca()
    {
        return $this->hasOne(Marcas::class, 'id', 'id_marca');
    }
}
