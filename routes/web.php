<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\AuthController;

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    // Halaman utama - Form pemesanan
    Route::get('/', [PesananController::class, 'index'])->name('pesanan.index');

// Routes untuk pemesanan
Route::prefix('pesanan')->name('pesanan.')->group(function () {
    // Simpan pesanan baru
    Route::post('/', [PesananController::class, 'store'])->name('store');
    
    // Daftar pesanan (untuk admin)
    Route::get('/list', [PesananController::class, 'list'])->name('list');
    
    // Detail pesanan
    Route::get('/{id}', [PesananController::class, 'show'])->name('show');

    // Invoice PDF
    Route::get('/{id}/invoice', [PesananController::class, 'invoice'])->name('invoice');
    
    // Update status pesanan
    Route::patch('/{id}/status', [PesananController::class, 'updateStatus'])->name('update-status');
});

    // API Routes
    Route::prefix('api')->name('api.')->group(function () {
        // Get ukuran baju
        Route::get('/ukuran-baju', [PesananController::class, 'getUkuranBaju'])->name('ukuran-baju');
        
        // Get harga berdasarkan ukuran
        Route::get('/harga-ukuran', [PesananController::class, 'getHargaByUkuran'])->name('harga-ukuran');
        
        // Validasi real-time
        Route::post('/validate-customer-name', [PesananController::class, 'validateCustomerName'])->name('validate-customer-name');
        Route::post('/validate-whatsapp', [PesananController::class, 'validateWhatsApp'])->name('validate-whatsapp');
        Route::post('/validate-jersey-number', [PesananController::class, 'validateJerseyNumber'])->name('validate-jersey-number');
        
        // Get list pemesan
        Route::get('/pemesan-list', [PesananController::class, 'getPemesanList'])->name('pemesan-list');
    });

    // Dashboard admin
    Route::get('/dashboard', [PesananController::class, 'dashboard'])->name('dashboard');
});
