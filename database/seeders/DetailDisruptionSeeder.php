<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Disruptions\DetailDisruption;

class DetailDisruptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $detail_disruptions = [
            // Gangguan Karena Hardware (id = 1)
            [
                'jenis_gangguan' => 1,
                'detail' => 'HDD SATA',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'SSD SATA',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Mouse USB',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Mouse Wireless',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'MOBO Combo',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Fan Processor',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Memory/RAM',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Power Supply',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Monitor',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Kabel HDMI',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Hubswitch',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'FO Hubswitch',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'WIFI Router',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'VGA Card',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Printer',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'WIFI Card',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'WIFI USB',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'LAN Card',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Radio Access Point',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Kabel Patch Cord',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Keyboard USB',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Keyboard Combo',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Kabel LAN UTP',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Kabel Fiber Optic',
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Konektor RJ45',
            ],

            // Gangguan Karena Software (id = 2)
            [
                'jenis_gangguan' => 2,
                'detail' => 'Format/install HDD',
            ],
            [
                'jenis_gangguan' => 2,
                'detail' => 'Re-Install sistem operasi',
            ],
            [
                'jenis_gangguan' => 2,
                'detail' => 'Check dan bersihkan virus',
            ],
            [
                'jenis_gangguan' => 2,
                'detail' => 'Re-install software',
            ],
            [
                'jenis_gangguan' => 2,
                'detail' => 'Lainnya',
            ],

            // Gangguan Karena Jaringan (id = 3)
            [
                'jenis_gangguan' => 3,
                'detail' => 'Check dan perbaikan kabel LAN yang putus',
            ],
            [
                'jenis_gangguan' => 3,
                'detail' => 'Check kabel LAN dari PC ke Hubswitch',
            ],
            [
                'jenis_gangguan' => 3,
                'detail' => 'Check koneksi karingan LAN ke Server',
            ],
            [
                'jenis_gangguan' => 3,
                'detail' => 'Check konfigurasi TCP/IP dan DNS',
            ],
            [
                'jenis_gangguan' => 3,
                'detail' => 'Check koneksi layanan jarignan internet',
            ],
            [
                'jenis_gangguan' => 3,
                'detail' => 'Check koneksi jaringan WIFI Router ke Hub',
            ],
            [
                'jenis_gangguan' => 3,
                'detail' => 'Check signal WIFI',
            ],
            [
                'jenis_gangguan' => 3,
                'detail' => 'Check koneksi layanan jaringan ISP internet',
            ],
        ];

        foreach ($detail_disruptions as $disruption) {
            DetailDisruption::create($disruption);
        }
    }
}
