<?php

namespace App\Models\Disruptions;

use Illuminate\Database\Eloquent\Model;

class Disruption extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'nama_gangguan',
    ];
}
