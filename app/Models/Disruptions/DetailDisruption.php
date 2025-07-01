<?php

namespace App\Models\Disruptions;

use Illuminate\Database\Eloquent\Model;

class DetailDisruption extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'jenis_gangguan',
        'detail',
        'hal_lain',
        'keterangan',
    ];
}
