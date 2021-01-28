<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Condutores extends Model
{
    protected $table = 'Condutores';

    protected $fillable = [
        'id_usuario', 'cnh', 'categoria_cnh', 'data_vencimento_cnh', 'status', 'rg',
    ];

    protected $hidden = [ '' ];

    public $timestamps = false;

    public function usuario() {
        return $this->hasOne(Usuarios::class, 'id', 'id_usuario');
    }

    public function ut() {
        return $this->hasOne(UT::class, 'id', 'id_ut_permitida');
    }

    public function getCnhAttribute($value)
    {
        return $this->attributes['cnh'] = mb_substr($value, 0, 3) . '********';
    }
}