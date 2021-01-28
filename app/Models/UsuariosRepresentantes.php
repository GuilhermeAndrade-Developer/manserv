<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuariosRepresentantes extends Model
{
    protected $table = 'Usuario_Representante';

    protected $fillable = [
        'id_usuario', 'id_ut_permitida','id_responsavel', 'status'
    ];

    protected $hidden = [];

    public $timestamps = false;

    public function usuario() {
        return $this->hasOne(Usuarios::class, 'id', 'id_usuario');
    }

    public function ut() {
        return $this->hasOne(UT::class, 'id', 'id_ut_permitida');
    }
}
