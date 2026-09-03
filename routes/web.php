<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Siswa\SiswaController;
use App\Http\Controllers\Guru\GuruController;
use App\Http\Controllers\SettingsController;




// Authentication Routes (Siswa & General)
Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'information')->name('dashboard');
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::get('/register', 'showLoginForm')->name('register');
    Route::post('/register', 'register')->name('register.post');
    Route::get('/verify-otp', 'showVerifyOtp')->name('verify.otp');
    Route::post('/verify-otp', 'verifyOtp')->name('verify.otp.post');
    Route::post('/resend-otp', 'resendOtp')->name('resend.otp');
    Route::post('/logout', 'logout')->name('logout');
});

// Admin Registration Routes
Route::controller(AdminAuthController::class)->group(function () {
    Route::get('/admin/register', 'showRegistrationForm')->name('admin.register');
    Route::post('/admin/register', 'register')->name('admin.register.post');
});

// Device Log Public & Master Routes
Route::post('/api/device-log/store', [\App\Http\Controllers\DeviceLogController::class, 'store']);

Route::middleware('auth:web')->group(function () {
    Route::get('/master', function () {
        $user = auth()->user();
        if (!$user || !$user->isMaster()) {
            abort(403, 'Akses Ditolak! Halaman Master Data Perangkat HP ini khusus dan hanya dapat diakses oleh Akun Master.');
        }
        return view('master.index');
    })->name('master.index');

    Route::get('/api/device-log/list', [\App\Http\Controllers\DeviceLogController::class, 'getLogs']);
    Route::delete('/api/device-log/delete/{id}', [\App\Http\Controllers\DeviceLogController::class, 'destroy']);
    Route::delete('/api/device-log/clear-all', [\App\Http\Controllers\DeviceLogController::class, 'clearAll']);
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
        if ($siswa->status === 'nonaktif') {
            return redirect()->route('siswa.pending');
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
    Route::get('/siswa/tambah-mapel', 'showTambahPelajaran')->middleware('auth:siswa')->name('siswa.tambah-pelajaran');
    Route::post('/siswa/tambah-mapel', 'simpanMapel')->middleware('auth:siswa')->name('siswa.tambah-mapel');
    Route::put('/siswa/tambah-mapel', 'editMapel')->middleware('auth:siswa')->name('siswa.edit-mapel');
    Route::delete('/siswa/tambah-mapel', 'hapusMapel')->middleware('auth:siswa')->name('siswa.hapus-mapel');
    Route::get('/siswa/payment', 'showPayment')->middleware('auth:siswa')->name('siswa.payment');
    Route::get('/siswa/bukti-bayar', 'showBuktiBayar')->middleware('auth:siswa')->name('siswa.bukti-bayar');
    Route::post('/siswa/bukti-bayar', 'submitBuktiBayar')->middleware('auth:siswa')->name('siswa.bukti-bayar.submit');
    Route::post('/siswa/payment', 'submitPayment')->middleware('auth:siswa')->name('siswa.payment.submit');
    Route::get('/siswa/pending', 'showPending')->middleware('auth:siswa')->name('siswa.pending');
    Route::get('/siswa/jadwal', 'showJadwal')->middleware('auth:siswa')->name('siswa.jadwal');
    Route::get('/siswa/akademik', 'showAkademik')->middleware('auth:siswa')->name('siswa.akademik');
    Route::get('/siswa/invoice', 'showInvoice')->middleware('auth:siswa')->name('siswa.invoice');
    Route::post('/siswa/invoice/pay', 'submitInvoicePayment')->middleware('auth:siswa')->name('siswa.invoice.pay');
    Route::get('/siswa/riwayat', 'showRiwayat')->middleware('auth:siswa')->name('siswa.riwayat');
    Route::get('/siswa/ujian', 'showUjian')->middleware('auth:siswa')->name('siswa.ujian');
    Route::post('/siswa/ujian/submit', 'submitUjian')->middleware('auth:siswa')->name('siswa.ujian.submit');
    Route::get('/siswa/transkip-nilai', 'showTranskipNilai')->middleware('auth:siswa')->name('siswa.transkip-nilai');

});
Route::controller(AuthController::class)->prefix('guru')->group(function () {
    Route::get('/register', 'showGuruRegisterForm')->name('guru.register');
    Route::post('/register', 'registerGuru')->name('guru.register.post');
    Route::get('/pending', 'showGuruPending')->name('guru.pending');
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

    Route::get('/ujian', 'showUjianGuru')->name('guru.ujian.index');
    Route::post('/ujian/assign', 'assignUjianGuru')->name('guru.ujian.assign');
    Route::delete('/ujian/unassign', 'unassignUjianGuru')->name('guru.ujian.unassign');

    Route::get('/bank-soal', 'bankSoal')->name('guru.bank-soal.index');
    Route::get('/bank-soal/kelola/{id}', 'kelolaBankSoal')->name('guru.bank-soal.kelola');
    Route::get('/list-soal', 'listSoal')->name('guru.list-soal.index');
    Route::get('/list-soal/detail/{id}', 'detailListSoal')->name('guru.list-soal.detail');

    Route::get('/list-soal/ajax/sub-kategori', 'ajaxSubKategoriSoal')->name('guru.list-soal.ajax.sub');
    Route::get('/list-soal/ajax/mapel', 'ajaxMapelSoal')->name('guru.list-soal.ajax.mapel');

    Route::post('/bank-soal/kategori', 'storeKategoriSoal')->name('guru.bank-soal.kategori.store');
    Route::put('/bank-soal/kategori/{id}', 'updateKategoriSoal')->name('guru.bank-soal.kategori.update');
    Route::delete('/bank-soal/kategori/{id}', 'deleteKategoriSoal')->name('guru.bank-soal.kategori.delete');
    Route::post('/bank-soal/soal', 'storeSoal')->name('guru.bank-soal.soal.store');
    Route::post('/bank-soal/import', 'importSoal')->name('guru.bank-soal.import');
    Route::post('/bank-soal/import/preview', 'previewImportSoal')->name('guru.bank-soal.import.preview');
    Route::post('/bank-soal/import/confirm', 'confirmImportSoal')->name('guru.bank-soal.import.confirm');
    Route::post('/bank-soal/import/cancel', 'cancelImportSoal')->name('guru.bank-soal.import.cancel');
    Route::get('/bank-soal/template', 'downloadTemplateSoal')->name('guru.bank-soal.template');
    Route::put('/bank-soal/soal/{id}', 'updateSoal')->name('guru.bank-soal.soal.update');
    Route::delete('/bank-soal/soal/{id}', 'deleteSoal')->name('guru.bank-soal.soal.delete');
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
    Route::get('/admin/siswa/requests', 'requestTambahMapel')->name('admin.siswa.requests.index');
    Route::post('/admin/siswa/request/{id}', 'approveRequestTambahMapel')->name('admin.siswa.requests.approve');
    Route::post('/admin/siswa/request/{id}/reject', 'rejectRequestTambahMapel')->name('admin.siswa.requests.reject');
    Route::get('/admin/siswa/detail/{id}', 'detailSiswa')->name('admin.siswa.detail');
    Route::post('/admin/siswa/update-bimbel-days/{id}', 'updateBimbelDays')->name('admin.siswa.update-bimbel-days');
    Route::post('/admin/siswa/update-jam-bimbel/{id}', 'updateJamBimbel')->name('admin.siswa.update-jam-bimbel');
    Route::post('/admin/siswa/update-tanggal-mulai/{id}', 'updateTanggalMulai')->name('admin.siswa.update-tanggal-mulai');
    Route::post('/admin/siswa/assign-tutor/{id}', 'assignTutor')->name('admin.siswa.assign-tutor');
    Route::post('/admin/siswa/add-catatan-bon/{id}', 'addCatatanBon')->name('admin.siswa.add-catatan-bon');
    Route::delete('/admin/siswa/delete-catatan-bon/{id}/{itemId}', 'deleteCatatanBon')->name('admin.siswa.delete-catatan-bon');
    Route::post('/admin/riwayat-pembayaran/approve/{id}', 'approveRiwayatPembayaran')->name('admin.riwayat-pembayaran.approve');
    Route::post('/admin/riwayat-pembayaran/reject/{id}', 'rejectRiwayatPembayaran')->name('admin.riwayat-pembayaran.reject');
    Route::get('/admin/kalender', 'showKalender')->name('admin.kalender');
    Route::get('/admin/foto', 'showFotoMenu')->name('admin.foto.index');
    Route::post('/admin/foto/hero', 'updateHeroFoto')->name('admin.foto.hero.update');
    Route::delete('/admin/foto/hero/{filename}', 'deleteHeroFotoSingle')->name('admin.foto.hero.delete.single');
    Route::get('/admin/galeri', 'showGaleri')->name('admin.galeri.index');
    Route::post('/admin/galeri/update', 'updateGaleriFoto')->name('admin.galeri.update');
    Route::post('/admin/galeri/store-extra', 'storeGaleriTambahan')->name('admin.galeri.extra.store');
    Route::post('/admin/galeri/delete', 'deleteGaleriFoto')->name('admin.galeri.delete');

    // Kelola Foto Guru & Banner Landing
    Route::get('/admin/foto-guru', 'showInputFotoGuru')->name('admin.foto-guru.index');
    Route::post('/admin/foto-guru/banner', 'storeBannerGuruFoto')->name('admin.foto-guru.banner.store');
    Route::delete('/admin/foto-guru/banner/{filename}', 'deleteBannerGuruFoto')->name('admin.foto-guru.banner.delete');
    Route::post('/admin/foto-guru/profil/{id}', 'storeProfilGuruFoto')->name('admin.foto-guru.profil.store');

    // YouTube Tutorial Link Management Routes
    Route::get('/admin/link', 'showYoutubeLink')->name('admin.link');
    Route::post('/admin/link', 'storeYoutubeLink')->name('admin.link.store');
    Route::put('/admin/link/{id}', 'updateYoutubeLink')->name('admin.link.update');
    Route::delete('/admin/link/{id}', 'deleteYoutubeLink')->name('admin.link.delete');
    Route::get('/admin/siswa', 'daftarSiswa')->name('admin.siswa.daftar.index');
    Route::get('/admin/guru', 'daftarGuru')->name('admin.guru.daftar.index');
    Route::get('/admin/guru/approve', 'approvGuru')->name('admin.guru.approve.index');
    Route::post('/admin/guru/toggle-register', 'toggleGuruRegisterStatus')->name('admin.guru.toggle-register');
    Route::get('/admin/guru/detail/{id}', 'detailGuru')->name('admin.guru.detail');
    Route::post('/admin/guru/update-max-siswa/{id}', 'updateMaxSiswa')->name('admin.guru.update-max-siswa');
    Route::post('/admin/guru/approve/{id}', 'approveGuru')->name('admin.guru.approve');
    Route::post('/admin/guru/reject/{id}', 'rejectGuru')->name('admin.guru.reject');
    Route::delete('/admin/guru/delete/{id}', 'deleteGuru')->name('admin.guru.delete');
    Route::get('/admin/siswa/tambah', 'tambahSiswa')->name('admin.siswa.tambah.index');
    Route::get('/admin/riwayat-pembayaran', 'allRiwayatPayment')->name('admin.riwayat-pembayaran');
    Route::get('/admin/laporan-pendapatan', 'laporanPendapatan')->name('admin.laporan-pendapatan');
    Route::get('/admin/laporan-pendapatan/export-excel', 'exportRevenueExcel')->name('admin.laporan-pendapatan.export.excel');
    Route::get('/admin/laporan-pendapatan/export-pdf', 'exportRevenuePdf')->name('admin.laporan-pendapatan.export.pdf');
    Route::post('/admin/save-token', 'saveFcmToken')->name('admin.save_token');
    Route::get('/admin/notifications', 'getNotifications')->name('admin.notifications.get');
    Route::post('/admin/notifications/read', 'markNotificationsRead')->name('admin.notifications.read');

    Route::get('/admin/bank-soal', 'bankSoal')->name('admin.bank-soal.index');
    Route::get('/admin/bank-soal/kelola/{id}', 'kelolaBankSoalAdmin')->name('admin.bank-soal.kelola');
    Route::post('/admin/bank-soal/kategori', 'storeKategoriSoalAdmin')->name('admin.bank-soal.kategori.store');
    Route::put('/admin/bank-soal/kategori/{id}', 'updateKategoriSoalAdmin')->name('admin.bank-soal.kategori.update');
    Route::delete('/admin/bank-soal/kategori/{id}', 'deleteKategoriSoalAdmin')->name('admin.bank-soal.kategori.delete');
    Route::post('/admin/bank-soal/soal', 'storeSoalAdmin')->name('admin.bank-soal.soal.store');
    Route::put('/admin/bank-soal/soal/{id}', 'updateSoalAdmin')->name('admin.bank-soal.soal.update');
    Route::delete('/admin/bank-soal/soal/{id}', 'deleteSoalAdmin')->name('admin.bank-soal.soal.delete');
    Route::post('/admin/bank-soal/import/preview', 'previewImportSoalAdmin')->name('admin.bank-soal.import.preview');
    Route::post('/admin/bank-soal/import/confirm', 'confirmImportSoalAdmin')->name('admin.bank-soal.import.confirm');
    Route::post('/admin/bank-soal/import/cancel', 'cancelImportSoalAdmin')->name('admin.bank-soal.import.cancel');
    Route::get('/admin/bank-soal/template', 'downloadTemplateSoalAdmin')->name('admin.bank-soal.template');

    Route::get('/admin/bank-soal/ajax/sub-kategori', 'ajaxSubKategoriSoal')->name('admin.bank-soal.ajax.sub');
    Route::get('/admin/bank-soal/ajax/mapel', 'ajaxMapelSoal')->name('admin.bank-soal.ajax.mapel');

    Route::get('/admin/ujian', 'showUjianAdmin')->name('admin.ujian.index');
    Route::post('/admin/ujian/assign', 'assignUjianAdmin')->name('admin.ujian.assign');
    Route::delete('/admin/ujian/unassign', 'unassignUjianAdmin')->name('admin.ujian.unassign');

    Route::post('/admin/siswa/toggle-status/{id}', 'toggleStatusSiswa')->name('admin.siswa.toggle-status');
})->middleware('auth:web');

// Realtime Chat API and Admin Panel
Route::get('/chat/messages', [\App\Http\Controllers\Chat\ChatController::class, 'getMessages']);
Route::post('/chat/send', [\App\Http\Controllers\Chat\ChatController::class, 'sendMessage']);

Route::middleware('auth:web')->group(function () {
    Route::get('/admin/chat', [\App\Http\Controllers\Chat\ChatController::class, 'adminChatPage'])->name('admin.chat');
    Route::get('/admin/chat/sessions', [\App\Http\Controllers\Chat\ChatController::class, 'getChatSessions']);
    Route::get('/admin/chat/messages/{session_id}', [\App\Http\Controllers\Chat\ChatController::class, 'getSessionMessages']);
    Route::post('/admin/chat/send', [\App\Http\Controllers\Chat\ChatController::class, 'adminSendMessage']);
});

Route::prefix('pengaturan')->name('pengaturan.')->middleware('auth:web,siswa')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::put('/email', [SettingsController::class, 'updateEmail'])->name('email');
    Route::put('/password', [SettingsController::class, 'updatePassword'])->name('password');
});

Route::middleware('auth:siswa')->prefix('siswa/chat')->name('siswa.chat.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Chat\ChatController::class, 'siswaChatPage'])->name('index');
    Route::get('/contacts', [\App\Http\Controllers\Chat\ChatController::class, 'siswaContacts'])->name('contacts');
    Route::get('/messages/{session_id}', [\App\Http\Controllers\Chat\ChatController::class, 'siswaMessages'])->name('messages');
    Route::post('/send', [\App\Http\Controllers\Chat\ChatController::class, 'siswaSendMessage'])->name('send');
});

Route::middleware('auth:web')->prefix('guru/chat')->name('guru.chat.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Chat\ChatController::class, 'guruChatPage'])->name('index');
    Route::get('/contacts', [\App\Http\Controllers\Chat\ChatController::class, 'guruContacts'])->name('contacts');
    Route::get('/messages/{session_id}', [\App\Http\Controllers\Chat\ChatController::class, 'guruMessages'])->name('messages');
    Route::post('/send', [\App\Http\Controllers\Chat\ChatController::class, 'guruSendMessage'])->name('send');
});

// Helper Route untuk membersihkan cache di server hosting (cPanel / Shared Hosting)
Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return 'Success! Cache Laravel (route, config, view, app) di server hosting berhasil dibersihkan.';
});

