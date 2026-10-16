<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SkriningController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\AuthController;

// Halaman Beranda langsung mengarah ke Skrining Mandiri (Publik)
Route::get('/', [SkriningController::class, 'index'])->name('home');

// Modul Skrining Mandiri & Inferensi ONNX Client-Side
Route::get('/skrining', [SkriningController::class, 'index'])->name('skrining.index');
Route::post('/skrining/simpan', [SkriningController::class, 'store'])->name('skrining.store');
Route::get('/skrining/rujukan/{id?}', [SkriningController::class, 'printRujukan'])->name('skrining.rujukan');
Route::get('/skrining/cetak/{id?}', [SkriningController::class, 'printRujukan'])->name('skrining.print');

// Modul Internal Tenaga Medis / Dokter (Hanya Bisa Diakses Setelah Login)
Route::middleware('auth')->group(function () {
    // Modul Dasbor Pemantauan (Monitoring Dashboard)
    Route::get('/dasbor', [DashboardController::class, 'index'])->name('dashboard');

    // Modul Riwayat Transaksi Skrining Pasien (Audit Log)
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/ekspor-csv', [RiwayatController::class, 'exportCsv'])->name('riwayat.export');
    Route::delete('/riwayat/{id}', [RiwayatController::class, 'destroy'])->name('riwayat.destroy');
});

// Autentikasi Staf / Dokter RSI Kendal & Demo Sidang
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/quick-login', [AuthController::class, 'quickLogin'])->name('login.quick');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
