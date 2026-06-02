<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// =========================================================
// 1. ENDPOINT UNTUK MICROSERVICES (Dipakai Cart & Order)
// =========================================================
// Cart & Order butuh ambil detail spesifik produk
Route::get('/products/{id}/microservice', [ProductController::class, 'getForMicroservice']);
// Order butuh memotong stok setelah pembayaran berhasil
Route::post('/products/stock/deduct', [ProductController::class, 'deductStock']);


// =========================================================
// 2. ENDPOINT UTAMA PRODUCT SERVICE (Untuk Frontend/Pengguna)
// =========================================================
// Mengambil daftar produk (termasuk fitur SEARCH dan FILTER)
Route::get('/products', [ProductController::class, 'index']);

// Mengambil detail 1 produk
Route::get('/products/{id}', [ProductController::class, 'show']);

// Penjual menambah produk baru
Route::post('/products', [ProductController::class, 'store']);

// Penjual mengubah data produk
Route::put('/products/{id}', [ProductController::class, 'update']);

// Penjual menghapus produk
Route::delete('/products/{id}', [ProductController::class, 'destroy']);

// =========================================================
// 2. ENDPOINT UNTUK PENJUAL (CRUD) - DILINDUNGI JWT
// =========================================================
Route::middleware(['jwt'])->group(function () {
    // Hanya user yang punya token valid yang bisa akses rute di bawah ini:
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});