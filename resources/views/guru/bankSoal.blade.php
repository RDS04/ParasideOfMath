@extends('layout.app')

@section('title', 'Bank Soal · Paradise of Math')

@php
    $isGuru = auth()->check() && auth()->user()->isGuru();
    $prefixRoute = $isGuru ? 'guru.bank-soal' : 'admin.bank-soal';
    $dashRoute = $isGuru ? route('guru.dashboard') : route('admin.dashboard');
@endphp

@section('content')
    <!-- Content Header -->
    <div class="content-header py-3">
        <div class="container-fluid">
            <div class="row align-items-center g-2">
                <div class="col-12 col-sm-7 mb-2 mb-sm-0">
                    <h1 class="m-0 font-weight-bold text-purple-950 d-flex align-items-center text-xl sm:text-2xl">
                        <i class="fas fa-folder-open text-purple-600 mr-2.5"></i> Bank Soal &amp; Latihan
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mb-0 mt-1">
                        Kelola soal secara terstruktur: Jenjang → Kelas → Semester/TKA → Mata Pelajaran → Soal.
                    </p>
                </div>
                <div class="col-12 col-sm-5">
                    <ol class="breadcrumb float-sm-right text-xs sm:text-sm bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ $dashRoute }}"
                                class="text-purple-600 font-semibold">Dashboard</a></li>
                        <li class="breadcrumb-item active text-slate-500">Bank Soal</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content pb-5">
        <div class="container-fluid">

            <!-- Alert Flash Notification -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0 d-flex align-items-center"
                    role="alert" style="background-color: #ecfdf5; color: #065f46; border-left: 4px solid #10b981;">
                    <i class="fas fa-check-circle fa-lg mr-3 text-emerald-500 shrink-0"></i>
                    <div class="text-xs sm:text-sm">
                        <strong class="font-bold">Berhasil!</strong> {{ session('success') }}
                    </div>
                    <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" class="text-emerald-700">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0 text-xs sm:text-sm" role="alert"
                    style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444;">
                    <strong class="font-bold"><i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0 text-xs sm:text-sm" role="alert"
                    style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444;">
                    <strong class="font-bold"><i class="fas fa-exclamation-circle mr-2"></i> Terdapat kesalahan input:</strong>
                    <ul class="mb-0 mt-1 pl-4 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- ════════════════════════════════════════════════════════════ -->
            <!-- FILTER BANK SOAL: JENJANG, KELAS, SEMESTER, & MAPEL          -->
            <!-- ════════════════════════════════════════════════════════════ -->
            <!-- FILTER BANK SOAL -->
            <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden bank-filter-card">

                <!-- Filter Header -->
                <div class="card-header bg-white border-bottom px-3 px-md-4 py-3">
                    <div class="filter-header">

                        <!-- Judul -->
                        <div class="filter-header-title">
                            <div class="filter-icon">
                                <i class="fas fa-filter"></i>
                            </div>

                            <div class="filter-title-content">
                                <h5 class="mb-0 font-bold text-purple-950 text-sm sm:text-base">
                                    Filter Bank Soal &amp; Latihan
                                </h5>
                            </div>
                        </div>

                        <!-- Action Buttons (Pojok Kanan Atas Header) -->
                        <div class="d-flex align-items-center gap-1.5 sm:gap-2 shrink-0 ml-auto">
                            <!-- Button Filter (Ditampilkan di Mobile, di Sebelah Kiri Reset) -->
                            <button type="button"
                                id="btnToggleMobileFilter"
                                class="btn btn-sm btn-purple font-bold rounded-xl px-2.5 sm:px-3 py-1.5 text-xs shadow-xs d-md-none d-inline-flex align-items-center gap-1.5 shrink-0">
                                <i class="fas fa-sliders-h text-amber-300"></i>
                                <span>Filter</span>
                                @if($jenjang || $kelas || $sub || $mapel)
                                    <span class="badge bg-amber-400 text-purple-950 font-extrabold rounded-full px-1.5 py-0.5 text-[10px]">
                                        <i class="fas fa-check"></i>
                                    </span>
                                @endif
                            </button>

                            <!-- Clear / Reset Filter Button (Di Sebelah Kanan Button Filter) -->
                            @if($jenjang || $kelas || $sub || $mapel)
                                <a href="{{ route($prefixRoute . '.index') }}"
                                    class="btn btn-outline-danger btn-sm font-bold rounded-xl px-2.5 px-sm-3 reset-filter-btn d-inline-flex align-items-center gap-1 shrink-0"
                                    title="Reset Filter">
                                    <i class="fas fa-undo"></i>
                                    <span class="d-none d-sm-inline">Reset</span>
                                </a>
                            @endif
                        </div>

                    </div>
                </div>

                <!-- Filter Body (Khusus Tampilan Desktop) -->
                <div class="card-body p-3.5 p-md-4 bg-purple-50 d-none d-md-block">

                    <form id="filterBankSoalForm"
                        action="{{ route($prefixRoute . '.index') }}"
                        method="GET">

                        <div class="row">

                            <!-- 1. Jenjang -->
                            <div class="col-12 col-md-6 col-lg-3 mb-3 mb-lg-0">
                                <div class="filter-field">

                                    <label for="filterJenjang">
                                        <i class="fas fa-graduation-cap"></i>
                                        1. Jenjang Pendidikan
                                    </label>

                                    <select name="jenjang"
                                        id="filterJenjang"
                                        class="form-control custom-select filter-select"
                                        onchange="onJenjangChange(this)">

                                        <option value="">-- Pilih Jenjang --</option>

                                        <option value="SD"
                                            {{ $jenjang === 'SD' ? 'selected' : '' }}>
                                            SD (Sekolah Dasar)
                                        </option>

                                        <option value="SMP"
                                            {{ $jenjang === 'SMP' ? 'selected' : '' }}>
                                            SMP (Sekolah Menengah Pertama)
                                        </option>

                                        <option value="SMA"
                                            {{ $jenjang === 'SMA' ? 'selected' : '' }}>
                                            SMA (Sekolah Menengah Atas)
                                        </option>

                                    </select>
                                </div>
                            </div>


                            <!-- 2. Kelas -->
                            <div class="col-12 col-md-6 col-lg-3 mb-3 mb-lg-0">
                                <div class="filter-field">

                                    <label for="filterKelas">
                                        <i class="fas fa-users"></i>
                                        2. Kelas
                                    </label>

                                    <select name="kelas"
                                        id="filterKelas"
                                        class="form-control custom-select filter-select"
                                        {{ !$jenjang ? 'disabled' : '' }}
                                        onchange="onKelasChange(this)">

                                        <option value="">-- Pilih Kelas --</option>

                                        @if ($jenjang)
                                            @foreach ($availableClasses as $cls)
                                                <option value="{{ $cls }}"
                                                    {{ (string) $kelas === (string) $cls ? 'selected' : '' }}>
                                                    Kelas {{ $cls }}
                                                </option>
                                            @endforeach
                                        @endif

                                    </select>
                                </div>
                            </div>


                            <!-- 3. Semester / TKA -->
                            <div class="col-12 col-md-6 col-lg-3 mb-3 mb-lg-0">
                                <div class="filter-field">

                                    <label for="filterSub">
                                        <i class="fas fa-bookmark"></i>
                                        3. Semester / TKA
                                    </label>

                                    <select name="sub_kategori"
                                        id="filterSub"
                                        class="form-control custom-select filter-select"
                                        {{ !($jenjang && $kelas) ? 'disabled' : '' }}
                                        onchange="onSubChange(this)">

                                        <option value="">
                                            -- Pilih Semester / TKA --
                                        </option>

                                        @if ($jenjang && $kelas)
                                            @foreach ($availableSubs as $subItem)
                                                <option value="{{ $subItem }}"
                                                    {{ $sub === $subItem ? 'selected' : '' }}>
                                                    {{ $subItem }}
                                                </option>
                                            @endforeach
                                        @endif

                                    </select>
                                </div>
                            </div>


                            <!-- 4. Mata Pelajaran -->
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="filter-field">

                                    <label for="filterMapel">
                                        <i class="fas fa-book"></i>
                                        4. Mata Pelajaran
                                    </label>

                                    <select name="mapel"
                                        id="filterMapel"
                                        class="form-control custom-select filter-select"
                                        {{ !($jenjang && $kelas && $sub) ? 'disabled' : '' }}>

                                        <option value="">
                                            -- Pilih Mata Pelajaran --
                                        </option>

                                        @if ($jenjang && $kelas && $sub)
                                            @foreach ($mapelList as $m)
                                                <option value="{{ $m }}"
                                                    {{ $mapel === $m ? 'selected' : '' }}>
                                                    {{ $m }}
                                                </option>
                                            @endforeach
                                        @endif

                                    </select>
                                </div>
                            </div>

                        </div>

                        <!-- Submit Filter Button (Desktop) -->
                        <div class="mt-3.5 pt-3 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
                            @if($jenjang || $kelas || $sub || $mapel)
                                <div class="active-filter mt-0 pt-0 border-0">
                                    <span class="active-filter-label">Filter Aktif:</span>
                                    @if($jenjang)<span class="filter-badge"><i class="fas fa-graduation-cap"></i> {{ $jenjang }}</span>@endif
                                    @if($kelas)<span class="filter-badge"><i class="fas fa-users"></i> Kelas {{ $kelas }}</span>@endif
                                    @if($sub)<span class="filter-badge"><i class="fas fa-bookmark"></i> {{ $sub }}</span>@endif
                                    @if($mapel)<span class="filter-badge filter-badge-active"><i class="fas fa-book"></i> {{ $mapel }}</span>@endif
                                </div>
                            @else
                                <div></div>
                            @endif

                            <button type="submit" class="btn btn-purple font-bold rounded-xl px-4 py-2.5 text-xs sm:text-sm shadow-xs ml-auto d-inline-flex align-items-center gap-2">
                                <i class="fas fa-search text-amber-300"></i>
                                <span>Filter Paket Soal</span>
                            </button>
                        </div>
                    </form>

                </div>

                <!-- Active Filter Banner di Header Card (Tampilan Mobile) -->
                @if($jenjang || $kelas || $sub || $mapel)
                    <div class="card-body p-3 bg-purple-50 border-top d-md-none">
                        <div class="active-filter mt-0 pt-0 border-0">
                            <span class="active-filter-label">Filter Aktif:</span>
                            @if($jenjang)<span class="filter-badge"><i class="fas fa-graduation-cap"></i> {{ $jenjang }}</span>@endif
                            @if($kelas)<span class="filter-badge"><i class="fas fa-users"></i> Kelas {{ $kelas }}</span>@endif
                            @if($sub)<span class="filter-badge"><i class="fas fa-bookmark"></i> {{ $sub }}</span>@endif
                            @if($mapel)<span class="filter-badge filter-badge-active"><i class="fas fa-book"></i> {{ $mapel }}</span>@endif
                        </div>
                    </div>
                @endif

            </div>

            <!-- ════════════════════════════════════════════════════════════ -->
            <!-- MOBILE BOTTOM SHEET FILTER MODAL (SLIDE UP DARI NAVIGASI BAWAH) -->
            <!-- ════════════════════════════════════════════════════════════ -->
            <div id="mobileFilterBottomSheet" class="mobile-filter-backdrop d-md-none">
                <div class="mobile-filter-sheet">
                    <!-- Drag Handle -->
                    <div class="mobile-filter-drag-handle"></div>

                    <!-- Sheet Header -->
                    <div class="d-flex align-items-center justify-content-between px-4 pt-1 pb-3 border-bottom">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="rounded-circle p-2 bg-purple-100 text-purple-700 d-flex align-items-center justify-content-center shrink-0" style="width: 36px; height: 36px;">
                                <i class="fas fa-filter text-sm"></i>
                            </div>
                            <div>
                                <h6 class="font-bold text-slate-800 mb-0 text-sm">Filter Bank Soal</h6>
                                <span class="text-xs text-slate-500 font-medium">Pilih Kategori &amp; Mata Pelajaran</span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-light rounded-circle p-1.5 close-bottom-sheet-btn" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="fas fa-times text-slate-500"></i>
                        </button>
                    </div>

                    <!-- Sheet Body (Form Filter Mobile) -->
                    <div class="px-4 py-3" style="max-height: 60vh; overflow-y: auto;">
                        <form id="mobileFilterSheetForm" action="{{ route($prefixRoute . '.index') }}" method="GET">
                            
                            <!-- 1. Jenjang -->
                            <div class="mb-3">
                                <label class="font-extrabold text-[11px] uppercase text-purple-900 mb-1.5 d-flex align-items-center gap-1.5">
                                    <i class="fas fa-graduation-cap text-purple-600"></i> 1. Jenjang Pendidikan
                                </label>
                                <select name="jenjang" id="mobileFilterJenjang" class="form-control custom-select filter-select" onchange="onJenjangChange(this)">
                                    <option value="">-- Pilih Jenjang --</option>
                                    <option value="SD" {{ $jenjang === 'SD' ? 'selected' : '' }}>SD (Sekolah Dasar)</option>
                                    <option value="SMP" {{ $jenjang === 'SMP' ? 'selected' : '' }}>SMP (Sekolah Menengah Pertama)</option>
                                    <option value="SMA" {{ $jenjang === 'SMA' ? 'selected' : '' }}>SMA (Sekolah Menengah Atas)</option>
                                </select>
                            </div>

                            <!-- 2. Kelas -->
                            <div class="mb-3">
                                <label class="font-extrabold text-[11px] uppercase text-purple-900 mb-1.5 d-flex align-items-center gap-1.5">
                                    <i class="fas fa-users text-purple-600"></i> 2. Kelas
                                </label>
                                <select name="kelas" id="mobileFilterKelas" class="form-control custom-select filter-select" {{ !$jenjang ? 'disabled' : '' }} onchange="onKelasChange(this)">
                                    <option value="">-- Pilih Kelas --</option>
                                    @if ($jenjang)
                                        @foreach ($availableClasses as $cls)
                                            <option value="{{ $cls }}" {{ (string) $kelas === (string) $cls ? 'selected' : '' }}>Kelas {{ $cls }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- 3. Semester -->
                            <div class="mb-3">
                                <label class="font-extrabold text-[11px] uppercase text-purple-900 mb-1.5 d-flex align-items-center gap-1.5">
                                    <i class="fas fa-bookmark text-purple-600"></i> 3. Semester / TKA
                                </label>
                                <select name="sub_kategori" id="mobileFilterSub" class="form-control custom-select filter-select" {{ !($jenjang && $kelas) ? 'disabled' : '' }} onchange="onSubChange(this)">
                                    <option value="">-- Pilih Semester / TKA --</option>
                                    @if ($jenjang && $kelas)
                                        @foreach ($availableSubs as $subItem)
                                            <option value="{{ $subItem }}" {{ $sub === $subItem ? 'selected' : '' }}>{{ $subItem }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- 4. Mata Pelajaran -->
                            <div class="mb-3">
                                <label class="font-extrabold text-[11px] uppercase text-purple-900 mb-1.5 d-flex align-items-center gap-1.5">
                                    <i class="fas fa-book text-purple-600"></i> 4. Mata Pelajaran
                                </label>
                                <select name="mapel" id="mobileFilterMapel" class="form-control custom-select filter-select" {{ !($jenjang && $kelas && $sub) ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @if ($jenjang && $kelas && $sub)
                                        @foreach ($mapelList as $m)
                                            <option value="{{ $m }}" {{ $mapel === $m ? 'selected' : '' }}>{{ $m }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- Active Filter Badges -->
                            @if($jenjang || $kelas || $sub || $mapel)
                                <div class="active-filter mt-3 pt-3 border-top">
                                    <span class="active-filter-label">Filter Aktif:</span>
                                    @if($jenjang)<span class="filter-badge"><i class="fas fa-graduation-cap"></i> {{ $jenjang }}</span>@endif
                                    @if($kelas)<span class="filter-badge"><i class="fas fa-users"></i> Kelas {{ $kelas }}</span>@endif
                                    @if($sub)<span class="filter-badge"><i class="fas fa-bookmark"></i> {{ $sub }}</span>@endif
                                    @if($mapel)<span class="filter-badge filter-badge-active"><i class="fas fa-book"></i> {{ $mapel }}</span>@endif
                                </div>
                            @endif

                        </form>
                    </div>

                    <!-- Sheet Footer Actions -->
                    <div class="px-4 py-3 border-top bg-slate-50 d-flex align-items-center gap-2">
                        @if($jenjang || $kelas || $sub || $mapel)
                            <a href="{{ route($prefixRoute . '.index') }}" class="btn btn-outline-danger btn-sm font-bold rounded-xl px-3 py-2 text-xs flex-1 text-center">
                                <i class="fas fa-undo mr-1"></i> Reset Filter
                            </a>
                        @endif
                        <button type="submit" form="mobileFilterSheetForm" class="btn btn-purple btn-sm font-bold rounded-xl px-4 py-2 text-xs flex-1 text-center">
                            <i class="fas fa-search mr-1 text-amber-300"></i> Filter Paket Soal
                        </button>
                    </div>
                </div>
            </div>
            </div>
            <!-- ════════════════════════════════════════════════════════════ -->
            <!-- LANGKAH 5: DAFTAR JUDUL SOAL (DESKRIPSI KATEGORI)          -->
            <!-- ════════════════════════════════════════════════════════════ -->
            @if ($jenjang && $kelas && $sub && $mapel)
                <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                    <div
                        class="card-header bg-white py-3 px-3.5 sm:px-4 border-bottom d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-2.5">
                        <div class="d-flex align-items-center min-w-0">
                            <span
                                class="badge bg-purple-100 text-purple-700 font-extrabold rounded-circle mr-2.5 d-flex align-items-center justify-content-center shrink-0"
                                style="width: 28px; height: 28px; font-size: 12px; border: 1px solid #e9d5ff;">5</span>
                            <h5 class="card-title font-bold text-slate-800 mb-0 text-sm sm:text-base text-truncate">
                                Daftar Soal {{ $mapel }}
                                <span
                                    class="badge bg-slate-100 text-slate-700 border border-slate-200 font-medium ml-1.5 px-2 py-0.5 text-[11px] sm:text-xs">{{ $jenjang }}
                                    - K{{ $kelas }} - {{ $sub }}</span>
                            </h5>
                        </div>
                        <button type="button" class="btn btn-sm btn-purple font-semibold rounded-xl px-3.5 py-2 text-xs shadow-xs text-center w-100 w-sm-auto shrink-0"
                            data-toggle="modal" data-target="#modalTambahKategori">
                            <i class="fas fa-plus-circle mr-1"></i> Buat Paket Soal Baru
                        </button>
                    </div>
                    <div class="card-body p-3.5 sm:p-4">
                        @if ($kategoriList->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-folder-open text-slate-300 fa-3x mb-3"></i>
                                <p class="text-slate-500 font-semibold text-sm mb-1">Belum ada paket soal untuk {{ $mapel }}.</p>
                                <p class="text-xs text-slate-400 mb-3">Klik tombol di bawah ini untuk membuat paket/judul soal baru
                                    untuk mata pelajaran ini.</p>
                                <button type="button" class="btn btn-sm btn-purple font-semibold rounded-xl px-4 py-2 text-xs shadow-xs"
                                    data-toggle="modal" data-target="#modalTambahKategori">
                                    <i class="fas fa-plus-circle mr-1.5"></i> Buat Paket Soal {{ $mapel }} Baru
                                </button>
                            </div>
                        @else
                            <div class="d-flex flex-column gap-2.5">
                                @foreach ($kategoriList as $kat)
                                    @php
                                        $docCount = count(glob(public_path("uploads/bank_soal_docs/doc_{$kat->id}_*.*")) ?: []);
                                        $url = route($prefixRoute . '.kelola', $kat->id);
                                    @endphp
                                    <a href="{{ $url }}"
                                        class="p-3 sm:p-3.5 rounded-2xl text-decoration-none d-flex align-items-center justify-content-between gap-2.5 sm:gap-3 transition-all hover:shadow-md shadow-xs bg-white border border-slate-200 hover:border-purple-300"
                                        style="background: #ffffff !important; border: 1px solid #e2e8f0 !important;">
                                        <div class="d-flex align-items-center gap-2.5 sm:gap-3 min-w-0 flex-1">
                                            <div class="rounded-xl p-2 sm:p-2.5 d-flex align-items-center justify-center shrink-0"
                                                style="width: 38px; height: 38px; background: #f8fafc; border: 1px solid #e2e8f0;">
                                                <i class="fas fa-file-alt text-slate-600 text-sm sm:text-lg"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="d-flex align-items-center gap-1.5 flex-wrap mb-0.5">
                                                    <span class="badge font-bold px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md text-[9px] sm:text-xs uppercase d-inline-flex align-items-center gap-1"
                                                        style="background-color: #f5f3ff !important; color: #6d28d9 !important; border: 1px solid #ddd6fe !important;">
                                                        <i class="fas fa-book text-purple-600"></i> {{ $mapel }}
                                                    </span>
                                                </div>
                                                <h6 class="font-bold text-xs sm:text-base text-truncate mb-0.5 text-slate-800" title="{{ $kat->deskripsi ?: $kat->nama_kategori }}">
                                                    {{ $kat->deskripsi ?: $kat->nama_kategori }}
                                                </h6>
                                                <div class="d-flex align-items-center gap-1.5 flex-wrap text-slate-500 text-[10px] sm:text-xs font-medium mb-1">
                                                    <span><i class="fas fa-layer-group text-slate-400 mr-0.5"></i> Kelas {{ $kelas }} ({{ $sub }})</span>
                                                    <span class="d-none d-sm-inline">&bull;</span>
                                                    <span class="d-none d-sm-inline"><i class="far fa-clock text-slate-400 mr-0.5"></i> {{ $kat->created_at->diffForHumans() }}</span>
                                                </div>
                                                <div class="d-flex align-items-center gap-1 sm:gap-2 flex-wrap">
                                                    <span class="badge font-medium px-1.5 py-0.5 sm:px-2 sm:py-0.5 rounded-md text-[9px] sm:text-xs d-inline-flex align-items-center"
                                                        style="background-color: #fffbeb !important; color: #b45309 !important; border: 1px solid #fef3c7 !important;">
                                                        <i class="fas fa-list-ol mr-1 text-amber-500"></i> {{ $kat->bank_soals_count }} Soal
                                                    </span>
                                                    <span class="badge font-medium px-1.5 py-0.5 sm:px-2 sm:py-0.5 rounded-md text-[9px] sm:text-xs d-inline-flex align-items-center"
                                                        style="background-color: #f0f9ff !important; color: #0369a1 !important; border: 1px solid #bae6fd !important;">
                                                        <i class="fas fa-file-pdf mr-1 text-sky-500"></i> {{ $docCount }} PDF
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center shrink-0 ml-1">
                                            <span class="btn btn-sm font-semibold rounded-xl px-3.5 py-2 text-xs shadow-xs text-white d-none d-sm-inline-flex align-items-center gap-1"
                                                style="background: linear-gradient(135deg, #7c3aed, #6d28d9) !important; color: #ffffff !important; border: none !important;">
                                                <i class="fas fa-edit mr-1 text-purple-200"></i> Kelola Soal &amp; Modul <i class="fas fa-chevron-right ml-1 opacity-75 text-xxs"></i>
                                            </span>
                                            <span class="d-sm-none text-slate-400 p-1">
                                                <i class="fas fa-chevron-right text-xs"></i>
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden text-center py-5 px-4">
                    <div class="rounded-circle p-3.5 bg-purple-50 text-purple-600 d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                        <i class="fas fa-filter fa-2x"></i>
                    </div>
                    <h5 class="font-bold text-slate-800 mb-1.5 text-base sm:text-lg">Pilih Semua Menu Filter Terlebih Dahulu</h5>
                    <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mb-1">
                        Silakan pilih <strong>1. Jenjang Pendidikan, 2. Kelas, 3. Semester / TKA,</strong> dan <strong>4. Mata Pelajaran</strong> di atas, lalu klik tombol <strong class="text-purple-700">"Filter Paket Soal"</strong> untuk menampilkan daftar paket soal yang Anda cari.
                    </p>
                </div>
            @endif
        </div>
    </section>

    <!-- Modal Tambah Paket/Kategori Soal Baru -->
    @if ($jenjang && $kelas && $sub && $mapel)
        <div class="modal fade" id="modalTambahKategori" tabindex="-1" role="dialog" aria-labelledby="modalTambahKategoriLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow-lg rounded-2xl overflow-hidden">
                    <div class="modal-header bg-purple-900 text-white py-3 px-4">
                        <h5 class="modal-title font-bold text-base d-flex align-items-center" id="modalTambahKategoriLabel">
                            <i class="fas fa-folder-plus text-amber-300 mr-2"></i> Buat Paket Soal {{ $mapel }} Baru
                        </h5>
                        <button type="button" class="close text-white opacity-80 hover:opacity-100" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route($prefixRoute . '.kategori.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="jenjang" value="{{ $jenjang }}">
                        <input type="hidden" name="kelas" value="{{ $kelas }}">
                        <input type="hidden" name="sub_kategori" value="{{ $sub }}">
                        <input type="hidden" name="nama_kategori" value="{{ $mapel }}">
                        <div class="modal-body p-4 bg-slate-50/50">
                            <div class="alert alert-purple bg-purple-50 border border-purple-200 rounded-xl p-3 mb-3">
                                <div class="text-xs font-bold text-purple-950 d-flex align-items-center">
                                    <i class="fas fa-info-circle text-purple-600 mr-2 fa-lg"></i>
                                    <div>
                                        Mata Pelajaran: <strong>{{ $mapel }}</strong><br>
                                        Target: {{ $jenjang }} - Kelas {{ $kelas }} ({{ $sub }})
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-xs font-bold text-slate-700 uppercase">Judul / Deskripsi Paket
                                    Soal</label>
                                <input type="text" name="deskripsi" class="form-control rounded-xl font-semibold text-sm"
                                    placeholder="Contoh: Latihan Bab 1 - Aljabar / PTS Semester 1" required>
                                <span class="text-xs text-slate-400 mt-1 d-block">Tuliskan nama paket atau judul latihan yang
                                    akan ditampilkan.</span>
                            </div>
                        </div>
                        <div class="modal-footer bg-white py-2.5 px-4 border-top">
                            <button type="button" class="btn btn-sm btn-light font-bold rounded-xl px-3"
                                data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-purple font-bold rounded-xl px-4">
                                <i class="fas fa-plus-circle mr-1"></i> Buat Paket Soal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif



    <!-- Custom styling -->
    <style>
        .btn-purple {
            background: linear-gradient(135deg, #7c3aed, #6d28d9) !important;
            color: #ffffff !important;
            border: none !important;
        }

        .btn-purple:hover,
        .btn-purple:focus {
            background: linear-gradient(135deg, #6d28d9, #5b21b6) !important;
            color: #ffffff !important;
        }

        .btn-outline-purple {
            color: #581c87;
            border-color: #c084fc;
            background-color: #f3e8ff;
        }

        .btn-outline-purple:hover,
        .btn-outline-purple:focus {
            background-color: #581c87;
            color: #ffffff;
            border-color: #581c87;
        }

        label.btn-outline-purple input[type="radio"]:checked+span,
        label.btn-outline-purple:has(input[type="radio"]:checked) {
            background-color: #581c87 !important;
            color: #ffffff !important;
            border-color: #581c87 !important;
            box-shadow: 0 4px 6px -1px rgba(88, 28, 135, 0.4);
        }

        /* =========================================================
        FILTER BANK SOAL
        ========================================================= */

        .bank-filter-card {
            border-radius: 18px !important;
        }

        /* Header */

        .filter-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            width: 100%;
        }

        .filter-header-title {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .filter-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #f3e8ff;
            color: #7e22ce;

            box-shadow: 0 2px 6px rgba(88, 28, 135, 0.08);
        }

        .filter-title-content {
            min-width: 0;
        }

        .filter-title-content h5 {
            font-size: 15px;
            line-height: 1.3;
        }

        .filter-subtitle {
            display: block;
            margin-top: 3px;

            font-size: 12px;
            line-height: 1.4;

            color: #64748b;
        }

        .reset-filter-btn {
            flex: 0 0 auto !important;
            width: auto !important;

            white-space: nowrap;

            min-height: 36px;

            border-width: 1px;
        }


        /* Body */

        .bank-filter-card .card-body {
            background: #faf5ff !important;
        }


        /* Field */

        .filter-field {
            height: 100%;
        }

        .filter-field label {
            display: flex;
            align-items: center;

            margin-bottom: 7px;

            font-size: 11px;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: 0.04em;

            color: #3b0764;
        }

        .filter-field label i {
            width: 18px;
            margin-right: 5px;

            color: #9333ea;
        }


        /* Select */

        .filter-select {
            height: 43px !important;

            padding: 8px 12px !important;

            border: 1px solid #ddd6fe !important;
            border-radius: 10px !important;

            background-color: #ffffff !important;

            color: #334155 !important;

            font-size: 13px !important;
            font-weight: 600 !important;

            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);

            transition: all 0.2s ease;
        }

        .filter-select:hover {
            border-color: #c084fc !important;
        }

        .filter-select:focus {
            border-color: #9333ea !important;

            box-shadow:
                0 0 0 3px rgba(147, 51, 234, 0.10),
                0 2px 5px rgba(15, 23, 42, 0.05) !important;

            outline: none !important;
        }

        .filter-select:disabled {
            background-color: #f1f5f9 !important;

            color: #94a3b8 !important;

            cursor: not-allowed;

            opacity: 0.85;
        }


        /* Active filter */

        .active-filter {
            display: flex;
            align-items: center;
            flex-wrap: wrap;

            gap: 7px;

            margin-top: 18px;
            padding-top: 14px;

            border-top: 1px solid #ede9fe;
        }

        .active-filter-label {
            margin-right: 3px;

            font-size: 11px;
            font-weight: 700;

            color: #64748b;
        }

        .filter-badge {
            display: inline-flex;
            align-items: center;

            padding: 6px 9px;

            border-radius: 8px;

            background: #f3e8ff;
            border: 1px solid #ddd6fe;

            color: #581c87;

            font-size: 11px;
            font-weight: 700;
        }

        .filter-badge i {
            margin-right: 5px;

            color: #9333ea;
        }

        .filter-badge-active {
            background: #f5f3ff;
            border-color: #ddd6fe;

            color: #6d28d9;
        }

        .filter-badge-active i {
            color: #7c3aed;
        }


        /* =========================================================
        MOBILE
        ========================================================= */

        @media (max-width: 767.98px) {

            .filter-header {
                align-items: center;
                flex-direction: row;
                justify-content: space-between;
                gap: 8px;
            }

            .filter-header-title {
                flex: 1;
                min-width: 0;
            }

            .filter-title-content h5 {
                font-size: 13px;
            }

            .reset-filter-btn {
                width: auto !important;
            }

            .filter-select {
                height: 42px !important;
            }

            .active-filter {
                align-items: flex-start;
            }

        }

        /* =========================================================
        MOBILE BOTTOM SHEET FILTER MODAL (SLIDE FROM BOTTOM)
        ========================================================= */
        .mobile-filter-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 10050;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(4px);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .mobile-filter-backdrop.show-sheet {
            opacity: 1;
            pointer-events: auto;
        }

        .mobile-filter-sheet {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 10051;
            max-width: 520px;
            margin: 0 auto;
            background: #ffffff;
            border-top-left-radius: 24px;
            border-top-right-radius: 24px;
            box-shadow: 0 -10px 40px rgba(15, 23, 42, 0.22);
            transform: translateY(100%);
            transition: transform 0.32s cubic-bezier(0.16, 1, 0.3, 1);
            padding-bottom: calc(env(safe-area-inset-bottom, 12px) + 12px);
        }

        .mobile-filter-backdrop.show-sheet .mobile-filter-sheet {
            transform: translateY(0);
        }

        .mobile-filter-drag-handle {
            width: 36px;
            height: 4px;
            background: #cbd5e1;
            border-radius: 99px;
            margin: 10px auto 6px auto;
        }
    </style>

    <script>
        function onJenjangChange(selectEl) {
            const form = selectEl.form;
            const jenjangVal = selectEl.value ? selectEl.value.toUpperCase() : '';
            const kelasSelect = form.querySelector('select[name="kelas"]');
            const subSelect = form.querySelector('select[name="sub_kategori"]');
            const mapelSelect = form.querySelector('select[name="mapel"]');

            if (kelasSelect) {
                kelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';
                if (jenjangVal === 'SD') {
                    for (let i = 1; i <= 6; i++) {
                        kelasSelect.innerHTML += `<option value="${i}">Kelas ${i}</option>`;
                    }
                    kelasSelect.disabled = false;
                } else if (jenjangVal === 'SMP' || jenjangVal === 'SMA') {
                    for (let i = 1; i <= 3; i++) {
                        kelasSelect.innerHTML += `<option value="${i}">Kelas ${i}</option>`;
                    }
                    kelasSelect.disabled = false;
                } else {
                    kelasSelect.disabled = true;
                }
            }

            if (subSelect) {
                subSelect.innerHTML = '<option value="">-- Pilih Semester / TKA --</option>';
                subSelect.disabled = true;
            }

            if (mapelSelect) {
                mapelSelect.disabled = true;
            }
        }

        function onKelasChange(selectEl) {
            const form = selectEl.form;
            const jenjangVal = form.querySelector('select[name="jenjang"]')?.value ? form.querySelector('select[name="jenjang"]').value.toUpperCase() : '';
            const kelasVal = parseInt(selectEl.value);
            const subSelect = form.querySelector('select[name="sub_kategori"]');
            const mapelSelect = form.querySelector('select[name="mapel"]');

            if (subSelect) {
                subSelect.innerHTML = '<option value="">-- Pilih Semester / TKA --</option>';
                if (kelasVal) {
                    subSelect.innerHTML += '<option value="Semester 1">Semester 1</option>';
                    subSelect.innerHTML += '<option value="Semester 2">Semester 2</option>';

                    const isKelasAkhir = (jenjangVal === 'SD' && kelasVal === 6) ||
                                         (jenjangVal === 'SMP' && kelasVal === 3) ||
                                         (jenjangVal === 'SMA' && kelasVal === 3);
                    if (isKelasAkhir) {
                        subSelect.innerHTML += '<option value="TKA">TKA</option>';
                    }
                    subSelect.disabled = false;
                } else {
                    subSelect.disabled = true;
                }
            }

            if (mapelSelect) {
                mapelSelect.disabled = true;
            }
        }

        function onSubChange(selectEl) {
            const form = selectEl.form;
            const mapelSelect = form.querySelector('select[name="mapel"]');
            if (mapelSelect) {
                if (selectEl.value) {
                    mapelSelect.disabled = false;
                } else {
                    mapelSelect.disabled = true;
                }
            }
        }

        function updateLivePreview() {
            const nomorEl = document.getElementById('input_nomor');
            const soalEl = document.getElementById('input_soal');
            const opsiAEl = document.getElementById('input_opsi_a');
            const opsiBEl = document.getElementById('input_opsi_b');
            const opsiCEl = document.getElementById('input_opsi_c');
            const opsiDEl = document.getElementById('input_opsi_d');

            const nomor = nomorEl && nomorEl.value ? nomorEl.value : '1';
            const soal = soalEl && soalEl.value.trim() ? soalEl.value : 'Tuliskan teks pertanyaan di sebelah kiri untuk melihat pratinjau hasil tampilan soal di sini...';
            const opsiA = opsiAEl && opsiAEl.value.trim() ? opsiAEl.value : 'Opsi Jawaban A...';
            const opsiB = opsiBEl && opsiBEl.value.trim() ? opsiBEl.value : 'Opsi Jawaban B...';
            const opsiC = opsiCEl && opsiCEl.value.trim() ? opsiCEl.value : 'Opsi Jawaban C...';
            const opsiD = opsiDEl && opsiDEl.value.trim() ? opsiDEl.value : 'Opsi Jawaban D...';

            const kunciChecked = document.querySelector('#formInputSoalLive input[name="kunci_jawaban"]:checked');
            const kunci = kunciChecked ? kunciChecked.value : 'A';

            const previewNomor = document.getElementById('preview_nomor');
            const previewKunciBadge = document.getElementById('preview_kunci_badge');
            const previewSoalText = document.getElementById('preview_soal_text');

            if (previewNomor) previewNomor.innerText = 'No. ' + nomor;
            if (previewKunciBadge) previewKunciBadge.innerText = 'Kunci: ' + kunci;
            if (previewSoalText) previewSoalText.innerText = soal;

            const vals = { 'A': opsiA, 'B': opsiB, 'C': opsiC, 'D': opsiD };
            ['A', 'B', 'C', 'D'].forEach(key => {
                const kLower = key.toLowerCase();
                const textEl = document.getElementById('preview_opsi_' + kLower + '_text');
                const boxEl = document.getElementById('preview_opsi_' + kLower + '_box');
                const badgeEl = document.getElementById('preview_badge_' + kLower);
                const iconEl = document.getElementById('preview_icon_' + kLower);

                if (textEl) textEl.innerText = vals[key];

                if (boxEl) {
                    if (key === kunci) {
                        boxEl.className = "p-2.5 rounded-xl border text-xs font-semibold d-flex align-items-start bg-emerald-50 border-emerald-300 text-emerald-950 font-bold";
                        if (badgeEl) badgeEl.className = "badge bg-emerald-600 text-white mr-2 px-2 py-1 rounded-md text-xs font-bold";
                        if (iconEl) iconEl.classList.remove('d-none');
                    } else {
                        boxEl.className = "p-2.5 rounded-xl border text-xs font-semibold d-flex align-items-start bg-slate-50 border-slate-200 text-slate-700";
                        if (badgeEl) badgeEl.className = "badge bg-slate-200 text-slate-700 mr-2 px-2 py-1 rounded-md text-xs font-bold";
                        if (iconEl) iconEl.classList.add('d-none');
                    }
                }
            });
        }

        function openMobileFilterSheet() {
            const backdrop = document.getElementById('mobileFilterBottomSheet');
            if (backdrop) {
                backdrop.classList.add('show-sheet');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeMobileFilterSheet() {
            const backdrop = document.getElementById('mobileFilterBottomSheet');
            if (backdrop) {
                backdrop.classList.remove('show-sheet');
                document.body.style.overflow = '';
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            // Open Mobile Bottom Sheet on Filter button click
            const btnFilter = document.getElementById('btnToggleMobileFilter');
            if (btnFilter) {
                btnFilter.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openMobileFilterSheet();
                });
            }

            // Close Mobile Bottom Sheet on close button or backdrop click
            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('close-bottom-sheet-btn') || 
                    e.target.closest('.close-bottom-sheet-btn') ||
                    e.target.id === 'mobileFilterBottomSheet') {
                    closeMobileFilterSheet();
                }
            });

            updateLivePreview();
            document.addEventListener('change', function (e) {
                if (e.target && e.target.name === 'kunci_jawaban') {
                    const form = e.target.closest('form');
                    if (!form) return;
                    form.querySelectorAll('input[name="kunci_jawaban"]').forEach(r => {
                        const label = r.closest('label') || r.closest('.form-check')?.querySelector('label');
                        if (label) {
                            if (r.checked) {
                                label.classList.add('btn-purple', 'shadow-sm', 'text-white');
                                label.classList.remove('btn-outline-purple');
                            } else {
                                label.classList.remove('btn-purple', 'shadow-sm', 'text-white');
                                label.classList.add('btn-outline-purple');
                            }
                        }
                    });
                    updateLivePreview();
                }
            });
        });
    </script>
@endsection