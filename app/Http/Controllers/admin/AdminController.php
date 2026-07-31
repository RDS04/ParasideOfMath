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
     * Tampilkan Halaman Detail Data Registrasi Siswa.
     */
    public function detailSiswa($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Halaman khusus Admin.');
        }

        $student = \App\Models\Siswa::findOrFail($id);
        $paket = \App\Models\PaketBelajar::find($student->paket_id);

        return view('admin.detailData', compact('student', 'paket'));
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
}
