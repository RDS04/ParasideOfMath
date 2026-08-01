<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Ditinjau · Paradise of Math</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f4f2fa 0%, #ece9f5 100%);
        }
        .font-display {
            font-family: 'Fraunces', Georgia, serif;
        }
        .pulse-icon {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }
        .btn-wa {
            background: #25d366;
            color: white;
            font-weight: 700;
            padding: 12px 24px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.2);
            border: none;
            cursor: pointer;
        }
        .btn-wa:hover {
            background: #20ba5a;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 211, 102, 0.3);
            color: white;
            text-decoration: none;
        }
        .btn-outline {
            border: 1.5px solid #ece7f7;
            background: transparent;
            color: #64748b;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 12px;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .btn-outline:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #334155;
            text-decoration: none;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-8">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl border border-purple-100 p-8 text-center relative overflow-hidden">
        
        <!-- Top decorative border color -->
        <div class="absolute top-0 left-0 w-full h-2 bg-amber-400"></div>

        <!-- Clock status icon -->
        <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mx-auto mb-6 pulse-icon border border-amber-100">
            <i class="far fa-clock text-3xl"></i>
        </div>

        <h1 class="font-display text-2xl font-bold text-purple-950 mb-3">Pembayaran Ditinjau</h1>
        <p class="text-sm text-slate-500 leading-relaxed mb-6">
            Terima kasih! Bukti transfer Anda telah kami terima dan saat ini sedang dalam proses verifikasi oleh Admin. Akun belajar Anda akan aktif otomatis maksimal dalam waktu 1x24 jam.
        </p>

        <!-- Summary box -->
        @if ($siswa && $paket)
            <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl text-left mb-8">
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Rincian Paket Pilihan</span>
                <span class="d-block text-sm font-bold text-purple-950">{{ $paket->nama_paket }}</span>
                <span class="d-block text-xs text-slate-500 mt-0.5">{{ $siswa->tipe_paket }}</span>
                <div class="mt-3 pt-3 border-t border-slate-200/60 flex items-center justify-between">
                    <span class="text-xs text-slate-400 font-medium">Status Pendaftaran</span>
                    <span class="px-2.5 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold rounded-full uppercase tracking-wider">
                        Pending
                    </span>
                </div>
            </div>
        @endif

        <!-- Call to actions -->
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ $waUrl }}" 
               target="_blank" class="btn-wa justify-center">
                <i class="fab fa-whatsapp text-lg"></i> Hubungi Admin
            </a>
            
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn-outline w-full sm:w-auto">
                    Keluar
                </button>
            </form>
        </div>

    </div>

    <!-- Script to clear onboarding local storage data after completion -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Remove wizard step tracker
            localStorage.removeItem('siswa_biodata_step');
            localStorage.removeItem('siswa_regis_step');

            // Find and remove all form field inputs from local storage
            const keysToRemove = [];
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (key && (key.startsWith('siswa_biodata_') || key.startsWith('siswa_regis_'))) {
                    keysToRemove.push(key);
                }
            }
            keysToRemove.forEach(key => localStorage.removeItem(key));
        });
    </script>
</body>
</html>
