<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Model;
use App\Models\Identification;
use App\Models\Reports\Report;

class ReportIdentification extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'report_id',
        'identifikasi_masalah'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id', 'id');
    }

    public function identification()
    {
        return $this->belongsTo(Identification::class, 'identifikasi_masalah', 'id');
    }
}
