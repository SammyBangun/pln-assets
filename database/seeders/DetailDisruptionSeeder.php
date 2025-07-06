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
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'SSD SATA',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Mouse USB',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Mouse Wireless',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'MOBO Combo',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Fan Processor',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Memory/RAM',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Power Supply',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Monitor',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Kabel HDMI',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Hubswitch',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'FO Hubswitch',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'WIFI Router',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'VGA Card',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Printer',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'WIFI Card',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'WIFI USB',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'LAN Card',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Radio Access Point',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Kabel Patch Cord',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Keyboard USB',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Keyboard Combo',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Kabel LAN UTP',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Kabel Fiber Optic',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 1,
                'detail' => 'Konektor RJ45',
                'hal_lain' => null,
                'keterangan' => null
            ],

            // Gangguan Karena Software (id = 2)
            [
                'jenis_gangguan' => 2,
                'detail' => 'Format/install HDD',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 2,
                'detail' => 'Re-Install sistem operasi',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 2,
                'detail' => 'Check dan bersihkan virus',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 2,
                'detail' => 'Re-install software',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 2,
                'detail' => 'Lainnya',
                'hal_lain' => null,
                'keterangan' => null
            ],

            // Gangguan Karena Jaringan (id = 3)
            [
                'jenis_gangguan' => 3,
                'detail' => 'Check dan perbaikan kabel LAN yang putus',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 3,
                'detail' => 'Check kabel LAN dari PC ke Hubswitch',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 3,
                'detail' => 'Check koneksi karingan LAN ke Server',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 3,
                'detail' => 'Check konfigurasi TCP/IP dan DNS',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 3,
                'detail' => 'Check koneksi layanan jarignan internet',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 3,
                'detail' => 'Check koneksi jaringan WIFI Router ke Hub',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 3,
                'detail' => 'Check signal WIFI',
                'hal_lain' => null,
                'keterangan' => null
            ],
            [
                'jenis_gangguan' => 3,
                'detail' => 'Check koneksi layanan jaringan ISP internet',
                'hal_lain' => null,
                'keterangan' => null
            ],
        ];

        foreach ($detail_disruptions as $disruption) {
            DetailDisruption::create($disruption);
        }
    }
}
