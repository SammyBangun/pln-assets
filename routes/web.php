<?php

use Inertia\Inertia;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ReportController;
use App\Models\User;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

use Illuminate\Support\Facades\Auth;

// Rute untuk menampilkan pengguna (admin only)
Route::get('/admin/users', function () {
    // Mengecek apakah pengguna yang login adalah admin
    if (Auth::check() && Auth::user()->role === 'admin') {
        return (new UserController())->index();  // Menampilkan data pengguna
    }

    // Arahkan ke halaman login jika bukan admin
    return redirect()->route('login');
});

// Rute untuk memperbarui role pengguna (admin only)
Route::put('/users/{user}/role', function (Request $request, User $user) {
    // Mengecek apakah pengguna yang login adalah admin
    if (Auth::check() && Auth::user()->role === 'admin') {
        return (new UserController())->updateRole($request, $user);  // Update role pengguna
    }

    // Arahkan ke halaman login jika bukan admin
    return redirect()->route('login');
});


//pdf
Route::get('/laporan/{id}/export', [ReportController::class, 'exportPdf'])->name('laporan.export');

//laporan
Route::middleware(['auth'])->group(function () {
    Route::get('/riwayat', [ReportController::class, 'index'])->name('riwayat.index');
    Route::post('/riwayat', [ReportController::class, 'store'])->name('riwayat.store');
    Route::get('/riwayat/{id}/edit', [ReportController::class, 'edit'])->name('riwayat.edit');
    Route::get('/riwayat/{id}', [ReportController::class, 'show'])->name('riwayat.show');
    Route::put('/riwayat/{id}', [ReportController::class, 'update'])->name('riwayat.update');
    Route::delete('/riwayat/{id}', [ReportController::class, 'destroy'])->name('riwayat.destroy');
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

        // Tambahkan route untuk users
    });

    // Route untuk form laporan
    Route::get('/form', function () {
        return Inertia::render('FormLaporan');
    })->name('form');

    // Route untuk manajemen asset
    Route::resource('assets', AssetController::class);

    // Route untuk profile user
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
