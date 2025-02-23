<?php

namespace App\Http\Controllers;

use App\Models\Report;
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
            'laporan_kerusakan' => $request->laporan_kerusakan,
            'deskripsi' => $request->deskripsi,
            'gambar' => $path ? '/storage/' . $path : null
        ]);

        return redirect()->route('riwayat.index')->with('success', 'Laporan berhasil dikirim');
    }

    public function show($id)
    {
        $report = Report::with('user')->findOrFail($id);
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

        $pdf = Pdf::loadView('pdf.report', ['report' => $report]);

        return $pdf->download('laporan_kerusakan_' . $id . '.pdf');
    }
    public function edit($id): Response
    {
        $report = Report::findOrFail($id);
        $this->authorize('update', $report);

        return Inertia::render('Reports/Edit', [
            'report' => $report
        ]);
    }
}
