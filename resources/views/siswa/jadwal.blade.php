@extends('layout.app')

@section('title', 'Jadwal Belajar · Paradise of Math')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-purple-950">Jadwal Belajar Saya</h1>
                <p class="text-sm text-muted mb-0">Atur dan pantau jadwal pertemuan bimbingan belajar Anda secara langsung.</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right text-sm">
                    <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}" class="text-purple-600">Home</a></li>
                    <li class="breadcrumb-item active">Jadwal Belajar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(!empty($hariPertemuan) && $tanggalMulai)
        <!-- Notification Alert Banner Placeholder -->
        <div id="sessionNotificationBanner" class="alert alert-warning alert-dismissible fade show rounded-xl shadow-sm border-0 mb-4 d-none" role="alert" style="background-color: #faf5ff; border-left: 5px solid #7c3aed !important; color: #2e1065;">
            <h5><i class="icon fas fa-bell text-purple-600 mr-2"></i> Pengumuman Sesi Belajar!</h5>
            <span id="sessionNotificationText"></span>
            <button type="button" class="close text-purple-950" data-dismiss="alert" aria-label="Close" style="color: #2e1065; outline: none; border: 0; background: transparent;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if(isset($hasGuru) && !$hasGuru)
        <!-- Guru Belum Ditentukan Notification Alert Banner -->
        <div class="alert alert-danger alert-dismissible fade show rounded-xl shadow-sm border-0 mb-4" role="alert" style="background-color: #fff1f2; border-left: 5px solid #f43f5e !important; color: #9f1239;">
            <h5><i class="icon fas fa-exclamation-triangle text-rose-600 mr-2"></i> Perhatian: Guru Pendamping Belum Ditentukan!</h5>
            <span>Guru pendamping untuk pembelajaran Anda belum diinput oleh Admin. Silakan hubungi Admin atau pihak pengelola bimbingan belajar untuk segera menginputkan guru pendamping bimbingan belajar Anda agar sesi berjalan optimal.</span>
            <button type="button" class="close text-rose-950" data-dismiss="alert" aria-label="Close" style="color: #9f1239; outline: none; border: 0; background: transparent;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if(empty($hariPertemuan) || !$tanggalMulai)
            <!-- Belum Ada Jadwal / Review State -->
            <div class="card border-0 shadow-sm overflow-hidden text-center py-5 px-4" style="border-radius: 20px;">
                <div class="card-body">
                    <div class="mb-4">
                        <i class="far fa-calendar-times fa-4x text-purple-300"></i>
                    </div>
                    <h4 class="font-weight-bold text-purple-950">Jadwal Belajar Belum Tersedia</h4>
                    <p class="text-muted max-w-md mx-auto mb-4">
                        Anda belum memiliki jadwal bimbingan yang aktif. Pastikan Anda telah menyelesaikan pembayaran pendaftaran dan data Anda telah disetujui oleh Admin.
                    </p>
                    <a href="{{ route('siswa.register-kategori') }}" class="btn btn-brand px-4 py-2 font-weight-bold text-sm rounded-xl">
                        Daftar Paket Belajar <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        @else
            <!-- Rincian & Kalender Container -->
            <div class="row">
                <!-- Left Column: Kalender Interaktif (8 cols) -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title font-weight-bold text-purple-950 mb-0">Kalender Bimbingan</h5>
                            <!-- Navigasi Bulan -->
                            <div class="d-flex align-items-center gap-2">
                                <button id="btnPrevMonth" class="btn btn-light rounded-circle border-0 shadow-sm" style="width: 38px; height: 38px; color: #7c3aed;">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <h6 id="currentMonthYear" class="font-weight-bold text-purple-950 px-3 mb-0 text-center" style="min-width: 140px;">Agustus 2026</h6>
                                <button id="btnNextMonth" class="btn btn-light rounded-circle border-0 shadow-sm" style="width: 38px; height: 38px; color: #7c3aed;">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <!-- Hari dalam Seminggu -->
                            <div class="grid grid-cols-7 text-center font-weight-bold text-xs uppercase text-muted mb-2 py-2 border-bottom border-light">
                                <div class="text-danger">Min</div>
                                <div>Sen</div>
                                <div>Sel</div>
                                <div>Rab</div>
                                <div>Kam</div>
                                <div>Jum</div>
                                <div>Sab</div>
                            </div>
                            <!-- Grid Hari (JavaScript generated) -->
                            <div id="calendarDaysGrid" class="grid grid-cols-7 gap-1 text-center font-weight-bold text-sm">
                                <!-- Day cells inserted here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Detail Jadwal (4 cols) -->
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%); color: #fff;">
                        <div class="card-body p-4">
                            <span class="px-2.5 py-1 bg-amber-400 text-purple-950 text-xxs font-black uppercase tracking-wider rounded-full shadow-sm">
                                Status Aktif
                            </span>
                            <h5 class="font-weight-bold mt-3 mb-1">Bimbel Anda</h5>

                            <div class="border-top border-purple-800 my-3"></div>

                            <div class="space-y-2 text-xs">
                                {{-- Per-mapel display --}}
                                @if(!empty($mapelJadwal))
                                    @php
                                        $mapelColors = ['#7c3aed','#2563eb','#059669','#d97706','#e11d48','#0891b2'];
                                    @endphp
                                    @foreach($mapelJadwal as $idx => $namaMapel)
                                        @php
                                            $hariRaw   = $hariPerMapel[$idx] ?? [];
                                            $hariList  = is_array($hariRaw) ? array_values(array_filter($hariRaw)) : [];
                                            $tgl       = $tanggalPerMapel[$idx] ?? null;
                                            $sesiIdx   = $sesiPerMapel[$idx] ?? 0;
                                            $colorIdx  = $idx % count($mapelColors);
                                            $dotColor  = $mapelColors[$colorIdx];
                                        @endphp
                                        <div class="mb-2 p-2 rounded-xl" style="background:rgba(255,255,255,0.08);">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span style="width:8px;height:8px;border-radius:50%;background:{{ $dotColor }};display:inline-block;flex-shrink:0;"></span>
                                                <span class="font-weight-bold text-sm">{{ $namaMapel }}</span>
                                                <span class="ml-auto text-xxs" style="background:rgba(255,255,255,0.15);padding:2px 7px;border-radius:99px;">{{ $sesiIdx }}x</span>
                                            </div>
                                            @if(!empty($hariList))
                                            <div style="font-size:11px;color:#c4b5fd;margin-left:16px;">
                                                📅 {{ implode(' & ', $hariList) }}
                                                @if($tgl)
                                                · Mulai {{ \Carbon\Carbon::parse($tgl)->format('d M Y') }}
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    {{-- Fallback: tampilan lama --}}
                                    <div>
                                        <span class="text-purple-300 font-semibold block uppercase tracking-wider text-[10px]">Hari Les Seminggu</span>
                                        <span class="font-bold text-sm">{{ implode(', ', $hariPertemuan) }}</span>
                                    </div>
                                @endif

                                <div class="mt-2">
                                    <span class="text-purple-300 font-semibold block uppercase tracking-wider text-[10px]">Tanggal Mulai Les</span>
                                    <span class="font-bold text-sm">{{ $tanggalMulai ? date('d F Y', strtotime($tanggalMulai)) : '-' }}</span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-purple-300 font-semibold block uppercase tracking-wider text-[10px]">Jam Belajar</span>
                                    <span class="font-bold text-sm">{{ $jamMulai }} - {{ $jamSelesai }}</span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-purple-300 font-semibold block uppercase tracking-wider text-[10px]">Total Sesi</span>
                                    <span class="font-bold text-sm">{{ $jumlahPertemuan ?? '-' }}x Pertemuan</span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-purple-300 font-semibold block uppercase tracking-wider text-[10px]">Sesi Berakhir</span>
                                    <span id="sessionEndDateVal" class="font-bold text-sm">-</span>
                                </div>
                                @if(!empty($mapels))
                                <div class="mt-2">
                                    <span class="text-purple-300 font-semibold block uppercase tracking-wider text-[10px]">Mata Pelajaran</span>
                                    <span class="font-bold text-sm">{{ implode(', ', $mapels) }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Agenda Sesi Belajar -->
                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="card-title font-weight-bold text-purple-950 mb-0">Agenda Semua Sesi Belajar ({{ $jumlahPertemuan ?: 0 }} Sesi)</h6>
                        </div>
                        <div class="card-body p-0 max-h-[300px] overflow-y-auto" id="agendaListContainer" style="max-height: 300px; overflow-y: auto;">
                            <!-- Agenda sessions rendered dynamically here -->
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Section Menu Akademik -->
        
    </div>
</section>


<!-- Click Event Detail Modal -->
<div class="modal fade" id="sessionDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 18px; overflow: hidden;">
            <div class="modal-header bg-purple-950 text-white border-0 py-3" style="background-color: #2e1065;">
                <h5 class="modal-title font-weight-bold text-md" id="modalTitle" style="color: #fff;">Detail Sesi Les</h5>
                <button type="button" class="close text-white border-0 bg-transparent" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; outline: none; color: #fff;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <i class="fas fa-graduation-cap fa-3x text-amber-500"></i>
                </div>
                <h5 class="font-weight-bold text-purple-950 mb-1" style="color: #2e1065;">Sesi Bimbingan Les</h5>
                <div class="mb-2" id="modalBadgeContainer">
                    <span id="modalSessionIndex" class="badge badge-warning text-purple-950 font-weight-bold px-3 py-1.5 rounded-full text-xs">Sesi 1 dari 9</span>
                </div>
                <p id="modalDate" class="text-sm font-semibold text-purple-700 mb-4" style="color: #7c3aed;">Senin, 10 Agustus 2026</p>
                
                <div id="modalSessionsList">
                    <!-- Dynamic session details per mapel inserted here -->
                </div>

                <p class="text-slate-400 text-xxs mb-0 mt-3" style="font-size: 11px;">Hubungi Admin jika Anda ingin merubah atau menjadwalkan ulang sesi ini.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .grid { display: grid; }
    .grid-cols-7 { grid-template-columns: repeat(7, minmax(0, 1fr)); }
    .gap-1 { gap: 0.25rem; }
    .gap-2 { gap: 0.5rem; }
    .gap-3 { gap: 0.75rem; }
    .gap-4 { gap: 1rem; }
    
    .calendar-day-cell {
        aspect-ratio: 1 / 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border-radius: 12px;
        position: relative;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        background-color: #fff;
    }
    .calendar-day-cell:hover {
        background-color: #f5f3ff;
        transform: translateY(-1px);
    }
    .calendar-day-cell.other-month {
        opacity: 0.3;
        cursor: default;
        pointer-events: none;
    }
    .calendar-day-cell.scheduled {
        background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%) !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(124, 58, 237, 0.25);
    }
    .calendar-day-cell.scheduled:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 14px rgba(124, 58, 237, 0.35);
    }
    .calendar-day-cell.today {
        border-color: #f59e0b;
        background-color: #fffbeb;
    }
    .calendar-day-cell.scheduled.today {
        border-color: #fff;
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important;
        color: #40206b !important;
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.25);
    }

    .calendar-day-cell.scheduled.completed {
        background: transparent !important;
        color: #64748b !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: none !important;
    }
    
    .schedule-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background-color: #fff;
        position: absolute;
        bottom: 6px;
    }
    .calendar-day-cell.scheduled.completed .schedule-dot {
        background-color: #94a3b8 !important;
    }

    .agenda-item {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .agenda-item:hover {
        background-color: #fdfcff !important;
        transform: translateX(2px);
    }
    .agenda-item.today-agenda {
        border-left-color: #f59e0b;
    }
    .badge-purple {
        background-color: #7c3aed;
        color: #fff;
    }

    .academic-menu-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1.25rem 0.25rem;
    }

    @media (max-width: 480px) {
        .academic-menu-label {
            font-size: 0.62rem !important;
            max-width: 75px !important;
        }
        .academic-menu-icon-wrapper {
            width: 46px !important;
            height: 46px !important;
            font-size: 1.1rem !important;
            border-radius: 14px !important;
        }
    }

    .academic-menu-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 0.75rem 0.5rem;
        border-radius: 16px;
    }
    .academic-menu-item:hover {
        transform: translateY(-3px);
        background-color: #faf8ff;
    }
    .academic-menu-icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
        box-shadow: 0 4px 6px -1px rgba(76, 29, 149, 0.05);
    }
    .academic-menu-item:hover .academic-menu-icon-wrapper {
        transform: scale(1.06);
        box-shadow: 0 8px 12px -2px rgba(76, 29, 149, 0.12);
    }
    .academic-menu-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #4b5563;
        line-height: 1.3;
        max-width: 90px;
    }
</style>

@if(!empty($hariPertemuan) && $tanggalMulai)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ── Data dari controller ──
        const mapelJadwal     = @json($mapelJadwal ?? []);
        const sesiPerMapel    = @json($sesiPerMapel ?? []);
        const hariPerMapelRaw = @json($hariPerMapel ?? []);
        const tanggalPerMapel = @json($tanggalPerMapel ?? []);

        // Fallback ke data lama jika per-mapel kosong
        const legacyDays     = @json($hariPertemuan);
        const legacyStart    = "{{ $tanggalMulai }}";
        const legacyLimit    = {{ $jumlahPertemuan ?? 0 }};

        const jamMulai      = "{{ $jamMulai }}";
        const jamSelesai    = "{{ $jamSelesai }}";
        const gurus         = @json($gurus ?? []);
        const tipePaketStr  = "{{ str_contains(strtolower($siswa->tipe_paket), 'privat') ? 'Privat 1 on 1' : 'Kelompok' }}";

        const dayNames   = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const monthNames = ['Januari','Februari','Maret','April','Mei','Juni',
                            'Juli','Agustus','September','Oktober','November','Desember'];
        const dayMap     = {'Minggu':0,'Senin':1,'Selasa':2,'Rabu':3,'Kamis':4,'Jumat':5,'Sabtu':6};

        // Warna per mapel (purple, blue, green, orange, rose, teal)
        const mapelColors = [
            {bg:'#7c3aed', light:'#ede9fe', text:'#4c1d95'},
            {bg:'#2563eb', light:'#dbeafe', text:'#1e3a8a'},
            {bg:'#059669', light:'#d1fae5', text:'#065f46'},
            {bg:'#d97706', light:'#fef3c7', text:'#92400e'},
            {bg:'#e11d48', light:'#ffe4e6', text:'#881337'},
            {bg:'#0891b2', light:'#cffafe', text:'#164e63'},
        ];

        // Helper untuk mencocokkan tutor/guru berdasarkan mata pelajaran
        function getGuruForMapel(mapelName) {
            if (!gurus || gurus.length === 0) return 'Belum ditentukan oleh Admin';
            const normMapel = String(mapelName).toLowerCase().trim();
            let matched = gurus.find(g => String(g).toLowerCase().includes(normMapel));
            if (matched) {
                return matched.replace(/^(math|english|ipa|ips|fisika|kimia|biologi|matematika):\s*/i, '');
            }
            const cleaned = gurus.map(g => g.replace(/^(math|english|ipa|ips|fisika|kimia|biologi|matematika):\s*/i, ''));
            return cleaned.join(', ') || 'Belum ditentukan oleh Admin';
        }

        // ── Build scheduledDates dari per-mapel atau fallback ──
        const scheduledDates = []; // { dateStr, sessionIndex, dayName, dateObj, mapelName, mapelIdx, totalSesi }

        function buildSchedule(days, startStr, limitSesi, mapelName, mapelIdx) {
            const scheduledDayNums = days.map(d => dayMap[d] ?? -1).filter(n => n >= 0);
            const startDate = new Date(startStr);
            startDate.setHours(0,0,0,0);
            if (isNaN(startDate.getTime()) || scheduledDayNums.length === 0 || limitSesi === 0) return;

            let count = 0;
            let tempDate = new Date(startDate);
            for (let d = 0; d < 730 && count < limitSesi; d++) {
                if (scheduledDayNums.includes(tempDate.getDay())) {
                    const y = tempDate.getFullYear();
                    const m = String(tempDate.getMonth()+1).padStart(2,'0');
                    const dd = String(tempDate.getDate()).padStart(2,'0');
                    scheduledDates.push({
                        dateStr: `${y}-${m}-${dd}`,
                        sessionIndex: ++count,
                        dayName: dayNames[tempDate.getDay()],
                        dateObj: new Date(tempDate),
                        mapelName: mapelName,
                        mapelIdx: mapelIdx,
                        totalSesi: limitSesi
                    });
                }
                tempDate.setDate(tempDate.getDate() + 1);
            }
        }

        if (mapelJadwal.length > 0) {
            // Mode per-mapel baru
            mapelJadwal.forEach((mapel, idx) => {
                const hariRaw  = hariPerMapelRaw[idx] ?? {};
                const days     = Array.isArray(hariRaw) ? hariRaw : Object.values(hariRaw).filter(h => h);
                const startStr = tanggalPerMapel[idx] ?? legacyStart;
                const limit    = parseInt(sesiPerMapel[idx] ?? 0);
                buildSchedule(days, startStr, limit, mapel, idx);
            });
        } else {
            // Fallback: mode lama (satu jadwal flat)
            buildSchedule(legacyDays, legacyStart, legacyLimit, 'Bimbingan', 0);
        }

        // Sort semua sesi berdasarkan tanggal
        scheduledDates.sort((a,b) => a.dateObj - b.dateObj);

        // Set Sesi Berakhir
        if (scheduledDates.length > 0) {
            const last = scheduledDates[scheduledDates.length - 1];
            const endEl = document.getElementById('sessionEndDateVal');
            if (endEl) endEl.textContent = last.dateObj.toLocaleDateString('id-ID', {weekday:'long',day:'numeric',month:'long',year:'numeric'});

            // Notif sesi pertama
            const first = scheduledDates[0];
            const today = new Date(); today.setHours(0,0,0,0);
            const todayStr = `${today.getFullYear()}-${String(today.getMonth()+1).padStart(2,'0')}-${String(today.getDate()).padStart(2,'0')}`;
            if (first.dateStr === todayStr) {
                const banner = document.getElementById('sessionNotificationBanner');
                const bannerText = document.getElementById('sessionNotificationText');
                if (banner && bannerText) {
                    bannerText.innerHTML = `Hari ini adalah <strong>Sesi 1 (${first.mapelName})</strong> Bimbingan Belajar Anda! Sesi dimulai pukul <strong>${jamMulai} - ${jamSelesai}</strong>. Selamat belajar!`;
                    banner.classList.remove('d-none');
                }
            }
        }

        // ── Render Kalender ──
        const startDateObj = new Date(tanggalPerMapel[0] ?? legacyStart);
        let currentMonth = !isNaN(startDateObj) ? startDateObj.getMonth() : new Date().getMonth();
        let currentYear  = !isNaN(startDateObj) ? startDateObj.getFullYear() : new Date().getFullYear();

        const grid            = document.getElementById('calendarDaysGrid');
        const monthYearLabel  = document.getElementById('currentMonthYear');
        const agendaContainer = document.getElementById('agendaListContainer');

        function openModalForSessions(currentDate, sessionsToday) {
            const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const formattedDate = currentDate.toLocaleDateString('id-ID', opts);
            document.getElementById('modalDate').textContent = formattedDate;

            const now = new Date();
            const curMin = now.getHours()*60 + now.getMinutes();
            const endParts = jamSelesai.split(':');
            const endMin = parseInt(endParts[0])*60 + (parseInt(endParts[1])||0);
            const today = new Date(); today.setHours(0,0,0,0);
            const isPast = currentDate < today;
            const isDoneToday = (currentDate.getTime() === today.getTime()) && curMin > endMin;
            const isCompleted = isPast || isDoneToday;

            // Badges atas modal
            const badgeLabel = sessionsToday.map(s => `${s.mapelName}: Sesi ${s.sessionIndex}/${s.totalSesi}`).join(' | ');
            document.getElementById('modalSessionIndex').textContent = badgeLabel + (isCompleted ? ' (Selesai)' : ' (Belum Mulai)');

            // Build dynamic details box per mapel session
            const sessionsListEl = document.getElementById('modalSessionsList');
            if (sessionsListEl) {
                sessionsListEl.innerHTML = sessionsToday.map(s => {
                    const guruName = getGuruForMapel(s.mapelName);
                    const color = mapelColors[s.mapelIdx % mapelColors.length];
                    return `
                    <div class="p-3.5 rounded-2xl text-left border mb-3 text-xs shadow-xs" 
                         style="background-color: ${color.light}; border-color: ${color.bg}40;">
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom" style="border-color: ${color.bg}30;">
                            <span class="font-weight-bold text-sm" style="color: ${color.text};">
                                <i class="fas fa-book-open mr-1.5"></i> ${s.mapelName}
                            </span>
                            <span class="badge px-2.5 py-1 rounded-full text-xxs font-weight-bold" style="background-color: ${color.bg}; color: #fff;">
                                Sesi ${s.sessionIndex} dari ${s.totalSesi}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-1.5">
                            <span class="text-slate-500"><i class="fas fa-chalkboard-teacher mr-1 text-slate-400"></i>Tutor Pendamping:</span>
                            <span class="font-weight-bold text-slate-800">${guruName}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1.5">
                            <span class="text-slate-500"><i class="far fa-clock mr-1 text-slate-400"></i>Jam Sesi:</span>
                            <span class="font-weight-bold text-slate-800">${jamMulai} - ${jamSelesai} WIB</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1.5">
                            <span class="text-slate-500"><i class="fas fa-users mr-1 text-slate-400"></i>Metode:</span>
                            <span class="font-weight-bold text-slate-800">${tipePaketStr}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-slate-500"><i class="fas fa-map-marker-alt mr-1 text-slate-400"></i>Lokasi:</span>
                            <span class="font-weight-bold text-slate-800">Paradise of Math Center / Online</span>
                        </div>
                    </div>`;
                }).join('');
            }

            $('#sessionDetailModal').modal('show');
        }

        function renderCalendar(month, year) {
            grid.innerHTML = '';
            monthYearLabel.textContent = `${monthNames[month]} ${year}`;

            const firstDayIndex  = new Date(year, month, 1).getDay();
            const totalDays      = new Date(year, month+1, 0).getDate();
            const prevTotalDays  = new Date(year, month, 0).getDate();
            const today = new Date(); today.setHours(0,0,0,0);

            // Previous month trailing
            for (let x = firstDayIndex; x > 0; x--) {
                const cell = document.createElement('div');
                cell.className = 'calendar-day-cell other-month';
                cell.textContent = prevTotalDays - x + 1;
                grid.appendChild(cell);
            }

            const agendaThisMonth = [];

            // Current month days
            for (let i = 1; i <= totalDays; i++) {
                const currentDate = new Date(year, month, i);
                currentDate.setHours(0,0,0,0);
                const y  = currentDate.getFullYear();
                const m  = String(currentDate.getMonth()+1).padStart(2,'0');
                const d  = String(currentDate.getDate()).padStart(2,'0');
                const currentDateStr = `${y}-${m}-${d}`;

                const cell = document.createElement('div');
                cell.className = 'calendar-day-cell';

                const numSpan = document.createElement('span');
                numSpan.textContent = i;
                cell.appendChild(numSpan);

                const isToday = currentDate.getTime() === today.getTime();
                if (isToday) cell.classList.add('today');

                // Cari semua sesi di tanggal ini
                const sessionsToday = scheduledDates.filter(s => s.dateStr === currentDateStr);

                if (sessionsToday.length > 0) {
                    cell.classList.add('scheduled');
                    const now = new Date();
                    const curMin = now.getHours()*60 + now.getMinutes();
                    const endParts = jamSelesai.split(':');
                    const endMin = parseInt(endParts[0])*60 + (parseInt(endParts[1])||0);
                    const isPast = currentDate < today;
                    const isDoneToday = isToday && curMin > endMin;
                    const isCompleted = isPast || isDoneToday;
                    if (isCompleted) cell.classList.add('completed');

                    // Dot container (satu per mapel)
                    const dotRow = document.createElement('div');
                    dotRow.style.cssText = 'position:absolute;bottom:5px;display:flex;gap:3px;justify-content:center;';
                    const seenMapel = new Set();
                    sessionsToday.forEach(s => {
                        if (!seenMapel.has(s.mapelIdx)) {
                            seenMapel.add(s.mapelIdx);
                            const dot = document.createElement('span');
                            const color = mapelColors[s.mapelIdx % mapelColors.length];
                            dot.style.cssText = `width:5px;height:5px;border-radius:50%;background:${isCompleted?'#10b981':color.bg};display:inline-block;`;
                            dotRow.appendChild(dot);
                        }
                    });
                    cell.appendChild(dotRow);

                    // Tooltip
                    const labels = sessionsToday.map(s => `${s.mapelName} Sesi ${s.sessionIndex}/${s.totalSesi}`).join(', ');
                    cell.title = labels;

                    // Click modal popup
                    cell.addEventListener('click', function() {
                        openModalForSessions(currentDate, sessionsToday);
                    });

                    sessionsToday.forEach(s => {
                        agendaThisMonth.push({ date: currentDate, dayNum: i, dayName: dayNames[currentDate.getDay()], session: s });
                    });
                }

                grid.appendChild(cell);
            }

            // Next month padding
            const totalCells = firstDayIndex + totalDays;
            const remaining  = (7 - (totalCells % 7)) % 7;
            for (let y = 1; y <= remaining; y++) {
                const cell = document.createElement('div');
                cell.className = 'calendar-day-cell other-month';
                cell.textContent = y;
                grid.appendChild(cell);
            }

            // Render agenda
            renderAgenda(agendaThisMonth, today);
        }

        function renderAgenda(agendaItems, today) {
            if (!agendaContainer) return;
            if (agendaItems.length === 0) {
                agendaContainer.innerHTML = `<div class="text-center text-muted py-4 px-3 text-xs">Tidak ada sesi di bulan ini.</div>`;
                return;
            }
            const html = agendaItems.map(item => {
                const s = item.session;
                const isToday = item.date.getTime() === today.getTime();
                const isPast  = item.date < today;
                const color   = mapelColors[s.mapelIdx % mapelColors.length];
                const guruName = getGuruForMapel(s.mapelName);
                const statusBadge = isPast
                    ? `<span class="badge badge-success text-xs font-weight-bold px-2 py-1 rounded-full" style="background-color:#d1fae5;color:#065f46;font-size:10px;">Selesai</span>`
                    : (isToday
                        ? `<span class="badge text-xs font-weight-bold px-2 py-1 rounded-full" style="background-color:#fef3c7;color:#92400e;font-size:10px;">Hari Ini</span>`
                        : `<span class="badge text-xs font-weight-bold px-2 py-1 rounded-full" style="background-color:#f5f3ff;color:#6d28d9;font-size:10px;">Belum Mulai</span>`);
                return `
                <div class="agenda-item px-3 py-2.5 border-bottom border-light d-flex align-items-center gap-3 ${isToday ? 'today-agenda' : ''}"
                     style="border-left: 4px solid ${color.bg}; background:${isPast ? '#fafafa' : '#fff'};"
                     onclick="openModalForAgenda('${s.dateStr}', ${s.mapelIdx})">
                    <div class="text-center shrink-0" style="min-width:36px;">
                        <div class="font-weight-bold text-purple-950 text-sm">${item.dayNum}</div>
                        <div class="text-muted text-xxs" style="font-size:10px;">${item.dayName.slice(0,3)}</div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="font-weight-bold text-xs text-purple-950">${s.mapelName}</div>
                        <div class="text-muted text-xxs" style="font-size:10px;">Tutor: <strong>${guruName}</strong> • Sesi ${s.sessionIndex} dari ${s.totalSesi}</div>
                    </div>
                    <div>${statusBadge}</div>
                </div>`;
            }).join('');
            agendaContainer.innerHTML = html;

            // Make openModalForAgenda globally available for agenda item clicks
            window.openModalForAgenda = function(dateStr, mapelIdx) {
                const targetDate = new Date(dateStr + 'T00:00:00');
                const sessions = scheduledDates.filter(s => s.dateStr === dateStr);
                openModalForSessions(targetDate, sessions);
            };
        }

        renderCalendar(currentMonth, currentYear);

        document.getElementById('btnPrevMonth').addEventListener('click', () => {
            if (currentMonth === 0) { currentMonth = 11; currentYear--; }
            else currentMonth--;
            renderCalendar(currentMonth, currentYear);
        });
        document.getElementById('btnNextMonth').addEventListener('click', () => {
            if (currentMonth === 11) { currentMonth = 0; currentYear++; }
            else currentMonth++;
            renderCalendar(currentMonth, currentYear);
        });
    });
</script>
@endif
@endsection

