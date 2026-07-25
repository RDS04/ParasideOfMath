<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk & Daftar · Paradise of Math</title>
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
                linear-gradient(160deg, #2e1065 0%, #3f1d78 45%, #4c1d95 100%);
        }

        /* subtle grid, like graph paper — nods to the subject */
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

        /* floating glyphs */
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

        /* traveling dot along the learning path */
        .path-dot {
            offset-rotate: 0deg;
        }

        /* node pulse */
        .node-ring {
            animation: ringPulse 2.6s ease-out infinite;
            transform-origin: center;
        }
        @keyframes ringPulse {
            0% { transform: scale(0.8); opacity: 0.55; }
            100% { transform: scale(1.9); opacity: 0; }
        }

        @media (prefers-reduced-motion: reduce) {
            .glyph, .node-ring, .path-draw, .path-dot-anim { animation: none !important; }
        }

        /* ── shared form styling ── */
        .card-shadow {
            box-shadow: 0 1px 2px rgba(17, 12, 46, 0.04), 0 20px 45px -18px rgba(76, 29, 149, 0.18);
        }

        .tab-pill {
            background: #f3f1fb;
            border-radius: 14px;
            padding: 4px;
            position: relative;
        }
        .tab-pill button {
            position: relative;
            z-index: 2;
            padding: 10px 0;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 11px;
            transition: color 0.25s ease;
            cursor: pointer;
            background: transparent;
            border: none;
            flex: 1;
            text-align: center;
        }
        .tab-pill button.tab-active { color: #4c1d95; }
        .tab-pill button:not(.tab-active) { color: #8b85a0; }
        .tab-pill button:not(.tab-active):hover { color: #574f73; }

        .tab-slider {
            position: absolute;
            bottom: 4px;
            left: 4px;
            height: calc(100% - 8px);
            width: calc(50% - 4px);
            background: white;
            border-radius: 11px;
            box-shadow: 0 4px 12px rgba(76, 29, 149, 0.12);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            z-index: 1;
            pointer-events: none;
        }
        .tab-slider.slide-right { transform: translateX(calc(100% + 4px)); }

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
        .check-custom:focus { box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.22); }

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

        .link-purple {
            color: #7c3aed;
            font-weight: 600;
            cursor: pointer;
            background: none;
            border: none;
            font-size: inherit;
        }
        .link-purple:hover { color: #5b21b6; }

        .divider-text {
            display: flex;
            align-items: center;
            gap: 16px;
            color: #b3aec4;
            font-size: 0.78rem;
            font-weight: 500;
        }
        .divider-text::before, .divider-text::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #ece9f5;
        }

        .form-panel.hidden-panel { display: none; }
        .form-panel.visible-panel { display: block; animation: fadeUp 0.35s ease both; }
        @keyframes fadeUp {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
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

        <!-- floating glyphs, purely decorative -->
        <span class="glyph text-6xl" style="top:8%; left:62%; animation-delay:0s;">π</span>
        <span class="glyph text-5xl" style="top:70%; left:8%; animation-delay:1.2s;">∑</span>
        <span class="glyph text-4xl" style="top:22%; left:6%; animation-delay:2.1s;">√</span>
        <span class="glyph text-6xl" style="top:78%; left:70%; animation-delay:0.6s;">∞</span>
        <span class="glyph text-4xl" style="top:48%; left:85%; animation-delay:1.8s;">÷</span>
        <span class="glyph text-3xl" style="top:38%; left:38%; animation-delay:2.6s;">x²</span>

        <!-- signature: the "learning path" constellation -->
        <div class="relative z-10 flex-1 flex items-center justify-center py-6">
            <svg viewBox="0 0 460 340" class="w-full max-w-md" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- connecting path -->
                <path id="learningPath"
                      d="M 40 260 C 100 150, 150 280, 210 190 S 320 60, 420 90"
                      stroke="rgba(255,255,255,0.35)"
                      stroke-width="2"
                      stroke-dasharray="6 8"
                      fill="none" />

                <!-- traveling dot -->
                <circle r="6" fill="#fbbf24">
                    <animateMotion dur="6s" repeatCount="indefinite"
                        path="M 40 260 C 100 150, 150 280, 210 190 S 320 60, 420 90" />
                </circle>

                <!-- topic nodes -->
                <g>
                    <circle class="node-ring" cx="40" cy="260" r="7" fill="none" stroke="#c4b5fd" stroke-width="2" />
                    <circle cx="40" cy="260" r="7" fill="#ffffff" />
                    <text x="40" y="288" fill="rgba(255,255,255,0.75)" font-size="13" text-anchor="middle" font-family="Inter, sans-serif">Aljabar</text>
                </g>
                <g>
                    <circle class="node-ring" cx="210" cy="190" r="7" fill="none" stroke="#c4b5fd" stroke-width="2" style="animation-delay:0.6s" />
                    <circle cx="210" cy="190" r="7" fill="#ffffff" />
                    <text x="210" y="218" fill="rgba(255,255,255,0.75)" font-size="13" text-anchor="middle" font-family="Inter, sans-serif">Geometri</text>
                </g>
                <g>
                    <circle class="node-ring" cx="420" cy="90" r="7" fill="none" stroke="#c4b5fd" stroke-width="2" style="animation-delay:1.2s" />
                    <circle cx="420" cy="90" r="7" fill="#ffffff" />
                    <text x="420" y="70" fill="rgba(255,255,255,0.75)" font-size="13" text-anchor="middle" font-family="Inter, sans-serif">Kalkulus</text>
                </g>
            </svg>
        </div>

        <!-- headline -->
        <div class="relative z-10 max-w-sm">
            <h2 class="font-display text-3xl leading-snug font-medium">
                Setiap soal punya jalur penyelesaiannya sendiri.
            </h2>
            <p class="text-white/60 text-sm mt-3 leading-relaxed">
                Bimbingan privat yang menemani langkah demi langkah, dari angka pertama sampai jawaban akhir.
            </p>
        </div>
    </div>

    <!-- ══════════════ RIGHT — FORM PANEL ══════════════ -->
    <div class="flex items-center justify-center px-5 py-10 sm:px-10 relative">

        <div class="w-full max-w-md">

            <!-- mobile-only brand (left panel is hidden below lg) -->
            <div class="lg:hidden text-center mb-8">
                <div class="flex justify-center mb-3">
                    <img src="{{ asset('images/logoPM.webp') }}" alt="Paradise of Math" class="w-16 h-16 object-contain" />
                </div>
                <h1 class="font-display text-2xl font-semibold text-[#2e1065]">
                    Paradise <span class="text-amber-500">of Math</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">SUKSES AKADEMIK · bimbingan privat</p>
            </div>

            <div class="hidden lg:block mb-8">
                <h1 class="font-display text-2xl font-semibold text-[#2e1065]">Selamat datang kembali</h1>
                <p class="text-sm text-gray-500 mt-1">Masuk untuk melanjutkan sesi belajarmu.</p>
            </div>

            @if (session('success'))
                <div class="mb-5 p-4 rounded-xl text-sm font-medium border bg-emerald-50 border-emerald-200 text-emerald-800" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 p-4 rounded-xl text-sm font-medium border bg-rose-50 border-rose-200 text-rose-800" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- tab toggle -->
            <div class="tab-pill flex mb-7">
                <button id="tabLogin" class="tab-active tab-btn">Masuk</button>
                <button id="tabRegister" class="tab-btn">Daftar</button>
                <div class="tab-slider" id="tabSlider"></div>
            </div>

            <!-- FORM LOGIN -->
            <form id="formLogin" class="form-panel visible-panel space-y-5" action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="field-group">
                    <span class="icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <input type="email" id="loginEmail" name="email" value="{{ old('email') }}" placeholder="Alamat email" autocomplete="email" required />
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
                    <input type="password" id="loginPassword" name="password" placeholder="Kata sandi" autocomplete="current-password" required />
                    @error('password')
                        <p class="text-xs text-rose-500 mt-1 pl-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="loginRemember" name="remember" class="check-custom" />
                        <label for="loginRemember" class="text-sm text-gray-600 cursor-pointer select-none">Ingat saya</label>
                    </div>
                    <a href="#" class="text-sm text-purple-600 hover:text-purple-700 font-medium transition-colors">Lupa sandi?</a>
                </div>

                <button type="submit" class="btn-primary" id="loginSubmit">Masuk</button>

                <div class="divider-text">atau</div>

                <p class="text-center text-sm text-gray-500">
                    Belum punya akun?
                    <button type="button" id="switchToRegister" class="link-purple">Daftar sekarang</button>
                </p>
            </form>

            <!-- FORM REGISTER -->
            <form id="formRegister" class="form-panel hidden-panel space-y-4" action="{{ route('register.post') }}" method="POST">
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
                    <input type="email" id="regEmail" name="email" value="{{ old('email') }}" placeholder="Alamat email" autocomplete="email" required />
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
                        Saya setuju dengan
                        <a href="#" class="text-purple-600 hover:text-purple-700 font-medium transition-colors">Syarat &amp; Ketentuan</a>
                        dan
                        <a href="#" class="text-purple-600 hover:text-purple-700 font-medium transition-colors">Kebijakan Privasi</a>
                    </label>
                </div>

                <button type="submit" class="btn-primary" id="registerSubmit">Buat Akun</button>

                <div class="divider-text">atau</div>

                <p class="text-center text-sm text-gray-500">
                    Sudah punya akun?
                    <button type="button" id="switchToLogin" class="link-purple">Masuk di sini</button>
                </p>
            </form>

            <div class="mt-8 pt-5 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400 tracking-wide">
                    &copy; 2026 · Paradise of Math — bimbingan privat No.1
                </p>
            </div>

        </div>
    </div>
</div>

<!-- ─── JAVASCRIPT ─── -->
<script>
    (function() {
        'use strict';

        const tabLogin = document.getElementById('tabLogin');
        const tabRegister = document.getElementById('tabRegister');
        const slider = document.getElementById('tabSlider');
        const formLogin = document.getElementById('formLogin');
        const formRegister = document.getElementById('formRegister');
        const switchToRegister = document.getElementById('switchToRegister');
        const switchToLogin = document.getElementById('switchToLogin');

        function setActiveTab(tab) {
            const isLogin = tab === 'login';

            tabLogin.classList.toggle('tab-active', isLogin);
            tabRegister.classList.toggle('tab-active', !isLogin);
            slider.classList.toggle('slide-right', !isLogin);

            if (isLogin) {
                formLogin.classList.remove('hidden-panel');
                formLogin.classList.add('visible-panel');
                formRegister.classList.remove('visible-panel');
                formRegister.classList.add('hidden-panel');
            } else {
                formRegister.classList.remove('hidden-panel');
                formRegister.classList.add('visible-panel');
                formLogin.classList.remove('visible-panel');
                formLogin.classList.add('hidden-panel');
            }
        }

        tabLogin.addEventListener('click', (e) => { e.preventDefault(); setActiveTab('login'); });
        tabRegister.addEventListener('click', (e) => { e.preventDefault(); setActiveTab('register'); });
        switchToRegister.addEventListener('click', (e) => { e.preventDefault(); setActiveTab('register'); });
        switchToLogin.addEventListener('click', (e) => { e.preventDefault(); setActiveTab('login'); });

        let initialTab = @json(old('name') || session('active_tab') === 'register' ? 'register' : 'login');
        if (window.location.hash === '#daftar' || window.location.hash === '#register') {
            initialTab = 'register';
        }
        setActiveTab(initialTab);
    })();
</script>

</body>
</html>