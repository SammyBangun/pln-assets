<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AssetController extends Controller
{
    // GET /assets
    public function index()
    {
        $assets = Asset::all();

        return Inertia::render('Assets/Index', [
            'assets' => $assets,
        ]);
    }

    // GET /assets/create
    public function create()
    {
        return Inertia::render('Assets/Create');
    }

    // POST /assets
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'type' => 'required|string|max:50',
            'series' => 'required|string|max:50',
            'tgl_beli' => 'required|date',
            'last_service' => 'nullable|date',
        ]);

        Asset::create($request->all());

        return redirect()->route('assets.index')->with('success', 'Asset berhasil ditambahkan');
    }

    // GET /assets/{id}
    public function show($id)
    {
        $asset = Asset::findOrFail($id);

        return Inertia::render('Assets/Show', [
            'asset' => $asset,
        ]);
    }

    // GET /assets/{id}/edit
    public function edit($id)
    {
        $asset = Asset::findOrFail($id);

        return Inertia::render('Assets/Edit', [
            'asset' => $asset,
        ]);
    }

    // PUT /assets/{id}
    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:50',
            'type' => 'sometimes|string|max:50',
            'series' => 'sometimes|string|max:50',
            'tgl_beli' => 'sometimes|date',
            'last_service' => 'nullable|date',
        ]);

        $asset->update($request->all());

        return redirect()->route('assets.index')->with('success', 'Asset berhasil diperbarui');
    }

    // DELETE /assets/{id}
    public function destroy($id)
    {
        $asset = Asset::findOrFail($id);
        $asset->delete();

        return redirect()->route('assets.index')->with('success', 'Asset berhasil dihapus');
    }
}