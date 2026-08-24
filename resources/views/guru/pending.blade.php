<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pendaftaran Dalam Peninjauan · Paradise of Math</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: radial-gradient(ellipse 900px 700px at 15% -10%, rgba(251, 191, 36, 0.16), transparent 60%),
                        radial-gradient(ellipse 700px 600px at 100% 110%, rgba(192, 132, 252, 0.20), transparent 55%),
                        linear-gradient(160deg, #1e1b4b 0%, #2e1065 45%, #4c1d95 100%);
            min-height: 100vh;
        }
        .font-display {
            font-family: 'Fraunces', Georgia, serif;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 min-h-screen text-slate-100">
    <div class="max-w-lg w-full bg-white/10 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-white/20 shadow-2xl text-center relative overflow-hidden">
        <!-- Accent Glow Header -->
        <div class="w-20 h-20 bg-amber-500/20 text-amber-300 rounded-full flex items-center justify-center mx-auto mb-6 border border-amber-400/30 shadow-inner">
            <i class="fas fa-clock-rotate-left fa-3x animate-pulse"></i>
        </div>

        <span class="inline-block px-3.5 py-1.5 bg-amber-500/20 text-amber-300 border border-amber-400/30 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
            <i class="fas fa-hourglass-half mr-1.5"></i> Menunggu Approval Admin
        </span>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-white mb-3 tracking-tight font-display">
            Pendaftaran Dalam Peninjauan
        </h1>

        <p class="text-slate-300 text-sm leading-relaxed mb-6">
            Terima kasih telah mendaftar sebagai pengajar di <strong class="text-amber-300 font-semibold">Paradise of Math</strong>. Akun registrasi Anda saat ini sedang dalam peninjauan dan verifikasi oleh tim Admin kami.
        </p>

        @if (session('info'))
            <div class="mb-5 p-3.5 bg-amber-500/20 text-amber-200 border border-amber-400/30 rounded-2xl text-xs font-semibold text-left flex items-start gap-2.5">
                <i class="fas fa-exclamation-triangle text-amber-300 text-sm mt-0.5 flex-shrink-0"></i>
                <div>{{ session('info') }}</div>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-5 p-3.5 bg-emerald-500/20 text-emerald-200 border border-emerald-400/30 rounded-2xl text-xs font-semibold text-left flex items-start gap-2.5">
                <i class="fas fa-check-circle text-emerald-300 text-sm mt-0.5 flex-shrink-0"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 p-3.5 bg-rose-500/20 text-rose-200 border border-rose-400/30 rounded-2xl text-xs font-semibold text-left flex items-start gap-2.5">
                <i class="fas fa-times-circle text-rose-300 text-sm mt-0.5 flex-shrink-0"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <!-- Status Card Alert -->
        <div class="bg-slate-900/60 rounded-2xl p-4 border border-white/10 text-left mb-6 space-y-2">
            <div class="flex items-center justify-between text-xs">
                <span class="text-slate-400 font-semibold uppercase">Status Registrasi</span>
                <span class="text-amber-400 font-bold bg-amber-500/10 px-2.5 py-0.5 rounded-full border border-amber-500/30">
                    PENDING / MENUNGGU PERSETUJUAN
                </span>
            </div>
            <p class="text-xs text-slate-300 pt-1 border-t border-white/10">
                <i class="fas fa-info-circle text-amber-400 mr-1"></i>
                Setelah Admin menyetujui pendaftaran Anda, Anda dapat melakukan login dan langsung mengakses Dashboard Pengajar.
            </p>
        </div>

        <div class="space-y-3">
            <a href="{{ route('login') }}" class="block w-full py-3 px-4 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-purple-950 font-bold rounded-xl text-sm transition-all duration-200 shadow-lg">
                <i class="fas fa-sign-in-alt mr-2"></i> Kembali ke Halaman Login
            </a>
            <a href="/" class="block w-full py-2.5 px-4 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl text-xs transition-all duration-200 border border-white/10">
                <i class="fas fa-home mr-1.5"></i> Ke Halaman Utama (Home)
            </a>
        </div>
    </div>
</body>
</html>
