<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Reports\Report;
use App\Models\Reports\ReportIdentification;

class Identification extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'identifikasi_masalah',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                do {
                    $generate = 'IDENTIFY' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
                } while (self::where('id', $generate)->exists());
                $model->id = $generate;
            }
        });
    }

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
