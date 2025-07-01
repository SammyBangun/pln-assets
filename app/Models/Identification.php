<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Reports\Report;
use App\Models\Reports\ReportIdentification;

class Identification extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'identifikasi_masalah',
    ];

    public function reports()
    {
        return $this->belongsToMany(
            Report::class,
            'report_identifications',
            'identifikasi_masalah',
            'report_id'
        );
    }
}
