<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\KategoriSoal;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GuruController extends Controller
{
    /**
     * Display Guru Dashboard with Biodata Completion Status.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
        }

        $guruProfile = $user->getOrCreateGuruProfile();
        $isBiodataComplete = $guruProfile->isComplete();
        $guruNameNorm = strtolower(trim($user->name));

        $assignedStudents = \App\Models\Siswa::where('status', 'active')
            ->get()
            ->filter(fn($siswa) => $this->isSiswaAssignedToGuru($siswa, $guruNameNorm))
            ->values();

        // Hitung Metric 1 (mapel unik yang diajar) & Metric 2 (akumulasi jam)
        $uniqueMapelsTaught = [];
        $totalMinutesTaught = 0;

        foreach ($assignedStudents as $siswa) {
            $biodata = $siswa->biodata ?? [];
            $mapelJadwal = $biodata['mapel_jadwal'] ?? [];
            $sesiPerMapel = $biodata['sesi_per_mapel'] ?? [];

            $paket = $siswa->paket;
            $durationMinutes = 90;
            if ($paket && preg_match('/(\d+)\s*menit/i', $paket->detail_5 ?? '', $dM)) {
                $durationMinutes = (int) $dM[1];
            }

            $mapelForThisGuru = $this->getMapelAssignedToGuru($siswa, $guruNameNorm);

            foreach ($mapelForThisGuru as $mapelName) {
                $uniqueMapelsTaught[$mapelName] = true;

                $mIdx = array_search($mapelName, $mapelJadwal);
                $sesi = ($mIdx !== false)
                    ? (int) ($sesiPerMapel[$mIdx] ?? ($biodata['jumlah_pertemuan'] ?? 0))
                    : (int) ($biodata['jumlah_pertemuan'] ?? 0);

                $totalMinutesTaught += $sesi * $durationMinutes;
            }
        }

        $totalKelasMengajar = count($uniqueMapelsTaught);
        $totalJamMengajar = round($totalMinutesTaught / 60, 1);

        return view('guru.index', compact(
            'user',
            'guruProfile',
            'isBiodataComplete',
            'assignedStudents',
            'totalKelasMengajar',
            'totalJamMengajar'
        ));
    }

    /**
     * Show Form to fill/update Guru Biodata.
     */
    public function showBiodata()
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
        }

        $guruProfile = $user->getOrCreateGuruProfile();

        return view('guru.biodata', compact('user', 'guruProfile'));
    }

    /**
     * Tampilkan formulir edit Biodata Guru (Pendidikan, Alamat, dll).
     */
    public function editBiodata()
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
        }

        $guruProfile = $user->getOrCreateGuruProfile();

        return view('guru.editBiodata', compact('user', 'guruProfile'));
    }

    /**
     * Update Guru Biodata.
     */
    public function updateBiodata(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
        }

        $request->validate([
            'gelar' => ['nullable', 'string', 'max:50'],
            'pendidikan_terakhir' => ['required', 'string', 'max:255'],
            'spesialisasi' => ['required', 'string', 'max:255'],
            'no_telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string', 'max:1000'],
            'pengalaman_mengajar' => ['nullable', 'string', 'max:100'],
        ], [
            'pendidikan_terakhir.required' => 'Pendidikan terakhir / lulusan wajib diisi.',
            'spesialisasi.required' => 'Spesialisasi mengajar (misal: Matematika SMA) wajib diisi.',
            'no_telp.required' => 'Nomor WhatsApp / telepon wajib diisi.',
            'alamat.required' => 'Alamat domisili wajib diisi.',
        ]);

        // Update Guru Profile
        $guruProfile = $user->getOrCreateGuruProfile();
        $guruProfile->update([
            'gelar' => $request->gelar,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'spesialisasi' => $request->spesialisasi,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'pengalaman_mengajar' => $request->pengalaman_mengajar,
        ]);

        return redirect()->route('guru.biodata')
            ->with('success', 'Biodata Guru berhasil diperbarui!');
    }

    /**
     * Tampilkan formulir edit Profil Guru (Foto, Nama, Bio).
     */
    public function editProfil()
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
        }

        $guruProfile = $user->getOrCreateGuruProfile();

        return view('guru.profil', compact('user', 'guruProfile'));
    }

    /**
     * Update Profil Guru (Foto, Nama, Bio).
     */
    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio_singkat' => ['nullable', 'string', 'max:2000'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'foto.image' => 'Berkas harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp.',
            'foto.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        // Update User Name
        $user->name = $request->name;
        $user->save();

        // Update Bio Singkat in Guru Profile
        $guruProfile = $user->getOrCreateGuruProfile();
        $guruProfile->update([
            'bio_singkat' => $request->bio_singkat,
        ]);

        // Handle Profile Photo Upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = 'guru_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Make sure directory exists
            if (!file_exists(public_path('uploads/guru'))) {
                mkdir(public_path('uploads/guru'), 0777, true);
            }

            // Move file to public/uploads/guru/ directory
            $file->move(public_path('uploads/guru'), $fileName);

            // Delete old photo if exists
            if ($guruProfile->foto && file_exists(public_path($guruProfile->foto))) {
                @unlink(public_path($guruProfile->foto));
            }

            $guruProfile->foto = 'uploads/guru/' . $fileName;
            $guruProfile->save();
        }

        return redirect()->route('guru.biodata')
            ->with('success', 'Profil Guru (Nama, Foto & Bio) berhasil diperbarui!');
    }

    /**
     * Tampilkan Halaman Jadwal Mengajar Guru / Kalender Interaktif.
     */
    public function showJadwal()
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
        }

        $guruNameNorm = strtolower(trim($user->name));

        // Fetch students assigned to this Guru
        $assignedStudents = \App\Models\Siswa::with('paket')->get()->filter(
            fn ($siswa) => $this->isSiswaAssignedToGuru($siswa, $guruNameNorm)
        );

        $sessions = [];
        $dayMap = [
            'minggu' => 0,
            'senin' => 1,
            'selasa' => 2,
            'rabu' => 3,
            'kamis' => 4,
            'jumat' => 5,
            'sabtu' => 6,
            'sunday' => 0,
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6
        ];

        foreach ($assignedStudents as $siswa) {
            $biodata = $siswa->biodata ?? [];

            // Extract mapels for this student
            $mapelJadwal = $biodata['mapel_jadwal'] ?? [];
            $sesiPerMapel = $biodata['sesi_per_mapel'] ?? [];
            $hariPerMapel = $biodata['hari_per_mapel'] ?? [];
            $tanggalPerMapel = $biodata['tanggal_mulai_per_mapel'] ?? [];
            $jamPerMapel = $biodata['jam_per_mapel'] ?? [];

            if (empty($mapelJadwal) && $siswa->tipe_paket) {
                if (preg_match('/Mapel:\s*([^)|]+)/i', $siswa->tipe_paket, $matches)) {
                    $mapelJadwal = array_map('trim', explode(',', $matches[1]));
                }
            }

            $paket = $siswa->paket;
            $jamMulai = $paket ? ($paket->jam_mulai ?? '15:30') : '15:30';
            $durationMinutes = 90;
            if ($paket && preg_match('/(\d+)\s*menit/i', $paket->detail_5 ?? '', $dMatches)) {
                $durationMinutes = (int) $dMatches[1];
            }

            // Determine which specific mapels are assigned to this Guru
            $tutorPerMapel = $biodata['tutor_per_mapel'] ?? [];
            $assignedMapelsForThisGuru = [];

            if (is_array($tutorPerMapel) && !empty($tutorPerMapel)) {
                foreach ($tutorPerMapel as $mapelName => $tName) {
                    if (strtolower(trim($tName)) === $guruNameNorm) {
                        $assignedMapelsForThisGuru[] = $mapelName;
                    }
                }
            }

            if (empty($assignedMapelsForThisGuru) && $siswa->tipe_paket && preg_match('/Guru:\s*([^|)]+)/i', $siswa->tipe_paket, $m)) {
                $guruParts = array_map('trim', explode(',', $m[1]));
                foreach ($guruParts as $part) {
                    if (str_contains(strtolower($part), $guruNameNorm)) {
                        if (str_contains($part, ':')) {
                            $p = explode(':', $part);
                            $assignedMapelsForThisGuru[] = trim($p[0]);
                        }
                    }
                }
            }

            // Fallback: if no specific per-mapel assignment found, assign all mapels
            if (empty($assignedMapelsForThisGuru)) {
                $assignedMapelsForThisGuru = !empty($mapelJadwal) ? $mapelJadwal : ['Bimbingan Belajar'];
            }

            // Jam Mulai & Selesai
            $paket = $siswa->paket;
            $jamMulai = $paket ? ($paket->jam_mulai ?? '15:30') : '15:30';
            $durationMinutes = 90;
            if ($paket && preg_match('/(\d+)\s*menit/i', $paket->detail_5 ?? '', $dMatches)) {
                $durationMinutes = (int) $dMatches[1];
            }
            $jamSelesai = date('H:i', strtotime($jamMulai . " + {$durationMinutes} minutes"));

            // Process sessions per mapel
            foreach ($assignedMapelsForThisGuru as $mapelName) {
                $mIdx = array_search($mapelName, $mapelJadwal);

                $hariList = [];
                $tglMulai = null;
                $limitSesi = 0;
                $jamMulaiItem = $jamMulai;
                $jamSelesaiItem = $jamSelesai;

                if ($mIdx !== false && isset($hariPerMapel[$mIdx])) {
                    $rawH = $hariPerMapel[$mIdx];
                    $hariList = is_array($rawH) ? array_values(array_filter($rawH)) : [];
                    $tglMulai = $tanggalPerMapel[$mIdx] ?? ($biodata['tanggal_mulai'] ?? null);
                    $limitSesi = (int) ($sesiPerMapel[$mIdx] ?? ($biodata['jumlah_pertemuan'] ?? 0));
                } else {
                    $hariList = $biodata['hari_pertemuan'] ?? [];
                    $tglMulai = $biodata['tanggal_mulai'] ?? null;
                    $limitSesi = (int) ($biodata['jumlah_pertemuan'] ?? 0);
                }

                if ($mIdx !== false && isset($jamPerMapel[$mIdx]) && is_array($jamPerMapel[$mIdx])) {
                    if (!empty($jamPerMapel[$mIdx]['jam_mulai'])) {
                        $jamMulaiItem = $jamPerMapel[$mIdx]['jam_mulai'];
                    }
                    if (!empty($jamPerMapel[$mIdx]['jam_selesai'])) {
                        $jamSelesaiItem = $jamPerMapel[$mIdx]['jam_selesai'];
                    }
                }

                // Fallback tipe_paket parsing
                if (empty($hariList) && $siswa->tipe_paket && preg_match('/Hari:\s*([^)|]+)/i', $siswa->tipe_paket, $m)) {
                    $hariList = array_map('trim', explode(',', $m[1]));
                }
                if ($limitSesi === 0 && $siswa->tipe_paket) {
                    if (preg_match('/Total Sesi:\s*(\d+)x/i', $siswa->tipe_paket, $m)) {
                        $limitSesi = (int) $m[1];
                    } elseif (preg_match('/Sesi:\s*(\d+)x/i', $siswa->tipe_paket, $m)) {
                        $limitSesi = (int) $m[1];
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

                // Convert day names to day numbers
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
                                $sessions[] = [
                                    'dateStr' => $dateStr,
                                    'student_name' => $siswa->name,
                                    'subject' => $mapelName,
                                    'time' => $jamMulaiItem . ' - ' . $jamSelesaiItem,
                                    'whatsapp' => $siswa->whatsapp,
                                    'sekolah' => $siswa->sekolah,
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

        return view('guru.jadwal', compact('sessions', 'assignedStudents'));
    }

    /**
     * Tampilkan Halaman Daftar Siswa Bimbingan Guru.
     */
    public function showSiswa()
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
        }

        $guruNameNorm = strtolower(trim($user->name));

        // Fetch students assigned to this Guru
        $assignedStudents = \App\Models\Siswa::with('paket')->get()->filter(
            fn ($siswa) => $this->isSiswaAssignedToGuru($siswa, $guruNameNorm)
        );

        return view('guru.dataSiswa', compact('assignedStudents'));
    }

    /**
     * Cek apakah siswa ini dibimbing oleh guru tertentu (normalized name).
     * Prioritas: tutor_per_mapel > tutor_names > tipe_paket string.
     */
    private function isSiswaAssignedToGuru($siswa, string $guruNameNorm): bool
    {
        $biodata = $siswa->biodata ?? [];

        $tutorPerMapel = $biodata['tutor_per_mapel'] ?? [];
        if (is_array($tutorPerMapel) && !empty($tutorPerMapel)) {
            foreach ($tutorPerMapel as $tName) {
                if (strtolower(trim($tName)) === $guruNameNorm) {
                    return true;
                }
            }
        }

        $tutorNames = $biodata['tutor_names'] ?? [];
        if (is_array($tutorNames) && !empty($tutorNames)) {
            foreach ($tutorNames as $tName) {
                if (strtolower(trim($tName)) === $guruNameNorm) {
                    return true;
                }
            }
        }

        if ($siswa->tipe_paket && str_contains(strtolower($siswa->tipe_paket), $guruNameNorm)) {
            return true;
        }

        return false;
    }

    /**
     * Ambil daftar nama mapel yang secara spesifik ditugaskan ke guru ini
     * untuk siswa tertentu. Fallback: semua mapel siswa jika tidak ada
     * penugasan per-mapel yang eksplisit.
     */
    private function getMapelAssignedToGuru($siswa, string $guruNameNorm): array
    {
        $biodata = $siswa->biodata ?? [];
        $mapelJadwal = $biodata['mapel_jadwal'] ?? [];

        if (empty($mapelJadwal) && $siswa->tipe_paket) {
            if (preg_match('/Mapel:\s*([^)|]+)/i', $siswa->tipe_paket, $matches)) {
                $mapelJadwal = array_map('trim', explode(',', $matches[1]));
            }
        }

        $tutorPerMapel = $biodata['tutor_per_mapel'] ?? [];
        $assigned = [];

        if (is_array($tutorPerMapel) && !empty($tutorPerMapel)) {
            foreach ($tutorPerMapel as $mapelName => $tName) {
                if (strtolower(trim($tName)) === $guruNameNorm) {
                    $assigned[] = $mapelName;
                }
            }
        }

        if (empty($assigned) && $siswa->tipe_paket && preg_match('/Guru:\s*([^|)]+)/i', $siswa->tipe_paket, $m)) {
            $guruParts = array_map('trim', explode(',', $m[1]));
            foreach ($guruParts as $part) {
                if (str_contains(strtolower($part), $guruNameNorm) && str_contains($part, ':')) {
                    $p = explode(':', $part);
                    $assigned[] = trim($p[0]);
                }
            }
        }

        if (empty($assigned)) {
            $assigned = !empty($mapelJadwal) ? $mapelJadwal : ['Bimbingan Belajar'];
        }

        return $assigned;
    }
    
    public function bankSoal(Request $request)
    {
        $user = Auth::user();
        if (!$user || (! $user->isGuru() && ! $user->isAdmin())) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
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
        //         ambil daftar KategoriSoal (judul/deskripsi) yang sudah diupload.
        if ($jenjang && $kelas && $sub && $mapel) {
            $kategoriList = KategoriSoal::where('jenjang', $jenjang)
                ->where('kelas', $kelas)
                ->where('sub_kategori', $sub)
                ->where('nama_kategori', $mapel)
                ->withCount('bankSoals')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Step 6: Jika memilih salah satu kategori, load soal-soalnya
        if ($kategoriId) {
            $selectedCategory = KategoriSoal::find($kategoriId);
            if ($selectedCategory) {
                $selectedCategory->load('bankSoals');
            }
        }

        return view('guru.bankSoal', compact(
            'jenjang', 'kelas', 'sub', 'mapel',
            'availableClasses', 'availableSubs', 'mapelList',
            'kategoriList', 'selectedCategory', 'kategoriId'
        ));
    }

    /**
     * Simpan Kategori Soal Baru.
     */
    public function storeKategoriSoal(Request $request)
    {
        $user = Auth::user();
        if (!$user || (! $user->isGuru() && ! $user->isAdmin())) {
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

        return redirect()->route('guru.bank-soal.index', [
            'jenjang'      => $kategori->jenjang,
            'kelas'        => $kategori->kelas,
            'sub_kategori' => $kategori->sub_kategori,
            'kategori_id'  => $kategori->id,
        ])->with('success', 'Kategori Soal berhasil ditambahkan!');
    }

    /**
     * Update Kategori Soal.
     */
    public function updateKategoriSoal(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || (! $user->isGuru() && ! $user->isAdmin())) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $kategori = KategoriSoal::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'sub_kategori' => 'required|string|max:100',
            'deskripsi' => 'required|string|max:255',
        ]);

        $kategori->update($validated);

        return redirect()->route('guru.bank-soal.index', [
            'jenjang' => $kategori->jenjang,
            'kelas' => $kategori->kelas,
            'sub_kategori' => $kategori->sub_kategori,
            'kategori_id' => $kategori->id,
        ])->with('success', 'Kategori Soal berhasil diperbarui!');
    }

    /**
     * Hapus Kategori Soal beserta soal-soalnya.
     */
    public function deleteKategoriSoal($id)
    {
        $user = Auth::user();
        if (!$user || (! $user->isGuru() && ! $user->isAdmin())) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $kategori = KategoriSoal::findOrFail($id);
        $jenjang = $kategori->jenjang;
        $kelas = $kategori->kelas;
        $sub_kategori = $kategori->sub_kategori;
        $kategori->delete();

        return redirect()->route('guru.bank-soal.index', [
            'jenjang' => $jenjang,
            'kelas' => $kelas,
            'sub_kategori' => $sub_kategori,
        ])->with('success', 'Kategori Soal beserta seluruh soal di dalamnya berhasil dihapus!');
    }

    /**
     * Simpan Soal Baru ke Kategori.
     */
    public function storeSoal(Request $request)
    {
        $user = Auth::user();
        if (!$user || (! $user->isGuru() && ! $user->isAdmin())) {
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

        return redirect()->route('guru.bank-soal.index', [
            'jenjang' => $kategori->jenjang,
            'kelas' => $kategori->kelas,
            'sub_kategori' => $kategori->sub_kategori,
            'kategori_id' => $kategori->id,
        ])->with('success', 'Soal No. ' . $soal->nomor . ' berhasil disimpan!');
    }

    /**
     * Update Data Soal.
     */
    public function updateSoal(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || (! $user->isGuru() && ! $user->isAdmin())) {
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

        return redirect()->route('guru.bank-soal.index', [
            'jenjang' => $kategori->jenjang,
            'kelas' => $kategori->kelas,
            'sub_kategori' => $kategori->sub_kategori,
            'kategori_id' => $kategori->id,
        ])->with('success', 'Soal No. ' . $soal->nomor . ' berhasil diperbarui!');
    }

    /**
     * Hapus Soal.
     */
    public function deleteSoal($id)
    {
        $user = Auth::user();
        if (!$user || (! $user->isGuru() && ! $user->isAdmin())) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $soal = BankSoal::findOrFail($id);
        $kategori = $soal->kategori;
        $nomor = $soal->nomor;
        $soal->delete();

        return redirect()->route('guru.bank-soal.index', [
            'jenjang' => $kategori->jenjang,
            'kelas' => $kategori->kelas,
            'sub_kategori' => $kategori->sub_kategori,
            'kategori_id' => $kategori->id,
        ])->with('success', 'Soal No. ' . $nomor . ' berhasil dihapus!');
    }

    /**
     * Preview / Pratinjau Soal dari file Excel / CSV sebelum disimpan ke DB.
     */
    public function previewImportSoal(Request $request)
    {
        $user = Auth::user();
        if (!$user || (!$user->isGuru() && !$user->isAdmin())) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'kategori_soal_id' => 'required|exists:kategori_soals,id',
            'file_excel'        => 'required|file|mimes:xlsx,xls,csv,txt|max:5120',
        ], [
            'file_excel.required' => 'File Excel / CSV wajib diunggah.',
            'file_excel.mimes'    => 'Format file harus berupa Excel (.xlsx, .xls) atau CSV (.csv).',
            'file_excel.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $kategoriId = $request->input('kategori_soal_id');
        $kategori   = KategoriSoal::findOrFail($kategoriId);

        $file      = $request->file('file_excel');
        $filePath  = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());

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

            // Skip header if line 0 looks like header
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
                'nomor'        => $nomorSoal,
                'soal'         => $soalText,
                'opsi_a'       => $opsiA,
                'opsi_b'       => $opsiB,
                'opsi_c'       => $opsiC,
                'opsi_d'       => $opsiD,
                'kunci_jawaban' => $kunci,
            ];
        }

        if (empty($previewData)) {
            return redirect()->back()->with('error', 'Tidak ditemukan data soal yang valid dalam file Excel tersebut.');
        }

        // Simpan data pratinjau di Session
        session([
            'import_preview_soals' => $previewData,
            'import_kategori_id'  => $kategoriId,
        ]);

        return redirect()->route('guru.bank-soal.index', [
            'jenjang'      => $kategori->jenjang,
            'kelas'        => $kategori->kelas,
            'sub_kategori' => $kategori->sub_kategori,
            'kategori_id'  => $kategori->id,
        ])->with('info', 'Pratinjau ' . count($previewData) . ' soal dari Excel berhasil dimuat. Silakan periksa dan klik "Konfirmasi & Simpan ke Database".');
    }

    /**
     * Legacy route helper pointing to previewImportSoal.
     */
    public function importSoal(Request $request)
    {
        return $this->previewImportSoal($request);
    }

    /**
     * Konfirmasi dan simpan soal dari session pratinjau ke Database.
     */
    public function confirmImportSoal(Request $request)
    {
        $user = Auth::user();
        if (!$user || (!$user->isGuru() && !$user->isAdmin())) {
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

        // Hapus data session pratinjau
        session()->forget(['import_preview_soals', 'import_kategori_id']);

        return redirect()->route('guru.bank-soal.index', [
            'jenjang'      => $kategori->jenjang,
            'kelas'        => $kategori->kelas,
            'sub_kategori' => $kategori->sub_kategori,
            'kategori_id'  => $kategori->id,
        ])->with('success', 'Berhasil menyimpan ' . $savedCount . ' soal dari file Excel ke database!');
    }

    /**
     * Batalkan pratinjau impor soal.
     */
    public function cancelImportSoal(Request $request)
    {
        session()->forget(['import_preview_soals', 'import_kategori_id']);
        return redirect()->back()->with('success', 'Pratinjau impor soal berhasil dibatalkan.');
    }

    /**
     * Download Template File Excel / CSV untuk Import Soal.
     */
    public function downloadTemplateSoal()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_soal.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header: 1 no, 2 soal, jawaban A, jawaban B, jawaban C, jawaban D, Kunci jawaban
            fputcsv($file, ['no', 'soal', 'jawaban_a', 'jawaban_b', 'jawaban_c', 'jawaban_d', 'kunci_jawaban']);

            // Sample rows
            fputcsv($file, ['1', 'Berapakah hasil dari 15 + 25?', '30', '35', '40', '45', 'C']);
            fputcsv($file, ['2', 'Apa nama ibu kota negara Indonesia?', 'Jakarta', 'Bandung', 'Surabaya', 'Medan', 'A']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Tampilkan Halaman Penugasan Ujian Siswa oleh Guru.
     */
    public function showUjianGuru(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
        }

        $guruNameNorm = strtolower(trim($user->name));

        // Fetch active students assigned to this Guru
        $assignedStudents = \App\Models\Siswa::with('paket')->where('status', 'active')->get()->filter(
            fn ($siswa) => $this->isSiswaAssignedToGuru($siswa, $guruNameNorm)
        )->values();

        // Selected student ID or first student
        $selectedSiswaId = $request->input('siswa_id');
        $selectedSiswa = $assignedStudents->firstWhere('id', $selectedSiswaId) ?: $assignedStudents->first();

        // All available KategoriSoal (Question Packages) with question count
        $categoriesQuery = \App\Models\KategoriSoal::withCount('bankSoals');

        // Optional filtering by Jenjang or Mapel if requested
        if ($request->filled('jenjang')) {
            $categoriesQuery->where('jenjang', strtoupper($request->jenjang));
        }
        if ($request->filled('mapel')) {
            $categoriesQuery->where('nama_kategori', $request->mapel);
        }

        $allCategories = $categoriesQuery->orderBy('created_at', 'desc')->get();

        // Existing assigned exams for the selected student
        $assignedExams = [];
        $hasilUjians = collect();

        if ($selectedSiswa) {
            $biodata = $selectedSiswa->biodata ?? [];
            $assignedExams = $biodata['assigned_ujian'] ?? [];

            // Get exam results completed by this student
            $hasilUjians = \App\Models\HasilUjian::where('siswa_id', $selectedSiswa->id)
                ->with('kategori')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('guru.ujian', compact(
            'user',
            'assignedStudents',
            'selectedSiswa',
            'allCategories',
            'assignedExams',
            'hasilUjians'
        ));
    }

    /**
     * Tugaskan Paket Soal Ujian ke Siswa.
     */
    public function assignUjianGuru(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
        }

        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'kategori_soal_id' => 'required|exists:kategori_soals,id',
            'catatan' => 'nullable|string|max:255',
            'tgl_deadline' => 'nullable|date',
        ]);

        $siswa = \App\Models\Siswa::findOrFail($request->siswa_id);
        $kategori = \App\Models\KategoriSoal::findOrFail($request->kategori_soal_id);

        $biodata = $siswa->biodata ?? [];
        $assignedExams = $biodata['assigned_ujian'] ?? [];

        // Check if already assigned
        $alreadyAssigned = false;
        foreach ($assignedExams as $item) {
            if (isset($item['kategori_soal_id']) && $item['kategori_soal_id'] == $kategori->id) {
                $alreadyAssigned = true;
                break;
            }
        }

        if ($alreadyAssigned) {
            return redirect()->route('guru.ujian.index', ['siswa_id' => $siswa->id])
                ->with('error', 'Paket soal "' . ($kategori->deskripsi ?: $kategori->nama_kategori) . '" sudah ditugaskan kepada siswa ' . $siswa->name . '.');
        }

        $newAssignment = [
            'id' => uniqid('ex_'),
            'kategori_soal_id' => $kategori->id,
            'nama_kategori' => $kategori->nama_kategori,
            'deskripsi' => $kategori->deskripsi,
            'jenjang' => $kategori->jenjang,
            'sub_kategori' => $kategori->sub_kategori,
            'guru_id' => $user->id,
            'guru_name' => $user->name,
            'tanggal_ditugaskan' => date('Y-m-d H:i'),
            'tgl_deadline' => $request->tgl_deadline,
            'catatan' => $request->catatan ?: 'Silakan dikerjakan dengan jujur & cermat.',
        ];

        $assignedExams[] = $newAssignment;
        $biodata['assigned_ujian'] = array_values($assignedExams);

        $siswa->biodata = $biodata;
        $siswa->save();

        return redirect()->route('guru.ujian.index', ['siswa_id' => $siswa->id])
            ->with('success', 'Berhasil menugaskan paket soal "' . ($kategori->deskripsi ?: $kategori->nama_kategori) . '" kepada ' . $siswa->name . '!');
    }

    /**
     * Batalkan / Hapus Penugasan Ujian Siswa.
     */
    public function unassignUjianGuru(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
        }

        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'assignment_id' => 'required|string',
        ]);

        $siswa = \App\Models\Siswa::findOrFail($request->siswa_id);
        $biodata = $siswa->biodata ?? [];
        $assignedExams = $biodata['assigned_ujian'] ?? [];

        $filteredExams = array_filter($assignedExams, function ($item) use ($request) {
            return isset($item['id']) && $item['id'] !== $request->assignment_id;
        });

        $biodata['assigned_ujian'] = array_values($filteredExams);
        $siswa->biodata = $biodata;
        $siswa->save();

        return redirect()->route('guru.ujian.index', ['siswa_id' => $siswa->id])
            ->with('success', 'Penugasan ujian telah dibatalkan!');
    }
}
