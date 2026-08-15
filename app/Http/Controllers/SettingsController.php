<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * Tampilkan Halaman Pengaturan (Ubah Email & Password).
     * Berlaku untuk ketiga role: Siswa, Guru, Admin.
     */
    public function index()
    {
        $guard = $this->activeGuard();
        if (!$guard) {
            return redirect()->route('login');
        }

        $currentUser = Auth::guard($guard)->user();

        return view('pengaturan.index', compact('currentUser', 'guard'));
    }

    /**
     * Update Email Akun.
     */
    public function updateEmail(Request $request)
    {
        $guard = $this->activeGuard();
        if (!$guard) {
            return redirect()->route('login');
        }

        $user  = Auth::guard($guard)->user();
        $table = $guard === 'siswa' ? 'siswa' : 'users';

        $request->validate([
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique($table, 'email')->ignore($user->id),
            ],
            'current_password' => ['required', 'current_password:' . $guard],
        ], [
            'email.required' => 'Alamat email baru wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'current_password.required' => 'Kata sandi saat ini wajib diisi untuk konfirmasi.',
            'current_password.current_password' => 'Kata sandi yang Anda masukkan salah.',
        ]);

        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Alamat email berhasil diperbarui menjadi ' . $user->email . '.');
    }

    /**
     * Update Password Akun.
     */
    public function updatePassword(Request $request)
    {
        $guard = $this->activeGuard();
        if (!$guard) {
            return redirect()->route('login');
        }

        $user = Auth::guard($guard)->user();

        $request->validate([
            'current_password' => ['required', 'current_password:' . $guard],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini yang Anda masukkan salah.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Kata sandi berhasil diperbarui. Gunakan kata sandi baru saat login berikutnya.');
    }

    /**
     * Deteksi guard mana yang sedang aktif login.
     */
    private function activeGuard(): ?string
    {
        if (Auth::guard('siswa')->check()) {
            return 'siswa';
        }
        if (Auth::guard('web')->check()) {
            return 'web';
        }
        return null;
    }
}