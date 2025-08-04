<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Disruptions\DetailDisruption;
use App\Models\Reports\ReportFollowUp;

class HardwareReplacement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_tindak_lanjut',
        'nama_komponen',
        'detail_merek_hardware',
        'jumlah',
    ];

    public function followUp()
    {
        return $this->belongsTo(ReportFollowUp::class, 'id_tindak_lanjut', 'id');
    }

    public function detailDisruption()
    {
        return $this->belongsTo(DetailDisruption::class, 'nama_komponen', 'id');
    }
}
