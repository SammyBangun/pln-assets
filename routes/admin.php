<?php

use App\Http\Controllers\Admin\AdminConfirmController;
use App\Http\Controllers\Admin\AdminAssignController;
use App\Http\Controllers\Admin\AdminFollowUpController;
use App\Http\Controllers\Admin\AdminLookupController;
use Illuminate\Support\Facades\Route;


Route::get('/admin/konfirmasi/{id}', [AdminConfirmController::class, 'index'])->name('admin.konfirmasi.index');
Route::post('/admin/konfirmasi/{id}', [AdminConfirmController::class, 'store'])->name('admin.konfirmasi.store');

Route::get('/admin/penugasan/{id}', [AdminAssignController::class, 'index'])->name('admin.penugasan.index');
Route::post('/admin/penugasan/{id}', [AdminAssignController::class, 'store'])->name('admin.penugasan.store');

Route::get('/admin/tindak-lanjut/hardware/{id}', [AdminFollowUpController::class, 'indexHardware'])->name('admin.tindak_lanjut.indexHardware');
Route::post('/admin/tindak-lanjut/hardware/{id}', [AdminFollowUpController::class, 'storeHardware'])->name('admin.tindak_lanjut.storeHardware');

Route::get('/admin/tindak-lanjut/software/{id}', [AdminFollowUpController::class, 'indexSoftware'])->name('admin.tindak_lanjut.indexSoftware');
Route::post('/admin/tindak-lanjut/software/{id}', [AdminFollowUpController::class, 'storeSoftware'])->name('admin.tindak_lanjut.storeSoftware');

Route::get('/admin/tindak-lanjut/network/{id}', [AdminFollowUpController::class, 'indexNetwork'])->name('admin.tindak_lanjut.indexNetwork');
Route::post('/admin/tindak-lanjut/network/{id}', [AdminFollowUpController::class, 'storeNetwork'])->name('admin.tindak_lanjut.storeNetwork');

Route::get('/admin/tindak-lanjut/finalization/{id}', [AdminFollowUpController::class, 'finalization'])->name('admin.tindak_lanjut.finalization');
Route::post('/admin/tindak-lanjut/finalization/{id}', [AdminFollowUpController::class, 'storeFinalization'])->name('admin.tindak_lanjut.storeFinalization');

Route::get('/admin/divisions/', [AdminLookupController::class, 'divisions'])->name('admin.lookup.divisions');
Route::post('/admin/divisions/', [AdminLookupController::class, 'storeDivisions'])->name('admin.divisions.store');
Route::delete('/admin/divisions/{id}', [AdminLookupController::class, 'deleteDivision'])->name('admin.divisions.delete');
