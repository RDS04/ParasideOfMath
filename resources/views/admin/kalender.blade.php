@extends('layout.app')

@section('title', 'Kalender Master Jadwal Siswa · Paradise of Math')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-purple-950 text-2xl tracking-tight">Kalender Master Jadwal Siswa</h1>
                <p class="text-xs text-muted mb-0">Pantau seluruh jadwal bimbingan belajar, mata pelajaran, dan penugasan tutor secara real-time.</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right text-xs bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600 font-semibold"><i class="fas fa-home mr-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active text-slate-500">Kalender Master</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
        <!-- Filter & Metrics Section -->
        <div class="card border-0 shadow-sm rounded-2xl mb-4" style="border-radius: 20px;">
            <div class="card-body p-4">
                <div class="row align-items-center gap-3">
                    <!-- Filters -->
                    <div class="col-lg-3 col-md-6 mb-2 mb-lg-0">
                        <label class="text-xs font-weight-bold text-purple-950 mb-1 d-block"><i class="fas fa-filter text-purple-600 mr-1"></i> Filter Mapel:</label>
                        <select id="filterMapel" class="form-control form-control-sm text-xs font-weight-semibold rounded-xl" style="height: 38px;">
                            <option value="">Semua Mata Pelajaran</option>
                            <option value="Fisika">Fisika</option>
                            <option value="Kimia">Kimia</option>
                            <option value="Biologi">Biologi</option>
                            <option value="Matematika">Matematika</option>
                            <option value="IPA">IPA</option>
                            <option value="IPS">IPS</option>
                            <option value="English">English</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-2 mb-lg-0">
                        <label class="text-xs font-weight-bold text-purple-950 mb-1 d-block"><i class="fas fa-chalkboard-teacher text-purple-600 mr-1"></i> Filter Tutor / Guru:</label>
                        <select id="filterTutor" class="form-control form-control-sm text-xs font-weight-semibold rounded-xl" style="height: 38px;">
                            <option value="">Semua Tutor Pendamping</option>
                            @foreach($gurusList as $g)
                                <option value="{{ $g->user->name ?? '' }}">{{ $g->user->name ?? '' }} ({{ $g->spesialisasi ?? 'Matematika' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-2 mb-lg-0">
                        <label class="text-xs font-weight-bold text-purple-950 mb-1 d-block"><i class="fas fa-search text-purple-600 mr-1"></i> Cari Nama Siswa:</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="searchSiswaName" class="form-control text-xs font-weight-semibold rounded-left-xl" placeholder="Cari nama siswa atau sekolah..." style="height: 38px; border-top-left-radius: 12px; border-bottom-left-radius: 12px;">
                            <div class="input-group-append">
                                <span class="input-group-text bg-purple-600 text-white border-0 px-3" style="border-top-right-radius: 12px; border-bottom-right-radius: 12px;"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 text-lg-right">
                        <button id="resetFilters" class="btn btn-sm btn-outline-secondary font-weight-bold text-xs rounded-xl w-full" style="height: 38px; border-radius: 12px;">
                            <i class="fas fa-undo mr-1"></i> Reset Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Kalender Interaktif (8 cols) -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title font-weight-bold text-purple-950 mb-0">Kalender Master</h5>
                        <!-- Navigasi Bulan -->
                        <div class="d-flex align-items-center gap-2">
                            <button id="btnPrevMonth" class="btn btn-light rounded-circle border-0 shadow-sm" style="width: 38px; height: 38px; color: #6b21a8;">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <h6 id="currentMonthYear" class="font-weight-bold text-purple-950 px-3 mb-0 text-center" style="min-width: 150px;">-</h6>
                            <button id="btnNextMonth" class="btn btn-light rounded-circle border-0 shadow-sm" style="width: 38px; height: 38px; color: #6b21a8;">
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
                <!-- Info Ringkas Stat Admin -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%); color: white;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="font-weight-bold uppercase tracking-wider text-xs text-purple-200 mb-0">Overview Akademik</h6>
                            <i class="fas fa-calendar-alt text-purple-300"></i>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="font-weight-bold mb-0 text-white">{{ count($allStudents) }}</h3>
                                <p class="text-xxs text-purple-200 mb-0">Total Siswa Aktif</p>
                            </div>
                            <div class="border-left pl-3 border-purple-800">
                                <h3 class="font-weight-bold mb-0 text-amber-300">{{ count($gurusList) }}</h3>
                                <p class="text-xxs text-purple-200 mb-0">Tutor Terdaftar</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Agenda Card -->
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <h5 class="card-title font-weight-bold text-purple-950 mb-0" id="agendaHeaderDate">Jadwal Sesi</h5>
                        <span class="badge bg-purple-100 text-purple-800 text-[10px] font-bold px-2 py-1 rounded-full" id="agendaCountBadge">0 Sesi</span>
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

<!-- Modal Detail Sesi Mengajar Admin -->
<div class="modal fade" id="adminSessionDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header text-white border-0 py-3" style="background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%);">
                <div class="d-flex align-items-center">
                    <div class="avatar-icon bg-white-20 text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px; background: rgba(255,255,255,0.15);">
                        <i class="fas fa-calendar-day fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold text-md text-white mb-0" id="adminModalDateTitle">Detail Sesi Master</h5>
                        <p class="text-xxs text-purple-200 mb-0">Rincian seluruh siswa, tutor &amp; mata pelajaran</p>
                    </div>
                </div>
                <button type="button" class="close text-white border-0 bg-transparent" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; outline: none; color: #fff;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 bg-slate-50/50">
                <div id="adminModalContent">
                    <!-- Dynamic session list rendered here -->
                </div>
            </div>
            <div class="modal-footer border-0 bg-light p-3 d-flex justify-content-between align-items-center">
                <span class="text-xs text-muted" id="adminModalTotalInfo">Total: 0 Sesi</span>
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
        background-color: #faf5ff;
        color: #7c3aed;
    }
    .calendar-day-cell.other-month {
        color: #cbd5e1;
        border-color: transparent;
        cursor: default;
        font-weight: 500;
    }
    .calendar-day-cell.today {
        border: 2px solid #7c3aed !important;
        color: #7c3aed;
        background-color: #faf5ff;
    }
    .calendar-day-cell.scheduled {
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%) !important;
        color: #ffffff !important;
        border-color: transparent;
        box-shadow: 0 4px 10px rgba(124, 58, 237, 0.25);
    }
    .calendar-day-cell.session-completed {
        background: #f8fafc !important;
        color: #64748b !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: none !important;
    }
    .calendar-day-cell.active-selected {
        box-shadow: 0 0 0 3px #fbbf24;
    }
    .schedule-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #fbbf24;
        position: absolute;
        bottom: 5px;
    }
    .schedule-badge-count {
        position: absolute;
        top: 2px;
        right: 4px;
        font-size: 9px;
        font-weight: 800;
        background-color: #fbbf24;
        color: #40206b;
        padding: 0px 4px;
        border-radius: 10px;
    }
    .schedule-dot-completed {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background-color: #94a3b8;
        position: absolute;
        bottom: 5px;
    }
    .space-y-3 > * + * {
        margin-top: 0.85rem;
    }
    .rounded-xl {
        border-radius: 12px !important;
    }
    .agenda-card-item {
        border-left: 4px solid #7c3aed;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .agenda-card-item:hover {
        background-color: #faf5ff !important;
        transform: translateX(3px);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rawSessions = @json($allSessions);
        
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        const mapelColors = {
            'fisika': { bg: '#3b82f6', light: '#eff6ff', text: '#1e40af' },
            'kimia': { bg: '#ec4899', light: '#fdf2f8', text: '#be185d' },
            'biologi': { bg: '#10b981', light: '#ecfdf5', text: '#047857' },
            'matematika': { bg: '#7c3aed', light: '#f3e8ff', text: '#6b21a8' },
            'ipa': { bg: '#06b6d4', light: '#ecfeff', text: '#0e7490' },
            'ips': { bg: '#f59e0b', light: '#fffbeb', text: '#b45309' },
            'english': { bg: '#6366f1', light: '#eef2ff', text: '#4338ca' }
        };
        
        let today = new Date();
        let currentMonth = today.getMonth();
        let currentYear = today.getFullYear();
        let selectedDateStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

        const filterMapelEl = document.getElementById('filterMapel');
        const filterTutorEl = document.getElementById('filterTutor');
        const searchSiswaEl = document.getElementById('searchSiswaName');
        const resetBtnEl    = document.getElementById('resetFilters');

        const grid = document.getElementById('calendarDaysGrid');
        const monthYearLabel = document.getElementById('currentMonthYear');
        const agendaHeaderDate = document.getElementById('agendaHeaderDate');
        const agendaContainer = document.getElementById('agendaListContainer');

        function getFilteredSessions() {
            const mapelVal = filterMapelEl.value.toLowerCase().trim();
            const tutorVal = filterTutorEl.value.toLowerCase().trim();
            const searchVal = searchSiswaEl.value.toLowerCase().trim();

            return rawSessions.filter(s => {
                const sMapel = (s.subject || '').toLowerCase();
                const sTutor = (s.tutor || '').toLowerCase();
                const sName  = (s.student_name || '').toLowerCase();
                const sSchool= (s.sekolah || '').toLowerCase();

                let matchMapel = !mapelVal || sMapel.includes(mapelVal);
                let matchTutor = !tutorVal || sTutor.includes(tutorVal);
                let matchSearch = !searchVal || (sName.includes(searchVal) || sSchool.includes(searchVal));

                return matchMapel && matchTutor && matchSearch;
            });
        }

        function renderCalendar(month, year) {
            grid.innerHTML = '';
            monthYearLabel.textContent = `${monthNames[month]} ${year}`;

            const filteredSessions = getFilteredSessions();

            const firstDayIndex = new Date(year, month, 1).getDay();
            const totalDays = new Date(year, month + 1, 0).getDate();
            const prevTotalDays = new Date(year, month, 0).getDate();

            const todayObj = new Date();
            todayObj.setHours(0,0,0,0);

            // Trailing days
            for (let x = firstDayIndex; x > 0; x--) {
                const cell = document.createElement('div');
                cell.className = 'calendar-day-cell other-month';
                cell.textContent = prevTotalDays - x + 1;
                grid.appendChild(cell);
            }

            // Current month days
            for (let i = 1; i <= totalDays; i++) {
                const cellDate = new Date(year, month, i);
                cellDate.setHours(0,0,0,0);
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;

                const cell = document.createElement('div');
                cell.className = 'calendar-day-cell';
                cell.textContent = i;
                cell.dataset.date = dateStr;

                if (cellDate.getTime() === todayObj.getTime()) {
                    cell.classList.add('today');
                }

                const dateSessions = filteredSessions.filter(s => s.dateStr === dateStr);
                if (dateSessions.length > 0) {
                    const now = new Date();
                    const curMin = now.getHours() * 60 + now.getMinutes();

                    let latestEndMin = 17 * 60;
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
                        cell.classList.add('scheduled');
                        const dot = document.createElement('span');
                        dot.className = 'schedule-dot';
                        cell.appendChild(dot);

                        if (dateSessions.length > 1) {
                            const badge = document.createElement('span');
                            badge.className = 'schedule-badge-count';
                            badge.textContent = dateSessions.length;
                            cell.appendChild(badge);
                        }
                    } else {
                        cell.classList.add('session-completed');
                        const dot = document.createElement('span');
                        dot.className = 'schedule-dot-completed';
                        cell.appendChild(dot);
                    }
                }

                if (dateStr === selectedDateStr) {
                    cell.classList.add('active-selected');
                }

                cell.addEventListener('click', function() {
                    document.querySelectorAll('.calendar-day-cell').forEach(c => c.classList.remove('active-selected'));
                    cell.classList.add('active-selected');
                    selectedDateStr = dateStr;
                    showAgenda(dateStr, cellDate);
                    openAdminModal(dateStr, cellDate);
                });

                grid.appendChild(cell);
            }
        }

        function openAdminModal(dateStr, dateObj) {
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            const formattedDate = dateObj.toLocaleDateString('id-ID', options);
            
            const titleEl = document.getElementById('adminModalDateTitle');
            if (titleEl) titleEl.textContent = `Jadwal Master: ${formattedDate}`;
            
            const daySessions = getFilteredSessions().filter(s => s.dateStr === dateStr);
            const contentEl = document.getElementById('adminModalContent');
            const totalInfoEl = document.getElementById('adminModalTotalInfo');

            if (totalInfoEl) {
                totalInfoEl.textContent = `Total: ${daySessions.length} Sesi Belajar Hari Ini`;
            }

            if (!contentEl) return;

            if (daySessions.length === 0) {
                contentEl.innerHTML = `
                    <div class="text-center py-5">
                        <div class="mx-auto mb-3 text-slate-300">
                            <i class="far fa-calendar-minus fa-4x text-purple-200"></i>
                        </div>
                        <h6 class="font-weight-bold text-slate-700 mb-1">Tidak Ada Sesi Bimbingan</h6>
                        <p class="text-xs text-muted mb-0">Tidak ada sesi belajar yang terjadwal pada tanggal ${formattedDate}.</p>
                    </div>
                `;
            } else {
                contentEl.innerHTML = `
                    <div class="mb-3 px-1">
                        <span class="text-xs text-muted">Berikut adalah daftar seluruh sesi belajar &amp; penugasan tutor pada <strong>${formattedDate}</strong>:</span>
                    </div>
                    <div class="row">
                        ${daySessions.map(s => {
                            let waSiswaBtn = '';
                            if (s.whatsapp) {
                                const waClean = s.whatsapp.replace(/[^0-9]/g, '');
                                const waFormatted = waClean.startsWith('0') ? '62' + waClean.substring(1) : waClean;
                                waSiswaBtn = `
                                    <a href="https://wa.me/${waFormatted}?text=Halo%20${encodeURIComponent(s.student_name)},%20informasi%20sesi%20bimbel%20${encodeURIComponent(s.subject)}%20hari%20ini%20mulai%20jam%20${encodeURIComponent(s.time)}." target="_blank" class="btn btn-xs btn-success rounded-lg font-weight-bold px-2.5 py-1 text-xs" style="background-color: #10b981; border: 0;">
                                        <i class="fab fa-whatsapp mr-1"></i> WA Siswa
                                    </a>
                                `;
                            }
                            
                            const key = (s.subject || '').toLowerCase();
                            const theme = mapelColors[key] || { bg: '#7c3aed', light: '#f3e8ff', text: '#6b21a8' };

                            return `
                                <div class="col-12 mb-3">
                                    <div class="card border-0 rounded-2xl p-3.5 shadow-sm bg-white" style="border-left: 5px solid ${theme.bg} !important; border-radius: 16px;">
                                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3 pb-2 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar font-bold d-flex justify-content-center align-items-center rounded-circle mr-3" style="width: 44px; height: 44px; font-size: 18px; min-width: 44px; background-color: ${theme.light}; color: ${theme.text};">
                                                    ${s.student_name.charAt(0).toUpperCase()}
                                                </div>
                                                <div>
                                                    <h6 class="font-weight-bold mb-0.5 text-base" style="color: ${theme.text};">${s.student_name}</h6>
                                                    <span class="text-xs text-slate-500"><i class="fas fa-school mr-1 text-slate-400"></i>${s.sekolah}</span>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge font-weight-bold px-3 py-1.5 rounded-full text-xs" style="background-color: ${theme.light}; color: ${theme.text}; border: 1px solid ${theme.bg}40;">
                                                    ${s.subject}
                                                </span>
                                                <span class="badge bg-slate-100 text-slate-700 font-weight-bold px-2.5 py-1.5 rounded-full text-xs">
                                                    Sesi ${s.session_index} / ${s.total_sessions}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-sm-7 mb-2 mb-sm-0">
                                                <div class="d-flex align-items-center text-xs font-weight-semibold text-slate-700 mb-1.5">
                                                    <i class="fas fa-chalkboard-teacher text-purple-600 mr-2" style="width: 16px;"></i>
                                                    <span>Tutor Pendamping: <strong class="text-purple-950">${s.tutor}</strong></span>
                                                </div>
                                                <div class="d-flex align-items-center text-xs font-weight-semibold text-slate-700">
                                                    <i class="far fa-clock text-amber-500 mr-2" style="width: 16px;"></i>
                                                    <span>Jam Sesi: <strong class="text-amber-700">${s.time} WIB</strong></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-5 text-sm-right mt-2 mt-sm-0 d-flex justify-content-end gap-1.5">
                                                ${waSiswaBtn}
                                                <a href="/admin/siswa/detail/${s.student_id}" class="btn btn-xs btn-primary rounded-lg font-weight-bold px-2.5 py-1 text-xs" style="background-color: #7c3aed; border: 0;">
                                                    <i class="fas fa-user-edit mr-1"></i> Detail
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                `;
            }

            $('#adminSessionDetailModal').modal('show');
        }

        function showAgenda(dateStr, dateObj) {
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            agendaHeaderDate.textContent = dateObj.toLocaleDateString('id-ID', options);

            const daySessions = getFilteredSessions().filter(s => s.dateStr === dateStr);
            const countBadge = document.getElementById('agendaCountBadge');
            if (countBadge) countBadge.textContent = `${daySessions.length} Sesi`;

            agendaContainer.innerHTML = '';

            if (daySessions.length === 0) {
                agendaContainer.innerHTML = `
                    <div class="text-center py-5 text-muted">
                        <i class="far fa-calendar-minus fa-2x text-slate-300 mb-2"></i>
                        <p class="text-xs mb-0">Tidak ada jadwal bimbingan pada tanggal ini.</p>
                    </div>
                `;
                return;
            }

            daySessions.forEach(s => {
                const item = document.createElement('div');
                item.className = 'card border-0 rounded-xl p-3 bg-light agenda-card-item shadow-xs mb-3';
                
                const key = (s.subject || '').toLowerCase();
                const theme = mapelColors[key] || { bg: '#7c3aed', light: '#f3e8ff', text: '#6b21a8' };
                item.style.borderLeft = `4px solid ${theme.bg}`;

                item.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="font-weight-bold text-purple-950 mb-0">${s.student_name}</h6>
                            <span class="text-xxs text-muted">${s.sekolah}</span>
                        </div>
                        <span class="badge text-xxs font-weight-bold px-2 py-1 rounded" style="background-color: ${theme.light}; color: ${theme.text};">
                            ${s.subject}
                        </span>
                    </div>
                    <div class="space-y-1 mt-2 pt-2 border-top border-white">
                        <div class="d-flex align-items-center text-xs text-slate-600 mb-1">
                            <i class="fas fa-chalkboard-teacher mr-2 text-purple-600" style="width: 14px;"></i>
                            <span class="font-weight-bold text-slate-800">${s.tutor}</span>
                        </div>
                        <div class="d-flex align-items-center text-xs text-slate-600 mb-2">
                            <i class="far fa-clock mr-2 text-amber-500" style="width: 14px;"></i>
                            <span>${s.time}</span>
                        </div>
                    </div>
                `;

                item.addEventListener('click', function() {
                    openAdminModal(dateStr, dateObj);
                });

                agendaContainer.appendChild(item);
            });
        }

        // Filter event listeners
        filterMapelEl.addEventListener('change', () => {
            renderCalendar(currentMonth, currentYear);
            showAgenda(selectedDateStr, new Date(currentYear, currentMonth, parseInt(selectedDateStr.split('-')[2])));
        });

        filterTutorEl.addEventListener('change', () => {
            renderCalendar(currentMonth, currentYear);
            showAgenda(selectedDateStr, new Date(currentYear, currentMonth, parseInt(selectedDateStr.split('-')[2])));
        });

        searchSiswaEl.addEventListener('input', () => {
            renderCalendar(currentMonth, currentYear);
            showAgenda(selectedDateStr, new Date(currentYear, currentMonth, parseInt(selectedDateStr.split('-')[2])));
        });

        resetBtnEl.addEventListener('click', () => {
            filterMapelEl.value = '';
            filterTutorEl.value = '';
            searchSiswaEl.value = '';
            renderCalendar(currentMonth, currentYear);
            showAgenda(selectedDateStr, new Date(currentYear, currentMonth, parseInt(selectedDateStr.split('-')[2])));
        });

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
