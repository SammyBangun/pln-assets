<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Model;

class ReportFollowUp extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_penugasan',
        'jenis_gangguan',
        'id_detail_gangguan',
        'catatan_tambahan'
    ];
}
