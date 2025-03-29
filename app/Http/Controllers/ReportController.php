<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Asset;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    use AuthorizesRequests;

    public function index(): Response
    {
        return Inertia::render('Reports/Index', [
            'reports' => Report::with('user')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'aset' => 'required|string',
            'laporan_kerusakan' => 'required|string',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('laporan', 'public');
        }

        Report::create([
            'user_pelapor' => Auth::id(),
            'aset' => $request->aset,
            'laporan_kerusakan' => $request->laporan_kerusakan,
            'deskripsi' => $request->deskripsi,
            'gambar' => $path ? '/storage/' . $path : null
        ]);

        return redirect()->route('riwayat.index')->with('success', 'Laporan berhasil dikirim');
    }

    public function show($id)
    {
        $report = Report::with('user', 'asset')->findOrFail($id);
        return Inertia::render('Reports/Detail', [
            'report' => $report
        ]);
    }

    public function update(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $this->authorize('update', $report);

        $validated = $request->validate([
            'laporan_kerusakan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        try {
            $report->update([
                'laporan_kerusakan' => $validated['laporan_kerusakan'],
                'deskripsi' => $validated['deskripsi'],
            ]);

            return to_route('riwayat.index');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update report: ' . $e->getMessage()]);
        }
    }
    public function destroy(Report $report, $id)
    {
        $this->authorize('delete', $report);

        $report = Report::findOrFail($id);
        $report->delete();
        return redirect()->route('riwayat.index');
    }

    public function exportPdf($id)
    {
        $report = Report::with('user')->findOrFail($id);
        $no_tiket = 'WG-' . strtoupper(uniqid());

        $pdf = Pdf::loadView('pdf.report', ['report' => $report, 'no_tiket' => $no_tiket])->setPaper('A4', 'portrait');;

        return $pdf->stream('laporan_kerusakan_' . $id . '.pdf');
    }

    public function edit($id): Response
    {
        $report = Report::findOrFail($id);
        $this->authorize('update', $report);

        return Inertia::render('Reports/Edit', [
            'report' => $report
        ]);
    }

    public function getAssetBySerial($serialNumber)
    {
        $asset = Asset::where('serial_number', $serialNumber)->first();

        if (!$asset) {
            return response()->json(['message' => 'Aset tidak ditemukan'], 404);
        }

        return response()->json($asset);
    }

    public function konfirmasi($id): Response
    {
        // return Inertia::render('Admin/AdminConfirmation', [
        //     'report' => Report::with('user')->get()
        // ]);

        $report = Report::with(['user', 'asset'])->findOrFail($id);
        // $this->authorize('update', $item);

        return Inertia::render('Admin/AdminConfirmation', [
            'report' => $report
        ]);
    }

    public function kirim(Request $request, $id)
    {
        // $report = Report::findOrFail($id);
        // $this->authorize('update', $report);

        // $validated = $request->validate([
        //     'tindak_lanjut' => 'required|string|max:255',
        //     'deskripsi_lanjut' => 'required|string',
        //     'realisasi' => 'required|string',
        //     'gambar_konfirmasi' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        //     'status' => 'required|string'
        // ]);

        // try {
        //     // Cek jika ada gambar yang diunggah
        //     if ($request->hasFile('gambar_konfirmasi')) {
        //         $gambarPath = $request->file('gambar_konfirmasi')->store('konfirmasi', 'public');
        //     } else {
        //         $gambarPath = $report->gambar_konfirmasi; // Gunakan gambar lama jika tidak ada unggahan baru
        //     }
        //     // Hanya mengisi jika kolom masih kosong (null atau string kosong)
        //     $report->update([
        //         'tindak_lanjut' => $report->tindak_lanjut ?? $validated['tindak_lanjut'],
        //         'deskripsi_lanjut' => $report->deskripsi_lanjut ?? $validated['deskripsi_lanjut'],
        //         'realisasi' => $report->realisasi ?? $validated['realisasi'],
        //         'gambar_konfirmasi' => $gambarPath,
        //         'status' => $report->status ?? $validated['status'],
        //     ]);

        //     return to_route('riwayat.index')->with('success', 'Laporan berhasil dikonfirmasi.');
        // } catch (\Exception $e) {
        //     return back()->withErrors(['error' => 'Failed to update report: ' . $e->getMessage()]);
        // }    

        // dd($request->all());

        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $report = Report::where('id', $id)->firstOrFail();

        $validatedData = $request->validate([
            'tindak_lanjut' => 'required|string|max:255',
            'deskripsi_lanjut' => 'required|string',
            'realisasi' => 'required|string',
            'gambar_konfirmasi' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|string'
        ]);

        // Jika ada file baru diunggah, simpan file baru dan hapus yang lama
        if ($request->hasFile('gambar_konfirmasi')) {
            $file = $request->file('gambar_konfirmasi');
            $filename = time() . '-' . $file->getClientOriginalName();
            $path = $file->storeAs('konfirmasi', $filename, 'public');

            // Cek apakah path sudah benar sebelum masuk ke database
            // dd($path); // Tambahkan ini dulu untuk debug
            $validatedData['gambar_konfirmasi'] = '/storage/' . $path;
        } else {
            // Jika tidak ada gambar baru, tetap gunakan gambar lama
            $validatedData['gambar_konfirmasi'] = $report->gambar_konfirmasi;
        }


        // dd($report->gambar_konfirmasi);
        $report->update($validatedData);


        return redirect()->back()->with('success', 'Item berhasil diperbarui.');
    }
}
