@extends('layout.app')

@section('title', 'Dashboard Siswa · Paradise of Math')

@section('content')
    @php
        $siswa   = Auth::guard('siswa')->user();
        $biodata = $siswa->biodata ?? [];

        // ── Data per-mapel baru ──
        $mapelJadwal     = $biodata['mapel_jadwal'] ?? [];
        $sesiPerMapel    = $biodata['sesi_per_mapel'] ?? [];
        $hariPerMapel    = $biodata['hari_per_mapel'] ?? [];
        $tanggalPerMapel = $biodata['tanggal_mulai_per_mapel'] ?? [];
        $jamPerMapel     = $biodata['jam_per_mapel'] ?? [];

        // ── Data database untuk Modal Tambah & Bayar ──
        $availableMapels = \App\Models\Mapel::all();
        if ($availableMapels->isEmpty()) {
            $availableMapels = collect([
                (object)['id' => 1, 'nama_mapel' => 'Matematika', 'shift' => 'Reguler'],
                (object)['id' => 2, 'nama_mapel' => 'Fisika', 'shift' => 'Reguler'],
                (object)['id' => 3, 'nama_mapel' => 'Kimia', 'shift' => 'Reguler'],
                (object)['id' => 4, 'nama_mapel' => 'Biologi', 'shift' => 'Reguler'],
                (object)['id' => 5, 'nama_mapel' => 'Bahasa Inggris', 'shift' => 'Reguler'],
                (object)['id' => 6, 'nama_mapel' => 'Bahasa Indonesia', 'shift' => 'Reguler'],
            ]);
        }
        $rekeningBanks    = \App\Models\Rekening::where('tipe', 'bank')->get();
        $rekeningEwallets = \App\Models\Rekening::where('tipe', 'ewallet')->get();

        // ── Data flat (legacy / gabungan) ──
        $hariPertemuan   = $biodata['hari_pertemuan'] ?? [];
        $tanggalMulai    = $biodata['tanggal_mulai'] ?? null;
        $jumlahPertemuan = $biodata['jumlah_pertemuan'] ?? null;

        // Fallback parsing dari tipe_paket
        if ($siswa->tipe_paket) {
            if (empty($mapelJadwal) && preg_match('/Mapel:\s*([^)|]+)/i', $siswa->tipe_paket, $m)) {
                $mapelJadwal = array_map('trim', explode(',', $m[1]));
            }
            if (empty($hariPertemuan) && preg_match('/Hari:\s*([^)|]+)/i', $siswa->tipe_paket, $m)) {
                $hariPertemuan = array_map('trim', explode(',', $m[1]));
            }
            if (!$tanggalMulai && preg_match('/Mulai:\s*([\d\-]+)/i', $siswa->tipe_paket, $m)) {
                $d = trim($m[1]);
                if (preg_match('/(\d{2})-(\d{2})-(\d{4})/', $d, $dM)) {
                    $tanggalMulai = $dM[3] . '-' . $dM[2] . '-' . $dM[1];
                } else {
                    $tanggalMulai = $d;
                }
            }
            if (!$jumlahPertemuan && preg_match('/Total Sesi:\s*(\d+)x/i', $siswa->tipe_paket, $m)) {
                $jumlahPertemuan = (int)$m[1];
            }
            if (!$jumlahPertemuan && preg_match('/Sesi:\s*(\d+)x/i', $siswa->tipe_paket, $m)) {
                $jumlahPertemuan = (int)$m[1];
            }
        }

        // Isi hariPertemuan dari per-mapel jika flat kosong
        if (empty($hariPertemuan) && !empty($hariPerMapel)) {
            foreach ($hariPerMapel as $h) {
                if (is_array($h)) {
                    foreach ($h as $hari) { if ($hari) $hariPertemuan[] = $hari; }
                }
            }
            $hariPertemuan = array_unique($hariPertemuan);
        }

        if (!$tanggalMulai && !empty($tanggalPerMapel)) {
            $filteredDates = array_filter($tanggalPerMapel);
            if (!empty($filteredDates)) {
                $tanggalMulai = min($filteredDates);
            }
        }
        if (!$tanggalMulai) {
            $tanggalMulai = $siswa->created_at ? $siswa->created_at->format('Y-m-d') : date('Y-m-d');
        }

        // Mapel list flat
        $mapels = $mapelJadwal ?: [];
        if (empty($mapels) && $siswa->tipe_paket && preg_match('/Mapel:\s*([^)|]+)/i', $siswa->tipe_paket, $m)) {
            $mapels = array_map('trim', explode(',', $m[1]));
        }

        // Jam Mulai & Selesai
        $paket          = $siswa->paket;
        $jamMulai       = $paket ? ($paket->jam_mulai ?? '15:30') : '15:30';
        $jamSelesai     = $jamMulai;
        $durationMinutes = 90;
        if ($paket && preg_match('/(\d+)\s*menit/i', $paket->detail_5 ?? '', $dM)) {
            $durationMinutes = (int) $dM[1];
        }

        if (!empty($jamPerMapel) && is_array($jamPerMapel)) {
            foreach ($jamPerMapel as $timeSet) {
                if (is_array($timeSet) && !empty($timeSet['jam_mulai'])) {
                    $jamMulai = $timeSet['jam_mulai'];
                    $jamSelesai = $timeSet['jam_selesai'] ?? date('H:i', strtotime($jamMulai . " + {$durationMinutes} minutes"));
                    break;
                }
            }
        }

        if ($jamSelesai === $jamMulai) {
            $jamSelesai = date('H:i', strtotime($jamMulai . " + {$durationMinutes} minutes"));
        }

        // Parse Guru — prioritaskan biodata->tutor_per_mapel (sama seperti showJadwal())
        $tutorPerMapel = $biodata['tutor_per_mapel'] ?? [];
        $gurus   = [];
        $hasGuru = false;

        if (!empty($tutorPerMapel) && is_array($tutorPerMapel)) {
            foreach ($tutorPerMapel as $mapelName => $guruName) {
                if (!empty($guruName) && strtolower($guruName) !== 'belum ditentukan') {
                    $gurus[] = $mapelName . ': ' . $guruName;
                    $hasGuru = true;
                }
            }
        } elseif ($siswa->tipe_paket && preg_match('/Guru:\s*([^)|]+)/i', $siswa->tipe_paket, $m)) {
            $gurus = array_map('trim', explode(',', $m[1]));
            foreach ($gurus as $g) {
                if (!empty($g) && $g !== '-' && strtolower($g) !== 'belum ditentukan') {
                    $hasGuru = true;
                }
            }
        }

        // Dynamic greeting
        $hour = date('H');
        if ($hour >= 5 && $hour < 11)       { $greeting = 'Selamat Pagi';  $greetingIcon = 'fa-sun text-amber-400'; }
        elseif ($hour >= 11 && $hour < 15)  { $greeting = 'Selamat Siang'; $greetingIcon = 'fa-cloud-sun text-yellow-300'; }
        elseif ($hour >= 15 && $hour < 18)  { $greeting = 'Selamat Sore';  $greetingIcon = 'fa-cloud-sun-rain text-orange-300'; }
        else                                { $greeting = 'Selamat Malam'; $greetingIcon = 'fa-moon text-purple-200'; }
    @endphp


    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6 col-12">
                    <h1 class="m-0 font-weight-bold text-purple-950">Dashboard Belajar</h1>
                    <p class="text-xs text-muted mb-0">Selamat datang kembali di portal ruang belajar Anda.</p>
                </div>
                <div class="col-sm-6 col-12 text-sm-right mt-2 mt-sm-0">
                    <div id="live-clock-container"
                        class="inline-block px-3 py-1.5 bg-white rounded-xl shadow-sm border border-purple-100 text-xs font-semibold text-purple-950">
                        <i class="far fa-clock text-purple-600 mr-1.5"></i> <span id="live-clock">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Flash Message Alerts -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-xl shadow-sm border-0 mb-4" role="alert"
                    style="background-color: #d1fae5; border-color: #a7f3d0; color: #065f46;">
                    <h5><i class="icon fas fa-check mr-2"></i>Sukses!</h5>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #065f46;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Welcome Banner -->
            <div class="card mb-4 overflow-hidden border-0 shadow-sm welcome-banner"
                style="background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%);">
                <div class="card-body p-4 text-white relative">
                    <div class="row align-items-center">
                        <div class="col-md-9 position-relative" style="z-index: 2;">
                            <span
                                class="px-3 py-1 bg-amber-400 text-purple-950 text-xs font-bold uppercase tracking-wider rounded-full shadow-sm">
                                Status: Siswa Aktif
                            </span>
                            <h2 class="font-serif mt-3 text-3xl font-bold d-flex align-items-center">
                                <i class="fas {{ $greetingIcon }} mr-3 mr-xs-2 welcome-icon"></i>
                                <span>{{ $greeting }}, {{ Auth::guard('siswa')->user()->name }}!</span>
                            </h2>
                            <p class="text-purple-200 mt-2 mb-0 max-w-xl text-sm">
                                Tetap semangat! Setiap tantangan matematika adalah langkah menuju prestasi terbaikmu. Pantau
                                jadwal belajar dan mari capai target belajarmu hari ini.
                            </p>
                        </div>
                        <div class="col-md-3 text-right d-none d-md-block position-relative" style="z-index: 2;">
                            <i class="fas fa-user-graduate fa-7x text-white-50 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Metrics -->
            <div class="row">
                <!-- Metric 1: Sesi Selesai -->
                <div class="col-lg-3 col-sm-6 col-6 mb-3 mb-sm-4 px-[6px] px-sm-3">
                    <div class="metric-card bg-white p-3 h-100 d-flex flex-column justify-between shadow-sm">
                        <div>
                            <div class="d-flex justify-between items-center mb-2">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kemajuan Sesi</span>
                                <div class="metric-icon-wrapper bg-emerald-50 text-emerald-500">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            <h3 class="font-weight-bold text-purple-950 mb-1">
                                <span id="completed-sessions-badge">-</span><span
                                    class="text-muted text-lg">/{{ $jumlahPertemuan ?: 0 }}</span>
                            </h3>
                            <p class="text-muted text-xxs mb-0">Total Sesi Bimbingan</p>
                        </div>
                        <a href="{{ route('siswa.jadwal') }}"
                            class="small-box-footer text-emerald-600 mt-3 pt-2 border-top text-left text-xs font-semibold d-flex items-center justify-between">
                            <span>Lihat Detail Sesi</span> <i class="fas fa-chevron-right text-xxs"></i>
                        </a>
                    </div>
                </div>

                <!-- Metric 2: Sesi Pekan Ini -->
                <div class="col-lg-3 col-sm-6 col-6 mb-3 mb-sm-4 px-[6px] px-sm-3">
                    <div class="metric-card bg-white p-3 h-100 d-flex flex-column justify-between shadow-sm">
                        <div>
                            <div class="d-flex justify-between items-center mb-2">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sesi Pekan
                                    Ini</span>
                                <div class="metric-icon-wrapper bg-indigo-50 text-indigo-500">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                            <h3 class="font-weight-bold text-purple-950 mb-1">
                                <span id="weekly-completed-badge">-</span><span
                                    class="text-purple-300 text-lg">/</span><span id="weekly-sessions-badge"
                                    class="text-muted text-lg">-</span> <span
                                    class="text-muted text-sm font-semibold">Selesai</span>
                            </h3>
                            <p class="text-muted text-xxs mb-0">Jadwal Les Minggu Ini</p>
                        </div>
                        <a href="{{ route('siswa.jadwal') }}"
                            class="small-box-footer text-indigo-600 mt-3 pt-2 border-top text-left text-xs font-semibold d-flex items-center justify-between">
                            <span>Buka Kalender Belajar</span> <i class="fas fa-chevron-right text-xxs"></i>
                        </a>
                    </div>
                </div>

                <!-- Metric 3: Tutor Pengajar -->
                <div class="col-lg-3 col-sm-6 col-6 mb-3 mb-sm-4 px-[6px] px-sm-3">
                    @php
                        $isMultipleGurus = $hasGuru && count($gurus) > 1;
                    @endphp
                    <div class="metric-card bg-white p-3 h-100 d-flex flex-column justify-between shadow-sm"
                         @if($isMultipleGurus)
                             style="cursor: pointer;"
                             data-toggle="modal"
                             data-target="#listTutorModal"
                         @endif>
                        <div>
                            <div class="d-flex justify-between items-center mb-2">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tutor
                                    Pendamping</span>
                                <div class="metric-icon-wrapper bg-teal-50 text-teal-500">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                            </div>
                            <h3 class="font-weight-bold text-purple-950 mb-1 text-truncate"
                                title="{{ $hasGuru ? implode(', ', $gurus) : 'Belum ditentukan' }}">
                                {{ $hasGuru ? count($gurus) : '0' }} <span class="text-muted text-lg">Tutor</span>
                            </h3>
                            <p class="text-muted text-xxs mb-0 text-truncate">
                                {{ $hasGuru ? implode(', ', $gurus) : 'Belum ditentukan oleh Admin' }}
                            </p>
                        </div>
                        @if($isMultipleGurus)
                            <a href="javascript:void(0)"
                                class="small-box-footer text-teal-600 mt-3 pt-2 border-top text-left text-xs font-semibold d-flex items-center justify-between">
                                <span>Lihat Tutor Pengajar</span> <i class="fas fa-eye text-xxs"></i>
                            </a>
                        @else
                            <a href="https://wa.me/6289675053537" target="_blank"
                                class="small-box-footer text-teal-600 mt-3 pt-2 border-top text-left text-xs font-semibold d-flex items-center justify-between">
                                <span>Hubungi Admin/Tutor</span> <i class="fas fa-chevron-right text-xxs"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Metric 4: Kelas Terdaftar -->
                <div class="col-lg-3 col-sm-6 col-6 mb-3 mb-sm-4 px-[6px] px-sm-3">
                    <div class="metric-card bg-white p-3 h-100 d-flex flex-column justify-between shadow-sm">
                        <div>
                            <div class="d-flex justify-between items-center mb-2">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Mata
                                    Pelajaran</span>
                                <div class="metric-icon-wrapper bg-amber-50 text-amber-500">
                                    <i class="fas fa-book-open"></i>
                                </div>
                            </div>
                            <h3 class="font-weight-bold text-purple-950 mb-1 text-truncate"
                                title="{{ count($mapels) > 0 ? implode(', ', $mapels) : 'Matematika' }}">
                                {{ count($mapels) > 0 ? count($mapels) : '1' }} <span
                                    class="text-muted text-lg">Mapel</span>
                            </h3>
                            <p class="text-muted text-xxs mb-0 text-truncate">
                                {{ count($mapels) > 0 ? implode(', ', $mapels) : 'Bimbingan Belajar Matematika' }}
                            </p>
                        </div>
                        <a href="{{ route('siswa.tambah-pelajaran') }}"
                            class="small-box-footer text-amber-600 mt-3 pt-2 border-top text-left text-xs font-semibold d-flex items-center justify-between">
                            <span>Lihat & Tambah Mapel</span> <i class="fas fa-chevron-right text-xxs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Detailed Graphic & Schedule Section -->
            <div class="row">

                <!-- Left Column: Charts and Today's Schedule -->
                <div class="col-lg-8 col-12">

                    <!-- Charts Grid -->
                    <div class="row">
                        <!-- Chart 1: Doughnut Progress -->
                        <div class="col-md-6 col-12 mb-4">
                            <div class="card chart-card bg-white h-100">
                                <div class="card-header bg-white border-0 pt-4 pb-2">
                                    <h5 class="card-title font-weight-bold text-purple-950 mb-0">Progress Belajar Anda</h5>
                                </div>
                                <div class="card-body d-flex flex-column justify-center items-center py-4">
                                    <div class="position-relative doughnut-wrapper" style="width: 170px; height: 170px;">
                                        <canvas id="sessionDoughnutChart"></canvas>
                                        <div class="doughnut-center-text">
                                            <span id="doughnut-percentage"
                                                class="d-block font-weight-bold text-2xl text-purple-950">0%</span>
                                            <span class="text-muted text-xxs">Selesai</span>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-4 mt-4 text-xs font-semibold text-slate-600">
                                        <span class="d-flex items-center"><i
                                                class="fas fa-circle text-emerald-500 mr-2 text-xxs"></i> Selesai</span>
                                        <span class="d-flex items-center"><i
                                                class="fas fa-circle text-purple-600 mr-2 text-xxs"></i> Sisa Sesi</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chart 2: Bar Rhythm -->
                        <div class="col-md-6 col-12 mb-4">
                            <div class="card chart-card bg-white h-100">
                                <div class="card-header bg-white border-0 pt-4 pb-2">
                                    <h5 class="card-title font-weight-bold text-purple-950 mb-0">Ritme Les Mingguan</h5>
                                </div>
                                <div class="card-body py-3 d-flex flex-column justify-center">
                                    <div style="height: 170px; position: relative;">
                                        <canvas id="weeklyRhythmChart"></canvas>
                                    </div>
                                    <p class="text-center text-muted text-xxs mt-3 mb-0">Rasio durasi belajar (dalam menit)
                                        per sesi terjadwal.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Schedule Card -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                        <div class="card-header bg-white border-0 pt-4 pb-2">
                            <h5 class="card-title font-weight-bold text-purple-950 mb-0">Jadwal Sesi Belajar Hari Ini</h5>
                        </div>
                        <div class="card-body pt-2" id="today-schedule-content">
                            <!-- Populated Dynamically by JS -->
                            <div class="text-center py-4 text-muted text-xs">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Menghitung jadwal hari ini...
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Student Activities & Trivia -->
                <div class="col-lg-4 col-12 mb-4">

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                                <div class="card-header bg-white border-0 pt-4 pb-2">
                                    <h5 class="font-weight-bold text-purple-950 mb-1">Layanan &amp; Fitur Akademik</h5>
                                    <p class="text-xs text-muted mb-0">Akses cepat menu bimbingan belajar dan administrasi
                                        Anda.</p>
                                </div>
                                <div class="card-body">
                                    <div class="academic-menu-grid">
                                        <!-- Menu 1 -->
                                        <a href="{{ route('siswa.invoice') }}" class="academic-menu-item text-decoration-none">
                                            <div class="academic-menu-icon-wrapper"
                                                style="background-color: #ecfeff; color: #0e7490;">
                                                <i class="fas fa-credit-card"></i>
                                            </div>
                                            <span class="academic-menu-label">Invoice Payment</span>
                                        </a>
                                        <!-- Menu 2 -->
                                        <a href="{{ route('siswa.riwayat') }}" class="academic-menu-item">
                                            <div class="academic-menu-icon-wrapper"
                                                style="background-color: #d1fae5; color: #065f46;">
                                                <i class="fas fa-book-reader"></i>
                                            </div>
                                            <span class="academic-menu-label">Riwayat Payment</span>
                                        </a>
                                        <!-- Menu 3 -->
                                         <a href="{{ route('siswa.transkip-nilai') }}" class="academic-menu-item">
                                            <div class="academic-menu-icon-wrapper"
                                                style="background-color: #d1fae5; color: #065f46;">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                            <span class="academic-menu-label">Transkip Nilai</span>
                                         </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trivia & Motivation Widget -->
                    <div class="card shadow-sm border-0 text-white overflow-hidden motivation-card"
                        style="background: linear-gradient(135deg, #4c1d95 0%, #7c3aed 100%); border-radius: 16px;">
                        <div class="card-body p-4 text-center d-flex flex-column justify-between h-100"
                            style="min-height: 200px;">
                            <div class="mb-3">
                                <div class="trivia-icon-bg inline-block px-3.5 py-3 rounded-2xl bg-white-10 mb-2">
                                    <i class="fas fa-lightbulb fa-2x text-amber-300"></i>
                                </div>
                                <h6 class="font-bold text-sm text-white mb-1">Math Trivia & Tips</h6>
                                <p class="text-xxs text-purple-300 uppercase tracking-widest font-semibold mb-2">Paradise of
                                    Math</p>
                            </div>
                            <div>
                                <p id="trivia-text" class="text-xs text-purple-100 italic px-2 mb-3 leading-relaxed">
                                    "Matematika adalah bahasa yang digunakan Tuhan untuk menulis alam semesta." &mdash;
                                    Galileo Galilei
                                </p>
                            </div>
                            <div>
                                <button id="trivia-btn"
                                    class="btn btn-xs btn-outline-light text-xxs px-4 py-2 rounded-full font-bold border-white-30"
                                    style="font-size: 11px; background-color: rgba(255,255,255,0.08);">
                                    Tips Lainnya <i class="fas fa-random ml-1.5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ChartJS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom styling inside view -->
    <style>
        .metric-card {
            border-radius: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #ece7f7 !important;
            position: relative;
            overflow: hidden;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px -10px rgba(76, 29, 149, 0.12), 0 4px 6px -2px rgba(76, 29, 149, 0.04);
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #7c3aed, #fbbf24);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .metric-card:hover::before {
            opacity: 1;
        }

        .metric-icon-wrapper {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }

        .chart-card {
            border-radius: 16px;
            border: 1px solid #ece7f7 !important;
            box-shadow: 0 1px 2px rgba(46, 16, 101, 0.02), 0 10px 18px -10px rgba(76, 29, 149, 0.08);
        }

        .doughnut-center-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            pointer-events: none;
        }

        .activity-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .schedule-icon-circle {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background-color: #f3e8ff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .bg-white-10 {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .border-white-30 {
            border-color: rgba(255, 255, 255, 0.25) !important;
        }

        .text-xxs {
            font-size: 0.68rem;
        }

        .gap-4 {
            gap: 1rem;
        }

        .welcome-icon {
            font-size: 1.8rem;
        }

        /* Pulse animations */
        .pulse-badge {
            animation: pulse-yellow-badge 2s infinite;
        }

        @keyframes pulse-yellow-badge {
            0% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(245, 158, 11, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
            }
        }

        .pulse-badge-active {
            background-color: #ef4444;
            color: #fff;
            animation: pulse-red-badge 1.5s infinite;
        }

        @keyframes pulse-red-badge {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(239, 68, 68, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }

        .welcome-banner {
            border-radius: 16px !important;
        }

        .motivation-card {
            box-shadow: 0 10px 15px -3px rgba(76, 29, 149, 0.15), 0 4px 6px -2px rgba(76, 29, 149, 0.05);
        }

        @media (max-width: 575.98px) {
            .metric-card {
                padding: 0.75rem !important;
            }

            .metric-card h3 {
                font-size: 1.15rem !important;
            }

            .metric-card h3 .text-lg {
                font-size: 0.8rem !important;
            }

            .metric-card .text-xs {
                font-size: 0.65rem !important;
            }

            .metric-card .text-xxs {
                font-size: 0.6rem !important;
                display: -webkit-box;
                -webkit-line-clamp: 1;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .metric-card .small-box-footer {
                font-size: 0.65rem !important;
                margin-top: 0.5rem !important;
                padding-top: 0.5rem !important;
            }

            .metric-icon-wrapper {
                width: 28px !important;
                height: 28px !important;
                font-size: 0.85rem !important;
                border-radius: 6px !important;
            }
        }

        /* Layanan & Fitur Akademik Grid Styles */
        .academic-menu-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.25rem 0.25rem;
        }

        @media (max-width: 991.98px) {
            .academic-menu-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        .academic-menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 0.75rem 0.25rem;
            border-radius: 16px;
        }

        .academic-menu-item:hover {
            transform: translateY(-3px);
            background-color: #faf8ff;
        }

        .academic-menu-icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(76, 29, 149, 0.05);
        }

        .academic-menu-item:hover .academic-menu-icon-wrapper {
            transform: scale(1.06);
            box-shadow: 0 8px 12px -2px rgba(76, 29, 149, 0.12);
        }

        .academic-menu-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: #4b5563;
            line-height: 1.3;
            max-width: 85px;
        }

        @media (max-width: 575.98px) {
            .academic-menu-label {
                font-size: 0.62rem !important;
                max-width: 75px !important;
            }

            .academic-menu-icon-wrapper {
                width: 44px !important;
                height: 44px !important;
                font-size: 1.05rem !important;
                border-radius: 12px !important;
            }
        }
    </style>

    <!-- JavaScript calculations & updates -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data per-mapel & legacy fallback dari controller
            const mapelJadwal     = @json($mapelJadwal ?? []);
            const sesiPerMapel    = @json($sesiPerMapel ?? []);
            const hariPerMapelRaw = @json($hariPerMapel ?? []);
            const tanggalPerMapel = @json($tanggalPerMapel ?? []);

            const legacyDays     = @json($hariPertemuan ?? []);
            const legacyStart    = "{{ $tanggalMulai }}";
            const legacyLimit    = {{ $jumlahPertemuan ?? 0 }};

            const jamMulai   = "{{ $jamMulai }}";
            const jamSelesai = "{{ $jamSelesai }}";
            const mapels     = @json($mapels ?? []);
            const gurus      = @json($gurus ?? []);

            const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const dayMap = {
                'minggu': 0, 'senin': 1, 'selasa': 2, 'rabu': 3,
                'kamis': 4, 'jumat': 5, 'sabtu': 6,
                'sunday': 0, 'monday': 1, 'tuesday': 2, 'wednesday': 3,
                'thursday': 4, 'friday': 5, 'saturday': 6
            };

            // Calculate exact scheduled dates for all mapels
            const scheduledDates = []; // { dateObj, mapelName, mapelIdx }

            function buildSchedule(days, startStr, limitSesi, mapelName, mapelIdx) {
                if (!days || !Array.isArray(days)) return;
                const scheduledDayNums = days.map(d => {
                    if (!d) return null;
                    const norm = String(d).trim().toLowerCase();
                    return dayMap[norm] !== undefined ? dayMap[norm] : null;
                }).filter(n => n !== null);

                const startDate = new Date(startStr);
                startDate.setHours(0, 0, 0, 0);
                if (isNaN(startDate.getTime()) || scheduledDayNums.length === 0 || limitSesi <= 0) return;

                let count = 0;
                let tempDate = new Date(startDate);
                for (let d = 0; d < 730 && count < limitSesi; d++) {
                    if (scheduledDayNums.includes(tempDate.getDay())) {
                        scheduledDates.push({
                            dateObj: new Date(tempDate),
                            mapelName: mapelName,
                            mapelIdx: mapelIdx
                        });
                        count++;
                    }
                    tempDate.setDate(tempDate.getDate() + 1);
                }
            }

            if (mapelJadwal && mapelJadwal.length > 0) {
                mapelJadwal.forEach((mapel, idx) => {
                    const hariRaw  = hariPerMapelRaw[idx] ?? {};
                    const days     = isArray(hariRaw) ? hariRaw : Object.values(hariRaw).filter(h => h);
                    const startStr = tanggalPerMapel[idx] ?? legacyStart;
                    const limit    = parseInt(sesiPerMapel[idx] ?? 0);
                    buildSchedule(days, startStr, limit, mapel, idx);
                });
            } else {
                buildSchedule(legacyDays, legacyStart, legacyLimit, 'Bimbingan', 0);
            }

            function isArray(val) {
                return Array.isArray(val);
            }

            // Sort all sessions chronologically
            scheduledDates.sort((a, b) => a.dateObj - b.dateObj);

            const totalLimitSesi = scheduledDates.length > 0 ? scheduledDates.length : (legacyLimit || 0);

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const endParts = jamSelesai.split(':');
            const startParts = jamMulai.split(':');
            const nowTime = new Date();
            const endMinutesToday = (parseInt(endParts[0]) || 17) * 60 + (parseInt(endParts[1]) || 0);
            const currentMinutesNow = nowTime.getHours() * 60 + nowTime.getMinutes();

            let completedCount = 0;
            let upcomingSessions = [];
            let todaySessions = [];

            scheduledDates.forEach((sItem) => {
                const sDate = sItem.dateObj;
                const sTime = sDate.getTime();
                const tTime = today.getTime();

                if (sTime < tTime) {
                    completedCount++;
                } else if (sTime === tTime) {
                    if (currentMinutesNow > endMinutesToday) {
                        completedCount++;
                    } else {
                        todaySessions.push(sItem);
                        upcomingSessions.push(sItem);
                    }
                } else {
                    upcomingSessions.push(sItem);
                }
            });

            // Update DOM completed sessions badge
            const completedBadge = document.getElementById('completed-sessions-badge');
            if (completedBadge) completedBadge.textContent = completedCount;

            // Calculate sessions scheduled for this week (Mon - Sun)
            const curr = new Date();
            const dayOfWeekNow = curr.getDay(); // 0 is Sun, 1 is Mon...
            const distanceToMon = dayOfWeekNow === 0 ? -6 : 1 - dayOfWeekNow;
            const firstday = new Date(curr);
            firstday.setDate(curr.getDate() + distanceToMon);
            firstday.setHours(0, 0, 0, 0);

            const lastday = new Date(firstday);
            lastday.setDate(firstday.getDate() + 6);
            lastday.setHours(23, 59, 59, 999);

            let thisWeekCount = 0;
            let thisWeekCompletedCount = 0;
            scheduledDates.forEach(sItem => {
                const date = sItem.dateObj;
                if (date >= firstday && date <= lastday) {
                    thisWeekCount++;
                    const sTime = date.getTime();
                    const tTime = today.getTime();
                    if (sTime < tTime) {
                        thisWeekCompletedCount++;
                    } else if (sTime === tTime) {
                        if (currentMinutesNow > endMinutesToday) {
                            thisWeekCompletedCount++;
                        }
                    }
                }
            });
            
            const weeklyBadge = document.getElementById('weekly-sessions-badge');
            const weeklyCompBadge = document.getElementById('weekly-completed-badge');
            if (weeklyBadge) weeklyBadge.textContent = thisWeekCount;
            if (weeklyCompBadge) weeklyCompBadge.textContent = thisWeekCompletedCount;

            // Doughnut Chart Progress
            const canvasDoughnut = document.getElementById('sessionDoughnutChart');
            if (canvasDoughnut) {
                const ctxDoughnut = canvasDoughnut.getContext('2d');
                const remainingCount = totalLimitSesi - completedCount;

                new Chart(ctxDoughnut, {
                    type: 'doughnut',
                    data: {
                        labels: ['Selesai', 'Sisa Sesi'],
                        datasets: [{
                            data: [completedCount, remainingCount < 0 ? 0 : (totalLimitSesi === 0 ? 1 : remainingCount)],
                            backgroundColor: ['#10b981', '#7c3aed'],
                            borderColor: ['#fff', '#fff'],
                            borderWidth: 2,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return ` ${context.label}: ${context.raw} Sesi`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            const percentage = totalLimitSesi > 0 ? Math.round((completedCount / totalLimitSesi) * 100) : 0;
            const pctEl = document.getElementById('doughnut-percentage');
            if (pctEl) pctEl.textContent = percentage + '%';

            // Weekly study duration chart
            const canvasBar = document.getElementById('weeklyRhythmChart');
            if (canvasBar) {
                const ctxBar = canvasBar.getContext('2d');
                const weeklyDuration = [0, 0, 0, 0, 0, 0, 0]; // Sun, Mon, Tue, Wed, Thu, Fri, Sat

                let durMin = 90;
                if (endParts.length === 2 && startParts.length === 2) {
                    const diffMin = (parseInt(endParts[0]) * 60 + parseInt(endParts[1])) - (parseInt(startParts[0]) * 60 + parseInt(startParts[1]));
                    if (diffMin > 0) durMin = diffMin;
                }

                // Populate duration for all active scheduled days in the week
                scheduledDates.forEach(sItem => {
                    const d = sItem.dateObj;
                    if (d >= firstday && d <= lastday) {
                        weeklyDuration[d.getDay()] += durMin;
                    }
                });

                // Fallback: if no sessions this week yet, show pattern by active day numbers
                if (weeklyDuration.every(v => v === 0)) {
                    let activeDays = [];
                    if (mapelJadwal && mapelJadwal.length > 0) {
                        mapelJadwal.forEach((m, idx) => {
                            const hRaw = hariPerMapelRaw[idx] ?? {};
                            const list = isArray(hRaw) ? hRaw : Object.values(hRaw);
                            list.forEach(dayStr => {
                                if (dayStr) {
                                    const n = dayMap[String(dayStr).trim().toLowerCase()];
                                    if (n !== undefined) activeDays.push(n);
                                }
                            });
                        });
                    } else if (legacyDays) {
                        legacyDays.forEach(dayStr => {
                            if (dayStr) {
                                const n = dayMap[String(dayStr).trim().toLowerCase()];
                                if (n !== undefined) activeDays.push(n);
                            }
                        });
                    }
                    activeDays.forEach(dayIdx => {
                        weeklyDuration[dayIdx] = durMin;
                    });
                }

                const sortedDurations = [
                    weeklyDuration[1], // Mon
                    weeklyDuration[2], // Tue
                    weeklyDuration[3], // Wed
                    weeklyDuration[4], // Thu
                    weeklyDuration[5], // Fri
                    weeklyDuration[6], // Sat
                    weeklyDuration[0]  // Sun
                ];

                new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                        datasets: [{
                            label: 'Durasi Belajar (Menit)',
                            data: sortedDurations,
                            backgroundColor: sortedDurations.map(dur => dur > 0 ? 'rgba(124, 58, 237, 0.85)' : 'rgba(226, 232, 240, 0.45)'),
                            borderColor: sortedDurations.map(dur => dur > 0 ? '#7c3aed' : '#cbd5e1'),
                            borderWidth: 1.5,
                            borderRadius: 8,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return ` Durasi: ${context.raw} Menit`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: Math.max(180, ...sortedDurations) + 30,
                                grid: { color: 'rgba(241, 245, 249, 1)' },
                                ticks: {
                                    stepSize: 30,
                                    callback: function (value) { return value + 'm'; }
                                }
                            },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // Jadwal Hari Ini / Countdown
            const scheduleContent = document.getElementById('today-schedule-content');

            if (scheduleContent) {
                if (todaySessions.length > 0) {
                    const mapelNamesToday = [...new Set(todaySessions.map(s => s.mapelName))].join(', ');
                    const startMins = (parseInt(startParts[0]) || 15) * 60 + (parseInt(startParts[1]) || 30);

                    let statusBadge = '';
                    if (currentMinutesNow < startMins) {
                        statusBadge = '<span class="badge badge-warning text-white pulse-badge"><i class="fas fa-clock mr-1"></i> Akan Datang</span>';
                    } else if (currentMinutesNow >= startMins && currentMinutesNow <= endMinutesToday) {
                        statusBadge = '<span class="badge badge-danger pulse-badge-active"><i class="fas fa-video mr-1"></i> Berlangsung</span>';
                    } else {
                        statusBadge = '<span class="badge badge-success"><i class="fas fa-check mr-1"></i> Selesai</span>';
                    }

                    scheduleContent.innerHTML = `
                        <div class="p-3 bg-purple-50 rounded-2xl border border-purple-100 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-between gap-3">
                            <div class="d-flex align-items-center">
                                <div class="schedule-icon-circle mr-3">
                                    <i class="fas fa-graduation-cap fa-lg text-purple-600"></i>
                                </div>
                                <div>
                                    <h6 class="font-bold text-purple-950 mb-1">${mapelNamesToday || mapels.join(', ') || 'Belajar Matematika'}</h6>
                                    <p class="text-xs text-slate-500 mb-1"><i class="far fa-user mr-1"></i> Tutor: ${gurus.join(', ') || 'Paradise of Math Tutor'}</p>
                                    <p class="text-xs text-purple-600 font-bold mb-0"><i class="far fa-clock mr-1"></i> ${jamMulai} - ${jamSelesai} WIB</p>
                                </div>
                            </div>
                            <div class="mt-2 mt-sm-0 align-self-end align-self-sm-center">
                                ${statusBadge}
                            </div>
                        </div>
                    `;
                } else {
                    let nextSessionItem = null;
                    let diffDays = 0;

                    for (let i = 0; i < upcomingSessions.length; i++) {
                        const sItem = upcomingSessions[i];
                        if (sItem.dateObj > today) {
                            nextSessionItem = sItem;
                            const timeDiff = nextSessionItem.dateObj.getTime() - today.getTime();
                            diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                            break;
                        }
                    }

                    if (nextSessionItem) {
                        const opt = { weekday: 'long', day: 'numeric', month: 'long' };
                        const formattedNextDate = nextSessionItem.dateObj.toLocaleDateString('id-ID', opt);

                        scheduleContent.innerHTML = `
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center py-4">
                                <div class="mb-2">
                                    <i class="far fa-calendar-alt text-purple-300 fa-2x"></i>
                                </div>
                                <h6 class="font-bold text-purple-950 mb-1">Hari Ini Tidak Ada Sesi Les</h6>
                                <p class="text-xs text-slate-400 mb-3">Gunakan waktu luangmu untuk mengulang pelajaran atau mencoba latihan kuis.</p>
                                <div class="inline-block px-4 py-2 bg-amber-50 border border-amber-200 rounded-xl">
                                    <span class="text-xs font-semibold text-amber-800">
                                        <i class="fas fa-hourglass-start mr-1"></i> Sesi berikutnya (${nextSessionItem.mapelName}): <strong>${formattedNextDate}</strong> (${diffDays} hari lagi) pukul <strong>${jamMulai}</strong>
                                    </span>
                                </div>
                            </div>
                        `;
                    } else {
                        scheduleContent.innerHTML = `
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center py-4">
                                <div class="mb-2">
                                    <i class="fas fa-check-double text-emerald-400 fa-2x"></i>
                                </div>
                                <h6 class="font-bold text-slate-800 mb-1">Semua Sesi Telah Selesai</h6>
                                <p class="text-xs text-slate-500 mb-0">Luar biasa! Anda telah menyelesaikan semua sesi pada paket bimbingan belajar Anda.</p>
                            </div>
                        `;
                    }
                }
            }

            // Realtime clock update
            function updateClock() {
                const clockEl = document.getElementById('live-clock');
                if (!clockEl) return;
                const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
                const now = new Date();
                const dateString = now.toLocaleDateString('id-ID', options);
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                clockEl.innerHTML = `${dateString} &bull; <strong class="text-purple-900">${hours}:${minutes}:${seconds} WIB</strong>`;
            }
            updateClock();
            setInterval(updateClock, 1000);

            // Math trivia quotes rotation
            const trivias = [
                "Matematika adalah bahasa yang digunakan Tuhan untuk menulis alam semesta. &mdash; Galileo Galilei",
                "Tahukah kamu? Angka Nol (0) tidak ditulis dalam huruf/angka Romawi kuno.",
                "TIPS: Menggambar bentuk visual sangat membantu memecahkan soal geometri rumit secara cepat.",
                "Suku pertama dalam urutan Fibonacci berasal dari Leonardo dari Pisa (dikenal sebagai Fibonacci) di abad ke-12.",
                "TIPS: Luangkan waktu 5 menit istirahat jika merasa buntu dalam menyelesaikan soal hitungan aljabar.",
                "Matematika berasal dari bahasa Yunani 'mathema' yang berarti sains, pengetahuan, atau pembelajaran.",
                "TIPS: Menghafal konsep di balik rumus matematika jauh lebih efektif dibanding hanya sekadar menghafal bentuk rumusnya!"
            ];

            let triviaIdx = 0;
            const triviaText = document.getElementById('trivia-text');
            const triviaBtn = document.getElementById('trivia-btn');

            if (triviaText && triviaBtn) {
                triviaBtn.addEventListener('click', function () {
                    let nextIdx = triviaIdx;
                    while (nextIdx === triviaIdx) {
                        nextIdx = Math.floor(Math.random() * trivias.length);
                    }
                    triviaIdx = nextIdx;
                    triviaText.innerHTML = `"${trivias[triviaIdx]}"`;
                });
            }
        });
    </script>

    <!-- Modal List Tutor Pengajar -->
    <div class="modal fade" id="listTutorModal" tabindex="-1" role="dialog" aria-labelledby="listTutorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 18px; overflow: hidden;">
                <div class="modal-header bg-purple-950 text-white border-0 py-3" style="background-color: #2e1065;">
                    <h5 class="modal-title font-weight-bold text-md text-white" id="listTutorModalLabel" style="color: #fff;">Daftar Tutor Pendamping Anda</h5>
                    <button type="button" class="close text-white border-0 bg-transparent" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; outline: none; color: #fff;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4 text-left">
                    <p class="text-xs text-muted mb-4">Berikut adalah daftar guru pendamping yang ditugaskan untuk bimbingan belajar Anda:</p>
                    <div class="d-flex flex-column gap-3">
                        @foreach($gurus as $gName)
                            <div class="p-3 rounded-xl border d-flex align-items-center mb-3" style="background-color: #f8fafc; border-color: #e2e8f0; border-radius: 12px;">
                                <div class="avatar bg-purple-100 text-purple-700 font-bold d-flex justify-content-center align-items-center rounded-circle mr-3" style="width: 40px; height: 40px; font-size: 16px; min-width: 40px;">
                                    {{ strtoupper(substr(preg_replace('/^(math|english|ipa|ips):\s*/i', '', $gName), 0, 1)) }}
                                </div>
                                <div style="flex: 1;">
                                    <h6 class="font-weight-bold text-purple-950 mb-0.5 text-sm">{{ preg_replace('/^(math|english|ipa|ips):\s*/i', '', $gName) }}</h6>
                                    @if(preg_match('/^(math|english|ipa|ips)/i', $gName, $specMatches))
                                        <span class="badge bg-purple-100 text-purple-800 px-2 py-0.5 text-[10px] font-bold uppercase rounded">
                                            {{ strtoupper($specMatches[1]) }}
                                        </span>
                                    @else
                                        <span class="badge bg-slate-100 text-slate-600 px-2 py-0.5 text-[10px] font-bold uppercase rounded">
                                            Tutor Pendamping
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light p-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-secondary rounded-lg font-weight-bold px-4" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection