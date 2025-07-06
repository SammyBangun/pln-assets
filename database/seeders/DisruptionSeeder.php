<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Disruptions\Disruption;

class DisruptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disruptions = [
            "Gangguan Karena Hardware",
            "Gangguan Karena Software",
            "Gangguan Karena Jaringan, LAN, Internet, Intranet, dan WIFI",
        ];

        foreach ($disruptions as $name) {
            Disruption::create(['nama_gangguan' => $name]);
        }
    }
}
