<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendencia extends Model
{
    protected $table = "Checklists";

    protected $fillable = [
        'id_veiculo', 'data_checklist', 'tipo_checklist', 'id_condutor', 'atendido', 'aceite', 'observacao'
    ];

    public $timestamps = false;

    public function condutor() {
        return $this->hasOne(Condutores::class, 'id', 'id_condutor');
    }

//    public function usuario() {
//        return $this->hasOne(Usuarios::class, 'id', 'id_usuario');
//    }

//    public function scopeUsuario($query, $value) {
////        $usuario = Usuarios
//        return $query->where('id_condutor', $value);
//    }
}
