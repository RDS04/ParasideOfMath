<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Paradise Of Math - Bimbingan Belajar Terpercaya</title>

    <!-- Favicon / Tab Icon -->
    <link rel="icon" type="image/webp" href="{{ asset('images/logoPM.webp') }}">
    <link rel="shortcut icon" type="image/webp" href="{{ asset('images/logoPM.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logoPM.webp') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Swiper.js CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        /* Swiper Pricing Slider Styles */
        .pricing-swiper {
            padding-top: 1.5rem !important;
            padding-bottom: 3.5rem !important;
            overflow: hidden !important;
            width: 100% !important;
        }

        .pricing-swiper .swiper-wrapper {
            align-items: center !important;
        }

        .pricing-swiper .swiper-slide {
            position: relative;
            z-index: 1;
            width: 360px !important;
            transition: all 0.4s ease;
            transform: scale(0.9);
            filter: blur(2px);
            opacity: 0.6;
        }

        .pricing-swiper .swiper-slide-active {
            position: relative;
            transform: scale(1) !important;
            filter: blur(0px) !important;
            opacity: 1 !important;
            z-index: 10 !important;
        }

        @media (max-width: 640px) {
            .pricing-swiper {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .pricing-swiper .swiper-slide {
                width: 285px !important;
                max-width: 82vw !important;
            }

            .pricing-swiper .swiper-slide-active {
                transform: translateX(30px) scale(1) !important;
                filter: blur(0px) !important;
                opacity: 1 !important;
                z-index: 20 !important;
            }
        }

        .swiper-button-prev-custom,
        .swiper-button-next-custom {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 3.25rem;
            height: 3.25rem;
            background: linear-gradient(135deg, #7c3aed, #4c1d95);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.1rem;
            box-shadow: 0 10px 20px -5px rgba(76, 29, 149, 0.4);
            cursor: pointer;
            z-index: 20;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .swiper-button-prev-custom:hover,
        .swiper-button-next-custom:hover {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: #2e1065;
            box-shadow: 0 10px 20px -5px rgba(245, 158, 11, 0.4);
            transform: translateY(-50%) scale(1.12);
        }

        .swiper-button-prev-custom:active,
        .swiper-button-next-custom:active {
            transform: translateY(-50%) scale(0.95);
        }

        .swiper-button-prev-custom {
            left: -1rem;
        }

        .swiper-button-next-custom {
            right: -1rem;
        }

        @media (max-width: 1024px) {
            .swiper-button-prev-custom {
                left: 0.5rem;
            }

            .swiper-button-next-custom {
                right: 0.5rem;
            }
        }

        @media (max-width: 768px) {

            .swiper-button-prev-custom,
            .swiper-button-next-custom {
                display: none !important;
            }
        }

        .pricing-swiper .swiper-pagination-bullet {
            width: 10px;
            height: 10px;
            background: #cbd5e1;
            opacity: 1;
            transition: all 0.3s ease;
        }

        .pricing-swiper .swiper-pagination-bullet-active {
            background: #4c1d95;
            width: 24px;
            border-radius: 5px;
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

        /* ── UNIVERSAL RESPONSIVE STYLES (ALL HP & TABLET SIZES) ── */
        html,
        body {
            max-width: 100vw;
            overflow-x: hidden !important;
        }

        img,
        video,
        iframe,
        embed,
        object {
            max-width: 100%;
        }

        /* Ultra Small Mobile (320px - 380px: Galaxy Fold, iPhone SE, Small Androids) */
        @media (max-width: 380px) {

            .container,
            .max-w-7xl,
            .max-w-6xl,
            .max-w-5xl {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }

            h1 {
                font-size: 1.65rem !important;
                line-height: 1.25 !important;
            }

            h2 {
                font-size: 1.3rem !important;
                line-height: 1.25 !important;
            }

            .yellow-sticker-badge {
                padding: 4px 14px !important;
                font-size: 0.7rem !important;
            }

            .pricing-swiper .swiper-slide {
                width: 270px !important;
                max-width: 80vw !important;
            }
        }

        /* Standard Mobile Devices (381px - 480px: iPhone 12/13/14/15, Samsung Galaxy S) */
        @media (min-width: 381px) and (max-width: 480px) {
            .pricing-swiper .swiper-slide {
                width: 290px !important;
                max-width: 84vw !important;
            }
        }

        /* Tablets & Foldables Portrait (640px - 1024px: iPad Mini, iPad Air, Galaxy Tab) */
        @media (min-width: 640px) and (max-width: 1024px) {
            .pricing-swiper .swiper-slide {
                width: 340px !important;
            }
        }

        /* ================= CUSTOM DIAGONAL PAGE CURL EFFECT ================= */
        .book-perspective {
            perspective: 2200px;
        }

        #book {
            position: relative;
            width: 100%;
            aspect-ratio: 7 / 10;
            border-radius: 0.75rem;
            overflow: hidden !important;
            transform-style: preserve-3d;
        }

        /* Leaf styles for page container */
        .leaf {
            position: absolute;
            inset: 0;
            border-radius: 0.75rem;
            overflow: hidden;
            backface-visibility: hidden;
            transform-style: preserve-3d;
            transition: transform 1.2s cubic-bezier(0.2, 0.8, 0.2, 1),
                clip-path 1.2s cubic-bezier(0.2, 0.8, 0.2, 1),
                opacity 1.2s cubic-bezier(0.2, 0.8, 0.2, 1);
            will-change: transform, clip-path, opacity;
        }

        /* Front leaf starts flat and covers the page */
        .leaf.front {
            z-index: 10;
            clip-path: polygon(0% 0%, 200% 0%, 0% 200%);
            transform: translate3d(0, 0, 0);
        }

        /* Back leaf starts underneath and scaled down slightly for depth */
        .leaf.back {
            z-index: 5;
            transform: scale(0.96) translate3d(0, 0, 0);
            opacity: 0.9;
        }

        /* Flipped state: Front page container is clipped but stays flat for alignment */
        .flipped .leaf.front {
            clip-path: polygon(0% 0%, 0% 0%, 0% 0%);
            transform: translate3d(0, 0, 0);
            opacity: 1;
            /* Keep solid opacity for physical paper look */
        }

        /* Flipped state: Back page active, scales up and centers */
        .flipped .leaf.back {
            transform: scale(1) translate3d(0, 0, 0);
            opacity: 1;
        }

        /* Image movement for physical sliding effect */
        .leaf img {
            transition: transform 1.2s cubic-bezier(0.2, 0.8, 0.2, 1);
            will-change: transform;
        }

        /* Front page image slides to top-left when flipped */
        .flipped .leaf.front img {
            transform: translate3d(-18%, -18%, 0) scale(1.06);
        }

        /* Back page image starts offset to bottom-right and slides to center when flipped */
        .leaf.back img {
            transform: translate3d(15%, 15%, 0) scale(1.06);
        }

        .flipped .leaf.back img {
            transform: translate3d(0, 0, 0) scale(1);
        }

        /* The Page Curl Roll & Shadow Effect */
        .page-curl {
            position: absolute;
            width: 90px;
            height: 250%;
            top: 120%;
            left: 120%;
            transform: translate(-50%, -50%) rotate(-45deg);
            transform-origin: center;
            pointer-events: none;
            z-index: 15;
            transition: all 1.2s cubic-bezier(0.2, 0.8, 0.2, 1);
            will-change: top, left, transform;
            /* Soft, natural crease shadow (no white highlights/bling bling) */
            background: linear-gradient(to right,
                    rgba(0, 0, 0, 0) 0%,
                    rgba(0, 0, 0, 0.06) 20%,
                    rgba(0, 0, 0, 0.32) 50%,
                    /* Peak crease shadow */
                    rgba(0, 0, 0, 0.06) 80%,
                    rgba(0, 0, 0, 0) 100%);
        }

        /* Flipped state: the page curl sweeps to the top-left */
        .flipped .page-curl {
            top: -20%;
            left: -20%;
            transform: translate(-50%, -50%) rotate(-45deg);
        }
    </style>

    @php
        $resolveFacilityImage = function (string $baseName, string $fallbackPath, string $title, string $alt, string $sourceLabel) {
            $possibleExts = ['jpg', 'jpeg', 'png', 'webp'];

            foreach ($possibleExts as $ext) {
                $path = public_path("uploads/landing/{$baseName}.{$ext}");
                if (file_exists($path)) {
                    return [
                        'title' => $title,
                        'alt' => $alt,
                        'url' => asset("uploads/landing/{$baseName}.{$ext}") . '?v=' . filemtime($path),
                        'source' => $sourceLabel,
                    ];
                }
            }

            $fallbackFullPath = public_path($fallbackPath);
            if (file_exists($fallbackFullPath)) {
                return [
                    'title' => $title,
                    'alt' => $alt,
                    'url' => asset($fallbackPath),
                    'source' => 'Default image',
                ];
            }

            return null;
        };

        $galleryItems = [];

        foreach (
            [
                ['base' => 'fasilitas_kelas', 'fallback' => 'images/fasilitas-kelas.jpg', 'title' => 'Ruang Kelas Ber-AC & WiFi', 'alt' => 'Ruang Kelas Paradise of Math', 'source' => 'Fasilitas utama'],
                ['base' => 'fasilitas_toilet', 'fallback' => 'images/fasilitas-toilet.jpg', 'title' => 'Toilet Bersih & Higienis', 'alt' => 'Toilet Bersih Paradise of Math', 'source' => 'Fasilitas utama'],
                ['base' => 'fasilitas_mushala', 'fallback' => 'images/fasilitas-mushala.jpg', 'title' => 'Mushala Luas & Suci', 'alt' => 'Mushala Paradise of Math', 'source' => 'Fasilitas utama'],
                ['base' => 'fasilitas_gedung', 'fallback' => 'images/fasilitas-gedung.jpg', 'title' => 'Gedung & Lingkungan Belajar', 'alt' => 'Gedung Bimbel Paradise of Math', 'source' => 'Fasilitas utama'],
            ] as $item
        ) {
            $resolved = $resolveFacilityImage($item['base'], $item['fallback'], $item['title'], $item['alt'], $item['source']);
            if ($resolved) {
                $galleryItems[] = $resolved;
            }
        }

        $galleryExtraDir = public_path('uploads/landing/galeri');
        if (file_exists($galleryExtraDir)) {
            $galleryExtraFiles = glob($galleryExtraDir . '/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE) ?: [];
            usort($galleryExtraFiles, function ($a, $b) {
                return filemtime($b) <=> filemtime($a);
            });

            foreach ($galleryExtraFiles as $file) {
                $basename = basename($file);
                $galleryItems[] = [
                    'title' => pathinfo($basename, PATHINFO_FILENAME),
                    'alt' => 'Foto galeri tambahan Paradise of Math',
                    'url' => asset("uploads/landing/galeri/{$basename}") . '?v=' . filemtime($file),
                    'source' => 'Upload admin',
                ];
            }
        }
    @endphp
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
                        Paradise <span class="text-violet-700">Of </span> Math
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
                        <a href="#biaya" class="relative py-1 hover:text-violet-800 transition-colors group">
                            Biaya
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
                        <div class="relative">
                            <button id="profile-dropdown-btn"
                                class="flex items-center gap-2 px-4 py-2 rounded-xl bg-violet-50 border border-violet-100 hover:bg-violet-100 hover:border-violet-200 transition duration-200">
                                <!-- Icon -->
                                <div
                                    class="w-8 h-8 rounded-full bg-violet-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <!-- Name -->
                                <span
                                    class="text-sm font-bold text-violet-950 max-w-[120px] truncate">{{ $user->name }}</span>
                                <!-- Arrow -->
                                <svg id="profile-dropdown-arrow"
                                    class="w-4 h-4 text-violet-900 transition-transform duration-200" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="profile-dropdown-menu"
                                class="absolute right-0 mt-2 w-48 bg-white border border-violet-100 rounded-2xl shadow-xl py-2 hidden transition duration-200 animate-fadeIn z-50">
                                <div class="px-4 py-2 border-b border-violet-50">
                                    <p class="text-xs text-slate-400 font-medium">Masuk sebagai</p>
                                    <p class="text-xs font-bold text-violet-950 capitalize">
                                        {{ auth()->guard('siswa')->check() ? 'Siswa' : $user->role }}
                                    </p>
                                </div>
                                <a href="{{ $dashboardRoute }}"
                                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-violet-50 hover:text-violet-900 transition duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    Dashboard
                                </a>
                                <hr class="border-violet-50 my-1">
                                <form action="{{ route('logout') }}" method="POST" class="block w-full">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50 transition duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
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
                    <li><a href="#biaya"
                            class="block py-2 px-3 rounded-lg hover:bg-violet-50 hover:text-violet-900 transition-colors">Biaya</a>
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
                                <div
                                    class="w-8 h-8 rounded-full bg-violet-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="truncate">
                                    <p class="text-xs font-bold text-violet-950">{{ $user->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium capitalize">
                                        {{ auth()->guard('siswa')->check() ? 'Siswa' : $user->role }}
                                    </p>
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
                    <a href="{{ route('login') }}"
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

                    <a href="#Youtube"
                        class="px-7 py-3.5 rounded-xl backdrop-blur-md bg-white/10 border-2 border-white/30 text-white font-extrabold hover:bg-white hover:text-violet-950 hover:scale-105 active:scale-95 transition-all duration-300">
                        Pelajari Lebih Lanjut
                    </a>
                </div>

            </div>

            <!-- Kanan (gambar / illustration with floating cards) -->
            <div class="flex justify-center relative reveal-element delay-200" id="Youtube">
                <div class="relative w-full max-w-xs sm:max-w-sm">

                    <!-- Glow effect behind image -->
                    <div
                        class="absolute -inset-2 rounded-3xl bg-gradient-to-r from-amber-400 to-violet-500 opacity-40 blur-xl animate-pulse-glow">
                    </div>

                    <!-- Main Hero Image Frame (Auto-rotating slider) -->
                    <div
                        class="relative aspect-[4/5] rounded-3xl bg-gradient-to-b from-violet-800/90 to-violet-950/90 border-2 border-white/20 shadow-2xl p-2 flex flex-col items-center justify-center text-center overflow-hidden animate-float">
                        @php
                            $heroDir = public_path('uploads/landing/hero');
                            $heroFiles = glob($heroDir . '/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE) ?: [];

                            usort($heroFiles, function ($a, $b) {
                                return filemtime($b) - filemtime($a);
                            });

                            $heroImagesList = [];
                            foreach ($heroFiles as $hf) {
                                $bn = basename($hf);
                                $heroImagesList[] = asset("uploads/landing/hero/{$bn}") . '?v=' . filemtime($hf);
                            }

                            // Fallback single file check if list empty
                            if (empty($heroImagesList)) {
                                foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                                    $fp = public_path("uploads/landing/hero_image.{$ext}");
                                    if (file_exists($fp)) {
                                        $heroImagesList[] = asset("uploads/landing/hero_image.{$ext}") . '?v=' . filemtime($fp);
                                        break;
                                    }
                                }
                            }
                        @endphp

                        @if(count($heroImagesList) > 0)
                            <div class="relative w-full h-full overflow-hidden rounded-[22px]" id="landingHeroSlider">
                                @foreach($heroImagesList as $idx => $imgUrl)
                                    <img src="{{ $imgUrl }}" alt="Paradise of Math Hero Image {{ $idx + 1 }}"
                                        class="landing-hero-item absolute top-0 left-0 w-full h-full object-cover rounded-[22px] shadow-md transition-opacity duration-700 ease-in-out"
                                        style="opacity: {{ $idx === 0 ? '1' : '0' }}; z-index: {{ $idx === 0 ? '10' : '1' }};" />
                                @endforeach
                            </div>
                            @if(count($heroImagesList) > 1)
                                <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-1.5 z-20"
                                    id="landingHeroDots">
                                    @foreach($heroImagesList as $idx => $imgUrl)
                                        <span
                                            class="landing-hero-dot w-2 h-2 rounded-full bg-white shadow-sm transition-all duration-300"
                                            style="opacity: {{ $idx === 0 ? '1' : '0.4' }};"></span>
                                    @endforeach
                                </div>
                            @endif
                        @elseif(file_exists(public_path('images/hero-student.jpg')))
                            <img src="{{ asset('images/hero-student.jpg') }}" alt="Paradise of Math Hero Student"
                                class="w-full h-full object-cover rounded-[22px] shadow-md" />
                        @else
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
                        @endif
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

    <!-- ================= YOUTUBE TUTORIAL & PREVIEW SECTION ================= -->
    <section id="youtube-tutorial"
        class="py-12 sm:py-20 md:py-24 bg-gradient-to-b from-violet-950 via-slate-900 to-violet-950 text-white relative overflow-hidden">
        <!-- Ambient Glow Backgrounds -->
        <div
            class="absolute top-1/4 -left-32 w-80 sm:w-96 h-80 sm:h-96 bg-red-600/15 rounded-full blur-3xl pointer-events-none">
        </div>
        <div
            class="absolute bottom-1/4 -right-32 w-80 sm:w-96 h-80 sm:h-96 bg-violet-600/20 rounded-full blur-3xl pointer-events-none">
        </div>

        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 relative z-10">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-14 reveal-element">
                <span
                    class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-red-500/10 border border-red-500/30 text-red-400 text-xs sm:text-sm font-extrabold tracking-wide uppercase shadow-sm mb-3 sm:mb-4">
                    <i class="fab fa-youtube text-sm sm:text-base text-red-500"></i> YouTube Tutorial &amp; Preview
                    Belajar
                </span>
                <h2 class="text-xl sm:text-3xl md:text-5xl font-black text-white tracking-tight leading-tight">
                    Tonton Serunya Belajar &amp; <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-amber-400 to-amber-500">Trik
                    </span>
                </h2>
                <p class="mt-2.5 sm:mt-4 text-xs sm:text-base text-violet-200/80 leading-relaxed font-medium px-2">
                    Pelajari teknik cepat berhitung, pembahasan soal ujian, dan suasana kelas bimbingan interaktif
                    bersama para master tutor Paradise of Math.
                </p>
            </div>

            @php
                $hasLinks = isset($youtubeLinks) && count($youtubeLinks) > 0;
                $firstVideo = $hasLinks ? $youtubeLinks->first() : null;
                $firstId = $hasLinks ? $firstVideo->youtube_id : '5p2g1YVwSxE';
                $firstTitle = $hasLinks ? $firstVideo->judul : '🚀 Trik Cepat Hitung Matematika & Pembahasan Soal';
                $firstDesc = $hasLinks ? ($firstVideo->deskripsi ?: 'Video tutorial eksklusif penjelasan konsep matematika secara simpel dan menyenangkan.') : 'Video tutorial eksklusif penjelasan konsep matematika secara simpel dan menyenangkan.';
                $firstUrl = $hasLinks ? $firstVideo->youtube_url : 'https://www.youtube.com';
            @endphp

            <!-- Featured Video Player & Preview Cards Grid -->
            <div class="grid lg:grid-cols-12 gap-6 sm:gap-8 items-start reveal-element delay-100">
                <!-- Main Featured Player Container (8 cols on lg) -->
                <div
                    class="lg:col-span-8 bg-violet-900/40 backdrop-blur-xl border border-violet-700/50 rounded-2xl sm:rounded-3xl p-2.5 sm:p-5 shadow-2xl relative group">
                    <div class="relative w-full aspect-video rounded-xl sm:rounded-2xl overflow-hidden bg-black shadow-inner border border-white/10"
                        id="yt-featured-container">
                        <iframe id="main-yt-iframe" class="w-full h-full rounded-xl sm:rounded-2xl"
                            src="https://www.youtube-nocookie.com/embed/{{ $firstId }}?autoplay=0&rel=0"
                            title="Paradise of Math YouTube Tutorial Preview" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>
                    </div>

                    <!-- Video Details Banner -->
                    <div
                        class="mt-3 sm:mt-4 px-1 sm:px-4 py-2 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4 border-t border-white/10 pt-3 sm:pt-4">
                        <div class="min-w-0 flex-1">
                            <h3 id="yt-featured-title"
                                class="text-sm sm:text-xl font-extrabold text-white leading-snug">
                                {{ $firstTitle }}
                            </h3>
                            <p id="yt-featured-desc" class="text-xs sm:text-sm text-violet-200/70 mt-1 leading-relaxed">
                                {{ $firstDesc }}
                            </p>
                        </div>
                        <a id="yt-featured-link" href="{{ $firstUrl }}" target="_blank" rel="noopener noreferrer"
                            class="w-full sm:w-auto shrink-0 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-red-600/30 hover:scale-105 active:scale-95 transition-all duration-300">
                            <i class="fab fa-youtube text-base sm:text-lg"></i>
                            <span>Buka di YouTube</span>
                        </a>
                    </div>
                </div>

                <!-- Playlist / Video Preview Selector List (4 cols on lg) -->
                <div class="lg:col-span-4 flex flex-col gap-3 sm:gap-4">
                    <div class="flex items-center justify-between px-1">
                        <h4
                            class="text-xs sm:text-sm font-extrabold text-amber-300 uppercase tracking-wider flex items-center gap-2">
                            <i class="fas fa-play-circle text-red-500"></i> Video Pilihan Tutorial
                        </h4>
                        <span
                            class="text-[11px] sm:text-xs text-violet-300 font-semibold">{{ $hasLinks ? count($youtubeLinks) : 3 }}
                            Video</span>
                    </div>

                    @if($hasLinks)
                        @foreach($youtubeLinks as $idx => $link)
                            <button type="button"
                                onclick="switchYtVideo('{{ $link->youtube_id }}', '{{ addslashes($link->judul) }}', '{{ addslashes($link->deskripsi ?: '') }}', '{{ addslashes($link->youtube_url) }}', this)"
                                class="yt-playlist-item text-left p-2.5 sm:p-3.5 rounded-2xl {{ $idx === 0 ? 'bg-white/10 border-2 border-amber-400/80' : 'bg-white/5 border border-white/10 hover:border-amber-400/50' }} shadow-md transition-all duration-300 flex gap-2.5 sm:gap-3.5 items-center group cursor-pointer">
                                <div
                                    class="relative w-20 sm:w-28 aspect-video rounded-xl overflow-hidden bg-violet-950 shrink-0 border border-white/20">
                                    <img src="https://img.youtube.com/vi/{{ $link->youtube_id }}/hqdefault.jpg"
                                        alt="{{ $link->judul }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" />
                                    <div
                                        class="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/20 transition-colors">
                                        <span
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-red-600 text-white flex items-center justify-center text-[10px] sm:text-xs shadow-md"><i
                                                class="fas fa-play ml-0.5"></i></span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span
                                        class="px-2 py-0.5 rounded bg-amber-400/20 text-amber-300 text-[9px] sm:text-[10px] font-black uppercase tracking-wider">{{ $link->kategori }}</span>
                                    <h5 class="text-xs sm:text-sm font-extrabold text-white truncate mt-1">{{ $link->judul }}
                                    </h5>
                                    <p class="text-[10px] sm:text-[11px] text-violet-200/70 truncate mt-0.5">
                                        {{ $link->deskripsi ?: 'Tonton video tutorial selengkapnya' }}
                                    </p>
                                </div>
                            </button>
                        @endforeach
                    @else
                        <!-- Video Item 1 (Active by default) -->
                        <button type="button"
                            onclick="switchYtVideo('5p2g1YVwSxE', '🚀 Trik Cepat Hitung Matematika & Pembahasan Soal', 'Video tutorial eksklusif penjelasan konsep matematika secara simpel dan menyenangkan.', 'https://www.youtube.com', this)"
                            class="yt-playlist-item text-left p-2.5 sm:p-3.5 rounded-2xl bg-white/10 hover:bg-white/15 border-2 border-amber-400/80 shadow-md transition-all duration-300 flex gap-2.5 sm:gap-3.5 items-center group cursor-pointer">
                            <div
                                class="relative w-20 sm:w-28 aspect-video rounded-xl overflow-hidden bg-violet-950 shrink-0 border border-white/20">
                                <img src="https://img.youtube.com/vi/5p2g1YVwSxE/hqdefault.jpg" alt="Preview Video 1"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" />
                                <div
                                    class="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/20 transition-colors">
                                    <span
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-red-600 text-white flex items-center justify-center text-[10px] sm:text-xs shadow-md"><i
                                            class="fas fa-play ml-0.5"></i></span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span
                                    class="px-2 py-0.5 rounded bg-amber-400/20 text-amber-300 text-[9px] sm:text-[10px] font-black uppercase tracking-wider">Trik
                                    Hitung</span>
                                <h5 class="text-xs sm:text-sm font-extrabold text-white truncate mt-1">Trik Cepat Cerdas
                                    Matematika</h5>
                                <p class="text-[10px] sm:text-[11px] text-violet-200/70 truncate mt-0.5">Pembahasan konsep
                                    dasar &amp;
                                    metode kilat</p>
                            </div>
                        </button>

                        <!-- Video Item 2 -->
                        <button type="button"
                            onclick="switchYtVideo('_uQrJ0TkZlc', '📐 Kuasai Rumus Aljabar & Geometri Mudah', 'Panduan langkah demi langkah memahami materi aljabar dan bangun ruang tanpa hafal rumus mati.', 'https://www.youtube.com', this)"
                            class="yt-playlist-item text-left p-2.5 sm:p-3.5 rounded-2xl bg-white/5 hover:bg-white/15 border border-white/10 hover:border-amber-400/50 shadow-md transition-all duration-300 flex gap-2.5 sm:gap-3.5 items-center group cursor-pointer">
                            <div
                                class="relative w-20 sm:w-28 aspect-video rounded-xl overflow-hidden bg-violet-950 shrink-0 border border-white/20">
                                <img src="https://img.youtube.com/vi/_uQrJ0TkZlc/hqdefault.jpg" alt="Preview Video 2"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" />
                                <div
                                    class="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/20 transition-colors">
                                    <span
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-red-600 text-white flex items-center justify-center text-[10px] sm:text-xs shadow-md"><i
                                            class="fas fa-play ml-0.5"></i></span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span
                                    class="px-2 py-0.5 rounded bg-violet-500/20 text-violet-300 text-[9px] sm:text-[10px] font-black uppercase tracking-wider">Aljabar
                                    &amp; Bangun Ruang</span>
                                <h5 class="text-xs sm:text-sm font-extrabold text-white truncate mt-1">Kuasai Aljabar Tanpa
                                    Stres
                                </h5>
                                <p class="text-[10px] sm:text-[11px] text-violet-200/70 truncate mt-0.5">Metode visual &amp;
                                    contoh
                                    penerapan soal</p>
                            </div>
                        </button>

                        <!-- Video Item 3 -->
                        <button type="button"
                            onclick="switchYtVideo('3JZ_D3ELwOQ', '🎯 Strategi Lolos Ujian & Bedah Soal PTN', 'Kupas tuntas soal-soal latihan ujian sekolah dan tryout persiapan jenjang SD, SMP, SMA.', 'https://www.youtube.com', this)"
                            class="yt-playlist-item text-left p-2.5 sm:p-3.5 rounded-2xl bg-white/5 hover:bg-white/15 border border-white/10 hover:border-amber-400/50 shadow-md transition-all duration-300 flex gap-2.5 sm:gap-3.5 items-center group cursor-pointer">
                            <div
                                class="relative w-20 sm:w-28 aspect-video rounded-xl overflow-hidden bg-violet-950 shrink-0 border border-white/20">
                                <img src="https://img.youtube.com/vi/3JZ_D3ELwOQ/hqdefault.jpg" alt="Preview Video 3"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" />
                                <div
                                    class="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/20 transition-colors">
                                    <span
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-red-600 text-white flex items-center justify-center text-[10px] sm:text-xs shadow-md"><i
                                            class="fas fa-play ml-0.5"></i></span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span
                                    class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-[9px] sm:text-[10px] font-black uppercase tracking-wider">Bedah
                                    Soal</span>
                                <h5 class="text-xs sm:text-sm font-extrabold text-white truncate mt-1">Persiapan Ujian &amp;
                                    Tryout</h5>
                                <p class="text-[10px] sm:text-[11px] text-violet-200/70 truncate mt-0.5">Strategi pengerjaan
                                    cepat &amp;
                                    akurat</p>
                            </div>
                        </button>
                    @endif
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

                <div class="w-full max-w-[450px] flex flex-col items-center justify-center relative">

                    <div class="w-full book-perspective">
                        <p class="text-center text-slate-500 text-xs mb-4 font-semibold select-none">klik tepi
                            kanan/kiri gambar, atau pakai tombol di bawah</p>

                        <div id="book" class="group">

                            <!-- front leaf -->
                            <div class="leaf front">
                                <img src="{{ asset('images/front.jpeg') }}" alt="Halaman 1"
                                    class="w-full h-full object-fill block select-none pointer-events-none">
                                <div
                                    class="absolute inset-0 bg-gradient-to-tr from-black/15 via-transparent to-transparent pointer-events-none">
                                </div>
                            </div>

                            <!-- back leaf -->
                            <div class="leaf back">
                                <img src="{{ asset('images/backside.jpeg') }}" alt="Halaman 2"
                                    class="w-full h-full object-fill block select-none pointer-events-none">
                                <div
                                    class="absolute inset-0 bg-gradient-to-tr from-black/15 via-transparent to-transparent pointer-events-none">
                                </div>
                            </div>

                            <!-- Page curl overlay -->
                            <div class="page-curl"></div>

                        </div>

                        <div class="flex items-center justify-center gap-4 mt-5">
                            <button id="prevBtn" aria-label="Sebelumnya"
                                class=" rounded-full text-[#1e1b2e] flex items-center justify-center border-none cursor-pointer shadow-[0_8px_20px_-6px_rgba(0,0,0,0.5)] transition-transform duration-150 ease-out hover:-translate-y-0.5 hover:scale-105 active:scale-95 focus:outline-none">
                            </button>

                            <div class="flex gap-1.5 items-center select-none">
                                <span data-p="0"
                                    class="dot active w-5 h-2 rounded-full bg-violet-900 transition-all duration-250 ease-out cursor-pointer"></span>
                                <span data-p="1"
                                    class="dot w-2 h-2 rounded-full bg-violet-900/30 transition-all duration-250 ease-out cursor-pointer"></span>
                            </div>

                            <button id="nextBtn" aria-label="Berikutnya"
                                class=" rounded-full text-[#1e1b2e] flex items-center justify-center border-none cursor-pointer shadow-[0_8px_20px_-6px_rgba(0,0,0,0.5)] transition-transform duration-150 ease-out hover:-translate-y-0.5 hover:scale-105 active:scale-95 focus:outline-none">
                            </button>
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
        <div class="w-96 h-96 bg-violet-200/40 rounded-full blur-3xl absolute -top-10 -left-20 pointer-events-none">
        </div>
        <div class="w-96 h-96 bg-amber-300/30 rounded-full blur-3xl absolute -bottom-10 -right-20 pointer-events-none">
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-14 reveal-element">
                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-violet-100 text-violet-900 font-extrabold text-xs sm:text-sm mb-4 shadow-sm">
                    ✨ FASILITAS LENGKAP & NYAMAN
                </div>

                <h2 class="font-black text-2xl sm:text-3xl md:text-4xl lg:text-5xl leading-tight mb-4 tracking-tight">
                    <span
                        class="yellow-sticker-badge shadow-2xl transform -rotate-1 hover:rotate-0 transition-transform duration-300">
                        Belajar dengan Nyaman di Paradise of Math!
                    </span>
                </h2>

                <p class="text-slate-600 text-sm sm:text-base md:text-lg leading-relaxed font-normal">
                    Fasilitas modern dan lingkungan belajar yang kondusif disiapkan khusus untuk mendukung kenyamanan
                    maksimal siswa.
                </p>

                <div class="mt-6">
                    <button type="button" id="gallery-view-all-btn"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-violet-950 text-white font-extrabold text-sm shadow-lg shadow-violet-950/20 hover:bg-violet-900 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300">
                        <i class="fas fa-images"></i>
                        Lihat Semua Foto ({{ count($galleryItems) }})
                    </button>
                </div>
            </div>

            <!-- Facilities Cards Grid (Asymmetrical / Staggered / Bento Layout - Mobile Responsive) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-5 sm:gap-6 md:gap-8 mb-16 items-stretch">

                <!-- Card 1: Kelas Yang Nyaman (+ AC & + WiFi) -->
                <div
                    class="sm:col-span-2 md:col-span-7 group relative rounded-3xl bg-white border-2 border-violet-800 p-3 sm:p-4 shadow-xl hover:shadow-2xl hover:-translate-y-2 transform md:-rotate-1 hover:rotate-0 transition-all duration-300 reveal-element flex flex-col justify-between">
                    <div
                        class="relative aspect-[16/10] sm:aspect-[16/9] rounded-2xl bg-violet-950 overflow-hidden flex flex-col items-center justify-center text-center p-1.5 sm:p-2">
                        @php
                            $imgKelas = null;
                            foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                                $fp = public_path("uploads/landing/fasilitas_kelas.{$ext}");
                                if (file_exists($fp)) {
                                    $imgKelas = asset("uploads/landing/fasilitas_kelas.{$ext}") . '?v=' . filemtime($fp);
                                    break;
                                }
                            }
                        @endphp

                        @if($imgKelas)
                            <img src="{{ $imgKelas }}" alt="Ruang Kelas Paradise of Math"
                                class="w-full h-full object-cover rounded-xl" />
                        @elseif(file_exists(public_path('images/fasilitas-kelas.jpg')))
                            <img src="{{ asset('images/fasilitas-kelas.jpg') }}" alt="Ruang Kelas Paradise of Math"
                                class="w-full h-full object-cover rounded-xl" />
                        @else
                            <span class="text-violet-200 font-bold text-xs sm:text-base px-2">
                                [ foto kelas ber-AC & WiFi ]
                            </span>
                            <span class="text-violet-400 text-[10px] sm:text-xs font-mono mt-1">
                                public/images/fasilitas-kelas.jpg
                            </span>
                        @endif

                        <!-- Badge Top Left (+ AC) -->
                        <div
                            class="absolute top-2.5 left-2.5 sm:top-3 sm:left-3 bg-violet-950/90 text-amber-300 font-black text-[10px] sm:text-xs px-2.5 py-1 sm:px-3.5 sm:py-1.5 rounded-xl border border-amber-300/40 shadow-lg backdrop-blur-md animate-pulse-glow">
                            + AC
                        </div>

                        <!-- Badge Bottom Right (+ WiFi) -->
                        <div
                            class="absolute bottom-2.5 right-2.5 sm:bottom-3 sm:right-3 bg-violet-950/90 text-amber-300 font-black text-[10px] sm:text-xs px-2.5 py-1 sm:px-3.5 sm:py-1.5 rounded-xl border border-amber-300/40 shadow-lg backdrop-blur-md">
                            + WiFi
                        </div>

                        <!-- Badge Bottom Left Tag (Kelas yang nyaman) -->
                        <div
                            class="absolute bottom-2.5 left-2.5 sm:bottom-3 sm:left-3 bg-amber-400 text-violet-950 font-black text-[10px] sm:text-xs px-2.5 py-1 sm:px-3.5 sm:py-1.5 rounded-xl shadow-md transform -rotate-1">
                            Kelas yang nyaman
                        </div>
                    </div>
                    <div class="p-3 sm:p-5 flex items-center justify-between flex-wrap gap-2 mt-2">
                        <div>
                            <h3 class="font-extrabold text-violet-950 text-sm sm:text-base md:text-lg">Ruang Kelas
                                Ber-AC &amp; High-Speed WiFi</h3>
                            <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Ruangan sejuk, pencahayaan optimal, dan
                                meja-kursi nyaman untuk fokus belajar.</p>
                        </div>
                        <span
                            class="px-2.5 py-1 bg-violet-100 text-violet-900 font-extrabold text-[10px] sm:text-xs rounded-full">★
                            Utama</span>
                    </div>
                </div>

                <!-- Card 2: Toilet Bersih -->
                <div
                    class="sm:col-span-1 md:col-span-5 group relative rounded-3xl bg-white border-2 border-violet-800 p-3 sm:p-4 shadow-xl hover:shadow-2xl hover:-translate-y-2 transform md:rotate-2 md:translate-y-6 hover:rotate-0 transition-all duration-300 reveal-element delay-100 flex flex-col justify-between">
                    <div
                        class="relative aspect-[4/3] rounded-2xl bg-violet-950 overflow-hidden flex flex-col items-center justify-center text-center p-1.5 sm:p-2">
                        @php
                            $imgToilet = null;
                            foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                                $fp = public_path("uploads/landing/fasilitas_toilet.{$ext}");
                                if (file_exists($fp)) {
                                    $imgToilet = asset("uploads/landing/fasilitas_toilet.{$ext}") . '?v=' . filemtime($fp);
                                    break;
                                }
                            }
                        @endphp

                        @if($imgToilet)
                            <img src="{{ $imgToilet }}" alt="Toilet Bersih Paradise of Math"
                                class="w-full h-full object-cover rounded-xl" />
                        @elseif(file_exists(public_path('images/fasilitas-toilet.jpg')))
                            <img src="{{ asset('images/fasilitas-toilet.jpg') }}" alt="Toilet Bersih Paradise of Math"
                                class="w-full h-full object-cover rounded-xl" />
                        @else
                            <span class="text-violet-200 font-bold text-xs sm:text-sm px-2">
                                [ foto toilet ]
                            </span>
                            <span class="text-violet-400 text-[10px] font-mono mt-1">
                                public/images/fasilitas-toilet.jpg
                            </span>
                        @endif

                        <!-- Badge Top Center Tag (Toilet Bersih) -->
                        <div
                            class="absolute top-2.5 left-1/2 -translate-x-1/2 bg-amber-400 text-violet-950 font-black text-[10px] sm:text-xs px-3 py-1 sm:px-4 sm:py-1.5 rounded-xl shadow-md transform rotate-1 whitespace-nowrap">
                            Toilet Bersih
                        </div>
                    </div>
                    <div class="p-3 sm:p-4 mt-2">
                        <h3 class="font-extrabold text-violet-950 text-sm sm:text-base mb-1">Toilet Bersih &amp;
                            Higienis</h3>
                        <p class="text-slate-500 text-xs sm:text-sm">Fasilitas sanitasi yang selalu terawat dan bersih
                            demi kenyamanan setiap hari.</p>
                    </div>
                </div>

                <!-- Card 3: Mushala Luas -->
                <div
                    class="sm:col-span-1 md:col-span-5 group relative rounded-3xl bg-white border-2 border-violet-800 p-3 sm:p-4 shadow-xl hover:shadow-2xl hover:-translate-y-2 transform md:-rotate-2 md:-translate-y-2 hover:rotate-0 transition-all duration-300 reveal-element delay-200 flex flex-col justify-between">
                    <div
                        class="relative aspect-[4/3] rounded-2xl bg-violet-950 overflow-hidden flex flex-col items-center justify-center text-center p-1.5 sm:p-2">
                        @php
                            $imgMushala = null;
                            foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                                $fp = public_path("uploads/landing/fasilitas_mushala.{$ext}");
                                if (file_exists($fp)) {
                                    $imgMushala = asset("uploads/landing/fasilitas_mushala.{$ext}") . '?v=' . filemtime($fp);
                                    break;
                                }
                            }
                        @endphp

                        @if($imgMushala)
                            <img src="{{ $imgMushala }}" alt="Mushala Paradise of Math"
                                class="w-full h-full object-cover rounded-xl" />
                        @elseif(file_exists(public_path('images/fasilitas-mushala.jpg')))
                            <img src="{{ asset('images/fasilitas-mushala.jpg') }}" alt="Mushala Paradise of Math"
                                class="w-full h-full object-cover rounded-xl" />
                        @else
                            <span class="text-violet-200 font-bold text-xs sm:text-sm px-2">
                                [ foto mushala ]
                            </span>
                            <span class="text-violet-400 text-[10px] font-mono mt-1">
                                public/images/fasilitas-mushala.jpg
                            </span>
                        @endif

                        <!-- Badge Bottom Left Tag (Mushala Luas) -->
                        <div
                            class="absolute bottom-2.5 left-2.5 sm:bottom-3 sm:left-3 bg-amber-400 text-violet-950 font-black text-[10px] sm:text-xs px-2.5 py-1 sm:px-3.5 sm:py-1.5 rounded-xl shadow-md transform -rotate-2">
                            Mushala Luas
                        </div>
                    </div>
                    <div class="p-3 sm:p-4 mt-2">
                        <h3 class="font-extrabold text-violet-950 text-sm sm:text-base mb-1">Mushala Luas &amp; Suci
                        </h3>
                        <p class="text-slate-500 text-xs sm:text-sm">Tempat ibadah yang tenang, harum, dan luas agar
                            siswa beribadah tepat waktu.</p>
                    </div>
                </div>

                <!-- Card 4: Pigura Foto Gedung / Rumah Bimbel -->
                @php
                    $imgGedung = null;
                    foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                        $fp = public_path("uploads/landing/fasilitas_gedung.{$ext}");
                        if (file_exists($fp)) {
                            $imgGedung = asset("uploads/landing/fasilitas_gedung.{$ext}") . '?v=' . filemtime($fp);
                            break;
                        }
                    }
                    if (!$imgGedung && file_exists(public_path('images/fasilitas-gedung.jpg'))) {
                        $imgGedung = asset('images/fasilitas-gedung.jpg');
                    }
                @endphp
                <div
                    class="sm:col-span-2 md:col-span-7 group relative rounded-3xl bg-white border-2 border-violet-800 p-3 sm:p-4 shadow-xl hover:shadow-2xl hover:-translate-y-2 transform md:rotate-1 hover:rotate-0 transition-all duration-300 reveal-element delay-300 flex flex-col justify-between">
                    <div
                        class="relative aspect-[16/10] sm:aspect-[16/9] rounded-2xl bg-violet-950 overflow-hidden flex flex-col items-center justify-center text-center p-1.5 sm:p-2">
                        @if($imgGedung)
                            <img src="{{ $imgGedung }}" alt="Gedung Bimbel Paradise of Math"
                                class="w-full h-full object-cover rounded-xl" />
                        @else
                            <span class="text-violet-200 font-bold text-xs sm:text-base px-2">
                                [ foto gedung/rumah bimbel ]
                            </span>
                            <span class="text-violet-400 text-[10px] sm:text-xs font-mono mt-1">
                                public/images/fasilitas-gedung.jpg
                            </span>
                        @endif

                        <!-- Badge Top Right Tag (Gedung Bimbel) -->
                        <div
                            class="absolute top-2.5 right-2.5 sm:top-3 sm:right-3 bg-amber-400 text-violet-950 font-black text-[10px] sm:text-xs px-2.5 py-1 sm:px-3.5 sm:py-1.5 rounded-xl shadow-md transform rotate-1">
                            Gedung Bimbel
                        </div>

                        <!-- Badge Bottom Left Tag (Lingkungan Kondusif) -->
                        <div
                            class="absolute bottom-2.5 left-2.5 sm:bottom-3 sm:left-3 bg-violet-950/90 text-amber-300 font-black text-[10px] sm:text-xs px-2.5 py-1 sm:px-3.5 sm:py-1.5 rounded-xl border border-amber-300/40 shadow-lg backdrop-blur-md">
                            Lingkungan Kondusif
                        </div>
                    </div>
                    <div class="p-3 sm:p-5 flex items-center justify-between flex-wrap gap-2 mt-2">
                        <div>
                            <h3 class="font-extrabold text-violet-950 text-sm sm:text-base md:text-lg">Gedung &amp;
                                Lingkungan Belajar</h3>
                            <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Lokasi bimbingan belajar yang sejuk,
                                tenang, dan bebas bising untuk kenyamanan belajar.</p>
                        </div>
                        <span
                            class="px-2.5 py-1 bg-amber-100 text-amber-900 font-extrabold text-[10px] sm:text-xs rounded-full">🏠
                            Lokasi</span>
                    </div>
                </div>
            </div>

            <!-- ================= BANNER FOTO GURU & SERTIFIKAT AUTO-SLIDER SECTION ================= -->
            <div id="banner-guru-section" class="mt-14 mb-14 reveal-element">
                <div
                    class="relative w-full rounded-3xl overflow-hidden border border-slate-700/60 shadow-2xl group bg-slate-950">
                    @php
                        $guruBannerFiles = glob(public_path('uploads/landing/guru_banner*.*')) ?: [];
                        if (empty($guruBannerFiles)) {
                            $guruBannerFiles = glob(public_path('uploads/guru/*.*')) ?: [];
                        }
                        if (empty($guruBannerFiles)) {
                            $guruBannerFiles = glob(public_path('uploads/landing/galeri/*.*')) ?: [];
                        }

                        $guruBannerImagesList = [];
                        foreach ($guruBannerFiles as $gbf) {
                            $bn = basename($gbf);
                            $dirName = str_contains($gbf, 'uploads/guru') ? 'guru' : (str_contains($gbf, 'uploads/landing/galeri') ? 'landing/galeri' : 'landing');
                            $guruBannerImagesList[] = asset("uploads/{$dirName}/{$bn}") . '?v=' . filemtime($gbf);
                        }
                    @endphp

                    <!-- Banner Image Slider Container -->
                    <div class="relative w-full h-[300px] sm:h-[380px] md:h-[430px] lg:h-[480px] overflow-hidden"
                        id="bannerGuruSlider">
                        @if(count($guruBannerImagesList) > 0)
                            @foreach($guruBannerImagesList as $idx => $imgUrl)
                                <img src="{{ $imgUrl }}" alt="Foto Tim Pengajar & Sertifikat {{ $idx + 1 }}"
                                    class="banner-guru-item absolute top-0 left-0 w-full h-full object-cover transition-opacity duration-700 ease-in-out"
                                    style="opacity: {{ $idx === 0 ? '1' : '0' }}; z-index: {{ $idx === 0 ? '10' : '1' }};" />
                            @endforeach
                        @else
                            <!-- Default Fallback Banner Slides if no uploaded image files yet -->
                            <div class="banner-guru-item absolute top-0 left-0 w-full h-full bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 flex flex-col items-center justify-center p-6 text-center transition-opacity duration-700 ease-in-out"
                                style="opacity: 1; z-index: 10;">
                                <div
                                    class="w-20 h-20 rounded-2xl bg-amber-400/20 border border-amber-300/40 text-amber-300 flex items-center justify-center text-3xl mb-3 shadow-xl backdrop-blur-md">
                                    🎓</div>
                                <span
                                    class="text-amber-300 font-extrabold text-xs sm:text-sm uppercase tracking-widest mb-1">Master
                                    Tutor Kak Ika, S.Pd.</span>
                                <h3 class="text-xl sm:text-3xl font-black text-white max-w-xl leading-tight">Pimpinan &amp;
                                    Master Tutor Matematika</h3>
                            </div>
                            <div class="banner-guru-item absolute top-0 left-0 w-full h-full bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 flex flex-col items-center justify-center p-6 text-center transition-opacity duration-700 ease-in-out"
                                style="opacity: 0; z-index: 1;">
                                <div
                                    class="w-20 h-20 rounded-2xl bg-purple-400/20 border border-purple-300/40 text-purple-200 flex items-center justify-center text-3xl mb-3 shadow-xl backdrop-blur-md">
                                    ⭐</div>
                                <span
                                    class="text-amber-300 font-extrabold text-xs sm:text-sm uppercase tracking-widest mb-1">Co
                                    Master Tutor Kak Angel &amp; Kak Sofia</span>
                                <h3 class="text-xl sm:text-3xl font-black text-white max-w-xl leading-tight">Spesialisasi
                                    Matematika &amp; Bahasa Inggris</h3>
                            </div>
                        @endif

                        <!-- Neutral Dark Bottom Overlay Gradient (NO PURPLE TINT) for crisp text contrast -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent pointer-events-none z-10">
                        </div>

                        <!-- Top Floating Badges -->
                        <div class="absolute top-4 left-4 sm:top-6 sm:left-6 flex flex-wrap items-center gap-2 z-20">
                            <span
                                class="px-3 py-1.5 rounded-xl bg-amber-400 text-slate-950 font-black text-xs sm:text-sm shadow-lg transform -rotate-1">
                                🎓 Tim Pengajar &amp; Master Tutor
                            </span>
                            <span
                                class="px-3 py-1.5 rounded-xl bg-slate-900/90 text-amber-300 font-extrabold text-xs border border-amber-300/40 shadow-lg backdrop-blur-md">
                                📜 Sertifikat Terverifikasi
                            </span>
                        </div>

                        <!-- Bottom Banner Content & Slide Indicators -->
                        <div
                            class="absolute bottom-4 left-4 right-4 sm:bottom-6 sm:left-6 sm:right-6 z-20 flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4">
                            <div class="max-w-2xl">
                                <h3
                                    class="text-lg sm:text-2xl md:text-3xl font-black text-white tracking-tight drop-shadow-md">
                                    Pengajar Profesional &amp; Bersertifikasi Master
                                </h3>
                                <p
                                    class="text-slate-200 text-xs sm:text-sm mt-1.5 font-medium leading-relaxed drop-shadow">
                                    Kak Ika, S.Pd. (Master Tutor), Kak Angel, dan Kak Sofia membimbing siswa dengan
                                    pendekatan personal, metode cepat, dan kurikulum terbaik.
                                </p>
                            </div>

                            <!-- Dots Indicator for Auto-Slider -->
                            <div class="flex items-center gap-2 shrink-0 bg-slate-900/80 px-3 py-1.5 rounded-full border border-white/20 backdrop-blur-md"
                                id="bannerGuruDots">
                                @php
                                    $slideCount = max(count($guruBannerImagesList), 2);
                                @endphp
                                @for($i = 0; $i < $slideCount; $i++)
                                    <span
                                        class="banner-guru-dot w-2.5 h-2.5 rounded-full bg-white transition-all duration-300 cursor-pointer"
                                        style="opacity: {{ $i === 0 ? '1' : '0.4' }};" data-slide-index="{{ $i }}"></span>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="reveal-element delay-300 rounded-3xl bg-gradient-to-r from-violet-950 via-violet-900 to-violet-950 text-white p-6 sm:p-8 shadow-2xl border border-violet-800">
                <div class="grid md:grid-cols-2 gap-6 items-center">

                    <!-- Social Media Links -->
                    <div class="flex flex-wrap items-center gap-4">
                        <a href="https://www.instagram.com/paradiseofmath/" target="_blank" rel="noopener noreferrer"
                            class="flex items-center gap-2.5 bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl border border-white/15 hover:bg-white/20 hover:border-pink-400/50 hover:scale-105 transition-all duration-300 group">
                            <span
                                class="w-5 h-5 flex items-center justify-center text-pink-400 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                                </svg>
                            </span>
                            <span
                                class="text-xs sm:text-sm font-extrabold text-amber-300 group-hover:text-white transition-colors">@paradiseofmath</span>
                        </a>
                        <a href="https://www.tiktok.com/@paradiseofmath?is_from_webapp=1&sender_device=pc"
                            target="_blank" rel="noopener noreferrer"
                            class="flex items-center gap-2.5 bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl border border-white/15 hover:bg-white/20 hover:border-cyan-400/50 hover:scale-105 transition-all duration-300 group">
                            <span
                                class="w-5 h-5 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                                <svg class="w-4.5 h-4.5 fill-current" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.02 1.59 4.23.86.97 2.07 1.62 3.39 1.83v3.71c-1.84-.02-3.61-.71-4.96-1.97-.24-.2-.46-.43-.66-.67v6.62c.04 2.64-1.28 5.12-3.51 6.47-2.3 1.48-5.3 1.68-7.74.52-2.73-1.22-4.48-4.22-4.26-7.22.18-3.08 2.37-5.78 5.37-6.5 1.09-.27 2.22-.24 3.29.07V10.7c-.89-.31-1.87-.33-2.76-.04-1.39.42-2.45 1.62-2.71 3.05-.33 1.62.43 3.3 1.88 4.09 1.34.78 3.1.66 4.31-.31.86-.68 1.35-1.74 1.33-2.84V.02z" />
                                </svg>
                            </span>
                            <span
                                class="text-xs sm:text-sm font-extrabold text-amber-300 group-hover:text-white transition-colors">@paradiseofmath</span>
                        </a>
                    </div>

                    <!-- Contact & Address -->
                    <div class="space-y-2 text-xs sm:text-sm text-violet-100">
                        <div class="flex items-start gap-2">
                            <span class="text-amber-400 font-bold shrink-0">📍 Alamat:</span>
                            <span>Jln. Jati I No.19 RT/RW 002/001 Sawahan, Padang Timur, Sumatera Barat, 25121</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-amber-400 font-bold shrink-0">📞 WhatsApp / Kontak:</span>
                            <span class="font-extrabold text-white">08116612050 (Pimpinan - Kak Ika)</span>
                        </div>
                    </div>

                </div>
            </div>

            <div id="facility-gallery-modal" class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6">
                <div class="absolute inset-0 bg-violet-950/80 backdrop-blur-md" data-facility-gallery-close></div>

                <div
                    class="relative w-full max-w-6xl overflow-hidden rounded-3xl bg-white shadow-2xl border border-violet-200">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-4 sm:px-6 py-4">
                        <div>
                            <p class="text-[11px] sm:text-xs font-black tracking-[0.2em] text-amber-500 uppercase mb-1">
                                Galeri Lengkap</p>
                            <h3 class="text-lg sm:text-2xl font-black text-violet-950">Semua Foto Fasilitas & Upload
                                Admin</h3>
                        </div>

                        <button type="button"
                            class="shrink-0 rounded-xl border border-slate-200 px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors"
                            data-facility-gallery-close>
                            Tutup
                        </button>
                    </div>

                    <div class="max-h-[calc(90vh-84px)] overflow-y-auto bg-slate-50 p-4 sm:p-6">
                        @if(count($galleryItems) > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($galleryItems as $photo)
                                    <figure
                                        class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                        <div class="aspect-square overflow-hidden bg-violet-950">
                                            <img src="{{ $photo['url'] }}" alt="{{ $photo['alt'] }}"
                                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                        </div>
                                        <figcaption class="p-3">
                                            <p class="text-sm font-bold text-violet-950 truncate">{{ $photo['title'] }}</p>
                                            <p class="text-[11px] text-slate-500 truncate">{{ $photo['source'] }}</p>
                                        </figcaption>
                                    </figure>
                                @endforeach
                            </div>
                        @else
                            <div class="py-14 text-center">
                                <i class="fas fa-images text-slate-300 text-4xl mb-3"></i>
                                <p class="text-slate-500 font-semibold">Belum ada foto yang diupload admin.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!-- ================= BIAYA SECTION ================= -->
    <section id="biaya" class="py-20 sm:py-24 md:py-28 bg-slate-50 relative overflow-hidden">
        <!-- Ambient background elements -->
        <div
            class="w-80 h-80 bg-violet-600/10 rounded-full blur-3xl absolute -top-10 -right-10 pointer-events-none animate-pulse-glow">
        </div>
        <div
            class="w-80 h-80 bg-amber-400/10 rounded-full blur-3xl absolute -bottom-10 -left-10 pointer-events-none animate-float animate-delay-200">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-12 reveal-element">
                <span class="yellow-sticker-badge mb-4">
                    Investasi Pendidikan
                </span>
                <h2 class="font-black text-3xl sm:text-4xl md:text-5xl text-violet-950 mb-5 leading-tight">
                    Pilihan Paket <span class="text-violet-700">Belajar Privat</span>
                </h2>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed font-semibold">
                    Geser kartu ke kanan atau ke kiri untuk melihat program les privat kami. Pilih paket terbaik sesuai
                    kebutuhan Anda!
                </p>
            </div>

            <!-- ChatGPT-Style Pricing Grid (Carousel Slider) -->
            <div class="relative px-2 sm:px-12 reveal-element delay-100">
                <!-- Swiper Container -->
                <div class="swiper pricing-swiper overflow-hidden">
                    <div class="swiper-wrapper">

                        @foreach(\App\Models\PaketBelajar::all() as $paket)
                            <!-- CARD: {{ $paket->nama_paket }} -->
                            <div class="swiper-slide py-6 h-auto">
                                @if($paket->is_populer)
                                    <div
                                        class="bg-gradient-to-b from-violet-900 to-violet-955 text-white rounded-3xl p-5 sm:p-8 flex flex-col justify-between hover:shadow-2xl transition-all duration-300 relative h-full min-h-[500px] shadow-xl shadow-purple-950/20 border-2 border-amber-400 animate-pulse-glow-subtle">
                                        <!-- Popular Tag badge -->
                                        <div
                                            class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 bg-amber-400 text-purple-950 text-xs font-black uppercase tracking-wider rounded-full shadow-md">
                                            Paling Populer
                                        </div>

                                        <div>
                                            <div class="mb-4">
                                                <span
                                                    class="px-3 py-1 bg-white/10 text-amber-300 text-xs font-bold uppercase rounded-full">{{ $paket->kategori }}</span>
                                            </div>
                                            <h3 class="text-xl font-bold text-white mb-2">{{ $paket->nama_paket }}</h3>
                                            <p class="text-violet-200 text-xs mb-6">{{ $paket->deskripsi }}</p>

                                            <div class="flex items-baseline gap-1 mb-8">
                                                <span
                                                    class="text-3xl sm:text-4xl font-black text-amber-300">{{ $paket->harga_min >= 1000 ? ($paket->harga_min / 1000) . 'K' : $paket->harga_min }}</span>
                                                <span class="text-violet-200 text-sm font-semibold">-
                                                    {{ $paket->harga_max >= 1000 ? ($paket->harga_max / 1000) . 'K' : $paket->harga_max }}</span>
                                                <span class="text-violet-300 text-xs font-semibold ml-1">/ sesi / org</span>
                                            </div>

                                            <!-- Feature list -->
                                            <ul class="space-y-3 text-sm text-violet-100 mb-8">
                                                @if($paket->detail_1)
                                                    <li class="flex items-start gap-2.5">
                                                        <span class="text-amber-400 shrink-0 mt-0.5"><i
                                                                class="fas fa-check-circle"></i></span>
                                                        <span>{{ $paket->detail_1 }}</span>
                                                    </li>
                                                @endif
                                                @if($paket->detail_2)
                                                    <li class="flex items-start gap-2.5">
                                                        <span class="text-amber-400 shrink-0 mt-0.5"><i
                                                                class="fas fa-check-circle"></i></span>
                                                        <span>{{ $paket->detail_2 }}</span>
                                                    </li>
                                                @endif
                                                @if($paket->detail_3)
                                                    <li class="flex items-start gap-2.5">
                                                        <span class="text-amber-400 shrink-0 mt-0.5"><i
                                                                class="fas fa-check-circle"></i></span>
                                                        <span>{{ $paket->detail_3 }}</span>
                                                    </li>
                                                @endif
                                                @if($paket->detail_4)
                                                    <li class="flex items-start gap-2.5">
                                                        <span class="text-amber-400 shrink-0 mt-0.5"><i
                                                                class="fas fa-check-circle"></i></span>
                                                        <span>{{ $paket->detail_4 }}</span>
                                                    </li>
                                                @endif
                                                @if($paket->detail_5)
                                                    <li class="flex items-start gap-2.5">
                                                        <span class="text-amber-400 shrink-0 mt-0.5"><i
                                                                class="fas fa-clock"></i></span>
                                                        <span>{{ $paket->detail_5 }}</span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>

                                        <a href="{{ route('login') }}#daftar"
                                            class="w-full text-center py-3 rounded-xl bg-amber-400 text-purple-950 font-black shadow-md hover:bg-amber-300 hover:scale-105 transition duration-200 block">
                                            Daftar Paket {{ $paket->nama_paket }}
                                        </a>
                                    </div>
                                @else
                                    <div
                                        class="bg-white rounded-3xl border border-violet-100 shadow-lg shadow-violet-100/30 p-5 sm:p-8 flex flex-col justify-between hover:shadow-xl transition-all duration-300 relative h-full min-h-[500px]">
                                        <div>
                                            <div class="mb-4">
                                                <span
                                                    class="px-3 py-1 bg-violet-50 text-violet-700 text-xs font-bold uppercase rounded-full">{{ $paket->kategori }}</span>
                                            </div>
                                            <h3 class="text-xl font-bold text-violet-950 mb-2">{{ $paket->nama_paket }}</h3>
                                            <p class="text-slate-500 text-xs mb-6">{{ $paket->deskripsi }}</p>

                                            <div class="flex items-baseline gap-1 mb-8">
                                                <span
                                                    class="text-3xl sm:text-4xl font-black text-violet-950">{{ $paket->harga_min >= 1000 ? ($paket->harga_min / 1000) . 'K' : $paket->harga_min }}</span>
                                                <span class="text-slate-500 text-sm font-semibold">-
                                                    {{ $paket->harga_max >= 1000 ? ($paket->harga_max / 1000) . 'K' : $paket->harga_max }}</span>
                                                <span class="text-slate-400 text-xs font-semibold ml-1">/ sesi / org</span>
                                            </div>

                                            <!-- Feature list -->
                                            <ul class="space-y-3 text-sm text-slate-600 mb-8">
                                                @if($paket->detail_1)
                                                    <li class="flex items-start gap-2.5">
                                                        <span class="text-emerald-500 shrink-0 mt-0.5"><i
                                                                class="fas fa-check-circle"></i></span>
                                                        <span>{{ $paket->detail_1 }}</span>
                                                    </li>
                                                @endif
                                                @if($paket->detail_2)
                                                    <li class="flex items-start gap-2.5">
                                                        <span class="text-emerald-500 shrink-0 mt-0.5"><i
                                                                class="fas fa-check-circle"></i></span>
                                                        <span>{{ $paket->detail_2 }}</span>
                                                    </li>
                                                @endif
                                                @if($paket->detail_3)
                                                    <li class="flex items-start gap-2.5">
                                                        <span class="text-emerald-500 shrink-0 mt-0.5"><i
                                                                class="fas fa-check-circle"></i></span>
                                                        <span>{{ $paket->detail_3 }}</span>
                                                    </li>
                                                @endif
                                                @if($paket->detail_4)
                                                    <li class="flex items-start gap-2.5">
                                                        <span class="text-emerald-500 shrink-0 mt-0.5"><i
                                                                class="fas fa-check-circle"></i></span>
                                                        <span>{{ $paket->detail_4 }}</span>
                                                    </li>
                                                @endif
                                                @if($paket->detail_5)
                                                    <li class="flex items-start gap-2.5">
                                                        <span class="text-emerald-500 shrink-0 mt-0.5"><i
                                                                class="fas fa-clock"></i></span>
                                                        <span>{{ $paket->detail_5 }}</span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>

                                        <a href="{{ route('login') }}#daftar"
                                            class="w-full text-center py-3 rounded-xl border-2 border-violet-800 text-violet-900 font-bold hover:bg-violet-900 hover:text-white transition duration-200 block">
                                            Daftar Paket {{ $paket->nama_paket }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach

                    </div>
                    <!-- Swiper Pagination -->
                    <div class="swiper-pagination"></div>
                </div>

                <!-- Custom Navigation Buttons -->
                <div class="swiper-button-prev-custom shadow-lg"><i class="fas fa-chevron-left"></i></div>
                <div class="swiper-button-next-custom shadow-lg"><i class="fas fa-chevron-right"></i></div>
            </div>

            <!-- Footer note below cards -->
            <div class="mt-8 text-center text-xs font-semibold text-slate-500 reveal-element delay-200">
                * Seluruh tarif tertera di atas dihitung per orang per sesi (durasi 90 menit). Harga dapat disesuaikan
                berdasarkan kesepakatan kelompok belajar.
            </div>
        </div>
    </section>


    <!-- ================= JADWAL SECTION ================= -->
    <section id="jadwal"
        class="py-20 sm:py-24 md:py-28 bg-gradient-to-b from-violet-950 via-violet-900 to-violet-950 text-white relative overflow-hidden">

        <!-- Ambient Glow Background Blobs -->
        <div
            class="w-96 h-96 bg-amber-400/15 rounded-full blur-3xl absolute -top-20 right-0 animate-pulse-glow pointer-events-none">
        </div>
        <div
            class="w-96 h-96 bg-violet-600/20 rounded-full blur-3xl absolute -bottom-20 -left-20 animate-float pointer-events-none">
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Section Title -->
            <div class="text-center max-w-3xl mx-auto mb-14 reveal-element">
                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-400/15 border border-amber-400/30 text-amber-300 font-bold text-xs sm:text-sm mb-4 backdrop-blur-md">
                    📅 WAKTU & SESI BELAJAR FLEKSIBEL
                </div>

                <h2 class="font-black text-2xl sm:text-3xl md:text-4xl lg:text-5xl leading-tight mb-4 tracking-tight">
                    <span
                        class="yellow-sticker-badge shadow-2xl transform -rotate-1 hover:rotate-0 transition-transform duration-300">
                        JADWAL YANG TERSEDIA
                    </span>
                </h2>

                <p class="text-violet-200 text-sm sm:text-base md:text-lg leading-relaxed mt-2 font-normal">
                    Pilih sesi bimbingan 90 menit yang paling sesuai dengan aktivitas dan kesibukan sekolahmu.
                </p>
            </div>

            <!-- Quick Day Info Badges -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10 reveal-element delay-100">
                <div
                    class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-center hover:bg-white/15 hover:border-amber-400/40 transition-all duration-300">
                    <div class="text-xs text-amber-300 font-bold uppercase tracking-wider mb-1">Senin s.d Kamis</div>
                    <div class="text-lg font-black text-white">5 Sesi Shift</div>
                    <div class="text-xs text-violet-200 mt-1">13.30 - 21.00 WIB</div>
                </div>
                <div
                    class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-center hover:bg-white/15 hover:border-amber-400/40 transition-all duration-300">
                    <div class="text-xs text-amber-300 font-bold uppercase tracking-wider mb-1">Jumat</div>
                    <div class="text-lg font-black text-white">6 Sesi Shift</div>
                    <div class="text-xs text-violet-200 mt-1">12.00 - 21.00 WIB</div>
                </div>
                <div
                    class="p-4 rounded-2xl bg-amber-400/20 backdrop-blur-md border border-amber-400/50 text-center hover:bg-amber-400/30 transition-all duration-300 shadow-lg shadow-amber-400/10">
                    <div class="text-xs text-amber-300 font-black uppercase tracking-wider mb-1">⭐ Sabtu (Full Day)
                    </div>
                    <div class="text-lg font-black text-amber-300">9 Sesi Lengkap</div>
                    <div class="text-xs text-amber-100 mt-1">07.30 - 21.00 WIB</div>
                </div>
                <div
                    class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-center hover:bg-white/15 transition-all duration-300">
                    <div class="text-xs text-violet-300 font-bold uppercase tracking-wider mb-1">Minggu</div>
                    <div class="text-lg font-black text-slate-300">Libur Sesi</div>
                    <div class="text-xs text-violet-300 mt-1">Privat By Request</div>
                </div>
            </div>

            <!-- Table Card Container -->
            <div
                class="reveal-element delay-200 bg-white rounded-3xl shadow-2xl overflow-hidden border-2 border-amber-400/40">

                <!-- Table Header Card Title -->
                <div
                    class="bg-gradient-to-r from-violet-950 via-violet-900 to-violet-950 px-6 py-5 text-center border-b border-violet-800 flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-amber-400 animate-ping"></div>
                        <span class="font-extrabold text-lg text-white tracking-wide">Tabel Shift Bimbingan (90 Menit /
                            Sesi)</span>
                    </div>
                    <span class="text-xs font-bold bg-amber-400 text-violet-950 px-3 py-1 rounded-full shadow-sm">
                        ✓ Slot Tersedia
                    </span>
                </div>

                <!-- Responsive Table Wrapper -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-violet-100/80 text-violet-950 text-xs sm:text-sm font-extrabold uppercase border-b-2 border-violet-200">
                                <th class="py-4 px-6 text-slate-900 whitespace-nowrap">Shift (90 menit)</th>
                                <th class="py-4 px-6 text-center text-violet-900 whitespace-nowrap">Senin s.d Kamis</th>
                                <th class="py-4 px-6 text-center text-violet-900 whitespace-nowrap">Jumat</th>
                                <th class="py-4 px-6 text-center text-violet-950 bg-amber-200/60 whitespace-nowrap">
                                    Sabtu</th>
                                <th class="py-4 px-6 text-center text-slate-500 whitespace-nowrap">Minggu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-violet-100 text-xs sm:text-sm font-bold text-slate-800">

                            <!-- Row 1: 07.30 - 09.00 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200">
                                <td
                                    class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-violet-400"></span>
                                    07.30 - 09.00
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                            <!-- Row 2: 09.00 - 10.30 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200 bg-slate-50/40">
                                <td
                                    class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-violet-400"></span>
                                    09.00 - 10.30
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                            <!-- Row 3: 10.30 - 12.00 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200">
                                <td
                                    class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-violet-400"></span>
                                    10.30 - 12.00
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                            <!-- Row 4: 12.00 - 13.30 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200 bg-slate-50/40">
                                <td
                                    class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    12.00 - 13.30
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                                <td class="py-3.5 px-6 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                            <!-- Row 5: 13.30 - 15.00 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200">
                                <td
                                    class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    13.30 - 15.00
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                            <!-- Row 6: 15.00 - 16.30 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200 bg-slate-50/40">
                                <td
                                    class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    15.00 - 16.30
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                            <!-- Row 7: 16.30 - 18.00 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200">
                                <td
                                    class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    16.30 - 18.00
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                            <!-- Row 8: 18.00 - 19.30 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200 bg-slate-50/40">
                                <td
                                    class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    18.00 - 19.30
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                            <!-- Row 9: 19.30 - 21.00 -->
                            <tr class="hover:bg-violet-50/70 transition-colors duration-200">
                                <td
                                    class="py-3.5 px-6 font-extrabold text-violet-950 flex items-center gap-2 whitespace-nowrap">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    19.30 - 21.00
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-900 text-amber-300 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center bg-amber-50/50">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-violet-950 font-black shadow-md">✓</span>
                                </td>
                                <td class="py-3.5 px-6 text-center text-slate-300">-</td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <!-- Footer Note -->
                <div
                    class="bg-violet-50 px-6 py-4 border-t border-violet-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs font-semibold text-slate-600">
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
                <div
                    class="inline-flex flex-col sm:flex-row items-center gap-4 p-4 sm:px-8 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20">
                    <span class="text-sm font-bold text-violet-100">Ingin request jadwal khusus di luar tabel di
                        atas?</span>
                    <a href="#kontak"
                        class="px-6 py-2.5 rounded-xl bg-amber-400 text-violet-950 font-extrabold shadow-md hover:bg-amber-300 hover:scale-105 transition-all duration-300 text-xs sm:text-sm">
                        Konsultasi Jadwal Sesuai Keinginan
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- ================= KONTAK & ALAMAT SECTION ================= -->
    <section id="kontak" class="py-20 sm:py-24 md:py-28 bg-white relative overflow-hidden">
        <!-- Ambient background blobs -->
        <div class="w-72 h-72 bg-violet-600/5 rounded-full blur-3xl absolute top-10 left-10 pointer-events-none"></div>
        <div class="w-72 h-72 bg-amber-400/5 rounded-full blur-3xl absolute bottom-10 right-10 pointer-events-none">
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16 reveal-element">
                <span class="yellow-sticker-badge mb-4">
                    Hubungi Kami
                </span>
                <h2 class="font-black text-3xl sm:text-4xl md:text-5xl text-violet-950 mb-5 leading-tight">
                    Alamat Kantor &amp; <span class="text-violet-700">Lokasi Google Map</span>
                </h2>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed font-semibold">
                    Temukan lokasi ruang belajar Paradise of Math atau hubungi kami langsung via WhatsApp untuk
                    berkonsultasi mengenai kelas bimbingan terbaik Anda.
                </p>
            </div>

            <!-- Two Column Layout: Contact Card & Map -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                <!-- Left: Contact Details Card (Col-span 5) -->
                <div
                    class="lg:col-span-5 flex flex-col justify-between bg-violet-50/70 border border-violet-100 rounded-3xl p-8 reveal-element delay-100 shadow-sm">
                    <div>
                        <h3 class="text-xl font-bold text-violet-950 mb-6 flex items-center gap-2">
                            <span class="text-violet-700"><i class="fas fa-info-circle"></i></span>
                            Informasi Kontak PoM
                        </h3>

                        <!-- Detail Items -->
                        <div class="space-y-6">
                            <!-- Address -->
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-violet-600/10 text-violet-700 flex items-center justify-center shrink-0">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-extrabold text-violet-950 uppercase tracking-wider mb-1">
                                        Alamat Belajar</h4>
                                    <p class="text-sm text-slate-600 leading-relaxed font-semibold">
                                        Jln. Jati I No.19 RT/RW 002/001 Sawahan, Padang Timur, Padang, Sumatera Barat,
                                        25121
                                    </p>
                                </div>
                            </div>

                            <!-- WhatsApp -->
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <i class="fab fa-whatsapp text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-extrabold text-violet-950 uppercase tracking-wider mb-1">
                                        WhatsApp Chat</h4>
                                    <p class="text-sm text-slate-600 font-semibold mb-2">
                                        Hubungi Admin
                                    </p>
                                    <a href="https://wa.me/6289675053537" target="_blank"
                                        class="inline-flex items-center gap-2 text-sm text-white bg-emerald-600 hover:bg-emerald-500 font-extrabold px-4 py-2 rounded-xl transition duration-200 shadow-md shadow-emerald-600/10">
                                        <i class="fab fa-whatsapp"></i> Chat WhatsApp
                                    </a>
                                </div>
                            </div>

                            <!-- Office Hours -->
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-extrabold text-violet-950 uppercase tracking-wider mb-1">Jam
                                        Operasional</h4>
                                    <p class="text-sm text-slate-600 leading-relaxed font-semibold">
                                        Senin s.d Kamis: 13.30 - 21.00 WIB<br>
                                        Jumat: 12.00 - 21.00 WIB<br>
                                        Sabtu (Full Day): 07.30 - 21.00 WIB<br>
                                        <span class="text-xs text-amber-600 font-bold">* Minggu: Libur / Privat By
                                            Request</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Small Brand Branding footer -->
                    <div
                        class="pt-6 mt-6 border-t border-violet-200/50 text-xs text-slate-500 font-semibold flex items-center justify-between">
                        <span>© Paradise of Math</span>
                        <span>Sawahan, Padang Timur</span>
                    </div>
                </div>

                <!-- Right: Google Map Iframe (Col-span 7) -->
                <div
                    class="lg:col-span-7 bg-white border border-violet-100 rounded-3xl p-3 reveal-element delay-200 shadow-lg shadow-violet-150/20 overflow-hidden min-h-[400px] flex">
                    <iframe class="w-full h-full min-h-[400px] rounded-2xl border-0"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.275700703673!2d100.36296367472394!3d-0.944960699045907!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2fd4b93920c71cb5%3A0x67fcd9bdfbc466e1!2sLBB%20Paradise%20Of%20Math!5e0!3m2!1sid!2sid!4v1784967251178!5m2!1sid!2sid"
                        allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FOOTER SECTION ================= -->
    <footer
        class="bg-gradient-to-br from-violet-950 via-violet-900 to-violet-950 text-white pt-16 pb-8 border-t border-violet-850 relative overflow-hidden">
        <!-- Background light effects -->
        <div
            class="w-96 h-96 bg-violet-600/10 rounded-full blur-3xl absolute -top-40 -left-40 pointer-events-none animate-pulse-glow">
        </div>
        <div
            class="w-96 h-96 bg-amber-400/5 rounded-full blur-3xl absolute -bottom-40 -right-40 pointer-events-none animate-float animate-delay-200">
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-12 pb-12 border-b border-violet-800">
                <!-- Col 1: Brand Info -->
                <div class="md:col-span-5 space-y-4">
                    <a href="#" class="inline-flex items-center gap-2">
                        <span class="text-xl sm:text-2xl font-black tracking-tight text-white">
                            Paradise <span class="text-amber-400">of Math</span>
                        </span>
                    </a>
                    <p class="text-xs sm:text-sm text-violet-200/80 leading-relaxed font-medium">
                        Lembaga Bimbingan Belajar (LBB) Matematika terpercaya di Kota Padang. Kami berkomitmen
                        meningkatkan pemahaman dan kecintaan siswa terhadap matematika melalui bimbingan privat
                        berkualitas.
                    </p>
                    <!-- Social Media Links (Instagram & TikTok) -->
                    <div class="pt-2 flex items-center gap-3">
                        <!-- Instagram Button -->
                        <a href="https://www.instagram.com/paradiseofmath/" target="_blank"
                            class="w-10 h-10 rounded-full bg-violet-900/50 border border-violet-750/50 text-white flex items-center justify-center hover:bg-gradient-to-tr hover:from-yellow-500 hover:via-pink-500 hover:to-purple-600 hover:border-transparent hover:scale-110 transition duration-300 shadow-md">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                            </svg>
                        </a>
                        <!-- TikTok Button -->
                        <a href="https://www.tiktok.com/@paradiseofmath?is_from_webapp=1&sender_device=pc"
                            target="_blank"
                            class="w-10 h-10 rounded-full bg-violet-900/50 border border-violet-750/50 text-white flex items-center justify-center hover:bg-black hover:border-transparent hover:scale-110 hover:shadow-cyan-400/20 hover:shadow-lg transition duration-300 shadow-md">
                            <svg class="w-4.5 h-4.5 fill-current" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.02 1.59 4.23.86.97 2.07 1.62 3.39 1.83v3.71c-1.84-.02-3.61-.71-4.96-1.97-.24-.2-.46-.43-.66-.67v6.62c.04 2.64-1.28 5.12-3.51 6.47-2.3 1.48-5.3 1.68-7.74.52-2.73-1.22-4.48-4.22-4.26-7.22.18-3.08 2.37-5.78 5.37-6.5 1.09-.27 2.22-.24 3.29.07V10.7c-.89-.31-1.87-.33-2.76-.04-1.39.42-2.45 1.62-2.71 3.05-.33 1.62.43 3.3 1.88 4.09 1.34.78 3.1.66 4.31-.31.86-.68 1.35-1.74 1.33-2.84V.02z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Program Belajar -->
                <div class="md:col-span-3 space-y-4">
                    <h4 class="text-sm font-extrabold text-amber-400 uppercase tracking-wider">Program Kami</h4>
                    <ul class="space-y-2 text-xs sm:text-sm text-violet-200/70 font-semibold">
                        <li><a href="#biaya" class="hover:text-white transition">Kelas SD &amp; SMP</a></li>
                        <li><a href="#biaya" class="hover:text-white transition">Kelas SMA</a></li>
                        <li><a href="#biaya" class="hover:text-white transition">Persiapan Ujian &amp; SNBT</a></li>
                        <li><a href="#biaya" class="hover:text-white transition">Program Khusus &amp; S2/S3</a></li>
                    </ul>
                </div>

                <!-- Col 3: Hubungi Kami -->
                <div class="md:col-span-4 space-y-4">
                    <h4 class="text-sm font-extrabold text-amber-400 uppercase tracking-wider">Lokasi &amp; Kontak</h4>
                    <ul class="space-y-3 text-xs sm:text-sm text-violet-200/70 font-semibold">
                        <li class="flex items-start gap-2.5">
                            <span class="text-amber-400 shrink-0 mt-0.5"><i class="fas fa-map-marker-alt"></i></span>
                            <span>Jln. Jati I No.19 RT/RW 002/001 Sawahan, Padang Timur, Padang, Sumatera Barat,
                                25121</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright Strip -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                <span>© {{ date('Y') }} LBB Paradise of Math. Hak Cipta Dilindungi. Dikelola &amp; Dikembangkan oleh
                    <strong class="text-amber-300 font-bold">Reyhan &amp; Rizky</strong> (Universitas Metamedia).</span>
                <span class="flex gap-4">
                    <a href="#beranda" class="hover:text-white transition">Kembali ke Atas</a>
                </span>
            </div>
        </div>
    </footer>


    <!-- ================= FLOATING SCROLL PROGRESS BACK-TO-TOP BUTTON ================= -->
    <div id="scroll-to-top-btn"
        class="fixed bottom-20 sm:bottom-24 right-5 sm:right-7 z-40 opacity-0 pointer-events-none transition-all duration-300 transform translate-y-4 no-print">
        <button type="button" onclick="scrollToTop()" aria-label="Kembali ke atas"
            class="relative w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-white text-violet-950 shadow-xl flex items-center justify-center group hover:scale-110 active:scale-95 transition-transform duration-200 border border-violet-100 cursor-pointer">
            <!-- SVG Progress Ring Circle -->
            <svg class="absolute inset-0 w-full h-full -rotate-90 transform" viewBox="0 0 48 48">
                <!-- Background Ring -->
                <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="3" fill="transparent"
                    class="text-violet-100" />
                <!-- Dynamic Scroll Progress Ring -->
                <circle id="scroll-progress-circle" cx="24" cy="24" r="20" stroke="currentColor" stroke-width="3.5"
                    fill="transparent" stroke-dasharray="125.66" stroke-dashoffset="125.66" stroke-linecap="round"
                    class="text-amber-400 transition-all duration-75" />
            </svg>
            <!-- Arrow Up Icon -->
            <i
                class="fas fa-arrow-up text-xs sm:text-sm font-bold text-violet-950 group-hover:text-amber-500 group-hover:-translate-y-0.5 transition-all duration-200 relative z-10"></i>
        </button>
    </div>

    <!-- ================= FLOATING REALTIME CHAT WIDGET ================= -->
    <div class="fixed bottom-4 sm:bottom-6 right-4 sm:right-6 z-50 no-print">
        <!-- Chat Bubble Trigger -->
        <button id="chat-trigger-btn"
            class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-br from-violet-600 to-indigo-700 text-white flex items-center justify-center shadow-lg shadow-violet-300 hover:scale-105 hover:shadow-violet-400 active:scale-95 transition-all duration-300 focus:outline-none">
            <!-- Pulsing ring effect -->
            <span class="absolute inline-flex h-full w-full rounded-full bg-violet-400 opacity-75 animate-ping"></span>

            <i class="fas fa-comments text-lg sm:text-xl relative z-10"></i>

            <!-- Notification Badge -->
            <span
                class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 rounded-full text-white text-[10px] font-bold flex items-center justify-center border-2 border-white animate-bounce">1</span>
        </button>

        <!-- Chat Window Box -->
        <div id="chat-window-box"
            class="absolute bottom-16 sm:bottom-20 right-0 w-[calc(100vw-2rem)] max-w-[320px] sm:max-w-[360px] h-[440px] sm:h-[480px] bg-white rounded-2xl shadow-2xl border border-violet-100/80 flex flex-col overflow-hidden hidden transform scale-95 opacity-0 origin-bottom-right transition-all duration-300">
            <!-- Header -->
            <div
                class="bg-gradient-to-r from-violet-700 to-indigo-800 p-4 text-white flex items-center justify-between shadow-md">
                <div class="flex items-center gap-3">
                    <div class="relative w-10 h-10 rounded-full bg-white/20 p-0.5">
                        <img src="{{ asset('images/logoPM.webp') }}" alt="logo"
                            class="w-full h-full object-contain rounded-full bg-white">
                        <span
                            class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-white"></span>
                    </div>
                    <div>
                        <div class="font-bold text-sm">Customer Service PM</div>
                        <div class="text-[10px] text-violet-200 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Online (Siap
                            Membantu)
                        </div>
                    </div>
                </div>
                <button id="chat-close-btn"
                    class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors focus:outline-none">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            <!-- Messages Area -->
            <div id="chat-messages-container" class="flex-1 p-4 overflow-y-auto space-y-3 bg-slate-50/50 text-xs">
            </div>

            <!-- Quick Option Chips -->
            <div id="quick-options-container"
                class="px-4 py-2 bg-slate-50 border-t border-slate-100 flex flex-wrap gap-1.5">
                <button onclick="sendQuickOption('Tanya Paket Belajar')"
                    class="px-2.5 py-1.5 rounded-full bg-white border border-violet-200 text-[10px] font-semibold text-violet-700 hover:bg-violet-50 transition-colors focus:outline-none">📦
                    Paket Belajar</button>
                <button onclick="sendQuickOption('Biaya Pendaftaran')"
                    class="px-2.5 py-1.5 rounded-full bg-white border border-violet-200 text-[10px] font-semibold text-violet-700 hover:bg-violet-50 transition-colors focus:outline-none">💰
                    Biaya & Promo</button>
                <button onclick="sendQuickOption('Hubungi WhatsApp')"
                    class="px-2.5 py-1.5 rounded-full bg-white border border-violet-200 text-[10px] font-semibold text-violet-700 hover:bg-violet-50 transition-colors focus:outline-none">💬
                    WhatsApp Admin</button>
            </div>

            <!-- Footer Input -->
            <form id="chat-input-form" class="p-3 bg-white border-t border-slate-100 flex items-center gap-2">
                <input type="text" id="chat-input-text" placeholder="Tulis pesan..." autocomplete="off"
                    class="flex-1 bg-slate-100 text-xs px-3 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-600 transition-all">
                <button type="submit"
                    class="w-8 h-8 rounded-xl bg-violet-600 text-white flex items-center justify-center hover:bg-violet-700 transition-colors focus:outline-none">
                    <i class="fas fa-paper-plane text-xs"></i>
                </button>
            </form>
        </div>
    </div>

    @php
        $chatAppUser = auth()->guard('siswa')->user() ?? auth()->guard('web')->user();
    @endphp
    <script>
        window.currentUserName = "{{ $chatAppUser ? $chatAppUser->name : 'Anonymous' }}";
        window.currentUserRole = "{{ $chatAppUser ? (auth()->guard('siswa')->check() ? 'siswa' : $chatAppUser->role) : 'visitor' }}";
    </script>
    <!-- Script Auto-Rotator Hero Slider -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const galleryModal = document.getElementById('facility-gallery-modal');
            const galleryOpenButton = document.getElementById('gallery-view-all-btn');

            function hideGalleryModal() {
                if (!galleryModal) {
                    return;
                }

                galleryModal.classList.add('hidden');
                galleryModal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            }

            if (galleryOpenButton && galleryModal) {
                galleryOpenButton.addEventListener('click', function () {
                    galleryModal.classList.remove('hidden');
                    galleryModal.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                });

                galleryModal.querySelectorAll('[data-facility-gallery-close]').forEach(function (button) {
                    button.addEventListener('click', hideGalleryModal);
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        hideGalleryModal();
                    }
                });
            }

            const heroItems = document.querySelectorAll('.landing-hero-item');
            const heroDots = document.querySelectorAll('.landing-hero-dot');
            if (heroItems.length > 1) {
                let currentHeroIndex = 0;
                setInterval(function () {
                    heroItems[currentHeroIndex].style.opacity = '0';
                    heroItems[currentHeroIndex].style.zIndex = '1';
                    if (heroDots[currentHeroIndex]) heroDots[currentHeroIndex].style.opacity = '0.4';

                    currentHeroIndex = (currentHeroIndex + 1) % heroItems.length;

                    heroItems[currentHeroIndex].style.opacity = '1';
                    heroItems[currentHeroIndex].style.zIndex = '10';
                    if (heroDots[currentHeroIndex]) heroDots[currentHeroIndex].style.opacity = '1';
                }, 3500);
            }

            // Script Auto-Rotator Banner Foto Guru & Sertifikat
            const bannerGuruItems = document.querySelectorAll('.banner-guru-item');
            const bannerGuruDots = document.querySelectorAll('.banner-guru-dot');
            if (bannerGuruItems.length > 1) {
                let currentGuruIndex = 0;
                setInterval(function () {
                    bannerGuruItems[currentGuruIndex].style.opacity = '0';
                    bannerGuruItems[currentGuruIndex].style.zIndex = '1';
                    if (bannerGuruDots[currentGuruIndex]) bannerGuruDots[currentGuruIndex].style.opacity = '0.4';

                    currentGuruIndex = (currentGuruIndex + 1) % bannerGuruItems.length;

                    bannerGuruItems[currentGuruIndex].style.opacity = '1';
                    bannerGuruItems[currentGuruIndex].style.zIndex = '10';
                    if (bannerGuruDots[currentGuruIndex]) bannerGuruDots[currentGuruIndex].style.opacity = '1';
                }, 3500);

                bannerGuruDots.forEach((dot, idx) => {
                    dot.addEventListener('click', function () {
                        bannerGuruItems[currentGuruIndex].style.opacity = '0';
                        bannerGuruItems[currentGuruIndex].style.zIndex = '1';
                        if (bannerGuruDots[currentGuruIndex]) bannerGuruDots[currentGuruIndex].style.opacity = '0.4';

                        currentGuruIndex = idx;

                        bannerGuruItems[currentGuruIndex].style.opacity = '1';
                        bannerGuruItems[currentGuruIndex].style.zIndex = '10';
                        if (bannerGuruDots[currentGuruIndex]) bannerGuruDots[currentGuruIndex].style.opacity = '1';
                    });
                });
            }
        });
    </script>

    <!-- Script Controller YouTube Video Switcher -->
    <script>
        function switchYtVideo(videoId, title, desc, videoUrl, btnEl) {
            const iframe = document.getElementById('main-yt-iframe');
            const titleEl = document.getElementById('yt-featured-title');
            const descEl = document.getElementById('yt-featured-desc');
            const linkEl = document.getElementById('yt-featured-link');

            if (iframe) {
                iframe.src = `https://www.youtube-nocookie.com/embed/${videoId}?autoplay=1&rel=0`;
            }
            if (titleEl) titleEl.innerText = title;
            if (descEl) descEl.innerText = desc || 'Video tutorial eksklusif bimbingan belajar.';
            if (linkEl && videoUrl) linkEl.href = videoUrl;

            document.querySelectorAll('.yt-playlist-item').forEach(el => {
                el.classList.remove('border-2', 'border-amber-400/80', 'bg-white/10');
                el.classList.add('border-white/10', 'bg-white/5');
            });

            if (btnEl) {
                btnEl.classList.remove('border-white/10', 'bg-white/5');
                btnEl.classList.add('border-2', 'border-amber-400/80', 'bg-white/10');
            }
        }
    </script>

    <!-- Script Controller Scroll Progress & Back To Top Button -->
    <script>
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        window.addEventListener('scroll', function () {
            const scrollTop = window.scrollY || document.documentElement.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrollBtn = document.getElementById('scroll-to-top-btn');
            const progressCircle = document.getElementById('scroll-progress-circle');

            if (scrollBtn && progressCircle && scrollHeight > 0) {
                if (scrollTop > 180) {
                    scrollBtn.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-4');
                    scrollBtn.classList.add('opacity-100', 'translate-y-0');
                } else {
                    scrollBtn.classList.remove('opacity-100', 'translate-y-0');
                    scrollBtn.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4');
                }

                const progress = Math.min(Math.max(scrollTop / scrollHeight, 0), 1);
                const radius = 20;
                const circumference = 2 * Math.PI * radius;
                const strokeDashoffset = circumference - (progress * circumference);

                progressCircle.style.strokeDasharray = circumference;
                progressCircle.style.strokeDashoffset = strokeDashoffset;
            }
        });
    </script>
    <script src="{{ asset('js/informasi.js') }}?v={{ filemtime(public_path('js/informasi.js')) }}"></script>

    <!-- Script Automatic Visitor Device & Silent IP Geolocation Recorder (No Location Permission Needed) -->
    <script>
        (function() {
            function detectDeviceBrandAndModel() {
                var ua = navigator.userAgent || '';
                var platform = navigator.platform || '';
                var maxTouchPoints = navigator.maxTouchPoints || 0;

                // 1. Deteksi Perangkat Komputer / Laptop Terlebih Dahulu
                if (/Windows/i.test(ua)) return 'Windows PC / Laptop';
                if (/Macintosh|Mac OS X/i.test(ua) && !/iPhone|iPad/i.test(ua)) return 'Apple MacBook / Mac';
                if (/Linux/i.test(ua) && !/Android/i.test(ua)) return 'Linux PC / Laptop';

                // 2. Deteksi iPhone & iPad
                if (/iPhone/i.test(ua)) {
                    var h = window.screen.height;
                    var w = window.screen.width;
                    if (h === 932 || w === 430) return 'Apple iPhone 15 Pro Max / 14 Pro Max';
                    if (h === 852 || w === 393) return 'Apple iPhone 15 / 14 Pro';
                    if (h === 844 || w === 390) return 'Apple iPhone 14 / 13 / 12';
                    if (h === 667 || w === 375) return 'Apple iPhone SE / 8';
                    return 'Apple iPhone';
                }
                if (/iPad/i.test(ua) || (platform === 'MacIntel' && maxTouchPoints > 1)) return 'Apple iPad';
                
                // 3. Deteksi Xiaomi / Redmi / POCO
                if (/Redmi/i.test(ua)) return 'Xiaomi Redmi Smartphone';
                if (/POCO/i.test(ua)) return 'Xiaomi POCO Smartphone';
                if (/Xiaomi|Mi |Mi-|Build\/[0-9A-Z]*Xiaomi|Build\/[0-9A-Z]*Redmi|Build\/[0-9A-Z]*POCO|2201|2202|2203|2204|2207|2208|2210|2211|2301|2302|2303|2304|2305|2307|2308|2309|2310|2311|2312|2401|2402|2403|2404|2405|2406|2407|M200|M201|M210|2106|2107|2108|2109|2112/i.test(ua)) {
                    return 'Xiaomi Redmi / POCO Smartphone';
                }

                // 4. Deteksi Brand Lainnya
                if (/Samsung|SM-|SGH-|SCH-|SPH-/i.test(ua)) {
                    if (/SM-S92/i.test(ua)) return 'Samsung Galaxy S24 Series';
                    if (/SM-S91/i.test(ua)) return 'Samsung Galaxy S23 Series';
                    if (/SM-A/i.test(ua)) return 'Samsung Galaxy A-Series';
                    if (/SM-M/i.test(ua)) return 'Samsung Galaxy M-Series';
                    return 'Samsung Galaxy Phone';
                }
                if (/OPPO|CPH|PPCM|PBEM|PDEM/i.test(ua)) return 'OPPO Smartphone';
                if (/vivo|V2|V1|V3|V4|PD1/i.test(ua)) return 'Vivo Smartphone';
                if (/Realme|RMX/i.test(ua)) return 'Realme Smartphone';
                if (/\bInfinix\b|X6[0-9]|X68|X69/i.test(ua)) return 'Infinix Smartphone';
                if (/Pixel/i.test(ua)) return 'Google Pixel Phone';

                // 5. Fallback Android (Sangat sering merupakan Redmi / Xiaomi)
                if (/Android/i.test(ua)) {
                    return 'Xiaomi Redmi / Android Smartphone';
                }

                return 'Perangkat HP / Komputer';
            }

            function getBrowserName() {
                var ua = navigator.userAgent;
                if (/Chrome/i.test(ua) && !/Edg/i.test(ua) && !/OPR/i.test(ua)) return 'Google Chrome';
                if (/Safari/i.test(ua) && !/Chrome/i.test(ua)) return 'Apple Safari';
                if (/Firefox/i.test(ua)) return 'Mozilla Firefox';
                if (/Edg/i.test(ua)) return 'Microsoft Edge';
                if (/OPR|Opera/i.test(ua)) return 'Opera';
                if (/SamsungBrowser/i.test(ua)) return 'Samsung Internet';
                return 'Web Browser';
            }

            function getDeviceType() {
                var ua = navigator.userAgent;
                if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) return 'Tablet';
                if (/Mobile|iP(hone|od)|Android|BlackBerry|IEMobile|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/i.test(ua)) return 'Mobile (HP)';
                return 'Desktop / PC';
            }

            function captureVisitorDeviceLog() {
                try {
                    var logs = JSON.parse(localStorage.getItem('pm_visitor_device_logs') || '[]');
                    var logId = 'DEV-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
                    
                    var newLog = {
                        id: logId,
                        logCode: logId,
                        time: new Date().toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'medium' }),
                        timestamp: Date.now(),
                        deviceType: getDeviceType(),
                        brandModel: detectDeviceBrandAndModel(),
                        browser: getBrowserName(),
                        platform: navigator.platform || 'N/A',
                        userAgent: navigator.userAgent,
                        screen: window.screen.width + ' x ' + window.screen.height + ' px',
                        viewport: window.innerWidth + ' x ' + window.innerHeight + ' px',
                        dpr: window.devicePixelRatio || 1,
                        language: navigator.language || 'id-ID',
                        onlineStatus: navigator.onLine ? 'Online' : 'Offline',
                        page: 'Landing Page (/informasi)',
                        location: null
                    };

                    // Simpan data perangkat dasar secara instan
                    logs.unshift(newLog);
                    if (logs.length > 50) logs.pop();
                    localStorage.setItem('pm_visitor_device_logs', JSON.stringify(logs));
                    window.dispatchEvent(new Event('pm_device_logged'));

                    // Deteksi Model Presisi HP Redmi / Xiaomi via UserAgentData High Entropy API (Chrome Mobile)
                    if (navigator.userAgentData && navigator.userAgentData.getHighEntropyValues) {
                        navigator.userAgentData.getHighEntropyValues(['model', 'manufacturer', 'platform']).then(function(uaHigh) {
                            if (uaHigh && (uaHigh.model || uaHigh.manufacturer)) {
                                var modelStr = uaHigh.model || '';
                                var manufacturerStr = uaHigh.manufacturer || '';
                                var detectedBrand = newLog.brandModel;

                                if (/Redmi/i.test(modelStr) || /Redmi/i.test(manufacturerStr)) {
                                    detectedBrand = 'Xiaomi Redmi (' + (modelStr || 'Smartphone') + ')';
                                } else if (/POCO/i.test(modelStr) || /POCO/i.test(manufacturerStr)) {
                                    detectedBrand = 'Xiaomi POCO (' + (modelStr || 'Smartphone') + ')';
                                } else if (/Xiaomi/i.test(modelStr) || /Xiaomi/i.test(manufacturerStr)) {
                                    detectedBrand = 'Xiaomi (' + (modelStr || 'Smartphone') + ')';
                                } else if (modelStr && modelStr !== 'K' && modelStr !== 'Android') {
                                    detectedBrand = (manufacturerStr ? manufacturerStr + ' ' : '') + modelStr;
                                }

                                if (detectedBrand !== newLog.brandModel) {
                                    newLog.brandModel = detectedBrand;
                                    var currentLogs = JSON.parse(localStorage.getItem('pm_visitor_device_logs') || '[]');
                                    var target = currentLogs.find(function(l) { return l.id === logId; });
                                    if (target) {
                                        target.brandModel = detectedBrand;
                                        localStorage.setItem('pm_visitor_device_logs', JSON.stringify(currentLogs));
                                        window.dispatchEvent(new Event('pm_device_logged'));
                                    }
                                    sendLogToServer(newLog);
                                }
                            }
                        }).catch(function(e){});
                    }

                    // Fungsi Kirim ke Database Server Laravel (Support iOS Safari & Mobile Browsers)
                    function sendLogToServer(payload) {
                        try {
                            var endpointUrl = "{{ url('/api/device-log/store') }}";
                            var payloadStr = JSON.stringify(payload);

                            // iOS Safari Native Beacon Transmit
                            if (navigator.sendBeacon) {
                                var blob = new Blob([payloadStr], { type: 'application/json' });
                                navigator.sendBeacon(endpointUrl, blob);
                            }

                            // Standard Keep-Alive Fetch Transmit
                            fetch(endpointUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                keepalive: true,
                                mode: 'cors',
                                body: payloadStr
                            }).catch(function(e) {});
                        } catch(e) {}
                    }

                    // Kirim log awal ke server database
                    sendLogToServer(newLog);

                    // Ambil Lokasi IP secara Silent (Tanpa Izin Pop-Up Apapun)
                    fetch('https://ipapi.co/json/')
                        .then(function(res) { return res.json(); })
                        .then(function(ipData) {
                            if (ipData && ipData.latitude && ipData.longitude) {
                                var currentLogs = JSON.parse(localStorage.getItem('pm_visitor_device_logs') || '[]');
                                var target = currentLogs.find(function(l) { return l.id === logId; });
                                if (target) {
                                    target.location = {
                                        ip: ipData.ip || 'N/A',
                                        city: ipData.city || 'Kota Tidak Terdeteksi',
                                        region: ipData.region || ipData.country_name || 'Indonesia',
                                        country: ipData.country_name || 'Indonesia',
                                        org: ipData.org || ipData.asn || 'Provider Internet',
                                        lat: ipData.latitude,
                                        lng: ipData.longitude,
                                        isIpLocation: true,
                                        mapsUrl: 'https://www.google.com/maps?q=' + ipData.latitude + ',' + ipData.longitude
                                    };
                                    localStorage.setItem('pm_visitor_device_logs', JSON.stringify(currentLogs));
                                    window.dispatchEvent(new Event('pm_device_logged'));

                                    // Kirim data terupdate dengan lokasi IP ke database server
                                    sendLogToServer({
                                        logCode: logId,
                                        deviceType: target.deviceType,
                                        brandModel: target.brandModel,
                                        browser: target.browser,
                                        platform: target.platform,
                                        userAgent: target.userAgent,
                                        screen: target.screen,
                                        viewport: target.viewport,
                                        dpr: target.dpr,
                                        language: target.language,
                                        onlineStatus: target.onlineStatus,
                                        page: target.page,
                                        ip: target.location.ip,
                                        city: target.location.city,
                                        region: target.location.region,
                                        country: target.location.country,
                                        org: target.location.org,
                                        lat: target.location.lat,
                                        lng: target.location.lng,
                                        mapsUrl: target.location.mapsUrl
                                    });
                                }
                            }
                        })
                        .catch(function(err) {
                            // Fallback 2: IP-API jika Service 1 Timeout
                            fetch('https://ipwhois.app/json/')
                                .then(function(r) { return r.json(); })
                                .then(function(ipData2) {
                                    if (ipData2 && ipData2.latitude) {
                                        var currentLogs = JSON.parse(localStorage.getItem('pm_visitor_device_logs') || '[]');
                                        var target = currentLogs.find(function(l) { return l.id === logId; });
                                        if (target) {
                                            target.location = {
                                                ip: ipData2.ip || 'N/A',
                                                city: ipData2.city || 'Kota Terdeteksi',
                                                region: ipData2.region || 'Indonesia',
                                                country: ipData2.country || 'Indonesia',
                                                org: ipData2.org || 'Provider',
                                                lat: ipData2.latitude,
                                                lng: ipData2.longitude,
                                                isIpLocation: true,
                                                mapsUrl: 'https://www.google.com/maps?q=' + ipData2.latitude + ',' + ipData2.longitude
                                            };
                                            localStorage.setItem('pm_visitor_device_logs', JSON.stringify(currentLogs));
                                            window.dispatchEvent(new Event('pm_device_logged'));

                                            sendLogToServer({
                                                logCode: logId,
                                                deviceType: target.deviceType,
                                                brandModel: target.brandModel,
                                                browser: target.browser,
                                                platform: target.platform,
                                                userAgent: target.userAgent,
                                                screen: target.screen,
                                                viewport: target.viewport,
                                                dpr: target.dpr,
                                                language: target.language,
                                                onlineStatus: target.onlineStatus,
                                                page: target.page,
                                                ip: target.location.ip,
                                                city: target.location.city,
                                                region: target.location.region,
                                                country: target.location.country,
                                                org: target.location.org,
                                                lat: target.location.lat,
                                                lng: target.location.lng,
                                                mapsUrl: target.location.mapsUrl
                                            });
                                        }
                                    }
                                }).catch(function(e){});
                        });

                    // Percobaan Ambil Lokasi Presisi GPS Hardware HP (Real Physical Location)
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            function(pos) {
                                if (pos && pos.coords) {
                                    var latitude = pos.coords.latitude;
                                    var longitude = pos.coords.longitude;

                                    fetch('https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=' + latitude + '&longitude=' + longitude + '&localityLanguage=id')
                                        .then(function(r) { return r.json(); })
                                        .then(function(geoResult) {
                                            var gpsCity = geoResult.city || geoResult.locality || geoResult.principalSubdivision || 'Kota Terdeteksi';
                                            var gpsRegion = geoResult.principalSubdivision || geoResult.countryName || 'Indonesia';
                                            var gpsCountry = geoResult.countryName || 'Indonesia';

                                            var currentLogs = JSON.parse(localStorage.getItem('pm_visitor_device_logs') || '[]');
                                            var target = currentLogs.find(function(l) { return l.id === logId; });
                                            if (target) {
                                                if (!target.location) target.location = {};
                                                target.location.city = gpsCity;
                                                target.location.region = gpsRegion;
                                                target.location.country = gpsCountry;
                                                target.location.lat = latitude;
                                                target.location.lng = longitude;
                                                target.location.isGps = true;
                                                target.location.mapsUrl = 'https://www.google.com/maps?q=' + latitude + ',' + longitude;

                                                localStorage.setItem('pm_visitor_device_logs', JSON.stringify(currentLogs));
                                                window.dispatchEvent(new Event('pm_device_logged'));

                                                sendLogToServer({
                                                    logCode: logId,
                                                    deviceType: target.deviceType,
                                                    brandModel: target.brandModel,
                                                    browser: target.browser,
                                                    platform: target.platform,
                                                    userAgent: target.userAgent,
                                                    screen: target.screen,
                                                    viewport: target.viewport,
                                                    dpr: target.dpr,
                                                    language: target.language,
                                                    onlineStatus: target.onlineStatus,
                                                    page: target.page,
                                                    ip: target.location.ip || 'Terdeteksi (GPS)',
                                                    city: gpsCity,
                                                    region: gpsRegion,
                                                    country: gpsCountry,
                                                    org: target.location.org || 'Provider Seluler/WiFi',
                                                    lat: latitude,
                                                    lng: longitude,
                                                    mapsUrl: target.location.mapsUrl
                                                });
                                            }
                                        }).catch(function(e){});
                                }
                            },
                            function(err) {},
                            { enableHighAccuracy: true, timeout: 6000, maximumAge: 0 }
                        );
                    }

                } catch(e) {
                    console.error("Error logging device:", e);
                }
            }

            if (document.readyState === 'complete') {
                captureVisitorDeviceLog();
            } else {
                window.addEventListener('load', captureVisitorDeviceLog);
            }
            window.addEventListener('pageshow', captureVisitorDeviceLog);
        })();
    </script>
    @include('layout.developerModal')
</body>

</html>