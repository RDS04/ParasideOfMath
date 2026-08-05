<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-role" content="{{ Auth::user() ? Auth::user()->role : '' }}">
    <title>@yield('title', 'Paradise of Math')</title>

    @if(auth()->check() && auth()->user()->isAdmin())
        <!-- Firebase SDK Compat -->
        <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
        <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js"></script>
        <script>
            try {
                firebase.initializeApp({
                    apiKey: "{{ env('FIREBASE_API_KEY', 'mock-api-key') }}",
                    authDomain: "{{ env('FIREBASE_PROJECT_ID', 'mock-project') }}.firebaseapp.com",
                    projectId: "{{ env('FIREBASE_PROJECT_ID', 'mock-project') }}",
                    storageBucket: "{{ env('FIREBASE_PROJECT_ID', 'mock-project') }}.appspot.com",
                    messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID', 'mock-sender-id') }}",
                    appId: "{{ env('FIREBASE_APP_ID', 'mock-app-id') }}"
                });
                window.FCM_VAPID_KEY = "{{ env('FIREBASE_VAPID_KEY', '') }}";
            } catch(e) {
                console.error("Firebase Initialization Error:", e);
            }
        </script>
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        :root {
            --pom-violet-deep: #2e1065;
            --pom-violet: #4c1d95;
            --pom-violet-light: #7c3aed;
            --pom-amber: #f59e0b;
            --pom-amber-light: #fbbf24;
        }
        body, .wrapper { font-family: 'Inter', system-ui, -apple-system, sans-serif; background: #f4f2fa; }
        .main-sidebar { background: linear-gradient(180deg, var(--pom-violet-deep) 0%, var(--pom-violet) 100%) !important; }
        .brand-link { border-bottom: 1px solid rgba(255,255,255,0.08) !important; }
        .brand-image { max-height: 32px; }
        .brand-text { font-weight: 700; letter-spacing: .2px; }
        .sidebar .nav-sidebar > .nav-item > .nav-link { border-radius: 8px; margin: 2px 8px; color: rgba(255,255,255,0.75); }
        .sidebar .nav-sidebar > .nav-item > .nav-link:hover { background: rgba(255,255,255,0.08); color: #fff; }
        .sidebar .nav-sidebar > .nav-item > .nav-link.active { background: rgba(251, 191, 36, 0.16); color: #fbbf24; box-shadow: inset 3px 0 0 #fbbf24; }
        .sidebar .nav-sidebar .nav-icon { color: inherit; opacity: .9; }
        .nav-sidebar .nav-header { color: rgba(255,255,255,0.35); font-size: .72rem; letter-spacing: .6px; }
        .user-panel .info a { color: #fff; font-weight: 600; }
        .main-header.navbar { background: #fff; border-bottom: 1px solid #ece7f7; }
        .main-header .nav-link { color: #4b4560; }
        .main-header .nav-link:hover { color: var(--pom-violet-light); }
        .content-wrapper { background: #f4f2fa; }
        .small-box, .card { border-radius: 14px; border: 1px solid #ece7f7; box-shadow: 0 1px 2px rgba(46,16,101,0.04), 0 10px 24px -14px rgba(76,29,149,0.14); }
        .card-header { border-radius: 14px 14px 0 0 !important; border-bottom: 1px solid #f0edf9; }
        .btn-brand { background: linear-gradient(135deg, var(--pom-amber-light), var(--pom-amber)); border: none; color: #40206b; font-weight: 700; }
        .main-footer { background: #fff; border-top: 1px solid #ece7f7; color: #7a7391; font-size: .85rem; }
    </style>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">
    
    @include('layout.header')
    @include('layout.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper {{ (auth()->guard('siswa')->check() || (auth()->check() && auth()->user()->isGuru())) ? 'pb-24 md:pb-0' : '' }}">
        @yield('content')
    </div>

    @include('layout.footer')

    @if (auth()->guard('siswa')->check() || (auth()->check() && auth()->user()->isGuru()))
        @include('layout.sideMobile')
    @endif

    @if (auth()->guard('siswa')->check())

        @php
            $globalSiswa = auth()->guard('siswa')->user();
            $globalBiodata = $globalSiswa->biodata ?? [];
            $globalHariPertemuan = $globalBiodata['hari_pertemuan'] ?? [];
            $globalTanggalMulai = $globalBiodata['tanggal_mulai'] ?? null;
            
            // Fallback parsing
            if (empty($globalHariPertemuan) && $globalSiswa->tipe_paket) {
                if (preg_match('/Hari:\s*([^)|]+)/i', $globalSiswa->tipe_paket, $globalMatches)) {
                    $globalHariPertemuan = array_map('trim', explode(',', $globalMatches[1]));
                }
                if (preg_match('/Mulai:\s*([\d\-]+)/i', $globalSiswa->tipe_paket, $globalMatches)) {
                    $globalD = trim($globalMatches[1]);
                    if (preg_match('/(\d{2})-(\d{2})-(\d{4})/', $globalD, $globalDMatches)) {
                        $globalTanggalMulai = $globalDMatches[3] . '-' . $globalDMatches[2] . '-' . $globalDMatches[1];
                    }
                }
            }
            if (!$globalTanggalMulai && $globalSiswa->created_at) {
                $globalTanggalMulai = $globalSiswa->created_at->format('Y-m-d');
            }
            
            $globalPaket = $globalSiswa->paket;
            $globalJamMulai = $globalPaket ? ($globalPaket->jam_mulai ?? '15:30') : '15:30';
            $globalDurationMinutes = 90;
            if ($globalPaket && preg_match('/(\d+)\s*menit/i', $globalPaket->detail_5, $globalDurationMatches)) {
                $globalDurationMinutes = (int) $globalDurationMatches[1];
            }
            $globalJamSelesai = date('H:i', strtotime($globalJamMulai . " + {$globalDurationMinutes} minutes"));
            
            $globalJumlahPertemuan = $globalBiodata['jumlah_pertemuan'] ?? null;
            if (!$globalJumlahPertemuan && $globalSiswa->tipe_paket) {
                if (preg_match('/Sesi:\s*(\d+)x/i', $globalSiswa->tipe_paket, $globalMatches)) {
                    $globalJumlahPertemuan = (int) $globalMatches[1];
                }
            }
        @endphp
        
        @if(!empty($globalHariPertemuan) && $globalTanggalMulai && $globalJumlahPertemuan)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Hindari notifikasi berulang di navigasi halaman yang sama
                if (sessionStorage.getItem('notified_session_1')) return;
                
                const selectedDays = @json($globalHariPertemuan);
                const startDateStr = "{{ $globalTanggalMulai }}";
                const limitSesi = {{ $globalJumlahPertemuan }};
                const jamMulai = "{{ $globalJamMulai }}";
                const jamSelesai = "{{ $globalJamSelesai }}";
                
                const dayMap = {
                    'Minggu': 0, 'Senin': 1, 'Selasa': 2, 'Rabu': 3,
                    'Kamis': 4, 'Jumat': 5, 'Sabtu': 6
                };
                const scheduledDayNums = selectedDays.map(d => dayMap[d]);
                const startLimitDate = new Date(startDateStr);
                startLimitDate.setHours(0,0,0,0);
                
                // Hitung tanggal sesi
                const scheduledDates = [];
                let tempDate = new Date(startLimitDate);
                for (let d = 0; d < 365; d++) {
                    if (scheduledDates.length >= limitSesi) break;
                    const dayOfWeek = tempDate.getDay();
                    if (scheduledDayNums.includes(dayOfWeek)) {
                        const y = tempDate.getFullYear();
                        const m = String(tempDate.getMonth() + 1).padStart(2, '0');
                        const day = String(tempDate.getDate()).padStart(2, '0');
                        scheduledDates.push(`${y}-${m}-${day}`);
                    }
                    tempDate.setDate(tempDate.getDate() + 1);
                }
                
                if (scheduledDates.length > 0) {
                    const today = new Date();
                    const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
                    if (scheduledDates[0] === todayStr) {
                        sessionStorage.setItem('notified_session_1', 'true');
                        // Kirim Notifikasi Native Browser (HP / PC)
                        if ("Notification" in window) {
                            if (Notification.permission === "granted") {
                                showNativeNotification();
                            } else if (Notification.permission !== "denied") {
                                Notification.requestPermission().then(permission => {
                                    if (permission === "granted") {
                                        showNativeNotification();
                                    }
                                });
                            }
                        }
                    }
                }
                
                function showNativeNotification() {
                    new Notification("Sesi 1 Mulai Hari Ini!", {
                        body: `Jam Sesi: ${jamMulai} - ${jamSelesai}. Ayo bersiap untuk belajar di Paradise of Math!`,
                        icon: "/images/logoPM.webp"
                    });
                }
            });
        </script>
        @endif
    @endif

</div>
<!-- ./wrapper -->

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body> 
</html>