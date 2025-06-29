<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
                    $generate = 'ID' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
                } while (self::where('id', $generate)->exists());
                $model->id = $generate;
            }
        });
    }
}
