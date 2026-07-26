<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan form login & register.
     */
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

            return redirect()->route('siswa.dashboard')
                ->with('success', 'Selamat datang kembali, ' . Auth::guard('siswa')->user()->name . '!');
        }

        // 2. Coba login sebagai Guru atau Admin (tabel users)
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return $this->redirectUserBasedOnRole(Auth::guard('web')->user())
                ->with('success', 'Selamat datang kembali, ' . Auth::guard('web')->user()->name . '!');
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

        return redirect()->route('siswa.register-kategori')
            ->with('success', 'Akun Siswa berhasil dibuat! Silakan pilih kategori bimbel Anda.');
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
            return redirect()->route('guru.dashboard');
        }

        return redirect()->route('login');
    }
}
