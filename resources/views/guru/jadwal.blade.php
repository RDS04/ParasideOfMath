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
    .space-y-3 > * + * {
        margin-top: 0.85rem;
    }
    .rounded-xl {
        border-radius: 12px !important;
    }
    .agenda-card-item {
        border-left: 4px solid #0f766e;
        transition: all 0.2s ease;
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
                    cell.classList.add('scheduled');
                    
                    // Add dot
                    const dot = document.createElement('span');
                    dot.className = 'schedule-dot';
                    cell.appendChild(dot);
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
                });

                grid.appendChild(cell);
            }
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
                        <a href="https://wa.me/${waFormatted}?text=Halo%20${encodeURIComponent(s.student_name)},%20hari%20ini%20sesi%20bimbel%20kita%20mulai%20jam%20${s.time}.%20Sampai%20jumpa%20nanti!" target="_blank" class="btn btn-xs btn-success rounded-lg py-1 px-2 font-weight-bold text-xs" style="background-color: #10b981; border: 0;">
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
                            <span>${s.subject}</span>
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
