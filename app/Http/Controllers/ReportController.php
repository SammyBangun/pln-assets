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


    public function update(Request $request, Report $report)
    {
        $this->authorize('update', $report);

        $report->update($request->validate([
            'laporan_kerusakan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]));

        return redirect()->route('riwayat.index');
    }

    public function destroy(Report $report)
    {
        $this->authorize('delete', $report);

        $report->delete();
        return redirect()->route('riwayat.index');
    }

    public function exportPdf($id)
{
    $report = Report::with('user')->findOrFail($id);

    $pdf = Pdf::loadView('pdf.report', ['report' => $report]);

    return $pdf->download('laporan_kerusakan_' . $id . '.pdf');
}
}
