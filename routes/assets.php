<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetsController;
use App\Models\Asset;

// Group route untuk menambahkan aset
Route::middleware(['auth'])->group(function () {
    Route::get('/assets/create', [AssetsController::class, 'create'])->name('assets.create');
    Route::post('/assets/store', [AssetsController::class, 'store'])->name('assets.store');
});

Route::get('/item/{type}', [AssetsController::class, 'show'])->name('Item.Show');

Route::get('/item/{type}/{serial_number}', [AssetsController::class, 'detail'])->name('Item.Detail');

Route::delete('/item/{serial_number}', [AssetsController::class, 'destroy'])->name('Item.Destroy');

Route::get('/item/latest/{serial_number}', [AssetsController::class, 'latest'])->name('Item.Latest');

Route::get('/latest-assets', function () {
    return response()->json(Asset::latest()->take(4)->get());
});

Route::get('/api/assets', [AssetsController::class, 'getAssets']);
