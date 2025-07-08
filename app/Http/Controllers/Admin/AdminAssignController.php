<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use App\Models\Reports\ReportAssignment;
use App\Models\Operator;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AdminAssignController extends Controller
{
    use AuthorizesRequests;

    public function index($id)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $operators = Operator::all();

        $assignment = ReportAssignment::findOrFail($id);

        return Inertia::render('Admin/Assign', [
            'operators' => $operators,
            'assignment' => $assignment
        ]);
    }

    public function store(Request $request, $id)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $validated = $request->validate([
            'petugas' => 'required|exists:operators,id',
            'tanggal_penugasan' => 'required|date',
            'lokasi' => 'required|string|max:255',
        ]);

        $assignment = ReportAssignment::findOrFail($id);
        $assignment->update([
            'petugas' => $validated['petugas'],
            'tanggal_penugasan' => $validated['tanggal_penugasan'],
            'lokasi' => $validated['lokasi'],
            'status' => 'Ditugaskan'
        ]);

        return redirect()->route('admin.tindak_lanjut.indexHardware', ['id' => $assignment->id])->with('success', 'Penugasan berhasil disimpan.');
    }
}
