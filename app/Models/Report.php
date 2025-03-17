<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['aset', 'user_pelapor', 'laporan_kerusakan', 'deskripsi', 'gambar'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_pelapor');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'aset', 'serial_number');
    }
}
