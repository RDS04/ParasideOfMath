<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Siswa\SiswaController;

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

// Protected Dashboard & Onboarding Routes
Route::get('/siswa', function () {
    $siswa = auth()->guard('siswa')->user();
    if ($siswa) {
        if ($siswa->status === 'active') {
            return view('siswa.dashboard');
        }
        if ($siswa->status === 'pending') {
            return redirect()->route('siswa.biodata');
        }
        if ($siswa->status === 'under_review' || !empty($siswa->bukti_transfer)) {
            return redirect()->route('siswa.pending');
        }
    }
    return view('siswa.dashboard');
})->middleware('auth:siswa')->name('siswa.dashboard');

Route::controller(SiswaController::class)->group(function () {
    Route::get('/siswa/biodata', 'showBiodata')->middleware('auth:siswa')->name('siswa.biodata');
    Route::post('/siswa/biodata', 'submitBiodata')->middleware('auth:siswa')->name('siswa.biodata.submit');
    Route::get('/siswa/register-kategori', 'showRegisterKategori')->middleware('auth:siswa')->name('siswa.register-kategori');
    Route::get('/siswa/payment', 'showPayment')->middleware('auth:siswa')->name('siswa.payment');
    Route::post('/siswa/payment', 'submitPayment')->middleware('auth:siswa')->name('siswa.payment.submit');
    Route::get('/siswa/pending', 'showPending')->middleware('auth:siswa')->name('siswa.pending');
    Route::get('/siswa/jadwal', 'showJadwal')->middleware('auth:siswa')->name('siswa.jadwal');

});
Route::controller(AuthController::class)->prefix('guru')->group(function () {
    Route::get('/register', 'showGuruRegisterForm')->name('guru.register');
    Route::post('/register', 'registerGuru')->name('guru.register.post');

    Route::middleware('auth:web')->group(function () {
        Route::get('/', function () {
            if (!auth()->user()->isGuru()) {
                return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
            }
            return view('guru.index');
        })->name('guru.dashboard');
    });
});
Route::controller(AdminController::class)->group(function () {
    Route::get('/admin', 'index')->name('admin.dashboard');
    Route::get('/admin/paket', 'inputPrice')->name('admin.paket');
    Route::put('/admin/paket/{id}', 'updatePrice')->name('admin.paket.update');
    Route::get('/admin/rekening', 'inputRekening')->name('admin.rekening');
    Route::post('/admin/rekening', 'storeRekening')->name('admin.rekening.store');
    Route::put('/admin/rekening/{id}', 'updateRekening')->name('admin.rekening.update');
    Route::delete('/admin/rekening/{id}', 'deleteRekening')->name('admin.rekening.delete');
    Route::get('/admin/mapel', 'inputMapel')->name('admin.mapel');
    Route::post('/admin/mapel', 'storeMapel')->name('admin.mapel.store');
    Route::put('/admin/mapel/{id}', 'updateMapel')->name('admin.mapel.update');
    Route::delete('/admin/mapel/{id}', 'deleteMapel')->name('admin.mapel.delete');
    Route::get('/admin/siswa/approve', 'approvSiswa')->name('admin.siswa.approve.index');
    Route::post('/admin/siswa/approve/{id}', 'submitApprovSiswa')->name('admin.siswa.approve.submit');
    Route::get('/admin/siswa/detail/{id}', 'detailSiswa')->name('admin.siswa.detail');
    Route::get('/admin/siswa', 'daftarSiswa')->name('admin.siswa.daftar.index');
    Route::get('/admin/siswa/tambah', 'tambahSiswa')->name('admin.siswa.tambah.index');
    Route::post('/admin/save-token', 'saveFcmToken')->name('admin.save_token');
    Route::get('/admin/notifications', 'getNotifications')->name('admin.notifications.get');
    Route::post('/admin/notifications/read', 'markNotificationsRead')->name('admin.notifications.read');
})->middleware('auth:web');

