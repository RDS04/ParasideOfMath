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
                            <p class="text-purple-200 text-xs mb-3">Tipe Paket: {{ $siswa->tipe_paket }}</p>
                            
                            <div class="border-top border-purple-800 my-3"></div>

                            <div class="space-y-2 text-xs">
                                <div>
                                    <span class="text-purple-300 font-semibold block uppercase tracking-wider text-[10px]">Hari Les Seminggu</span>
                                    <span class="font-bold text-sm">{{ implode(', ', $hariPertemuan) }}</span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-purple-300 font-semibold block uppercase tracking-wider text-[10px]">Tanggal Mulai Les</span>
                                    <span class="font-bold text-sm">{{ date('d F Y', strtotime($tanggalMulai)) }}</span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-purple-300 font-semibold block uppercase tracking-wider text-[10px]">Jam Belajar</span>
                                    <span class="font-bold text-sm">{{ $jamMulai }} - {{ $jamSelesai }}</span>
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

                    <!-- Agenda Sesi Bulan Ini -->
                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="card-title font-weight-bold text-purple-950 mb-0">Agenda Sesi Bulan Ini</h6>
                        </div>
                        <div class="card-body p-0 max-h-[300px] overflow-y-auto" id="agendaListContainer" style="max-height: 300px; overflow-y: auto;">
                            <!-- Agenda sessions rendered dynamically here -->
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Section Menu Akademik -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-header bg-white border-0 pt-4 pb-2">
                        <h5 class="font-weight-bold text-purple-950 mb-1">Layanan &amp; Fitur Akademik</h5>
                        <p class="text-xs text-muted mb-0">Akses cepat menu bimbingan belajar dan administrasi Anda.</p>
                    </div>
                    <div class="card-body">
                        <div class="academic-menu-grid">
                            <!-- Menu 1 -->
                            <div class="academic-menu-item">
                                <div class="academic-menu-icon-wrapper" style="background-color: #d1fae5; color: #065f46;">
                                    <i class="fas fa-qrcode"></i>
                                </div>
                                <span class="academic-menu-label">Presence QR Code</span>
                            </div>
                            <!-- Menu 2 -->
                            <div class="academic-menu-item">
                                <div class="academic-menu-icon-wrapper" style="background-color: #fef3c7; color: #92400e;">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <span class="academic-menu-label">Student e-Card</span>
                            </div>
                            <!-- Menu 3 -->
                            <div class="academic-menu-item">
                                <div class="academic-menu-icon-wrapper" style="background-color: #ecfeff; color: #0e7490;">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <span class="academic-menu-label">Invoice Payment</span>
                            </div>
                            <!-- Menu 4 -->
                            <div class="academic-menu-item">
                                <div class="academic-menu-icon-wrapper" style="background-color: #d1fae5; color: #065f46;">
                                    <i class="fas fa-book-reader"></i>
                                </div>
                                <span class="academic-menu-label">Registered Course</span>
                            </div>
                            <!-- Menu 5 -->
                            <div class="academic-menu-item">
                                <div class="academic-menu-icon-wrapper" style="background-color: #d1fae5; color: #065f46;">
                                    <i class="fas fa-poll"></i>
                                </div>
                                <span class="academic-menu-label">Exam Result</span>
                            </div>
                            <!-- Menu 6 -->
                            <div class="academic-menu-item">
                                <div class="academic-menu-icon-wrapper" style="background-color: #d1fae5; color: #065f46;">
                                    <i class="fas fa-book"></i>
                                </div>
                                <span class="academic-menu-label">Thesis</span>
                            </div>
                            <!-- Menu 7 -->
                            <div class="academic-menu-item">
                                <div class="academic-menu-icon-wrapper" style="background-color: #d1fae5; color: #065f46;">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <span class="academic-menu-label">Lecturer Consultation</span>
                            </div>
                            <!-- Menu 8 -->
                            <div class="academic-menu-item">
                                <div class="academic-menu-icon-wrapper" style="background-color: #fef3c7; color: #92400e;">
                                    <i class="fas fa-award"></i>
                                </div>
                                <span class="academic-menu-label">Transcript</span>
                            </div>
                            <!-- Menu 9 -->
                            <div class="academic-menu-item">
                                <div class="academic-menu-icon-wrapper" style="background-color: #fef3c7; color: #92400e;">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <span class="academic-menu-label">Learning Progress</span>
                            </div>
                            <!-- Menu 10 -->
                            <div class="academic-menu-item">
                                <div class="academic-menu-icon-wrapper" style="background-color: #d1fae5; color: #065f46;">
                                    <i class="fas fa-clipboard-check"></i>
                                </div>
                                <span class="academic-menu-label">Assessment</span>
                            </div>
                            <!-- Menu 11 -->
                            <div class="academic-menu-item">
                                <div class="academic-menu-icon-wrapper" style="background-color: #d1fae5; color: #065f46;">
                                    <i class="fas fa-file-signature"></i>
                                </div>
                                <span class="academic-menu-label">Input KRS</span>
                            </div>
                            <!-- Menu 12 -->
                            <div class="academic-menu-item">
                                <div class="academic-menu-icon-wrapper" style="background-color: #fef3c7; color: #92400e;">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <span class="academic-menu-label">Academic Data</span>
                            </div>
                            <!-- Menu 13 -->
                            <div class="academic-menu-item">
                                <div class="academic-menu-icon-wrapper" style="background-color: #e0e7ff; color: #3730a3;">
                                    <i class="fas fa-hotel"></i>
                                </div>
                                <span class="academic-menu-label">Accreditation</span>
                            </div>
                            <!-- Menu 14 -->
                            <div class="academic-menu-item">
                                <div class="academic-menu-icon-wrapper" style="background-color: #e0e7ff; color: #3730a3;">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <span class="academic-menu-label">Majors' Accreditation</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                <div class="mb-2">
                    <span id="modalSessionIndex" class="badge badge-warning text-purple-950 font-weight-bold px-3 py-1.5 rounded-full text-xs">Sesi 1 dari 9</span>
                </div>
                <p id="modalDate" class="text-sm font-semibold text-purple-700 mb-4" style="color: #7c3aed;">Senin, 10 Agustus 2026</p>
                <div class="p-3 bg-purple-50 rounded-2xl text-left border border-purple-100 mb-3 text-xs" style="background-color: #f5f3ff; border: 1px solid #ddd6fe;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-slate-500">Jam Sesi:</span>
                        <span class="font-weight-bold text-slate-800">{{ $jamMulai }} - {{ $jamSelesai }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-slate-500">Metode:</span>
                        <span class="font-weight-bold text-slate-800">{{ str_contains(strtolower($siswa->tipe_paket), 'privat') ? 'Privat 1 on 1' : 'Kelompok' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-slate-500">Lokasi:</span>
                        <span class="font-weight-bold text-slate-800">Paradise of Math Center / Online</span>
                    </div>
                    @if(!empty($mapels))
                    <div class="d-flex justify-content-between">
                        <span class="text-slate-500">Mata Pelajaran:</span>
                        <span class="font-weight-bold text-slate-800 text-right">{{ implode(', ', $mapels) }}</span>
                    </div>
                    @endif
                </div>
                <p class="text-slate-400 text-xxs mb-0" style="font-size: 11px;">Hubungi Admin jika Anda ingin merubah atau menjadwalkan ulang sesi ini.</p>
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
    
    .schedule-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background-color: #fff;
        position: absolute;
        bottom: 6px;
    }

    .agenda-item {
        border-left: 4px solid #7c3aed;
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
        const selectedDays = @json($hariPertemuan);
        const startDateStr = "{{ $tanggalMulai }}";
        const mapels = @json($mapels);
        const limitSesi = {{ $jumlahPertemuan ?? 0 }};
        
        const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        const dayMap = {
            'Minggu': 0, 'Senin': 1, 'Selasa': 2, 'Rabu': 3,
            'Kamis': 4, 'Jumat': 5, 'Sabtu': 6
        };

        const scheduledDayNums = selectedDays.map(d => dayMap[d]);
        const startLimitDate = new Date(startDateStr);
        startLimitDate.setHours(0,0,0,0);

        // Pre-calculate exact scheduled sessions dates
        const scheduledDates = [];
        if (limitSesi > 0 && scheduledDayNums.length > 0) {
            let tempDate = new Date(startLimitDate);
            for (let d = 0; d < 365; d++) {
                if (scheduledDates.length >= limitSesi) {
                    break;
                }
                const dayOfWeek = tempDate.getDay();
                if (scheduledDayNums.includes(dayOfWeek)) {
                    const y = tempDate.getFullYear();
                    const m = String(tempDate.getMonth() + 1).padStart(2, '0');
                    const day = String(tempDate.getDate()).padStart(2, '0');
                    const dateStr = `${y}-${m}-${day}`;
                    scheduledDates.push({
                        dateStr: dateStr,
                        sessionIndex: scheduledDates.length + 1,
                        dayName: dayNames[dayOfWeek],
                        dateObj: new Date(tempDate)
                    });
                }
                tempDate.setDate(tempDate.getDate() + 1);
            }
        }

        // Set Sesi Berakhir date in info panel
        if (scheduledDates.length > 0) {
            const lastSession = scheduledDates[scheduledDates.length - 1];
            const lastDate = lastSession.dateObj;
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            const endDateElement = document.getElementById('sessionEndDateVal');
            if (endDateElement) {
                endDateElement.textContent = lastDate.toLocaleDateString('id-ID', options);
            }

            // Notification Check for Session 1
            const firstSession = scheduledDates[0];
            const firstDateStr = firstSession.dateStr;
            const today = new Date();
            const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

            if (firstDateStr === todayStr) {
                // 1. Show in-app banner
                const banner = document.getElementById('sessionNotificationBanner');
                const bannerText = document.getElementById('sessionNotificationText');
                if (banner && bannerText) {
                    bannerText.innerHTML = `Hari ini adalah <strong>Sesi 1</strong> Bimbingan Belajar Anda! Sesi dimulai pukul <strong>{{ $jamMulai }} - {{ $jamSelesai }}</strong>. Selamat belajar dan mari raih prestasi terbaik!`;
                    banner.classList.remove('d-none');
                }

                // 2. Trigger native phone notification via HTML5 Web Notifications API
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

            function showNativeNotification() {
                const title = "Sesi 1 Mulai Hari Ini!";
                const options = {
                    body: `Jam: {{ $jamMulai }} - {{ $jamSelesai }}. Ayo persiapkan diri Anda untuk belajar di Paradise of Math!`,
                    silent: false
                };
                new Notification(title, options);
            }
        }

        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();

        const grid = document.getElementById('calendarDaysGrid');
        const monthYearLabel = document.getElementById('currentMonthYear');
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

            const today = new Date();
            today.setHours(0,0,0,0);

            // 1. Previous Month's trailing days
            for (let x = firstDayIndex; x > 0; x--) {
                const cell = document.createElement('div');
                cell.className = 'calendar-day-cell other-month';
                cell.textContent = prevTotalDays - x + 1;
                grid.appendChild(cell);
            }

            const agendaSesi = [];

            // 2. Current Month's days
            for (let i = 1; i <= totalDays; i++) {
                const currentDate = new Date(year, month, i);
                currentDate.setHours(0,0,0,0);

                const cell = document.createElement('div');
                cell.className = 'calendar-day-cell';
                
                const numberSpan = document.createElement('span');
                numberSpan.textContent = i;
                cell.appendChild(numberSpan);

                const isToday = currentDate.getTime() === today.getTime();
                if (isToday) {
                    cell.classList.add('today');
                }

                // Check if this date is a scheduled date in our precalculated list
                const y = currentDate.getFullYear();
                const m = String(currentDate.getMonth() + 1).padStart(2, '0');
                const d = String(currentDate.getDate()).padStart(2, '0');
                const currentDateStr = `${y}-${m}-${d}`;

                const scheduleInfo = scheduledDates.find(sd => sd.dateStr === currentDateStr);

                if (scheduleInfo) {
                    cell.classList.add('scheduled');
                    const dot = document.createElement('span');
                    dot.className = 'schedule-dot';
                    cell.appendChild(dot);
                    
                    cell.title = `Sesi ${scheduleInfo.sessionIndex} dari ${limitSesi}`;

                    // Add click event for modal popup
                    cell.addEventListener('click', function() {
                        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                        const formattedDate = currentDate.toLocaleDateString('id-ID', options);
                        document.getElementById('modalDate').textContent = formattedDate;
                        document.getElementById('modalSessionIndex').textContent = `Sesi ${scheduleInfo.sessionIndex} dari ${limitSesi}`;
                        $('#sessionDetailModal').modal('show');
                    });

                    // Store in agenda list
                    agendaSesi.push({
                        date: currentDate,
                        dayNum: i,
                        dayName: dayNames[currentDate.getDay()],
                        sessionIndex: scheduleInfo.sessionIndex
                    });
                }

                grid.appendChild(cell);
            }

            // 3. Next Month's leading days (pad to multiples of 7)
            const totalCells = firstDayIndex + totalDays;
            const remainingCells = (7 - (totalCells % 7)) % 7;
            for (let y = 1; y <= remainingCells; y++) {
                const cell = document.createElement('div');
                cell.className = 'calendar-day-cell other-month';
                cell.textContent = y;
                grid.appendChild(cell);
            }

            // Render agenda sidebar
            renderAgendaList(agendaSesi, today);
        }

        function renderAgendaList(sessions, today) {
            agendaContainer.innerHTML = '';
            if (sessions.length === 0) {
                agendaContainer.innerHTML = `
                    <div class="py-4 text-center text-muted text-xs">
                        Tidak ada sesi belajar terjadwal di bulan ini.
                    </div>
                `;
                return;
            }

            // Sort by date ascending
            sessions.sort((a, b) => a.date - b.date);

            sessions.forEach(session => {
                const isToday = session.date.getTime() === today.getTime();
                const formattedDate = session.date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                
                const agendaItem = document.createElement('div');
                agendaItem.className = `agenda-item p-3 mb-2 bg-light rounded-xl d-flex justify-between align-items-center text-xs mx-3 ${isToday ? 'today-agenda bg-amber-50' : ''}`;
                
                agendaItem.innerHTML = `
                    <div style="flex: 1;">
                        <span class="d-block font-weight-bold text-purple-950">${session.dayName}, ${formattedDate}</span>
                        <small class="text-slate-400">Mapel: ${mapels.join(', ') || 'Semua'}</small>
                        <small class="d-block text-purple-600 font-weight-bold mt-1">Sesi ${session.sessionIndex} dari ${limitSesi}</small>
                    </div>
                    <span class="badge ${isToday ? 'badge-warning text-purple-950' : 'badge-purple'} font-weight-bold shrink-0">${isToday ? 'Hari Ini' : 'Sesi ' + session.sessionIndex}</span>
                `;

                agendaItem.addEventListener('click', function() {
                    document.getElementById('modalDate').textContent = `${session.dayName}, ${formattedDate}`;
                    document.getElementById('modalSessionIndex').textContent = `Sesi ${session.sessionIndex} dari ${limitSesi}`;
                    $('#sessionDetailModal').modal('show');
                });

                agendaContainer.appendChild(agendaItem);
            });
        }

        document.getElementById('btnPrevMonth').addEventListener('click', function() {
            if (currentMonth === 0) {
                currentMonth = 11;
                currentYear--;
            } else {
                currentMonth--;
            }
            renderCalendar(currentMonth, currentYear);
        });

        document.getElementById('btnNextMonth').addEventListener('click', function() {
            if (currentMonth === 11) {
                currentMonth = 0;
                currentYear++;
            } else {
                currentMonth++;
            }
            renderCalendar(currentMonth, currentYear);
        });

        // Initial calendar render
        renderCalendar(currentMonth, currentYear);
    });
</script>
@endif
@endsection
