<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HardwareReplacement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_gangguan_hardware',
        'detail_merek_hardware',
        'jumlah',
    ];
}
