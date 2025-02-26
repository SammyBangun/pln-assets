<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetsController;
use App\Models\Asset;



Route::get('/item/{type}', [AssetsController::class, 'show'])->name('Item.Show');


Route::get('/latest-assets', function () {
    return response()->json(Asset::latest()->take(4)->get());
});
