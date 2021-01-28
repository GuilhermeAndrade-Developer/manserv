<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VouchersAnexos extends Model
{
    protected $table = 'Vouchers_Anexos';
    protected $fillable = [
        'id_voucher',
        'path',
        'arquivo'
    ];
    public $timestamps = false;
}