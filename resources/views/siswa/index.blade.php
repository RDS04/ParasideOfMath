<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - Paradise of Math</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #faf9fc; }
        .gradient-bg {
            background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-lg bg-white rounded-3xl shadow-xl overflow-hidden border border-purple-100">
        <!-- Top accent banner -->
        <div class="gradient-bg px-8 py-10 text-white relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-purple-600/20 blur-xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 rounded-full bg-amber-500/10 blur-xl"></div>
            
            <div class="relative z-10">
                <span class="px-3 py-1 bg-amber-400 text-purple-950 text-xs font-bold uppercase tracking-wider rounded-full shadow-md">
                    Portal Siswa
                </span>
                <h1 class="text-3xl font-serif mt-4">Paradise of Math</h1>
                <p class="text-purple-200 text-sm mt-1">Platform Belajar & Bimbingan Privat Matematika</p>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-8 space-y-6">
            <div class="space-y-2">
                <h2 class="text-2xl font-bold text-purple-950">Selamat Datang!</h2>
                <p class="text-gray-600 leading-relaxed">
                    Halo, <span class="font-semibold text-purple-700">{{ Auth::guard('siswa')->user()->name }}</span>! Sesi belajar aktif Anda sudah siap. Mulai jelajahi topik aljabar, geometri, atau kalkulus hari ini.
                </p>
            </div>

            <!-- Profile Info card -->
            <div class="bg-purple-50/50 border border-purple-100 rounded-2xl p-5 space-y-3">
                <div class="flex justify-between items-center text-sm border-b border-purple-100/50 pb-2">
                    <span class="text-gray-500 font-medium">Email Terdaftar</span>
                    <span class="text-purple-950 font-semibold">{{ Auth::guard('siswa')->user()->email }}</span>
                </div>
                <div class="flex justify-between items-center text-sm border-b border-purple-100/50 pb-2">
                    <span class="text-gray-500 font-medium">Status Akun</span>
                    <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full uppercase">Aktif</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500 font-medium">Peran Pengguna</span>
                    <span class="px-2.5 py-0.5 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full uppercase">siswa</span>
                </div>
            </div>

            <!-- Action Area -->
            <div class="pt-2 flex flex-col sm:flex-row gap-4">
                <a href="/" class="flex-1 text-center py-3 bg-purple-100 text-purple-700 font-semibold rounded-xl hover:bg-purple-200 transition duration-200">
                    Halaman Utama
                </a>
                
                <form action="{{ route('logout') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-amber-400 text-purple-950 font-bold rounded-xl hover:bg-amber-500 transition duration-200 shadow-md shadow-amber-400/20">
                        Keluar (Logout)
                    </button>
                </form>
            </div>
        </div>
        
        <div class="bg-gray-50 px-8 py-4 text-center border-t border-gray-100">
            <span class="text-xs text-gray-400">© 2026 · Paradise of Math — Sistem Manajemen Pengguna</span>
        </div>
    </div>
</body>
</html>
