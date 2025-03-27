<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminOnly;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;


class AssetsController extends Controller
{
    use AuthorizesRequests;
    public function show($type)
    {
        $items = Asset::where('type', $type)->get();

        return Inertia::render('Assets/Item', [
            'items' => $items,
            'type' => $type
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

        $users = User::select('id', 'name')->get();

        return Inertia::render('Assets/Create', ['users' => $users]);
    }

    public function store(Request $request)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        // Validasi input
        $validated = $request->validate([
            'serial_number' => 'required|string|max:50|unique:assets',
            'id_user' => 'required|exists:users,id',
            'name' => 'nullable|string|max:50',
            'type' => 'nullable|string|max:50',
            'series' => 'nullable|string|max:50',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tgl_beli' => 'nullable|date',
            'last_service' => 'nullable|date',
        ]);

        // Cek apakah ada file gambar yang diunggah
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('assets', 'public'); // Simpan di storage/app/public/assets
            $validated['gambar'] = $gambarPath;
        }

        // Simpan ke database
        Asset::create($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Asset berhasil ditambahkan!');
    }

    public function getAssets()
    {
        $assets = Asset::select('serial_number', 'name')->get();
        return response()->json($assets);
    }

    public function detail($type, $serial_number)
    {
        $item = Asset::with('user')
            ->where('type', $type)
            ->where('serial_number', $serial_number)
            ->firstOrFail();
        return Inertia::render('Assets/Detail', ['item' => $item]);
    }

    public function destroy(Asset $asset, $serial_number)
    {
        // $this->authorize('delete', $asset);
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $asset = Asset::findOrFail($serial_number);
        $type = $asset->type;
        $asset->delete();
        return redirect()->route('Item.Show', ['type' => $type]);
    }

    public function edit($type, $serial_number): Response
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $item = Asset::findOrFail($serial_number);
        // $this->authorize('update', $item);

        return Inertia::render('Assets/Edit', [
            'item' => $item
        ]);
    }

    public function update(Request $request, $serial_number)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $item = Asset::where('serial_number', $serial_number)->firstOrFail();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'series' => 'required|string|max:255',
            'tgl_beli' => 'required|date',
            'last_service' => 'nullable|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        // Jika ada file baru diunggah, simpan file baru dan hapus yang lama
        if ($request->hasFile('gambar')) {
            if ($item->gambar) {
                Storage::delete('public/' . $item->gambar);
            }
            $path = $request->file('gambar')->store('assets', 'public');
            $validatedData['gambar'] = $path;
        } else {
            // Jika tidak ada gambar baru, tetap gunakan gambar lama
            $validatedData['gambar'] = $item->gambar;
        }

        $item->update($validatedData);

        return redirect()->back()->with('success', 'Item berhasil diperbarui.');
    }
}
