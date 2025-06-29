<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Asset;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'aset',
        'user_pelapor',
        'deskripsi',
        'gambar',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                do {
                    $generate = 'REP' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
                } while (self::where('id', $generate)->exists());
                $model->id = $generate;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_pelapor');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'aset', 'serial_number');
    }
}
