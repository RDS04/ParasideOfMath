<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Siswa\SiswaController;
use App\Http\Controllers\Guru\GuruController;




// Authentication Routes (Siswa & General)
Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'information')->name('dashboard');
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::get('/register', 'showLoginForm')->name('register');
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
        if ($siswa->status === 'rejected') {
            $siswa->status = 'pending';
            $siswa->save();
            return redirect()->route('siswa.biodata')
                ->with('error', 'Pendaftaran Anda sebelumnya ditolak oleh Admin. Seluruh data registrasi telah dibersihkan. Silakan isi kembali biodata Anda dari awal.');
        }
        if ($siswa->status === 'pending') {
            if (empty($siswa->biodata) || !is_array($siswa->biodata) || count($siswa->biodata) === 0) {
                return redirect()->route('siswa.biodata');
            } else {
                return redirect()->route('siswa.register-kategori');
            }
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
    Route::get('/siswa/akademik', 'showAkademik')->middleware('auth:siswa')->name('siswa.akademik');
    Route::get('/siswa/invoice', 'showInvoice')->middleware('auth:siswa')->name('siswa.invoice');
    Route::get('/siswa/riwayat', 'showRiwayat')->middleware('auth:siswa')->name('siswa.riwayat');
});
Route::controller(AuthController::class)->prefix('guru')->group(function () {
    Route::get('/register', 'showGuruRegisterForm')->name('guru.register');
    Route::post('/register', 'registerGuru')->name('guru.register.post');
});

Route::middleware('auth:web')->prefix('guru')->controller(GuruController::class)->group(function () {
    Route::get('/', 'index')->name('guru.dashboard');
    Route::get('/biodata', 'showBiodata')->name('guru.biodata');
    Route::get('/biodata/edit', 'editBiodata')->name('guru.biodata.edit');
    Route::post('/biodata/edit', 'updateBiodata')->name('guru.biodata.update');
    Route::get('/profil', 'editProfil')->name('guru.profil');
    Route::post('/profil', 'updateProfil')->name('guru.profil.update');
    Route::get('/jadwal', 'showJadwal')->name('guru.jadwal');
    Route::get('/siswa', 'showSiswa')->name('guru.siswa');
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
    Route::post('/admin/siswa/reject/{id}', 'rejectSiswa')->name('admin.siswa.reject.submit');
    Route::get('/admin/siswa/detail/{id}', 'detailSiswa')->name('admin.siswa.detail');
    Route::post('/admin/siswa/update-bimbel-days/{id}', 'updateBimbelDays')->name('admin.siswa.update-bimbel-days');
    Route::post('/admin/siswa/assign-tutor/{id}', 'assignTutor')->name('admin.siswa.assign-tutor');
    Route::get('/admin/kalender', 'showKalender')->name('admin.kalender');
    Route::get('/admin/foto', 'showFotoMenu')->name('admin.foto.index');
    Route::post('/admin/foto/hero', 'updateHeroFoto')->name('admin.foto.hero.update');
    Route::delete('/admin/foto/hero/{filename}', 'deleteHeroFotoSingle')->name('admin.foto.hero.delete.single');
    Route::get('/admin/galeri', 'showGaleri')->name('admin.galeri.index');
    Route::post('/admin/galeri/update', 'updateGaleriFoto')->name('admin.galeri.update');
    Route::post('/admin/galeri/store-extra', 'storeGaleriTambahan')->name('admin.galeri.extra.store');
    Route::post('/admin/galeri/delete', 'deleteGaleriFoto')->name('admin.galeri.delete');
    Route::get('/admin/siswa', 'daftarSiswa')->name('admin.siswa.daftar.index');
    Route::get('/admin/guru', 'daftarGuru')->name('admin.guru.daftar.index');
    Route::get('/admin/guru/detail/{id}', 'detailGuru')->name('admin.guru.detail');
    Route::get('/admin/siswa/tambah', 'tambahSiswa')->name('admin.siswa.tambah.index');
    Route::get('/admin/riwayat-pembayaran', 'allRiwayatPayment')->name('admin.riwayat-pembayaran');
    Route::get('/admin/laporan-pendapatan', 'laporanPendapatan')->name('admin.laporan-pendapatan');
    Route::get('/admin/laporan-pendapatan/export-excel', 'exportRevenueExcel')->name('admin.laporan-pendapatan.export.excel');
    Route::get('/admin/laporan-pendapatan/export-pdf', 'exportRevenuePdf')->name('admin.laporan-pendapatan.export.pdf');
    Route::post('/admin/save-token', 'saveFcmToken')->name('admin.save_token');
    Route::get('/admin/notifications', 'getNotifications')->name('admin.notifications.get');
    Route::post('/admin/notifications/read', 'markNotificationsRead')->name('admin.notifications.read');
})->middleware('auth:web');

// Realtime Chat API and Admin Panel
Route::get('/chat/messages', [\App\Http\Controllers\Chat\ChatController::class, 'getMessages']);
Route::post('/chat/send', [\App\Http\Controllers\Chat\ChatController::class, 'sendMessage']);

Route::middleware('auth:web')->group(function () {
    Route::get('/admin/chet', [\App\Http\Controllers\Chat\ChatController::class, 'adminChatPage'])->name('admin.chat');
    Route::get('/admin/chat/sessions', [\App\Http\Controllers\Chat\ChatController::class, 'getChatSessions']);
    Route::get('/admin/chat/messages/{session_id}', [\App\Http\Controllers\Chat\ChatController::class, 'getSessionMessages']);
    Route::post('/admin/chat/send', [\App\Http\Controllers\Chat\ChatController::class, 'adminSendMessage']);
});

