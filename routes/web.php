<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/storage-file/{path}', [FileController::class, 'show'])
        ->where('path', '.*')->name('storage.file');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Super Admin routes
Route::middleware(['auth', 'role:super_admin'])->prefix('master')->name('master.')->group(function () {
    Route::resource('bidang', \App\Http\Controllers\Master\BidangController::class);
    Route::resource('users', \App\Http\Controllers\Master\UserController::class);
});

// Admin Bidang routes
Route::middleware(['auth', 'role:admin_bidang,super_admin'])->prefix('kegiatan')->name('kegiatan.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Kegiatan\KegiatanController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Kegiatan\KegiatanController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Kegiatan\KegiatanController::class, 'store'])->name('store');
    Route::get('/{kegiatan}', [\App\Http\Controllers\Kegiatan\KegiatanController::class, 'show'])->name('show');
    Route::get('/{kegiatan}/edit', [\App\Http\Controllers\Kegiatan\KegiatanController::class, 'edit'])->name('edit');
    Route::put('/{kegiatan}', [\App\Http\Controllers\Kegiatan\KegiatanController::class, 'update'])->name('update');
    Route::delete('/{kegiatan}', [\App\Http\Controllers\Kegiatan\KegiatanController::class, 'destroy'])->name('destroy');
});

// Verifikasi routes (admin_bidang)
Route::middleware(['auth', 'role:admin_bidang'])->prefix('verifikasi')->name('verifikasi.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Verifikasi\VerifikasiController::class, 'index'])->name('index');
    Route::get('/{kegiatanStaff}', [\App\Http\Controllers\Verifikasi\VerifikasiController::class, 'show'])->name('show');
    Route::post('/{kegiatanStaff}/setujui', [\App\Http\Controllers\Verifikasi\VerifikasiController::class, 'setujui'])->name('setujui');
    Route::post('/{kegiatanStaff}/revisi', [\App\Http\Controllers\Verifikasi\VerifikasiController::class, 'revisi'])->name('revisi');
});

// Staff routes
Route::middleware(['auth', 'role:staff,admin_bidang,pimpinan'])->prefix('tugas')->name('tugas.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Laporan\TugasController::class, 'index'])->name('index');
    Route::get('/{kegiatanStaff}', [\App\Http\Controllers\Laporan\TugasController::class, 'show'])->name('show');
    Route::get('/{kegiatanStaff}/laporan', [\App\Http\Controllers\Laporan\LaporanController::class, 'show'])->name('laporan');
    Route::post('/{kegiatanStaff}/laporan/simpan', [\App\Http\Controllers\Laporan\LaporanController::class, 'simpan'])->name('laporan.simpan');
    Route::post('/{kegiatanStaff}/laporan/submit', [\App\Http\Controllers\Laporan\LaporanController::class, 'submit'])->name('laporan.submit');
    Route::post('/{kegiatanStaff}/laporan/dokumentasi', [\App\Http\Controllers\Laporan\LaporanController::class, 'uploadDokumentasi'])->name('laporan.dokumentasi');
    Route::delete('/dokumentasi/{doc}', [\App\Http\Controllers\Laporan\LaporanController::class, 'hapusDokumentasi'])->name('laporan.hapus-dok');
    Route::post('/{kegiatanStaff}/laporan/biaya', [\App\Http\Controllers\Laporan\LaporanController::class, 'tambahBiaya'])->name('laporan.biaya');
    Route::delete('/biaya/{biaya}', [\App\Http\Controllers\Laporan\LaporanController::class, 'hapusBiaya'])->name('laporan.hapus-biaya');
});

// Rekap routes (all authenticated)
Route::middleware(['auth'])->prefix('rekap')->name('rekap.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Rekap\RekapController::class, 'index'])->name('index');
    Route::get('/export-excel', [\App\Http\Controllers\Rekap\RekapController::class, 'exportExcel'])->name('export-excel');
    Route::get('/export-pdf', [\App\Http\Controllers\Rekap\RekapController::class, 'exportPdf'])->name('export-pdf');
    Route::get('/{kegiatan}/export-excel', [\App\Http\Controllers\Rekap\RekapController::class, 'exportKegiatanExcel'])->name('kegiatan.excel');
    Route::get('/{kegiatan}/export-pdf', [\App\Http\Controllers\Rekap\RekapController::class, 'exportKegiatanPdf'])->name('kegiatan.pdf');
});

require __DIR__.'/auth.php';
