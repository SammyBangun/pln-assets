<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Model;

class ReportAssignment extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'report_id',
        'petugas',
        'realisasi',
        'gambar_tindak_lanjut',
        'tanggal_penugasan',
        'status'
    ];
}
