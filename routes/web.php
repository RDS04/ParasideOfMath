<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
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
    return view('siswa.dashboard');
})->middleware('auth:siswa')->name('siswa.dashboard');

Route::get('/siswa/payment', function () {
    return view('siswa.payment');
})->name('siswa.payment');

Route::get('/siswa/register-kategori', function () {
    return view('siswa.regisKategory');
})->name('siswa.register-kategori');

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
});

