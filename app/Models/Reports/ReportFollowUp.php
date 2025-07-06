<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Model;
use App\Models\Disruptions\Disruption;
use App\Models\Disruptions\DetailDisruption;


class ReportFollowUp extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_penugasan',
        'jenis_gangguan',
        'id_detail_gangguan',
        'hal_lain'
    ];

    public function reportAssignment()
    {
        return $this->belongsTo(ReportAssignment::class, 'id_penugasan', 'id');
    }

    public function disruption()
    {
        return $this->belongsTo(Disruption::class, 'jenis_gangguan', 'id');
    }

    public function detailDisruption()
    {
        return $this->belongsTo(DetailDisruption::class, 'id_detail_gangguan', 'id');
    }
}
