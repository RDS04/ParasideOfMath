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
            if ($siswa->status === 'under_review') {
                return redirect()->route('siswa.pending');
            }
            if ($siswa->status === 'active') {
                return redirect()->route('siswa.dashboard');
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
            if ($siswa->status === 'under_review') {
                return redirect()->route('siswa.pending');
            }
            if ($siswa->status === 'active') {
                return redirect()->route('siswa.dashboard');
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
            if ($siswa->status === 'under_review') {
                return redirect()->route('siswa.pending');
            }
            if ($siswa->status === 'active') {
                return redirect()->route('siswa.dashboard');
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
        $total = $harga;

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

            // Simpan pendaftaran ke database
            $siswa->update([
                'paket_id' => $request->paket_id,
                'tipe_paket' => $detailString,
                'bukti_transfer' => 'uploads/bukti_transfer/' . $filename,
                'status' => 'under_review',
            ]);
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
}
