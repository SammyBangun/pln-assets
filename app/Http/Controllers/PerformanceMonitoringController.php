<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Carbon\Carbon;

class PerformanceMonitoringController extends Controller
{
    public function index()
    {
        // $startOfMonth = Carbon::now()->startOfMonth();
        // $endOfMonth = Carbon::now()->endOfMonth();

        $petugas = User::where('role', 'petugas')
            ->withCount('assignments')
            ->orderByDesc('assignments_count')
            ->get();

        $pelapor = User::with('division')->where('role', 'user')
            ->withCount('reports')
            ->orderByDesc('reports_count')
            ->get();

        return Inertia::render('Admin/Monitoring/Performance', [
            'petugas' => $petugas,
            'pelapor' => $pelapor,
        ]);
    }
}
