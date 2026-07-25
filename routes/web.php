<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return view('/informasi/index');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Dashboard Routes
Route::get('/siswa', function () {
    return view('siswa.dashboard');
})->middleware('auth:siswa')->name('siswa.dashboard');

Route::get('/guru', function () {
    if (!auth()->user()->isGuru()) {
        return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
    }
    return view('guru.index');
})->middleware('auth:web')->name('guru.dashboard');

Route::get('/admin', function () {
    if (!auth()->user()->isAdmin()) {
        return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
    }
    return view('admin.index');
})->middleware('auth:web')->name('admin.dashboard');

