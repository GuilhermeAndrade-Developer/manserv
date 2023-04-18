<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UT extends Model
{
    protected $table = 'UT_CC';

    protected $fillable = [
        'numero_ut', 'descricao', 'numero_coligada', 'cidade', 'status', 'ano_mes_inicio', 'ano_mes_fim', 'negocio_bu',
        'regional', 'tipo_despesa', 'regiao', 'segmento', 'grupo_cliente',
        'diretor_presidente', 'diretor_presidente_cpf', 'diretor_vpresidente', 'diretor_vpresidente_cpf',
        'diretor', 'diretor_cpf', 'gerente', 'gerente_cpf', 'responsavel', 'responsavel_cpf', 'admin', 'admin_cpf'
    ];

    protected $hidden = [];

    public $timestamps = false;

    public function usuarios() {
        return $this->hasMany(Usuarios::class, 'id_ut_cc', 'id');
    }

    public function usuariosRepresentantes() {
        return $this->hasMany(UsuariosRepresentantes::class, 'id_ut_permitida', 'id');
    }

    public function gestores()
    {
        return $this->hasMany(GestoresUt::class, 'id_ut_cc', 'id');
    }

    public function gestor() {
        return $this->belongsToMany(Usuarios::class, 'Gestores_UT');
    }
}