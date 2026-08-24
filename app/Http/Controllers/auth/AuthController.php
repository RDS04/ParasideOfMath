<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function information()
    {
        $youtubeLinks = \App\Models\YoutubeLink::orderBy('urutan', 'asc')->orderBy('id', 'desc')->get();
        return view('informasi.index', compact('youtubeLinks'));
    }
    public function showLoginForm()
    {
        if (Auth::guard('siswa')->check()) {
            return redirect()->route('siswa.dashboard');
        }

        if (Auth::guard('web')->check()) {
            return $this->redirectUserBasedOnRole(Auth::guard('web')->user());
        }

        return view('login');
    }

    /**
     * Proses Login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->has('remember');

        // 1. Coba login sebagai Siswa
        if (Auth::guard('siswa')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $siswa = Auth::guard('siswa')->user();
            if ($siswa->status === 'rejected') {
                $siswa->status = 'pending';
                $siswa->save();
                return redirect()->route('siswa.biodata')
                    ->with('error', 'Pendaftaran Anda sebelumnya ditolak oleh Admin. Seluruh data registrasi telah dibersihkan. Silakan isi kembali biodata Anda dari awal.');
            }
            if ($siswa->status === 'pending') {
                if (empty($siswa->biodata) || !is_array($siswa->biodata) || count($siswa->biodata) === 0) {
                    return redirect()->route('siswa.biodata')
                        ->with('info', 'Selesaikan pendaftaran Anda dan lanjutkan progres pendaftaran! Silakan isi biodata terlebih dahulu.');
                } else {
                    return redirect()->route('siswa.register-kategori')
                        ->with('info', 'Selesaikan pendaftaran Anda dan lanjutkan progres pendaftaran! Silakan pilih paket belajar Anda.');
                }
            }
            if ($siswa->status === 'under_review') {
                return redirect()->route('siswa.pending');
            }

            return redirect()->route('siswa.dashboard')
                ->with('success', 'Selamat datang kembali, ' . $siswa->name . '!');
        }

        // 2. Coba login sebagai Guru atau Admin (tabel users)
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::guard('web')->user();

            if ($user && $user->isGuru()) {
                $guruProfile = $user->getOrCreateGuruProfile();
                if (strtolower($guruProfile->status) === 'pending') {
                    Auth::guard('web')->logout();
                    return redirect()->route('guru.pending')
                        ->with('info', 'Akun Guru Anda (' . $user->name . ') saat ini masih dalam proses peninjauan dan menunggu persetujuan (approval) dari Admin.');
                } elseif (strtolower($guruProfile->status) === 'ditolak') {
                    Auth::guard('web')->logout();
                    return redirect()->route('login')
                        ->with('error', 'Maaf, pendaftaran akun Guru Anda (' . $user->name . ') ditolak oleh Admin.');
                }
            }

            return $this->redirectUserBasedOnRole($user)
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
            ]);
    }

    /**
     * Proses Registrasi Akun Baru (Siswa).
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:siswa'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Buat data di tabel siswa
        $siswa = Siswa::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Login as newly registered student
        Auth::guard('siswa')->login($siswa);

        return redirect()->route('siswa.biodata')
            ->with('success', 'Akun Siswa berhasil dibuat! Silakan lengkapi biodata Anda.');
    }

    /**
     * Tampilkan form registrasi Guru.
     */
    public function showGuruRegisterForm()
    {
        if (Setting::get('guru_register_enabled', '1') === '0') {
            return redirect()->route('login')->with('error', 'Pendaftaran guru/tutor saat ini sedang nonaktif.');
        }
        return view('guru.register');
    }

    /**
     * Proses Registrasi Guru Baru.
     */
    public function registerGuru(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Buat data di tabel users dengan role guru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru',
        ]);

        // Buat profil guru di tabel gurus dengan status pending (memerlukan approval Admin)
        \App\Models\Guru::create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        return redirect()->route('guru.pending')
            ->with('success', 'Pendaftaran Akun Guru berhasil! Akun Anda (' . $user->name . ') sedang dalam proses peninjauan dan menunggu persetujuan (approval) dari Admin.');
    }

    /**
     * Tampilkan halaman tunggu peninjauan (Pending) untuk Guru.
     */
    public function showGuruPending()
    {
        return view('guru.pending');
    }

    /**
     * Proses Logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('siswa')->logout();
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Redirect user based on their role (for web guard).
     */
    protected function redirectUserBasedOnRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isGuru()) {
            $guruProfile = $user->getOrCreateGuruProfile();
            if (strtolower($guruProfile->status) === 'pending') {
                Auth::guard('web')->logout();
                return redirect()->route('guru.pending')
                    ->with('info', 'Akun Guru Anda (' . $user->name . ') masih dalam proses peninjauan oleh Admin.');
            } elseif (strtolower($guruProfile->status) === 'ditolak') {
                Auth::guard('web')->logout();
                return redirect()->route('login')
                    ->with('error', 'Maaf, pendaftaran akun Guru Anda (' . $user->name . ') ditolak oleh Admin.');
            }
            return redirect()->route('guru.dashboard');
        }

        return redirect()->route('login');
    }
}
