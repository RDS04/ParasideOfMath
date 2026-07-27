<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AdminAuthController;

Route::get('/', function () {
    return view('/informasi/index');
});

// Authentication Routes (Siswa & General)
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::post('/register', 'register')->name('register.post');
    Route::post('/logout', 'logout')->name('logout');
});

// Admin Registration Routes
Route::controller(AdminAuthController::class)->group(function () {
    Route::get('/admin/register', 'showRegistrationForm')->name('admin.register');
    Route::post('/admin/register', 'register')->name('admin.register.post');
});

// Protected Dashboard Routes
Route::get('/siswa', function () {
    $siswa = auth()->guard('siswa')->user();
    if ($siswa) {
        if ($siswa->status === 'pending') {
            return redirect()->route('siswa.register-kategori');
        }
        if ($siswa->status === 'under_review') {
            return redirect()->route('siswa.pending');
        }
    }
    return view('siswa.dashboard');
})->middleware('auth:siswa')->name('siswa.dashboard');

Route::get('/siswa/pending', [App\Http\Controllers\siswa\SiswaController::class, 'showPending'])->middleware('auth:siswa')->name('siswa.pending');
Route::get('/siswa/payment', [App\Http\Controllers\siswa\SiswaController::class, 'showPayment'])->name('siswa.payment');
Route::get('/siswa/register-kategori', [App\Http\Controllers\siswa\SiswaController::class, 'showRegisterKategori'])->name('siswa.register-kategori');
Route::post('/siswa/payment', [App\Http\Controllers\siswa\SiswaController::class, 'submitPayment'])->name('siswa.payment.submit');

Route::get('/guru', function () {
    if (!auth()->user()->isGuru()) {
        return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
    }
    return view('guru.index');
})->middleware('auth:web')->name('guru.dashboard');

// Admin Dashboard & Pricing CRUD
Route::middleware(['auth:web'])->group(function () {
    Route::get('/admin', [\App\Http\Controllers\admin\AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/paket', [\App\Http\Controllers\admin\AdminController::class, 'inputPrice'])->name('admin.paket');
    Route::put('/admin/paket/{id}', [\App\Http\Controllers\admin\AdminController::class, 'updatePrice'])->name('admin.paket.update');
    Route::get('/admin/rekening', [\App\Http\Controllers\admin\AdminController::class, 'inputRekening'])->name('admin.rekening');
    Route::post('/admin/rekening', [\App\Http\Controllers\admin\AdminController::class, 'storeRekening'])->name('admin.rekening.store');
    Route::put('/admin/rekening/{id}', [\App\Http\Controllers\admin\AdminController::class, 'updateRekening'])->name('admin.rekening.update');
    Route::delete('/admin/rekening/{id}', [\App\Http\Controllers\admin\AdminController::class, 'deleteRekening'])->name('admin.rekening.delete');
    Route::get('/admin/siswa/approve', [\App\Http\Controllers\admin\AdminController::class, 'approvSiswa'])->name('admin.siswa.approve.index');
    Route::post('/admin/siswa/approve/{id}', [\App\Http\Controllers\admin\AdminController::class, 'submitApprovSiswa'])->name('admin.siswa.approve.submit');
    Route::get('/admin/siswa/detail/{id}', [\App\Http\Controllers\admin\AdminController::class, 'detailSiswa'])->name('admin.siswa.detail');
    Route::get('/admin/siswa', [\App\Http\Controllers\admin\AdminController::class, 'daftarSiswa'])->name('admin.siswa.daftar.index');
});

