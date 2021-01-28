<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marcas extends Model
{
    protected $table = 'Marcas';

    protected $fillable = ['nome_fabricante'];

    public $timestamps = false;
}