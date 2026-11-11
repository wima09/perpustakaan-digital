<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ===== Bawaan Breeze (jangan dihapus) =====
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===== Fitur Perpustakaan =====
Route::middleware('auth')->group(function () {
    // semua role yang login boleh melihat daftar buku
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

    // tambah/ubah/hapus: hanya admin (siswa ke /buku/create => 403)
    Route::middleware('role:admin')->group(function () {
        Route::resource('buku', BukuController::class)->except(['index', 'show']);
    });

    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

    Route::post('/peminjaman', [PeminjamanController::class, 'store'])
        ->middleware('role:siswa')->name('peminjaman.store');

    Route::patch('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'kembali'])
        ->middleware('role:petugas,admin')->name('peminjaman.kembali');
});

require __DIR__ . '/auth.php';