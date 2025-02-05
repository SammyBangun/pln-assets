<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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
            'laporan_kerusakan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        Report::create([
            'user_pelapor' => Auth::id(),
            'laporan_kerusakan' => $request->laporan_kerusakan,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('riwayat.index');
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
}
