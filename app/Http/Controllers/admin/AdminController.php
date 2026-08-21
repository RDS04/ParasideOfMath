<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Guru;
use App\Models\Mapel;
use App\Models\PaketBelajar;
use App\Models\Rekening;
use App\Models\Siswa;
use App\Models\KategoriSoal;
use App\Models\BankSoal;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Validation\Rule;
use App\Models\RiwayatPembayaran;
use App\Models\YoutubeLink;

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
        $biodata = $siswa->biodata ?? [];
        $pendingMapels = $biodata['pending_mapel_jadwal'] ?? [];
        $pendingSesi = $biodata['pending_sesi_per_mapel'] ?? [];

        if (!empty($pendingMapels)) {
            $activeMapels = $biodata['mapel_jadwal'] ?? [];
            $activeSesi = $biodata['sesi_per_mapel'] ?? [];

            $activeHari = $biodata['hari_per_mapel'] ?? [];
            $activeTanggal = $biodata['tanggal_mulai_per_mapel'] ?? [];

            foreach ($pendingMapels as $idx => $mapelName) {
                if (!in_array($mapelName, $activeMapels)) {
                    $activeMapels[] = $mapelName;
                    $activeSesi[] = isset($pendingSesi[$idx]) ? (int) $pendingSesi[$idx] : 8;

                    $activeHari[] = $pendingHari[$idx] ?? [];
                    $activeTanggal[] = $pendingTanggal[$idx] ?? null;
                }
            }

            $biodata['mapel_jadwal'] = array_values($activeMapels);
            $biodata['sesi_per_mapel'] = array_values($activeSesi);
             $biodata['hari_per_mapel'] = array_values($activeHari);
            $biodata['tanggal_mulai_per_mapel'] = array_values($activeTanggal);
            $biodata['jumlah_pertemuan'] = array_sum($activeSesi);
            unset($biodata['pending_mapel_jadwal'], $biodata['pending_sesi_per_mapel'],$biodata['pending_hari_per_mapel'], $biodata['pending_tanggal_mulai_per_mapel'], $biodata['pending_jumlah_pertemuan']);
        }

        $siswa->update([
            'status' => 'active',
            'biodata' => $biodata,
        ]);

        RiwayatPembayaran::where('siswa_id', $siswa->id)
            ->where('status', 'under_review')
            ->update(['status' => 'approved', 'approved_at' => now()]);

        return back()->with('success', 'Akun pendaftaran ' . $siswa->name . ' berhasil disetujui dan diaktifkan!');
    }

    /**
     * Tampilkan daftar request tambah mapel untuk siswa aktif.
     */
    public function requestTambahMapel()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $students = Siswa::where('status', 'active')
            ->whereNotNull('biodata')
            ->get()
            ->filter(function ($student) {
                $biodata = $student->biodata ?? [];
                return !empty($biodata['pending_mapel_jadwal']);
            });

        return view('admin.requestTambahMapel', compact('students'));
    }

    /**
     * Approve request tambah mapel tanpa mereset status akun.
     */
    public function approveRequestTambahMapel($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $siswa = Siswa::findOrFail($id);
        $biodata = $siswa->biodata ?? [];
        $pendingMapels = $biodata['pending_mapel_jadwal'] ?? [];
        $pendingSesi = $biodata['pending_sesi_per_mapel'] ?? [];
        $pendingHari = $biodata['pending_hari_per_mapel'] ?? [];
        $pendingTanggal = $biodata['pending_tanggal_mulai_per_mapel'] ?? [];

        if (!empty($pendingMapels)) {
            $activeMapels = $biodata['mapel_jadwal'] ?? [];
            $activeSesi = $biodata['sesi_per_mapel'] ?? [];
            $activeHari = $biodata['hari_per_mapel'] ?? [];
            $activeTanggal = $biodata['tanggal_mulai_per_mapel'] ?? [];

            foreach ($pendingMapels as $idx => $mapelName) {
                if (!in_array($mapelName, $activeMapels)) {
                    $activeMapels[] = $mapelName;
                    $activeSesi[] = isset($pendingSesi[$idx]) ? (int) $pendingSesi[$idx] : 8;
                    $activeHari[] = $pendingHari[$idx] ?? [];
                    $activeTanggal[] = $pendingTanggal[$idx] ?? null;
                }
            }

            $biodata['mapel_jadwal'] = array_values($activeMapels);
            $biodata['sesi_per_mapel'] = array_values($activeSesi);
            $biodata['hari_per_mapel'] = array_values($activeHari);
            $biodata['tanggal_mulai_per_mapel'] = array_values($activeTanggal);
            $biodata['jumlah_pertemuan'] = array_sum($activeSesi);
            unset($biodata['pending_mapel_jadwal'], $biodata['pending_sesi_per_mapel'], $biodata['pending_hari_per_mapel'], $biodata['pending_tanggal_mulai_per_mapel'], $biodata['pending_jumlah_pertemuan']);

            $siswa->update(['biodata' => $biodata]);
        }

        RiwayatPembayaran::where('siswa_id', $siswa->id)
            ->where('status', 'under_review')
            ->update(['status' => 'approved', 'approved_at' => now()]);

        return back()->with('success', 'Request tambah mapel siswa ' . $siswa->name . ' berhasil disetujui.');
    }

    /**
     * Reject request tambah mapel untuk siswa aktif.
     */
    public function rejectRequestTambahMapel($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $siswa = Siswa::findOrFail($id);
        $biodata = $siswa->biodata ?? [];

        unset($biodata['pending_mapel_jadwal'], $biodata['pending_sesi_per_mapel'],$biodata['pending_hari_per_mapel'], $biodata['pending_tanggal_mulai_per_mapel'], $biodata['pending_jumlah_pertemuan']);
        $siswa->update(['biodata' => $biodata]);

         RiwayatPembayaran::where('siswa_id', $siswa->id)
        ->where('status', 'under_review')
        ->update(['status' => 'rejected']);

        return back()->with('success', 'Request tambah mapel siswa ' . $siswa->name . ' berhasil ditolak.');
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

        RiwayatPembayaran::where('siswa_id', $siswa->id)
        ->where('status', 'under_review')
        ->update(['status' => 'rejected']);


        // Reset all registration data EXCEPT email & password
        $siswa->update([
            'paket_id' => null,
            'tipe_paket' => null,
            'whatsapp' => null,
            'sekolah' => null,
            'bukti_transfer' => null,
            'biodata' => null,
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Pendaftaran siswa ' . $siswa->name . ' berhasil ditolak! Semua data registrasi & biodata telah dihapus (kecuali akun email & password) sehingga siswa dapat mendaftar ulang dari awal.');
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
     * Atur / assign guru pendamping untuk siswa (dapat diatur per mata pelajaran).
     */
    public function assignTutor(Request $request, $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $siswa = Siswa::findOrFail($id);
        $biodata = $siswa->biodata ?? [];

        $tutorFormattedParts = [];
        $tutorNamesFlat = [];

        // Case 1: Assign per-mapel (e.g. ['Fisika' => 'Guru A', 'Kimia' => 'Guru B'])
        if ($request->has('tutor_per_mapel') && is_array($request->tutor_per_mapel)) {
            $tutorPerMapel = [];
            foreach ($request->tutor_per_mapel as $mapel => $guru) {
                $guruClean = trim($guru);
                if (!empty($guruClean)) {
                    $tutorPerMapel[$mapel] = $guruClean;
                    $tutorFormattedParts[] = $mapel . ': ' . $guruClean;
                    $tutorNamesFlat[] = $guruClean;
                }
            }
            $biodata['tutor_per_mapel'] = $tutorPerMapel;
        }

        // Case 2: Assign general tutors[] checkboxes (legacy/fallback)
        if ($request->has('tutors') && is_array($request->tutors)) {
            $tutorsSelected = array_filter(array_map('trim', $request->tutors));
            $biodata['tutor_names'] = $tutorsSelected;
            if (empty($tutorNamesFlat)) {
                $tutorNamesFlat = $tutorsSelected;
            }
        }

        // Build tutors string for tipe_paket descriptor
        $tutorsStr = !empty($tutorFormattedParts) ? implode(', ', $tutorFormattedParts) : implode(', ', array_unique($tutorNamesFlat));
        if (empty($tutorsStr)) {
            $tutorsStr = 'Belum ditentukan';
        }

        // Update tipe_paket descriptor (e.g. "Guru: Fisika: Guru A, Kimia: Guru B")
        if ($siswa->tipe_paket) {
            if (preg_match('/Guru:\s*([^|)]+)/i', $siswa->tipe_paket)) {
                $newTipePaket = preg_replace('/Guru:\s*([^|)]+)/i', 'Guru: ' . $tutorsStr, $siswa->tipe_paket);
            } else {
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

        return back()->with('success', 'Guru pendamping untuk ' . $siswa->name . ' berhasil diatur: ' . $tutorsStr);
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
            'hari_per_mapel' => ['nullable', 'array'],
            'hari_per_mapel.*' => ['array'],
            'hari_per_mapel.*.*' => ['string', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],
        ]);

        $siswa = Siswa::findOrFail($id);
        $biodata = $siswa->biodata ?? [];
        $biodata['hari_per_mapel'] = $request->input('hari_per_mapel', []);

        $siswa->update([
            'biodata' => $biodata,
        ]);

        return back()->with('success', 'Jadwal hari bimbingan per mata pelajaran untuk ' . $siswa->name . ' berhasil diperbarui!');
    }

    /**
     * Update jam mulai dan jam berakhir bimbel siswa per mata pelajaran.
     */
    public function updateJamBimbel(Request $request, $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $request->validate([
            'jam_per_mapel'             => ['nullable', 'array'],
            'jam_per_mapel.*.jam_mulai' => ['nullable', 'regex:/^\d{2}:\d{2}$/'],
        ]);

        $siswa = Siswa::findOrFail($id);
        $biodata = $siswa->biodata ?? [];

        $durasiMenit = 90; // Semua mata pelajaran durasinya 90 menit
        $jamPerMapelInput = $request->input('jam_per_mapel', []);
        $jamPerMapelFinal = [];

        foreach ($jamPerMapelInput as $idx => $item) {
            $jamMulai = $item['jam_mulai'] ?? null;

            if ($jamMulai) {
                $jamSelesai = date('H:i', strtotime($jamMulai . " + {$durasiMenit} minutes"));
            } else {
                $jamSelesai = null;
            }

            $jamPerMapelFinal[$idx] = [
                'jam_mulai'   => $jamMulai,
                'jam_selesai' => $jamSelesai,
            ];
        }

        $biodata['jam_per_mapel'] = $jamPerMapelFinal;

        $siswa->update([
            'biodata' => $biodata,
        ]);

        return back()->with('success', 'Jam bimbingan per mata pelajaran untuk ' . $siswa->name . ' berhasil diperbarui! Jam berakhir otomatis dihitung +90 menit dari jam mulai.');
    }

    /**
     * Tampilkan Halaman Kalender Master Admin untuk seluruh jadwal siswa.
     */
    public function showKalender()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $allStudents = Siswa::whereIn('status', ['active', 'pending', 'under_review'])->get();
        $gurusList   = Guru::with('user')->get();

        $allSessions = [];
        $dayMap = [
            'minggu' => 0, 'senin' => 1, 'selasa' => 2, 'rabu' => 3,
            'kamis' => 4, 'jumat' => 5, 'sabtu' => 6,
            'sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3,
            'thursday' => 4, 'friday' => 5, 'saturday' => 6
        ];

        foreach ($allStudents as $siswa) {
            $biodata = $siswa->biodata ?? [];

            $mapelJadwal     = $biodata['mapel_jadwal'] ?? [];
            $sesiPerMapel    = $biodata['sesi_per_mapel'] ?? [];
            $hariPerMapel    = $biodata['hari_per_mapel'] ?? [];
            $tanggalPerMapel = $biodata['tanggal_mulai_per_mapel'] ?? [];
            $tutorPerMapel   = $biodata['tutor_per_mapel'] ?? [];

            if (empty($mapelJadwal) && $siswa->tipe_paket) {
                if (preg_match('/Mapel:\s*([^)|]+)/i', $siswa->tipe_paket, $matches)) {
                    $mapelJadwal = array_map('trim', explode(',', $matches[1]));
                }
            }

            if (empty($mapelJadwal)) {
                $mapelJadwal = ['Bimbingan Belajar'];
            }

            $paket = $siswa->paket;
            $jamMulai = $paket ? ($paket->jam_mulai ?? '15:30') : '15:30';
            $durationMinutes = 90;
            if ($paket && preg_match('/(\d+)\s*menit/i', $paket->detail_5 ?? '', $dMatches)) {
                $durationMinutes = (int) $dMatches[1];
            }
            $jamSelesai = date('H:i', strtotime($jamMulai . " + {$durationMinutes} minutes"));

            // Parsed current tutors
            $currentGurus = [];
            if ($siswa->tipe_paket && preg_match('/Guru:\s*([^|)]+)/i', $siswa->tipe_paket, $m)) {
                $currentGurus = array_map('trim', explode(',', $m[1]));
            }

            foreach ($mapelJadwal as $mIdx => $mapelName) {
                $hariList = [];
                $tglMulai = null;
                $limitSesi = 0;

                if (isset($hariPerMapel[$mIdx])) {
                    $rawH = $hariPerMapel[$mIdx];
                    $hariList = is_array($rawH) ? array_values(array_filter($rawH)) : [];
                    $tglMulai = $tanggalPerMapel[$mIdx] ?? ($biodata['tanggal_mulai'] ?? null);
                    $limitSesi = (int)($sesiPerMapel[$mIdx] ?? ($biodata['jumlah_pertemuan'] ?? 0));
                } else {
                    $hariList = $biodata['hari_pertemuan'] ?? [];
                    $tglMulai = $biodata['tanggal_mulai'] ?? null;
                    $limitSesi = (int)($biodata['jumlah_pertemuan'] ?? 0);
                }

                // Fallbacks
                if (empty($hariList) && $siswa->tipe_paket && preg_match('/Hari:\s*([^)|]+)/i', $siswa->tipe_paket, $m)) {
                    $hariList = array_map('trim', explode(',', $m[1]));
                }
                if ($limitSesi === 0 && $siswa->tipe_paket) {
                    if (preg_match('/Total Sesi:\s*(\d+)x/i', $siswa->tipe_paket, $m)) {
                        $limitSesi = (int)$m[1];
                    } elseif (preg_match('/Sesi:\s*(\d+)x/i', $siswa->tipe_paket, $m)) {
                        $limitSesi = (int)$m[1];
                    }
                }
                if (!$tglMulai && $siswa->tipe_paket && preg_match('/Mulai:\s*([\d\-]+)/i', $siswa->tipe_paket, $m)) {
                    $d = trim($m[1]);
                    if (preg_match('/(\d{2})-(\d{2})-(\d{4})/', $d, $dM)) {
                        $tglMulai = $dM[3] . '-' . $dM[2] . '-' . $dM[1];
                    } else {
                        $tglMulai = $d;
                    }
                }
                if (!$tglMulai && $siswa->created_at) {
                    $tglMulai = $siswa->created_at->format('Y-m-d');
                }

                // Tutor assigned for this mapel
                $tutorName = $tutorPerMapel[$mapelName] ?? null;
                if (!$tutorName && !empty($currentGurus)) {
                    foreach ($currentGurus as $cg) {
                        if (str_contains(strtolower($cg), strtolower($mapelName))) {
                            $tutorName = preg_replace('/^(math|english|ipa|ips|fisika|kimia|biologi|matematika):\s*/i', '', $cg);
                        }
                    }
                    if (!$tutorName) {
                        $tutorName = implode(', ', $currentGurus);
                    }
                }
                if (!$tutorName) {
                    $tutorName = 'Belum ditentukan';
                }

                $scheduledDayNums = [];
                foreach ($hariList as $h) {
                    $normH = strtolower(trim($h));
                    if (isset($dayMap[$normH])) {
                        $scheduledDayNums[] = $dayMap[$normH];
                    }
                }

                if ($limitSesi > 0 && !empty($scheduledDayNums) && $tglMulai) {
                    try {
                        $startDate = \Carbon\Carbon::parse($tglMulai);
                        $tempDate = $startDate->copy();
                        $studentSessionCount = 0;

                        for ($d = 0; $d < 730; $d++) {
                            if ($studentSessionCount >= $limitSesi) {
                                break;
                            }
                            $dayOfWeek = $tempDate->dayOfWeek;
                            if (in_array($dayOfWeek, $scheduledDayNums)) {
                                $studentSessionCount++;
                                $dateStr = $tempDate->format('Y-m-d');
                                $allSessions[] = [
                                    'dateStr' => $dateStr,
                                    'student_id' => $siswa->id,
                                    'student_name' => $siswa->name,
                                    'sekolah' => $siswa->sekolah ?? 'Sekolah',
                                    'whatsapp' => $siswa->whatsapp ?? '',
                                    'subject' => $mapelName,
                                    'tutor' => $tutorName,
                                    'time' => $jamMulai . ' - ' . $jamSelesai,
                                    'session_index' => $studentSessionCount,
                                    'total_sessions' => $limitSesi,
                                ];
                            }
                            $tempDate->addDay();
                        }
                    } catch (\Exception $e) {
                        // Ignore errors
                    }
                }
            }
        }

        return view('admin.kalender', compact('allSessions', 'gurusList', 'allStudents'));
    }

    /**
     * Tampilkan Halaman Daftar Siswa Keseluruhan.
     */
    public function daftarSiswa(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $search = $request->input('search');

        $students = Siswa::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%")
                    ->orWhere('sekolah', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.daftarSiswa', compact('students', 'search'));
    }

    public function toggleStatusSiswa($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $siswa = Siswa::findOrFail($id);

        if ($siswa->status === 'active') {
            $siswa->update(['status' => 'nonaktif']);
            $message = 'Akun siswa ' . $siswa->name . ' berhasil DINONAKTIFKAN.';
        } elseif ($siswa->status === 'nonaktif') {
            $siswa->update(['status' => 'active']);
            $message = 'Akun siswa ' . $siswa->name . ' berhasil DIAKTIFKAN kembali.';
        } else {
            return back()->with('error', 'Status siswa ini (' . $siswa->status . ') tidak dapat diubah dari sini. Gunakan halaman Persetujuan untuk status tersebut.');
        }

        return back()->with('success', $message);
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
        $guruRegisterEnabled = Setting::get('guru_register_enabled', '1') !== '0';

        return view('admin.daftarGuru', compact('gurus', 'guruRegisterEnabled'));
    }

    /**
     * Toggle status aktif/nonaktif tampilan pendaftaran guru di login.
     */
    public function toggleGuruRegisterStatus(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $current = Setting::get('guru_register_enabled', '1');
        $newStatus = ($current === '0') ? '1' : '0';
        Setting::set('guru_register_enabled', $newStatus);

        $msg = ($newStatus === '1')
            ? 'Tampilan link pendaftaran guru ("Ingin bergabung sebagai pengajar? Daftar sebagai Guru") berhasil DIAKTIFKAN.'
            : 'Tampilan link pendaftaran guru ("Ingin bergabung sebagai pengajar? Daftar sebagai Guru") berhasil DINONAKTIFKAN.';

        return redirect()->back()->with('success', $msg);
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

        $riwayatList = \App\Models\RiwayatPembayaran::with(['siswa', 'paket'])
            ->orderBy('created_at', 'desc')
            ->get();

        $payments = $riwayatList->map(function ($riwayat) {
            $siswa = $riwayat->siswa;
            $paket = $riwayat->paket;

            $jumlahSesi  = $riwayat->jumlah_sesi ?: 1;
            $hargaPerSesi = $jumlahSesi > 0 ? intdiv($riwayat->total_harga, $jumlahSesi) : $riwayat->total_harga;

            // Mapping status histori -> label status yang sudah dikenal blade (active/under_review/rejected)
            $displayStatus = match ($riwayat->status) {
                'approved'     => 'active',
                'rejected'     => 'rejected',
                default        => 'under_review',
            };

            return (object) [
                'id'               => $riwayat->id, // ← sekarang ID transaksi asli, bukan ID siswa
                'name'             => $siswa->name ?? '(Siswa terhapus)',
                'email'            => $siswa->email ?? '-',
                'whatsapp'         => $siswa->whatsapp ?? null,
                'nama_paket'       => $paket ? $paket->nama_paket : 'Pendaftaran Bimbel',
                'tipe_paket'       => $riwayat->tipe_paket_snapshot,
                'total_bayar'      => $riwayat->total_harga,
                'harga_sesi'       => $hargaPerSesi,
                'jumlah_pertemuan' => $jumlahSesi,
                'bukti_transfer'   => $riwayat->bukti_transfer,
                'status'           => $displayStatus,
                'tanggal'          => $riwayat->created_at->format('d M Y H:i'),
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

    /**
     * Tampilkan Halaman Kelola Foto Landing Page.
     */
    public function showFotoMenu()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $heroDir = public_path('uploads/landing/hero');
        if (!file_exists($heroDir)) {
            mkdir($heroDir, 0777, true);
        }

        // Migrate legacy single hero_image if exists into the folder
        $possibleExts = ['jpg', 'jpeg', 'png', 'webp'];
        foreach ($possibleExts as $ext) {
            $legacyPath = public_path("uploads/landing/hero_image.{$ext}");
            if (file_exists($legacyPath)) {
                $newName = 'hero_' . time() . '_' . rand(100, 999) . '.' . $ext;
                @rename($legacyPath, $heroDir . '/' . $newName);
            }
        }

        // Scan hero images in heroDir
        $files = glob($heroDir . '/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE) ?: [];
        
        // Sort files newest first
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        $heroImages = [];
        foreach ($files as $file) {
            $basename = basename($file);
            $heroImages[] = [
                'filename' => $basename,
                'url' => asset("uploads/landing/hero/{$basename}") . '?v=' . filemtime($file),
                'mtime' => filemtime($file),
                'size' => round(filesize($file) / 1024, 1) . ' KB'
            ];
        }

        return view('admin.fotoMenu', compact('heroImages'));
    }

    /**
     * Update / Tambah Foto Hero (Mendukung Multi-Upload).
     */
    public function updateHeroFoto(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $request->validate([
            'hero_images' => ['required'],
            'hero_images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ], [
            'hero_images.required' => 'File foto wajib dipilih.',
            'hero_images.*.image' => 'File harus berupa foto/gambar.',
            'hero_images.*.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'hero_images.*.max' => 'Ukuran gambar maksimal adalah 5MB per file.',
        ]);

        $heroDir = public_path('uploads/landing/hero');
        if (!file_exists($heroDir)) {
            mkdir($heroDir, 0777, true);
        }

        $files = $request->file('hero_images');
        if (!is_array($files)) {
            $files = [$files];
        }

        $count = 0;
        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $ext = strtolower($file->getClientOriginalExtension());
                $fileName = 'hero_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $file->move($heroDir, $fileName);
                $count++;
            }
        }

        return back()->with('success', "{$count} foto Hero baru berhasil ditambahkan! Foto akan berganti-ganti secara otomatis di Landing Page.");
    }

    /**
     * Hapus Spesifik Foto Hero dari List.
     */
    public function deleteHeroFotoSingle(Request $request, $filename)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        // Prevent directory traversal
        $filename = basename($filename);
        $filePath = public_path("uploads/landing/hero/{$filename}");

        if (file_exists($filePath)) {
            @unlink($filePath);
            return back()->with('success', 'Foto Hero berhasil dihapus dari daftar slider.');
        }

        return back()->with('error', 'Foto tidak ditemukan.');
    }

    /**
     * Tampilkan Halaman Kelola Foto Fasilitas & Galeri Landing Page.
     */
    public function showGaleri()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $possibleExts = ['jpg', 'jpeg', 'png', 'webp'];

        $galeri = [
            'kelas' => null,
            'toilet' => null,
            'mushala' => null,
            'gedung' => null,
        ];

        foreach (['kelas', 'toilet', 'mushala', 'gedung'] as $key) {
            foreach ($possibleExts as $ext) {
                $path = public_path("uploads/landing/fasilitas_{$key}.{$ext}");
                if (file_exists($path)) {
                    $galeri[$key] = asset("uploads/landing/fasilitas_{$key}.{$ext}") . '?v=' . filemtime($path);
                    break;
                }
            }
        }

        $galleryDir = public_path('uploads/landing/galeri');
        if (!file_exists($galleryDir)) {
            mkdir($galleryDir, 0777, true);
        }

        $galleryExtras = [];
        $extraFiles = glob($galleryDir . '/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE) ?: [];
        usort($extraFiles, function ($a, $b) {
            return filemtime($b) <=> filemtime($a);
        });

        foreach ($extraFiles as $file) {
            $basename = basename($file);
            $galleryExtras[] = [
                'filename' => $basename,
                'url' => asset("uploads/landing/galeri/{$basename}") . '?v=' . filemtime($file),
                'mtime' => filemtime($file),
                'size' => round(filesize($file) / 1024, 1) . ' KB',
            ];
        }

        return view('admin.galeri', compact('galeri', 'galleryExtras'));
    }

    /**
     * Update Foto Fasilitas Landing Page.
     */
    public function updateGaleriFoto(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $request->validate([
            'key' => ['required', 'in:kelas,toilet,mushala,gedung'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ], [
            'image.required' => 'File foto wajib dipilih.',
            'image.image' => 'File harus berupa gambar/foto.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'image.max' => 'Ukuran gambar maksimal adalah 5MB.',
        ]);

        $key = $request->key;
        $uploadDir = public_path('uploads/landing');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Clean old file
        $possibleExts = ['jpg', 'jpeg', 'png', 'webp'];
        foreach ($possibleExts as $ext) {
            $existing = public_path("uploads/landing/fasilitas_{$key}.{$ext}");
            if (file_exists($existing)) {
                @unlink($existing);
            }
        }

        $file = $request->file('image');
        $ext = strtolower($file->getClientOriginalExtension());
        $fileName = "fasilitas_{$key}.{$ext}";

        $file->move($uploadDir, $fileName);

        $labelMap = [
            'kelas' => 'Ruang Kelas', 
            'toilet' => 'Toilet', 
            'mushala' => 'Mushala', 
            'gedung' => 'Gedung / Rumah Bimbel'
        ];
        $label = $labelMap[$key] ?? $key;

        return back()->with('success', 'Foto Fasilitas (' . $label . ') berhasil diperbarui! Gambar kini tampil di Halaman Depan.');
    }

    /**
     * Tambah foto galeri ekstra untuk tombol "Lihat Semua".
     */
    public function storeGaleriTambahan(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $request->validate([
            'gallery_images' => ['required'],
            'gallery_images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ], [
            'gallery_images.required' => 'Minimal satu foto harus dipilih.',
            'gallery_images.*.image' => 'File harus berupa gambar/foto.',
            'gallery_images.*.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'gallery_images.*.max' => 'Ukuran gambar maksimal adalah 5MB per file.',
        ]);

        $galleryDir = public_path('uploads/landing/galeri');
        if (!file_exists($galleryDir)) {
            mkdir($galleryDir, 0777, true);
        }

        $files = $request->file('gallery_images');
        if (!is_array($files)) {
            $files = [$files];
        }

        $count = 0;
        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $ext = strtolower($file->getClientOriginalExtension());
                $fileName = 'galeri_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $file->move($galleryDir, $fileName);
                $count++;
            }
        }

        return back()->with('success', "{$count} foto galeri tambahan berhasil diupload dan siap muncul di tombol Lihat Semua.");
    }

    /**
     * Hapus / Reset Foto Fasilitas Landing Page.
     */
    public function deleteGaleriFoto(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $key = $request->input('key');
        $deleted = false;

        if (in_array($key, ['kelas', 'toilet', 'mushala', 'gedung'])) {
            $possibleExts = ['jpg', 'jpeg', 'png', 'webp'];
            foreach ($possibleExts as $ext) {
                $existing = public_path("uploads/landing/fasilitas_{$key}.{$ext}");
                if (file_exists($existing)) {
                    @unlink($existing);
                    $deleted = true;
                }
            }

            if ($deleted) {
                return back()->with('success', 'Foto fasilitas berhasil direset ke tampilan default.');
            }

            return back()->with('error', 'Foto fasilitas default tidak ditemukan.');
        }

        if ($key === 'galeri_extra') {
            $filename = basename((string) $request->input('filename'));
            $filePath = public_path("uploads/landing/galeri/{$filename}");

            if ($filename && file_exists($filePath)) {
                @unlink($filePath);
                return back()->with('success', 'Foto galeri tambahan berhasil dihapus.');
            }

            return back()->with('error', 'Foto galeri tambahan tidak ditemukan.');
        }

        return back()->with('error', 'Permintaan hapus tidak valid.');
    }

    /**
     * Bank Soal — Alur 5 Langkah: Jenjang → Kelas → Semester/TKA → Mapel → Soal.
     */
    public function bankSoal(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $jenjang = strtoupper($request->input('jenjang', ''));
        $kelas   = $request->input('kelas', '');
        $sub     = $request->input('sub_kategori', '');
        $mapel   = $request->input('mapel', '');

        // Step 2: Kelas tersedia berdasarkan jenjang
        $availableClasses = [];
        if ($jenjang === 'SD') {
            $availableClasses = range(1, 6);
        } elseif (in_array($jenjang, ['SMP', 'SMA'])) {
            $availableClasses = range(1, 3);
        }

        // Step 3: Semester / TKA tersedia berdasarkan jenjang + kelas
        $availableSubs = ($jenjang && $kelas)
            ? KategoriSoal::availableSubKategori($jenjang, $kelas)
            : [];

        if ($sub && !in_array($sub, $availableSubs)) {
            $sub = '';
        }

        // Step 4: Daftar Mata Pelajaran tersedia setelah Semester / TKA dipilih
        $mapelList = collect();
        if ($sub) {
            $mapelQuery = Mapel::where('nama_mapel', 'not like', '%Wajib + Lanjut%');
            if ($sub === 'TKA') {
                $mapelQuery->where('nama_mapel', 'like', '%TKA%');
            } else {
                $mapelQuery->where('nama_mapel', 'not like', '%TKA%');
            }
            $mapelList = $mapelQuery->orderBy('nama_mapel')
                ->pluck('nama_mapel')
                ->unique()
                ->values();
        }

        $selectedCategory = null;
        $kategoriList = collect();
        $kategoriId = $request->input('kategori_id', '');

        // Step 5: Jika kombinasi Jenjang + Kelas + Semester/TKA + Mapel lengkap,
        //         ambil daftar KategoriSoal (judul/deskripsi) yang sudah diupload guru.
        if ($jenjang && $kelas && $sub && $mapel) {
            $kategoriList = KategoriSoal::where('jenjang', $jenjang)
                ->where('kelas', $kelas)
                ->where('sub_kategori', $sub)
                ->where('nama_kategori', $mapel)
                ->withCount('bankSoals')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Step 6: Jika admin memilih salah satu kategori, load soal-soalnya
        if ($kategoriId) {
            $selectedCategory = KategoriSoal::find($kategoriId);
            if ($selectedCategory) {
                $selectedCategory->load('bankSoals');
            }
        }

        return view('admin.listBanksoal', compact(
            'jenjang', 'kelas', 'sub', 'mapel',
            'availableClasses', 'availableSubs', 'mapelList',
            'kategoriList', 'selectedCategory', 'kategoriId'
        ));
    }

    /**
     * Halaman Terpisah Kelola Soal & Modul untuk Paket Soal tertentu (Admin).
     */
    public function kelolaBankSoalAdmin(Request $request, $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $selectedCategory = KategoriSoal::with('bankSoals')->findOrFail($id);

        $jenjang = $selectedCategory->jenjang;
        $kelas   = $selectedCategory->kelas;
        $sub     = $selectedCategory->sub_kategori;
        $mapel   = $selectedCategory->nama_kategori;
        $kategoriId = $selectedCategory->id;

        $prefixRoute = 'admin.bank-soal';

        return view('guru.kelolaBankSoal', compact(
            'selectedCategory', 'jenjang', 'kelas', 'sub', 'mapel', 'kategoriId', 'prefixRoute'
        ));
    }

    /**
     * Hapus Kategori Soal (beserta semua soal di dalamnya) untuk kombinasi tertentu.
     */
    public function deleteKategoriSoalAdmin($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $kategori = KategoriSoal::findOrFail($id);
        $mapel = $kategori->nama_kategori;
        $jenjang = $kategori->jenjang;
        $kelas = $kategori->kelas;
        $sub_kategori = $kategori->sub_kategori;
        $kategori->delete();

        return redirect()->route('admin.bank-soal.index', [
            'mapel' => $mapel,
            'jenjang' => $jenjang,
            'kelas' => $kelas,
            'sub_kategori' => $sub_kategori,
        ])->with('success', 'Semua soal ' . $mapel . ' untuk kombinasi ini berhasil dihapus!');
    }

    /**
     * Simpan Soal Baru ke Kategori.
     */
    public function storeSoalAdmin(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $validated = $request->validate([
            'kategori_soal_id' => 'required|exists:kategori_soals,id',
            'nomor' => 'required|integer|min:1',
            'soal' => 'required|string',
            'opsi_a' => 'required|string',
            'opsi_b' => 'required|string',
            'opsi_c' => 'required|string',
            'opsi_d' => 'required|string',
            'kunci_jawaban' => 'required|in:A,B,C,D',
        ]);

        $soal = BankSoal::create($validated);
        $kategori = $soal->kategori;

        return redirect()->route('admin.bank-soal.index', [
            'mapel' => $kategori->nama_kategori,
            'jenjang' => $kategori->jenjang,
            'kelas' => $kategori->kelas,
            'sub_kategori' => $kategori->sub_kategori,
            'kategori_id' => $kategori->id,
        ])->with('success', 'Soal No. ' . $soal->nomor . ' berhasil disimpan!');
    }

    /**
     * Update Data Soal.
     */
    public function updateSoalAdmin(Request $request, $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $soal = BankSoal::findOrFail($id);

        $validated = $request->validate([
            'nomor' => 'required|integer|min:1',
            'soal' => 'required|string',
            'opsi_a' => 'required|string',
            'opsi_b' => 'required|string',
            'opsi_c' => 'required|string',
            'opsi_d' => 'required|string',
            'kunci_jawaban' => 'required|in:A,B,C,D',
        ]);

        $soal->update($validated);
        $kategori = $soal->kategori;

        return redirect()->route('admin.bank-soal.index', [
            'mapel' => $kategori->nama_kategori,
            'jenjang' => $kategori->jenjang,
            'kelas' => $kategori->kelas,
            'sub_kategori' => $kategori->sub_kategori,
            'kategori_id' => $kategori->id,
        ])->with('success', 'Soal No. ' . $soal->nomor . ' berhasil diperbarui!');
    }

    /**
     * Hapus Soal.
     */
    public function deleteSoalAdmin($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $soal = BankSoal::findOrFail($id);
        $kategori = $soal->kategori;
        $nomor = $soal->nomor;
        $soal->delete();

        return redirect()->route('admin.bank-soal.index', [
            'mapel' => $kategori->nama_kategori,
            'jenjang' => $kategori->jenjang,
            'kelas' => $kategori->kelas,
            'sub_kategori' => $kategori->sub_kategori,
            'kategori_id' => $kategori->id,
        ])->with('success', 'Soal No. ' . $nomor . ' berhasil dihapus!');
    }

    /**
     * Simpan Kategori Soal Baru (Admin).
     */
    public function storeKategoriSoalAdmin(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $allowedSub = KategoriSoal::availableSubKategori(
            $request->input('jenjang'), $request->input('kelas')
        );

        $validated = $request->validate([
            'jenjang'       => 'required|in:SD,SMP,SMA',
            'kelas'         => 'required|integer|min:1|max:6',
            'sub_kategori'  => ['required', 'string', Rule::in($allowedSub)],
            'nama_kategori' => 'required|string|max:255',
            'deskripsi'     => 'required|string|max:255',
        ]);

        $kategori = KategoriSoal::create($validated);

        return redirect()->route('admin.bank-soal.kelola', $kategori->id)
            ->with('success', 'Paket soal "' . ($kategori->deskripsi ?: $kategori->nama_kategori) . '" berhasil dibuat! Silakan buat soal manual atau upload dokumen PDF/Word.');
    }

    /**
     * Update Kategori Soal (Admin).
     */
    public function updateKategoriSoalAdmin(Request $request, $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $kategori = KategoriSoal::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'sub_kategori'  => 'required|string|max:100',
            'deskripsi'     => 'required|string|max:255',
        ]);

        $kategori->update($validated);

        return redirect()->route('admin.bank-soal.index', [
            'jenjang'      => $kategori->jenjang,
            'kelas'        => $kategori->kelas,
            'sub_kategori' => $kategori->sub_kategori,
            'mapel'        => $kategori->nama_kategori,
            'kategori_id'  => $kategori->id,
        ])->with('success', 'Kategori Soal berhasil diperbarui!');
    }

    /**
     * Preview Import Soal dari Excel/CSV (Admin).
     */
    public function previewImportSoalAdmin(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'kategori_soal_id' => 'required|exists:kategori_soals,id',
            'file_excel'        => 'required|file|mimes:pdf,doc,docx,xlsx,xls,csv,txt|max:10240',
        ], [
            'file_excel.required' => 'File dokumen wajib diunggah.',
            'file_excel.mimes'    => 'Format file harus berupa PDF (.pdf), Word (.doc, .docx), Excel (.xlsx, .xls), atau CSV.',
            'file_excel.max'      => 'Ukuran file maksimal 10MB.',
        ]);

        $kategoriId = $request->input('kategori_soal_id');
        $kategori   = KategoriSoal::findOrFail($kategoriId);

        $file      = $request->file('file_excel');
        $filePath  = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());
        $origName  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        if (in_array($extension, ['pdf', 'doc', 'docx'])) {
            $destDir = public_path('uploads/bank_soal_docs');
            if (!file_exists($destDir)) {
                mkdir($destDir, 0777, true);
            }
            $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $origName);
            $fileName = "doc_{$kategoriId}_" . time() . "_{$safeName}.{$extension}";
            $file->move($destDir, $fileName);

            return redirect()->route('admin.bank-soal.index', [
                'jenjang'      => $kategori->jenjang,
                'kelas'        => $kategori->kelas,
                'sub_kategori' => $kategori->sub_kategori,
                'mapel'        => $kategori->nama_kategori,
                'kategori_id'  => $kategori->id,
            ])->with('success', 'File dokumen ' . strtoupper($extension) . ' ("' . $file->getClientOriginalName() . '") berhasil diunggah & tersimpan untuk kategori ini!');
        }

        $rows = [];

        try {
            if (in_array($extension, ['csv', 'txt'])) {
                if (($handle = fopen($filePath, 'r')) !== false) {
                    while (($data = fgetcsv($handle, 2000, ',')) !== false) {
                        if (count($data) === 1 && str_contains($data[0], ';')) {
                            $data = explode(';', $data[0]);
                        }
                        $rows[] = $data;
                    }
                    fclose($handle);
                }
            } else {
                $spreadsheet = IOFactory::load($filePath);
                $worksheet   = $spreadsheet->getActiveSheet();
                $rows        = $worksheet->toArray();
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }

        if (empty($rows)) {
            return redirect()->back()->with('error', 'File Excel kosong atau tidak dapat dibaca.');
        }

        $maxNo = (int) ($kategori->bankSoals()->max('nomor') ?? 0);
        $previewData = [];

        foreach ($rows as $index => $row) {
            $col0 = trim((string)($row[0] ?? ''));
            $col1 = trim((string)($row[1] ?? ''));

            if ($index === 0 && (!is_numeric($col0) || strtolower($col1) === 'soal' || strtolower($col0) === 'no')) {
                continue;
            }

            $no        = $col0;
            $soalText  = $col1;
            $opsiA     = trim((string)($row[2] ?? ''));
            $opsiB     = trim((string)($row[3] ?? ''));
            $opsiC     = trim((string)($row[4] ?? ''));
            $opsiD     = trim((string)($row[5] ?? ''));
            $kunciRaw  = strtoupper(trim((string)($row[6] ?? 'A')));

            if (empty($soalText) || empty($opsiA) || empty($opsiB)) {
                continue;
            }

            $nomorSoal = is_numeric($no) && (int)$no > 0 ? (int)$no : ($maxNo + count($previewData) + 1);
            $kunci     = in_array($kunciRaw, ['A', 'B', 'C', 'D']) ? $kunciRaw : 'A';

            $previewData[] = [
                'nomor'         => $nomorSoal,
                'soal'          => $soalText,
                'opsi_a'        => $opsiA,
                'opsi_b'        => $opsiB,
                'opsi_c'        => $opsiC,
                'opsi_d'        => $opsiD,
                'kunci_jawaban' => $kunci,
            ];
        }

        if (empty($previewData)) {
            return redirect()->back()->with('error', 'Tidak ditemukan data soal yang valid dalam file Excel tersebut.');
        }

        session([
            'import_preview_soals' => $previewData,
            'import_kategori_id'   => $kategoriId,
        ]);

        return redirect()->route('admin.bank-soal.index', [
            'jenjang'      => $kategori->jenjang,
            'kelas'        => $kategori->kelas,
            'sub_kategori' => $kategori->sub_kategori,
            'mapel'        => $kategori->nama_kategori,
            'kategori_id'  => $kategori->id,
        ])->with('success', 'Pratinjau ' . count($previewData) . ' soal dari Excel berhasil dimuat. Silakan periksa dan klik "Konfirmasi & Simpan ke Database".');
    }

    /**
     * Konfirmasi dan simpan soal dari session pratinjau ke Database (Admin).
     */
    public function confirmImportSoalAdmin(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $previewData = session('import_preview_soals', []);
        $kategoriId  = session('import_kategori_id');

        if (empty($previewData) || !$kategoriId) {
            return redirect()->back()->with('error', 'Data pratinjau tidak ditemukan atau sudah kedaluwarsa.');
        }

        $kategori = KategoriSoal::findOrFail($kategoriId);
        $savedCount = 0;

        foreach ($previewData as $item) {
            BankSoal::updateOrCreate([
                'kategori_soal_id' => $kategoriId,
                'nomor'            => $item['nomor'],
            ], [
                'soal'          => $item['soal'],
                'opsi_a'        => $item['opsi_a'],
                'opsi_b'        => $item['opsi_b'],
                'opsi_c'        => $item['opsi_c'],
                'opsi_d'        => $item['opsi_d'],
                'kunci_jawaban' => $item['kunci_jawaban'],
            ]);
            $savedCount++;
        }

        session()->forget(['import_preview_soals', 'import_kategori_id']);

        return redirect()->route('admin.bank-soal.index', [
            'jenjang'      => $kategori->jenjang,
            'kelas'        => $kategori->kelas,
            'sub_kategori' => $kategori->sub_kategori,
            'mapel'        => $kategori->nama_kategori,
            'kategori_id'  => $kategori->id,
        ])->with('success', 'Berhasil menyimpan ' . $savedCount . ' soal dari file Excel ke database!');
    }

    /**
     * Batalkan pratinjau impor soal (Admin).
     */
    public function cancelImportSoalAdmin(Request $request)
    {
        session()->forget(['import_preview_soals', 'import_kategori_id']);
        return redirect()->back()->with('success', 'Pratinjau impor soal berhasil dibatalkan.');
    }

    /**
     * Download Template File Excel / CSV untuk Import Soal (Admin).
     */
    public function downloadTemplateSoalAdmin()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_soal.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['no', 'soal', 'jawaban_a', 'jawaban_b', 'jawaban_c', 'jawaban_d', 'kunci_jawaban']);
            fputcsv($file, ['1', 'Berapakah hasil dari 15 + 25?', '30', '35', '40', '45', 'C']);
            fputcsv($file, ['2', 'Apa nama ibu kota negara Indonesia?', 'Jakarta', 'Bandung', 'Surabaya', 'Medan', 'A']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function syncRiwayatStatus(int $siswaId, string $newStatus, ?int $riwayatId = null): void
    {
        $query = RiwayatPembayaran::where('siswa_id', $siswaId)
            ->where('status', 'under_review');

        if ($riwayatId) {
            $query->where('id', $riwayatId); // spesifik, bukan asal broad
        }

        $query->update([
            'status' => $newStatus,
            'approved_at' => $newStatus === 'approved' ? now() : null,
        ]);
    }

    public function showYoutubeLink()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $youtubeLinks = YoutubeLink::orderBy('urutan', 'asc')->orderBy('id', 'desc')->get();
        return view('admin.link', compact('youtubeLinks'));
    }

    public function storeYoutubeLink(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'youtube_url' => 'required|string',
            'kategori' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        $youtubeId = YoutubeLink::parseYoutubeId($request->youtube_url);

        YoutubeLink::create([
            'judul' => $request->judul,
            'youtube_url' => $request->youtube_url,
            'youtube_id' => $youtubeId,
            'kategori' => $request->kategori ?? 'Tutorial',
            'deskripsi' => $request->deskripsi,
            'urutan' => $request->urutan ?? 0,
        ]);

        return redirect()->back()->with('success', 'Link YouTube tutorial berhasil ditambahkan!');
    }

    public function updateYoutubeLink(Request $request, $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $link = YoutubeLink::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'youtube_url' => 'required|string',
            'kategori' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        $youtubeId = YoutubeLink::parseYoutubeId($request->youtube_url);

        $link->update([
            'judul' => $request->judul,
            'youtube_url' => $request->youtube_url,
            'youtube_id' => $youtubeId,
            'kategori' => $request->kategori ?? 'Tutorial',
            'deskripsi' => $request->deskripsi,
            'urutan' => $request->urutan ?? 0,
        ]);

        return redirect()->back()->with('success', 'Link YouTube tutorial berhasil diperbarui!');
    }

    public function deleteYoutubeLink($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $link = YoutubeLink::findOrFail($id);
        $link->delete();

        return redirect()->back()->with('success', 'Link YouTube tutorial berhasil dihapus!');
    }

    /**
     * Halaman Input / Kelola Foto Guru & Banner Landing (Admin).
     */
    public function showInputFotoGuru()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $gurus = Guru::with('user')->get();

        // Banner photos
        $bannerFiles = [];
        $rawBanner = glob(public_path('uploads/landing/guru_banner*.*')) ?: [];
        foreach ($rawBanner as $file) {
            $bannerFiles[] = [
                'filename' => basename($file),
                'url'      => asset('uploads/landing/' . basename($file)) . '?v=' . filemtime($file),
            ];
        }

        return view('admin.inputfotoGuru', compact('gurus', 'bannerFiles'));
    }

    /**
     * Upload Foto Banner Guru & Sertifikat Landing Page (Admin).
     */
    public function storeBannerGuruFoto(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'foto_banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'foto_banner.required' => 'File foto banner wajib dipilih.',
            'foto_banner.image'    => 'File harus berupa gambar.',
            'foto_banner.mimes'    => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'foto_banner.max'      => 'Ukuran foto maksimal 5MB.',
        ]);

        $file = $request->file('foto_banner');
        $extension = $file->getClientOriginalExtension();
        $filename = 'guru_banner_' . time() . '.' . $extension;

        $destinationPath = public_path('uploads/landing');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $file->move($destinationPath, $filename);

        return redirect()->back()->with('success', 'Foto Banner Tim Pengajar & Sertifikat berhasil diunggah!');
    }

    /**
     * Hapus Foto Banner Guru Landing Page (Admin).
     */
    public function deleteBannerGuruFoto($filename)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $filePath = public_path('uploads/landing/' . basename($filename));
        if (file_exists($filePath)) {
            unlink($filePath);
            return redirect()->back()->with('success', 'Foto banner berhasil dihapus!');
        }

        return redirect()->back()->with('error', 'File foto banner tidak ditemukan.');
    }

    /**
     * Upload Foto Profil Guru Spesifik (Admin).
     */
    public function storeProfilGuruFoto(Request $request, $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $guru = Guru::findOrFail($id);

        $request->validate([
            'foto_guru' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'foto_guru.required' => 'File foto profil guru wajib dipilih.',
            'foto_guru.image'    => 'File harus berupa gambar.',
            'foto_guru.mimes'    => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'foto_guru.max'      => 'Ukuran foto maksimal 5MB.',
        ]);

        // Hapus foto lama jika ada
        if (!empty($guru->foto)) {
            $oldPath = public_path('uploads/guru/' . $guru->foto);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $file = $request->file('foto_guru');
        $extension = $file->getClientOriginalExtension();
        $filename = 'guru_' . $guru->id . '_' . time() . '.' . $extension;

        $destinationPath = public_path('uploads/guru');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $file->move($destinationPath, $filename);

        $guru->foto = $filename;
        $guru->save();

        return redirect()->back()->with('success', 'Foto profil untuk ' . ($guru->user ? $guru->user->name : 'Guru') . ' berhasil diperbarui!');
    }
}

