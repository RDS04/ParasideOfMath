<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
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

        // Fetch students assigned to this Guru by Admin
        $assignedStudents = \App\Models\Siswa::all()->filter(function ($siswa) use ($user) {
            $biodata = $siswa->biodata ?? [];
            $tutorNames = $biodata['tutor_names'] ?? [];
            if (is_array($tutorNames)) {
                return in_array($user->name, $tutorNames);
            }
            return str_contains($siswa->tipe_paket ?? '', $user->name);
        });

        return view('guru.index', compact('user', 'guruProfile', 'isBiodataComplete', 'assignedStudents'));
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

        // Fetch students assigned to this Guru
        $assignedStudents = \App\Models\Siswa::all()->filter(function ($siswa) use ($user) {
            $biodata = $siswa->biodata ?? [];
            $tutorNames = $biodata['tutor_names'] ?? [];
            if (is_array($tutorNames)) {
                return in_array($user->name, $tutorNames);
            }
            return false;
        });

        $sessions = [];
        $dayMap = [
            'Minggu' => 0, 'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3,
            'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6
        ];

        foreach ($assignedStudents as $siswa) {
            $biodata = $siswa->biodata ?? [];
            $hariPertemuan = $biodata['hari_pertemuan'] ?? [];
            $jumlahPertemuan = $biodata['jumlah_pertemuan'] ?? 0;
            $tanggalMulai = $biodata['tanggal_mulai'] ?? null;
            $paket = $siswa->paket;

            // Fallback parsing from tipe_paket if empty
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
            if (!$tanggalMulai && $siswa->tipe_paket) {
                if (preg_match('/Mulai:\s*([\d\-]+)/i', $siswa->tipe_paket, $matches)) {
                    $d = trim($matches[1]);
                    if (preg_match('/(\d{2})-(\d{2})-(\d{4})/', $d, $dMatches)) {
                        $tanggalMulai = $dMatches[3] . '-' . $dMatches[2] . '-' . $dMatches[1];
                    }
                }
            }
            if (!$tanggalMulai && $siswa->created_at) {
                $tanggalMulai = $siswa->created_at->format('Y-m-d');
            }

            $jamMulai = $paket ? ($paket->jam_mulai ?? '15:30') : '15:30';
            $durationMinutes = 90;
            if ($paket && preg_match('/(\d+)\s*menit/i', $paket->detail_5, $dMatches)) {
                $durationMinutes = (int) $dMatches[1];
            }
            $jamSelesai = date('H:i', strtotime($jamMulai . " + {$durationMinutes} minutes"));

            // Find the subject taught by this teacher for this student
            $tutorNames = $biodata['tutor_names'] ?? [];
            $tutorSubjects = $biodata['tutor_subjects'] ?? [];
            $tIndex = is_array($tutorNames) ? array_search($user->name, $tutorNames) : false;
            $assignedMapel = ($tIndex !== false && isset($tutorSubjects[$tIndex])) ? $tutorSubjects[$tIndex] : 'Matapelajaran';

            // Generate dates for sessions
            $scheduledDayNums = [];
            foreach ($hariPertemuan as $h) {
                if (isset($dayMap[$h])) {
                    $scheduledDayNums[] = $dayMap[$h];
                }
            }

            if ($jumlahPertemuan > 0 && !empty($scheduledDayNums) && $tanggalMulai) {
                try {
                    $startDate = \Carbon\Carbon::parse($tanggalMulai);
                    $tempDate = $startDate->copy();
                    $studentSessionCount = 0;
                    
                    for ($d = 0; $d < 365; $d++) {
                        if ($studentSessionCount >= $jumlahPertemuan) {
                            break;
                        }
                        $dayOfWeek = $tempDate->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
                        if (in_array($dayOfWeek, $scheduledDayNums)) {
                            $studentSessionCount++;
                            $dateStr = $tempDate->format('Y-m-d');
                            $sessions[] = [
                                'dateStr' => $dateStr,
                                'student_name' => $siswa->name,
                                'subject' => $assignedMapel,
                                'time' => $jamMulai . ' - ' . $jamSelesai,
                                'whatsapp' => $siswa->whatsapp,
                                'sekolah' => $siswa->sekolah,
                                'session_index' => $studentSessionCount,
                                'total_sessions' => $jumlahPertemuan,
                            ];
                        }
                        $tempDate->addDay();
                    }
                } catch (\Exception $e) {
                    // Ignore errors
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

        // Fetch students assigned to this Guru
        $assignedStudents = \App\Models\Siswa::all()->filter(function ($siswa) use ($user) {
            $biodata = $siswa->biodata ?? [];
            $tutorNames = $biodata['tutor_names'] ?? [];
            if (is_array($tutorNames)) {
                return in_array($user->name, $tutorNames);
            }
            return false;
        });

        return view('guru.dataSiswa', compact('assignedStudents'));
    }
}
