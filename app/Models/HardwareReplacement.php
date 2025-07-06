<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Disruptions\DetailDisruption;

class HardwareReplacement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_gangguan_hardware',
        'detail_merek_hardware',
        'jumlah',
    ];

    public function detailDisruption()
    {
        return $this->belongsTo(DetailDisruption::class, 'id_gangguan_hardware', 'id');
    }
}
