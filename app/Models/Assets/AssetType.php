<?php

namespace App\Models\Assets;

use Illuminate\Database\Eloquent\Model;

class AssetType extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'tipe'
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'tipe', 'id');
    }
}
