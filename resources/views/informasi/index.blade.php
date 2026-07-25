<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Paradise Of Math - Bimbingan Belajar Terpercaya</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        /* 1. Yellow Sticker Badge with Dark Purple Font (EXACT Match to uploaded image) */
        .yellow-sticker-badge {
            background-color: #facc15 !important;
            /* Yellow amber fill */
            color: #2e1065 !important;
            /* Dark purple font */
            font-weight: 800;
            border-radius: 20px;
            padding: 6px 22px;
            display: inline-block;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.18);
            line-height: 1.25;
        }

        /* 2. Heavy Yellow Stroke / Outline around Purple Font */
        .purple-font-yellow-stroke {
            color: #2e1065 !important;
            -webkit-text-stroke: 5px #facc15;
            paint-order: stroke fill;
            font-weight: 900;
        }

        /* 3. Solid Yellow Bubble Shadow surrounding Purple Font */
        .purple-font-yellow-bubble {
            color: #2e1065 !important;
            text-shadow:
                -3px -3px 0 #facc15,
                3px -3px 0 #facc15,
                -3px 3px 0 #facc15,
                3px 3px 0 #facc15,
                -4px 0px 0 #facc15,
                4px 0px 0 #facc15,
                0px -4px 0 #facc15,
                0px 4px 0 #facc15,
                0px 4px 10px rgba(0, 0, 0, 0.15);
            font-weight: 800;
        }

        /* Animations */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-12px) rotate(1deg);
            }
        }

        @keyframes float-reverse {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(12px) rotate(-1deg);
            }
        }

        @keyframes pulse-glow {

            0%,
            100% {
                opacity: 0.35;
                transform: scale(1);
            }

            50% {
                opacity: 0.75;
                transform: scale(1.08);
            }
        }

        .animate-float {
            animation: float 4.5s ease-in-out infinite;
        }

        .animate-float-reverse {
            animation: float-reverse 6s ease-in-out infinite;
        }

        .animate-pulse-glow {
            animation: pulse-glow 5s ease-in-out infinite;
        }

        /* Scroll reveal animation styles */
        .reveal-element {
            opacity: 0;
            transform: translateY(35px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-element.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        .delay-100 {
            transition-delay: 100ms;
        }

        .delay-200 {
            transition-delay: 200ms;
        }

        .delay-300 {
            transition-delay: 300ms;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased selection:bg-amber-400 selection:text-violet-950">

    <!-- ================= HEADER ================= -->
    <header
        class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-violet-100/70 shadow-sm transition-all duration-300">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center justify-between py-3 md:py-4">

                <!-- Logo -->
                <a href="#beranda" class="flex items-center gap-3 group">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-600 to-violet-900 p-0.5 shadow-md shadow-violet-200 group-hover:scale-105 transition-transform duration-300">
                        <div
                            class="w-full h-full bg-white rounded-[10px] overflow-hidden flex items-center justify-center p-1">
                            <img src="{{ asset('images/logoPM.webp') }}" alt="logo"
                                class="w-full h-full object-contain group-hover:rotate-6 transition-transform duration-300" />
                        </div>
                    </div>
                    <!-- Brand Name -->
                    <div
                        class="font-black text-lg md:text-xl purple-font-yellow-bubble whitespace-nowrap tracking-tight">
                        Paradise<span class="text-violet-700">Of </span>Math
                    </div>
                </a>

                <!-- Nav links (desktop) -->
                <ul class="hidden md:flex items-center gap-7 lg:gap-9 text-sm font-bold text-slate-700">
                    <li>
                        <a href="#beranda"
                            class="relative py-1 text-violet-900 transition-colors after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-full after:h-0.5 after:bg-amber-400 after:rounded-full">
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="#about" class="relative py-1 hover:text-violet-800 transition-colors group">
                            Tentang
                            <span
                                class="absolute left-0 bottom-0 w-0 h-0.5 bg-amber-400 rounded-full group-hover:w-full transition-all duration-300"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#fasilitas" class="relative py-1 hover:text-violet-800 transition-colors group">
                            Fasilitas
                            <span
                                class="absolute left-0 bottom-0 w-0 h-0.5 bg-amber-400 rounded-full group-hover:w-full transition-all duration-300"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#jadwal" class="relative py-1 hover:text-violet-800 transition-colors group">
                            Jadwal
                            <span
                                class="absolute left-0 bottom-0 w-0 h-0.5 bg-amber-400 rounded-full group-hover:w-full transition-all duration-300"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#matkul" class="relative py-1 hover:text-violet-800 transition-colors group">
                            Mata Pelajaran
                            <span
                                class="absolute left-0 bottom-0 w-0 h-0.5 bg-amber-400 rounded-full group-hover:w-full transition-all duration-300"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#kontak" class="relative py-1 hover:text-violet-800 transition-colors group">
                            Kontak
                            <span
                                class="absolute left-0 bottom-0 w-0 h-0.5 bg-amber-400 rounded-full group-hover:w-full transition-all duration-300"></span>
                        </a>
                    </li>
                </ul>

                <!-- Auth buttons (desktop) -->
                <div class="hidden md:flex items-center gap-3">
                    @if (auth()->guard('siswa')->check() || auth()->guard('web')->check())
                        @php
                            $user = auth()->guard('siswa')->user() ?? auth()->guard('web')->user();
                            $dashboardRoute = auth()->guard('siswa')->check() 
                                ? route('siswa.dashboard') 
                                : ($user->isAdmin() ? route('admin.dashboard') : route('guru.dashboard'));
                        @endphp
                        <!-- Profile Dropdown (Desktop) -->
                        <div class="relative group">
                            <button id="profile-dropdown-btn" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-violet-50 border border-violet-100 hover:bg-violet-100 hover:border-violet-200 transition duration-200">
                                <!-- Icon -->
                                <div class="w-8 h-8 rounded-full bg-violet-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <!-- Name -->
                                <span class="text-sm font-bold text-violet-950 max-w-[120px] truncate">{{ $user->name }}</span>
                                <!-- Arrow -->
                                <svg class="w-4 h-4 text-violet-900 transition-transform group-hover:rotate-180 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-white border border-violet-100 rounded-2xl shadow-xl py-2 hidden group-hover:block transition duration-200 animate-fadeIn z-50">
                                <div class="px-4 py-2 border-b border-violet-50">
                                    <p class="text-xs text-slate-400 font-medium">Masuk sebagai</p>
                                    <p class="text-xs font-bold text-violet-950 capitalize">{{ auth()->guard('siswa')->check() ? 'Siswa' : $user->role }}</p>
                                </div>
                                <a href="{{ $dashboardRoute }}" class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-violet-50 hover:text-violet-900 transition duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    Dashboard
                                </a>
                                <hr class="border-violet-50 my-1">
                                <form action="{{ route('logout') }}" method="POST" class="block w-full">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50 transition duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Keluar (Logout)
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-5 py-2.5 rounded-xl text-sm font-bold border-2 border-violet-800 text-violet-900 hover:bg-violet-900 hover:text-white hover:border-violet-900 hover:shadow-lg hover:shadow-violet-200 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300">
                            Login
                        </a>
                        <a href="{{ route('login') }}#daftar"
                            class="px-5 py-2.5 rounded-xl text-sm font-bold bg-amber-400 text-violet-950 shadow-md shadow-amber-400/20 hover:bg-amber-300 hover:shadow-lg hover:shadow-amber-400/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300">
                            Register
                        </a>
                    @endif
                </div>

                <!-- Mobile menu button -->
                <button id="mobile-menu-toggle"
                    class="md:hidden text-violet-950 p-2 rounded-lg hover:bg-violet-50 transition-colors active:scale-95"
                    aria-label="Buka menu">
                    <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7 transition-transform duration-300" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

            </nav>

            <!-- Mobile menu (hidden by default) -->
            <div id="mobile-menu" class="md:hidden hidden pb-5 pt-2 border-t border-violet-100/80 animate-fadeIn">
                <ul class="flex flex-col gap-3 text-sm font-bold text-slate-700">
                    <li><a href="#beranda" class="block py-2 px-3 rounded-lg bg-violet-50 text-violet-900">Beranda</a>
                    </li>
                    <li><a href="#about"
                            class="block py-2 px-3 rounded-lg hover:bg-violet-50 hover:text-violet-900 transition-colors">Tentang</a>
                    </li>
                    <li><a href="#fasilitas"
                            class="block py-2 px-3 rounded-lg hover:bg-violet-50 hover:text-violet-900 transition-colors">Fasilitas</a>
                    </li>
                    <li><a href="#jadwal"
                            class="block py-2 px-3 rounded-lg hover:bg-violet-50 hover:text-violet-900 transition-colors">Jadwal</a>
                    </li>

                    <li><a href="#matkul"
                            class="block py-2 px-3 rounded-lg hover:bg-violet-50 hover:text-violet-900 transition-colors">Mata
                            Pelajaran</a></li>
                    <li><a href="#kontak"
                            class="block py-2 px-3 rounded-lg hover:bg-violet-50 hover:text-violet-900 transition-colors">Kontak</a>
                    </li>

                    @if (auth()->guard('siswa')->check() || auth()->guard('web')->check())
                        @php
                            $user = auth()->guard('siswa')->user() ?? auth()->guard('web')->user();
                            $dashboardRoute = auth()->guard('siswa')->check() 
                                ? route('siswa.dashboard') 
                                : ($user->isAdmin() ? route('admin.dashboard') : route('guru.dashboard'));
                        @endphp
                        <li class="pt-3 border-t border-violet-100/80 flex flex-col gap-2">
                            <div class="px-3 py-1.5 flex items-center gap-2 bg-violet-50 rounded-xl">
                                <div class="w-8 h-8 rounded-full bg-violet-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="truncate">
                                    <p class="text-xs font-bold text-violet-950">{{ $user->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium capitalize">{{ auth()->guard('siswa')->check() ? 'Siswa' : $user->role }}</p>
                                </div>
                            </div>
                            <a href="{{ $dashboardRoute }}"
                                class="text-center px-5 py-2.5 rounded-xl text-sm font-bold bg-violet-800 text-white shadow-md hover:bg-violet-950 transition-all">
                                Masuk Dashboard
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                    class="w-full text-center px-5 py-2.5 rounded-xl text-sm font-bold border-2 border-rose-600 text-rose-600 hover:bg-rose-50 transition-all">
                                    Keluar (Logout)
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="pt-3 flex flex-col gap-2.5">
                            <a href="{{ route('login') }}"
                                class="text-center px-5 py-2.5 rounded-xl text-sm font-bold border-2 border-violet-800 text-violet-900 hover:bg-violet-50 transition-all">
                                Login
                            </a>
                            <a href="{{ route('login') }}#daftar"
                                class="text-center px-5 py-2.5 rounded-xl text-sm font-bold bg-amber-400 text-violet-950 shadow-md transition-all">
                                Register
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </header>

    <!-- ================= HERO ================= -->
    <section id="beranda"
        class="relative bg-gradient-to-br from-violet-950 via-violet-900 to-violet-800 py-16 sm:py-24 md:py-28 overflow-hidden">

        <!-- Animated Background Orbs / Blobs -->
        <div
            class="w-96 h-96 bg-amber-400/15 rounded-full blur-3xl absolute -top-16 -left-20 animate-pulse-glow pointer-events-none">
        </div>
        <div
            class="w-80 h-80 bg-violet-500/20 rounded-full blur-3xl absolute -bottom-10 right-0 animate-float-slow pointer-events-none">
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-12 items-center relative z-10">

            <!-- Kiri (Text & CTA) -->
            <div class="text-center md:text-left reveal-element">

                <!-- Floating pill badge -->
                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-400/15 border border-amber-400/30 text-amber-300 font-bold text-xs sm:text-sm tracking-wide mb-6 shadow-sm backdrop-blur-md animate-float">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                    ✨ Bimbingan Belajar Terpercaya
                </div>

                <!-- MAIN HERO HEADING MATCHING THE UPLOADED IMAGE (YELLOW STICKER BADGE WITH DARK PURPLE FONT) -->
                <h1
                    class="font-black text-2xl sm:text-3xl md:text-4xl lg:text-[2.75rem] leading-snug mb-6 tracking-tight">
                    <span
                        class="yellow-sticker-badge my-1.5 shadow-xl transform -rotate-1 hover:rotate-0 transition-transform duration-300">
                        Sukses Akademik
                    </span><br />
                    <span
                        class="yellow-sticker-badge my-1.5 shadow-xl transform rotate-1 hover:rotate-0 transition-transform duration-300">
                        bersama Paradise of Math!
                    </span>
                </h1>

                <p
                    class="text-violet-100/90 text-sm sm:text-base md:text-lg max-w-md mx-auto md:mx-0 mb-9 leading-relaxed font-normal">
                    Menawarkan bimbingan belajar yang berkualitas dengan tutor pribadi berpengalaman untuk melepaskan
                    potensi terbaik siswa.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    <a href="#kelas"
                        class="relative group px-7 py-3.5 rounded-xl bg-gradient-to-r from-amber-400 to-amber-300 text-violet-950 font-extrabold shadow-lg shadow-amber-400/25 hover:shadow-xl hover:shadow-amber-400/40 hover:scale-105 active:scale-95 transition-all duration-300 overflow-hidden">
                        <span class="relative z-10 flex items-center gap-2">
                            Daftar Sekarang
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </span>
                    </a>

                    <a href="#about"
                        class="px-7 py-3.5 rounded-xl backdrop-blur-md bg-white/10 border-2 border-white/30 text-white font-extrabold hover:bg-white hover:text-violet-950 hover:scale-105 active:scale-95 transition-all duration-300">
                        Pelajari Lebih Lanjut
                    </a>
                </div>

                <!-- Floating stat highlight -->
                <div
                    class="mt-10 pt-6 border-t border-violet-800/80 flex items-center justify-center md:justify-start gap-8">
                    <div>
                        <div class="text-2xl font-extrabold text-amber-400">1000+</div>
                        <div class="text-xs text-violet-200/80 font-medium">Siswa Aktif</div>
                    </div>
                    <div class="w-px h-8 bg-violet-800"></div>
                    <div>
                        <div class="text-2xl font-extrabold text-amber-400">98%</div>
                        <div class="text-xs text-violet-200/80 font-medium">Kelulusan PTN</div>
                    </div>
                    <div class="w-px h-8 bg-violet-800"></div>
                    <div>
                        <div class="text-2xl font-extrabold text-amber-400">4.9/5</div>
                        <div class="text-xs text-violet-200/80 font-medium">Rating Tutor</div>
                    </div>
                </div>

            </div>

            <!-- Kanan (gambar / illustration with floating cards) -->
            <div class="flex justify-center relative reveal-element delay-200">
                <div class="relative w-full max-w-xs sm:max-w-sm">

                    <!-- Glow effect behind image -->
                    <div
                        class="absolute -inset-2 rounded-3xl bg-gradient-to-r from-amber-400 to-violet-500 opacity-40 blur-xl animate-pulse-glow">
                    </div>

                    <!-- Main Hero Image Frame -->
                    <div
                        class="relative aspect-[4/5] rounded-3xl bg-gradient-to-b from-violet-800/90 to-violet-950/90 border-2 border-white/20 shadow-2xl p-4 flex flex-col items-center justify-center text-center overflow-hidden animate-float">
                        <div
                            class="w-20 h-20 rounded-full bg-violet-600/50 flex items-center justify-center mb-4 border border-violet-400/40 text-amber-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                        </div>
                        <span class="text-violet-100 font-bold text-sm px-4">
                            [ foto siswa/tutor ]
                        </span>
                        <span class="text-violet-300/70 text-xs font-mono mt-2">
                            public/images/hero-student.jpg
                        </span>
                    </div>

                    <!-- Floating Badge 1 (Top Right) -->
                    <div
                        class="absolute -top-4 -right-4 bg-white/90 backdrop-blur-md border border-amber-300 p-3 rounded-2xl shadow-xl flex items-center gap-3 animate-float-reverse">
                        <div
                            class="w-8 h-8 rounded-lg bg-amber-400 text-violet-950 flex items-center justify-center font-bold text-sm">
                            🏆
                        </div>
                        <div class="text-xs">
                            <p class="font-extrabold text-violet-950">Top Ranking</p>
                            <p class="text-slate-500 font-medium">Bimbel No.1</p>
                        </div>
                    </div>

                    <!-- Floating Badge 2 (Bottom Left) -->
                    <div
                        class="absolute -bottom-4 -left-4 bg-violet-900/90 backdrop-blur-md border border-violet-600 p-3 rounded-2xl shadow-xl flex items-center gap-3 animate-float">
                        <div
                            class="w-8 h-8 rounded-lg bg-violet-600 text-white flex items-center justify-center font-bold text-sm">
                            💡
                        </div>
                        <div class="text-xs">
                            <p class="font-extrabold text-white">Tutor Stand By</p>
                            <p class="text-violet-200 font-medium">1-on-1 Mentoring</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- ================= ABOUT ================= -->
    <section id="about"
        class="py-20 sm:py-24 md:py-28 bg-gradient-to-b from-white via-violet-50/30 to-white relative overflow-hidden">

        <!-- Subtle Ambient Background Light -->
        <div class="w-96 h-96 bg-amber-300/20 rounded-full blur-3xl absolute top-1/3 -right-32 pointer-events-none">
        </div>
        <div class="w-80 h-80 bg-violet-200/30 rounded-full blur-3xl absolute bottom-10 -left-20 pointer-events-none">
        </div>

        <div
            class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-12 md:gap-16 items-start relative z-10">

            <!-- Kiri (Gambar & Visual Card) -->
            <div class="flex justify-center md:justify-start order-1 md:order-none reveal-element">
                <div class="relative w-full max-w-xs sm:max-w-sm sticky top-28">

                    <!-- Decorative background frame -->
                    <div
                        class="absolute -inset-3 rounded-3xl bg-gradient-to-tr from-violet-200 via-amber-200 to-violet-300 opacity-60 blur-md">
                    </div>

                    <!-- Main Illustration Card -->
                    <div
                        class="relative aspect-square rounded-3xl bg-white border border-violet-100 shadow-2xl p-6 flex flex-col items-center justify-center text-center overflow-hidden ">
                        <div
                            class="w-24 h-24 rounded-2xl bg-violet-100 flex items-center justify-center mb-4 text-violet-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="text-violet-950 font-bold text-sm">
                            [ ilustrasi kartun ]
                        </span>
                        <span class="text-slate-400 text-xs font-mono mt-1">
                            public/images/about-kartun.png
                        </span>

                        <div
                            class="mt-6 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 border border-amber-300 text-amber-900 text-xs font-bold">
                            ⭐ 4.9/5 Rating Dari Orang Tua & Siswa
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kanan (Daftar Poin Interaktif) -->
            <div class="order-2 md:order-none reveal-element delay-100">

                <div class="mb-8">
                    <span
                        class="inline-block px-3.5 py-1 rounded-full bg-violet-100 text-violet-900 font-extrabold text-xs tracking-wider uppercase mb-3">
                        Mengapa Memilih Kami?
                    </span>
                    <!-- Heading with Purple Font + Yellow Bubble Shadow -->
                    <h2 class="font-extrabold text-2xl sm:text-3xl md:text-4xl purple-font-yellow-bubble leading-tight">
                        Keunggulan Paradise Of Math
                    </h2>
                </div>

                <ol class="space-y-4 mb-10">

                    <!-- Item 01 -->
                    <li
                        class="group p-4 rounded-2xl bg-white/90 backdrop-blur-sm border border-violet-100 hover:border-violet-300 hover:shadow-xl hover:shadow-violet-200/50 hover:-translate-y-1.5 transition-all duration-300 ease-out cursor-pointer flex gap-4">
                        <span
                            class="shrink-0 w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-violet-950 text-amber-300 group-hover:bg-amber-400 group-hover:text-violet-950 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 flex items-center justify-center font-extrabold text-xs sm:text-sm shadow-md">
                            01
                        </span>
                        <div>
                            <!-- PURPLE FONT WITH YELLOW TEXT STROKE/BUBBLE -->
                            <h3
                                class="font-extrabold purple-font-yellow-bubble group-hover:text-violet-900 text-sm sm:text-base mb-1 transition-colors">
                                EXCLUSIVE CLASS
                            </h3>
                            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                Dapatkan perhatian penuh dan pembelajaran personal yang interaktif dan intensif, sesuai
                                dengan kebutuhan individual siswa.
                            </p>
                        </div>
                    </li>

                    <!-- Item 02 -->
                    <li
                        class="group p-4 rounded-2xl bg-white/90 backdrop-blur-sm border border-violet-100 hover:border-violet-300 hover:shadow-xl hover:shadow-violet-200/50 hover:-translate-y-1.5 transition-all duration-300 ease-out cursor-pointer flex gap-4">
                        <span
                            class="shrink-0 w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-violet-950 text-amber-300 group-hover:bg-amber-400 group-hover:text-violet-950 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 flex items-center justify-center font-extrabold text-xs sm:text-sm shadow-md">
                            02
                        </span>
                        <div>
                            <!-- PURPLE FONT WITH YELLOW TEXT STROKE/BUBBLE -->
                            <h3
                                class="font-extrabold purple-font-yellow-bubble group-hover:text-violet-900 text-sm sm:text-base mb-1 transition-colors">
                                TUTOR SELALU STAND BY
                            </h3>
                            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                Bingung dengan materi? Tutor kami selalu siap menjelaskan hingga siswa paham sepenuhnya
                                tanpa batasan waktu.
                            </p>
                        </div>
                    </li>

                    <!-- Item 03 -->
                    <li
                        class="group p-4 rounded-2xl bg-white/90 backdrop-blur-sm border border-violet-100 hover:border-violet-300 hover:shadow-xl hover:shadow-violet-200/50 hover:-translate-y-1.5 transition-all duration-300 ease-out cursor-pointer flex gap-4">
                        <span
                            class="shrink-0 w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-violet-950 text-amber-300 group-hover:bg-amber-400 group-hover:text-violet-950 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 flex items-center justify-center font-extrabold text-xs sm:text-sm shadow-md">
                            03
                        </span>
                        <div>
                            <!-- PURPLE FONT WITH YELLOW TEXT STROKE/BUBBLE -->
                            <h3
                                class="font-extrabold purple-font-yellow-bubble group-hover:text-violet-900 text-sm sm:text-base mb-1 transition-colors">
                                BELAJAR SESUAI KURIKULUM ANDA
                            </h3>
                            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                Materi bimbel menyesuaikan dengan kurikulum sekolah individual siswa, memastikan siswa
                                selalu selangkah lebih maju.
                            </p>
                        </div>
                    </li>

                    <!-- Item 04 -->
                    <li
                        class="group p-4 rounded-2xl bg-white/90 backdrop-blur-sm border border-violet-100 hover:border-violet-300 hover:shadow-xl hover:shadow-violet-200/50 hover:-translate-y-1.5 transition-all duration-300 ease-out cursor-pointer flex gap-4">
                        <span
                            class="shrink-0 w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-violet-950 text-amber-300 group-hover:bg-amber-400 group-hover:text-violet-950 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 flex items-center justify-center font-extrabold text-xs sm:text-sm shadow-md">
                            04
                        </span>
                        <div>
                            <!-- PURPLE FONT WITH YELLOW TEXT STROKE/BUBBLE -->
                            <h3
                                class="font-extrabold purple-font-yellow-bubble group-hover:text-violet-900 text-sm sm:text-base mb-1 transition-colors">
                                DUKUNGAN PENUH UNTUK PERSIAPAN UJIAN
                            </h3>
                            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                Strategi belajar jangka panjang dari tutor berpengalaman, siap membantu siswa sukses
                                dalam ujian sekolah hingga SNBT.
                            </p>
                        </div>
                    </li>

                    <!-- Item 05 -->
                    <li
                        class="group p-4 rounded-2xl bg-white/90 backdrop-blur-sm border border-violet-100 hover:border-violet-300 hover:shadow-xl hover:shadow-violet-200/50 hover:-translate-y-1.5 transition-all duration-300 ease-out cursor-pointer flex gap-4">
                        <span
                            class="shrink-0 w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-violet-950 text-amber-300 group-hover:bg-amber-400 group-hover:text-violet-950 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 flex items-center justify-center font-extrabold text-xs sm:text-sm shadow-md">
                            05
                        </span>
                        <div>
                            <!-- PURPLE FONT WITH YELLOW TEXT STROKE/BUBBLE -->
                            <h3
                                class="font-extrabold purple-font-yellow-bubble group-hover:text-violet-900 text-sm sm:text-base mb-1 transition-colors">
                                LINGKUNGAN BELAJAR KONDUSIF
                            </h3>
                            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                Nikmati suasana belajar yang nyaman, menyenangkan, dan mendukung dengan fasilitas
                                unggulan modern.
                            </p>
                        </div>
                    </li>

                </ol>

                <!-- Bottom Callout Card with Purple Font + Yellow Text Shadow -->
                <div
                    class="relative rounded-2xl bg-gradient-to-r from-violet-100 via-amber-50 to-violet-50 border border-violet-200 p-6 sm:p-7 shadow-lg overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
                    <div
                        class="absolute -right-6 -bottom-6 w-24 h-24 bg-amber-400/20 rounded-full blur-xl pointer-events-none">
                    </div>
                    <span
                        class="inline-block px-3 py-1 rounded-full bg-amber-400 text-violet-950 font-bold text-xs shadow-sm mb-2">
                        🎉 1000+ siswa telah membuktikan
                    </span>
                    <!-- PURPLE FONT WITH YELLOW STICKER / BUBBLE SHADOW -->
                    <h2 class="font-extrabold text-xl sm:text-2xl purple-font-yellow-bubble leading-snug mt-1">
                        Raih Nilai Terbaik hingga Masuk Sekolah/PTN Impian!
                    </h2>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= FASILITAS SECTION (GALERI) ================= -->
    <section id="fasilitas" class="py-20 sm:py-24 md:py-28 bg-slate-100/80 relative overflow-hidden">
        
        <!-- Background Orbs -->
        <div class="w-96 h-96 bg-violet-200/40 rounded-full blur-3xl absolute -top-10 -left-20 pointer-events-none"></div>
        <div class="w-96 h-96 bg-amber-300/30 rounded-full blur-3xl absolute -bottom-10 -right-20 pointer-events-none"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-14 reveal-element">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-violet-100 text-violet-900 font-extrabold text-xs sm:text-sm mb-4 shadow-sm">
                    ✨ FASILITAS LENGKAP & NYAMAN
                </div>
                
                <h2 class="font-black text-2xl sm:text-3xl md:text-4xl lg:text-5xl leading-tight mb-4 tracking-tight">
                    <span class="yellow-sticker-badge shadow-2xl transform -rotate-1 hover:rotate-0 transition-transform duration-300">
                        Belajar dengan Nyaman di Paradise of Math!
                    </span>
                </h2>
                
                <p class="text-slate-600 text-sm sm:text-base md:text-lg leading-relaxed font-normal">
                    Fasilitas modern dan lingkungan belajar yang kondusif disiapkan khusus untuk mendukung kenyamanan maksimal siswa.
                </p>
            </div>

            <!-- Facilities Cards Grid (Asymmetrical / Staggered / Bento "Acak" Layout) -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-8 mb-16 items-stretch">
                
                <!-- Card 1: Kelas Yang Nyaman (+ AC & + WiFi) - Featured Large Card (7 Columns, Tilted Left) -->
                <div class="md:col-span-7 group relative rounded-3xl bg-white border-2 border-violet-800 p-3 shadow-xl hover:shadow-2xl hover:-translate-y-2 transform md:-rotate-1 hover:rotate-0 transition-all duration-300 reveal-element">
                    <div class="relative aspect-[16/10] sm:aspect-[16/9] rounded-2xl bg-violet-950 overflow-hidden flex flex-col items-center justify-center text-center p-4">
                        <span class="text-violet-200 font-bold text-sm sm:text-base">
                            [ foto kelas ber-AC & WiFi ]
                        </span>
                        <span class="text-violet-400 text-xs font-mono mt-1">
                            public/images/fasilitas-kelas.jpg
                        </span>

                        <!-- Badge Top Left (+ AC) -->
                        <div class="absolute top-3 left-3 bg-violet-950/90 text-amber-300 font-black text-xs sm:text-sm px-3.5 py-1.5 rounded-xl border border-amber-300/40 shadow-lg backdrop-blur-md animate-pulse-glow">
                            + AC
                        </div>

                        <!-- Badge Bottom Right (+ WiFi) -->
                        <div class="absolute bottom-3 right-3 bg-violet-950/90 text-amber-300 font-black text-xs sm:text-sm px-3.5 py-1.5 rounded-xl border border-amber-300/40 shadow-lg backdrop-blur-md">
                            + WiFi
                        </div>

                        <!-- Badge Bottom Left Tag (Kelas yang nyaman) -->
                        <div class="absolute bottom-3 left-3 bg-amber-400 text-violet-950 font-black text-xs sm:text-sm px-3.5 py-1.5 rounded-xl shadow-md transform -rotate-1">
                            Kelas yang nyaman
                        </div>
                    </div>
                    <div class="p-4 sm:p-5 flex items-center justify-between flex-wrap gap-2">
                        <div>
                            <h3 class="font-extrabold text-violet-950 text-base sm:text-lg">Ruang Kelas Ber-AC & High-Speed WiFi</h3>
                            <p class="text-slate-500 text-xs sm:text-sm">Ruangan sejuk, pencahayaan optimal, dan meja-kursi nyaman untuk fokus belajar.</p>
                        </div>
                        <span class="px-3 py-1 bg-violet-100 text-violet-900 font-extrabold text-xs rounded-full">★ Utama</span>
                    </div>
                </div>

                <!-- Card 2: Toilet Bersih (5 Columns, Tilted Right & Offset Down) -->
                <div class="md:col-span-5 group relative rounded-3xl bg-white border-2 border-violet-800 p-3 shadow-xl hover:shadow-2xl hover:-translate-y-2 transform md:rotate-2 md:translate-y-6 hover:rotate-0 transition-all duration-300 reveal-element delay-100">
                    <div class="relative aspect-[4/3] rounded-2xl bg-violet-950 overflow-hidden flex flex-col items-center justify-center text-center p-4">
                        <span class="text-violet-200 font-bold text-sm">
                            [ foto toilet ]
                        </span>
                        <span class="text-violet-400 text-xs font-mono mt-1">
                            public/images/fasilitas-toilet.jpg
                        </span>

                        <!-- Badge Top Center Tag (Toilet Bersih) -->
                        <div class="absolute top-3 left-1/2 -translate-x-1/2 bg-amber-400 text-violet-950 font-black text-xs sm:text-sm px-4 py-1.5 rounded-xl shadow-md transform rotate-1">
                            Toilet Bersih
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-extrabold text-violet-950 text-base mb-1">Toilet Bersih & Higienis</h3>
                        <p class="text-slate-500 text-xs sm:text-sm">Fasilitas sanitasi yang selalu terawat dan bersih demi kenyamanan setiap hari.</p>
                    </div>
                </div>

                <!-- Card 3: Mushala Luas (5 Columns, Tilted Left & Offset Up) -->
                <div class="md:col-span-5 group relative rounded-3xl bg-white border-2 border-violet-800 p-3 shadow-xl hover:shadow-2xl hover:-translate-y-2 transform md:-rotate-2 md:-translate-y-2 hover:rotate-0 transition-all duration-300 reveal-element delay-200">
                    <div class="relative aspect-[4/3] rounded-2xl bg-violet-950 overflow-hidden flex flex-col items-center justify-center text-center p-4">
                        <span class="text-violet-200 font-bold text-sm">
                            [ foto mushala ]
                        </span>
                        <span class="text-violet-400 text-xs font-mono mt-1">
                            public/images/fasilitas-mushala.jpg
                        </span>

                        <!-- Badge Bottom Left Tag (Mushala Luas) -->
                        <div class="absolute bottom-3 left-3 bg-amber-400 text-violet-950 font-black text-xs sm:text-sm px-3.5 py-1.5 rounded-xl shadow-md transform -rotate-2">
                            Mushala Luas
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-extrabold text-violet-950 text-base mb-1">Mushala Luas & Suci</h3>
                        <p class="text-slate-500 text-xs sm:text-sm">Tempat ibadah yang tenang, harum, dan luas agar siswa beribadah tepat waktu.</p>
                    </div>
                </div>

                <!-- Card 4: Extra Feature Highlights (7 Columns, Tilted Right) -->
                <div class="md:col-span-7 group relative rounded-3xl bg-gradient-to-br from-violet-950 via-violet-900 to-violet-950 text-white border-2 border-amber-400/50 p-6 sm:p-7 shadow-xl hover:shadow-2xl transform md:rotate-1 hover:rotate-0 transition-all duration-300 flex flex-col justify-between reveal-element delay-300">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full bg-amber-400 text-violet-950 font-black text-xs">
                            💡 Kenyamanan 100%
                        </span>
                        <span class="text-xs text-amber-300 font-bold">Paradise of Math</span>
                    </div>
                    
                    <h3 class="font-extrabold text-lg sm:text-xl text-amber-300 mb-3">
                        Lingkungan Belajar Bebas Bising & Kondusif
                    </h3>
                    <p class="text-violet-100 text-xs sm:text-sm mb-6 leading-relaxed">
                        Dirancang khusus dengan standar kenyamanan tinggi untuk memastikan siswa fokus dalam memahami setiap rumus dan konsep materi.
                    </p>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-violet-800">
                        <div class="bg-white/10 rounded-xl p-2.5 text-center backdrop-blur-sm">
                            <div class="text-lg mb-0.5">❄️</div>
                            <div class="text-xs font-bold text-amber-300">Full AC</div>
                        </div>
                        <div class="bg-white/10 rounded-xl p-2.5 text-center backdrop-blur-sm">
                            <div class="text-lg mb-0.5">📶</div>
                            <div class="text-xs font-bold text-amber-300">Free WiFi</div>
                        </div>
                        <div class="bg-white/10 rounded-xl p-2.5 text-center backdrop-blur-sm">
                            <div class="text-lg mb-0.5">🕌</div>
                            <div class="text-xs font-bold text-amber-300">Mushala</div>
                        </div>
                        <div class="bg-white/10 rounded-xl p-2.5 text-center backdrop-blur-sm">
                            <div class="text-lg mb-0.5">🚽</div>
                            <div class="text-xs font-bold text-amber-300">Toilet</div>
                        </div>
                    </div>
                </div>

            </div>


            <!-- Footer Banner Contact & Address (Matching Bottom of Uploaded Image) -->
            <div class="reveal-element delay-300 rounded-3xl bg-gradient-to-r from-violet-950 via-violet-900 to-violet-950 text-white p-6 sm:p-8 shadow-2xl border border-violet-800">
                <div class="grid md:grid-cols-2 gap-6 items-center">
                    
                    <!-- Social Media Links -->
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl border border-white/15">
                            <span class="text-pink-400 text-lg">📸</span>
                            <span class="text-xs sm:text-sm font-extrabold text-amber-300">@paradiseofmath</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl border border-white/15">
                            <span class="text-white text-lg">🎵</span>
                            <span class="text-xs sm:text-sm font-extrabold text-amber-300">@paradiseofmath</span>
                        </div>
                    </div>

                    <!-- Contact & Address -->
                    <div class="space-y-2 text-xs sm:text-sm text-violet-100">
                        <div class="flex items-start gap-2">
                            <span class="text-amber-400 font-bold shrink-0">📍 Alamat:</span>
                            <span>Jln. Jati I No.19 RT/RW 002/001 Sawahan, Padang Timur, Sumatera Barat, 25121</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-amber-400 font-bold shrink-0">📞 WhatsApp / Kontak:</span>
                            <span class="font-extrabold text-white">0811-6612-050 (Pimpinan - Kiki)</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>


    <!-- ================= JADWAL SECTION ================= -->
    <section id="jadwal" class="py-20 sm:py-24 md:py-28 bg-gradient-to-b from-violet-950 via-violet-900 to-violet-950 text-white relative overflow-hidden">
        
        <!-- Ambient Glow Background Blobs -->
        <div class="w-96 h-96 bg-amber-400/15 rounded-full blur-3xl absolute -top-20 right-0 animate-pulse-glow pointer-events-none"></div>
        <div class="w-96 h-96 bg-violet-600/20 rounded-full blur-3xl absolute -bottom-20 -left-20 animate-float pointer-events-none"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Section Title -->
            <div class="text-center max-w-3xl mx-auto mb-14 reveal-element">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-400/15 border border-amber-400/30 text-amber-300 font-bold text-xs sm:text-sm mb-4 backdrop-blur-md">
                    📅 WAKTU & SESI BELAJAR FLEKSIBEL
                </div>
                
                <h2 class="font-black text-2xl sm:text-3xl md:text-4xl lg:text-5xl leading-tight mb-4 tracking-tight">
                    <span class="yellow-sticker-badge shadow-2xl transform -rotate-1 hover:rotate-0 transition-transform duration-300">
                        JADWAL YANG TERSEDIA
                    </span>
                </h2>
                
                <p class="text-violet-200 text-sm sm:text-base md:text-lg leading-relaxed mt-2 font-normal">
                    Pilih sesi bimbingan 90 menit yang paling sesuai dengan aktivitas dan kesibukan sekolahmu.
                </p>
            </div>

            <!-- Quick Day Info Badges -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10 reveal-element delay-100">
                <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-center hover:bg-white/15 hover:border-amber-400/40 transition-all duration-300">
                    <div class="text-xs text-amber-300 font-bold uppercase tracking-wider mb-1">Senin s.d Kamis</div>
                    <div class="text-lg font-black text-white">5 Sesi Shift</div>
                    <div class="text-xs text-violet-200 mt-1">13.30 - 21.00 WIB</div>
                </div>
                <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-center hover:bg-white/15 hover:border-amber-400/40 transition-all duration-300">
                    <div class="text-xs text-amber-300 font-bold uppercase tracking-wider mb-1">Jumat</div>
                    <div class="text-lg font-black text-white">6 Sesi Shift</div>
                    <div class="text-xs text-violet-200 mt-1">12.00 - 21.00 WIB</div>
                </div>
                <div class="p-4 rounded-2xl bg-amber-400/20 backdrop-blur-md border border-amber-400/50 text-center hover:bg-amber-400/30 transition-all duration-300 shadow-lg shadow-amber-400/10">
                    <div class="text-xs text-amber-300 font-black uppercase tracking-wider mb-1">⭐ Sabtu (Full Day)</div>
                    <div class="text-lg font-black text-amber-300">9 Sesi Lengkap</div>
                    <div class="text-xs text-amber-100 mt-1">07.30 - 21.00 WIB</div>
                </div>
                <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-center hover:bg-white/15 transition-all duration-300">
                    <div class="text-xs text-violet-300 font-bold uppercase tracking-wider mb-1">Minggu</div>
                    <div class="text-lg font-black text-slate-300">Libur Sesi</div>
                    <div class="text-xs text-violet-300 mt-1">Privat By Request</div>
                </div>
            </div>

            <!-- Table Card Container -->
            <div class="reveal-element delay-200 bg-white rounded-3xl shadow-2xl overflow-hidden border-2 border-amber-400/40">
                
                <!-- Table Header Card Title -->
                <div class="bg-gradient-to-r from-violet-950 via-violet-900 to-violet-950 px-6 py-5 text-center border-b border-violet-800 flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-amber-400 animate-ping"></div>
                        <span class="font-extrabold text-lg text-white tracking-wide">Tabel Shift Bimbingan (90 Menit / Sesi)</span>
                    </div>
                    <span class="text-xs font-bold bg-amber-400 text-violet-950 px-3 py-1 rounded-full shadow-sm">
                        ✓ Slot Tersedia
                    </span>
                </div>

                <!-- Responsive Table Wrapper -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-violet-100/80 text-violet-950 text-xs sm:text-sm font-extrabold uppercase border-b-2 border-violet-200">
                                <th class="py-4 px-6 text-slate-900 whitespace-nowrap">Shift (90 menit)</th>
                                <th class="py-4 px-6 text-center text-violet-900 whitespace-nowrap">Senin s.d Kamis</th>
                                <th class="py-4 px-6 text-center text-violet-900 whitespace-nowrap">Jumat</th>
                                <th class="py-4 px-6 text-center text-violet-950 bg-amber-200/60 whitespace-nowrap">Sabtu</th>
                                <th class="py-4 px-6 text-center text-slate-500 whitespace-nowrap">Minggu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-violet-100 text-xs sm:text-sm font-bold text-slate-800">
                            
                            <!-- Row 1: 07.30 - 09.00 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200">
                                <td class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-violet-400"></span>
                                    07.30 - 09.00
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                            <!-- Row 2: 09.00 - 10.30 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200 bg-slate-50/40">
                                <td class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-violet-400"></span>
                                    09.00 - 10.30
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                            <!-- Row 3: 10.30 - 12.00 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200">
                                <td class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-violet-400"></span>
                                    10.30 - 12.00
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                            <!-- Row 4: 12.00 - 13.30 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200 bg-slate-50/40">
                                <td class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    12.00 - 13.30
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                            <!-- Row 5: 13.30 - 15.00 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200">
                                <td class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    13.30 - 15.00
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                            <!-- Row 6: 15.00 - 16.30 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200 bg-slate-50/40">
                                <td class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    15.00 - 16.30
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                            <!-- Row 7: 16.30 - 18.00 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200">
                                <td class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    16.30 - 18.00
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                            <!-- Row 8: 18.00 - 19.30 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200 bg-slate-50/40">
                                <td class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    18.00 - 19.30
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                            <!-- Row 9: 19.30 - 21.00 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200">
                                <td class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    19.30 - 21.00
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <!-- Footer Note -->
                <div class="bg-violet-50 px-6 py-4 border-t border-violet-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs font-semibold text-slate-600">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span>Durasi per sesi bimbingan adalah <strong>90 Menit</strong></span>
                    </div>
                    <div class="text-violet-900 font-bold">
                        *Jadwal privat khusus dapat didiskusikan langsung dengan Tutor
                    </div>
                </div>

            </div>

            <!-- Callout CTA Below Schedule -->
            <div class="mt-12 text-center reveal-element delay-300">
                <div class="inline-flex flex-col sm:flex-row items-center gap-4 p-4 sm:px-8 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20">
                    <span class="text-sm font-bold text-violet-100">Ingin request jadwal khusus di luar tabel di atas?</span>
                    <a href="#kontak" class="px-6 py-2.5 rounded-xl bg-amber-400 text-violet-950 font-extrabold shadow-md hover:bg-amber-300 hover:scale-105 transition-all duration-300 text-xs sm:text-sm">
                        Konsultasi Jadwal Sesuai Keinginan
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- ================= SCRIPTS ================= -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mobile Menu Toggle with Smooth Fade
            const toggleBtn = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');

            if (toggleBtn && mobileMenu) {
                toggleBtn.addEventListener('click', function () {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Scroll Reveal Animation via IntersectionObserver
            const revealElements = document.querySelectorAll('.reveal-element');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -40px 0px'
            });

            revealElements.forEach(el => observer.observe(el));
        });
    </script>

</body>

</html>