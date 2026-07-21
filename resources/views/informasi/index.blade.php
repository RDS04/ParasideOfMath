<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Paradise Of Math</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <!-- ================= HEADER ================= -->
    <header class="sticky top-0 z-50 bg-white border-b border-violet-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center justify-between py-3 md:py-4">

                <!-- Logo -->
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 md:w-10 md:h-10 rounded-lg overflow-hidden flex items-center justify-center">
                        <img src="{{ asset('images/logoPM.webp') }}" alt="logo" class="w-full h-full object-contain" />
                    </div>
                    <div class="font-bold text-base md:text-lg text-violet-950 whitespace-nowrap">
                        Paradise<span class="text-violet-500">Of</span>Math
                    </div>
                </div>

                <!-- Nav links (desktop) -->
                <ul class="hidden md:flex items-center gap-6 lg:gap-9 text-sm font-semibold text-slate-800">
                    <li>
                        <a href="#beranda" class="relative pb-1 text-violet-700 after:content-[''] after:absolute after:left-0 after:right-0 after:-bottom-0.5 after:h-0.5 after:bg-amber-400">
                            Beranda
                        </a>
                    </li>
                    <li><a href="#about" class="hover:text-violet-700 transition-colors">Tentang</a></li>
                    <li><a href="#matkul" class="hover:text-violet-700 transition-colors">Mata Pelajaran</a></li>
                    <li><a href="#kontak" class="hover:text-violet-700 transition-colors">Kontak</a></li>
                </ul>

                <!-- Auth buttons (desktop) -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="#" class="px-4 py-2 lg:px-5 lg:py-2.5 rounded-lg text-sm font-bold border-2 border-violet-800 text-violet-900 hover:bg-violet-50 transition-colors">
                        Login
                    </a>
                    <a href="#" class="px-4 py-2 lg:px-5 lg:py-2.5 rounded-lg text-sm font-bold bg-amber-400 text-violet-950 hover:bg-amber-300 transition-colors">
                        Register
                    </a>
                </div>

                <!-- Mobile menu button -->
                <button id="mobile-menu-toggle" class="md:hidden text-violet-900 p-1" aria-label="Buka menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

            </nav>

            <!-- Mobile menu (hidden by default) -->
            <div id="mobile-menu" class="md:hidden hidden pb-4 border-t border-violet-100">
                <ul class="flex flex-col gap-3 pt-4 text-sm font-semibold text-slate-800">
                    <li><a href="#" class="block text-violet-700">Beranda</a></li>
                    <li><a href="#tentang" class="block hover:text-violet-700 transition-colors">Tentang</a></li>
                    <li><a href="#kelas" class="block hover:text-violet-700 transition-colors">Mata Pelajaran</a></li>
                    <li><a href="#kontak" class="block hover:text-violet-700 transition-colors">Kontak</a></li>
                    <li class="pt-2 flex flex-col sm:flex-row gap-3">
                        <a href="#" class="text-center px-5 py-2.5 rounded-lg text-sm font-bold border-2 border-violet-800 text-violet-900 hover:bg-violet-50 transition-colors">
                            Login
                        </a>
                        <a href="#" class="text-center px-5 py-2.5 rounded-lg text-sm font-bold bg-amber-400 text-violet-950 hover:bg-amber-300 transition-colors">
                            Register
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- ================= HERO ================= -->
    <section id="beranda" class="bg-gradient-to-br from-violet-950 to-violet-700 py-16 sm:py-20 md:py-28">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-10 items-center">

            <!-- Kiri -->
            <div class="text-center md:text-left">
                <span class="inline-block text-amber-400 font-bold text-sm tracking-wide mb-4">
                    Bimbingan Belajar Terpercaya
                </span>
                <h1 class="font-extrabold text-3xl sm:text-4xl md:text-5xl leading-tight text-white mb-5">
                    Solusi Terbaik <span class="text-amber-400">Sukses Akademik!</span>
                </h1>
                <p class="text-violet-100 text-sm sm:text-base max-w-md mx-auto md:mx-0 mb-8">
                    Menawarkan bimbingan belajar yang berkualitas dengan tutor pribadi berpengalaman.
                </p>
                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    <a href="#kelas" class="px-6 py-3 rounded-lg bg-amber-400 text-violet-950 font-bold hover:bg-amber-300 transition-colors">
                        Daftar Sekarang
                    </a>
                    <a href="#tentang" class="px-6 py-3 rounded-lg border-2 border-white/40 text-white font-bold hover:bg-white/10 transition-colors">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>

            <!-- Kanan (gambar) -->
            <div class="flex justify-center">
                <div class="w-full max-w-xs sm:max-w-sm aspect-[4/5] rounded-2xl bg-violet-500/40 flex items-center justify-center overflow-hidden">
                    <span class="text-violet-100/60 text-xs sm:text-sm font-mono text-center px-4">
                        [ foto siswa/tutor — public/images/hero-student.jpg ]
                    </span>
                </div>
            </div>

        </div>
    </section>

    <!-- ================= ABOUT ================= -->
    <section id="about" class="py-16 sm:py-20 md:py-24 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-12 md:gap-14 items-start">

            <!-- Gambar ilustrasi -->
            <div class="flex justify-center md:justify-start order-1 md:order-none">
                <div class="w-full max-w-xs sm:max-w-sm aspect-square rounded-2xl bg-violet-100 flex items-center justify-center overflow-hidden">
                    <span class="text-violet-400 text-xs sm:text-sm font-mono text-center px-4">
                        [ ilustrasi kartun — public/images/about-kartun.png ]
                    </span>
                </div>
            </div>

            <!-- Daftar poin -->
            <div class="order-2 md:order-none">
                <ol class="space-y-5 sm:space-y-6 mb-8 sm:mb-10">
                    <li class="flex gap-4">
                        <span class="shrink-0 w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-violet-800 text-white flex items-center justify-center font-mono font-bold text-xs sm:text-sm">01</span>
                        <div>
                            <h3 class="font-extrabold text-violet-950 text-sm sm:text-base mb-0.5">EXCLUSIVE CLASS</h3>
                            <p class="text-slate-600 text-xs sm:text-sm">Dapatkan perhatian penuh dan pembelajaran personal yang interaktif dan intensif, sesuai dengan kebutuhan individual siswa.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <span class="shrink-0 w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-violet-800 text-white flex items-center justify-center font-mono font-bold text-xs sm:text-sm">02</span>
                        <div>
                            <h3 class="font-extrabold text-violet-950 text-sm sm:text-base mb-0.5">TUTOR SELALU STAND BY</h3>
                            <p class="text-slate-600 text-xs sm:text-sm">Bingung dengan materi? Tutor kami selalu siap menjelaskan hingga siswa paham sepenuhnya.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <span class="shrink-0 w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-violet-800 text-white flex items-center justify-center font-mono font-bold text-xs sm:text-sm">03</span>
                        <div>
                            <h3 class="font-extrabold text-violet-950 text-sm sm:text-base mb-0.5">BELAJAR SESUAI KURIKULUM ANDA</h3>
                            <p class="text-slate-600 text-xs sm:text-sm">Materi bimbel menyesuaikan dengan kurikulum sekolah individual siswa, memastikan siswa selalu selangkah lebih maju.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <span class="shrink-0 w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-violet-800 text-white flex items-center justify-center font-mono font-bold text-xs sm:text-sm">04</span>
                        <div>
                            <h3 class="font-extrabold text-violet-950 text-sm sm:text-base mb-0.5">DUKUNGAN PENUH UNTUK PERSIAPAN UJIAN</h3>
                            <p class="text-slate-600 text-xs sm:text-sm">Strategi belajar jangka panjang dari tutor berpengalaman, siap membantu siswa sukses dalam ujian sekolah hingga SNBT.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <span class="shrink-0 w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-violet-800 text-white flex items-center justify-center font-mono font-bold text-xs sm:text-sm">05</span>
                        <div>
                            <h3 class="font-extrabold text-violet-950 text-sm sm:text-base mb-0.5">LINGKUNGAN BELAJAR KONDUSIF</h3>
                            <p class="text-slate-600 text-xs sm:text-sm">Nikmati suasana belajar yang nyaman dan mendukung dengan fasilitas unggulan.</p>
                        </div>
                    </li>
                </ol>

                <div class="border-t border-violet-100 pt-6 sm:pt-8">
                    <h3 class="text-amber-500 font-mono font-bold text-xs sm:text-sm mb-1 sm:mb-2">1000+ siswa telah membuktikan</h3>
                    <h2 class="font-extrabold text-xl sm:text-2xl text-violet-950 leading-snug">
                        Raih Nilai Terbaik hingga Masuk Sekolah/PTN Impian!
                    </h2>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= SCRIPT TOGGLE MOBILE MENU ================= -->
    <script>
        (function() {
            const toggleBtn = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');

            if (toggleBtn && mobileMenu) {
                toggleBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        })();
    </script>

</body>
</html>