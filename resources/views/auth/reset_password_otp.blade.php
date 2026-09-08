<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Kata Sandi via OTP · Paradise of Math</title>

    <link rel="icon" type="image/webp" href="{{ asset('images/logoPM.webp') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .font-display { font-family: 'Fraunces', Georgia, serif; }

        .panel-left {
            background:
                radial-gradient(ellipse 900px 700px at 15% -10%, rgba(251, 191, 36, 0.16), transparent 60%),
                radial-gradient(ellipse 700px 600px at 100% 110%, rgba(192, 132, 252, 0.20), transparent 55%),
                linear-gradient(160deg, #1e1b4b 0%, #2e1065 45%, #4c1d95 100%);
        }

        .field-group {
            position: relative;
        }
        .field-group input {
            width: 100%;
            padding: 14px 48px 14px 46px;
            border-radius: 12px;
            border: 1.5px solid #e2ded8;
            background: #faf9fb;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.2s ease;
        }
        .field-group .icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a8a2bd;
            transition: color 0.2s;
        }
        .field-group input:focus {
            background: white;
            border-color: #7c3aed;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.12);
        }

        .otp-input-box {
            width: 44px;
            height: 52px;
            text-align: center;
            font-size: 1.35rem;
            font-weight: 700;
            border-radius: 12px;
            border: 2px solid #e9e6f4;
            background: #faf9fd;
            color: #2e1065;
            outline: none;
            transition: all 0.2s ease;
        }
        @media (min-width: 400px) {
            .otp-input-box { width: 50px; height: 58px; font-size: 1.5rem; }
        }
        .otp-input-box:focus {
            background: #ffffff;
            border-color: #7c3aed;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.15);
        }

        .toggle-password-btn {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0;
            color: #a8a2bd;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
            z-index: 5;
        }
        .toggle-password-btn:hover { color: #7c3aed; }

        .btn-primary {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border: none;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            color: #40206b;
            width: 100%;
            transition: all 0.2s ease;
            cursor: pointer;
            box-shadow: 0 10px 26px -10px rgba(245, 158, 11, 0.55);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 30px -10px rgba(245, 158, 11, 0.6); }
        .btn-primary:active { transform: scale(0.98); }

        .btn-resend {
            background: none;
            border: none;
            color: #7c3aed;
            font-weight: 600;
            cursor: pointer;
            transition: color 0.2s;
        }
        .btn-resend:hover:not(:disabled) { color: #5b21b6; text-decoration: underline; }
        .btn-resend:disabled { color: #a8a2bd; cursor: not-allowed; text-decoration: none; }
    </style>
</head>
<body class="bg-[#fcfaff] text-gray-800 antialiased min-h-screen flex flex-col justify-between">

    <div class="grid grid-cols-1 lg:grid-cols-12 min-h-screen">
        <!-- LEFT PANEL -->
        <div class="panel-left hidden lg:flex lg:col-span-5 xl:col-span-6 relative p-12 flex-col justify-between text-white overflow-hidden">
            <div class="relative z-10 flex items-center gap-3">
                <img src="{{ asset('images/logoPM.webp') }}" alt="Paradise of Math" class="w-10 h-10 object-contain drop-shadow-md" />
                <span class="font-display text-xl font-semibold tracking-tight">Paradise <span class="text-amber-300">of Math</span></span>
            </div>

            <div class="relative z-10 my-auto max-w-lg space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-semibold tracking-wide text-amber-300 uppercase">
                    <i class="fa-solid fa-shield-halved"></i> Keamanan Akun
                </div>
                <h1 class="font-display text-4xl leading-tight font-bold">Verifikasi & Buat Kata Sandi Baru</h1>
                <p class="text-indigo-200 text-base leading-relaxed">
                    Masukkan 6-digit kode OTP yang dikirimkan ke email <strong class="text-white underline decoration-amber-400">{{ $email }}</strong> dan tentukan kata sandi baru Anda.
                </p>
            </div>

            <div class="relative z-10 text-xs text-indigo-300/80">
                &copy; {{ date('Y') }} Paradise of Math. Hak Cipta Dilindungi.
            </div>
        </div>

        <!-- RIGHT PANEL / FORM -->
        <div class="lg:col-span-7 xl:col-span-6 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md space-y-6 bg-white p-8 sm:p-10 rounded-3xl border border-purple-50 shadow-xl shadow-purple-900/5">

                <div>
                    <a href="{{ route('password.request') }}" class="inline-flex items-center text-xs font-semibold text-purple-600 hover:text-purple-800 transition-colors mb-4">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Ganti Email
                    </a>
                    <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl font-bold mb-3 shadow-sm">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <h2 class="text-2xl font-bold font-display text-gray-900 tracking-tight">Reset Kata Sandi</h2>
                    <p class="text-xs text-gray-500 mt-1">Kode OTP telah dikirimkan ke <strong>{{ $email }}</strong></p>
                </div>

                @if(session('error'))
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 text-sm flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm flex items-start gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST" id="resetOtpForm" class="space-y-5">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="otp" id="fullOtpInput">

                    <!-- OTP INPUT BOXES -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-700 tracking-wider uppercase">Masukkan 6 Digit OTP</label>
                        <div class="flex justify-between gap-1 sm:gap-2" id="otpBoxContainer">
                            <input type="text" maxlength="1" class="otp-input-box" inputmode="numeric" autocomplete="one-time-code" autofocus />
                            <input type="text" maxlength="1" class="otp-input-box" inputmode="numeric" />
                            <input type="text" maxlength="1" class="otp-input-box" inputmode="numeric" />
                            <input type="text" maxlength="1" class="otp-input-box" inputmode="numeric" />
                            <input type="text" maxlength="1" class="otp-input-box" inputmode="numeric" />
                            <input type="text" maxlength="1" class="otp-input-box" inputmode="numeric" />
                        </div>
                        @error('otp')
                            <p class="text-xs text-rose-500 font-medium pl-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NEW PASSWORD -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">Kata Sandi Baru</label>
                        <div class="field-group">
                            <span class="icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required />
                            <button type="button" class="toggle-password-btn" data-target="password" tabindex="-1">
                                <i class="fa-solid fa-eye text-sm" id="eye-icon-password"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-rose-500 font-medium pl-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CONFIRM PASSWORD -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Konfirmasi Kata Sandi Baru</label>
                        <div class="field-group">
                            <span class="icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi baru" required />
                            <button type="button" class="toggle-password-btn" data-target="password_confirmation" tabindex="-1">
                                <i class="fa-solid fa-eye text-sm" id="eye-icon-password_confirmation"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary flex items-center justify-center gap-2 mt-4">
                        <i class="fa-solid fa-check"></i>
                        <span>Simpan Kata Sandi Baru</span>
                    </button>
                </form>

                <!-- RESEND OTP FORM -->
                <div class="text-center pt-2 border-t border-purple-50">
                    <form action="{{ route('password.email') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <p class="text-xs text-gray-500">
                            Tidak menerima kode?
                            <button type="submit" class="btn-resend">Kirim Ulang OTP</button>
                        </p>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // OTP Inputs auto-advance and paste logic
            const boxes = Array.from(document.querySelectorAll('.otp-input-box'));
            const fullOtpInput = document.getElementById('fullOtpInput');
            const resetOtpForm = document.getElementById('resetOtpForm');

            function syncOtp() {
                fullOtpInput.value = boxes.map(b => b.value).join('');
            }

            boxes.forEach((box, index) => {
                box.addEventListener('input', (e) => {
                    const val = e.target.value;
                    if (val.length === 1) {
                        if (index < boxes.length - 1) {
                            boxes[index + 1].focus();
                        }
                    }
                    syncOtp();
                });

                box.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !box.value && index > 0) {
                        boxes[index - 1].focus();
                    }
                });

                box.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();
                    if (/^\d{6}$/.test(pasteData)) {
                        pasteData.split('').forEach((char, i) => {
                            if (boxes[i]) boxes[i].value = char;
                        });
                        boxes[boxes.length - 1].focus();
                        syncOtp();
                    }
                });
            });

            resetOtpForm.addEventListener('submit', (e) => {
                syncOtp();
                if (fullOtpInput.value.length !== 6) {
                    e.preventDefault();
                    alert('Silakan lengkapi 6 digit kode OTP.');
                }
            });

            // Toggle Password Visibility
            document.querySelectorAll('.toggle-password-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
        });
    </script>
</body>
</html>
