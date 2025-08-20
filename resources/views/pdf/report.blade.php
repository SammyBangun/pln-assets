<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Gangguan</title>
    <link rel="stylesheet" href="{{ public_path('css/app.css') }}">
</head>

<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto bg-white p-6 border border-gray-200 rounded-lg shadow-lg">
        <div class="border-b pb-4 mb-4">
            <div class="text-xs text-gray-600 mb-8">No Tiket: {{ $no_tiket }}</div>
            <div class="mx-auto">
                <img src="{{ public_path('img/logopln.png') }}" alt="PLN Nusantara Power" class="h-12 mx-auto">
            </div>
        </div>


        <h1 class="text-xl font-bold text-center text-gray-800 mb-4">LAPORAN GANGGUAN SARANA TEKNOLOGI INFORMASI DAN
            TINDAK LANJUTNYA</h1>

        {{-- <p>Debug: {{ $assignment }}</p> --}}

        <div class="flex justify-between">
            <div class="text-sm text-gray-700 col-span-1">
                <p><strong>Tanggal Laporan:</strong> {{ $report->created_at->format('d/m/Y') }}</p>
                <p><strong>User Pelapor:</strong> {{ $report->user->name }}</p>
                <p><strong>Nama Aset:</strong> {{ $aset->nama }}</p>
                <p><strong>Serial Number:</strong> {{ $report->aset }}</p>
            </div>

            <div class="text-sm text-gray-700 col-span-1">
                <p class="text-underline"><strong>Penugasan pekerjaan</strong></p>
                <p><strong>Tanggal:</strong> {{ optional($assignment->tanggal_penugasan)->format('d/m/Y') ?? '-' }}</p>
                <p><strong>Nama:</strong> {{ $assignment->petugasUser->name ?? '-' }}</p>
                <p><strong>Lokasi:</strong> {{ $assignment->lokasi ?? '-' }}</p>
            </div>
        </div>

        <div class="mb-3 mt-4">
            <!-- Header Box -->
            <div class="bg-gray-900 rounded-t-xl p-3">
                <h2 class="font-semibold text-white text-center">Identifikasi Masalah</h2>
            </div>
            <!-- Isi List -->
            <div class="bg-white border border-gray-200 rounded-b-xl p-3">
                <ul class="list-disc ml-6 text-gray-800">
                    @foreach ($report->reportIdentifications as $item)
                        <li>{{ $item->identification->identifikasi_masalah }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="mb-6">
            <div class="bg-gray-900 rounded-t-xl p-3">
                <h2 class="font-semibold text-white text-center">Deskripsi Masalah</h2>
            </div>
            <div class="bg-white border border-gray-200 rounded-b-xl p-3">
                <p class="text-gray-800 ml-3">{{ $report->deskripsi }}</p>
            </div>
        </div>

        <h3 class="text-lg font-semibold text-gray-700 text-center mb-2">
            Keterangan Gangguan
        </h3>

        <div class="flex justify-between my-8 gap-4">
            <!-- Foto Dari User -->
            <div class="w-1/2 text-center items-center text-gray-700 font-semibold border border-gray-100 py-4">
                Foto Dari User
                <br>
                <img src="{{ public_path('storage/laporan/' . basename($report->gambar)) }}" width="200"
                    class="mx-auto">
            </div>

            <!-- Foto Dari Admin -->
            <div class="w-1/2 text-center items-center text-gray-700 font-semibold border border-gray-100 py-4">
                Foto Dari Admin
                <br>
                <img src="{{ public_path('storage/tindak_lanjut/' . basename($assignment->gambar_tindak_lanjut)) }}"
                    width="200" class="mx-auto">
            </div>
        </div>

        <div class="mb-6">
            <div class="bg-gray-900 rounded-t-xl p-3">
                <h2 class="font-semibold text-white text-center">Tindak Lanjut Pekerjaan</h2>
            </div>
            <div class="flex justify-around gap-6 p-3 bg-white border border-gray-200 rounded-b-xl">
                <div class="">
                    @if (isset($followUp) && count($followUp) > 0)
                        @foreach ($followUp as $item)
                            <div class="mb-4 pb-2">
                                <p class="text-lg text-gray-700">
                                    <strong>Jenis Gangguan {{ $loop->iteration }}:</strong>
                                    {{ optional($item->disruption)->nama_gangguan ?? '-' }}
                                </p>
                                <p class="text-lg text-gray-700">
                                    <strong>Tindak Lanjut:</strong>
                                    {{ optional($item->detailDisruption)->detail ?? '-' }}
                                </p>
                                <p class="text-lg text-gray-700">
                                    <strong>Keterangan:</strong>
                                    {{ $item->hal_lain ?? '-' }}
                                </p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center">Belum ada tindak lanjut.</p>
                    @endif
                </div>
                <div>
                    <p class="text-lg text-gray-700"><strong>Hardware yang diganti baru:</strong></p>
                    @if (isset($hardwareReplacement) && count($hardwareReplacement) > 0)
                        @foreach ($hardwareReplacement as $item)
                            <div class="mb-4 pb-2">
                                <p class="text-lg text-gray-700">
                                    <strong>Komponen:</strong>
                                    {{ $item->detailDisruption->detail ?? '-' }}
                                </p>
                                <p class="text-lg text-gray-700">
                                    <strong>Merek:</strong>
                                    {{ $item->detail_merek_hardware ?? '-' }}
                                </p>
                                <p class="text-lg text-gray-700">
                                    <strong>Jumlah:</strong>
                                    {{ $item->jumlah ?? '-' }}
                                </p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center">Belum ada penggantian hardware.</p>
                    @endif

                </div>
            </div>
        </div>

        <div>
            <div class="bg-gray-900 rounded-t-xl p-3">
                <h2 class="font-semibold text-white text-center">Realisasi Hasil Pekerjaan</h2>
            </div>
            <div class="bg-white border border-gray-200 rounded-b-xl p-3">
                <p class="text-gray-800">
                    {{ $assignment->realisasiHasil->realisasi_hasil ?? '-' }}
                </p>
                <p class="text-gray-600">
                    Catatan: {{ $assignment->catatan ?? 'Tidak ada catatan' }}
                </p>
            </div>
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
