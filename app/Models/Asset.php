<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'assets';

    protected $primaryKey = 'serial_number';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'serial_number',
        'name',
        'id_user',
        'type',
        'series',
        'gambar',
        'tgl_beli',
        'last_service',
    ];

    public function reports()
    {
        return $this->hasMany(Report::class, 'aset', 'serial_number');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
