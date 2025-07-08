<!DOCTYPE html>
<html>
<head>
    <title>Laporan Gangguan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Gangguan</h2>
    <p>No Tiket: <strong>{{ $no_tiket }}</strong></p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pelapor</th>
                <th>Serial Number</th>
                <th>Nama Aset</th>
                <th>Identifikasi Masalah</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $index => $report)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $report->user->name ?? '-' }}</td>
                    <td>{{ $report->aset->serial_number ?? '-' }}</td>
                    <td>{{ $report->aset->nama ?? '-' }}</td>
                    <td>
                        <ul style="margin: 0; padding-left: 15px;">
                            @foreach ($report->report_identifications->take(2) as $item)
                                <li>{{ $item->identification->identifikasi_masalah ?? '-' }}</li>
                            @endforeach
                            @if ($report->report_identifications->count() > 2)
                                <li>...</li>
                            @endif
                        </ul>
                    </td>
                    <td>{{ Str::limit($report->deskripsi, 60) }}</td>
                    <td>{{ $report->assignment->status ?? 'Belum ada status' }}</td>
                    <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
