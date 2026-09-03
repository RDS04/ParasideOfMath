<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verifikasi Kode OTP · Paradise of Math</title>
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

        .panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.045) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.045) 1px, transparent 1px);
            background-size: 42px 42px;
            mask-image: radial-gradient(ellipse 80% 80% at 50% 40%, black 40%, transparent 85%);
            pointer-events: none;
        }

        .otp-input-box {
            width: 48px;
            height: 58px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            border-radius: 12px;
            border: 2px solid #e9e6f4;
            background: #faf9fd;
            color: #2e1065;
            outline: none;
            transition: all 0.2s ease;
        }

        @media (min-width: 400px) {
            .otp-input-box {
                width: 54px;
                height: 64px;
                font-size: 1.75rem;
            }
        }

        .otp-input-box:focus {
            background: #ffffff;
            border-color: #7c3aed;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.15);
        }

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
<body class="bg-[#fcfaff] text-gray-800 antialiased selection:bg-purple-500 selection:text-white min-h-screen flex flex-col justify-between">

    <div class="grid grid-cols-1 lg:grid-cols-12 min-h-screen">
        <!-- LEFT PANEL -->
        <div class="panel-left hidden lg:flex lg:col-span-5 xl:col-span-6 relative p-12 flex-col justify-between text-white overflow-hidden">
            <div class="relative z-10 flex items-center gap-3">
                <img src="{{ asset('images/logoPM.webp') }}" alt="Paradise of Math" class="w-10 h-10 object-contain drop-shadow-md" />
                <span class="font-display text-xl font-semibold tracking-tight text-white">
                    Paradise <span class="text-amber-400">of Math</span>
                </span>
            </div>

            <div class="relative z-10 max-w-sm">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-md text-amber-300 text-xs font-semibold uppercase tracking-wider mb-4 border border-white/10">
                    <i class="fas fa-shield-alt"></i> Keamanan Akun
                </div>
                <h2 class="font-display text-3xl leading-snug font-medium">
                    Verifikasi Email Anda.
                </h2>
                <p class="text-white/70 text-sm mt-3 leading-relaxed">
                    Kami telah mengirimkan 6 digit kode OTP ke alamat email Anda. Masukkan kode tersebut untuk menyelesaikan registrasi.
                </p>
            </div>

            <div class="relative z-10 text-xs text-white/50">
                &copy; {{ date('Y') }} Paradise of Math. All rights reserved.
            </div>
        </div>

        <!-- RIGHT PANEL (OTP FORM) -->
        <div class="lg:col-span-7 xl:col-span-6 flex items-center justify-center p-6 sm:p-12 relative">
            <div class="w-full max-w-md">

                <!-- Header Logo Mobile -->
                <div class="lg:hidden text-center mb-8">
                    <div class="flex justify-center mb-3">
                        <img src="{{ asset('images/logoPM.webp') }}" alt="Paradise of Math" class="w-16 h-16 object-contain" />
                    </div>
                    <h1 class="font-display text-2xl font-semibold text-[#2e1065]">
                        Paradise <span class="text-amber-500">of Math</span>
                    </h1>
                </div>

                <div class="text-center lg:text-left mb-8">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-purple-100 text-purple-700 mb-4 shadow-sm">
                        <i class="fas fa-envelope-open-text text-2xl"></i>
                    </div>
                    <h1 class="font-display text-2xl sm:text-3xl font-semibold text-[#2e1065]">
                        Masukkan Kode OTP
                    </h1>
                    <p class="text-sm text-gray-600 mt-2">
                        Kode 6 digit telah dikirim ke: <br>
                        <strong class="text-purple-900 font-semibold underline">{{ $email }}</strong>
                    </p>
                </div>

                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl text-sm font-medium border bg-emerald-50 border-emerald-200 text-emerald-800 flex items-center gap-3">
                        <i class="fas fa-check-circle text-lg text-emerald-600 flex-shrink-0"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 rounded-xl text-sm font-medium border bg-rose-50 border-rose-200 text-rose-800 flex items-center gap-3">
                        <i class="fas fa-exclamation-circle text-lg text-rose-600 flex-shrink-0"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl text-sm font-medium border bg-rose-50 border-rose-200 text-rose-800">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- OTP Form -->
                <form action="{{ route('verify.otp.post') }}" method="POST" id="otpForm" class="space-y-6">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="otp" id="fullOtp" value="">

                    <!-- 6 Digit Box Container -->
                    <div class="flex justify-between items-center gap-2 sm:gap-3" id="otpBoxesContainer">
                        <input type="text" maxlength="1" class="otp-input-box" inputmode="numeric" pattern="[0-9]*" autofocus autocomplete="off" />
                        <input type="text" maxlength="1" class="otp-input-box" inputmode="numeric" pattern="[0-9]*" autocomplete="off" />
                        <input type="text" maxlength="1" class="otp-input-box" inputmode="numeric" pattern="[0-9]*" autocomplete="off" />
                        <input type="text" maxlength="1" class="otp-input-box" inputmode="numeric" pattern="[0-9]*" autocomplete="off" />
                        <input type="text" maxlength="1" class="otp-input-box" inputmode="numeric" pattern="[0-9]*" autocomplete="off" />
                        <input type="text" maxlength="1" class="otp-input-box" inputmode="numeric" pattern="[0-9]*" autocomplete="off" />
                    </div>

                    <button type="submit" class="btn-primary flex items-center justify-center gap-2">
                        <span>Verifikasi & Lanjutkan</span>
                        <i class="fas fa-arrow-right text-sm"></i>
                    </button>
                </form>

                <!-- Resend Form -->
                <div class="mt-8 pt-6 border-t border-purple-100 text-center">
                    <p class="text-sm text-gray-600">
                        Tidak menerima kode OTP?
                    </p>
                    <form action="{{ route('resend.otp') }}" method="POST" class="mt-2" id="resendForm">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <button type="submit" id="btnResend" class="btn-resend text-sm">
                            <i class="fas fa-redo-alt mr-1"></i> Kirim Ulang OTP
                        </button>
                        <span id="cooldownTimer" class="text-xs text-gray-500 block mt-1 hidden"></span>
                    </form>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-xs text-gray-500 hover:text-purple-700 transition-colors">
                        <i class="fas fa-chevron-left mr-1"></i> Kembali ke Halaman Utama / Login
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const boxes = Array.from(document.querySelectorAll('.otp-input-box'));
            const fullOtpInput = document.getElementById('fullOtp');
            const otpForm = document.getElementById('otpForm');

            // Handle OTP boxes auto-focus & paste
            boxes.forEach((box, index) => {
                box.addEventListener('input', (e) => {
                    const val = e.target.value;

                    // Allow only digits
                    if (!/^\d*$/.test(val)) {
                        box.value = '';
                        return;
                    }

                    if (val.length === 1 && index < boxes.length - 1) {
                        boxes[index + 1].focus();
                    }

                    updateFullOtp();
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
                        boxes[5].focus();
                        updateFullOtp();
                    }
                });
            });

            function updateFullOtp() {
                fullOtpInput.value = boxes.map(b => b.value).join('');
            }

            otpForm.addEventListener('submit', function(e) {
                updateFullOtp();
                if (fullOtpInput.value.length !== 6) {
                    e.preventDefault();
                    alert('Silakan masukkan 6 digit kode OTP secara lengkap.');
                }
            });

            // Resend OTP Cooldown Timer (60s)
            const btnResend = document.getElementById('btnResend');
            const cooldownTimer = document.getElementById('cooldownTimer');
            let countdown = 60;
            let timer = null;

            function startCooldown() {
                btnResend.disabled = true;
                cooldownTimer.classList.remove('hidden');

                timer = setInterval(() => {
                    countdown--;
                    cooldownTimer.textContent = `Tunggu ${countdown} detik untuk menginput ulang.`;

                    if (countdown <= 0) {
                        clearInterval(timer);
                        btnResend.disabled = false;
                        cooldownTimer.classList.add('hidden');
                        countdown = 60;
                    }
                }, 1000);
            }

            document.getElementById('resendForm').addEventListener('submit', function() {
                startCooldown();
            });
        });
    </script>

</body>
</html>
