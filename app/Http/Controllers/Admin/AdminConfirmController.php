<?php

namespace App\Http\Controllers\Admin;

use App\Models\Assets\AssetType;
use App\Models\Reports\ReportAssignment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Reports\Report;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class AdminConfirmController extends Controller
{
    use AuthorizesRequests;

    public function index($id)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $report = Report::with([
            'user',
            'aset',
            'reportIdentifications.identification',
            'assignment'
        ])->findOrFail($id);

        $report_assign = $report->assignment;
        if (!$report_assign) {
            abort(404, 'Penugasan tidak ditemukan.');
        }

        if (in_array($report->assignment?->status, ['Diterima'])) {
            return redirect()->route('admin.penugasan.index', ['id' => $report_assign->id]);
        } else if (in_array($report->assignment?->status, ['Ditolak'])) {
            return redirect()->route('riwayat.index');
        } else if (in_array($report->assignment?->status, ['Diproses'])) {
            return redirect()->route('admin.tindak_lanjut.indexHardware', ['id' => $report_assign->id]);
        }

        return Inertia::render('Admin/AdminConfirmation', [
            'report' => $report,
            'report_assign' => $report_assign
        ]);
    }

    public function store(Request $request, $report_id)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $validatedData = $request->validate([
            'status' => 'required|string|in:Ditolak,Diterima',
            'keterangan_status' => 'nullable|string'
        ]);

        // Cari report_assignments berdasarkan id_pelaporan (foreign key)
        $assignment = ReportAssignment::where('report_id', $report_id)->firstOrFail();
        $assignment->update([
            'status' => $request->status,
            'keterangan_status' => $request->keterangan_status
        ]);

        // Update status
        $assignment->status = $validatedData['status'];
        $assignment->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }
}
