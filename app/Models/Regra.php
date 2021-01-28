<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regra extends Model
{
    protected $table = "Regra";

    protected $fillable = [
        'nome', 'descricao', 'secao'
    ];

    protected $hidden = [];

    public $timestamps = false;

    public function scopeProfilesAvailable($query, $id)
    {
        $regra = Regra::find($id);
        $permissoes = Permissao::where('regra', $regra->id)->get(['perfil'])->toArray();

        $perfis = Perfil::whereNotIn('id', $permissoes)
            ->get();

        return $perfis;
    }
}
