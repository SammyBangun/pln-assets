<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Disruptions\Disruption;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use App\Models\Disruptions\DetailDisruption;
use App\Models\Reports\ReportFollowUp;
use App\Models\HardwareReplacement;
use App\Models\Deliverable;
use Illuminate\Support\Facades\DB;
use App\Models\Reports\ReportAssignment;
use App\Models\Operator;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AdminFollowUpController extends Controller
{
    use AuthorizesRequests;

    public function indexHardware($id)
    {
        if (Auth::check() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'petugas') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $disruption = Disruption::all();

        $detail_disruption = DetailDisruption::where('jenis_gangguan', 1)->get();

        $assignment = ReportAssignment::with('followUp')->find($id);

        return Inertia::render('Admin/FollowUp/FollowUpHardware', [
            'disruption' => $disruption,
            'detail_disruption' => $detail_disruption,
            'assignment' => $assignment
        ]);
    }

    public function storeHardware(Request $request, $id)
    {
        if (Auth::check() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'petugas') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $request->validate([
            'detail' => 'required|array|min:1',
            'hal_lain' => 'required|array|min:1',
            'hal_lain.*' => 'required|string',
            'hardware_replacements' => 'nullable|array',
            'hardware_replacements.*.nama_komponen' => 'nullable|string',
            'hardware_replacements.*.detail_merek_hardware' => 'nullable|string',
            'hardware_replacements.*.jumlah' => 'nullable|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Simpan semua laporan tindak lanjut
            $reportIds = [];

            foreach ($request->detail as $detailId) {
                $keterangan = $request->hal_lain[$detailId] ?? null;
                $report = ReportFollowUp::create([
                    'id_penugasan' => $id,
                    'jenis_gangguan' => $request->jenis_gangguan,
                    'id_detail_gangguan' => $detailId,
                    'hal_lain' => $keterangan
                ]);

                $reportIds[] = $report->id;
            }

            // Simpan penggantian hardware (hanya untuk satu report, misal pertama)
            $firstReportId = $reportIds[0] ?? null;

            if ($firstReportId && isset($request->hardware_replacements)) {
                foreach ($request->hardware_replacements as $item) {
                    if (!empty($item['nama_komponen']) && !empty($item['detail_merek_hardware']) && !empty($item['jumlah'])) {
                        HardwareReplacement::create([
                            'id_tindak_lanjut' => $firstReportId,
                            'nama_komponen' => $item['nama_komponen'],
                            'detail_merek_hardware' => $item['detail_merek_hardware'],
                            'jumlah' => $item['jumlah']
                        ]);
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Berhasil disimpan!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function indexSoftware($id)
    {
        if (Auth::check() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'petugas') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $disruption = Disruption::all();

        $detail_disruption = DetailDisruption::where('jenis_gangguan', 2)->get();

        $assignment = ReportAssignment::with('followUp')->find($id);

        return Inertia::render('Admin/FollowUp/FollowUpSoftware', [
            'disruption' => $disruption,
            'detail_disruption' => $detail_disruption,
            'assignment' => $assignment
        ]);
    }

    public function storeSoftware(Request $request, $id)
    {
        if (Auth::check() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'petugas') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        // Validasi data
        $validated = $request->validate([
            'detail' => 'array|required',
            'hal_lain' => 'array',
            'hal_lain.lainnya' => 'nullable|string',
        ]);

        // Simpan setiap detail ke tabel
        foreach ($validated['detail'] as $detailId) {
            $detail = DetailDisruption::find($detailId);
            $isLainnya = str_contains(strtolower($detail->detail), 'lainnya');

            ReportFollowUp::create([
                'id_penugasan' => $id,
                'jenis_gangguan' => 2,
                'id_detail_gangguan' => $detailId,
                'hal_lain' => $isLainnya ? $request->input('hal_lain.lainnya') : null,
            ]);
        }

        return redirect()->route('admin.tindak_lanjut.indexNetwork', $id);
    }

    public function indexNetwork($id)
    {
        if (Auth::check() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'petugas') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $disruption = Disruption::all();

        $detail_disruption = DetailDisruption::where('jenis_gangguan', 3)->get();

        $assignment = ReportAssignment::with('followUp')->find($id);

        return Inertia::render('Admin/FollowUp/FollowUpNetwork', [
            'disruption' => $disruption,
            'detail_disruption' => $detail_disruption,
            'assignment' => $assignment
        ]);
    }

    public function storeNetwork(Request $request, $id)
    {
        if (Auth::check() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'petugas') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        // Validasi data
        $validated = $request->validate([
            'detail' => 'array|required',
        ]);

        foreach ($validated['detail'] as $detailId) {
            ReportFollowUp::create([
                'id_penugasan' => $id,
                'jenis_gangguan' => 3,
                'id_detail_gangguan' => $detailId,
            ]);
        }

        return redirect()->route('admin.tindak_lanjut.finalization', $id);
    }

    public function mark($id)
    {
        if (Auth::check() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'petugas') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $assignment = ReportAssignment::findOrFail($id);
        $assignment->status = 'Finalisasi';
        $assignment->save();

        return redirect()->route('riwayat.index')->with('success', 'Tindak lanjut selesai, masuk ke tahap finalisasi.');
    }

    public function finalization($id)
    {
        if (Auth::check() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'petugas') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $assignment = ReportAssignment::with('followUp')->find($id);

        $deliverables = Deliverable::all();

        return Inertia::render('Admin/FollowUp/Finalization', [
            'assignment' => $assignment,
            'deliverables' => $deliverables
        ]);
    }

    public function storeFinalization(Request $request, $id)
    {
        if (Auth::check() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'petugas') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        // Validasi data
        $validated = $request->validate([
            'realisasi_hasil' => 'required|exists:deliverables,id',
            'catatan' => 'nullable|string|max:1000',
            'gambar_tindak_lanjut' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Ambil data penugasan
        $assignment = ReportAssignment::findOrFail($id);

        $gambarPath = null;
        if ($request->hasFile('gambar_tindak_lanjut')) {
            $gambarPath = $request->file('gambar_tindak_lanjut')->store('tindak_lanjut', 'public');
        }

        // Update data ke assignment
        $assignment->update([
            'realisasi' => $validated['realisasi_hasil'],
            'catatan' => $validated['catatan'],
            'gambar_tindak_lanjut' => $gambarPath,
            'status' => 'Selesai',
            'tanggal_selesai' => now()
        ]);

        return redirect()->route('riwayat.index')
            ->with('success', 'Finalisasi berhasil disimpan.');
    }
}
