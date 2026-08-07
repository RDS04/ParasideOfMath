<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PaketBelajar;
use Illuminate\Http\Request;

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
        if ($siswa) {
            if ($siswa->status === 'active') {
                return redirect()->route('siswa.dashboard');
            }
            if ($siswa->status === 'under_review' || !empty($siswa->bukti_transfer)) {
                return redirect()->route('siswa.pending');
            }
            // Continuous DB check: If biodata is not filled in DB yet, direct to biodata form
            if (empty($siswa->biodata) || !is_array($siswa->biodata) || count($siswa->biodata) === 0) {
                return redirect()->route('siswa.biodata')
                    ->with('error', 'Silakan isi biodata Anda terlebih dahulu sebelum memilih paket bimbel.');
            }
        }
        return view('siswa.regisKategory', compact('siswa'));
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
            if (empty($siswa->biodata) || empty($siswa->bukti_transfer)) {
                return redirect()->route('siswa.biodata');
            }
        }

        $paket = $siswa ? PaketBelajar::find($siswa->paket_id) : null;

        $waMessage = "Halo Admin Paradise of Math,\n\nSaya telah mengunggah bukti transfer pembayaran pendaftaran bimbingan belajar. Berikut adalah rincian data diri saya:\n\n";
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
        $waMessage .= "\nMohon bantuan untuk melakukan verifikasi bukti transfer dan aktivasi akun belajar saya. Terima kasih!";

        $waUrl = "https://wa.me/6282284260507?text=" . rawurlencode($waMessage);

        return view('siswa.pending', compact('siswa', 'paket', 'waUrl'));
    }

    /**
     * Tampilkan Halaman Checkout Pembayaran.
     */
    public function showPayment(Request $request)
    {
        $siswa = auth()->guard('siswa')->user();
        if ($siswa) {
            if ($siswa->status === 'active') {
                return redirect()->route('siswa.dashboard');
            }
            if ($siswa->status === 'under_review' || !empty($siswa->bukti_transfer)) {
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
        $totalSesi = 0;
        if (is_array($sesiPerMapel)) {
            foreach ($sesiPerMapel as $s) {
                $totalSesi += (int) $s;
            }
        }

        // Fallback: jika sesi[] kosong, hitung dari jumlah mapel yang dikirim
        if ($totalSesi === 0) {
            $mapels = $request->input('mapel', []);
            if (is_array($mapels)) {
                foreach ($mapels as $m) {
                    if (is_string($m) && preg_match('/(\d+)x/i', $m, $matches)) {
                        $totalSesi += (int) $matches[1];
                    }
                }
            }
            $totalSesi = $totalSesi > 0 ? $totalSesi : 1;
        }

        // Total harga = harga_per_sesi × total_sesi
        $total = $harga * $totalSesi;

        // Kumpulkan data mapel-jadwal untuk ditampilkan di payment review
        $mapelJadwal  = $request->input('mapel_jadwal', []);   // ['Fisika', 'Biologi', …]
        $hariPerMapel = $request->input('hari', []);            // [0 => [1=>'Senin', 2=>'Rabu'], …]
        $tanggalArr   = $request->input('tanggal_mulai', []);   // [0 => '2026-08-10', …]

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
     * Proses Submission Bukti Transfer Pembayaran Siswa.
     */
    public function submitPayment(Request $request)
    {
        $request->validate([
            'paket_id'       => ['required', 'exists:paket_belajar,id'],
            'tipe_paket'     => ['required'],
            'payment_method' => ['required', 'in:bank,ewallet'],
            'bukti_transfer' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ], [
            'bukti_transfer.required' => 'Bukti transfer pembayaran wajib diunggah.',
            'bukti_transfer.file'     => 'Bukti transfer harus berupa file valid.',
            'bukti_transfer.mimes'    => 'Format file bukti transfer harus berupa JPG, PNG, atau PDF.',
            'bukti_transfer.max'      => 'Ukuran file bukti transfer maksimal adalah 2MB.',
        ]);

        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        if ($request->hasFile('bukti_transfer')) {
            $file     = $request->file('bukti_transfer');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());

            if (!file_exists(public_path('uploads/bukti_transfer'))) {
                mkdir(public_path('uploads/bukti_transfer'), 0777, true);
            }
            $file->move(public_path('uploads/bukti_transfer'), $filename);

            // Detail string dari tipe paket
            $paket = PaketBelajar::find($request->paket_id);
            $detailString = '';
            if ($request->tipe_paket == '1')     $detailString = $paket->detail_1;
            elseif ($request->tipe_paket == '2') $detailString = $paket->detail_2;
            elseif ($request->tipe_paket == '3') $detailString = $paket->detail_3;
            elseif ($request->tipe_paket == '4') $detailString = $paket->detail_4;

            // ── Baca field per-mapel baru ──
            $mapelJadwal  = $request->input('mapel_jadwal', []);  // ['Fisika', 'Biologi']
            $sesiPerMapel = $request->input('sesi', []);           // [0 => 9, 1 => 4]
            $hariPerMapel = $request->input('hari', []);           // [0 => [1=>'Senin', 2=>'Rabu'], 1 => [...]]
            $tanggalArr   = $request->input('tanggal_mulai', []); // [0 => '2026-08-10', ...]
            $guruMatematika = $request->input('pilihan_guru');
            $guruInggris    = $request->input('pilihan_guru_inggris');

            // Pastikan semua adalah array
            if (!is_array($mapelJadwal))  $mapelJadwal  = [];
            if (!is_array($sesiPerMapel)) $sesiPerMapel = [];
            if (!is_array($hariPerMapel)) $hariPerMapel = [];
            if (!is_array($tanggalArr))   $tanggalArr   = [$tanggalArr];

            // Hitung total sesi
            $totalSesi = array_sum(array_map('intval', $sesiPerMapel));
            if ($totalSesi === 0) $totalSesi = 1;

            // Bangun ringkasan jadwal per mapel
            $jadwalSummary = [];
            foreach ($mapelJadwal as $idx => $namaMapel) {
                $sesi    = (int)($sesiPerMapel[$idx] ?? 0);
                $hari1   = $hariPerMapel[$idx][1] ?? '-';
                $hari2   = $hariPerMapel[$idx][2] ?? '-';
                $tanggal = $tanggalArr[$idx] ?? null;
                $tanggalStr = $tanggal ? date('d-m-Y', strtotime($tanggal)) : '-';

                $jadwalSummary[] = "{$namaMapel}: {$sesi}x sesi | Hari: {$hari1} & {$hari2} | Mulai: {$tanggalStr}";
            }

            // Bangun extra details string
            $extraDetails = [];
            if (!empty($mapelJadwal)) {
                $extraDetails[] = 'Mapel: ' . implode(', ', $mapelJadwal);
            }
            $guruSelected = [];
            if ($guruMatematika) $guruSelected[] = 'Math: ' . $guruMatematika;
            if ($guruInggris)    $guruSelected[] = 'English: ' . $guruInggris;
            if (!empty($guruSelected)) {
                $extraDetails[] = 'Guru: ' . implode(', ', $guruSelected);
            }
            $extraDetails[] = 'Total Sesi: ' . $totalSesi . 'x';
            if (!empty($jadwalSummary)) {
                $extraDetails[] = 'Jadwal: ' . implode(' || ', $jadwalSummary);
            }

            $finalTipePaket = $detailString;
            if (!empty($extraDetails)) {
                $finalTipePaket .= ' (' . implode(' | ', $extraDetails) . ')';
            }

            // Simpan jadwal ke dalam biodata JSON
            $biodata = $siswa->biodata ?? [];
            $biodata['mapel_jadwal']  = $mapelJadwal;
            $biodata['sesi_per_mapel'] = array_map('intval', $sesiPerMapel);
            $biodata['hari_per_mapel'] = $hariPerMapel;
            $biodata['tanggal_mulai_per_mapel'] = $tanggalArr;
            $biodata['jumlah_pertemuan'] = $totalSesi;
            // Flatten hari untuk kompatibilitas backward
            $allHari = [];
            foreach ($hariPerMapel as $h) {
                if (is_array($h)) {
                    foreach ($h as $hari) {
                        if ($hari) $allHari[] = $hari;
                    }
                }
            }
            $biodata['hari_pertemuan'] = array_unique($allHari);
            $biodata['tanggal_mulai']  = $tanggalArr[0] ?? null;

            $siswa->update([
                'paket_id'       => $request->paket_id,
                'tipe_paket'     => $finalTipePaket,
                'bukti_transfer' => 'uploads/bukti_transfer/' . $filename,
                'status'         => 'under_review',
                'biodata'        => $biodata,
            ]);

            // Notifikasi Admin
            $title   = "Pendaftaran Siswa Baru";
            $message = "Siswa " . $siswa->name . " telah mengunggah bukti transfer untuk bimbingan belajar.";
            $link    = route('admin.siswa.approve.index');

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
        }

        return redirect()->route('siswa.pending')
            ->with('success', 'Bukti transfer berhasil diunggah! Pendaftaran Anda akan diverifikasi oleh Admin dalam 1x24 jam.');
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

        $biodata = $siswa->biodata ?? [];

        // ── Data per-mapel baru ──
        $mapelJadwal     = $biodata['mapel_jadwal'] ?? [];
        $sesiPerMapel    = $biodata['sesi_per_mapel'] ?? [];
        $hariPerMapel    = $biodata['hari_per_mapel'] ?? [];
        $tanggalPerMapel = $biodata['tanggal_mulai_per_mapel'] ?? [];

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

        // Tanggal mulai fallback: ambil yang terlama dari per-mapel
        if (!$tanggalMulai && !empty($tanggalPerMapel)) {
            $tanggalMulai = min(array_filter($tanggalPerMapel));
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
        $durationMinutes = 90;
        if ($paket && preg_match('/(\d+)\s*menit/i', $paket->detail_5 ?? '', $dM)) {
            $durationMinutes = (int) $dM[1];
        }
        $jamSelesai = date('H:i', strtotime($jamMulai . " + {$durationMinutes} minutes"));

        // Guru
        $gurus   = [];
        $hasGuru = false;
        if ($siswa->tipe_paket && preg_match('/Guru:\s*([^)|]+)/i', $siswa->tipe_paket, $m)) {
            $gurus = array_map('trim', explode(',', $m[1]));
            foreach ($gurus as $g) {
                if (!empty($g) && $g !== '-' && strtolower($g) !== 'belum ditentukan') {
                    $hasGuru = true;
                }
            }
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
    public function showInvoice()
    {
        $siswa = auth()->guard('siswa')->user();
        if ($redirect = $this->checkActiveStatus($siswa)) {
            return $redirect;
        }

        $paket = $siswa->paket;
        $biodata = $siswa->biodata ?? [];
        
        // Parse details for invoice
        $hariPertemuan = $biodata['hari_pertemuan'] ?? [];
        $hariPerMapel  = $biodata['hari_per_mapel'] ?? [];
        $jumlahPertemuan = $biodata['jumlah_pertemuan'] ?? null;
        $tanggalMulai = $biodata['tanggal_mulai'] ?? null;

        // Fallback parsing from tipe_paket if hariPerMapel is empty
        if (empty($hariPerMapel) && $siswa->tipe_paket) {
            if (preg_match_all('/Hari:\s*([^|)]+)/i', $siswa->tipe_paket, $matchesAll)) {
                foreach ($matchesAll[1] as $mIdx => $hariStr) {
                    $cleanDays = array_map('trim', explode('&', $hariStr));
                    $hariPerMapel[$mIdx] = $cleanDays;
                }
            }
        }

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
            // Find which detail match
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

        // Get mapel
        $mapels = [];
        if (!empty($biodata['mapel_jadwal'])) {
            $mapels = $biodata['mapel_jadwal'];
        } elseif ($siswa->tipe_paket && preg_match('/Mapel:\s*([^)|]+)/i', $siswa->tipe_paket, $matches)) {
            $mapels = array_map('trim', explode(',', $matches[1]));
        }

        // Get guru
        $gurus = [];
        if ($siswa->tipe_paket && preg_match('/Guru:\s*([^)|]+)/i', $siswa->tipe_paket, $matches)) {
            $gurus = array_map('trim', explode(',', $matches[1]));
        }

        return view('siswa.invoice', compact('siswa', 'paket', 'hariPertemuan', 'hariPerMapel', 'jumlahPertemuan', 'tanggalMulai', 'hargaPerSesi', 'totalHarga', 'mapels', 'gurus'));
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

        return view('siswa.riwayat', compact('siswa', 'paket', 'hariPertemuan', 'jumlahPertemuan', 'tanggalMulai', 'hargaPerSesi', 'totalHarga'));
    }
}
