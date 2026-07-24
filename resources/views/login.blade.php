<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login & Register · Paradise of Math</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── additional polish beyond Tailwind ── */

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* subtle background glow */
        .glow-orbe {
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            pointer-events: none;
            z-index: 0;
        }

        .glow-orbe--1 {
            width: 400px;
            height: 400px;
            top: -120px;
            right: -80px;
            background: rgba(251, 191, 36, 0.20);
        }

        .glow-orbe--2 {
            width: 350px;
            height: 350px;
            bottom: -100px;
            left: -100px;
            background: rgba(192, 132, 252, 0.18);
        }

        /* card glass */
        .card-glass {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            box-shadow: 0 30px 60px -20px rgba(0, 0, 0, 0.5), 0 8px 24px -8px rgba(0, 0, 0, 0.2);
        }

        /* tab pill – animated indicator */
        .tab-pill {
            background: rgba(243, 244, 246, 0.70);
            backdrop-filter: blur(4px);
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
            transition: all 0.25s ease;
            cursor: pointer;
            background: transparent;
            border: none;
            flex: 1;
            text-align: center;
        }

        .tab-pill button.tab-active {
            background: white;
            color: #5b21b6;
            box-shadow: 0 4px 12px rgba(91, 33, 182, 0.12);
        }

        .tab-pill button:not(.tab-active) {
            color: #6b7280;
        }

        .tab-pill button:not(.tab-active):hover {
            color: #374151;
        }

        .tab-slider {
            position: absolute;
            bottom: 4px;
            left: 4px;
            height: calc(100% - 8px);
            width: calc(50% - 4px);
            background: white;
            border-radius: 11px;
            box-shadow: 0 4px 12px rgba(91, 33, 182, 0.10);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            z-index: 1;
            pointer-events: none;
        }

        .tab-slider.slide-right {
            transform: translateX(calc(100% + 4px));
        }

        /* form fields */
        .field-group {
            position: relative;
        }

        .field-group .icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
        }

        .field-group input {
            width: 100%;
            padding: 14px 16px 14px 46px;
            border-radius: 12px;
            border: 1.5px solid #e5e7eb;
            background: rgba(249, 250, 251, 0.70);
            font-size: 0.95rem;
            transition: all 0.2s ease;
            outline: none;
            color: #1f2937;
        }

        .field-group input::placeholder {
            color: #9ca3af;
        }

        .field-group input:focus {
            background: white;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15);
        }

        .field-group input:focus+.icon,
        .field-group input:focus~.icon {
            color: #7c3aed;
        }

        /* custom checkbox */
        .check-custom {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid #d1d5db;
            border-radius: 5px;
            background: white;
            transition: all 0.15s ease;
            cursor: pointer;
            flex-shrink: 0;
            margin-top: 2px;
            position: relative;
        }

        .check-custom:checked {
            background: #7c3aed;
            border-color: #7c3aed;
        }

        .check-custom:checked::after {
            content: "✓";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 13px;
            font-weight: 700;
            line-height: 1;
        }

        .check-custom:focus {
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.25);
        }

        /* btn primary */
        .btn-primary {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border: none;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            color: #4c1d95;
            width: 100%;
            transition: all 0.2s ease;
            cursor: pointer;
            box-shadow: 0 8px 24px -6px rgba(251, 191, 36, 0.45);
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: translateY(-2px) scale(1.01);
            box-shadow: 0 14px 32px -8px rgba(251, 191, 36, 0.55);
        }

        .btn-primary:active {
            transform: scale(0.97);
            box-shadow: 0 4px 12px -4px rgba(251, 191, 36, 0.4);
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent 60%);
            pointer-events: none;
        }

        /* link subtle */
        .link-purple {
            color: #7c3aed;
            font-weight: 600;
            transition: color 0.15s;
            cursor: pointer;
            background: none;
            border: none;
            font-size: inherit;
        }

        .link-purple:hover {
            color: #5b21b6;
        }

        /* divider */
        .divider-text {
            display: flex;
            align-items: center;
            gap: 16px;
            color: #9ca3af;
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .divider-text::before,
        .divider-text::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        /* responsive tweaks */
        @media (max-width: 480px) {
            .card-glass {
                padding: 24px 20px !important;
            }

            .field-group input {
                padding: 12px 14px 12px 42px;
                font-size: 0.9rem;
            }

            .btn-primary {
                padding: 12px 20px;
                font-size: 0.95rem;
            }
        }

        /* fade in animation */
        .fade-enter {
            animation: fadeUp 0.35s ease both;
        }

        @keyframes fadeUp {
            0% {
                opacity: 0;
                transform: translateY(12px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-panel {
            transition: opacity 0.25s ease, transform 0.3s ease;
        }

        .form-panel.hidden-panel {
            display: none;
        }

        .form-panel.visible-panel {
            display: block;
            animation: fadeUp 0.35s ease both;
        }
    </style>
</head>
<body>

    <!-- ─── decorative glows ─── -->
    <div class="glow-orbe glow-orbe--1"></div>
    <div class="glow-orbe glow-orbe--2"></div>

    <!-- ─── main wrapper ─── -->
    <div class="min-h-screen flex items-center justify-center px-4 py-8 relative z-10"
    style="background: linear-gradient(145deg, #4c1d95 0%, #2e1065 100%);">

    <!-- ─── card ─── -->
    <div class="w-full max-w-md card-glass rounded-3xl p-8 md:p-10 transition-all duration-300 relative">

        <!-- branding -->
        <div class="text-center mb-7">
            <div class="flex justify-center mb-3">
                <img src="{{ asset('images/logoPM.webp') }}" alt="Paradise of Math"
                class="w-18 h-18 object-contain drop-shadow-md" />
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-gray-800">
                Paradise <span class="text-amber-500">of Math</span>
            </h1>
            <p class="text-sm text-gray-500 mt-0.5 font-medium tracking-wide">
                SUKSES AKADEMIK · bimbingan privat
            </p>
        </div>

        <!-- ─── tab toggle ─── -->
        <div class="tab-pill flex mb-8">
            <button id="tabLogin" class="tab-active tab-btn">Masuk</button>
            <button id="tabRegister" class="tab-btn">Daftar</button>
            <div class="tab-slider" id="tabSlider"></div>
        </div>

        <!-- ─── FORM LOGIN ─── -->
        <div id="formLogin" class="form-panel visible-panel space-y-5">

            <div class="field-group">
                <span class="icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </span>
                <input type="email" id="loginEmail" placeholder="Alamat email" autocomplete="email" />
            </div>

            <div class="field-group">
                <span class="icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </span>
                <input type="password" id="loginPassword" placeholder="Kata sandi" autocomplete="current-password" />
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="loginRemember" class="check-custom" />
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
        </div>

        <!-- ─── FORM REGISTER ─── -->
        <div id="formRegister" class="form-panel hidden-panel space-y-4">

            <div class="field-group">
                <span class="icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </span>
                <input type="text" id="regName" placeholder="Nama lengkap" autocomplete="name" />
            </div>

            <div class="field-group">
                <span class="icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </span>
                <input type="email" id="regEmail" placeholder="Alamat email" autocomplete="email" />
            </div>

            <div class="field-group">
                <span class="icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </span>
                <input type="password" id="regPassword" placeholder="Kata sandi (min. 8 karakter)" autocomplete="new-password" />
            </div>

            <div class="field-group">
                <span class="icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </span>
                <input type="password" id="regPasswordConfirm" placeholder="Ulangi kata sandi" autocomplete="new-password" />
            </div>

            <div class="flex items-start gap-2.5 pt-0.5">
                <input type="checkbox" id="regTerms" class="check-custom" />
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
        </div>

        <!-- ─── footer ─── -->
        <div class="mt-8 pt-5 border-t border-gray-200/60 text-center">
            <p class="text-xs text-gray-400 tracking-wide">
                &copy; 2026 · Paradise of Math — bimbingan privat No.1
            </p>
        </div>

    </div>
</div>

<!-- ─── JAVASCRIPT ─── -->
<script>
    (function() {
        'use strict';

        // DOM refs
        const tabLogin = document.getElementById('tabLogin');
        const tabRegister = document.getElementById('tabRegister');
        const slider = document.getElementById('tabSlider');
        const formLogin = document.getElementById('formLogin');
        const formRegister = document.getElementById('formRegister');
        const switchToRegister = document.getElementById('switchToRegister');
        const switchToLogin = document.getElementById('switchToLogin');

        // ── helpers ──
        function setActiveTab(tab) {
            const isLogin = tab === 'login';

            // button states
            tabLogin.classList.toggle('tab-active', isLogin);
            tabRegister.classList.toggle('tab-active', !isLogin);

            // slider
            slider.classList.toggle('slide-right', !isLogin);

            // forms
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

        // ── events ──
        tabLogin.addEventListener('click', (e) => { e.preventDefault();
            setActiveTab('login'); });
        tabRegister.addEventListener('click', (e) => { e.preventDefault();
            setActiveTab('register'); });
        switchToRegister.addEventListener('click', (e) => { e.preventDefault();
            setActiveTab('register'); });
        switchToLogin.addEventListener('click', (e) => { e.preventDefault();
            setActiveTab('login'); });

        // ── init ──
        setActiveTab('login');

        // ── submit sim ──
        document.getElementById('loginSubmit')?.addEventListener('click', (e) => {
            e.preventDefault();
            const email = document.getElementById('loginEmail').value.trim();
            const pass = document.getElementById('loginPassword').value;
            console.log('[LOGIN]', { email, password: pass });
            alert('🔐 Login simulasi — cek console untuk detail.');
        });

        document.getElementById('registerSubmit')?.addEventListener('click', (e) => {
            e.preventDefault();
            const name = document.getElementById('regName').value.trim();
            const email = document.getElementById('regEmail').value.trim();
            const pass = document.getElementById('regPassword').value;
            const confirm = document.getElementById('regPasswordConfirm').value;
            const terms = document.getElementById('regTerms').checked;
            console.log('[REGISTER]', { name, email, password: pass, confirm, terms });
            alert('📝 Registrasi simulasi — cek console untuk detail.');
        });

        // ── enter key support ──
        document.querySelectorAll('#formLogin input').forEach(inp => {
            inp.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') document.getElementById('loginSubmit')?.click();
            });
        });
        document.querySelectorAll('#formRegister input').forEach(inp => {
            inp.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') document.getElementById('registerSubmit')?.click();
            });
        });

    })();
</script>

</body>
</html>