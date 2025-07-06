<?php

namespace Database\Seeders;

use App\Models\Deliverable;
use Illuminate\Database\Seeder;

class DeliverableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deliverables = [
            '100% Pekerjaan Telah Selesai',
            'Selesai Dengan Catatan (Tuliskan Catatan)',
            'Tidak Dapat Dilaksanakan (Tuliskan Catatan)',
        ];

        foreach ($deliverables as $name) {
            Deliverable::create(['realisasi_hasil' => $name]);
        }
    }
}
