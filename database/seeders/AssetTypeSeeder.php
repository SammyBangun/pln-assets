<?php

namespace Database\Seeders;

use App\Models\Assets\AssetType;
use Illuminate\Database\Seeder;

class AssetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $asset_types = [
            "Access Point",
            "Audio",
            "Hub",
            "Kamera",
            "Keyboard",
            "Laptop",
            "Monitor",
            "Mouse",
            "PC",
            "Printer",
            "Proyektor",
            "Router",
            "Switch",
            "TV",
            "Lainnya"
        ];

        foreach ($asset_types as $name) {
            AssetType::create(['tipe' => $name]);
        }
    }
}
