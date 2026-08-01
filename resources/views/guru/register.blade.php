<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Pengajar (Guru) · Paradise of Math</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .font-display {
            font-family: 'Fraunces', Georgia, serif;
            font-optical-sizing: auto;
        }

        /* ── left panel background ── */
        .panel-left {
            background:
                radial-gradient(ellipse 900px 700px at 15% -10%, rgba(251, 191, 36, 0.16), transparent 60%),
                radial-gradient(ellipse 700px 600px at 100% 110%, rgba(192, 132, 252, 0.20), transparent 55%),
                linear-gradient(160deg, #1e1b4b 0%, #2e1065 45%, #4c1d95 100%);
        }

        /* subtle grid */
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

        .glyph {
            position: absolute;
            font-family: 'Fraunces', serif;
            color: rgba(255, 255, 255, 0.16);
            font-weight: 500;
            user-select: none;
            pointer-events: none;
            animation: floaty 7s ease-in-out infinite;
        }
        @keyframes floaty {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-16px) rotate(3deg); }
        }

        .node-ring {
            animation: ringPulse 2.6s ease-out infinite;
            transform-origin: center;
        }
        @keyframes ringPulse {
            0% { transform: scale(0.8); opacity: 0.55; }
            100% { transform: scale(1.9); opacity: 0; }
        }

        .card-shadow {
            box-shadow: 0 1px 2px rgba(17, 12, 46, 0.04), 0 20px 45px -18px rgba(76, 29, 149, 0.18);
        }

        .field-group { position: relative; }
        .field-group .icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #a8a2bd;
            pointer-events: none;
            display: flex;
            transition: color 0.2s;
        }
        .field-group input {
            width: 100%;
            padding: 13px 16px 13px 46px;
            border-radius: 12px;
            border: 1.5px solid #e9e6f4;
            background: #faf9fd;
            font-size: 0.95rem;
            outline: none;
            color: #241b3d;
            transition: all 0.2s ease;
        }
        .field-group input::placeholder { color: #a8a2bd; }
        .field-group input:focus {
            background: white;
            border-color: #7c3aed;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.12);
        }
        .field-group input:focus + .icon { color: #7c3aed; }

        .check-custom {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid #d8d3e8;
            border-radius: 5px;
            background: white;
            transition: all 0.15s ease;
            cursor: pointer;
            flex-shrink: 0;
            margin-top: 2px;
            position: relative;
        }
        .check-custom:checked { background: #7c3aed; border-color: #7c3aed; }
        .check-custom:checked::after {
            content: "✓";
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 13px;
            font-weight: 700;
            line-height: 1;
        }

        .btn-primary {
            background: linear-gradient(135deg, #7c3aed, #4c1d95);
            border: none;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            color: white;
            width: 100%;
            transition: all 0.2s ease;
            cursor: pointer;
            box-shadow: 0 10px 26px -10px rgba(76, 29, 149, 0.4);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 30px -10px rgba(76, 29, 149, 0.5); }
        .btn-primary:active { transform: scale(0.98); }

        .link-purple {
            color: #7c3aed;
            font-weight: 600;
            text-decoration: none;
        }
        .link-purple:hover { color: #5b21b6; }
    </style>
</head>
<body class="bg-[#fdfcfb]">

<div class="min-h-screen lg:grid lg:grid-cols-2">

    <!-- ══════════════ LEFT — ILLUSTRATION PANEL ══════════════ -->
    <div class="panel-left hidden lg:flex relative overflow-hidden flex-col justify-between px-14 py-12 text-white">
        <!-- brand -->
        <div class="relative z-10 flex items-center gap-3">
            <img src="{{ asset('images/logoPM.webp') }}" alt="Paradise of Math" class="w-10 h-10 object-contain" />
            <span class="font-display text-lg font-semibold tracking-tight">Paradise <span class="text-amber-300">of Math</span></span>
        </div>

        <!-- floating glyphs -->
        <span class="glyph text-6xl" style="top:8%; left:62%; animation-delay:0s;">π</span>
        <span class="glyph text-5xl" style="top:70%; left:8%; animation-delay:1.2s;">∑</span>
        <span class="glyph text-4xl" style="top:22%; left:6%; animation-delay:2.1s;">√</span>
        <span class="glyph text-6xl" style="top:78%; left:70%; animation-delay:0.6s;">∞</span>

        <!-- learning path / constellation -->
        <div class="relative z-10 flex-1 flex items-center justify-center py-6">
            <svg viewBox="0 0 460 340" class="w-full max-w-md" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M 40 260 C 100 150, 150 280, 210 190 S 320 60, 420 90"
                      stroke="rgba(255,255,255,0.35)"
                      stroke-width="2"
                      stroke-dasharray="6 8"
                      fill="none" />
                <circle r="6" fill="#fbbf24">
                    <animateMotion dur="6s" repeatCount="indefinite"
                        path="M 40 260 C 100 150, 150 280, 210 190 S 320 60, 420 90" />
                </circle>
                <g>
                    <circle class="node-ring" cx="40" cy="260" r="7" fill="none" stroke="#c4b5fd" stroke-width="2" />
                    <circle cx="40" cy="260" r="7" fill="#ffffff" />
                    <text x="40" y="288" fill="rgba(255,255,255,0.75)" font-size="13" text-anchor="middle" font-family="Inter, sans-serif">Silabus</text>
                </g>
                <g>
                    <circle class="node-ring" cx="210" cy="190" r="7" fill="none" stroke="#c4b5fd" stroke-width="2" style="animation-delay:0.6s" />
                    <circle cx="210" cy="190" r="7" fill="#ffffff" />
                    <text x="210" y="218" fill="rgba(255,255,255,0.75)" font-size="13" text-anchor="middle" font-family="Inter, sans-serif">Kurikulum</text>
                </g>
                <g>
                    <circle class="node-ring" cx="420" cy="90" r="7" fill="none" stroke="#c4b5fd" stroke-width="2" style="animation-delay:1.2s" />
                    <circle cx="420" cy="90" r="7" fill="#ffffff" />
                    <text x="420" y="70" fill="rgba(255,255,255,0.75)" font-size="13" text-anchor="middle" font-family="Inter, sans-serif">Pengajaran</text>
                </g>
            </svg>
        </div>

        <div class="relative z-10 max-w-sm">
            <h2 class="font-display text-3xl leading-snug font-medium">
                Panel Kontrol Pengajar Utama.
            </h2>
            <p class="text-white/60 text-sm mt-3 leading-relaxed">
                Kelola jadwal bimbingan, buat materi ajar baru, dan pantau kemajuan belajar siswa Anda secara terstruktur dari satu dashboard terpusat.
            </p>
        </div>
    </div>

    <!-- ══════════════ RIGHT — FORM PANEL ══════════════ -->
    <div class="flex items-center justify-center px-5 py-10 sm:px-10 relative">
        <div class="w-full max-w-md">
            <!-- mobile-only brand -->
            <div class="lg:hidden text-center mb-8">
                <div class="flex justify-center mb-3">
                    <img src="{{ asset('images/logoPM.webp') }}" alt="Paradise of Math" class="w-16 h-16 object-contain" />
                </div>
                <h1 class="font-display text-2xl font-semibold text-[#2e1065]">
                    Paradise <span class="text-amber-500">of Math</span>
                </h1>
                <p class="text-xs text-gray-400 tracking-wide uppercase mt-1">Registrasi Pengajar (Guru)</p>
            </div>

            <div class="hidden lg:block mb-8">
                <h1 class="font-display text-2xl font-semibold text-[#2e1065]">Daftar Akun Guru</h1>
                <p class="text-sm text-gray-500 mt-1">Buat akun untuk masuk ke dashboard pengajar LBB.</p>
            </div>

            <!-- FORM REGISTER GURU -->
            <form id="formRegisterGuru" class="space-y-4" action="{{ route('guru.register.post') }}" method="POST">
                @csrf

                <div class="field-group">
                    <span class="icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    <input type="text" id="regName" name="name" value="{{ old('name') }}" placeholder="Nama lengkap" autocomplete="name" required />
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1 pl-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field-group">
                    <span class="icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <input type="email" id="regEmail" name="email" value="{{ old('email') }}" placeholder="Alamat email guru" autocomplete="email" required />
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1 pl-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field-group">
                    <span class="icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input type="password" id="regPassword" name="password" placeholder="Kata sandi (min. 8 karakter)" autocomplete="new-password" required />
                    @error('password')
                        <p class="text-xs text-rose-500 mt-1 pl-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field-group">
                    <span class="icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </span>
                    <input type="password" id="regPasswordConfirm" name="password_confirmation" placeholder="Ulangi kata sandi" autocomplete="new-password" required />
                </div>

                <div class="flex items-start gap-2.5 pt-0.5">
                    <input type="checkbox" id="regTerms" name="terms" class="check-custom" required />
                    <label for="regTerms" class="text-sm text-gray-600 leading-snug select-none cursor-pointer">
                        Saya menjamin data pendaftaran di atas adalah valid dan merupakan profil pengajar resmi.
                    </label>
                </div>

                <button type="submit" class="btn-primary">Daftar Guru</button>

                <p class="text-center text-sm text-gray-500 pt-2">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="link-purple">Masuk di sini</a>
                </p>
            </form>

            <div class="mt-8 pt-5 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400 tracking-wide">
                    &copy; 2026 · Paradise of Math — Panel Pengajar
                </p>
            </div>
        </div>
    </div>

</div>

</body>
</html>
