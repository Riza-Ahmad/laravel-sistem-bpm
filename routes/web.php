<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\PeraturanController;


Route::get('/', function () {
    return view('layouts.app');
});


Route::get('/peraturan/{type}', [PeraturanController::class, 'index'])
    ->where('type', 'kebijakan|eksternal|instrument')
    ->name('peraturan.index');

Route::get('/peraturan/{type}/create', function ($type) {
    return view('peraturan.add', ['type' => $type]);
})->where('type', 'kebijakan|eksternal|instrument')->name('peraturan.create');
Route::get('/peraturan/{type}/{id}/show', [PeraturanController::class, 'show'])
    ->where('type', 'kebijakan|eksternal|instrument')
    ->name('peraturan.show');
Route::get('/peraturan/{type}/{id}/edit', [PeraturanController::class, 'edit'])
    ->where('type', 'kebijakan|eksternal|instrument')
    ->name('peraturan.edit');
Route::post('/peraturan/store', [PeraturanController::class, 'store'])->name('peraturan.store');
Route::put('/peraturan/{type}/{id}', [PeraturanController::class, 'update'])
    ->where('type', 'kebijakan|eksternal|instrument')
    ->name('peraturan.update');
Route::get('/peraturan/{type}/{id}/download', [PeraturanController::class, 'download'])
    ->where('type', 'kebijakan|eksternal|instrument')
    ->name('peraturan.download');
Route::put('/peraturan/{type}/{id}/toggle', [PeraturanController::class, 'toggleStatus'])
    ->where('type', 'kebijakan|eksternal|instrument')
    ->name('peraturan.toggle');
Route::get('/peraturan/{type}/{id}/upload', [PeraturanController::class, 'showUploadForm'])
    ->where('type', 'kebijakan|eksternal|instrument')
    ->name('peraturan.upload');

Route::put('/peraturan/{type}/{id}/update-file', [PeraturanController::class, 'updateUnggah'])
    ->where('type', 'kebijakan|eksternal|instrument')
    ->name('peraturan.updateFile');
Route::get('/peraturan/{type}/{id}/history', [PeraturanController::class, 'showHistory'])
    ->where('type', 'kebijakan|eksternal|instrument')
    ->name('peraturan.history');
Route::get('/peraturan/{type}/{id}/history-download', [PeraturanController::class, 'showHistoryDownload'])
    ->where('type', 'kebijakan|eksternal|instrument')
    ->name('peraturan.historyDownload');

Route::get('/tentang', [TentangController::class, 'index'])->name('tentang.index');
Route::get('/tentang/read', [TentangController::class, 'read'])->name('tentang.read');
Route::get('/tentang/edit/{id}', [TentangController::class, 'edit'])->name('tentang.edit');
Route::post('tentang/update/{id}', [TentangController::class, 'update'])->name('tentang.update');
Route::get('/tentang/show/{id}', [TentangController::class, 'show'])->name('tentang.show');

Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/read', [BeritaController::class, 'read'])->name('berita.read');
Route::get('/berita/add', [BeritaController::class, 'add'])->name('berita.add');
Route::post('/berita/save', [BeritaController::class, 'save'])->name('berita.save');
Route::get('/berita/edit/{id}', [BeritaController::class, 'edit'])->name('berita.edit');
Route::post('berita/update/{id}', [BeritaController::class, 'update'])->name('berita.update');
Route::get('/berita/show/{id}', [BeritaController::class, 'show'])->name('berita.show');
Route::delete('/berita/{id}/delete', [BeritaController::class, 'delete'])->name('berita.delete');
Route::get('/berita/search', [BeritaController::class, 'search'])->name('berita.search');
Route::get('/berita/searchRead', [BeritaController::class, 'searchRead'])->name('berita.searchRead');
Route::get('/berita/bacaBerita/{id}', [BeritaController::class, 'see'])->name('berita.see');

// // Rute untuk Kebijakan Peraturan
// Route::get('/peraturan/kebijakan', [PeraturanController::class, 'kebijakanIndex'])->name('peraturan.kebijakan.index');
// Route::get('/peraturan/kebijakan/add', [PeraturanController::class, 'kebijakanAdd'])->name('peraturan.kebijakan.add');
// Route::post('/peraturan/kebijakan/save', [PeraturanController::class, 'kebijakanSave'])->name('peraturan.kebijakan.save');
// Route::get('/peraturan/kebijakan/edit/{id}', [PeraturanController::class, 'kebijakanEdit'])->name('peraturan.kebijakan.edit');
// Route::post('/peraturan/kebijakan/update/{id}', [PeraturanController::class, 'kebijakanUpdate'])->name('peraturan.kebijakan.update');
// Route::delete('/peraturan/kebijakan/{id}/delete', [PeraturanController::class, 'kebijakanDelete'])->name('peraturan.kebijakan.delete');

// // Rute untuk Peraturan Eksternal
// Route::get('/peraturan/eksternal', [PeraturanController::class, 'eksternalIndex'])->name('peraturan.eksternal.index');
// Route::get('/peraturan/eksternal/add', [PeraturanController::class, 'eksternalAdd'])->name('peraturan.eksternal.add');
// Route::post('/peraturan/eksternal/save', [PeraturanController::class, 'eksternalSave'])->name('peraturan.eksternal.save');
// Route::get('/peraturan/eksternal/edit/{id}', [PeraturanController::class, 'eksternalEdit'])->name('peraturan.eksternal.edit');
// Route::post('/peraturan/eksternal/update/{id}', [PeraturanController::class, 'eksternalUpdate'])->name('peraturan.eksternal.update');
// Route::delete('/peraturan/eksternal/{id}/delete', [PeraturanController::class, 'eksternalDelete'])->name('peraturan.eksternal.delete');

// Route::get('/instrumen-aps', [PeraturanController::class, 'instrumenApsIndex'])->name('peraturan.instrumenAps.index');
// Route::get('/instrumen-aps/add', [PeraturanController::class, 'instrumenApsAdd'])->name('peraturan.instrumenAps.add');
// Route::post('/instrumen-aps/save', [PeraturanController::class, 'instrumenApsSave'])->name('peraturan.instrumenAps.save');
// Route::get('/instrumen-aps/edit/{id}', [PeraturanController::class, 'instrumenApsEdit'])->name('peraturan.instrumenAps.edit');
// Route::put('/instrumen-aps/update/{id}', [PeraturanController::class, 'instrumenApsUpdate'])->name('peraturan.instrumenAps.update');
// Route::delete('/instrumen-aps/delete/{id}', [PeraturanController::class, 'instrumenApsDelete'])->name('peraturan.instrumenAps.delete');
