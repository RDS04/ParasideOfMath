<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-role" content="{{ Auth::user() ? Auth::user()->role : '' }}">
    <title>@yield('title', 'Paradise of Math')</title>

    <!-- Favicon / Tab Icon -->
    <link rel="icon" type="image/webp" href="{{ asset('images/logoPM.webp') }}">
    <link rel="shortcut icon" type="image/webp" href="{{ asset('images/logoPM.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logoPM.webp') }}">

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

    @php
        $appUser = auth()->guard('siswa')->user() ?? auth()->guard('web')->user();
        $isMobileLayout = $appUser && ($appUser->isSiswa() || $appUser->isGuru());
    @endphp

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper {{ $isMobileLayout ? 'pb-24 md:pb-0' : '' }}">
        @yield('content')
    </div>

    @include('layout.footer')
    @include('layout.developerModal')

    @if ($isMobileLayout)
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

@php
    $authUser = auth()->guard('siswa')->user() ?? auth()->guard('web')->user();
    $pollEndpoint = null;
    $chatTargetUrl = null;
    $userType = null;

    if ($authUser) {
        if ($authUser->isAdmin()) {
            $pollEndpoint = url('/admin/chat/sessions');
            $chatTargetUrl = route('admin.chat');
            $userType = 'admin';
        } elseif ($authUser->isGuru()) {
            $pollEndpoint = route('guru.chat.contacts');
            $chatTargetUrl = route('guru.chat.index');
            $userType = 'guru';
        } elseif ($authUser->isSiswa()) {
            $pollEndpoint = route('siswa.chat.contacts');
            $chatTargetUrl = route('siswa.chat.index');
            $userType = 'siswa';
        }
    }
@endphp

@if($pollEndpoint)
<!-- Floating Notification Permission Prompt Toast if permission is default -->
<div id="global-notif-toast" class="fixed bottom-4 right-4 z-50 bg-purple-900 text-white px-4 py-3 rounded-2xl shadow-2xl border border-purple-700 flex items-center gap-3 hidden animate-bounce">
    <div class="w-8 h-8 rounded-full bg-amber-400 text-purple-950 flex items-center justify-center font-bold flex-shrink-0">
        <i class="fas fa-bell"></i>
    </div>
    <div class="text-xs">
        <p class="font-bold mb-0">Aktifkan Notifikasi Chat Perangkat</p>
        <p class="text-[10px] text-purple-200 mb-0">Terima pesan masuk langsung di sistem PC/HP Anda.</p>
    </div>
    <button onclick="requestGlobalNotifPermission()" class="bg-amber-400 hover:bg-amber-300 text-purple-950 text-xs font-black px-3 py-1.5 rounded-xl shadow transition">
        Izinkan
    </button>
    <button onclick="this.parentElement.remove()" class="text-purple-300 hover:text-white text-xs px-1">
        <i class="fas fa-times"></i>
    </button>
</div>

<script>
    function requestGlobalNotifPermission() {
        if (!("Notification" in window)) {
            alert("Browser Anda tidak mendukung Notifikasi Perangkat.");
            return;
        }
        Notification.requestPermission().then(perm => {
            const toast = document.getElementById('global-notif-toast');
            if (toast) toast.remove();
            if (perm === "granted") {
                try {
                    new Notification("Notifikasi Berhasil Diaktifkan! 🔔", {
                        body: "Anda akan menerima pemberitahuan pesan chat meskipun sedang berada di Dashboard.",
                        icon: '/images/logoPM.webp'
                    });
                } catch(e) {}
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const pollEndpoint = "{{ $pollEndpoint }}";
        const chatTargetUrl = "{{ $chatTargetUrl }}";
        const userType = "{{ $userType }}";

        // Persistent unread map across tab navigation
        let globalUnreadCounts = {};
        try {
            globalUnreadCounts = JSON.parse(localStorage.getItem('pm_unread_map_' + userType) || '{}');
        } catch(e) {}

        let isFirstGlobalPoll = true;

        // Check if permission is default -> show toast after 2s
        if ("Notification" in window && Notification.permission === "default") {
            setTimeout(() => {
                const toast = document.getElementById('global-notif-toast');
                if (toast) toast.classList.remove('hidden');
            }, 2000);
        }

        // Web Audio Chime Sound Player
        function playNotificationChime() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
                osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.12); // A5
                gain.gain.setValueAtTime(0.2, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.3);
            } catch(e) {}
        }

        function triggerGlobalDesktopNotif(title, body) {
            if (!("Notification" in window) || Notification.permission !== "granted") return;
            try {
                const notif = new Notification(title, {
                    body: body || 'Ada pesan baru masuk.',
                    icon: '/images/logoPM.webp',
                    badge: '/images/logoPM.webp',
                    tag: 'global-chat-' + Date.now(),
                    renotify: true
                });
                notif.onclick = function() {
                    window.focus();
                    window.location.href = chatTargetUrl;
                    this.close();
                };
                playNotificationChime();
            } catch(e) {
                console.error("Global notification error:", e);
            }
        }

        function pollGlobalChatNotifications() {
            // Skip when user is actively inside the chat screen
            if (window.location.pathname.includes('/chat')) return;

            fetch(pollEndpoint)
                .then(res => {
                    if (!res.ok) return null;
                    return res.json();
                })
                .then(data => {
                    if (!data || !Array.isArray(data)) return;

                    let mapChanged = false;

                    data.forEach(item => {
                        const id = item.session_id;
                        const unread = item.unread_count || 0;
                        const prevUnread = (id in globalUnreadCounts) ? globalUnreadCounts[id] : 0;

                        if (unread > prevUnread) {
                            let title = '';
                            let body = item.last_message || 'Mengirim pesan baru...';

                            if (userType === 'admin') {
                                const roleLabel = item.user_role ? ` (${item.user_role})` : ' (Pengunjung)';
                                title = `💬 Chat Masuk: ${item.sender_name || 'Anonim'}${roleLabel}`;
                            } else if (userType === 'guru') {
                                title = `💬 Chat Siswa: ${item.contact_name || 'Siswa'}`;
                            } else if (userType === 'siswa') {
                                title = `💬 Chat Guru: ${item.contact_name || 'Guru'}`;
                            }

                            triggerGlobalDesktopNotif(title, body);
                        }

                        if (globalUnreadCounts[id] !== unread) {
                            globalUnreadCounts[id] = unread;
                            mapChanged = true;
                        }
                    });

                    if (mapChanged) {
                        try {
                            localStorage.setItem('pm_unread_map_' + userType, JSON.stringify(globalUnreadCounts));
                        } catch(e) {}
                    }

                    isFirstGlobalPoll = false;
                })
                .catch(err => {});
        }

        pollGlobalChatNotifications();
        setInterval(pollGlobalChatNotifications, 4000);
    });
</script>
@endif

</div>
<!-- ./wrapper -->

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body> 
</html>