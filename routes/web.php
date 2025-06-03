<?php

use Inertia\Inertia;
use App\Models\User;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canResetPassword' => Route::has('password.request'),
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Rute untuk menampilkan pengguna (admin only)
Route::get('/admin/users', function () {
    // Mengecek apakah pengguna yang login adalah admin
    if (Auth::check() && Auth::user()->role === 'admin') {
        return (new UserController())->index();  // Menampilkan data pengguna
    }

    // Arahkan ke halaman login jika bukan admin
    return redirect()->route('login');
});

Route::get('/admin/konfirmasi/{id}', [ReportController::class, 'konfirmasi'])->name('konfirmasi');
Route::post('/admin/konfirmasi/{id}', [ReportController::class, 'kirim'])->name('kirim');

// Rute untuk memperbarui role pengguna (admin only)
Route::put('/users/{user}/role', function (Request $request, User $user) {
    // Mengecek apakah pengguna yang login adalah admin
    if (Auth::check() && Auth::user()->role === 'admin') {
        return (new UserController())->updateRole($request, $user);  // Update role pengguna
    }

    // Arahkan ke halaman login jika bukan admin
    return redirect()->route('login');
});

//laporan
Route::middleware(['auth'])->group(function () {
    Route::get('/riwayat', [ReportController::class, 'index'])->name('riwayat.index');
    Route::post('/riwayat', [ReportController::class, 'store'])->name('riwayat.store');
    Route::get('/riwayat/{id}', [ReportController::class, 'show'])->name('riwayat.show');
    Route::get('/riwayat/{id}/edit', [ReportController::class, 'edit'])->name('riwayat.edit');
    Route::post('/riwayat/{id}', [ReportController::class, 'update'])->name('riwayat.update');
    Route::delete('/riwayat/{id}', [ReportController::class, 'destroy'])->name('riwayat.destroy');

    //pdf
    Route::get('/laporan/{id}/export', [ReportController::class, 'exportPdf'])->name('laporan.export');
});

// Group untuk route yang membutuhkan autentikasi dan verifikasi email
Route::middleware(['auth', 'verified'])->group(function () {
    // Route untuk user biasa
    Route::get('/dashboard', function () {
        return Inertia::render('Home');
    })->name('dashboard');

    // Route khusus admin dengan middleware role:admin
    Route::middleware([CheckRole::class . ':admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Admin/Dashboard');
        })->name('admin.dashboard');

        Route::get('/request-account', function () {
            return Inertia::render('Admin/ReqAccount');
        })->name('admin.request_account');
    });

    // Route untuk form laporan
    Route::get('/form', function () {
        return Inertia::render('FormLaporan');
    })->name('form');

    // Route untuk manajemen asset

    // Route untuk profile user
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/assets.php';
