<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Condutores;

class Pendencias extends Model
{
    protected $table = 'Pendencias';

    protected $fillable = [
        'tipo', 'condutor_id', 'abertura', 'fechamento', 'status'
    ];

    public $timestamps = false;

    public function condutor()
    {
        return $this->hasOne(Condutores::class, 'id', 'condutor_id');
    }
}
