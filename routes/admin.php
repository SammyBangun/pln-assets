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

Route::get('/tindak-lanjut/hardware/{id}', [AdminFollowUpController::class, 'indexHardware'])->name('admin.tindak_lanjut.indexHardware');
Route::post('/admin/tindak-lanjut/hardware/{id}', [AdminFollowUpController::class, 'storeHardware'])->name('admin.tindak_lanjut.storeHardware');

Route::get('/tindak-lanjut/software/{id}', [AdminFollowUpController::class, 'indexSoftware'])->name('admin.tindak_lanjut.indexSoftware');
Route::post('/admin/tindak-lanjut/software/{id}', [AdminFollowUpController::class, 'storeSoftware'])->name('admin.tindak_lanjut.storeSoftware');

Route::get('/tindak-lanjut/network/{id}', [AdminFollowUpController::class, 'indexNetwork'])->name('admin.tindak_lanjut.indexNetwork');
Route::post('/admin/tindak-lanjut/network/{id}', [AdminFollowUpController::class, 'storeNetwork'])->name('admin.tindak_lanjut.storeNetwork');

Route::post('/admin/tindak-lanjut/{id}', [AdminFollowUpController::class, 'mark'])->name('admin.tindak_lanjut.markAsDone');

Route::get('/tindak-lanjut/finalization/{id}', [AdminFollowUpController::class, 'finalization'])->name('admin.tindak_lanjut.finalization');
Route::post('/admin/tindak-lanjut/finalization/{id}', [AdminFollowUpController::class, 'storeFinalization'])->name('admin.tindak_lanjut.storeFinalization');

Route::get('/admin/divisions/', [AdminLookupController::class, 'divisions'])->name('admin.lookup.divisions');
Route::post('/admin/divisions/', [AdminLookupController::class, 'storeDivisions'])->name('admin.divisions.store');
Route::delete('/admin/divisions/{id}', [AdminLookupController::class, 'deleteDivision'])->name('admin.divisions.delete');

Route::get('/admin/identifications/', [AdminLookupController::class, 'identifications'])->name('admin.lookup.identifications');
Route::post('/admin/identifications/', [AdminLookupController::class, 'storeIdentifications'])->name('admin.identifications.store');
Route::delete('/admin/identifications/{id}', [AdminLookupController::class, 'deleteIdentifications'])->name('admin.identifications.delete');

Route::get('/admin/disruptions/', [AdminLookupController::class, 'disruptions'])->name('admin.lookup.disruptions');
Route::post('/admin/disruptions/', [AdminLookupController::class, 'storeDisruptions'])->name('admin.disruptions.store');
Route::delete('/admin/disruptions/{id}', [AdminLookupController::class, 'deleteDisruptions'])->name('admin.disruptions.delete');

Route::get('/admin/deliverables/', [AdminLookupController::class, 'deliverables'])->name('admin.lookup.deliverables');
Route::post('/admin/deliverables/', [AdminLookupController::class, 'storeDeliverables'])->name('admin.deliverables.store');
Route::delete('/admin/deliverables/{id}', [AdminLookupController::class, 'deleteDeliverables'])->name('admin.deliverables.delete');

Route::get('/admin/asset-types/', [AdminLookupController::class, 'assetTypes'])->name('admin.lookup.asset-types');
Route::post('/admin/asset-types/', [AdminLookupController::class, 'storeAssetTypes'])->name('admin.asset-types.store');
Route::delete('/admin/asset-types/{id}', [AdminLookupController::class, 'deleteAssetTypes'])->name('admin.asset-types.delete');

Route::get('/admin/detail-disruptions/', [AdminLookupController::class, 'detailDisruptions'])->name('admin.lookup.detail-disruptions');
Route::post('/admin/detail-disruptions/', [AdminLookupController::class, 'storeDetailDisruptions'])->name('admin.detail-disruptions.store');
Route::delete('/admin/detail-disruptions/{id}', [AdminLookupController::class, 'deleteDetailDisruptions'])->name('admin.detail-disruptions.delete');
