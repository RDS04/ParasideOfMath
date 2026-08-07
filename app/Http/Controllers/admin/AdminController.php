<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Guru;
use App\Models\Mapel;
use App\Models\PaketBelajar;
use App\Models\Rekening;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Tampilkan Dashboard Admin.
     */
    public function index(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $filter = $request->input('filter', 'monthly');
        $year = (int) $request->input('year', now()->year);
        $year = $year > 0 ? $year : now()->year;

        $revenueData = $this->getRevenueChartData($filter, $year);

        return view('admin.dashboard', array_merge(
            compact('filter', 'year'),
            $revenueData
        ));
    }

    /**
     * Ambil data chart pendapatan yang dapat digunakan di dashboard dan laporan.
     */
    private function getRevenueChartData(string $filter, int $year): array
    {
        $paymentsQuery = Siswa::with('paket')
            ->whereNotNull('bukti_transfer')
            ->where('bukti_transfer', '!=', '')
            ->where('status', 'active');

        if ($filter === 'monthly') {
            $paymentsQuery->whereYear('updated_at', $year);
        }

        $payments = $paymentsQuery->get();

        $availableYears = Siswa::whereNotNull('bukti_transfer')
            ->where('bukti_transfer', '!=', '')
            ->where('status', 'active')
            ->selectRaw('YEAR(updated_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        if ($availableYears->isEmpty()) {
            $availableYears = collect([now()->year]);
        }

        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyTotals = array_fill(0, 12, 0);
        $yearlyData = [];

        $totalRevenue = 0;
        $paymentCount = 0;

        foreach ($payments as $siswa) {
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

            $hargaPerSesi = $this->extractPrice($detailString, $paket ? $paket->harga_max : 450000);
            $totalHarga = $hargaPerSesi * ($jumlahPertemuan ?: 1);

            $paymentDate = $siswa->updated_at ?? $siswa->created_at;
            $monthIndex = $paymentDate ? ((int) $paymentDate->format('n') - 1) : null;
            $paymentYear = $paymentDate ? (int) $paymentDate->format('Y') : now()->year;

            if ($filter === 'monthly' && $paymentYear === $year && $monthIndex !== null) {
                $monthlyTotals[$monthIndex] += $totalHarga;
            }

            if ($filter === 'yearly') {
                $yearlyData[$paymentYear] = ($yearlyData[$paymentYear] ?? 0) + $totalHarga;
            }

            $totalRevenue += $totalHarga;
            $paymentCount++;
        }

        if ($filter === 'yearly') {
            ksort($yearlyData);
            $chartLabels = array_map('strval', array_keys($yearlyData));
            $chartData = array_values($yearlyData);
        } else {
            $chartLabels = $monthlyLabels;
            $chartData = $monthlyTotals;
        }

        return [
            'availableYears' => $availableYears,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'totalRevenue' => $totalRevenue,
            'paymentCount' => $paymentCount,
        ];
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
        $rekening = Rekening::all();
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

        Rekening::create([
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

        $rekening = Rekening::findOrFail($id);

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

        $rekening = Rekening::findOrFail($id);
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
        $students = Siswa::orderByRaw("CASE WHEN status = 'under_review' THEN 0 ELSE 1 END")
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

        $siswa = Siswa::findOrFail($id);
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

        $siswa = Siswa::findOrFail($id);
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

        $student = Siswa::findOrFail($id);
        $paket = PaketBelajar::find($student->paket_id);
        $gurusList = Guru::with('user')->get();

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

        $siswa = Siswa::findOrFail($id);
        
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

        $siswa = Siswa::findOrFail($id);
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
        $students = Siswa::orderBy('created_at', 'desc')->get();

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
        $gurus = Guru::with('user')->orderBy('created_at', 'desc')->get();

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

        $guru = Guru::with('user')->findOrFail($id);
        
        $gName = $guru->user->name ?? '';
        $siswaBimbingan = [];
        if ($gName) {
            $siswaBimbingan = Siswa::where('tipe_paket', 'LIKE', '%' . $gName . '%')
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
        $mapels = Mapel::all();
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

        Mapel::create([
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

        $mapel = Mapel::findOrFail($id);

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

        $mapel = Mapel::findOrFail($id);
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

        $siswas = Siswa::with('paket')
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
            
            $hargaPerSesi = $this->extractPrice($detailString, $paket ? $paket->harga_max : 450000);
            
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

    /**
     * Tampilkan Laporan Pendapatan Pembayaran Siswa.
     */
    public function laporanPendapatan(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }
        $filter = $request->input('filter', 'monthly');
        $year = (int) $request->input('year', now()->year);
        $year = $year > 0 ? $year : now()->year;

        $paymentsQuery = Siswa::with('paket')
            ->whereNotNull('bukti_transfer')
            ->where('bukti_transfer', '!=', '')
            ->where('status', 'active');

        if ($filter === 'monthly') {
            $paymentsQuery->whereYear('updated_at', $year);
        }

        $payments = $paymentsQuery->get();

        $availableYears = Siswa::whereNotNull('bukti_transfer')
            ->where('bukti_transfer', '!=', '')
            ->where('status', 'active')
            ->selectRaw('YEAR(updated_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        if ($availableYears->isEmpty()) {
            $availableYears = collect([now()->year]);
        }

        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyTotals = array_fill(0, 12, 0);
        $yearlyData = [];

        $totalRevenue = 0;
        $paymentCount = 0;

        // Aggregates
        $packageTotals = []; // name => ['revenue'=>int, 'count'=>int]
        $tutorTotals = []; // tutor => ['sesi'=>int, 'siswa'=>int, 'revenue'=>int]

        foreach ($payments as $siswa) {
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

            $hargaPerSesi = $this->extractPrice($detailString, $paket ? $paket->harga_max : 450000);
            $totalHarga = $hargaPerSesi * ($jumlahPertemuan ?: 1);

            $paymentDate = $siswa->updated_at ?? $siswa->created_at;
            $monthIndex = $paymentDate ? ((int) $paymentDate->format('n') - 1) : null;
            $paymentYear = $paymentDate ? (int) $paymentDate->format('Y') : now()->year;

            if ($filter === 'monthly' && $paymentYear === $year && $monthIndex !== null) {
                $monthlyTotals[$monthIndex] += $totalHarga;
            }

            if ($filter === 'yearly') {
                $yearlyData[$paymentYear] = ($yearlyData[$paymentYear] ?? 0) + $totalHarga;
            }

            $totalRevenue += $totalHarga;
            $paymentCount++;

            // package aggregate
            $pname = $paket ? $paket->nama_paket : 'Umum';
            if (!isset($packageTotals[$pname])) {
                $packageTotals[$pname] = ['revenue' => 0, 'count' => 0];
            }
            $packageTotals[$pname]['revenue'] += $totalHarga;
            $packageTotals[$pname]['count'] += 1;

            // tutor aggregate — try biodata->tutor_names or parse tipe_paket
            $tutors = $biodata['tutor_names'] ?? [];
            if (empty($tutors) && $siswa->tipe_paket) {
                if (preg_match('/Guru:\s*([^|]+)/i', $siswa->tipe_paket, $m)) {
                    $tutors = array_map('trim', explode(',', $m[1]));
                }
            }
            if (empty($tutors)) {
                $tutors = ['Belum Ditentukan'];
            }

            $tutorCount = count($tutors) ?: 1;
            foreach ($tutors as $t) {
                if (!isset($tutorTotals[$t])) {
                    $tutorTotals[$t] = ['sesi' => 0, 'siswa' => 0, 'revenue' => 0, 'unique_siswa' => []];
                }
                $tutorTotals[$t]['sesi'] += ($jumlahPertemuan ?: 1) / $tutorCount;
                // count unique siswa per tutor
                if (!in_array($siswa->id, $tutorTotals[$t]['unique_siswa'])) {
                    $tutorTotals[$t]['unique_siswa'][] = $siswa->id;
                    $tutorTotals[$t]['siswa'] += 1;
                }
                // split revenue evenly among tutors
                $tutorTotals[$t]['revenue'] += $totalHarga / $tutorCount;
            }
        }

        if ($filter === 'yearly') {
            ksort($yearlyData);
            $chartLabels = array_map('strval', array_keys($yearlyData));
            $chartData = array_values($yearlyData);
        } else {
            $chartLabels = $monthlyLabels;
            $chartData = $monthlyTotals;
        }

        // prepare package list sorted by revenue desc
        uasort($packageTotals, function ($a, $b) {
            return $b['revenue'] <=> $a['revenue'];
        });

        // prepare tutor list sorted by revenue desc
        foreach ($tutorTotals as $k => $v) {
            // round numeric fields
            $tutorTotals[$k]['sesi'] = (int) round($v['sesi']);
            $tutorTotals[$k]['revenue'] = (int) round($v['revenue']);
        }
        uasort($tutorTotals, function ($a, $b) {
            return $b['revenue'] <=> $a['revenue'];
        });

        $avgPerTransaction = $paymentCount ? (int) round($totalRevenue / $paymentCount) : 0;
        $monthlyTarget = 15000000; // default target — can be made configurable
        $targetPercent = $monthlyTarget > 0 ? min(100, (int) round($totalRevenue / $monthlyTarget * 100)) : 0;

        return view('admin.laporanPendapatan', array_merge(
            compact('filter', 'year'),
            [
                'availableYears' => $availableYears,
                'chartLabels' => $chartLabels,
                'chartData' => $chartData,
                'totalRevenue' => $totalRevenue,
                'paymentCount' => $paymentCount,
                'avgPerTransaction' => $avgPerTransaction,
                'monthlyTarget' => $monthlyTarget,
                'targetPercent' => $targetPercent,
                'packageTotals' => $packageTotals,
                'tutorTotals' => $tutorTotals,
            ]
        ));
    }

    /**
     * Helper untuk mengekstrak harga numerik dari deskripsi string.
     */
    private function extractPrice($str, $default)
    {
        if (empty($str)) {
            return $default;
        }
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
     * Export laporan pendapatan ke CSV (Excel compatible).
     */
    public function exportRevenueExcel(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $filter = $request->input('filter', 'monthly');
        $year = (int) $request->input('year', now()->year);
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        $paymentsQuery = Siswa::with('paket')
            ->whereNotNull('bukti_transfer')
            ->where('bukti_transfer', '!=', '')
            ->where('status', 'active');

        if ($filter === 'monthly') {
            $paymentsQuery->whereYear('updated_at', $year);
        }
        if ($start && $end) {
            $paymentsQuery->whereBetween('updated_at', [$start . ' 00:00:00', $end . ' 23:59:59']);
        }

        $payments = $paymentsQuery->get();

        $filename = 'laporan_pendapatan_' . now()->format('Ymd_His') . '.csv';

        $columns = ['ID', 'Nama', 'Email', 'Paket', 'Jumlah Pertemuan', 'Total Bayar', 'Tanggal'];

        $callback = function() use ($payments, $columns) {
            $file = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            foreach ($payments as $siswa) {
                $paket = $siswa->paket;
                $biodata = $siswa->biodata ?? [];
                $jumlahPertemuan = $biodata['jumlah_pertemuan'] ?? null;
                if (!$jumlahPertemuan && $siswa->tipe_paket) {
                    if (preg_match('/Sesi:\s*(\d+)x/i', $siswa->tipe_paket, $m)) {
                        $jumlahPertemuan = (int) $m[1];
                    }
                }
                $detailString = '';
                if ($siswa->tipe_paket && $paket) {
                    if (str_contains($siswa->tipe_paket, $paket->detail_1)) $detailString = $paket->detail_1;
                    elseif (str_contains($siswa->tipe_paket, $paket->detail_2)) $detailString = $paket->detail_2;
                    elseif (str_contains($siswa->tipe_paket, $paket->detail_3)) $detailString = $paket->detail_3;
                    elseif (str_contains($siswa->tipe_paket, $paket->detail_4)) $detailString = $paket->detail_4;
                }
                $hargaPerSesi = $this->extractPrice($detailString, $paket ? $paket->harga_max : 0);
                $totalHarga = $hargaPerSesi * ($jumlahPertemuan ?: 1);

                $row = [
                    $siswa->id,
                    $siswa->name,
                    $siswa->email,
                    $paket ? $paket->nama_paket : '-',
                    $jumlahPertemuan ?: 1,
                    $totalHarga,
                    $siswa->updated_at ? $siswa->updated_at->format('Y-m-d H:i:s') : ($siswa->created_at ? $siswa->created_at->format('Y-m-d H:i:s') : ''),
                ];
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Export laporan pendapatan ke PDF (menggunakan barryvdh/laravel-dompdf jika tersedia),
     * fallback: render HTML printable view for manual PDF export.
     */
    public function exportRevenuePdf(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $filter = $request->input('filter', 'monthly');
        $year = (int) $request->input('year', now()->year);
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        // reuse logic to gather aggregated data
        $data = $this->getRevenueExportData($filter, $year, $start, $end);

        // If dompdf available, generate PDF
        if (class_exists('\Barryvdh\DomPDF\Facade\Pdf') || class_exists('\Barryvdh\DomPDF\PDF')) {
            try {
                $pdf = app()->make('\Barryvdh\DomPDF\Facade\Pdf')::loadView('admin.laporanPendapatanPdf', $data);
                return $pdf->stream('laporan_pendapatan_' . now()->format('Ymd_His') . '.pdf');
            } catch (\Throwable $e) {
                // fallback to HTML view
            }
        }

        // fallback: return printable HTML and let user save as PDF
        return view('admin.laporanPendapatanPdf', $data);
    }

    /**
     * Helper to collect data used by export PDF and other exporters.
     */
    private function getRevenueExportData(string $filter, int $year, $start = null, $end = null): array
    {
        $paymentsQuery = Siswa::with('paket')
            ->whereNotNull('bukti_transfer')
            ->where('bukti_transfer', '!=', '')
            ->where('status', 'active');

        if ($filter === 'monthly') {
            $paymentsQuery->whereYear('updated_at', $year);
        }
        if ($start && $end) {
            $paymentsQuery->whereBetween('updated_at', [$start . ' 00:00:00', $end . ' 23:59:59']);
        }

        $payments = $paymentsQuery->get();

        $rows = [];
        foreach ($payments as $siswa) {
            $paket = $siswa->paket;
            $biodata = $siswa->biodata ?? [];
            $jumlahPertemuan = $biodata['jumlah_pertemuan'] ?? null;
            if (!$jumlahPertemuan && $siswa->tipe_paket) {
                if (preg_match('/Sesi:\s*(\d+)x/i', $siswa->tipe_paket, $m)) {
                    $jumlahPertemuan = (int) $m[1];
                }
            }
            $detailString = '';
            if ($siswa->tipe_paket && $paket) {
                if (str_contains($siswa->tipe_paket, $paket->detail_1)) $detailString = $paket->detail_1;
                elseif (str_contains($siswa->tipe_paket, $paket->detail_2)) $detailString = $paket->detail_2;
                elseif (str_contains($siswa->tipe_paket, $paket->detail_3)) $detailString = $paket->detail_3;
                elseif (str_contains($siswa->tipe_paket, $paket->detail_4)) $detailString = $paket->detail_4;
            }
            $hargaPerSesi = $this->extractPrice($detailString, $paket ? $paket->harga_max : 0);
            $totalHarga = $hargaPerSesi * ($jumlahPertemuan ?: 1);

            $rows[] = [
                'id' => $siswa->id,
                'name' => $siswa->name,
                'email' => $siswa->email,
                'paket' => $paket ? $paket->nama_paket : '-',
                'jumlah_pertemuan' => $jumlahPertemuan ?: 1,
                'total' => $totalHarga,
                'tanggal' => $siswa->updated_at ? $siswa->updated_at->format('Y-m-d H:i:s') : ($siswa->created_at ? $siswa->created_at->format('Y-m-d H:i:s') : ''),
            ];
        }

        return ['rows' => $rows, 'filter' => $filter, 'year' => $year, 'start' => $start, 'end' => $end];
    }
}
