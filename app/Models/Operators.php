<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Operators extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'nama_petugas',
        'no_hp'
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                do {
                    $generate = 'OPR' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
                } while (self::where('id', $generate)->exists());
                $model->id = $generate;
            }
        });
    }
}
