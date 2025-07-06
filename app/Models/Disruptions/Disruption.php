<?php

namespace App\Models\Disruptions;

use Illuminate\Database\Eloquent\Model;

class Disruption extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'nama_gangguan',
    ];

    public function details()
    {
        return $this->hasMany(DetailDisruption::class, 'jenis_gangguan', 'id');
    }
}
