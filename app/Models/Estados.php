<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estados extends Model
{
    protected $table = 'Estados';

    protected $fillable = [
        'uf', 'nome'
    ];

    protected $hidden = [];

    public $timestamps = false;

    public function cidades()
    {
        return $this->hasMany(Cidades::class, 'estado', 'id');
    }
}
