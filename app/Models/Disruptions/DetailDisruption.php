<?php

namespace App\Models\Disruptions;

use App\Models\HardwareReplacement;
use App\Models\Reports\ReportFollowUp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DetailDisruption extends Model
{
    public $timestamps = false;

    public $keyType = 'string';

    protected $fillable = [
        'jenis_gangguan',
        'detail',
        'hal_lain',
        'keterangan',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Mapping prefix berdasarkan jenis gangguan
            $prefixMap = [
                1 => 'DIS-HARDWARE',
                2 => 'DIS-SOFTWARE',
                3 => 'DIS-NETWORK',
            ];

            // Ambil prefix dari jenis gangguan
            $prefix = $prefixMap[$model->jenis_gangguan] ?? 'DIS-UNKNOWN';

            // Generate ID unik dengan prefix
            do {
                $generate = $prefix . '-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4));
            } while (self::where('id', $generate)->exists());

            $model->id = $generate;
        });
    }

    public function disruption()
    {
        return $this->belongsTo(Disruption::class, 'jenis_gangguan', 'id');
    }

    public function hardwareReplace()
    {
        return $this->hasMany(HardwareReplacement::class, 'id_gangguan_hardware', 'id');
    }

    public function followUp()
    {
        return $this->hasMany(ReportFollowUp::class, 'id_gangguan', 'id');
    }
}
