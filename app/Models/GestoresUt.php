<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GestoresUt extends Pivot
{
    protected $table = 'Gestores_UT';

    protected $fillable = [
        'data_inicio', 'tipo_gestor', 'id_gestor', 'id_ut_cc', 'data_fim'
    ];

    protected $hidden = [
        'id'
    ];

    public $timestamps = false;

    public function usuario()
    {
        return $this->hasOne(Usuarios::class, 'id', 'id_gestor');
    }

    public function gestorUt()
    {
        return $this->hasOne(Usuarios::class, 'id', 'id_gestor');
    }

    public function uts()
    {
        return $this->belongsTo(UT::class, 'UT_CC', 'id', 'id_ut_cc');
    }

    public function ut()
    {
        return $this->hasOne(UT::class, 'id', 'id_ut_cc');
    }

    public function gestor()
    {
        return $this->hasManyThrough(Usuarios::class, UT::class, 'numero_ut', 'ut_cc', 'id', 'id');
    }
}
