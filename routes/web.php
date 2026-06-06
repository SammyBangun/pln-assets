<?php

use Inertia\Inertia;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PerformanceMonitoringController;
use App\Models\Reports\Report;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

Route::get('/operator/dashboard', function () {
    return Inertia::render('Operator/Dashboard');
});

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canResetPassword' => Route::has('password.request'),
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Manajemen pengguna (admin only)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/admin/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
    Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});


//laporan
Route::middleware(['auth'])->group(function () {
    Route::get('/form', [ReportController::class, 'create'])->name('form');
    Route::get('/riwayat', [ReportController::class, 'index'])->name('riwayat.index');
    Route::post('/riwayat', [ReportController::class, 'store'])->name('riwayat.store');
    Route::get('/riwayat/{id}', [ReportController::class, 'show'])->name('riwayat.show');
    Route::get('/riwayat/{id}/edit', [ReportController::class, 'edit'])->name('riwayat.edit');
    Route::post('/riwayat/{id}', [ReportController::class, 'update'])->name('riwayat.update');
    Route::delete('/riwayat/{id}', [ReportController::class, 'destroy'])->name('riwayat.destroy');

    //pdf
    Route::get('/laporan/{id}/export', [ReportController::class, 'exportPdf'])->name('laporan.export');
    Route::get('/laporan/export', [ReportController::class, 'exportAllPdf'])->name('laporan.export.all');
});

// Group untuk route yang membutuhkan autentikasi dan verifikasi email
Route::middleware(['auth', 'verified'])->group(function () {
    // Route untuk user biasa
    Route::get('/dashboard', function () {
        return Inertia::render('Home');
    })->name('dashboard');

    // Route khusus admin dengan middleware role:admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            $users = User::where('role', 'user')->get(['id', 'name']);
            return Inertia::render('Admin/Dashboard', [
                'reports' => Report::with('user.division', 'aset', 'reportIdentifications.identification', 'assignment')->get(),
                'users' => $users
            ]);
        })->name('admin.dashboard');

        Route::get('/request-account', function () {
            return Inertia::render('Admin/ReqAccount');
        })->name('admin.request_account');
    });

    // Route untuk profile user
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/performance-monitoring', [PerformanceMonitoringController::class, 'index'])->name('performance-monitoring.index');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/assets.php';
require __DIR__ . '/admin.php';
