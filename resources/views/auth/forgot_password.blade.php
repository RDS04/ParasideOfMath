<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lupa Kata Sandi · Paradise of Math</title>

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
            padding: 14px 16px 14px 46px;
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
                    <i class="fa-solid fa-key"></i> Pulihkan Kata Sandi
                </div>
                <h1 class="font-display text-4xl leading-tight font-bold">Lupa kata sandi akun Anda?</h1>
                <p class="text-indigo-200 text-base leading-relaxed">
                    Jangan khawatir. Masukkan email terdaftar Anda dan kami akan mengirimkan Kode OTP 6-digit untuk mereset kata sandi Anda.
                </p>
            </div>

            <div class="relative z-10 text-xs text-indigo-300/80">
                &copy; {{ date('Y') }} Paradise of Math. Hak Cipta Dilindungi.
            </div>
        </div>

        <!-- RIGHT PANEL / FORM -->
        <div class="lg:col-span-7 xl:col-span-6 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md space-y-8 bg-white p-8 sm:p-10 rounded-3xl border border-purple-50 shadow-xl shadow-purple-900/5">

                <div>
                    <a href="{{ route('login') }}" class="inline-flex items-center text-xs font-semibold text-purple-600 hover:text-purple-800 transition-colors mb-6">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Halaman Log In
                    </a>
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl font-bold mb-4 shadow-sm">
                        <i class="fa-solid fa-lock-open"></i>
                    </div>
                    <h2 class="text-2xl font-bold font-display text-gray-900 tracking-tight">Lupa Kata Sandi</h2>
                    <p class="text-sm text-gray-500 mt-1">Masukkan email terdaftar untuk menerima Kode OTP verifikasi.</p>
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

                <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">Alamat Email Terdaftar</label>
                        <div class="field-group">
                            <span class="icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input type="email" id="email" name="email" value="{{ old('email', session('reset_otp_email')) }}" placeholder="nama@email.com" required autofocus />
                        </div>
                        @error('email')
                            <p class="text-xs text-rose-500 font-medium pl-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary flex items-center justify-center gap-2">
                        <span>Kirim Kode OTP</span>
                        <i class="fa-solid fa-paper-plane text-sm"></i>
                    </button>
                </form>

            </div>
        </div>
    </div>

</body>
</html>
