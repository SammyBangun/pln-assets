<?php

namespace App\Models\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reports\Report;
use App\Models\Assets\AssetType;
use App\Models\Division;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'assets';
    protected $primaryKey = 'serial_number';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'serial_number',
        'id_divisi',
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
        return $this->belongsTo(Division::class, 'id_divisi');
    }

    public function tipe()
    {
        return $this->belongsTo(AssetType::class, 'tipe', 'id');
    }
}
