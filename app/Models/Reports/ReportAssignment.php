<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Operator;

class ReportAssignment extends Model
{
    public $timestamps = false;

    public $keyType = 'string';

    protected $fillable = [
        'report_id',
        'petugas',
        'realisasi',
        'catatan',
        'gambar_tindak_lanjut',
        'tanggal_penugasan',
        'lokasi',
        'status',
        'keterangan_status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                do {
                    $generate = 'ASSIGN' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
                } while (self::where('id', $generate)->exists());
                $model->id = $generate;
            }
        });
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id', 'id');
    }


    public function petugas()
    {
        return $this->belongsTo(Operator::class, 'petugas', 'id');
    }

    public function followUp()
    {
        return $this->hasMany(ReportFollowUp::class, 'id_penugasan', 'id');
    }
}
