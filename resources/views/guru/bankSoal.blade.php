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
                                <h5 class="mb-0 font-bold text-purple-950">
                                    Filter Bank Soal &amp; Latihan
                                </h5>

                                <span class="filter-subtitle">
                                    Pilih Jenjang, Kelas, Semester, dan Mata Pelajaran
                                </span>
                            </div>
                        </div>

                        <!-- Reset -->
                        @if($jenjang || $kelas || $sub || $mapel)
                            <a href="{{ route($prefixRoute . '.index') }}"
                                class="btn btn-outline-danger btn-sm font-bold rounded-xl px-3 reset-filter-btn">
                                <i class="fas fa-undo mr-1"></i>
                                Reset Filter
                            </a>
                        @endif

                    </div>
                </div>

                <!-- Filter Body -->
                <div class="card-body p-3 p-md-4 bg-purple-50">

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
                                        onchange="handleJenjangChange(this)">

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
                                        onchange="handleKelasChange(this)">

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
                                        onchange="handleSubChange(this)">

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
                                        {{ !($jenjang && $kelas && $sub) ? 'disabled' : '' }}
                                        onchange="this.form.submit()">

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
                    </form>


                    <!-- Active Filter -->
                    @if($jenjang || $kelas || $sub || $mapel)

                        <div class="active-filter">

                            <span class="active-filter-label">
                                Filter Aktif:
                            </span>

                            @if($jenjang)
                                <span class="filter-badge">
                                    <i class="fas fa-graduation-cap"></i>
                                    {{ $jenjang }}
                                </span>
                            @endif

                            @if($kelas)
                                <span class="filter-badge">
                                    <i class="fas fa-users"></i>
                                    Kelas {{ $kelas }}
                                </span>
                            @endif

                            @if($sub)
                                <span class="filter-badge">
                                    <i class="fas fa-bookmark"></i>
                                    {{ $sub }}
                                </span>
                            @endif

                            @if($mapel)
                                <span class="filter-badge filter-badge-active">
                                    <i class="fas fa-book"></i>
                                    {{ $mapel }}
                                </span>
                            @endif

                        </div>

                    @endif

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
                                class="badge bg-purple-900 text-white rounded-circle mr-2.5 d-flex align-items-center justify-content-center shrink-0"
                                style="width: 26px; height: 26px; font-size: 12px;">5</span>
                            <h5 class="card-title font-bold text-purple-950 mb-0 text-sm sm:text-base text-truncate">
                                Daftar Soal {{ $mapel }}
                                <span
                                    class="badge bg-purple-100 text-purple-900 font-bold ml-1.5 px-2 py-0.5 text-[11px] sm:text-xs">{{ $jenjang }}
                                    - K{{ $kelas }} - {{ $sub }}</span>
                            </h5>
                        </div>
                        <button type="button" class="btn btn-sm btn-purple font-bold rounded-xl px-3.5 py-2 text-xs shadow-xs text-center w-100 w-sm-auto shrink-0"
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
                                <button type="button" class="btn btn-sm btn-purple font-bold rounded-xl px-4 py-2 text-xs shadow-xs"
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
                                        class="p-3.5 sm:p-4 rounded-3xl text-decoration-none d-flex flex-column flex-sm-row items-stretch items-sm-center justify-content-between gap-3 transition-all hover:shadow-lg shadow-sm text-white"
                                        style="background: linear-gradient(135deg, #581c87, #3b0764) !important; border: 1px solid rgba(255,255,255,0.15) !important;">
                                        <div class="d-flex items-center gap-3 min-w-0 flex-1">
                                            <div class="rounded-2xl p-2.5 sm:p-3 d-flex items-center justify-center shrink-0 shadow-sm"
                                                style="width: 48px; height: 48px; background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.25);">
                                                <i class="fas fa-file-alt fa-xl text-amber-300"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="d-flex align-items-center gap-1.5 flex-wrap mb-1">
                                                    <span class="badge font-extrabold px-2.5 py-1 rounded-md text-[10px] sm:text-xs uppercase shadow-xs d-inline-flex align-items-center gap-1"
                                                        style="background-color: #facc15 !important; color: #3b0764 !important;">
                                                        <i class="fas fa-book"></i> {{ $mapel }}
                                                    </span>
                                                </div>
                                                <h6 class="font-bold text-base sm:text-lg text-truncate mb-1 text-white" title="{{ $kat->deskripsi ?: $kat->nama_kategori }}">
                                                    {{ $kat->deskripsi ?: $kat->nama_kategori }}
                                                </h6>
                                                <div class="d-flex align-items-center gap-2 flex-wrap text-purple-200 text-xs font-medium mb-2">
                                                    <span><i class="fas fa-layer-group text-amber-300 mr-1"></i> {{ $jenjang }} Kelas {{ $kelas }} ({{ $sub }})</span>
                                                    &bull;
                                                    <span><i class="far fa-clock text-amber-300 mr-1"></i> {{ $kat->created_at->diffForHumans() }}</span>
                                                </div>
                                                <div class="d-flex align-items-center gap-1.5 sm:gap-2 flex-wrap">
                                                    <span class="badge font-bold px-2.5 py-1 rounded-lg text-[11px] sm:text-xs d-inline-flex align-items-center"
                                                        style="background-color: rgba(255, 255, 255, 0.15) !important; color: #fde047 !important; border: 1px solid rgba(255, 255, 255, 0.25) !important;">
                                                        <i class="fas fa-list-ol mr-1"></i> {{ $kat->bank_soals_count }} Soal Manual
                                                    </span>
                                                    <span class="badge font-bold px-2.5 py-1 rounded-lg text-[11px] sm:text-xs d-inline-flex align-items-center"
                                                        style="background-color: rgba(255, 255, 255, 0.15) !important; color: #38bdf8 !important; border: 1px solid rgba(255, 255, 255, 0.25) !important;">
                                                        <i class="fas fa-file-pdf mr-1"></i> {{ $docCount }} Dokumen PDF
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex items-center justify-content-end gap-2 shrink-0">
                                            <span class="btn btn-sm font-bold rounded-xl px-3.5 py-2.5 text-xs shadow-sm text-purple-950 w-100 w-sm-auto text-center justify-content-center d-flex items-center gap-1"
                                                style="background: #ffffff !important; color: #3b0764 !important; border: none !important;">
                                                <i class="fas fa-edit mr-1 text-purple-700"></i> Kelola Soal &amp; Modul <i class="fas fa-chevron-right ml-1"></i>
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
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
            background-color: #581c87;
            color: #ffffff;
            border: none;
        }

        .btn-purple:hover,
        .btn-purple:focus {
            background-color: #3b0764;
            color: #ffffff;
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
            background: #581c87;
            border-color: #581c87;

            color: #ffffff;
        }

        .filter-badge-active i {
            color: #facc15;
        }


        /* =========================================================
        MOBILE
        ========================================================= */

        @media (max-width: 767.98px) {

            .filter-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
            }

            .filter-header-title {
                width: 100%;
            }

            .filter-title-content h5 {
                font-size: 14px;
            }

            .filter-subtitle {
                font-size: 11px;
            }

            .reset-filter-btn {
                width: 100% !important;
            }

            .filter-select {
                height: 42px !important;
            }

            .active-filter {
                align-items: flex-start;
            }

        }
    </style>

    <script>
        function handleJenjangChange(selectEl) {
            const form = selectEl.form;
            const kelasSelect = document.getElementById('filterKelas');
            const subSelect = document.getElementById('filterSub');
            const mapelSelect = document.getElementById('filterMapel');

            if (kelasSelect) kelasSelect.value = '';
            if (subSelect) subSelect.value = '';
            if (mapelSelect) mapelSelect.value = '';

            form.submit();
        }

        function handleKelasChange(selectEl) {
            const form = selectEl.form;
            const subSelect = document.getElementById('filterSub');
            const mapelSelect = document.getElementById('filterMapel');

            if (subSelect) subSelect.value = '';
            if (mapelSelect) mapelSelect.value = '';

            form.submit();
        }

        function handleSubChange(selectEl) {
            const form = selectEl.form;
            const mapelSelect = document.getElementById('filterMapel');

            if (mapelSelect) mapelSelect.value = '';

            form.submit();
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

        document.addEventListener("DOMContentLoaded", function () {
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