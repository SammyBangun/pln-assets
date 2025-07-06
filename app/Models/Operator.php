<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reports\ReportAssignment;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Operator extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'nama_petugas',
        'no_hp'
    ];

    public function assign()
    {
        return $this->hasMany(ReportAssignment::class);
    }
}
