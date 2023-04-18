<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Termos extends Model
{
    protected $table = 'Termo_Responsabilidade';

    protected $fillable = [
        'Termo', 'data_criacao', 'status'
    ];

    protected $visible =  ['id','Termo', 'data_criacao', 'status'];
    
    public $timestamps = false;

    public function termosUsuarios()
    {
        return $this->hasOne(TermosUsuarios::class, 'termo_id', 'id'); 
    }

}