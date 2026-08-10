<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\KategoriSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                                    'time' => $jamMulai . ' - ' . $jamSelesai,
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
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
        }

        $jenjang = strtoupper($request->input('jenjang', 'SD'));
        if (!in_array($jenjang, ['SD', 'SMP', 'SMA'])) {
            $jenjang = 'SD';
        }

        $sub_kategori = $request->input('sub_kategori', 'Semester 1');

        // Ambil semua daftar sub_kategori unik untuk jenjang ini
        $availableSubKategori = KategoriSoal::where('jenjang', $jenjang)
            ->distinct()
            ->pluck('sub_kategori')
            ->toArray();

        $defaultSubKategori = ['Semester 1', 'Semester 2', 'TKA'];
        $allSubKategori = array_unique(array_merge($defaultSubKategori, $availableSubKategori));

        // Ambil daftar kategori soal berdasarkan jenjang & sub_kategori
        $categories = KategoriSoal::where('jenjang', $jenjang)
            ->where('sub_kategori', $sub_kategori)
            ->withCount('bankSoals')
            ->get();

        $selected_kategori_id = $request->input('kategori_id');
        if (!$selected_kategori_id && $categories->isNotEmpty()) {
            $selected_kategori_id = $categories->first()->id;
        }

        $selectedCategory = null;
        if ($selected_kategori_id) {
            // Gunakan with() biasa — orderBy sudah ada di relasi KategoriSoal::bankSoals()
            $selectedCategory = KategoriSoal::with('bankSoals')->find($selected_kategori_id);
        }

        return view('guru.bankSoal', compact(
            'jenjang',
            'sub_kategori',
            'allSubKategori',
            'categories',
            'selectedCategory',
            'selected_kategori_id'
        ));
    }

    /**
     * Simpan Kategori Soal Baru.
     */
    public function storeKategoriSoal(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $validated = $request->validate([
            'jenjang' => 'required|in:SD,SMP,SMA',
            'sub_kategori' => 'required|string|max:100',
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori = KategoriSoal::create($validated);

        return redirect()->route('guru.bank-soal.index', [
            'jenjang' => $kategori->jenjang,
            'sub_kategori' => $kategori->sub_kategori,
            'kategori_id' => $kategori->id,
        ])->with('success', 'Kategori Soal berhasil ditambahkan!');
    }

    /**
     * Update Kategori Soal.
     */
    public function updateKategoriSoal(Request $request, $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $kategori = KategoriSoal::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'sub_kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update($validated);

        return redirect()->route('guru.bank-soal.index', [
            'jenjang' => $kategori->jenjang,
            'sub_kategori' => $kategori->sub_kategori,
            'kategori_id' => $kategori->id,
        ])->with('success', 'Kategori Soal berhasil diperbarui!');
    }

    /**
     * Hapus Kategori Soal beserta soal-soalnya.
     */
    public function deleteKategoriSoal($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $kategori = KategoriSoal::findOrFail($id);
        $jenjang = $kategori->jenjang;
        $sub_kategori = $kategori->sub_kategori;
        $kategori->delete();

        return redirect()->route('guru.bank-soal.index', [
            'jenjang' => $jenjang,
            'sub_kategori' => $sub_kategori,
        ])->with('success', 'Kategori Soal beserta seluruh soal di dalamnya berhasil dihapus!');
    }

    /**
     * Simpan Soal Baru ke Kategori.
     */
    public function storeSoal(Request $request)
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

        return redirect()->route('guru.bank-soal.index', [
            'jenjang' => $kategori->jenjang,
            'sub_kategori' => $kategori->sub_kategori,
            'kategori_id' => $kategori->id,
        ])->with('success', 'Soal No. ' . $soal->nomor . ' berhasil disimpan!');
    }

    /**
     * Update Data Soal.
     */
    public function updateSoal(Request $request, $id)
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

        return redirect()->route('guru.bank-soal.index', [
            'jenjang' => $kategori->jenjang,
            'sub_kategori' => $kategori->sub_kategori,
            'kategori_id' => $kategori->id,
        ])->with('success', 'Soal No. ' . $soal->nomor . ' berhasil diperbarui!');
    }

    /**
     * Hapus Soal.
     */
    public function deleteSoal($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        $soal = BankSoal::findOrFail($id);
        $kategori = $soal->kategori;
        $nomor = $soal->nomor;
        $soal->delete();

        return redirect()->route('guru.bank-soal.index', [
            'jenjang' => $kategori->jenjang,
            'sub_kategori' => $kategori->sub_kategori,
            'kategori_id' => $kategori->id,
        ])->with('success', 'Soal No. ' . $nomor . ' berhasil dihapus!');
    }
}
