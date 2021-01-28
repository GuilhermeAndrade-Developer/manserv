<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermosUsuarios extends Model
{
    protected $table = 'Aceite_Termo';

    protected $fillable = [
        'id_usuario', 'id_termo', 'data_aceite','status'
    ];

    protected $visible =  ['id','id_usuario', 'id_termo', 'data_aceite','status'];
    
    public $timestamps = false;

    public function termo()
    {
        return $this->hasOne(Termos::class, 'id_termo', 'id'); 
    }

}