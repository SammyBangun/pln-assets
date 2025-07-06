<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Operator;
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

    public function operators()
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $operators = Operator::all();

        return Inertia::render('Admin/Lookup/Operators', [
            'operators' => $operators
        ]);
    }

    public function storeOperators(Request $request)
    {
        $validated = $request->validate([
            'nama_petugas' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
        ]);

        Operator::create($validated);

        return back()->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function deleteOperator($id)
    {
        $operator = Operator::findOrFail($id);
        $operator->delete();

        return back()->with('success', 'Petugas berhasil dihapus.');
    }
}
