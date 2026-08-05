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
     * Update Guru Biodata.
     */
    public function updateBiodata(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isGuru()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Guru.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'gelar' => ['nullable', 'string', 'max:50'],
            'pendidikan_terakhir' => ['required', 'string', 'max:255'],
            'spesialisasi' => ['required', 'string', 'max:255'],
            'no_telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string', 'max:1000'],
            'pengalaman_mengajar' => ['nullable', 'string', 'max:100'],
            'bio_singkat' => ['nullable', 'string', 'max:2000'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'pendidikan_terakhir.required' => 'Pendidikan terakhir / lulusan wajib diisi.',
            'spesialisasi.required' => 'Spesialisasi mengajar (misal: Matematika SMA) wajib diisi.',
            'no_telp.required' => 'Nomor WhatsApp / telepon wajib diisi.',
            'alamat.required' => 'Alamat domisili wajib diisi.',
        ]);

        // Update User Name
        $user->name = $request->name;
        $user->save();

        // Update Guru Profile
        $guruProfile = $user->getOrCreateGuruProfile();
        $guruProfile->update([
            'gelar' => $request->gelar,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'spesialisasi' => $request->spesialisasi,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'pengalaman_mengajar' => $request->pengalaman_mengajar,
            'bio_singkat' => $request->bio_singkat,
        ]);

        return redirect()->route('guru.dashboard')
            ->with('success', 'Biodata Pengajar berhasil diperbarui dan disimpan!');
    }
}
