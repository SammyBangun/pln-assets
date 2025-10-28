<?php

namespace App\Http\Controllers\Admin;

use App\Models\Reports\ReportAssignment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Reports\Report;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminConfirmController extends Controller
{
    use AuthorizesRequests;

    public function index($id)
    {
        if (Auth::check() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'petugas') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $report = Report::with([
            'user',
            'aset.tipe',
            'aset.division',
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
            return redirect()->route('admin.dashboard');
        } else if (in_array($report->assignment?->status, ['Ditugaskan'])) {
            return redirect()->route('admin.tindak_lanjut.indexHardware', ['id' => $report_assign->id]);
        } else if (in_array($report->assignment?->status, ['Finalisasi'])) {
            return redirect()->route('admin.tindak_lanjut.finalization', ['id' => $report_assign->id]);
        } else if (in_array($report->assignment?->status, ['Pending'])) {
            return redirect()->route('admin.tindak_lanjut.finalization', ['id' => $report_assign->id]);
        } else if (in_array($report->assignment?->status, ['Menunggu Verifikasi'])) {
            return redirect()->route('admin.tindak_lanjut.verification', ['id' => $report_assign->id]);
        } else if (in_array($report->assignment?->status, ['Selesai'])) {
            return redirect()->route('admin.dashboard');
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

        $assignment = ReportAssignment::where('report_id', $report_id)->firstOrFail();
        $assignment->update([
            'status' => $request->status,
            'keterangan_status' => $request->keterangan_status
        ]);

        $assignment->status = $validatedData['status'];
        $assignment->save();

        return redirect()->route('admin.dashboard')->with('success', 'Status berhasil diperbarui.');
    }
}
