@extends('layout.app')

@section('title', 'Jadwal Mengajar · Paradise of Math')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-teal-950">Jadwal Mengajar Saya</h1>
                <p class="text-sm text-muted mb-0">Lihat dan pantau seluruh jadwal sesi mengajar siswa Anda secara terpusat.</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right text-sm">
                    <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}" class="text-teal-600">Home</a></li>
                    <li class="breadcrumb-item active">Jadwal Mengajar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        @if(count($assignedStudents) === 0)
            <!-- Belum Ada Siswa State -->
            <div class="card border-0 shadow-sm overflow-hidden text-center py-5 px-4" style="border-radius: 20px;">
                <div class="card-body">
                    <div class="mb-4">
                        <i class="far fa-calendar-times fa-4x text-teal-300"></i>
                    </div>
                    <h4 class="font-weight-bold text-teal-950">Jadwal Mengajar Belum Tersedia</h4>
                    <p class="text-muted max-w-md mx-auto mb-4">
                        Saat ini belum ada siswa aktif yang didelegasikan oleh Admin kepada Anda. Silakan hubungi Admin jika ada penugasan baru.
                    </p>
                </div>
            </div>
        @else
            <!-- Rincian & Kalender Container -->
            <div class="row">
                <!-- Left Column: Kalender Interaktif (8 cols) -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title font-weight-bold text-teal-950 mb-0">Kalender Mengajar</h5>
                            <!-- Navigasi Bulan -->
                            <div class="d-flex align-items-center gap-2">
                                <button id="btnPrevMonth" class="btn btn-light rounded-circle border-0 shadow-sm" style="width: 38px; height: 38px; color: #0f766e;">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <h6 id="currentMonthYear" class="font-weight-bold text-teal-950 px-3 mb-0 text-center" style="min-width: 140px;">Agustus 2026</h6>
                                <button id="btnNextMonth" class="btn btn-light rounded-circle border-0 shadow-sm" style="width: 38px; height: 38px; color: #0f766e;">
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

                <!-- Right Column: Info Murid & Agenda Detail (4 cols) -->
                <div class="col-lg-4 mb-4">
                    <!-- Top Card: Gradient Card (Info Murid & Jadwal Les) -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; background: linear-gradient(135deg, #0f766e 0%, #115e59 100%); color: #fff;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="px-2.5 py-1 bg-amber-400 text-teal-950 text-xxs font-black uppercase tracking-wider rounded-full shadow-sm">
                                    Status Aktif
                                </span>
                                <span class="text-xs font-bold text-teal-100">
                                    <i class="fas fa-users mr-1"></i> {{ count($assignedStudents) }} Murid
                                </span>
                            </div>
                            <h5 class="font-weight-bold mt-3 mb-1">Murid Bimbingan Anda</h5>

                            <div class="border-top border-teal-800 my-3"></div>

                            <div class="space-y-2 text-xs" style="max-height: 220px; overflow-y: auto;">
                                @foreach($assignedStudents as $siswa)
                                    @php
                                        $bio = $siswa->biodata ?? [];
                                        $mapelJadwal = $bio['mapel_jadwal'] ?? [];
                                        $hariPerMapel = $bio['hari_per_mapel'] ?? [];
                                        $jamPerMapel = $bio['jam_per_mapel'] ?? [];
                                    @endphp
                                    <div class="mb-2 p-2.5 rounded-xl" style="background: rgba(255, 255, 255, 0.1);">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="font-weight-bold text-sm text-white">{{ $siswa->name }}</span>
                                            <span class="text-xxs text-teal-200 bg-teal-900/60 px-2 py-0.5 rounded-full">{{ $siswa->sekolah ?: 'Siswa' }}</span>
                                        </div>
                                        @if(!empty($mapelJadwal))
                                            @foreach($mapelJadwal as $idx => $mName)
                                                @php
                                                    $hList = $hariPerMapel[$idx] ?? [];
                                                    $hStr = is_array($hList) ? implode(' & ', array_filter($hList)) : '-';
                                                    $jMulai = $jamPerMapel[$idx]['jam_mulai'] ?? '15:30';
                                                    $jSelesai = $jamPerMapel[$idx]['jam_selesai'] ?? '17:00';
                                                @endphp
                                                <div style="font-size: 11px; color: #ccfbf1;" class="mt-1">
                                                    📘 <strong>{{ $mName }}</strong>: 📅 {{ $hStr }} ({{ $jMulai }} - {{ $jSelesai }})
                                                </div>
                                            @endforeach
                                        @else
                                            <div style="font-size: 11px; color: #ccfbf1;">
                                                📅 {{ implode(', ', $bio['hari_pertemuan'] ?? ['Belum diatur']) }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Agenda Card: Agenda Semua Sesi Mengajar -->
                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 id="agendaHeaderTitle" class="card-title font-weight-bold text-teal-950 mb-0">Agenda Mengajar (0 Sesi)</h6>
                        </div>
                        <div class="card-body p-0 max-h-[300px] overflow-y-auto" id="agendaListContainer" style="max-height: 300px; overflow-y: auto;">
                            <!-- Dynamic agenda sessions rendered via JS -->
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</section>

<!-- Click Event Detail Modal -->
<div class="modal fade" id="sessionDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 18px; overflow: hidden;">
            <div class="modal-header bg-teal-950 text-white border-0 py-3" style="background-color: #0f766e;">
                <h5 class="modal-title font-weight-bold text-md" id="modalTitle" style="color: #fff;">Detail Sesi Mengajar</h5>
                <button type="button" class="close text-white border-0 bg-transparent" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; outline: none; color: #fff;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <i class="fas fa-chalkboard-teacher fa-3x text-amber-500"></i>
                </div>
                <h5 class="font-weight-bold text-teal-950 mb-1" style="color: #0f766e;">Sesi Bimbingan Les</h5>
                <div class="mb-2" id="modalBadgeContainer">
                    <span id="modalSessionIndex" class="badge badge-warning text-teal-950 font-weight-bold px-3 py-1.5 rounded-full text-xs">Sesi Mengajar</span>
                </div>
                <p id="modalDate" class="text-sm font-semibold text-teal-700 mb-4" style="color: #0f766e;">Senin, 10 Agustus 2026</p>
                
                <div id="modalSessionsList">
                    <!-- Dynamic session details per student inserted here -->
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

    #calendarDaysGrid {
        align-items: start !important;
        align-content: start !important;
    }
    
    .calendar-day-cell {
        width: 100%;
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
        align-self: start !important;
        overflow: hidden;
        box-sizing: border-box;
    }
    .calendar-day-cell:hover {
        background-color: #f0fdfa;
        transform: translateY(-1px);
    }
    .calendar-day-cell.other-month {
        opacity: 0.3;
        cursor: default;
        pointer-events: none;
    }
    .calendar-day-cell.scheduled {
        background: linear-gradient(135deg, #14b8a6 0%, #0f766e 100%) !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(15, 118, 110, 0.22);
    }
    .calendar-day-cell.scheduled:hover {
        transform: scale(1.04);
        box-shadow: 0 6px 14px rgba(15, 118, 110, 0.35);
    }
    .calendar-day-cell.today {
        border-color: #f59e0b !important;
        background-color: #fffbeb;
        font-weight: 800;
    }
    .calendar-day-cell.scheduled.today {
        border-color: #fbbf24 !important;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3);
    }

    .calendar-day-cell.scheduled.completed {
        background: #f8fafc !important;
        color: #64748b !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: none !important;
    }
    
    .schedule-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background-color: #fff;
    }
    .calendar-day-cell.scheduled.completed .schedule-dot {
        background-color: #94a3b8 !important;
    }

    .agenda-item {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .agenda-item:hover {
        background-color: #f0fdfa !important;
        transform: translateX(2px);
    }
    .agenda-item.today-agenda {
        border-left-color: #f59e0b;
    }
</style>

@if(count($assignedStudents) > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scheduleConfigs = @json($scheduleConfigs ?? []);

        const dayNames   = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const monthNames = ['Januari','Februari','Maret','April','Mei','Juni',
                            'Juli','Agustus','September','Oktober','November','Desember'];

        const mapelColors = [
            {bg:'#0f766e', light:'#ccfbf1', text:'#0f766e'},
            {bg:'#2563eb', light:'#dbeafe', text:'#1e3a8a'},
            {bg:'#059669', light:'#d1fae5', text:'#065f46'},
            {bg:'#d97706', light:'#fef3c7', text:'#92400e'},
            {bg:'#e11d48', light:'#ffe4e6', text:'#881337'},
            {bg:'#0891b2', light:'#cffafe', text:'#164e63'},
        ];

        let today = new Date();
        let currentMonth = today.getMonth();
        let currentYear  = today.getFullYear();

        const grid            = document.getElementById('calendarDaysGrid');
        const monthYearLabel  = document.getElementById('currentMonthYear');
        const agendaContainer = document.getElementById('agendaListContainer');

        function getSessionsForMonth(viewMonth, viewYear) {
            const results = [];
            const totalDaysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();

            scheduleConfigs.forEach(cfg => {
                const scheduledDayNums = cfg.scheduledDayNums || [];
                if (scheduledDayNums.length === 0) return;

                const startDate = cfg.tglMulai ? new Date(cfg.tglMulai) : null;
                if (startDate) startDate.setHours(0,0,0,0);

                let totalSesiInMonth = 0;
                for (let d = 1; d <= totalDaysInMonth; d++) {
                    const currentDate = new Date(viewYear, viewMonth, d);
                    currentDate.setHours(0,0,0,0);
                    if (startDate && !isNaN(startDate.getTime()) && currentDate < startDate) continue;
                    if (scheduledDayNums.includes(currentDate.getDay())) {
                        totalSesiInMonth++;
                    }
                }
                if (totalSesiInMonth === 0) {
                    totalSesiInMonth = parseInt(cfg.limitSesi) || 4;
                }

                let sessionCount = 0;
                for (let d = 1; d <= totalDaysInMonth; d++) {
                    const currentDate = new Date(viewYear, viewMonth, d);
                    currentDate.setHours(0,0,0,0);
                    if (startDate && !isNaN(startDate.getTime()) && currentDate < startDate) continue;

                    if (scheduledDayNums.includes(currentDate.getDay())) {
                        sessionCount++;
                        const y = currentDate.getFullYear();
                        const m = String(currentDate.getMonth()+1).padStart(2,'0');
                        const dd = String(currentDate.getDate()).padStart(2,'0');
                        results.push({
                            dateStr: `${y}-${m}-${dd}`,
                            student_name: cfg.student_name,
                            subject: cfg.subject,
                            time: cfg.time,
                            whatsapp: cfg.whatsapp,
                            sekolah: cfg.sekolah,
                            session_index: sessionCount,
                            total_sessions: totalSesiInMonth,
                            dateObj: new Date(currentDate)
                        });
                    }
                }
            });

            results.sort((a,b) => a.dateObj - b.dateObj);
            return results;
        }

        function openModalForSessions(currentDate, sessionsToday) {
            const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const formattedDate = currentDate.toLocaleDateString('id-ID', opts);
            document.getElementById('modalDate').textContent = formattedDate;

            const todayObj = new Date(); todayObj.setHours(0,0,0,0);
            const isPast = currentDate < todayObj;
            const isToday = currentDate.getTime() === todayObj.getTime();

            const badgeLabel = `Total ${sessionsToday.length} Sesi Mengajar Hari Ini`;
            document.getElementById('modalSessionIndex').textContent = badgeLabel + (isPast ? ' (Selesai)' : (isToday ? ' (Hari Ini)' : ' (Akan Datang)'));

            const sessionsListEl = document.getElementById('modalSessionsList');
            if (sessionsListEl) {
                sessionsListEl.innerHTML = sessionsToday.map((s, idx) => {
                    const color = mapelColors[idx % mapelColors.length];
                    let waButton = '';
                    if (s.whatsapp) {
                        const waClean = s.whatsapp.replace(/[^0-9]/g, '');
                        const waFormatted = waClean.startsWith('0') ? '62' + waClean.substring(1) : waClean;
                        waButton = `
                            <a href="https://wa.me/${waFormatted}?text=Halo%20${encodeURIComponent(s.student_name)},%20hari%20ini%20sesi%20bimbel%20${encodeURIComponent(s.subject)}%20kita%20mulai%20jam%20${encodeURIComponent(s.time)}.%20Sampai%20jumpa%20nanti!" target="_blank" class="btn btn-xs btn-success font-weight-bold rounded-lg px-2.5 py-1 text-xs shadow-xs" style="background-color: #10b981; border: 0;">
                                <i class="fab fa-whatsapp mr-1"></i> Hubungi Siswa (WA)
                            </a>
                        `;
                    }
                    return `
                    <div class="p-3.5 rounded-2xl text-left border mb-3 text-xs shadow-xs" 
                         style="background-color: ${color.light}; border-color: ${color.bg}40;">
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom" style="border-color: ${color.bg}30;">
                            <span class="font-weight-bold text-sm text-teal-950">
                                <i class="fas fa-user-graduate mr-1.5 text-teal-700"></i> ${s.student_name}
                            </span>
                            <span class="badge px-2.5 py-1 rounded-full text-xxs font-weight-bold" style="background-color: ${color.bg}; color: #fff;">
                                Sesi ${s.session_index} dari ${s.total_sessions}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-1.5">
                            <span class="text-slate-500"><i class="fas fa-school mr-1 text-slate-400"></i>Sekolah:</span>
                            <span class="font-weight-bold text-slate-800">${s.sekolah || 'Paradise of Math Student'}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1.5">
                            <span class="text-slate-500"><i class="fas fa-book-open mr-1 text-slate-400"></i>Mata Pelajaran:</span>
                            <span class="font-weight-bold text-purple-900">${s.subject}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-slate-500"><i class="far fa-clock mr-1 text-slate-400"></i>Jam Mengajar:</span>
                            <span class="font-weight-bold text-amber-700">${s.time} WIB</span>
                        </div>
                        <div class="text-right border-top pt-2">
                            ${waButton}
                        </div>
                    </div>`;
                }).join('');
            }

            $('#sessionDetailModal').modal('show');
        }

        function renderCalendar(month, year) {
            grid.innerHTML = '';
            monthYearLabel.textContent = `${monthNames[month]} ${year}`;

            const sessionsData = getSessionsForMonth(month, year);

            const firstDayIndex  = new Date(year, month, 1).getDay();
            const totalDays      = new Date(year, month+1, 0).getDate();
            const prevTotalDays  = new Date(year, month, 0).getDate();
            const todayObj = new Date(); todayObj.setHours(0,0,0,0);

            const agendaTitleEl = document.getElementById('agendaHeaderTitle');
            if (agendaTitleEl) {
                agendaTitleEl.textContent = `Agenda Mengajar (${sessionsData.length} Sesi)`;
            }

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

                const isToday = currentDate.getTime() === todayObj.getTime();
                if (isToday) cell.classList.add('today');

                const sessionsToday = sessionsData.filter(s => s.dateStr === currentDateStr);

                if (sessionsToday.length > 0) {
                    cell.classList.add('scheduled');
                    const isPast = currentDate < todayObj;
                    if (isPast) cell.classList.add('completed');

                    const dotRow = document.createElement('div');
                    dotRow.style.cssText = 'position:absolute;bottom:4px;left:0;right:0;display:flex;gap:3px;justify-content:center;align-items:center;pointer-events:none;';
                    sessionsToday.forEach((s, idx) => {
                        const dot = document.createElement('span');
                        const color = mapelColors[idx % mapelColors.length];
                        dot.style.cssText = `width:4px;height:4px;border-radius:50%;background:${isPast ? '#94a3b8' : '#ffffff'};display:inline-block;`;
                        dotRow.appendChild(dot);
                    });
                    cell.appendChild(dotRow);

                    const labels = sessionsToday.map(s => `${s.student_name} (${s.subject})`).join(', ');
                    cell.title = labels;

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

            renderAgenda(agendaThisMonth, todayObj);
        }

        function renderAgenda(agendaItems, todayObj) {
            if (!agendaContainer) return;
            if (agendaItems.length === 0) {
                agendaContainer.innerHTML = `<div class="text-center text-muted py-4 px-3 text-xs">Tidak ada sesi mengajar di bulan ini.</div>`;
                return;
            }
            const html = agendaItems.map((item, idx) => {
                const s = item.session;
                const isToday = item.date.getTime() === todayObj.getTime();
                const isPast  = item.date < todayObj;
                const color   = mapelColors[idx % mapelColors.length];
                const statusBadge = isPast
                    ? `<span class="badge badge-success text-xs font-weight-bold px-2 py-1 rounded-full" style="background-color:#d1fae5;color:#065f46;font-size:10px;">Selesai</span>`
                    : (isToday
                        ? `<span class="badge text-xs font-weight-bold px-2 py-1 rounded-full" style="background-color:#fef3c7;color:#92400e;font-size:10px;">Hari Ini</span>`
                        : `<span class="badge text-xs font-weight-bold px-2 py-1 rounded-full" style="background-color:#f0fdfa;color:#0f766e;font-size:10px;">Akan Datang</span>`);
                
                let waBtn = '';
                if (s.whatsapp) {
                    const waClean = s.whatsapp.replace(/[^0-9]/g, '');
                    const waFormatted = waClean.startsWith('0') ? '62' + waClean.substring(1) : waClean;
                    waBtn = `<a href="https://wa.me/${waFormatted}?text=Halo%20${encodeURIComponent(s.student_name)},%20hari%20ini%20sesi%20bimbel%20${encodeURIComponent(s.subject)}%20kita%20mulai%20jam%20${encodeURIComponent(s.time)}.%20Sampai%20jumpa%20nanti!" target="_blank" onclick="event.stopPropagation();" class="text-emerald-600 hover:text-emerald-800 ml-2" title="Chat WhatsApp Siswa"><i class="fab fa-whatsapp fa-lg"></i></a>`;
                }

                return `
                <div class="agenda-item px-3 py-2.5 border-bottom border-light d-flex align-items-center gap-3 ${isToday ? 'today-agenda' : ''}"
                     style="border-left: 4px solid ${color.bg}; background:${isPast ? '#fafafa' : '#fff'};"
                     onclick="openModalForAgenda('${s.dateStr}')">
                    <div class="text-center shrink-0" style="min-width:36px;">
                        <div class="font-weight-bold text-teal-950 text-sm">${item.dayNum}</div>
                        <div class="text-muted text-xxs" style="font-size:10px;">${item.dayName.slice(0,3)}</div>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="font-weight-bold text-xs text-teal-950 text-truncate">${s.student_name} ${waBtn}</div>
                        <div class="text-muted text-xxs" style="font-size:10px;">${s.subject} • Jam ${s.time}</div>
                    </div>
                    <div>${statusBadge}</div>
                </div>`;
            }).join('');
            agendaContainer.innerHTML = html;

            window.openModalForAgenda = function(dateStr) {
                const targetDate = new Date(dateStr + 'T00:00:00');
                const sessions = sessionsData.filter(s => s.dateStr === dateStr);
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
