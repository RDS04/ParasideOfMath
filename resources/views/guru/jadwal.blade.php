@extends('layout.app')

@section('title', 'Jadwal Mengajar · Paradise of Math')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-teal-950">Jadwal Mengajar Saya</h1>
                <p class="text-sm text-muted mb-0">Lihat dan kelola seluruh jadwal sesi mengajar siswa Anda secara terpusat.</p>
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
                            <h6 id="currentMonthYear" class="font-weight-bold text-teal-950 px-3 mb-0 text-center" style="min-width: 140px;">-</h6>
                            <button id="btnNextMonth" class="btn btn-light rounded-circle border-0 shadow-sm" style="width: 38px; height: 38px; color: #0f766e;">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <!-- Hari dalam Seminggu -->
                        <div class="grid grid-cols-7 text-center font-weight-bold text-xs uppercase text-muted mb-2 py-2 border-bottom border-light" style="display: grid; grid-template-columns: repeat(7, 1fr);">
                            <div class="text-danger">Min</div>
                            <div>Sen</div>
                            <div>Sel</div>
                            <div>Rab</div>
                            <div>Kam</div>
                            <div>Jum</div>
                            <div>Sab</div>
                        </div>
                        <!-- Grid Hari (JavaScript generated) -->
                        <div id="calendarDaysGrid" class="grid grid-cols-7 gap-1 text-center font-weight-bold text-sm" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px;">
                            <!-- Day cells inserted here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Agenda Detail Bimbingan (4 cols) -->
            <div class="col-lg-4 mb-4">
                <!-- Info Statistik / Info Ringkas -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; background: linear-gradient(135deg, #0f766e 0%, #115e59 100%); color: white;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="font-weight-bold uppercase tracking-wider text-xs text-teal-100 mb-0">Total Siswa Bimbingan</h6>
                            <i class="fas fa-users text-teal-200"></i>
                        </div>
                        <h3 class="font-weight-bold mb-1">{{ count($assignedStudents) }}</h3>
                        <p class="text-xs text-teal-100 mb-0">Siswa aktif yang didelegasikan oleh Admin kepada Anda.</p>
                    </div>
                </div>

                <!-- Agenda Card -->
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="card-title font-weight-bold text-teal-950 mb-0" id="agendaHeaderDate">Jadwal Mengajar</h5>
                    </div>
                    <div class="card-body pt-0" style="max-height: 480px; overflow-y: auto;">
                        <div id="agendaListContainer" class="space-y-3">
                            <!-- Agenda items loaded dynamically via JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Modal Detail Jadwal Mengajar Guru -->
<div class="modal fade" id="guruSessionDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header text-white border-0 py-3" style="background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);">
                <div class="d-flex align-items-center">
                    <div class="avatar-icon bg-white-20 text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px; background: rgba(255,255,255,0.15);">
                        <i class="fas fa-calendar-check fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold text-md text-white mb-0" id="guruModalDateTitle">Detail Sesi Mengajar</h5>
                        <p class="text-xxs text-teal-100 mb-0" id="guruModalSubtitle">Rincian jam mengajar &amp; murid bimbingan</p>
                    </div>
                </div>
                <button type="button" class="close text-white border-0 bg-transparent" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; outline: none; color: #fff;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 bg-slate-50/50">
                <div id="guruModalContent">
                    <!-- Dynamic session list rendered here -->
                </div>
            </div>
            <div class="modal-footer border-0 bg-light p-3 d-flex justify-content-between align-items-center">
                <span class="text-xs text-muted" id="guruModalTotalInfo">Total: 0 Sesi</span>
                <button type="button" class="btn btn-sm btn-secondary rounded-lg font-weight-bold px-4" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- CSS Styling & Grid Layout Helper -->
<style>
    .calendar-day-cell {
        aspect-ratio: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        color: #334155;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        border: 1px solid #f1f5f9;
        font-weight: 700;
        user-select: none;
    }
    .calendar-day-cell:hover:not(.other-month) {
        background-color: #f0fdfa;
        color: #0f766e;
    }
    .calendar-day-cell.other-month {
        color: #cbd5e1;
        border-color: transparent;
        cursor: default;
        font-weight: 500;
    }
    .calendar-day-cell.today {
        border: 2px solid #0f766e !important;
        color: #0f766e;
        background-color: #f0fdfa;
    }
    .calendar-day-cell.scheduled {
        background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%) !important;
        color: #ffffff !important;
        border-color: transparent;
        box-shadow: 0 4px 10px rgba(15, 118, 110, 0.2);
    }
    .calendar-day-cell.session-completed {
        background: #f8fafc !important;
        color: #64748b !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: none !important;
    }
    .calendar-day-cell.active-selected {
        box-shadow: 0 0 0 3px #fdba74;
    }
    .schedule-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background-color: #ffffff;
        position: absolute;
        bottom: 6px;
    }
    .schedule-dot-completed {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background-color: #94a3b8;
        position: absolute;
        bottom: 6px;
    }
    .space-y-3 > * + * {
        margin-top: 0.85rem;
    }
    .rounded-xl {
        border-radius: 12px !important;
    }
    .agenda-card-item {
        border-left: 4px solid #0f766e;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .agenda-card-item:hover {
        background-color: #f0fdfa !important;
        transform: translateX(3px);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sessions = @json($sessions);
        
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        let today = new Date();
        let currentMonth = today.getMonth();
        let currentYear = today.getFullYear();
        let selectedDateStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

        const grid = document.getElementById('calendarDaysGrid');
        const monthYearLabel = document.getElementById('currentMonthYear');
        const agendaHeaderDate = document.getElementById('agendaHeaderDate');
        const agendaContainer = document.getElementById('agendaListContainer');

        function renderCalendar(month, year) {
            grid.innerHTML = '';
            monthYearLabel.textContent = `${monthNames[month]} ${year}`;

            // First day of the month
            const firstDayIndex = new Date(year, month, 1).getDay();
            // Total days in the month
            const totalDays = new Date(year, month + 1, 0).getDate();
            // Total days in the previous month
            const prevTotalDays = new Date(year, month, 0).getDate();

            const todayObj = new Date();
            todayObj.setHours(0,0,0,0);

            // 1. Previous Month's trailing days
            for (let x = firstDayIndex; x > 0; x--) {
                const cell = document.createElement('div');
                cell.className = 'calendar-day-cell other-month';
                cell.textContent = prevTotalDays - x + 1;
                grid.appendChild(cell);
            }

            // 2. Current Month's days
            for (let i = 1; i <= totalDays; i++) {
                const cellDate = new Date(year, month, i);
                cellDate.setHours(0,0,0,0);
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;

                const cell = document.createElement('div');
                cell.className = 'calendar-day-cell';
                cell.textContent = i;
                cell.dataset.date = dateStr;

                // Mark if it is today
                if (cellDate.getTime() === todayObj.getTime()) {
                    cell.classList.add('today');
                }

                // Check if there are scheduled sessions on this date
                const dateSessions = sessions.filter(s => s.dateStr === dateStr);
                if (dateSessions.length > 0) {
                    const now = new Date();
                    const curMin = now.getHours() * 60 + now.getMinutes();

                    let latestEndMin = 17 * 60; // 17:00
                    dateSessions.forEach(s => {
                        if (s.time) {
                            const parts = s.time.split('-');
                            if (parts.length === 2) {
                                const endStr = parts[1].trim();
                                const timeParts = endStr.split(':');
                                if (timeParts.length >= 2) {
                                    const h = parseInt(timeParts[0]);
                                    const m = parseInt(timeParts[1]);
                                    if (!isNaN(h) && !isNaN(m)) {
                                        const mins = h * 60 + m;
                                        if (mins > latestEndMin) latestEndMin = mins;
                                    }
                                }
                            }
                        }
                    });

                    const isPastDate = cellDate.getTime() < todayObj.getTime();
                    const isTodayEnded = (cellDate.getTime() === todayObj.getTime()) && (curMin > latestEndMin);
                    const isSessionEnded = isPastDate || isTodayEnded;

                    if (!isSessionEnded) {
                        // Jadwal belum habis: Beri warna background highlight teal
                        cell.classList.add('scheduled');
                        const dot = document.createElement('span');
                        dot.className = 'schedule-dot';
                        cell.appendChild(dot);
                    } else {
                        // Jadwal sudah habis: HILANGKAN WARNA background highlight!
                        cell.classList.add('session-completed');
                        const dot = document.createElement('span');
                        dot.className = 'schedule-dot-completed';
                        cell.appendChild(dot);
                    }
                }

                // Restore active-selected class
                if (dateStr === selectedDateStr) {
                    cell.classList.add('active-selected');
                }

                cell.addEventListener('click', function() {
                    document.querySelectorAll('.calendar-day-cell').forEach(c => c.classList.remove('active-selected'));
                    cell.classList.add('active-selected');
                    selectedDateStr = dateStr;
                    showAgenda(dateStr, cellDate);
                    openGuruModal(dateStr, cellDate);
                });

                grid.appendChild(cell);
            }
        }

        function openGuruModal(dateStr, dateObj) {
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            const formattedDate = dateObj.toLocaleDateString('id-ID', options);
            
            const titleEl = document.getElementById('guruModalDateTitle');
            if (titleEl) titleEl.textContent = `Jadwal Mengajar: ${formattedDate}`;
            
            const daySessions = sessions.filter(s => s.dateStr === dateStr);
            const contentEl = document.getElementById('guruModalContent');
            const totalInfoEl = document.getElementById('guruModalTotalInfo');

            if (totalInfoEl) {
                totalInfoEl.textContent = `Total: ${daySessions.length} Sesi Mengajar Hari Ini`;
            }

            if (!contentEl) return;

            if (daySessions.length === 0) {
                contentEl.innerHTML = `
                    <div class="text-center py-5">
                        <div class="mx-auto mb-3 text-slate-300">
                            <i class="far fa-calendar-check fa-4x text-teal-200"></i>
                        </div>
                        <h6 class="font-weight-bold text-slate-700 mb-1">Tidak Ada Jadwal Mengajar</h6>
                        <p class="text-xs text-muted mb-0">Tidak ada sesi bimbingan belajar yang dijadwalkan pada tanggal ${formattedDate}. Selamat beristirahat!</p>
                    </div>
                `;
            } else {
                contentEl.innerHTML = `
                    <div class="mb-3 px-1">
                        <span class="text-xs text-muted">Berikut adalah rincian jam mengajar &amp; murid bimbingan Anda pada <strong>${formattedDate}</strong>:</span>
                    </div>
                    <div class="row">
                        ${daySessions.map(s => {
                            let waButton = '';
                            if (s.whatsapp) {
                                const waClean = s.whatsapp.replace(/[^0-9]/g, '');
                                const waFormatted = waClean.startsWith('0') ? '62' + waClean.substring(1) : waClean;
                                waButton = `
                                    <a href="https://wa.me/${waFormatted}?text=Halo%20${encodeURIComponent(s.student_name)},%20hari%20ini%20sesi%20bimbel%20${encodeURIComponent(s.subject)}%20kita%20mulai%20jam%20${encodeURIComponent(s.time)}.%20Sampai%20jumpa%20nanti!" target="_blank" class="btn btn-sm btn-success rounded-lg font-weight-bold px-3 text-xs shadow-xs" style="background-color: #10b981; border: 0;">
                                        <i class="fab fa-whatsapp mr-1.5"></i> Hubungi Siswa (WA)
                                    </a>
                                `;
                            }
                            return `
                                <div class="col-12 mb-3">
                                    <div class="card border-0 rounded-2xl p-3.5 shadow-sm bg-white" style="border-left: 5px solid #0f766e !important; border-radius: 16px;">
                                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3 pb-2 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-teal-100 text-teal-800 font-bold d-flex justify-content-center align-items-center rounded-circle mr-3" style="width: 44px; height: 44px; font-size: 18px; min-width: 44px;">
                                                    ${s.student_name.charAt(0).toUpperCase()}
                                                </div>
                                                <div>
                                                    <h6 class="font-weight-bold text-teal-950 mb-0.5 text-base" style="color: #0f766e;">${s.student_name}</h6>
                                                    <span class="text-xs text-slate-500"><i class="fas fa-school mr-1 text-slate-400"></i>${s.sekolah || 'Siswa Paradise of Math'}</span>
                                                </div>
                                            </div>
                                            <span class="badge bg-teal-50 text-teal-700 font-weight-bold px-3 py-1.5 rounded-full text-xs border border-teal-200">
                                                Sesi ${s.session_index} dari ${s.total_sessions}
                                            </span>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-sm-7 mb-2 mb-sm-0">
                                                <div class="d-flex align-items-center text-xs font-weight-semibold text-slate-700 mb-1.5">
                                                    <i class="fas fa-book-open text-teal-600 mr-2" style="width: 16px;"></i>
                                                    <span>Mata Pelajaran: <strong class="text-purple-900">${s.subject}</strong></span>
                                                </div>
                                                <div class="d-flex align-items-center text-xs font-weight-semibold text-slate-700">
                                                    <i class="far fa-clock text-amber-500 mr-2" style="width: 16px;"></i>
                                                    <span>Jam Mengajar: <strong class="text-amber-700">${s.time} WIB</strong></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-5 text-sm-right mt-2 mt-sm-0">
                                                ${waButton}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                `;
            }

            $('#guruSessionDetailModal').modal('show');
        }

        function showAgenda(dateStr, dateObj) {
            // Update Header Date
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            agendaHeaderDate.textContent = dateObj.toLocaleDateString('id-ID', options);

            // Filter sessions
            const daySessions = sessions.filter(s => s.dateStr === dateStr);

            agendaContainer.innerHTML = '';

            if (daySessions.length === 0) {
                agendaContainer.innerHTML = `
                    <div class="text-center py-5 text-muted">
                        <i class="far fa-calendar-minus fa-2x text-slate-300 mb-2"></i>
                        <p class="text-xs mb-0">Tidak ada jadwal mengajar pada tanggal ini.</p>
                    </div>
                `;
                return;
            }

            daySessions.forEach(s => {
                const item = document.createElement('div');
                item.className = 'card border-0 rounded-xl p-3 bg-light agenda-card-item shadow-xs mb-3';
                
                let waButton = '';
                if (s.whatsapp) {
                    const waClean = s.whatsapp.replace(/[^0-9]/g, '');
                    const waFormatted = waClean.startsWith('0') ? '62' + waClean.substring(1) : waClean;
                    waButton = `
                        <a href="https://wa.me/${waFormatted}?text=Halo%20${encodeURIComponent(s.student_name)},%20hari%20ini%20sesi%20bimbel%20${encodeURIComponent(s.subject)}%20kita%20mulai%20jam%20${encodeURIComponent(s.time)}.%20Sampai%20jumpa%20nanti!" target="_blank" class="btn btn-xs btn-success rounded-lg py-1 px-2 font-weight-bold text-xs" style="background-color: #10b981; border: 0;">
                            <i class="fab fa-whatsapp mr-1"></i> Chat WA
                        </a>
                    `;
                }

                item.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="font-weight-bold text-teal-950 mb-0">${s.student_name}</h6>
                            <span class="text-xxs text-muted">${s.sekolah || 'Sekolah'}</span>
                        </div>
                        <span class="badge text-xxs font-weight-bold px-2 py-1 rounded bg-teal-100 text-teal-800" style="font-size: 10px;">
                            Sesi ${s.session_index} / ${s.total_sessions}
                        </span>
                    </div>
                    <div class="space-y-1 mt-2 pt-2 border-top border-white">
                        <div class="d-flex align-items-center text-xs text-slate-600 mb-1">
                            <i class="fas fa-book-open mr-2 text-teal-600" style="width: 14px;"></i>
                            <span class="font-weight-bold text-teal-900">${s.subject}</span>
                        </div>
                        <div class="d-flex align-items-center text-xs text-slate-600 mb-2">
                            <i class="far fa-clock mr-2 text-teal-600" style="width: 14px;"></i>
                            <span>${s.time}</span>
                        </div>
                        <div class="text-right">
                            ${waButton}
                        </div>
                    </div>
                `;

                item.addEventListener('click', function() {
                    openGuruModal(dateStr, dateObj);
                });

                agendaContainer.appendChild(item);
            });
        }

        // Navigation actions
        document.getElementById('btnPrevMonth').addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar(currentMonth, currentYear);
        });

        document.getElementById('btnNextMonth').addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar(currentMonth, currentYear);
        });

        // Initialize calendar and show today's agenda
        renderCalendar(currentMonth, currentYear);
        showAgenda(selectedDateStr, today);
    });
</script>
@endsection
