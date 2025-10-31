<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetsController;
use App\Models\Assets\Asset;

Route::middleware(['auth'])->group(function () {
    Route::get('/assets/create', [AssetsController::class, 'create'])->name('assets.create');

    Route::get('/assets', [AssetsController::class, 'index'])->name('assets.index');

    Route::get('/item/{tipe}', [AssetsController::class, 'show'])->name('Item.Show');

    Route::get('/item/{tipe}/{serial_number}', [AssetsController::class, 'detail'])->name('Item.Detail');

    Route::get('/item/{tipe}/{serial_number}/edit', [AssetsController::class, 'edit'])->name('Item.Edit');

    Route::post('/item/{serial_number}/update', [AssetsController::class, 'update'])->name('Item.Update');

    Route::delete('/item/{serial_number}', [AssetsController::class, 'destroy'])->name('Item.Destroy');

    Route::get('/item/latest/{serial_number}', [AssetsController::class, 'latest'])->name('Item.Latest');

    Route::get('/latest-assets', function () {
        return response()->json(Asset::with('tipe')->latest()->take(4)->get());
    });

    Route::get('/api/assets', [AssetsController::class, 'getAssets']);
    Route::post('/assets/store', [AssetsController::class, 'store'])->name('assets.store');
});
