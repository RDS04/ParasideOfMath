<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\OtpVerification;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Dispatch OTP Email (SMTP, Log, or HTTPS API).
     */
    protected function dispatchOtpEmail(string $email, string $otp, string $name, string $role): array
    {
        Log::info("KODE OTP REGISTRASI {$role} ({$email}): {$otp}");

        // 1. Cek apakah ada API Key Brevo di .env (Kirim via HTTPS Port 443 - Bebas Blokir ISP)
        $brevoKey = env('BREVO_API_KEY');
        if (!empty($brevoKey)) {
            try {
                $response = Http::withHeaders([
                    'api-key' => $brevoKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.brevo.com/v3/smtp/email', [
                    'sender' => ['name' => 'Paradise of Math', 'email' => env('MAIL_FROM_ADDRESS', 'syahputrareyhandwi@gmail.com')],
                    'to' => [['email' => $email]],
                    'subject' => 'Kode OTP Verifikasi - Paradise of Math',
                    'htmlContent' => view('emails.otp', compact('otp', 'name', 'role'))->render(),
                ]);

                if ($response->successful()) {
                    return ['success' => true, 'message' => 'Email OTP berhasil dikirim via Brevo HTTPS ke ' . $email];
                } else {
                    Log::error('Brevo API Mail Error: ' . $response->body());
                }
            } catch (\Exception $ex) {
                Log::error('Brevo API Exception: ' . $ex->getMessage());
            }
        }

        // 2. Gunakan Mailer standar Laravel (SMTP atau Log)
        try {
            Mail::to($email)->send(new OtpMail($otp, $name, $role));
            return ['success' => true, 'message' => 'Kode OTP verifikasi telah dikirimkan ke email Anda (' . $email . '). Silakan periksa kotak masuk/spam.'];
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email OTP: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Peringatan SMTP: Email fisik tidak dapat terkirim via SMTP (' . $e->getMessage() . '). Untuk pengujian lokal, Kode OTP Anda adalah: ' . $otp];
        }
    }
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
     * Proses Registrasi Akun Baru (Siswa) - Tahap 1: Send OTP.
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

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpVerification::updateOrCreate(
            ['email' => $request->email],
            [
                'role' => 'siswa',
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]
        );

        $this->dispatchOtpEmail($request->email, $otp, $request->name, 'siswa');

        $request->session()->put('otp_email', $request->email);

        return redirect()->route('verify.otp')
            ->with('success', 'Kode OTP verifikasi telah dibuat untuk (' . $request->email . '). Silakan periksa kotak masuk/spam (atau berkas log jika SMTP lokal diblokir).');
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
     * Proses Registrasi Guru Baru - Tahap 1: Send OTP.
     */
    public function registerGuru(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'phone.regex' => 'Format nomor telepon tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpVerification::updateOrCreate(
            ['email' => $request->email],
            [
                'role' => 'guru',
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]
        );

        $this->dispatchOtpEmail($request->email, $otp, $request->name, 'guru');

        $request->session()->put('otp_email', $request->email);

        return redirect()->route('verify.otp')
            ->with('success', 'Kode OTP verifikasi telah dibuat untuk (' . $request->email . '). Silakan periksa kotak masuk/spam.');
    }

    /**
     * Tampilkan Halaman Verifikasi OTP.
     */
    public function showVerifyOtp(Request $request)
    {
        $email = $request->query('email') ?? session('otp_email');

        if (!$email) {
            return redirect()->route('login')->with('error', 'Sesi verifikasi OTP tidak ditemukan. Silakan lakukan registrasi ulang.');
        }

        return view('auth.verify_otp', compact('email'));
    }

    /**
     * Proses Verifikasi Kode OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
        ], [
            'email.required' => 'Alamat email tidak valid.',
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.size' => 'Kode OTP harus berupa 6 digit angka.',
        ]);

        $otpRecord = OtpVerification::where('email', $request->email)->first();

        if (!$otpRecord) {
            return redirect()->route('login')->with('error', 'Data registrasi tidak ditemukan atau telah dikonfirmasi. Silakan daftar kembali.');
        }

        if ($otpRecord->isExpired()) {
            return back()->withInput()->with('error', 'Kode OTP sudah kadaluarsa (lebih dari 10 menit). Silakan klik "Kirim Ulang OTP".');
        }

        if ($otpRecord->otp !== $request->otp) {
            return back()->withInput()->with('error', 'Kode OTP yang Anda masukkan salah. Silakan periksa kembali email Anda.');
        }

        // OTP Valid - Buat Akun berdasarkan Role
        if ($otpRecord->role === 'siswa') {
            $siswa = Siswa::create([
                'name' => $otpRecord->name,
                'email' => $otpRecord->email,
                'password' => $otpRecord->password, // Password sudah di-hash saat registrasi
            ]);

            $otpRecord->delete();
            $request->session()->forget('otp_email');

            Auth::guard('siswa')->login($siswa);

            return redirect()->route('siswa.biodata')
                ->with('success', 'Verifikasi OTP berhasil! Akun Siswa Anda telah aktif. Silakan lengkapi biodata Anda.');
        } elseif ($otpRecord->role === 'guru') {
            $user = User::create([
                'name' => $otpRecord->name,
                'email' => $otpRecord->email,
                'phone' => $otpRecord->phone,
                'password' => $otpRecord->password,
                'role' => 'guru',
            ]);

            \App\Models\Guru::create([
                'user_id' => $user->id,
                'no_telp' => $otpRecord->phone,
                'status' => 'pending',
            ]);

            $otpRecord->delete();
            $request->session()->forget('otp_email');

            return redirect()->route('guru.pending')
                ->with('success', 'Verifikasi OTP berhasil! Pendaftaran Akun Guru Anda sedang dalam proses peninjauan dan menunggu persetujuan (approval) dari Admin.');
        }

        return redirect()->route('login');
    }

    /**
     * Kirim Ulang Kode OTP.
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $otpRecord = OtpVerification::where('email', $request->email)->first();

        if (!$otpRecord) {
            return redirect()->route('login')->with('error', 'Data registrasi tidak ditemukan. Silakan daftar kembali.');
        }

        $newOtp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $otpRecord->otp = $newOtp;
        $otpRecord->expires_at = Carbon::now()->addMinutes(10);
        $otpRecord->save();

        $result = $this->dispatchOtpEmail($otpRecord->email, $newOtp, $otpRecord->name, $otpRecord->role);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('info', $result['message']);
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
