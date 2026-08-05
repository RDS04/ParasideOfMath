<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\Models\PaketBelajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Tampilkan Dashboard Admin.
     */
    public function index()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }
        return view('admin.dashboard');
    }

    /**
     * Tampilkan form kelola harga paket belajar.
     */
    public function inputPrice()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }
        $packages = PaketBelajar::all();
        return view('admin.inputPrice', compact('packages'));
    }

    /**
     * Simpan perubahan paket belajar.
     */
    public function updatePrice(Request $request, $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $paket = PaketBelajar::findOrFail($id);

        $request->validate([
            'nama_paket' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'harga_min' => ['required', 'integer', 'min:0'],
            'harga_max' => ['required', 'integer', 'min:0'],
            'detail_1' => ['nullable', 'string', 'max:255'],
            'detail_2' => ['nullable', 'string', 'max:255'],
            'detail_3' => ['nullable', 'string', 'max:255'],
            'detail_4' => ['nullable', 'string', 'max:255'],
            'detail_5' => ['nullable', 'string', 'max:255'],
            'jam_mulai' => ['nullable', 'string', 'max:10'],
        ]);

        // If this package is marked as popular, remove popularity from other packages
        if ($request->has('is_populer')) {
            PaketBelajar::where('id', '!=', $id)->update(['is_populer' => false]);
            $paket->is_populer = true;
        } else {
            $paket->is_populer = false;
        }

        $paket->update([
            'nama_paket' => $request->nama_paket,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'harga_min' => $request->harga_min,
            'harga_max' => $request->harga_max,
            'detail_1' => $request->detail_1,
            'detail_2' => $request->detail_2,
            'detail_3' => $request->detail_3,
            'detail_4' => $request->detail_4,
            'detail_5' => $request->detail_5,
            'jam_mulai' => $request->jam_mulai ?? '15:30',
        ]);

        return back()->with('success', 'Paket ' . $paket->nama_paket . ' berhasil diupdate!');
    }

    /**
     * Tampilkan form kelola rekening / e-wallet.
     */
    public function inputRekening()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }
        $rekening = \App\Models\Rekening::all();
        return view('admin.inputRekening', compact('rekening'));
    }

    /**
     * Simpan rekening / e-wallet baru.
     */
    public function storeRekening(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $request->validate([
            'tipe' => ['required', 'in:bank,ewallet'],
            'nama_bank' => ['required', 'string', 'max:255'],
            'nomor_rekening' => ['required', 'string', 'max:255'],
            'atas_nama' => ['required', 'string', 'max:255'],
        ]);

        \App\Models\Rekening::create([
            'tipe' => $request->tipe,
            'nama_bank' => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama' => $request->atas_nama,
        ]);

        return back()->with('success', 'Rekening baru berhasil disimpan!');
    }

    /**
     * Update data rekening / e-wallet yang ada.
     */
    public function updateRekening(Request $request, $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $rekening = \App\Models\Rekening::findOrFail($id);

        $request->validate([
            'tipe' => ['required', 'in:bank,ewallet'],
            'nama_bank' => ['required', 'string', 'max:255'],
            'nomor_rekening' => ['required', 'string', 'max:255'],
            'atas_nama' => ['required', 'string', 'max:255'],
        ]);

        $rekening->update([
            'tipe' => $request->tipe,
            'nama_bank' => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama' => $request->atas_nama,
        ]);

        return back()->with('success', 'Rekening ' . $rekening->nama_bank . ' berhasil diupdate!');
    }

    /**
     * Hapus rekening / e-wallet.
     */
    public function deleteRekening($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $rekening = \App\Models\Rekening::findOrFail($id);
        $rekening->delete();

        return back()->with('success', 'Rekening berhasil dihapus!');
    }

    /**
     * Tampilkan Halaman Persetujuan Bukti Transfer Siswa.
     */
    public function approvSiswa()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        // Fetch students ordered so that 'under_review' comes first
        $students = \App\Models\Siswa::orderByRaw("CASE WHEN status = 'under_review' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.approvSiswa', compact('students'));
    }

    /**
     * Setujui pendaftaran dan pembayaran siswa.
     */
    public function submitApprovSiswa($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $siswa = \App\Models\Siswa::findOrFail($id);
        $siswa->update([
            'status' => 'active',
        ]);

        return back()->with('success', 'Akun pendaftaran ' . $siswa->name . ' berhasil disetujui dan diaktifkan!');
    }

    /**
     * Tolak pendaftaran dan pembayaran siswa.
     */
    public function rejectSiswa($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $siswa = \App\Models\Siswa::findOrFail($id);
        $siswa->update([
            'status' => 'rejected',
            'bukti_transfer' => null, // clear payment proof to allow re-upload
        ]);

        return back()->with('success', 'Pendaftaran ' . $siswa->name . ' telah ditolak. Bukti transfer telah dikosongkan agar siswa dapat mengunggah ulang pembayaran baru.');
    }

    /**
     * Tampilkan Halaman Detail Data Registrasi Siswa.
     */
    public function detailSiswa($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $student = \App\Models\Siswa::findOrFail($id);
        $paket = \App\Models\PaketBelajar::find($student->paket_id);
        $gurusList = \App\Models\Guru::with('user')->get();

        return view('admin.detailData', compact('student', 'paket', 'gurusList'));
    }

    /**
     * Atur / assign guru pendamping untuk siswa.
     */
    public function assignTutor(Request $request, $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $request->validate([
            'tutors' => ['required', 'array'],
            'tutors.*' => ['string', 'max:255'],
        ]);

        $siswa = \App\Models\Siswa::findOrFail($id);
        
        $tutorsSelected = $request->tutors; // e.g. ["Budi", "Asep"]
        $tutorsStr = implode(', ', $tutorsSelected);

        // Update biodata
        $biodata = $siswa->biodata ?? [];
        $biodata['tutor_names'] = $tutorsSelected;

        // Update tipe_paket descriptor (e.g. "Guru: Belum ditentukan" -> "Guru: Budi, Asep")
        if ($siswa->tipe_paket) {
            // Check if "Guru: " already exists in tipe_paket
            if (preg_match('/Guru:\s*([^|)]+)/i', $siswa->tipe_paket)) {
                $newTipePaket = preg_replace('/Guru:\s*([^|)]+)/i', 'Guru: ' . $tutorsStr, $siswa->tipe_paket);
            } else {
                // If it doesn't exist, append it
                // e.g. "Hari: Senin | Sesi: 8x" -> "Hari: Senin | Sesi: 8x | Guru: Budi, Asep"
                $newTipePaket = $siswa->tipe_paket . ' | Guru: ' . $tutorsStr;
            }
            $siswa->tipe_paket = trim($newTipePaket);
        } else {
            $siswa->tipe_paket = 'Guru: ' . $tutorsStr;
        }

        $siswa->update([
            'biodata' => $biodata,
            'tipe_paket' => $siswa->tipe_paket,
        ]);

        return back()->with('success', 'Guru pendamping untuk ' . $siswa->name . ' berhasil diatur menjadi: ' . $tutorsStr);
    }

    /**
     * Update hari bimbingan / bimbel siswa.
     */
    public function updateBimbelDays(Request $request, $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $request->validate([
            'hari_pertemuan' => ['required', 'array'],
            'hari_pertemuan.*' => ['string', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],
        ]);

        $siswa = \App\Models\Siswa::findOrFail($id);
        $biodata = $siswa->biodata ?? [];
        $biodata['hari_pertemuan'] = $request->hari_pertemuan;

        // Sync with tipe_paket string descriptor (e.g. "Hari: Senin, Rabu | ...")
        if ($siswa->tipe_paket) {
            $daysStr = implode(', ', $request->hari_pertemuan);
            $newTipePaket = preg_replace('/Hari:\s*([^|]+)/i', 'Hari: ' . $daysStr . ' ', $siswa->tipe_paket);
            $siswa->tipe_paket = trim($newTipePaket);
        }

        $siswa->update([
            'biodata' => $biodata,
        ]);

        return back()->with('success', 'Jadwal hari bimbingan bimbel untuk ' . $siswa->name . ' berhasil diperbarui!');
    }

    /**
     * Tampilkan Halaman Daftar Siswa Keseluruhan.
     */
    public function daftarSiswa()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        // Fetch all students (e.g. active status or all sorted by registration date)
        $students = \App\Models\Siswa::orderBy('created_at', 'desc')->get();

        return view('admin.daftarSiswa', compact('students'));
    }

    /**
     * Tampilkan Halaman Daftar Guru/Tutor Keseluruhan.
     */
    public function daftarGuru()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        // Fetch all tutors with their user details
        $gurus = \App\Models\Guru::with('user')->orderBy('created_at', 'desc')->get();

        return view('admin.daftarGuru', compact('gurus'));
    }

    /**
     * Tampilkan Halaman Detail Guru/Tutor.
     */
    public function detailGuru($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $guru = \App\Models\Guru::with('user')->findOrFail($id);
        
        $gName = $guru->user->name ?? '';
        $siswaBimbingan = [];
        if ($gName) {
            $siswaBimbingan = \App\Models\Siswa::where('tipe_paket', 'LIKE', '%' . $gName . '%')
                ->orWhereJsonContains('biodata->tutor_names', $gName)
                ->get();
        }

        return view('guru.detail', compact('guru', 'siswaBimbingan'));
    }


    public function inputMapel()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }
        $mapels = \App\Models\Mapel::all();
        return view('admin.inputMapel', compact('mapels'));
    }

    public function storeMapel(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $request->validate([
            'nama_mapel' => ['required', 'string', 'max:255'],
            'shift' => ['required', 'integer', 'min:1'],
        ]);

        \App\Models\Mapel::create([
            'nama_mapel' => $request->nama_mapel,
            'shift' => $request->shift,
        ]);

        return back()->with('success', 'Mata Pelajaran baru berhasil disimpan!');
    }

    public function updateMapel(Request $request, $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $mapel = \App\Models\Mapel::findOrFail($id);

        $request->validate([
            'nama_mapel' => ['required', 'string', 'max:255'],
            'shift' => ['required', 'integer', 'min:1'],
        ]);

        $mapel->update([
            'nama_mapel' => $request->nama_mapel,
            'shift' => $request->shift,
        ]);

        return back()->with('success', 'Mata Pelajaran ' . $mapel->nama_mapel . ' berhasil diupdate!');
    }

    public function deleteMapel($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $mapel = \App\Models\Mapel::findOrFail($id);
        $mapel->delete();

        return back()->with('success', 'Mata Pelajaran berhasil dihapus!');
    }

    public function tambahSiswa()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        return view('admin.tambahSiswa');
    }

    /**
     * Simpan token FCM dari Admin browser.
     */
    public function saveFcmToken(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
        ]);

        if (Auth::check() && Auth::user()->isAdmin()) {
            \Illuminate\Support\Facades\DB::table('fcm_tokens')->updateOrInsert(
                ['user_id' => Auth::id(), 'token' => $request->token],
                ['updated_at' => now(), 'created_at' => now()]
            );
            return response()->json(['message' => 'FCM Token berhasil disimpan']);
        }

        return response()->json(['error' => 'Akses ditolak'], 403);
    }

    /**
     * Ambil daftar notifikasi untuk header navbar Admin.
     */
    public function getNotifications()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        $notifications = \Illuminate\Support\Facades\DB::table('notifications')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $unreadCount = \Illuminate\Support\Facades\DB::table('notifications')
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Tandai semua notifikasi telah dibaca.
     */
    public function markNotificationsRead()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        \Illuminate\Support\Facades\DB::table('notifications')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['message' => 'Notifikasi ditandai telah dibaca']);
    }

    /**
     * Tampilkan Semua Riwayat Transaksi Pembayaran Siswa.
     */
    public function allRiwayatPayment()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $siswas = \App\Models\Siswa::with('paket')
            ->whereNotNull('bukti_transfer')
            ->where('bukti_transfer', '!=', '')
            ->orderBy('updated_at', 'desc')
            ->get();

        $payments = $siswas->map(function ($siswa) {
            $paket = $siswa->paket;
            $biodata = $siswa->biodata ?? [];
            
            $hariPertemuan = $biodata['hari_pertemuan'] ?? [];
            $jumlahPertemuan = $biodata['jumlah_pertemuan'] ?? null;
            $tanggalMulai = $biodata['tanggal_mulai'] ?? null;
            
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
            
            $detailString = '';
            if ($siswa->tipe_paket && $paket) {
                if (str_contains($siswa->tipe_paket, $paket->detail_1)) $detailString = $paket->detail_1;
                elseif (str_contains($siswa->tipe_paket, $paket->detail_2)) $detailString = $paket->detail_2;
                elseif (str_contains($siswa->tipe_paket, $paket->detail_3)) $detailString = $paket->detail_3;
                elseif (str_contains($siswa->tipe_paket, $paket->detail_4)) $detailString = $paket->detail_4;
            }
            
            $hargaPerSesi = 0;
            if ($detailString) {
                preg_match_all('/\d+/', str_replace('.', '', $detailString), $numbers);
                if (!empty($numbers[0])) {
                    $hargaPerSesi = (int) $numbers[0][0];
                }
            }
            if ($hargaPerSesi === 0 && $paket) {
                $hargaPerSesi = $paket->harga_max;
            }
            if ($hargaPerSesi === 0) {
                $hargaPerSesi = 450000; // default
            }
            
            $totalHarga = $hargaPerSesi * ($jumlahPertemuan ?: 1);
            
            return (object) [
                'id' => $siswa->id,
                'name' => $siswa->name,
                'email' => $siswa->email,
                'whatsapp' => $siswa->whatsapp,
                'nama_paket' => $paket ? $paket->nama_paket : 'Pendaftaran Bimbel',
                'tipe_paket' => $siswa->tipe_paket,
                'total_bayar' => $totalHarga,
                'harga_sesi' => $hargaPerSesi,
                'jumlah_pertemuan' => $jumlahPertemuan ?: 1,
                'hari_pertemuan' => implode(', ', (array) $hariPertemuan),
                'tanggal_mulai' => $tanggalMulai ?: '-',
                'bukti_transfer' => $siswa->bukti_transfer,
                'status' => $siswa->status,
                'tanggal' => $siswa->updated_at ? $siswa->updated_at->format('d M Y H:i') : ($siswa->created_at ? $siswa->created_at->format('d M Y H:i') : '-'),
            ];
        });

        return view('admin.allRiwayatPayment', compact('payments'));
    }
}
