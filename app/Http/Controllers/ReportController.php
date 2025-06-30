<?php

namespace App\Http\Controllers;

use App\Models\Reports\Report;
use App\Models\Asset;
use App\Models\Identification;
use App\Models\Reports\ReportIdentification;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    use AuthorizesRequests;

    public function create()
    {
        $identifications = Identification::select('id', 'identifikasi_masalah')->get();
        return Inertia::render('FormLaporan', [
            'identifications' => $identifications
        ]);
    }

    public function index(): Response
    {
        $user = Auth::user();

        $reports = $user->role === 'admin'
            ? Report::with('user', 'reportIdentifications.identification')->get()
            : Report::with('user', 'reportIdentifications.identification')
            ->where('user_pelapor', $user->id)->get();

        return Inertia::render('Reports/Index', [
            'reports' => $reports
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'aset' => 'required|string',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|max:2048',
            'identifikasi_masalah' => 'required|array',
            'identifikasi_masalah.*' => 'exists:identifications,id',
        ]);

        DB::beginTransaction();

        try {
            // Simpan laporan ke table `reports`
            $report = new Report();
            $report->aset = $validated['aset'];
            $report->user_pelapor = Auth::id();
            $report->deskripsi = $validated['deskripsi'];

            if ($request->hasFile('gambar')) {
                $path = $request->file('gambar')->store('laporan', 'public');
                $report->gambar = '/storage/' . $path;
            }

            $report->save();

            // Simpan identifikasi masalah ke table `report_identifications`
            foreach ($validated['identifikasi_masalah'] as $idMasalah) {
                ReportIdentification::create([
                    'report_id' => $report->id,
                    'identifikasi_masalah' => $idMasalah,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Laporan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan laporan. ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $report = Report::with('user', 'aset', 'reportIdentifications.identification')->findOrFail($id);
        return Inertia::render('Reports/Detail', [
            'report' => $report
        ]);
    }

    public function edit($id)
    {
        $report = Report::with('reportIdentifications.identification')->findOrFail($id);
        $allIdentification = Identification::all();
        $this->authorize('update', $report);

        return Inertia::render('Reports/Edit', [
            'report' => $report,
            'identifications' => $allIdentification
        ]);
    }

    public function update(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $this->authorize('update', $report);

        $validated = $request->validate([
            'aset' => 'required|string',
            'identifikasi_masalah' => 'required|array',
            'identifikasi_masalah.*' => 'exists:identifications,id',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        DB::beginTransaction();

        try {
            // Gambar (jika diupdate)
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $filename = time() . '-' . $file->getClientOriginalName();
                $path = $file->storeAs('laporan', $filename, 'public');

                $validated['gambar'] = '/storage/' . $path;
            } else {
                $validated['gambar'] = $report->gambar;
            }

            // Update report
            $report->update([
                'aset' => $validated['aset'],
                'deskripsi' => $validated['deskripsi'],
                'gambar' => $validated['gambar'],
            ]);

            // Update relasi identifikasi_masalah:
            // Hapus dulu semua relasi lama
            ReportIdentification::where('report_id', $report->id)->delete();

            // Tambahkan yang baru
            foreach ($validated['identifikasi_masalah'] as $idMasalah) {
                ReportIdentification::create([
                    'report_id' => $report->id,
                    'identifikasi_masalah' => $idMasalah,
                ]);
            }

            DB::commit();

            return redirect()->route('riwayat.index')->with('success', 'Laporan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui laporan. ' . $e->getMessage()]);
        }
    }

    public function destroy(Report $report, $id)
    {
        $report = Report::findOrFail($id);
        $this->authorize('delete', $report);

        $report->delete();
        return redirect()->route('riwayat.index');
    }

    public function exportPdf($id)
    {
        $report = Report::with('user')->findOrFail($id);
        // $this->authorize('exportPdf', $report);

        $no_tiket = 'WG-' . strtoupper(uniqid());

        $pdf = Pdf::loadView('pdf.report', ['report' => $report, 'no_tiket' => $no_tiket])->setPaper('A4', 'portrait');;

        return $pdf->stream('laporan_kerusakan_' . $id . '.pdf');
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
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $report = Report::with(['user', 'asset'])->findOrFail($id);

        return Inertia::render('Admin/AdminConfirmation', [
            'report' => $report
        ]);
    }

    public function kirim(Request $request, $id)
    {
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

        if ($request->hasFile('gambar_konfirmasi')) {
            $file = $request->file('gambar_konfirmasi');
            $filename = time() . '-' . $file->getClientOriginalName();
            $path = $file->storeAs('konfirmasi', $filename, 'public');

            $validatedData['gambar_konfirmasi'] = '/storage/' . $path;
        } else {
            $validatedData['gambar_konfirmasi'] = $report->gambar_konfirmasi;
        }

        $report->update($validatedData);

        return redirect()->back()->with('success', 'Item berhasil diperbarui.');
    }
}
