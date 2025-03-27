<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Gangguan</title>
    <style>
        <?php echo file_get_contents(public_path('tailwind.css')); ?>
    </style>
</head>

<body class="bg-gray-100 p-5">
    <div class="max-w-4xl mx-auto bg-white p-6 border border-gray-200 rounded-lg shadow-lg">
        <div class="border-b pb-4 mb-4">
            <div class="text-xs text-gray-600 mb-8">No Tiket: {{ $no_tiket }}</div>
            <div class=" text-center">
                <img src="{{ public_path('img/logopln.png') }}" alt="PLN Nusantara Power" class="h-12">
            </div>
        </div>


        <h1 class="text-xl font-bold text-center text-gray-800 mb-4">LAPORAN GANGGUAN SARANA TEKNOLOGI INFORMASI DAN
            TINDAK LANJUTNYA</h1>

        <div class="text-sm text-gray-700">
            <p class=""><strong>User Pelapor:</strong> {{ $report->user->name }}</p>
            <p class=""><strong>Nama Aset:</strong> {{ $report->asset->name }}</p>
            <p class=""><strong>SN:</strong> {{ $report->aset }}</p>
            <p class="text-right mb-2"><strong>Tanggal Laporan:</strong> {{ $report->created_at->format('d/m/Y') }}</p>
        </div>

        <div class="mb-3 mt-4">
            <div class="bg-gray-200 p-3 rounded-xl h-8">
                <h2 class="font-semibold text-gray-700 text-center">Berita Gangguan</h2>
            </div>
            <p class="text-gray-800 ml-3">{{ $report->laporan_kerusakan }}</p>
        </div>

        <div class="mb-6">
            <div class="bg-gray-200 p-3 rounded-xl h-8">
                <h2 class="font-semibold text-gray-700 text-center">Identifikasi Masalah</h2>
            </div>
            <p class="text-gray-800 ml-3">{{ $report->deskripsi }}</p>
        </div>

        <h3 class="text-lg font-semibold text-gray-700 text-center mb-2">
            Keterangan Gangguan
        </h3>

        <table class="w-full border-collapse border border-gray-400 my-8">
            <tr>
                <td class="border border-gray-400 py-4 px-0 text-center bg-gray-300 text-gray-700 font-semibold">
                    Foto Dari User
                    <br>
                    <img src="{{ public_path('storage/laporan/' . basename($report->gambar)) }}" width="200">
                </td>
                <td class="border border-gray-400 py-4 px-0 text-center bg-gray-300 text-gray-700 font-semibold">
                    Foto Dari Admin
                    <br>
                    <img src="{{ public_path('storage/konfirmasi/' . basename($report->gambar_konfirmasi)) }}"
                        width="200">
                </td>
            </tr>
        </table>

        <div class="mb-6">
            <div class="bg-gray-200 p-3 rounded-xl h-8">
                <h2 class="font-semibold text-gray-700 text-center">Tindak Lanjut Pekerjaan</h2>
            </div>
            <p class="text-gray-800 ml-3">{{ $report->tindak_lanjut }}:</p>
            <p class="text-gray-800 ml-3">{{ $report->deskripsi_lanjut }}</p>
        </div>

        <div>
            <div class="bg-gray-200 p-3 rounded-xl h-8">
                <h2 class="font-semibold text-gray-700 text-center">Realisasi Hasil Pekerjaan</h2>
            </div>
            <p class="text-gray-800 ml-3">{{ $report->realisasi }}</p>
        </div>

        <table class="w-full border-collapse border border-gray-400 my-8">
            <tr>
                <td class="border border-gray-400 p-4 text-center bg-gray-300 text-gray-700 font-semibold">
                    Tanda Tangan User
                    <br>
                    <br>
                    <br>
                    <p style="word-spacing: 65px; letter-spacing: 0px;">( <span> )</span>
                </td>
                <td class="border border-gray-400 p-4 text-center bg-gray-300 text-gray-700 font-semibold">
                    Tanda Tangan Admin
                    <br>
                    <br>
                    <br>
                    <p style="word-spacing: 65px; letter-spacing: 0px;">( <span> )</span>
                    </p>
                </td>
            </tr>
        </table>

        <div class="text-center text-gray-500 text-sm">
            Laporan ini dibuat secara otomatis oleh sistem pada {{ \Carbon\Carbon::now()->format('d/m/Y') }}.
        </div>
    </div>
</body>

</html>
