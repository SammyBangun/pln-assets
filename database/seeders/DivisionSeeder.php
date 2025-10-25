<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            'Engineering',
            'Operasi',
            'Pemeliharaan',
            'Business Support',
            'K3',
            'Lingkungan'
        ];

        foreach ($divisions as $name) {
            Division::create(['nama_divisi' => $name]);
        }
    }
}
