<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = 'Perfil';

    protected $fillable = [
        'nome'
    ];

    protected $hidden = [];

    public $timestamps = false;

    public function scopeRulesAvailable($query, $id)
    {
        $perfil = $query->where('id', $id)->first();

        $permissoes = Permissao::where('perfil', $perfil->id)->get(['regra'])->toArray();

        $regras = Regra::whereNotIn('id', $permissoes)
            ->get();

        return $regras;
    }

    public function permissions()
    {
        return $this->hasMany(Permissao::class, "perfil", "id");
    }
}
