@extends('layout.app')

@section('title', 'Dashboard Guru · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-teal-950 text-2xl tracking-tight">Dashboard Guru</h1>
                    <p class="text-xs text-muted mb-0">Selamat datang kembali di portal akademik Paradise of Math.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-xs bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="/" class="text-teal-600 font-semibold"><i class="fas fa-home mr-1"></i> Home</a></li>
                        <li class="breadcrumb-item active text-slate-500">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-xl shadow-sm border-0 mb-4 p-3" role="alert" style="background-color: #f0fdf4; color: #15803d; border-left: 5px solid #22c55e !important;">
                    <i class="fas fa-check-circle mr-2 text-lg align-middle"></i> 
                    <span class="align-middle font-medium text-sm">{{ session('success') }}</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #15803d; opacity: 0.8;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(isset($isBiodataComplete) && !$isBiodataComplete)
                <!-- ══════ INCOMPLETE BIODATA ALERT BANNER ══════ -->
                <div class="card mb-4 border-0 shadow-sm overflow-hidden rounded-2xl" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-left: 5px solid #f59e0b !important;">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-9">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-amber-500 text-white px-2.5 py-1 text-xs font-bold uppercase rounded-md mr-2" style="background-color: #f59e0b !important;">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> Perhatian
                                    </span>
                                    <h5 class="mb-0 font-weight-bold text-amber-950 text-sm sm:text-base">Biodata Pengajar Belum Lengkap!</h5>
                                </div>
                                <p class="text-amber-900 text-xs sm:text-sm mb-0">
                                    Silakan lengkapi informasi kualifikasi Anda (seperti gelar, pendidikan terakhir, nomor WA, spesialisasi, dan bio singkat) agar calon siswa dapat mengenal profil Anda secara detail.
                                </p>
                            </div>
                            <div class="col-md-3 text-md-right mt-3 mt-md-0">
                                <a href="{{ route('guru.biodata') }}" class="btn btn-warning font-weight-bold text-amber-950 px-4 py-2 rounded-xl shadow-sm border-0 text-xs sm:text-sm hover:scale-102 transition-all duration-200" style="background-color: #fbbf24;">
                                    <i class="fas fa-user-edit mr-1.5"></i> Lengkapi Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Welcome Banner -->
            <div class="card mb-4 overflow-hidden border-0 shadow-sm rounded-2xl" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 45%, #0f172a 100%);">
                <div class="card-body p-4 text-white relative">
                    <div class="row align-items-center">
                        <div class="col-md-9 position-relative d-flex align-items-center flex-column flex-sm-row" style="z-index: 2; gap: 20px;">
                            <!-- Profile Photo -->
                            <div class="flex-shrink-0">
                                @if($guruProfile->foto)
                                    <img src="{{ asset($guruProfile->foto) }}" class="rounded-circle shadow-sm" style="width: 84px; height: 84px; object-fit: cover; border: 3px solid rgba(255, 255, 255, 0.25) !important;" alt="Foto Profil">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ffffff&color=0f766e&size=84&bold=true" class="rounded-circle shadow-sm" style="width: 84px; height: 84px; object-fit: cover; border: 3px solid rgba(255, 255, 255, 0.25) !important;" alt="Avatar">
                                @endif
                            </div>
                            <div>
                                <span class="px-3 py-1 text-xxs font-extrabold uppercase tracking-wider rounded-full shadow-sm" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.25); display: inline-block;">
                                    <i class="fas fa-certificate text-amber-300 mr-1.5"></i> Status: Pengajar Aktif
                                </span>
                                <h2 class="mt-2 text-2xl sm:text-3xl font-bold tracking-tight mb-1">Halo, {{ Auth::user()->name }}!</h2>
                                <p class="text-teal-100 mb-0 max-w-xl text-xs sm:text-sm leading-relaxed">
                                    Ruang pengajar Anda sudah siap. Di sini Anda dapat memantau jadwal bimbingan belajar, mengunggah materi, dan berkoordinasi secara langsung dengan siswa bimbingan Anda.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 text-right d-none d-md-block position-relative" style="z-index: 2;">
                            <i class="fas fa-graduation-cap fa-7x text-white-50 opacity-20" style="color: rgba(255, 255, 255, 0.15);"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guru Metrics Grid -->
            <div class="row mb-2">
                <!-- Metric 1: Total Kelas -->
                <div class="col-lg-3 col-6 mb-3">
                    <div class="metric-card bg-white p-3 shadow-xs h-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="inner-metric">
                                <h3 class="font-weight-extrabold text-teal-950 mb-0">{{ $totalKelasMengajar }}</h3>
                                <p class="text-muted text-xs mb-1">Kelas Mengajar</p>
                            </div>
                            <div class="metric-icon bg-teal-50 text-teal-600"><i class="fas fa-chalkboard"></i></div>
                        </div>
                        <div class="pt-2 mt-2 border-top text-xxs text-teal-600 font-semibold">
                            <i class="fas fa-calendar-alt mr-1"></i> Bimbingan bulan ini
                        </div>
                    </div>
                </div>
                <!-- Metric 2: Jam Mengajar -->
                <div class="col-lg-3 col-6 mb-3">
                    <div class="metric-card bg-white p-3 shadow-xs h-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="inner-metric">
                                <h3 class="font-weight-extrabold text-teal-950 mb-0">{{ $totalJamMengajar }}<span class="text-xs font-weight-normal text-muted">Jam</span></h3>
                                <p class="text-muted text-xs mb-1">Akumulasi Durasi</p>
                            </div>
                            <div class="metric-icon bg-blue-50 text-blue-600"><i class="fas fa-clock"></i></div>
                        </div>
                        <div class="pt-2 mt-2 border-top text-xxs text-blue-600 font-semibold">
                            <i class="fas fa-chart-line mr-1"></i> Total jam mengajar
                        </div>
                    </div>
                </div>
                <!-- Metric 3: Siswa Bimbingan -->
                <div class="col-lg-3 col-6 mb-3">
                    <div class="metric-card bg-white p-3 shadow-xs h-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="inner-metric">
                                <h3 class="font-weight-extrabold text-teal-950 mb-0">{{ isset($assignedStudents) ? $assignedStudents->count() : 0 }} <span class="text-xs font-weight-normal text-muted">Orang</span></h3>
                                <p class="text-muted text-xs mb-1">Siswa Bimbingan</p>
                            </div>
                            <div class="metric-icon bg-purple-50 text-purple-600"><i class="fas fa-user-graduate"></i></div>
                        </div>
                        <div class="pt-2 mt-2 border-top text-xxs text-purple-600 font-semibold">
                            <i class="fas fa-check-double mr-1"></i> Siswa aktif Anda
                        </div>
                    </div>
                </div>
                <!-- Metric 4: Jam Mengajar -->
                <div class="col-lg-3 col-6 mb-3">
                    <div class="metric-card bg-white p-3 shadow-xs h-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="inner-metric">
                                <h3 class="font-weight-extrabold text-teal-950 mb-0" style="font-size: 1.6rem; line-height: 1.8rem; margin-top: 2px;">Aktif</h3>
                                <p class="text-muted text-xs mb-1">Status Tutor</p>
                            </div>
                            <div class="metric-icon bg-emerald-50 text-emerald-600"><i class="fas fa-user-check"></i></div>
                        </div>
                        <div class="pt-2 mt-2 border-top text-xxs text-emerald-600 font-semibold">
                            <i class="fas fa-shield-alt mr-1"></i> Terverifikasi Admin
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Section -->
            <div class="row">
                <!-- Single Column: Full Width Assigned Students -->
                <div class="col-12 mb-4">
                    <div class="card border-0 shadow-sm rounded-2xl">
                        <div class="card-header bg-white py-3 border-0 d-flex flex-column sm:flex-row align-items-start sm:align-items-center justify-content-between" style="gap: 12px;">
                            <div class="d-flex align-items-center">
                                <div class="p-2 rounded-xl bg-teal-50 text-teal-600 mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-users-cog"></i>
                                </div>
                                <div>
                                    <h3 class="card-title font-weight-bold text-teal-950 mb-0 text-lg">Siswa Bimbingan Ditugaskan</h3>
                                    <p class="text-xxs text-muted mb-0">Daftar seluruh siswa aktif yang berada di bawah bimbingan Anda.</p>
                                </div>
                            </div>
                            <!-- Search & Badge Container -->
                            <div class="d-flex align-items-center flex-wrap w-full sm:w-auto" style="gap: 10px;">
                                <div class="input-group input-group-sm" style="width: 220px; max-width: 100%;">
                                    <input type="text" id="siswaSearchInput" class="form-control rounded-xl border-light text-xs" placeholder="Cari nama atau sekolah..." style="border-radius: 10px 0 0 10px; font-family: 'Inter', sans-serif;">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-light border-light text-teal-600" style="border-radius: 0 10px 10px 0;"><i class="fas fa-search"></i></span>
                                    </div>
                                </div>
                                <span class="badge bg-teal-50 text-teal-700 border border-teal-200 px-3 py-2 rounded-xl text-xs font-bold shadow-xs">
                                    <i class="fas fa-user-graduate mr-1"></i> Total: {{ isset($assignedStudents) ? $assignedStudents->count() : 0 }} Siswa
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr class="bg-light/60 text-teal-950 text-xs font-bold uppercase tracking-wider">
                                            <th class="px-4 py-3 border-0">Siswa</th>
                                            <th class="px-4 py-3 border-0">Informasi Sekolah</th>
                                            <th class="px-4 py-3 border-0">Paket Terdaftar</th>
                                            <th class="px-4 py-3 border-0 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="siswaTableBody" class="text-slate-700 text-sm">
                                        @forelse($assignedStudents ?? [] as $s)
                                            <tr>
                                                <td class="px-4 py-3.5 align-middle">
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($s->name) }}&background=ccfbf1&color=0f766e&bold=true" class="rounded-full mr-3 border border-teal-100 shadow-xs" style="width: 40px; height: 40px;" alt="Avatar">
                                                        <div>
                                                            <div class="font-weight-bold text-teal-950 student-name-cell">{{ $s->name }}</div>
                                                            <span class="text-xs text-muted student-email-cell">{{ $s->email }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3.5 align-middle">
                                                    <div class="d-flex align-items-center mb-1 text-slate-700 font-semibold student-school-cell">
                                                        <i class="fas fa-school text-teal-500 mr-2 text-xs" style="width: 14px;"></i>
                                                        <span>{{ $s->sekolah ?? '-' }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center text-xs text-muted student-phone-cell">
                                                        <i class="fab fa-whatsapp text-emerald-500 mr-2 text-xs" style="width: 14px;"></i>
                                                        <span>{{ $s->whatsapp ?? '-' }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3.5 align-middle">
                                                    <span class="badge px-2.5 py-1.5 rounded-lg text-xs font-bold shadow-xs mb-1.5 d-inline-block" style="background-color: #f3e8ff; color: #6b21a8; border: 1px solid #e9d5ff;">
                                                        <i class="fas fa-graduation-cap mr-1"></i> {{ $s->paket ? $s->paket->nama_paket : 'Paket Belajar' }}
                                                    </span>
                                                    @php
                                                        $sBio = $s->biodata ?? [];
                                                        $mapelJadwal = $sBio['mapel_jadwal'] ?? [];
                                                        $sesiPerMapel = $sBio['sesi_per_mapel'] ?? [];
                                                        $tutorPerMapel = $sBio['tutor_per_mapel'] ?? [];
                                                        
                                                        if (empty($mapelJadwal) && $s->tipe_paket) {
                                                            if (preg_match('/Mapel:\s*([^)|]+)/i', $s->tipe_paket, $matches)) {
                                                                $mapelJadwal = array_map('trim', explode(',', $matches[1]));
                                                            }
                                                        }

                                                        $loggedGuruNameNorm = strtolower(trim(Auth::user()->name ?? ''));
                                                        $guruMapelDetails = [];

                                                        if (!empty($mapelJadwal)) {
                                                            foreach ($mapelJadwal as $idx => $mName) {
                                                                $mSesi = $sesiPerMapel[$idx] ?? ($sBio['jumlah_pertemuan'] ?? null);
                                                                $assignedGuru = $tutorPerMapel[$mName] ?? null;

                                                                if ($assignedGuru) {
                                                                    if (strtolower(trim($assignedGuru)) === $loggedGuruNameNorm) {
                                                                        $guruMapelDetails[] = [
                                                                            'name' => $mName,
                                                                            'sesi' => $mSesi,
                                                                        ];
                                                                    }
                                                                } else {
                                                                    $isMatch = false;
                                                                    if ($s->tipe_paket && preg_match('/Guru:\s*([^|)]+)/i', $s->tipe_paket, $m)) {
                                                                        $guruParts = array_map('trim', explode(',', $m[1]));
                                                                        foreach ($guruParts as $part) {
                                                                            if (str_contains(strtolower($part), $loggedGuruNameNorm)) {
                                                                                if (str_contains($part, ':')) {
                                                                                    $p = explode(':', $part);
                                                                                    if (strtolower(trim($p[0])) === strtolower(trim($mName))) {
                                                                                        $isMatch = true;
                                                                                    }
                                                                                } else {
                                                                                    $isMatch = true;
                                                                                }
                                                                            }
                                                                        }
                                                                    } else {
                                                                        $isMatch = true;
                                                                    }

                                                                    if ($isMatch) {
                                                                        $guruMapelDetails[] = [
                                                                            'name' => $mName,
                                                                            'sesi' => $mSesi,
                                                                        ];
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    @endphp

                                                    @if(!empty($guruMapelDetails))
                                                        <div class="space-y-1 mt-1">
                                                            @foreach($guruMapelDetails as $md)
                                                                <div class="text-[11px] font-bold text-purple-900 d-flex align-items-center gap-1.5">
                                                                    <span class="badge bg-purple-100 text-purple-800 text-[10px] px-1.5 py-0.5 rounded font-bold">
                                                                        {{ $md['name'] }}
                                                                    </span>
                                                                    @if($md['sesi'])
                                                                        <span><i class="far fa-clock mr-1 text-purple-400"></i>{{ $md['sesi'] }}x Pertemuan</span>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        @php
                                                            $sSesi = $sBio['jumlah_pertemuan'] ?? null;
                                                            if (!$sSesi && $s->tipe_paket) {
                                                                if (preg_match('/Sesi:\s*(\d+)x/i', $s->tipe_paket, $matches)) {
                                                                    $sSesi = $matches[1];
                                                                }
                                                            }
                                                        @endphp
                                                        @if($sSesi)
                                                            <div class="text-[11px] text-muted mt-1 font-semibold pl-1">
                                                                <i class="far fa-clock mr-1 text-purple-400"></i> {{ $sSesi }}x Pertemuan
                                                            </div>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3.5 align-middle text-center">
                                                    <div class="d-flex justify-content-center align-items-center" style="gap: 6px;">
                                                        @if($s->whatsapp)
                                                            @php
                                                                $waClean = preg_replace('/[^0-9]/', '', $s->whatsapp);
                                                                $waFormatted = str_starts_with($waClean, '0') ? '62' . substr($waClean, 1) : $waClean;
                                                            @endphp
                                                            <a href="https://wa.me/{{ $waFormatted }}?text=Halo%20{{ urlencode($s->name) }},%20saya%20tutor%20Anda%20dari%20Paradise%20of%20Math..." target="_blank" class="btn btn-sm btn-outline-emerald px-3 py-1.5 rounded-xl font-bold text-xs" style="border-radius: 10px;">
                                                                <i class="fab fa-whatsapp mr-1 text-sm align-middle"></i> <span class="align-middle">Hubungi</span>
                                                            </a>
                                                        @endif
                                                        <a href="{{ route('admin.siswa.detail', $s->id) }}" class="btn btn-sm btn-brand px-3 py-1.5 rounded-xl text-xs" style="border-radius: 10px;">
                                                            <i class="fas fa-user-info mr-1 align-middle"></i> <span class="align-middle">Profil</span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5 text-muted text-sm">
                                                    <i class="fas fa-users-slash fa-3x mb-3 d-block opacity-40 text-teal-300"></i>
                                                    Belum ada data siswa bimbingan yang ditugaskan oleh Admin untuk Anda saat ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Custom CSS styles for elegant metrics & table effects -->
    <style>
        .metric-card {
            border-radius: 20px;
            border: 1px solid #ece7f7;
            box-shadow: 0 1px 3px rgba(15, 118, 110, 0.02), 0 10px 24px -14px rgba(15, 118, 110, 0.12);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -8px rgba(15, 118, 110, 0.15);
        }
        .metric-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        .btn-outline-emerald {
            border: 1px solid #10b981;
            color: #10b981;
            background: #fff;
            transition: all 0.2s ease;
        }
        .btn-outline-emerald:hover {
            background-color: #ecfdf5;
            color: #047857;
            border-color: #059669;
        }
        .btn-brand {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border: none;
            color: #40206b;
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .btn-brand:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            color: #40206b;
        }
        .rounded-2xl {
            border-radius: 20px !important;
        }
        .rounded-xl {
            border-radius: 12px !important;
        }
        .shadow-xs {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
    </style>

    <!-- JS search filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('siswaSearchInput');
            const rows = document.querySelectorAll('#siswaTableBody tr');

            if (searchInput && rows.length > 0) {
                searchInput.addEventListener('input', function() {
                    const query = searchInput.value.toLowerCase().trim();
                    rows.forEach(row => {
                        const name = row.querySelector('.student-name-cell')?.textContent.toLowerCase() || '';
                        const email = row.querySelector('.student-email-cell')?.textContent.toLowerCase() || '';
                        const school = row.querySelector('.student-school-cell')?.textContent.toLowerCase() || '';
                        const phone = row.querySelector('.student-phone-cell')?.textContent.toLowerCase() || '';

                        if (name.includes(query) || email.includes(query) || school.includes(query) || phone.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
@endsection
