<?php

namespace Database\Seeders;

use App\Models\Identification;
use Illuminate\Database\Seeder;

class IdentificationSeeder extends Seeder
{
    public function run(): void
    {
        $identifications = [
            'Gangguan Hardware',
            'Gangguan Software',
            'Gangguan Virus',
            'Gangguan Sistem Operasi',
            'Gangguan Jaringan LAN',
            'Gangguan Jaringan WIFI',
            'Gangguan Jaringan Internet',
            'Gangguan Jaringan Intranet',
        ];

        foreach ($identifications as $name) {
            Identification::create(['identifikasi_masalah' => $name]);
        }
    }
}
