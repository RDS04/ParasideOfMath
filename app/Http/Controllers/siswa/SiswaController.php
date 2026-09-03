<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PaketBelajar;
use App\Models\KategoriSoal;
use App\Models\BankSoal;
use App\Models\HasilUjian;
use Illuminate\Http\Request;
use App\Models\RiwayatPembayaran;

class SiswaController extends Controller
{
    /**
     * Tampilkan Halaman Form Biodata Siswa.
     */
    public function showBiodata(Request $request)
    {
        $siswa = auth()->guard('siswa')->user();
        if ($siswa) {
            if ($siswa->status === 'active') {
                return redirect()->route('siswa.dashboard');
            }
            if ($siswa->status === 'nonaktif') {
                return redirect()->route('siswa.pending');
            }
            if ($siswa->status === 'rejected') {
                $siswa->status = 'pending';
                $siswa->save();
                session()->flash('error', 'Pendaftaran Anda sebelumnya ditolak oleh Admin. Seluruh data registrasi & biodata telah dibersihkan. Silakan isi kembali biodata Anda dari awal.');
            }
            if ($siswa->status === 'under_review' || !empty($siswa->bukti_transfer)) {
                return redirect()->route('siswa.pending');
            }
            // Continuous DB check: If biodata is already filled in DB, proceed to package selection
            if (!empty($siswa->biodata) && is_array($siswa->biodata) && count($siswa->biodata) > 0 && !$request->has('edit')) {
                return redirect()->route('siswa.register-kategori')
                    ->with('info', 'Biodata Anda telah tersimpan di database. Selesaikan pendaftaran Anda dan lanjutkan progres pendaftaran!');
            }
        }
        return view('siswa.biodata', compact('siswa'));
    }

    /**
     * Submit Halaman Form Biodata Siswa dan Lanjut ke Pilih Kategori.
     */
    public function submitBiodata(Request $request)
    {
        $siswa = auth()->guard('siswa')->user();
        if ($siswa) {
            if ($request->filled('no_hp')) {
                $siswa->whatsapp = $request->input('no_hp');
            }
            if ($request->filled('sekolah')) {
                $siswa->sekolah = $request->input('sekolah');
            }
            if ($request->filled('nama_lengkap')) {
                $siswa->name = $request->input('nama_lengkap');
            }
            // Save all other form inputs in the biodata array in database
            $siswa->biodata = $request->except(['_token', 'no_hp', 'sekolah', 'nama_lengkap']);
            $siswa->save();
        }

        return redirect()->route('siswa.register-kategori')
            ->with('info', 'Selesaikan pendaftaran Anda dan lanjutkan progres pendaftaran! Biodata Anda telah berhasil tersimpan di database.');
    }

    /**
     * Tampilkan Halaman Pilih Kategori Paket Belajar.
     */
    public function showRegisterKategori()
    {
        $siswa = auth()->guard('siswa')->user();
        $isTambahMode = false;
        $activeMapels = [];
        $pendingMapels = [];

        if ($siswa) {
            if ($siswa->status === 'active') {
                // ── Mode "Tambah Pelajaran" untuk siswa yang sudah aktif ──
                $isTambahMode = true;

                $biodata       = $siswa->biodata ?? [];
                $activeMapels  = is_array($biodata['mapel_jadwal'] ?? null) ? $biodata['mapel_jadwal'] : [];
                $pendingMapels = is_array($biodata['pending_mapel_jadwal'] ?? null) ? $biodata['pending_mapel_jadwal'] : [];

                if (empty($activeMapels) && $siswa->tipe_paket && preg_match('/Mapel:\s*([^)|]+)/i', $siswa->tipe_paket, $m)) {
                    $activeMapels = array_map('trim', explode(',', $m[1]));
                }
            } else {
                if ($siswa->status === 'nonaktif') {
                    return redirect()->route('siswa.pending');
                }
                // ── Mode pendaftaran siswa baru (logika lama tetap) ──
                if ($siswa->status === 'under_review' || !empty($siswa->bukti_transfer)) {
                    return redirect()->route('siswa.pending');
                }
                if (empty($siswa->biodata) || !is_array($siswa->biodata) || count($siswa->biodata) === 0) {
                    return redirect()->route('siswa.biodata')
                        ->with('error', 'Silakan isi biodata Anda terlebih dahulu sebelum memilih paket bimbel.');
                }
            }
        }

        // Mapel yang bisa dipilih: exclude yang sudah aktif/pending (khusus mode tambah)
        $availableMapels = \App\Models\Mapel::all()->filter(function ($m) use ($activeMapels, $pendingMapels) {
            return !in_array($m->nama_mapel, $activeMapels) && !in_array($m->nama_mapel, $pendingMapels);
        })->values();

        $paket = $isTambahMode ? ($siswa->paket ?: \App\Models\PaketBelajar::first()) : null;

        return view('siswa.regisKategory', compact(
            'siswa', 'isTambahMode', 'availableMapels', 'activeMapels', 'paket'
        ));
    }

    /**
     * Tampilkan Halaman Status Pembayaran Pending.
     */
    public function showPending()
    {
        $siswa = auth()->guard('siswa')->user();
        if ($siswa) {
            if ($siswa->status === 'active') {
                return redirect()->route('siswa.dashboard');
            }
            if ($siswa->status === 'rejected') {
                $siswa->status = 'pending';
                $siswa->save();
                return redirect()->route('siswa.biodata')
                    ->with('error', 'Pendaftaran Anda sebelumnya ditolak oleh Admin. Seluruh data registrasi & biodata telah dibersihkan. Silakan isi kembali biodata Anda dari awal.');
            }
            // Siswa nonaktif tetap harus mendarat di halaman ini, apapun kondisi biodatanya
            if ($siswa->status !== 'nonaktif' && empty($siswa->biodata)) {
                return redirect()->route('siswa.biodata');
            }
        }

        $paket = $siswa ? PaketBelajar::find($siswa->paket_id) : null;
        $biodata = $siswa->biodata ?? [];

        // Cek apakah Admin sudah menentukan hari bimbingan
        $hariPerMapel = $biodata['hari_per_mapel'] ?? [];
        $hariSudahDitentukan = false;

        if (!empty($hariPerMapel) && is_array($hariPerMapel)) {
            foreach ($hariPerMapel as $mIdx => $hList) {
                if (is_array($hList) && count(array_filter($hList)) > 0) {
                    $hariSudahDitentukan = true;
                    break;
                }
            }
        }

        $sudahUploadBukti = ($siswa->status === 'under_review' || !empty($siswa->bukti_transfer));

        if ($siswa && $siswa->status === 'nonaktif') {
            $waMessage = "Halo Admin Paradise of Math,\n\nSaya ingin menanyakan terkait akun belajar saya yang saat ini berstatus *nonaktif*. Berikut data diri saya:\n\n";
            $waMessage .= "• Nama: " . $siswa->name . "\n";
            $waMessage .= "• Email: " . $siswa->email . "\n";
            if ($paket) {
                $waMessage .= "• Paket Belajar: " . $paket->nama_paket . " (" . $paket->kategori . ")\n";
            }
            $waMessage .= "\nMohon informasi terkait alasan penonaktifan dan apakah akun saya bisa diaktifkan kembali. Terima kasih!";
        } else {
            $waMessage = "Halo Admin Paradise of Math,\n\nSaya telah melakukan pendaftaran bimbingan belajar. Berikut adalah rincian data diri saya:\n\n";
            if ($siswa) {
                $waMessage .= "• Nama: " . $siswa->name . "\n";
                $waMessage .= "• Email: " . $siswa->email . "\n";
            }
            if ($paket) {
                $waMessage .= "• Paket Belajar: " . $paket->nama_paket . " (" . $paket->kategori . ")\n";
            }
            if ($siswa && $siswa->tipe_paket) {
                $waMessage .= "• Pilihan Kelas: " . $siswa->tipe_paket . "\n";
            }
            $waMessage .= "\nMohon informasi terkait penentuan jadwal bimbingan belajar saya. Terima kasih!";
        }

        $waUrl = "https://wa.me/6289675053537?text=" . rawurlencode($waMessage);

        return view('siswa.pending', compact('siswa', 'paket', 'waUrl', 'hariSudahDitentukan', 'sudahUploadBukti'));
    }

    /**
     * Tampilkan Halaman Checkout Pembayaran.
     */
    public function showPayment(Request $request)
    {
        $siswa = auth()->guard('siswa')->user();
        if ($siswa) {
            if ($siswa->status === 'nonaktif') {
                return redirect()->route('siswa.pending');
            }
            if ($siswa->status === 'rejected') {
                return redirect()->route('siswa.biodata');
            }
            if ($siswa->status === 'under_review') {
                return redirect()->route('siswa.pending');
            }
            // Bukti transfer lama cuma relevan kalau siswa BELUM aktif.
            // Siswa aktif yang lagi nambah mapel tidak boleh ke-redirect ke halaman pending.
            if ($siswa->status !== 'active' && !empty($siswa->bukti_transfer)) {
                return redirect()->route('siswa.pending');
            }
        }

        $paketId   = $request->input('paket_id');
        $tipePaket = $request->input('tipe_paket', '1');


        $paket = PaketBelajar::find($paketId) ?? PaketBelajar::first();

        $detailString = '';
        if ($tipePaket == '1')      $detailString = $paket->detail_1;
        elseif ($tipePaket == '2')  $detailString = $paket->detail_2;
        elseif ($tipePaket == '3')  $detailString = $paket->detail_3;
        elseif ($tipePaket == '4')  $detailString = $paket->detail_4;

        // Harga per sesi (dari detail paket)
        $harga = $this->extractPrice($detailString, $paket ? $paket->harga_max : 450000);

        // ── Hitung total sesi dari field baru: sesi[0], sesi[1], … ──
        $sesiPerMapel = $request->input('sesi', []);  // array indexed by mapel
        $isTambahMode = $siswa && $siswa->status === 'active';

        if (empty($sesiPerMapel)) {
            $mapels = $request->input('mapel', []);
            if (is_array($mapels) && !empty($mapels)) {
                foreach ($mapels as $m) {
                    if (is_string($m) && preg_match('/(\d+)x/i', $m, $matches)) {
                        $sesiPerMapel[] = (int) $matches[1];
                    } else {
                        $sesiPerMapel[] = $isTambahMode ? 1 : 4;
                    }
                }
            }
        }
        if (empty($sesiPerMapel) && isset($siswa->biodata['pending_sesi_per_mapel'])) {
            $sesiPerMapel = $siswa->biodata['pending_sesi_per_mapel'];
        }
        if (empty($sesiPerMapel) && isset($siswa->biodata['sesi_per_mapel'])) {
            $sesiPerMapel = $siswa->biodata['sesi_per_mapel'];
        }

        $totalSesi = 0;
        if (is_array($sesiPerMapel)) {
            foreach ($sesiPerMapel as $s) {
                $totalSesi += (int) $s;
            }
        }
        $totalSesi = $totalSesi > 0 ? $totalSesi : 1;

        // Total harga = harga_per_sesi × total_sesi
        $total = $harga * $totalSesi;

        // Kumpulkan data mapel-jadwal untuk ditampilkan di payment review
        $mapelJadwal  = $request->input('mapel_jadwal', []);   // ['Fisika', 'Biologi', …]
        if (empty($mapelJadwal)) {
            foreach ((array) $request->input('mapel', []) as $rm) {
                if (is_string($rm) && trim($rm) !== '') {
                    $clean = trim(preg_replace('/\s+\d+x$/i', '', $rm));
                    if ($clean !== '') {
                        $mapelJadwal[] = $clean;
                    }
                }
            }
        }
        if (empty($mapelJadwal) && isset($siswa->biodata['pending_mapel_jadwal'])) {
            $mapelJadwal = $siswa->biodata['pending_mapel_jadwal'];
        }
        if (empty($mapelJadwal) && isset($siswa->biodata['mapel_jadwal'])) {
            $mapelJadwal = $siswa->biodata['mapel_jadwal'];
        }

        $hariPerMapel = $request->input('hari', []);            // [0 => [1=>'Senin', 2=>'Rabu'], …]
        $tanggalArr   = $request->input('tanggal_mulai', []);   // [0 => '2026-08-10', …]

        if ($siswa) {
            $biodata = $siswa->biodata ?? [];
            $existingPendingMapels = is_array($biodata['pending_mapel_jadwal'] ?? null) ? $biodata['pending_mapel_jadwal'] : [];
            $existingPendingSesi   = is_array($biodata['pending_sesi_per_mapel'] ?? null) ? $biodata['pending_sesi_per_mapel'] : [];

            $mergedPendingMapels = [];
            $mergedPendingSesi   = [];
            $defaultSesi = $isTambahMode ? 8 : 4;

            foreach ($existingPendingMapels as $index => $name) {
                $mergedPendingMapels[] = $name;
                $mergedPendingSesi[]   = isset($existingPendingSesi[$index]) ? (int) $existingPendingSesi[$index] : $defaultSesi;
            }

            foreach ((array) $mapelJadwal as $index => $name) {
                if (!in_array($name, $mergedPendingMapels, true)) {
                    $mergedPendingMapels[] = $name;
                    $mergedPendingSesi[]   = isset($sesiPerMapel[$index]) ? (int) $sesiPerMapel[$index] : $defaultSesi;
                }
            }

            $biodata['pending_mapel_jadwal'] = array_values($mergedPendingMapels);
            $biodata['pending_sesi_per_mapel'] = array_values($mergedPendingSesi);
            $biodata['pending_jumlah_pertemuan'] = array_sum($mergedPendingSesi);
            $siswa->biodata = $biodata;
            $siswa->save();
        }

        // Normalisasi: pastikan tanggalArr adalah array string
        if (!is_array($tanggalArr)) {
            $tanggalArr = [$tanggalArr];
        }

        $banks    = \App\Models\Rekening::where('tipe', 'bank')->get();
        $ewallets = \App\Models\Rekening::where('tipe', 'ewallet')->get();

        return view('siswa.payment', compact(
            'paket', 'detailString', 'harga', 'total',
            'banks', 'ewallets', 'totalSesi',
            'mapelJadwal', 'hariPerMapel', 'tanggalArr', 'sesiPerMapel'
        ));
    }

    /**
     * Tampilkan Halaman Tambah Pelajaran Siswa.
     */
    public function showTambahPelajaran()
    {
        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('login');
        }
        if ($siswa->status === 'nonaktif') {
            return redirect()->route('siswa.pending');
        }
        if ($siswa->status === 'rejected') {
            $siswa->status = 'pending';
            $siswa->save();
            return redirect()->route('siswa.biodata')
                ->with('error', 'Pendaftaran Anda sebelumnya ditolak oleh Admin. Silakan isi kembali biodata Anda dan lanjutkan pendaftaran seperti biasa.');
        }
        if ($siswa->status === 'under_review' || $siswa->status !== 'active') {
            return redirect()->route('siswa.pending');
        }

        $biodata = $siswa->biodata ?? [];

        $activeMapels        = is_array($biodata['mapel_jadwal'] ?? null) ? $biodata['mapel_jadwal'] : [];
        $activeSesiPerMapel   = is_array($biodata['sesi_per_mapel'] ?? null) ? $biodata['sesi_per_mapel'] : [];
        $pendingMapels       = is_array($biodata['pending_mapel_jadwal'] ?? null) ? $biodata['pending_mapel_jadwal'] : [];
        $pendingSesiPerMapel = is_array($biodata['pending_sesi_per_mapel'] ?? null) ? $biodata['pending_sesi_per_mapel'] : [];

        if (empty($activeMapels) && $siswa->tipe_paket && preg_match('/Mapel:\s*([^)|]+)/i', $siswa->tipe_paket, $m)) {
            $activeMapels = array_map('trim', explode(',', $m[1]));
        }

        $mapels = $pendingMapels;
        $sesiPerMapel = $pendingSesiPerMapel ?: [];
        $isPending = count($pendingMapels) > 0 || in_array($siswa->status, ['pending', 'under_review']);

        $availableMapels = \App\Models\Mapel::all()->filter(function ($m) use ($activeMapels, $pendingMapels) {
            return !in_array($m->nama_mapel, $activeMapels) && !in_array($m->nama_mapel, $pendingMapels);
        })->values();

        if ($availableMapels->isEmpty()) {
            $availableMapels = collect([
                (object)['id' => 1, 'nama_mapel' => 'Matematika', 'shift' => 'Reguler'],
                (object)['id' => 2, 'nama_mapel' => 'Fisika', 'shift' => 'Reguler'],
                (object)['id' => 3, 'nama_mapel' => 'Kimia', 'shift' => 'Reguler'],
                (object)['id' => 4, 'nama_mapel' => 'Biologi', 'shift' => 'Reguler'],
                (object)['id' => 5, 'nama_mapel' => 'Bahasa Inggris', 'shift' => 'Reguler'],
                (object)['id' => 6, 'nama_mapel' => 'Bahasa Indonesia', 'shift' => 'Reguler'],
            ])->filter(function ($m) use ($activeMapels, $pendingMapels) {
                return !in_array($m->nama_mapel, $activeMapels) && !in_array($m->nama_mapel, $pendingMapels);
            })->values();
        }

        $rekeningBanks    = \App\Models\Rekening::where('tipe', 'bank')->get();
        $rekeningEwallets = \App\Models\Rekening::where('tipe', 'ewallet')->get();
        $paket            = $siswa->paket ?: \App\Models\PaketBelajar::first();

        return view('siswa.tambahPelajaran', compact(
            'siswa', 'mapels', 'sesiPerMapel', 'availableMapels',
            'rekeningBanks', 'rekeningEwallets', 'paket', 'isPending',
            'activeMapels', 'activeSesiPerMapel'
        ));
    }

    /**
     * Simpan Pilihan Mata Pelajaran dari Modal Tambah Pelajaran.
     */
    public function simpanMapel(Request $request)
    {
        $request->validate([
            'mapel' => ['required', 'array', 'min:1'],
            'mapel.*' => ['string'],
        ], [
            'mapel.required' => 'Pilih minimal satu mata pelajaran.',
            'mapel.min'      => 'Pilih minimal satu mata pelajaran.',
        ]);

        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $selectedMapel = $request->input('mapel', []);
        $sesiMapel     = $request->input('sesi', []);

        $biodata = $siswa->biodata ?? [];
        $biodata['pending_mapel_jadwal'] = array_values($selectedMapel);

        $sesiPerMapel = [];
        $totalSesi = 0;
        foreach ($selectedMapel as $idx => $mName) {
            $sVal = isset($sesiMapel[$idx]) ? (int)$sesiMapel[$idx] : (isset($sesiMapel[$mName]) ? (int)$sesiMapel[$mName] : 8);
            if ($sVal <= 0) $sVal = 8;
            $sesiPerMapel[] = $sVal;
            $totalSesi += $sVal;
        }

        $biodata['pending_sesi_per_mapel']  = $sesiPerMapel;
        $biodata['pending_jumlah_pertemuan'] = $totalSesi;

        $siswa->biodata = $biodata;
        $siswa->save();

        return redirect()->route('siswa.tambah-pelajaran')->with('success', 'Mata pelajaran berhasil disimpan & ditambahkan!');
    }

    /**
     * Ubah jumlah sesi untuk satu mapel yang sudah dipilih.
     */
    public function editMapel(Request $request)
    {
        $request->validate([
            'mapel' => ['required', 'string'],
            'sesi'  => ['required', 'integer', 'min:1'],
        ]);

        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $mapelName = $request->input('mapel');
        $sesiCount = (int) $request->input('sesi', 8);
        if ($sesiCount <= 0) {
            $sesiCount = 8;
        }

        $biodata = $siswa->biodata ?? [];
        $mapelJadwal = $biodata['pending_mapel_jadwal'] ?? [];
        $sesiPerMapel = $biodata['pending_sesi_per_mapel'] ?? [];

        if (!is_array($mapelJadwal)) {
            $mapelJadwal = [];
        }
        if (!is_array($sesiPerMapel)) {
            $sesiPerMapel = [];
        }

        $updated = false;
        foreach ($mapelJadwal as $index => $name) {
            if ($name === $mapelName) {
                $sesiPerMapel[$index] = $sesiCount;
                $updated = true;
                break;
            }
        }

        if (!$updated) {
            return redirect()->route('siswa.tambah-pelajaran')
                ->with('error', 'Mata pelajaran tidak ditemukan dalam daftar Anda.');
        }

        $biodata['pending_sesi_per_mapel'] = array_values($sesiPerMapel);
        $siswa->biodata = $biodata;
        $siswa->save();

        return redirect()->route('siswa.tambah-pelajaran')->with('success', 'Jumlah sesi untuk mata pelajaran berhasil diperbarui!');
    }

    /**
     * Hapus satu mata pelajaran dari daftar pilihan siswa.
     */
    public function hapusMapel(Request $request)
    {
        $request->validate([
            'mapel' => ['required', 'string'],
        ]);

        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $mapelName = $request->input('mapel');
        $biodata = $siswa->biodata ?? [];
        $mapelJadwal = $biodata['pending_mapel_jadwal'] ?? [];
        $sesiPerMapel = $biodata['pending_sesi_per_mapel'] ?? [];

        if (!is_array($mapelJadwal)) {
            $mapelJadwal = [];
        }
        if (!is_array($sesiPerMapel)) {
            $sesiPerMapel = [];
        }

        $newMapelJadwal = [];
        $newSesiPerMapel = [];
        foreach ($mapelJadwal as $index => $name) {
            if ($name === $mapelName) {
                continue;
            }
            $newMapelJadwal[] = $name;
            $newSesiPerMapel[] = isset($sesiPerMapel[$index]) ? (int) $sesiPerMapel[$index] : 8;
        }

        $biodata['pending_mapel_jadwal'] = array_values($newMapelJadwal);
        $biodata['pending_sesi_per_mapel'] = array_values($newSesiPerMapel);
        $biodata['pending_jumlah_pertemuan'] = array_sum($newSesiPerMapel);
        if (empty($biodata['pending_mapel_jadwal'])) {
            unset($biodata['pending_mapel_jadwal'], $biodata['pending_sesi_per_mapel'], $biodata['pending_jumlah_pertemuan']);
        }

        $siswa->biodata = $biodata;
        $siswa->save();

        return redirect()->route('siswa.tambah-pelajaran')->with('success', 'Mata pelajaran berhasil dihapus dari daftar Anda.');
    }

    /**
     * Konfirmasi Pendaftaran Paket & Mapel Siswa (Lanjut ke Status Pending untuk Diskusi).
     */
    public function submitPayment(Request $request)
    {
        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $paketId = $request->paket_id ?: ($siswa->paket_id ?: 1);
        $paket   = PaketBelajar::find($paketId);
        
        $detailString = '';
        if ($paket) {
            if ($request->tipe_paket == '1')     $detailString = $paket->detail_1;
            elseif ($request->tipe_paket == '2') $detailString = $paket->detail_2;
            elseif ($request->tipe_paket == '3') $detailString = $paket->detail_3;
            elseif ($request->tipe_paket == '4') $detailString = $paket->detail_4;
            else $detailString = $paket->nama_paket ?? 'Bimbingan Belajar';
        }

        $mapelJadwal  = $request->input('mapel_jadwal', []);
        if (empty($mapelJadwal)) {
            foreach ((array) $request->input('mapel', []) as $rm) {
                if (is_string($rm) && trim($rm) !== '') {
                    $clean = trim(preg_replace('/\s+\d+x$/i', '', $rm));
                    if ($clean !== '') {
                        $mapelJadwal[] = $clean;
                    }
                }
            }
        }
        $pilihanGuru        = $request->input('pilihan_guru');
        $pilihanGuruInggris = $request->input('pilihan_guru_inggris');
        $stripLabel = fn ($v) => trim(preg_replace('/\s*\(.*?\)\s*$/', '', $v ?? ''));

        $sesiPerMapel = $request->input('sesi', []);
        if (empty($sesiPerMapel) && !empty($request->input('mapel', []))) {
            foreach ((array) $request->input('mapel', []) as $idx => $rm) {
                if (is_string($rm) && preg_match('/(\d+)x$/i', trim($rm), $matches)) {
                    $sesiPerMapel[$idx] = ((int) $matches[1]) * 4;
                } else {
                    $mNameClean = trim(preg_replace('/\s+\d+x$/i', '', $rm));
                    $mObj = \App\Models\Mapel::where('nama_mapel', $mNameClean)->first();
                    $shift = $mObj ? ($mObj->shift ?? 1) : 1;
                    $sesiPerMapel[$idx] = $shift * 4;
                }
            }
        }
        if (empty($sesiPerMapel) && !empty($mapelJadwal)) {
            foreach ($mapelJadwal as $idx => $mName) {
                $mObj = \App\Models\Mapel::where('nama_mapel', $mName)->first();
                $shift = $mObj ? ($mObj->shift ?? 1) : 1;
                $sesiPerMapel[$idx] = $shift * 4;
            }
        }

        if (empty($mapelJadwal) && isset($siswa->biodata['pending_mapel_jadwal'])) {
            $mapelJadwal = $siswa->biodata['pending_mapel_jadwal'];
        }
        if (empty($mapelJadwal) && isset($siswa->biodata['mapel_jadwal'])) {
            $mapelJadwal = $siswa->biodata['mapel_jadwal'];
        }

        if (!is_array($mapelJadwal))  $mapelJadwal  = [];
        if (!is_array($sesiPerMapel)) $sesiPerMapel = [];

        $biodata = $siswa->biodata ?? [];
        $biodata['mapel_jadwal'] = array_values($mapelJadwal);
        $biodata['sesi_per_mapel'] = array_values($sesiPerMapel);
        $biodata['jumlah_pertemuan'] = array_sum($sesiPerMapel) ?: 4;

        $tutorPerMapel = $biodata['tutor_per_mapel'] ?? [];
        foreach ($mapelJadwal as $mName) {
            $mNameLower = strtolower($mName);
            if ($pilihanGuru && $pilihanGuru !== 'Karyawan' && str_contains($mNameLower, 'matematika')) {
                $cleanName = $stripLabel($pilihanGuru);
                if (\App\Models\User::where('name', $cleanName)->where('role', 'guru')->exists()) {
                    $tutorPerMapel[$mName] = $cleanName;
                }
            }
            if ($pilihanGuruInggris && $pilihanGuruInggris !== 'Karyawan' && str_contains($mNameLower, 'inggris')) {
                $cleanName = $stripLabel($pilihanGuruInggris);
                if (\App\Models\User::where('name', $cleanName)->where('role', 'guru')->exists()) {
                    $tutorPerMapel[$mName] = $cleanName;
                }
            }
        }
        if (!empty($tutorPerMapel)) {
            $biodata['tutor_per_mapel'] = $tutorPerMapel;
        }

        $siswa->update([
            'paket_id'   => $paketId,
            'tipe_paket' => $detailString,
            'status'     => $siswa->status === 'active' ? 'active' : 'pending',
            'biodata'    => $biodata,
        ]);

        return redirect()->route('siswa.pending')
            ->with('info', 'Pendaftaran Anda berhasil dicatat! Silakan datang ke lokasi bimbingan untuk diskusi penentuan jadwal.');
    }

    /**
     * Tampilkan Halaman Baru Bukti Bayar Siswa (Dengan Pilihan Metode & Perhitungan Dinamis).
     */
    public function showBuktiBayar()
    {
        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('login');
        }
        if ($siswa->status === 'active') {
            return redirect()->route('siswa.dashboard');
        }

        $biodata = $siswa->biodata ?? [];
        $hariPerMapel = $biodata['hari_per_mapel'] ?? [];

        // Cek apakah Admin sudah menentukan hari
        $hariSudahDitentukan = false;
        if (!empty($hariPerMapel) && is_array($hariPerMapel)) {
            foreach ($hariPerMapel as $mIdx => $hList) {
                if (is_array($hList) && count(array_filter($hList)) > 0) {
                    $hariSudahDitentukan = true;
                    break;
                }
            }
        }

        if (!$hariSudahDitentukan) {
            return redirect()->route('siswa.pending')->with('error', 'Hari bimbingan Anda belum ditentukan oleh Admin.');
        }

        $paket = PaketBelajar::find($siswa->paket_id) ?? PaketBelajar::first();
        $detailString = $siswa->tipe_paket ?? ($paket ? $paket->detail_1 : '');
        $harga = $this->extractPrice($detailString, $paket ? $paket->harga_max : 450000);

        $mapelJadwal = $biodata['mapel_jadwal'] ?? [];

        // Hitung kemunculan hari bimbingan dalam bulan berjalan
        $currentMonth = \Carbon\Carbon::now();
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth   = $currentMonth->copy()->endOfMonth();

        $dayMap = [
            'senin' => 1, 'selasa' => 2, 'rabu' => 3, 'kamis' => 4,
            'jumat' => 5, 'sabtu' => 6, 'minggu' => 0
        ];

        $rincianMapel = [];
        $totalSesiBulanIni = 0;

        foreach ($mapelJadwal as $idx => $namaMapel) {
            $assignedDays = $hariPerMapel[$idx] ?? [];
            if (!is_array($assignedDays)) {
                $assignedDays = [$assignedDays];
            }
            $assignedDaysClean = array_values(array_filter($assignedDays));

            // Hitung berapa kali hari-hari ini muncul dalam bulan berjalan
            $countMapelInMonth = 0;
            $period = \Carbon\CarbonPeriod::create($startOfMonth, $endOfMonth);

            foreach ($period as $date) {
                $dayOfWeek = $date->dayOfWeek; // 0 (Sun) - 6 (Sat)
                foreach ($assignedDaysClean as $h) {
                    $hLower = strtolower(trim($h));
                    if (isset($dayMap[$hLower]) && $dayMap[$hLower] === $dayOfWeek) {
                        $countMapelInMonth++;
                    }
                }
            }

            if ($countMapelInMonth === 0) {
                $countMapelInMonth = 1;
            }

            $subtotalMapel = $harga * $countMapelInMonth;
            $totalSesiBulanIni += $countMapelInMonth;

            $rincianMapel[] = [
                'nama_mapel'  => $namaMapel,
                'hari_list'   => implode(', ', $assignedDaysClean),
                'jumlah_sesi' => $countMapelInMonth,
                'subtotal'    => $subtotalMapel,
            ];
        }

        if ($totalSesiBulanIni === 0) {
            $totalSesiBulanIni = 1;
        }

        $totalBiayaBulanIni = $harga * $totalSesiBulanIni;

        $banks    = \App\Models\Rekening::where('tipe', 'bank')->get();
        $ewallets = \App\Models\Rekening::where('tipe', 'ewallet')->get();

        return view('siswa.bukti_bayar', compact(
            'siswa', 'paket', 'harga', 'rincianMapel',
            'totalSesiBulanIni', 'totalBiayaBulanIni',
            'banks', 'ewallets', 'currentMonth'
        ));
    }

    /**
     * Submit Bukti Bayar Siswa.
     */
    public function submitBuktiBayar(Request $request)
    {
        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $request->validate([
            'payment_method' => ['required', 'in:bank,ewallet,tunai'],
            'bukti_transfer' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ], [
            'bukti_transfer.required' => 'Bukti transfer pembayaran wajib diunggah.',
            'bukti_transfer.file'     => 'Bukti transfer harus berupa file valid.',
            'bukti_transfer.mimes'    => 'Format file bukti transfer harus berupa JPG, PNG, atau PDF.',
            'bukti_transfer.max'      => 'Ukuran file bukti transfer maksimal adalah 2MB.',
        ]);

        $paymentMethod = $request->input('payment_method', 'bank');
        $buktiPath = '';

        if ($request->hasFile('bukti_transfer')) {
            $file     = $request->file('bukti_transfer');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());

            if (!file_exists(public_path('uploads/bukti_transfer'))) {
                mkdir(public_path('uploads/bukti_transfer'), 0777, true);
            }
            $file->move(public_path('uploads/bukti_transfer'), $filename);
            $buktiPath = 'uploads/bukti_transfer/' . $filename;
        }

        $paketId = $siswa->paket_id ?: 1;
        $paket   = PaketBelajar::find($paketId);
        $harga   = $this->extractPrice($siswa->tipe_paket, $paket ? $paket->harga_max : 450000);

        $biodata = $siswa->biodata ?? [];
        $totalSesi  = (int) $request->input('total_sesi', 4);
        $totalHarga = (int) $request->input('total_harga', $harga * $totalSesi);

        $biodata['payment_method'] = $paymentMethod;

        $siswa->update([
            'bukti_transfer' => $buktiPath,
            'status'         => 'under_review',
            'biodata'        => $biodata,
        ]);

        RiwayatPembayaran::create([
            'siswa_id'            => $siswa->id,
            'paket_id'            => $paketId,
            'tipe_paket_snapshot' => $siswa->tipe_paket ?? 'Bimbingan Belajar',
            'bukti_transfer'      => $buktiPath,
            'payment_method'      => $paymentMethod,
            'jumlah_sesi'         => $totalSesi,
            'total_harga'         => $totalHarga,
            'status'              => 'under_review',
        ]);

        $title   = "Pemberitahuan Bukti Transfer Siswa";
        $message = "Siswa " . $siswa->name . " telah mengunggah bukti transfer pembayaran via " . strtoupper($paymentMethod) . ".";
        $link    = route('admin.siswa.detail', $siswa->id);

        \Illuminate\Support\Facades\DB::table('notifications')->insert([
            'title'      => $title,
            'message'    => $message,
            'link'       => $link,
            'is_read'    => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            $firebaseService = new \App\Services\FirebaseService();
            $firebaseService->sendToAdmins($title, $message, $link);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Gagal mengirim FCM: " . $e->getMessage());
        }

        return redirect()->route('siswa.pending')
            ->with('success', 'Bukti transfer pembayaran berhasil dikirim! Menunggu verifikasi dari Admin.');
    }

    /**
     * Ekstrak Nilai Harga dari String Detail Paket.
     */
    private function extractPrice($str, $default)
    {
        if (empty($str))
            return $default;
        if (preg_match('/(\d+)\s*K/i', $str, $matches)) {
            return (int) $matches[1] * 1000;
        }
        if (preg_match('/Rp\s*([\d\.]+)/i', $str, $matches)) {
            return (int) str_replace('.', '', $matches[1]);
        }
        if (preg_match('/(\d[\d\.]*)/', $str, $matches)) {
            return (int) str_replace('.', '', $matches[1]);
        }
        return $default;
    }

    /**
     * Helper privat untuk memastikan hanya siswa dengan status active yang bisa mengakses dashboard utama.
     */
    private function checkActiveStatus($siswa)
    {
        if (!$siswa) {
            return redirect()->route('login');
        }
        if ($siswa->status === 'nonaktif') {
            return redirect()->route('siswa.pending');
        }
        if ($siswa->status !== 'active') {
            if ($siswa->status === 'under_review' || !empty($siswa->bukti_transfer)) {
                return redirect()->route('siswa.pending');
            }
            if ($siswa->status === 'rejected') {
                $siswa->status = 'pending';
                $siswa->save();
                return redirect()->route('siswa.biodata')
                    ->with('error', 'Pendaftaran Anda sebelumnya ditolak oleh Admin. Silakan isi kembali biodata Anda dan lanjutkan pendaftaran seperti biasa.');
            }
            return redirect()->route('siswa.biodata')
                ->with('info', 'Silakan lengkapi biodata dan pendaftaran Anda terlebih dahulu.');
        }
        return null;
    }

    /**
     * Tampilkan Halaman Jadwal Belajar Siswa (Kalender).
     */
    public function showJadwal()
    {
        $siswa = auth()->guard('siswa')->user();
        if ($redirect = $this->checkActiveStatus($siswa)) {
            return $redirect;
        }

        // Guru
        $biodata = $siswa->biodata ?? [];
        $tutorPerMapel = $biodata['tutor_per_mapel'] ?? [];

        $gurus   = [];
        $hasGuru = false;

        if (!empty($tutorPerMapel) && is_array($tutorPerMapel)) {
            foreach ($tutorPerMapel as $mapelName => $guruName) {
                if (!empty($guruName)) {
                    $gurus[] = $mapelName . ': ' . $guruName;
                    $hasGuru = true;
                }
            }
        } elseif ($siswa->tipe_paket && preg_match('/Guru:\s*([^)|]+)/i', $siswa->tipe_paket, $m)) {
            // Fallback untuk data lama yang masih pakai format tipe_paket string
            $gurus = array_map('trim', explode(',', $m[1]));
            foreach ($gurus as $g) {
                if (!empty($g) && $g !== '-' && strtolower($g) !== 'belum ditentukan') {
                    $hasGuru = true;
                }
            }
        }

        // ── Data per-mapel baru ──
        $mapelJadwal     = $biodata['mapel_jadwal'] ?? [];
        $sesiPerMapel    = $biodata['sesi_per_mapel'] ?? [];
        $hariPerMapel    = $biodata['hari_per_mapel'] ?? [];
        $tanggalPerMapel = $biodata['tanggal_mulai_per_mapel'] ?? [];
        $jamPerMapel     = $biodata['jam_per_mapel'] ?? [];

        // ── Data flat (untuk backward compat & kalender gabungan) ──
        $hariPertemuan   = $biodata['hari_pertemuan'] ?? [];
        $tanggalMulai    = $biodata['tanggal_mulai'] ?? null;
        $jumlahPertemuan = $biodata['jumlah_pertemuan'] ?? null;

        // Fallback: jika belum ada data per-mapel, coba parse dari biodata lama / tipe_paket
        if (empty($mapelJadwal) && $siswa->tipe_paket) {
            // Parse mapel dari tipe_paket lama
            if (preg_match('/Mapel:\s*([^)|]+)/i', $siswa->tipe_paket, $m)) {
                $mapelJadwal = array_map('trim', explode(',', $m[1]));
            }
            // Parse hari dari tipe_paket
            if (empty($hariPertemuan) && preg_match('/Hari:\s*([^)|]+)/i', $siswa->tipe_paket, $m)) {
                $hariPertemuan = array_map('trim', explode(',', $m[1]));
            }
            // Parse tanggal dari tipe_paket
            if (!$tanggalMulai && preg_match('/Mulai:\s*([\d\-]+)/i', $siswa->tipe_paket, $m)) {
                $d = trim($m[1]);
                if (preg_match('/(\d{2})-(\d{2})-(\d{4})/', $d, $dM)) {
                    $tanggalMulai = $dM[3] . '-' . $dM[2] . '-' . $dM[1];
                } else {
                    $tanggalMulai = $d;
                }
            }
            // Parse sesi dari tipe_paket (Total Sesi: 13x)
            if (!$jumlahPertemuan && preg_match('/Total Sesi:\s*(\d+)x/i', $siswa->tipe_paket, $m)) {
                $jumlahPertemuan = (int) $m[1];
            }
            if (!$jumlahPertemuan && preg_match('/Sesi:\s*(\d+)x/i', $siswa->tipe_paket, $m)) {
                $jumlahPertemuan = (int) $m[1];
            }
        }

        // Pastikan hariPertemuan terisi dari per-mapel jika flat kosong
        if (empty($hariPertemuan) && !empty($hariPerMapel)) {
            foreach ($hariPerMapel as $h) {
                if (is_array($h)) {
                    foreach ($h as $hari) {
                        if ($hari) $hariPertemuan[] = $hari;
                    }
                }
            }
            $hariPertemuan = array_unique($hariPertemuan);
        }

        // Pastikan sesiPerMapel & jumlahPertemuan terisi jika kosong
        if (!empty($mapelJadwal) && is_array($mapelJadwal)) {
            foreach ($mapelJadwal as $idx => $namaMapel) {
                if (empty($sesiPerMapel[$idx]) || (int)$sesiPerMapel[$idx] <= 0) {
                    $mObj = \App\Models\Mapel::where('nama_mapel', $namaMapel)->first();
                    $shift = $mObj ? ($mObj->shift ?? 1) : 1;
                    $sesiPerMapel[$idx] = $shift * 4;
                }
            }
        }
        if (empty($jumlahPertemuan) || (int)$jumlahPertemuan <= 0) {
            $jumlahPertemuan = !empty($sesiPerMapel) ? array_sum(array_map('intval', $sesiPerMapel)) : 4;
        }

        // Tanggal mulai fallback: ambil yang terlama dari per-mapel
        if (!$tanggalMulai && !empty($tanggalPerMapel)) {
            $filteredDates = array_filter($tanggalPerMapel);
            if (!empty($filteredDates)) {
                $tanggalMulai = min($filteredDates);
            }
        }

        if (!$tanggalMulai) {
            $tanggalMulai = $siswa->created_at ? $siswa->created_at->format('Y-m-d') : date('Y-m-d');
        }

        // Mapel list flat
        $mapels = $mapelJadwal ?: [];
        if (empty($mapels) && $siswa->tipe_paket && preg_match('/Mapel:\s*([^)|]+)/i', $siswa->tipe_paket, $m)) {
            $mapels = array_map('trim', explode(',', $m[1]));
        }

        // Jam belajar
        $paket          = $siswa->paket;
        $jamMulai       = $paket ? ($paket->jam_mulai ?? '15:30') : '15:30';
        $jamSelesai     = $jamMulai;
        $durationMinutes = 90;
        if ($paket && preg_match('/(\d+)\s*menit/i', $paket->detail_5 ?? '', $dM)) {
            $durationMinutes = (int) $dM[1];
        }

        if (!empty($jamPerMapel) && is_array($jamPerMapel)) {
            foreach ($jamPerMapel as $timeSet) {
                if (is_array($timeSet) && !empty($timeSet['jam_mulai'])) {
                    $jamMulai = $timeSet['jam_mulai'];
                    $jamSelesai = $timeSet['jam_selesai'] ?? date('H:i', strtotime($jamMulai . " + {$durationMinutes} minutes"));
                    break;
                }
            }
        }

        if ($jamSelesai === $jamMulai) {
            $jamSelesai = date('H:i', strtotime($jamMulai . " + {$durationMinutes} minutes"));
        }

        return view('siswa.jadwal', compact(
            'siswa', 'hariPertemuan', 'tanggalMulai', 'mapels',
            'jumlahPertemuan', 'jamMulai', 'jamSelesai', 'hasGuru', 'gurus',
            'mapelJadwal', 'sesiPerMapel', 'hariPerMapel', 'tanggalPerMapel'
        ));
    }

    /**
     * Tampilkan Halaman Data Akademik (Biodata terdaftar) Siswa.
     */
    public function showAkademik()
    {
        $siswa = auth()->guard('siswa')->user();
        if ($redirect = $this->checkActiveStatus($siswa)) {
            return $redirect;
        }

        return view('siswa.dataAkademik', compact('siswa'));
    }

    /**
     * Tampilkan Halaman Invoice Belajar Siswa.
     */
    public function showInvoice(Request $request)
    {
        $siswa = auth()->guard('siswa')->user();
        if ($redirect = $this->checkActiveStatus($siswa)) {
            return $redirect;
        }

        $paket = $siswa->paket;
        $biodata = $siswa->biodata ?? [];

        // Dynamic Month & Year selection (default: current month)
        $month = (int) $request->input('month', date('n'));
        $year  = (int) $request->input('year', date('Y'));

        $month = max(1, min(12, $month));
        $year  = max(2020, min(2050, $year));

        $startOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth   = $startOfMonth->copy()->endOfMonth();

        $hariPertemuan   = $biodata['hari_pertemuan'] ?? [];
        $hariPerMapel    = $biodata['hari_per_mapel'] ?? [];
        $tanggalMulai    = $biodata['tanggal_mulai'] ?? null;
        $mapelJadwal     = $biodata['mapel_jadwal'] ?? [];

        $mapels = [];
        if (!empty($mapelJadwal)) {
            $mapels = $mapelJadwal;
        } elseif ($siswa->tipe_paket && preg_match('/Mapel:\s*([^)|]+)/i', $siswa->tipe_paket, $matches)) {
            $mapels = array_map('trim', explode(',', $matches[1]));
        }

        $dayMap = [
            'senin' => 1, 'selasa' => 2, 'rabu' => 3, 'kamis' => 4,
            'jumat' => 5, 'sabtu' => 6, 'minggu' => 0
        ];

        // Hitung total sesi PER MAPEL untuk 1 bulan penuh kalender (Full Month)
        $sesiPerMapelBulanIni = [];
        foreach ($mapels as $idx => $mapelName) {
            $assignedDays = $hariPerMapel[$idx] ?? [];
            if (empty($assignedDays) && !empty($hariPertemuan)) {
                $assignedDays = $hariPertemuan;
            }
            if (!is_array($assignedDays)) {
                $assignedDays = [$assignedDays];
            }
            $assignedDaysClean = array_values(array_filter($assignedDays));

            $countMapelInMonth = 0;
            if (!empty($assignedDaysClean)) {
                $period = \Carbon\CarbonPeriod::create($startOfMonth, $endOfMonth);
                foreach ($period as $date) {
                    $dayOfWeek = $date->dayOfWeek; // 0 (Sun) - 6 (Sat)
                    foreach ($assignedDaysClean as $h) {
                        $hLower = strtolower(trim($h));
                        if (isset($dayMap[$hLower]) && $dayMap[$hLower] === $dayOfWeek) {
                            $countMapelInMonth++;
                        }
                    }
                }
            }
            $sesiPerMapelBulanIni[$idx] = $countMapelInMonth > 0 ? $countMapelInMonth : 4;
        }

        // Single session price
        $detailString = '';
        if ($siswa->tipe_paket) {
            if ($paket) {
                if (str_contains($siswa->tipe_paket, $paket->detail_1)) $detailString = $paket->detail_1;
                elseif (str_contains($siswa->tipe_paket, $paket->detail_2)) $detailString = $paket->detail_2;
                elseif (str_contains($siswa->tipe_paket, $paket->detail_3)) $detailString = $paket->detail_3;
                elseif (str_contains($siswa->tipe_paket, $paket->detail_4)) $detailString = $paket->detail_4;
            }
        }
        $hargaPerSesi = $this->extractPrice($detailString, $paket ? $paket->harga_max : 450000);

        // Total sesi & harga bulan terpilih
        $totalSesiBulanIni = array_sum($sesiPerMapelBulanIni);
        $totalHarga = $hargaPerSesi * $totalSesiBulanIni;

        // Guru
        $tutorPerMapel = $biodata['tutor_per_mapel'] ?? [];
        $gurus = [];
        foreach ($mapels as $idx => $mapelName) {
            $gurus[$idx] = $tutorPerMapel[$mapelName] ?? 'Belum Ditentukan';
        }

        // Filter Catatan Bon / Biaya Extra khusus bulan & tahun terpilih
        $allCatatanBon = $biodata['catatan_bon'] ?? [];
        $catatanBonFiltered = array_values(array_filter($allCatatanBon, function ($item) use ($month, $year) {
            $b = (int) ($item['bulan'] ?? 0);
            $y = (int) ($item['tahun'] ?? 0);
            return $b === $month && $y === $year;
        }));

        $totalCatatanBon = array_sum(array_column($catatanBonFiltered, 'harga'));
        $grandTotal = $totalHarga + $totalCatatanBon;

        $banks    = \App\Models\Rekening::where('tipe', 'bank')->get();
        $ewallets = \App\Models\Rekening::where('tipe', 'ewallet')->get();

        // Cek status riwayat pembayaran untuk bulan & tahun terpilih
        $riwayatBulanIni = \App\Models\RiwayatPembayaran::where('siswa_id', $siswa->id)
            ->where(function ($q) use ($month, $year) {
                $monthName = \Carbon\Carbon::createFromDate($year, $month, 1)->locale('id')->isoFormat('MMMM');
                $q->where('tipe_paket_snapshot', 'LIKE', "%{$monthName}%{$year}%")
                  ->orWhereRaw("MONTH(created_at) = ? AND YEAR(created_at) = ?", [$month, $year]);
            })
            ->get();

        $hasPaidCurrentMonth = $riwayatBulanIni->where('status', 'approved')->isNotEmpty();
        $hasPendingPayment   = $riwayatBulanIni->where('status', 'under_review')->isNotEmpty();

        // Overdue status: hari ini melewati tgl 10 atau dipicu ?test_late=1
        $isOverdue = ((int) date('j') > 10 || $request->has('test_late')) && !$hasPaidCurrentMonth && !$hasPendingPayment;

        $periodeText = strtoupper($startOfMonth->locale('id')->isoFormat('MMM\'YY'));

        return view('siswa.invoice', compact(
            'siswa', 'paket', 'hariPertemuan', 'hariPerMapel', 'hargaPerSesi',
            'totalHarga', 'mapels', 'gurus', 'sesiPerMapelBulanIni', 'totalSesiBulanIni',
            'month', 'year', 'periodeText', 'tanggalMulai',
            'catatanBonFiltered', 'totalCatatanBon', 'grandTotal',
            'banks', 'ewallets', 'hasPaidCurrentMonth', 'hasPendingPayment', 'isOverdue'
        ));
    }

    /**
     * Submit Bukti Pembayaran Bulanan Siswa (dari Halaman Invoice).
     */
    public function submitInvoicePayment(Request $request)
    {
        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $request->validate([
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
            'payment_method' => 'required|string',
            'month'          => 'required|integer|between:1,12',
            'year'           => 'required|integer|between:2020,2050',
        ], [
            'bukti_transfer.required' => 'Berkas bukti transfer wajib diunggah.',
            'bukti_transfer.mimes'    => 'Format file harus JPG, PNG, WEBP, atau PDF.',
            'bukti_transfer.max'      => 'Ukuran file maksimal 2MB.',
            'payment_method.required' => 'Pilih metode / bank pembayaran.',
        ]);

        $month = (int) $request->month;
        $year  = (int) $request->year;

        $monthNamesID = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $monthName = $monthNamesID[$month] ?? 'Bulan ' . $month;
        $snapshotName = "Pembayaran Bulanan - " . $monthName . " " . $year;

        $biodata = $siswa->biodata ?? [];
        $mapelJadwal = $biodata['mapel_jadwal'] ?? [];
        $hariPerMapel = $biodata['hari_per_mapel'] ?? [];
        $hariPertemuan = $biodata['hari_pertemuan'] ?? [];
        $paket = $siswa->paket;

        $mapels = !empty($mapelJadwal) ? $mapelJadwal : (
            $siswa->tipe_paket && preg_match('/Mapel:\s*([^)|]+)/i', $siswa->tipe_paket, $m)
                ? array_map('trim', explode(',', $m[1])) : ['Bimbingan']
        );

        $startOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth   = $startOfMonth->copy()->endOfMonth();
        $dayMap       = ['senin' => 1, 'selasa' => 2, 'rabu' => 3, 'kamis' => 4, 'jumat' => 5, 'sabtu' => 6, 'minggu' => 0];

        $sesiPerMapelBulanIni = [];
        foreach ($mapels as $idx => $mapelName) {
            $assignedDays = $hariPerMapel[$idx] ?? $hariPertemuan;
            if (!is_array($assignedDays)) $assignedDays = [$assignedDays];
            $assignedDaysClean = array_values(array_filter($assignedDays));

            $countMapelInMonth = 0;
            if (!empty($assignedDaysClean)) {
                $period = \Carbon\CarbonPeriod::create($startOfMonth, $endOfMonth);
                foreach ($period as $date) {
                    foreach ($assignedDaysClean as $h) {
                        if (isset($dayMap[strtolower(trim($h))]) && $dayMap[strtolower(trim($h))] === $date->dayOfWeek) {
                            $countMapelInMonth++;
                        }
                    }
                }
            }
            $sesiPerMapelBulanIni[$idx] = $countMapelInMonth > 0 ? $countMapelInMonth : 4;
        }

        $detailString = '';
        if ($siswa->tipe_paket && $paket) {
            if (str_contains($siswa->tipe_paket, $paket->detail_1)) $detailString = $paket->detail_1;
            elseif (str_contains($siswa->tipe_paket, $paket->detail_2)) $detailString = $paket->detail_2;
            elseif (str_contains($siswa->tipe_paket, $paket->detail_3)) $detailString = $paket->detail_3;
            elseif (str_contains($siswa->tipe_paket, $paket->detail_4)) $detailString = $paket->detail_4;
        }
        $hargaPerSesi = $this->extractPrice($detailString, $paket ? $paket->harga_max : 450000);
        $totalSesiBulanIni = array_sum($sesiPerMapelBulanIni);
        $totalHarga = $hargaPerSesi * $totalSesiBulanIni;

        $allCatatanBon = $biodata['catatan_bon'] ?? [];
        $catatanBonFiltered = array_values(array_filter($allCatatanBon, function ($item) use ($month, $year) {
            return (int)($item['bulan'] ?? 0) === $month && (int)($item['tahun'] ?? 0) === $year;
        }));
        $totalCatatanBon = array_sum(array_column($catatanBonFiltered, 'harga'));
        $grandTotal = $totalHarga + $totalCatatanBon;

        $file = $request->file('bukti_transfer');
        $fileName = 'invoice_' . $siswa->id . '_' . $year . '_' . sprintf('%02d', $month) . '_' . time() . '.' . $file->getClientOriginalExtension();
        if (!file_exists(public_path('uploads/bukti_pembayaran'))) {
            mkdir(public_path('uploads/bukti_pembayaran'), 0777, true);
        }
        $file->move(public_path('uploads/bukti_pembayaran'), $fileName);
        $filePath = 'uploads/bukti_pembayaran/' . $fileName;

        \App\Models\RiwayatPembayaran::create([
            'siswa_id'             => $siswa->id,
            'paket_id'             => $siswa->paket_id,
            'tipe_paket_snapshot' => $snapshotName,
            'bukti_transfer'       => $filePath,
            'payment_method'       => $request->payment_method,
            'jumlah_sesi'          => $totalSesiBulanIni,
            'total_harga'          => $grandTotal,
            'status'               => 'under_review',
        ]);

        return redirect()->route('siswa.riwayat')
            ->with('success', 'Bukti pembayaran untuk ' . $snapshotName . ' (Rp ' . number_format($grandTotal) . ') berhasil dikirim! Silakan tunggu verifikasi Admin.');
    }

    /**
     * Tampilkan Halaman Riwayat Pembayaran Siswa.
     */
    public function showRiwayat()
    {
        $siswa = auth()->guard('siswa')->user();
        if ($redirect = $this->checkActiveStatus($siswa)) {
            return $redirect;
        }

        $paket = $siswa->paket;
        $biodata = $siswa->biodata ?? [];
        
        $hariPertemuan = $biodata['hari_pertemuan'] ?? [];
        $jumlahPertemuan = $biodata['jumlah_pertemuan'] ?? null;
        $tanggalMulai = $biodata['tanggal_mulai'] ?? null;

        // Fallback parsing from tipe_paket
        if (empty($hariPertemuan) && $siswa->tipe_paket) {
            if (preg_match('/Hari:\s*([^)|]+)/i', $siswa->tipe_paket, $matches)) {
                $hariPertemuan = array_map('trim', explode(',', $matches[1]));
            }
        }

        if (!$jumlahPertemuan && $siswa->tipe_paket) {
            if (preg_match('/Sesi:\s*(\d+)x/i', $siswa->tipe_paket, $matches)) {
                $jumlahPertemuan = (int) $matches[1];
            }
        }

        // Get single session price
        $detailString = '';
        if ($siswa->tipe_paket) {
            if ($paket) {
                if (str_contains($siswa->tipe_paket, $paket->detail_1)) $detailString = $paket->detail_1;
                elseif (str_contains($siswa->tipe_paket, $paket->detail_2)) $detailString = $paket->detail_2;
                elseif (str_contains($siswa->tipe_paket, $paket->detail_3)) $detailString = $paket->detail_3;
                elseif (str_contains($siswa->tipe_paket, $paket->detail_4)) $detailString = $paket->detail_4;
            }
        }
        $hargaPerSesi = $this->extractPrice($detailString, $paket ? $paket->harga_max : 450000);
        
        // Total price
        $totalHarga = $hargaPerSesi * ($jumlahPertemuan ?: 1);

        // Ambill seluruh transaksi riwayat pembayaran siswa
        $riwayatList = \App\Models\RiwayatPembayaran::where('siswa_id', $siswa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('siswa.riwayat', compact('siswa', 'paket', 'hariPertemuan', 'jumlahPertemuan', 'tanggalMulai', 'hargaPerSesi', 'totalHarga', 'riwayatList'));
    }

    /**
     * Tampilkan Halaman Ujian / Latihan Soal Siswa (Katalog / Mode Ujian).
     */
    public function showUjian(Request $request)
    {
        $siswa = auth()->guard('siswa')->user();
        if ($redirect = $this->checkActiveStatus($siswa)) {
            return $redirect;
        }
        if (!$siswa) {
            return redirect()->route('login');
        }

        // Mode 2: Jika ada parameter kategori_id, tampilkan lembar pengerjaan ujian
        if ($request->filled('kategori_id')) {
            $selectedCategory = KategoriSoal::with(['bankSoals' => function ($q) {
                $q->orderBy('nomor', 'asc');
            }])->find($request->kategori_id);

            if (!$selectedCategory || $selectedCategory->bankSoals->isEmpty()) {
                return redirect()->route('siswa.ujian')
                    ->with('error', 'Soal belum tersedia untuk kategori ujian yang dipilih.');
            }

            $mode = 'exam';
            return view('siswa.ujian', compact('siswa', 'selectedCategory', 'mode'));
        }

        // Mode 1: Katalog Pilihan Ujian berdasarkan Jenjang & Sub-Kategori
        $jenjangInput = strtoupper($request->input('jenjang', ''));

        // Deteksi jenjang otomatis dari biodata atau paket siswa jika belum di-set
        if (empty($jenjangInput)) {
            $kelasSiswa = $siswa->biodata['kelas'] ?? ($siswa->paket->nama_paket ?? '');
            if (preg_match('/(sd|1|2|3|4|5|6)/i', $kelasSiswa)) {
                $jenjangInput = 'SD';
            } elseif (preg_match('/(smp|7|8|9)/i', $kelasSiswa)) {
                $jenjangInput = 'SMP';
            } elseif (preg_match('/(sma|smk|10|11|12)/i', $kelasSiswa)) {
                $jenjangInput = 'SMA';
            } else {
                $jenjangInput = 'SD';
            }
        }

        if (!in_array($jenjangInput, ['SD', 'SMP', 'SMA'])) {
            $jenjangInput = 'SD';
        }

        $jenjang = $jenjangInput;
        $sub_kategori = $request->input('sub_kategori', 'Semester 1');

        // Daftar sub-kategori unik dari DB
        $availableSubKategori = KategoriSoal::where('jenjang', $jenjang)
            ->distinct()
            ->pluck('sub_kategori')
            ->toArray();

        $defaultSubKategori = ['Semester 1', 'Semester 2', 'TKA'];
        $allSubKategori = array_unique(array_merge($defaultSubKategori, $availableSubKategori));

        // Ambil daftar mata pelajaran yang diambil oleh siswa ini
        $biodata = $siswa->biodata ?? [];
        $rawMapels = $biodata['mapel_jadwal'] ?? [];

        if (empty($rawMapels) && !empty($siswa->tipe_paket) && preg_match('/Mapel:\s*([^)|]+)/i', $siswa->tipe_paket, $m)) {
            $rawMapels = array_map('trim', explode(',', $m[1]));
        }

        $siswaMapelList = [];
        foreach ((array) $rawMapels as $mItem) {
            $cleanM = trim(preg_replace('/\s*\d+x$/i', '', $mItem));
            if (!empty($cleanM)) {
                $siswaMapelList[] = $cleanM;
            }
        }
        $siswaMapelList = array_values(array_unique($siswaMapelList));

        // Ambil daftar kategori soal khusus untuk mata pelajaran yang diambil siswa saja
        $categoriesQuery = KategoriSoal::where('jenjang', $jenjang)
            ->where('sub_kategori', $sub_kategori)
            ->withCount('bankSoals');

        if (!empty($siswaMapelList)) {
            $categoriesQuery->where(function ($q) use ($siswaMapelList) {
                foreach ($siswaMapelList as $mapelName) {
                    $q->orWhere('nama_kategori', 'LIKE', '%' . $mapelName . '%')
                      ->orWhere('deskripsi', 'LIKE', '%' . $mapelName . '%');
                }
            });
        }

        $categories = $categoriesQuery->get();

        // Riwayat Ujian Siswa
        $riwayatUjian = HasilUjian::where('siswa_id', $siswa->id)
            ->with('kategori')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Ujian yang ditugaskan oleh Guru
        $assignedExams = $siswa->biodata['assigned_ujian'] ?? [];

        $mode = 'catalog';

        return view('siswa.ujian', compact(
            'siswa',
            'jenjang',
            'sub_kategori',
            'allSubKategori',
            'categories',
            'riwayatUjian',
            'assignedExams',
            'siswaMapelList',
            'mode'
        ));
    }

    /**
     * Submit Jawaban Ujian Siswa & Hitung Nilai.
     */
    public function submitUjian(Request $request)
    {
        $siswa = auth()->guard('siswa')->user();
        if ($redirect = $this->checkActiveStatus($siswa)) {
            return $redirect;
        }
        if (!$siswa) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'kategori_soal_id' => 'required|exists:kategori_soals,id',
            'jawaban' => 'nullable|array',
        ]);

        $kategori = KategoriSoal::with(['bankSoals' => function ($q) {
            $q->orderBy('nomor', 'asc');
        }])->findOrFail($request->kategori_soal_id);

        $soalList = $kategori->bankSoals;
        $totalSoal = $soalList->count();
        $jawabanSiswa = $request->input('jawaban', []);

        $jumlahBenar = 0;
        $jumlahSalah = 0;
        $reviewData = [];

        foreach ($soalList as $soal) {
            $soalId = $soal->id;
            $userAnswer = $jawabanSiswa[$soalId] ?? null;
            $isCorrect = false;

            if ($userAnswer && strtoupper(trim($userAnswer)) === strtoupper(trim($soal->kunci_jawaban))) {
                $isCorrect = true;
                $jumlahBenar++;
            } else {
                $jumlahSalah++;
            }

            $reviewData[] = [
                'soal' => $soal,
                'jawaban_siswa' => $userAnswer,
                'is_correct' => $isCorrect,
            ];
        }

        $nilai = $totalSoal > 0 ? round(($jumlahBenar / $totalSoal) * 100, 2) : 0;

        // Simpan Hasil Ujian ke Database
        $hasil = HasilUjian::create([
            'siswa_id' => $siswa->id,
            'kategori_soal_id' => $kategori->id,
            'jumlah_soal' => $totalSoal,
            'jumlah_benar' => $jumlahBenar,
            'jumlah_salah' => $jumlahSalah,
            'nilai' => $nilai,
            'jawaban_siswa' => $jawabanSiswa,
        ]);

        $mode = 'result';

        return view('siswa.ujian', compact(
            'siswa',
            'kategori',
            'hasil',
            'reviewData',
            'nilai',
            'jumlahBenar',
            'jumlahSalah',
            'totalSoal',
            'mode'
        ));
    }
    /**
     * Tampilkan Halaman Transkip Nilai Siswa (Rekap Nilai per Mata Pelajaran per Semester).
     */
    public function showTranskipNilai(Request $request)
    {
        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('login');
        }

        $availableSemesters = ['Semester 1', 'Semester 2', 'TKA'];
        $semester = $request->input('semester', 'Semester 1');
        if (!in_array($semester, $availableSemesters)) {
            $semester = 'Semester 1';
        }

        // Ambil seluruh hasil ujian siswa untuk semester/sub-kategori terpilih
        $hasilUjians = HasilUjian::where('siswa_id', $siswa->id)
            ->whereHas('kategori', function ($q) use ($semester) {
                $q->where('sub_kategori', $semester);
            })
            ->with('kategori')
            ->orderBy('created_at', 'asc')
            ->get();

        // Rekap per Mata Pelajaran (kategori_soal_id): nilai terbaik, nilai terakhir, jumlah percobaan
        $rekapMapel = [];
        foreach ($hasilUjians as $hasil) {
            if (!$hasil->kategori) {
                continue;
            }

            $kategoriId = $hasil->kategori_soal_id;

            if (!isset($rekapMapel[$kategoriId])) {
                $rekapMapel[$kategoriId] = [
                    'kategori'          => $hasil->kategori,
                    'nilai_terbaik'     => $hasil->nilai,
                    'nilai_terakhir'    => $hasil->nilai,
                    'tanggal_terakhir'  => $hasil->created_at,
                    'jumlah_percobaan'  => 0,
                ];
            }

            $rekapMapel[$kategoriId]['jumlah_percobaan']++;

            if ($hasil->nilai > $rekapMapel[$kategoriId]['nilai_terbaik']) {
                $rekapMapel[$kategoriId]['nilai_terbaik'] = $hasil->nilai;
            }

            if ($hasil->created_at->greaterThanOrEqualTo($rekapMapel[$kategoriId]['tanggal_terakhir'])) {
                $rekapMapel[$kategoriId]['nilai_terakhir']   = $hasil->nilai;
                $rekapMapel[$kategoriId]['tanggal_terakhir'] = $hasil->created_at;
            }
        }

        $rekapMapel = collect($rekapMapel)
            ->sortBy(fn ($item) => $item['kategori']->nama_kategori ?? '')
            ->values();

        $rataRata = $rekapMapel->count() > 0
            ? round($rekapMapel->avg('nilai_terbaik'), 1)
            : 0;

        return view('siswa.transkipNilai', compact(
            'siswa', 'semester', 'availableSemesters', 'rekapMapel', 'rataRata'
        ));
    }
}

