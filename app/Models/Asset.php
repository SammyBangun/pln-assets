<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reports\Report;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'assets';
    protected $primaryKey = 'serial_number';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'serial_number',
        'divisi',
        'nama',
        'tipe',
        'seri',
        'gambar',
        'tanggal_beli',
        'terakhir_servis',
        'lokasi',
        'status_aset'
    ];

    public function reports()
    {
        return $this->hasMany(Report::class, 'aset', 'serial_number');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'divisi');
    }
}
