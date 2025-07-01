<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deliverable extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'realisasi_hasil',
        'catatan'
    ];
}
