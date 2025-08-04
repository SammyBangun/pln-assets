<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assets\Asset;
use App\Models\Assets\AssetType;
use App\Models\Deliverable;
use App\Models\Disruptions\DetailDisruption;
use App\Models\Disruptions\Disruption;
use App\Models\Division;
use App\Models\Identification;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AdminLookupController extends Controller
{
    public function divisions()
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $divisions = Division::all();

        return Inertia::render('Admin/Lookup/Divisions', [
            'divisions' => $divisions
        ]);
    }

    public function storeDivisions(Request $request)
    {
        $validated = $request->validate([
            'nama_divisi' => 'required|string|max:255|unique:divisions,nama_divisi',
        ]);

        Division::create($validated);

        return back()->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function deleteDivision($id)
    {
        $division = Division::findOrFail($id);
        $division->delete();

        return back()->with('success', 'Divisi berhasil dihapus.');
    }

    public function identifications()
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $identifications = Identification::all();

        return Inertia::render('Admin/Lookup/Identifications', [
            'identifications' => $identifications
        ]);
    }

    public function storeIdentifications(Request $request)
    {
        $validated = $request->validate([
            'identifikasi_masalah' => 'required|string|max:255|unique:identifications,identifikasi_masalah',
        ]);

        Identification::create($validated);

        return back()->with('success', 'Data identifikasi masalah berhasil ditambahkan.');
    }

    public function deleteIdentifications($id)
    {
        $division = Identification::findOrFail($id);
        $division->delete();

        return back()->with('success', 'Data identifikasi masalah berhasil dihapus.');
    }

    public function disruptions()
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $disruptions = Disruption::all();

        return Inertia::render('Admin/Lookup/Disruptions', [
            'disruptions' => $disruptions
        ]);
    }

    public function storeDisruptions(Request $request)
    {
        $validated = $request->validate([
            'nama_gangguan' => 'required|string|max:255|unique:disruptions,nama_gangguan',
        ]);

        Disruption::create($validated);

        return back()->with('success', 'Data gangguan berhasil ditambahkan.');
    }

    public function deleteDisruptions($id)
    {
        $disruptions = Disruption::findOrFail($id);
        $disruptions->delete();

        return back()->with('success', 'Data gangguan masalah berhasil dihapus.');
    }

    public function deliverables()
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $deliverables = Deliverable::all();

        return Inertia::render('Admin/Lookup/Deliverables', [
            'deliverables' => $deliverables
        ]);
    }

    public function storeDeliverables(Request $request)
    {
        $validated = $request->validate([
            'realisasi_hasil' => 'required|string|max:255|unique:deliverables,realisasi_hasil',
        ]);

        Deliverable::create($validated);

        return back()->with('success', 'Data realisasi berhasil ditambahkan.');
    }

    public function deleteDeliverables($id)
    {
        $deliverables = Deliverable::findOrFail($id);
        $deliverables->delete();

        return back()->with('success', 'Data realisasi berhasil dihapus.');
    }

    public function assetTypes()
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $assetTypes = AssetType::all();

        return Inertia::render('Admin/Lookup/AssetTypes', [
            'assetTypes' => $assetTypes
        ]);
    }

    public function storeAssetTypes(Request $request)
    {
        $validated = $request->validate([
            'tipe' => 'required|string|max:255|unique:asset_types,tipe',
        ]);

        AssetType::create($validated);

        return back()->with('success', 'Data tipe aset berhasil ditambahkan.');
    }

    public function deleteAssetTypes($id)
    {
        $assetTypes = AssetType::findOrFail($id);
        $assetTypes->delete();

        return back()->with('success', 'Data tipe aset berhasil dihapus.');
    }

    public function detailDisruptions()
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $detailDisruptions = DetailDisruption::with('disruption')->get();
        $disruptions = Disruption::all();

        return Inertia::render('Admin/Lookup/DetailDisruptions', [
            'detailDisruptions' => $detailDisruptions,
            'disruptions' => $disruptions
        ]);
    }

    public function storeDetailDisruptions(Request $request)
    {
        $validated = $request->validate([
            'disruption_id' => 'required|exists:disruptions,id',
            'detail' => 'required|string|max:255',
        ]);

        DetailDisruption::create($validated);

        return back()->with('success', 'Data tipe aset berhasil ditambahkan.');
    }

    public function deleteDetailDisruptions($id)
    {
        $assetTypes = AssetType::findOrFail($id);
        $assetTypes->delete();

        return back()->with('success', 'Data tipe aset berhasil dihapus.');
    }
}
