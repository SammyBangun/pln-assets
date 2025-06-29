<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Division extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'nama_divisi',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                do {
                    $generate = 'DIV' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
                } while (self::where('id', $generate)->exists());
                $model->id = $generate;
            }
        });
    }
}
