<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Asset;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
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
            'aset' => 'required|string',
            'laporan_kerusakan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '-' . $file->getClientOriginalName();
            $path = $file->storeAs('laporan', $filename, 'public');

            $validated['gambar'] = '/storage/' . $path;
        } else {
            $validated['gambar'] = $report->gambar;
        }

        $report->update($validated);

        return redirect()->route('riwayat.index')->with('success', 'Item berhasil diperbarui.');
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
        // $this->authorize('exportPdf', $report);

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
