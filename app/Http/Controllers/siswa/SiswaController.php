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
    public function showBiodata()
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
            // Save all other form inputs in the biodata array
            $siswa->biodata = $request->except(['_token', 'no_hp', 'sekolah', 'nama_lengkap']);
            $siswa->save();
        }

        return redirect()->route('siswa.register-kategori')
            ->with('success', 'Biodata berhasil disimpan! Silakan pilih paket bimbel Anda.');
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
        }
        return view('siswa.regisKategory');
    }

    /**
     * Tampilkan Halaman Status Pembayaran Pending.
     */
    public function showPending()
    {
        $siswa = auth()->guard('siswa')->user();
        if ($siswa && $siswa->status === 'active') {
            return redirect()->route('siswa.dashboard');
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

        $paketId = $request->input('paket_id');
        $tipePaket = $request->input('tipe_paket', '1');

        $paket = PaketBelajar::find($paketId) ?? PaketBelajar::first();

        $detailString = '';
        if ($tipePaket == '1')
            $detailString = $paket->detail_1;
        elseif ($tipePaket == '2')
            $detailString = $paket->detail_2;
        elseif ($tipePaket == '3')
            $detailString = $paket->detail_3;
        elseif ($tipePaket == '4')
            $detailString = $paket->detail_4;

        $harga = $this->extractPrice($detailString, $paket ? $paket->harga_max : 450000);
        
        $jumlahPertemuan = (int)$request->input('jumlah_pertemuan', 0);
        if ($jumlahPertemuan === 0) {
            $totalShifts = 0;
            $mapels = $request->input('mapel', []);
            if (is_array($mapels)) {
                foreach ($mapels as $m) {
                    if (preg_match('/(\d+)x/i', $m, $matches)) {
                        $totalShifts += (int)$matches[1];
                    }
                }
            }
            $jumlahPertemuan = $totalShifts > 0 ? $totalShifts : 1;
        }
        $total = $harga * $jumlahPertemuan;

        $banks = \App\Models\Rekening::where('tipe', 'bank')->get();
        $ewallets = \App\Models\Rekening::where('tipe', 'ewallet')->get();

        return view('siswa.payment', compact('paket', 'detailString', 'harga', 'total', 'banks', 'ewallets'));
    }

    /**
     * Proses Submission Bukti Transfer Pembayaran Siswa.
     */
    public function submitPayment(Request $request)
    {
        $request->validate([
            'paket_id' => ['required', 'exists:paket_belajar,id'],
            'tipe_paket' => ['required'],
            'payment_method' => ['required', 'in:bank,ewallet'],
            'bukti_transfer' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'tanggal_mulai' => ['nullable', 'date'],
        ], [
            'bukti_transfer.required' => 'Bukti transfer pembayaran wajib diunggah.',
            'bukti_transfer.file' => 'Bukti transfer harus berupa file valid.',
            'bukti_transfer.mimes' => 'Format file bukti transfer harus berupa JPG, PNG, atau PDF.',
            'bukti_transfer.max' => 'Ukuran file bukti transfer maksimal adalah 2MB.',
        ]);

        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());

            // Buat folder public/uploads/bukti_transfer jika belum ada
            if (!file_exists(public_path('uploads/bukti_transfer'))) {
                mkdir(public_path('uploads/bukti_transfer'), 0777, true);
            }

            $file->move(public_path('uploads/bukti_transfer'), $filename);

            // Dapatkan detail string pilihan
            $paket = PaketBelajar::find($request->paket_id);
            $detailString = '';
            if ($request->tipe_paket == '1')
                $detailString = $paket->detail_1;
            elseif ($request->tipe_paket == '2')
                $detailString = $paket->detail_2;
            elseif ($request->tipe_paket == '3')
                $detailString = $paket->detail_3;
            elseif ($request->tipe_paket == '4')
                $detailString = $paket->detail_4;

            // Build a string for mapel & guru details
            $mapelList = $request->input('mapel', []);
            $guruMatematika = $request->input('pilihan_guru');
            $guruInggris = $request->input('pilihan_guru_inggris');
            $jumlahPertemuan = $request->input('jumlah_pertemuan');
            $hariPertemuan = $request->input('hari_pertemuan', []);
            $tanggalMulai = $request->input('tanggal_mulai');

            $extraDetails = [];
            if (!empty($mapelList)) {
                $extraDetails[] = 'Mapel: ' . implode(', ', $mapelList);
            }
            
            $guruSelected = [];
            if ($guruMatematika) {
                $guruSelected[] = 'Math: ' . $guruMatematika;
            }
            if ($guruInggris) {
                $guruSelected[] = 'English: ' . $guruInggris;
            }
            if (!empty($guruSelected)) {
                $extraDetails[] = 'Guru: ' . implode(', ', $guruSelected);
            }

            if ($jumlahPertemuan) {
                $extraDetails[] = 'Sesi: ' . $jumlahPertemuan . 'x';
            }

            if (!empty($hariPertemuan)) {
                $extraDetails[] = 'Hari: ' . implode(', ', $hariPertemuan);
            }

            if ($tanggalMulai) {
                $extraDetails[] = 'Mulai: ' . date('d-m-Y', strtotime($tanggalMulai));
            }

            $finalTipePaket = $detailString;
            if (!empty($extraDetails)) {
                $finalTipePaket .= ' (' . implode(' | ', $extraDetails) . ')';
            }

            // Simpan pendaftaran ke database dengan array biodata diperbarui
            $biodata = $siswa->biodata ?? [];
            if (!empty($hariPertemuan)) {
                $biodata['hari_pertemuan'] = $hariPertemuan;
            }
            if ($tanggalMulai) {
                $biodata['tanggal_mulai'] = $tanggalMulai;
            }
            if ($jumlahPertemuan) {
                $biodata['jumlah_pertemuan'] = (int)$jumlahPertemuan;
            }

            $siswa->update([
                'paket_id' => $request->paket_id,
                'tipe_paket' => $finalTipePaket,
                'bukti_transfer' => 'uploads/bukti_transfer/' . $filename,
                'status' => 'under_review',
                'biodata' => $biodata,
            ]);

            // Buat Notifikasi Database untuk Admin
            $title = "Pendaftaran Siswa Baru";
            $message = "Siswa " . $siswa->name . " telah mengunggah bukti transfer untuk bimbingan belajar.";
            $link = route('admin.siswa.approve.index');

            \Illuminate\Support\Facades\DB::table('notifications')->insert([
                'title' => $title,
                'message' => $message,
                'link' => $link,
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Kirim Push Notification FCM ke Admin
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
     * Tampilkan Halaman Jadwal Belajar Siswa (Kalender).
     */
    public function showJadwal()
    {
        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('login');
        }

        // Ambil data jadwal dari kolom biodata
        $biodata = $siswa->biodata ?? [];
        $hariPertemuan = $biodata['hari_pertemuan'] ?? [];
        $tanggalMulai = $biodata['tanggal_mulai'] ?? null;
        $jumlahPertemuan = $biodata['jumlah_pertemuan'] ?? null;

        // Fallback parsing dari tipe_paket jika di biodata kosong
        if (empty($hariPertemuan) && $siswa->tipe_paket) {
            if (preg_match('/Hari:\s*([^)|]+)/i', $siswa->tipe_paket, $matches)) {
                $hariPertemuan = array_map('trim', explode(',', $matches[1]));
            }
            if (preg_match('/Mulai:\s*([\d\-]+)/i', $siswa->tipe_paket, $matches)) {
                $d = trim($matches[1]);
                if (preg_match('/(\d{2})-(\d{2})-(\d{4})/', $d, $dMatches)) {
                    $tanggalMulai = $dMatches[3] . '-' . $dMatches[2] . '-' . $dMatches[1];
                }
            }
        }

        // Tambahan parse jumlahPertemuan dari tipe_paket jika di biodata kosong
        if (!$jumlahPertemuan && $siswa->tipe_paket) {
            if (preg_match('/Sesi:\s*(\d+)x/i', $siswa->tipe_paket, $matches)) {
                $jumlahPertemuan = (int) $matches[1];
            }
        }

        // Jika tanggal mulai belum ditentukan (untuk siswa lama), gunakan tanggal pendaftaran
        if (!$tanggalMulai) {
            $tanggalMulai = $siswa->created_at ? $siswa->created_at->format('Y-m-d') : date('Y-m-d');
        }

        // Ambil mapel terpilih dari tipe_paket
        $mapels = [];
        if ($siswa->tipe_paket && preg_match('/Mapel:\s*([^)|]+)/i', $siswa->tipe_paket, $matches)) {
            $mapels = array_map('trim', explode(',', $matches[1]));
        }

        // Ambil jam mulai dari paket, dan hitung jam selesai dari durasi detail_5
        $paket = $siswa->paket;
        $jamMulai = $paket ? ($paket->jam_mulai ?? '15:30') : '15:30';
        $durationMinutes = 90;
        if ($paket && preg_match('/(\d+)\s*menit/i', $paket->detail_5, $durationMatches)) {
            $durationMinutes = (int) $durationMatches[1];
        }
        $jamSelesai = date('H:i', strtotime($jamMulai . " + {$durationMinutes} minutes"));

        // Parse Guru dari tipe_paket
        $gurus = [];
        $hasGuru = false;
        if ($siswa->tipe_paket && preg_match('/Guru:\s*([^)|]+)/i', $siswa->tipe_paket, $matches)) {
            $gurus = array_map('trim', explode(',', $matches[1]));
            foreach ($gurus as $g) {
                if (!empty($g) && $g !== '-' && strtolower($g) !== 'belum ditentukan') {
                    $hasGuru = true;
                }
            }
        }

        return view('siswa.jadwal', compact('siswa', 'hariPertemuan', 'tanggalMulai', 'mapels', 'jumlahPertemuan', 'jamMulai', 'jamSelesai', 'hasGuru', 'gurus'));
    }

    /**
     * Tampilkan Halaman Data Akademik (Biodata terdaftar) Siswa.
     */
    public function showAkademik()
    {
        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('login');
        }

        return view('siswa.dataAkademik', compact('siswa'));
    }

    /**
     * Tampilkan Halaman Invoice Belajar Siswa.
     */
    public function showInvoice()
    {
        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('login');
        }

        $paket = $siswa->paket;
        $biodata = $siswa->biodata ?? [];
        
        // Parse details for invoice
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
        if ($siswa->tipe_paket && preg_match('/Mapel:\s*([^)|]+)/i', $siswa->tipe_paket, $matches)) {
            $mapels = array_map('trim', explode(',', $matches[1]));
        }

        // Get guru
        $gurus = [];
        if ($siswa->tipe_paket && preg_match('/Guru:\s*([^)|]+)/i', $siswa->tipe_paket, $matches)) {
            $gurus = array_map('trim', explode(',', $matches[1]));
        }

        return view('siswa.invoice', compact('siswa', 'paket', 'hariPertemuan', 'jumlahPertemuan', 'tanggalMulai', 'hargaPerSesi', 'totalHarga', 'mapels', 'gurus'));
    }
}
