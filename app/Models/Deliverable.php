<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reports\ReportAssignment;

class Deliverable extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'realisasi_hasil',
    ];

    public function reportAssignment()
    {
        return $this->hasOne(ReportAssignment::class, 'realisasi', 'id');
    }
}
