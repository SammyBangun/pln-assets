<?php

namespace App\Http\Controllers;

use App\Models\HardwareReplacement;
use App\Models\Reports\Report;
use App\Models\Assets\Asset;
use App\Models\Assets\AssetType;
use App\Models\Identification;
use App\Models\Reports\ReportFollowUp;
use App\Models\Reports\ReportIdentification;
use App\Models\Reports\ReportAssignment;
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

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $reports = Report::with('user', 'aset', 'reportIdentifications.identification', 'assignment')->get();
        } elseif ($user->role === 'petugas') {
            $reports = Report::with('user', 'aset', 'reportIdentifications.identification', 'assignment')
                ->whereHas('assignment', function ($query) use ($user) {
                    $query->where('petugas', $user->id);
                })->get();
        } else {
            $reports = Report::with('user', 'aset', 'reportIdentifications.identification', 'assignment')
                ->where('user_pelapor', $user->id)->get();
        }

        return Inertia::render('Dashboard', [
            'reports' => $reports,
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

            ReportAssignment::create([
                'report_id' => $report->id,
                'status' => 'Menunggu Konfirmasi',
            ]);

            // Simpan identifikasi masalah ke table `report_identifications`
            foreach ($validated['identifikasi_masalah'] as $idMasalah) {
                ReportIdentification::create([
                    'report_id' => $report->id,
                    'identifikasi_masalah' => $idMasalah,
                ]);
            }

            DB::commit();

            return redirect()->route('riwayat.index')->with('success', 'Laporan berhasil dikirim');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan laporan. ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $report = Report::with('user', 'aset', 'reportIdentifications.identification', 'assignment')->findOrFail($id);
        $aset = Asset::find($report->aset);
        $tipe = AssetType::where('id', $aset->tipe)->first();
        $assignment = ReportAssignment::with('petugas', 'realisasi')->where('report_id', $report->id)->first();
        $followUp = ReportFollowUp::with('disruption', 'detailDisruption', 'hardwareReplacement')->where('id_penugasan', $assignment->id)->get();
        $hardwareReplacement = HardwareReplacement::where('id_tindak_lanjut', $followUp[0]->id)->get();

        // dd($hardwareReplacement);

        return Inertia::render('Reports/Detail', [
            'report' => $report,
            'tipe' => $tipe,
            'assignment' => $assignment,
            'followUp' => $followUp,
            'hardwareReplacement' => $hardwareReplacement
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
        $report = Report::with('user', 'aset', 'assignment')->get();

        $no_tiket = 'WG-' . strtoupper(uniqid());

        $pdf = Pdf::loadView('pdf.report', ['report' => $report, 'no_tiket' => $no_tiket])->setPaper('A4', 'portrait');

        return $pdf->stream('laporan_gangguan_' . $id . '.pdf');
    }

    public function exportAllPdf()
    {
        $reports = Report::with(['user', 'aset', 'assignment', 'reportIdentifications.identification'])->latest()->get();

        $no_tiket = 'WG-' . strtoupper(uniqid());

        $pdf = Pdf::loadView('pdf.reports-all', [
            'reports' => $reports,
            'no_tiket' => $no_tiket
        ])->setPaper('A4', 'landscape');

        return $pdf->stream('laporan_gangguan.pdf');
    }

    public function getAssetBySerial($serialNumber)
    {
        $asset = Asset::where('serial_number', $serialNumber)->first();

        if (!$asset) {
            return response()->json(['message' => 'Aset tidak ditemukan'], 404);
        }

        return response()->json($asset);
    }
}
