<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminOnly;
use App\Models\Assets\AssetType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Assets\Asset;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class AssetsController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return Inertia::render('Assets/Index');
    }

    public function show($tipe)
    {
        $tipeModel = AssetType::with('assets')->where('tipe', $tipe)->firstOrFail();

        if (!$tipeModel) {
            abort(404, 'Tipe aset tidak ditemukan');
        }

        $items = Asset::with('tipe')
            ->where('tipe', $tipeModel->id) // karena kolom 'tipe' di table Asset menyimpan ID
            ->get();

        return Inertia::render('Assets/Item', [
            'items' => $items,
            'tipe' => $tipeModel->tipe,
        ]);
    }


    public function latest($serial_number)
    {
        $items = Asset::where('serial_number', $serial_number)->get();

        return Inertia::render('Assets/Item', [
            'items' => $items
        ]);
    }

    public function create()
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $divisions = Division::select('id', 'nama_divisi')->get();
        $asset_types = AssetType::select('id', 'tipe')->get();

        return Inertia::render('Assets/Create', [
            'divisions' => $divisions,
            'asset_types' => $asset_types
        ]);
    }

    public function store(Request $request)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        // Validasi input
        $validated = $request->validate([
            'serial_number' => 'required|string|max:50|unique:assets',
            'id_divisi' => 'required|exists:divisions,id',
            'nama' => 'required|string|max:50',
            'tipe' => 'required|string|max:50',
            'seri' => 'required|string|max:50',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'lokasi' => 'required|string',
            'status_aset' => 'in:Aktif,Dalam Penanganan,Hilang',
            'tanggal_beli' => 'required|date',
            'terakhir_servis' => 'nullable|date',
        ]);

        $validated['status_aset'] = $validated['status_aset'] ?? 'Aktif';

        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('assets', 'public');
            $validated['gambar'] = basename($gambarPath);
        }

        Asset::create($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Asset berhasil ditambahkan!');
    }

    public function getAssets()
    {
        $assets = Asset::select('serial_number', 'nama')->get();
        return response()->json($assets);
    }

    public function detail($tipe, $serial_number)
    {
        $tipeModel = AssetType::with('assets')->where('tipe', $tipe)->firstOrFail();
        $item = Asset::with('division', 'tipe')
            ->where('tipe', $tipeModel->id)
            ->where('serial_number', $serial_number)
            ->firstOrFail();

        return Inertia::render('Assets/Detail', [
            'item' => $item,
            'tipeModel' => $tipeModel
        ]);
    }

    public function destroy(Asset $asset, $serial_number)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $asset = Asset::findOrFail($serial_number);
        $tipe = $asset->tipe;
        $asset->delete();
        return redirect()->route('Item.Show', ['tipe' => $tipe]);
    }

    public function edit($tipe, $serial_number): Response
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $tipeModel = AssetType::with('assets')->where('tipe', $tipe)->firstOrFail();
        $item = Asset::findOrFail($serial_number);
        $divisions = Division::select('id', 'nama_divisi')->get();

        return Inertia::render('Assets/Edit', [
            'item' => $item,
            'divisions' => $divisions,
            'tipeModel' => $tipeModel
        ]);
    }

    public function update(Request $request, $serial_number)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $validatedData = $request->validate([
            'serial_number' => 'required|string|max:255|unique:assets,serial_number,' . $serial_number . ',serial_number',
            'nama' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'seri' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'status_aset' => 'required|in:Aktif,Dalam Penanganan,Hilang',
            'tanggal_beli' => 'required|date',
            'terakhir_servis' => 'nullable|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        if ($serial_number !== $validatedData['serial_number']) {
            DB::table('assets')
                ->where('serial_number', $serial_number)
                ->update(['serial_number' => $validatedData['serial_number']]);
        }

        $item = Asset::where('serial_number', $validatedData['serial_number'])->first();
        if (!$item) {
            return redirect()->back()->with('error', 'Item tidak ditemukan setelah update serial_number.');
        }

        if ($request->hasFile('gambar')) {
            if ($item->gambar) {
                Storage::delete('public/assets/' . $item->gambar);
            }

            $gambarPath = $request->file('gambar')->store('assets', 'public');
            $gambarName = basename($gambarPath);

            $item->update(['gambar' => $gambarName]);
        }

        $item->update([
            'nama' => $validatedData['nama'],
            'tipe' => $validatedData['tipe'],
            'seri' => $validatedData['seri'],
            'lokasi' => $validatedData['lokasi'],
            'status_aset' => $validatedData['status_aset'],
            'tanggal_beli' => $validatedData['tanggal_beli'],
            'terakhir_servis' => $validatedData['terakhir_servis'],
        ]);

        return redirect()->route('Item.Edit', [
            'tipe' => $item->tipe,
            'serial_number' => $validatedData['serial_number']
        ])->with('success', 'Data berhasil diperbarui.');
    }
}
