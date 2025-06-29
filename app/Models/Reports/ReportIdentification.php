<?php

namespace App\Models\Reports\Report;

use Illuminate\Database\Eloquent\Model;
use App\Models\Identification;
use App\Models\Reports\Report;

class ReportIdentification extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'id_pelaporan',
        'identifikasi_masalah'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'id_pelaporan');
    }

    public function identification()
    {
        return $this->belongsTo(Identification::class, 'identifikasi_masalah');
    }
}
