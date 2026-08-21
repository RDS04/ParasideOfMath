@extends('layout.app')

@section('title', 'List Soal & Modul · Paradise of Math')

@php
    $isGuru = auth()->check() && auth()->user()->isGuru();
    $prefixRoute = $isGuru ? 'guru.list-soal' : 'admin.bank-soal';
    $dashRoute = $isGuru ? route('guru.dashboard') : route('admin.dashboard');
@endphp

@section('content')
    <!-- Content Header -->
    <div class="content-header py-3">
        <div class="container-fluid">
            <div class="row align-items-center g-2">
                <div class="col-12 col-sm-7 mb-2 mb-sm-0">
                    <h1 class="m-0 font-weight-bold page-title d-flex align-items-center">
                        <i class="fas fa-list-alt page-title-icon mr-2"></i> List Soal &amp; Modul Terinput
                    </h1>
                    <p class="page-subtitle mb-0 mt-1">
                        Pilih jenjang, kelas, semester, dan mata pelajaran untuk melihat daftar soal serta dokumen modul PDF.
                    </p>
                </div>
                <div class="col-12 col-sm-5">
                    <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0 page-breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ $dashRoute }}" class="breadcrumb-link">Dashboard</a></li>
                        <li class="breadcrumb-item active">List Soal &amp; Modul</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content pb-5">
        <div class="container-fluid">

            <!-- ════════════════════════════════════════════════════════════ -->
            <!-- FILTER LIST SOAL: JENJANG, KELAS, SEMESTER, & MAPEL          -->
            <!-- ════════════════════════════════════════════════════════════ -->
            <div class="card border-0 shadow-sm mb-4 bg-white overflow-hidden bank-filter-card">

                <!-- Filter Header -->
                <div class="card-header bg-white border-bottom px-3 px-md-4 py-3">
                    <div class="filter-header">

                        <div class="filter-header-title">
                            <div class="filter-icon">
                                <i class="fas fa-filter"></i>
                            </div>
                            <div class="filter-title-content">
                                <h5 class="mb-0 font-bold filter-title">Filter Pencarian Soal &amp; Modul</h5>
                                <span class="filter-subtitle">Pilih Jenjang, Kelas, Semester, dan Mata Pelajaran</span>
                            </div>
                        </div>

                        @if($jenjang || $kelas || $sub || $mapel)
                            <a href="{{ route($prefixRoute . '.index') }}"
                                class="btn btn-outline-danger btn-sm font-bold rounded-xl px-3 reset-filter-btn">
                                <i class="fas fa-undo mr-1"></i> Reset Filter
                            </a>
                        @endif

                    </div>
                </div>

                <!-- Filter Body -->
                <div class="card-body p-3 p-md-4 bg-purple-50">
                    <form id="filterListSoalForm" action="{{ route($prefixRoute . '.index') }}" method="GET">
                        <div class="row">

                            <!-- 1. Jenjang -->
                            <div class="col-12 col-md-6 col-lg-3 mb-3 mb-lg-0">
                                <div class="filter-field">
                                    <label for="filterJenjang"><i class="fas fa-graduation-cap"></i> 1. Jenjang Pendidikan</label>
                                    <select name="jenjang" id="filterJenjang" class="form-control custom-select filter-select"
                                        onchange="handleJenjangChange(this)">
                                        <option value="">-- Pilih Jenjang --</option>
                                        <option value="SD" {{ $jenjang === 'SD' ? 'selected' : '' }}>SD (Sekolah Dasar)</option>
                                        <option value="SMP" {{ $jenjang === 'SMP' ? 'selected' : '' }}>SMP (Sekolah Menengah Pertama)</option>
                                        <option value="SMA" {{ $jenjang === 'SMA' ? 'selected' : '' }}>SMA (Sekolah Menengah Atas)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- 2. Kelas -->
                            <div class="col-12 col-md-6 col-lg-3 mb-3 mb-lg-0">
                                <div class="filter-field">
                                    <label for="filterKelas"><i class="fas fa-users"></i> 2. Kelas</label>
                                    <select name="kelas" id="filterKelas" class="form-control custom-select filter-select"
                                        {{ !$jenjang ? 'disabled' : '' }} onchange="handleKelasChange(this)">
                                        <option value="">-- Pilih Kelas --</option>
                                        @if ($jenjang)
                                            @foreach ($availableClasses as $cls)
                                                <option value="{{ $cls }}" {{ (string) $kelas === (string) $cls ? 'selected' : '' }}>
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
                                    <label for="filterSub"><i class="fas fa-bookmark"></i> 3. Semester / TKA</label>
                                    <select name="sub_kategori" id="filterSub" class="form-control custom-select filter-select"
                                        {{ !($jenjang && $kelas) ? 'disabled' : '' }} onchange="handleSubChange(this)">
                                        <option value="">-- Pilih Semester / TKA --</option>
                                        @if ($jenjang && $kelas)
                                            @foreach ($availableSubs as $subItem)
                                                <option value="{{ $subItem }}" {{ $sub === $subItem ? 'selected' : '' }}>
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
                                    <label for="filterMapel"><i class="fas fa-book"></i> 4. Mata Pelajaran</label>
                                    <select name="mapel" id="filterMapel" class="form-control custom-select filter-select"
                                        {{ !($jenjang && $kelas && $sub) ? 'disabled' : '' }} onchange="this.form.submit()">
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                        @if ($jenjang && $kelas && $sub)
                                            @foreach ($mapelList as $m)
                                                <option value="{{ $m }}" {{ $mapel === $m ? 'selected' : '' }}>
                                                    {{ $m }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                        </div>
                    </form>

                    <!-- Active Filter Summary -->
                    @if($jenjang || $kelas || $sub || $mapel)
                        <div class="active-filter">
                            <span class="active-filter-label">Filter Aktif:</span>

                            @if($jenjang)
                                <span class="filter-badge"><i class="fas fa-graduation-cap"></i> {{ $jenjang }}</span>
                            @endif

                            @if($kelas)
                                <span class="filter-badge"><i class="fas fa-users"></i> Kelas {{ $kelas }}</span>
                            @endif

                            @if($sub)
                                <span class="filter-badge"><i class="fas fa-bookmark"></i> {{ $sub }}</span>
                            @endif

                            @if($mapel)
                                <span class="filter-badge filter-badge-active"><i class="fas fa-book"></i> {{ $mapel }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- NOTICE JIKA FILTER BELUM LENGKAP -->
            @if (!($jenjang && $kelas && $sub && $mapel))
                <div class="card border-0 shadow-sm mb-4 bg-white overflow-hidden empty-notice-card">
                    <div class="card-body p-4 p-md-5 text-center">
                        <div class="empty-notice-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h5 class="font-bold empty-notice-title">Silakan Pilih Filter Mata Pelajaran</h5>
                        <p class="empty-notice-text">
                            Pilih <strong>Jenjang</strong>, <strong>Kelas</strong>, <strong>Semester/TKA</strong>, dan <strong>Mata Pelajaran</strong>
                            pada filter di atas untuk menampilkan daftar soal &amp; modul PDF.
                        </p>
                    </div>
                </div>
            @endif

            <!-- ════════════════════════════════════════════════════════════ -->
            <!-- TAB UTAMA: 1. LIHAT SOAL  |  2. LIHAT MODUL PDF             -->
            <!-- ════════════════════════════════════════════════════════════ -->
            @if ($jenjang && $kelas && $sub && $mapel)
                @php
                    $allDocFilesCount = 0;
                    foreach ($kategoriList as $kCheck) {
                        $allDocFilesCount += count(glob(public_path("uploads/bank_soal_docs/doc_{$kCheck->id}_*.*")) ?: []);
                    }
                @endphp

                <div class="card border-0 shadow-sm mb-4 bg-white overflow-hidden">
                    <div class="card-header p-2 bg-slate-100 border-bottom">
                        <ul class="nav nav-pills nav-justified w-100 tab-nav-list" id="tabListSoalModul" role="tablist">
                            <li class="nav-item flex-1" role="presentation">
                                <a class="nav-link active tab-nav-link tab-nav-soal" id="tab-soal-manual" data-toggle="pill"
                                    href="#content-soal-manual" role="tab" aria-controls="content-soal-manual" aria-selected="true">
                                    <i class="fas fa-list-ol"></i>
                                    <span>Soal ({{ $mapel }})</span>
                                </a>
                            </li>
                            <li class="nav-item flex-1" role="presentation">
                                <a class="nav-link tab-nav-link tab-nav-modul" id="tab-modul-pdf" data-toggle="pill"
                                    href="#content-modul-pdf" role="tab" aria-controls="content-modul-pdf" aria-selected="false">
                                    <i class="fas fa-file-pdf"></i>
                                    <span>Modul PDF ({{ $allDocFilesCount }})</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-3 p-md-4 bg-slate-50">
                        <div class="tab-content" id="tabListSoalModulContent">

                            <!-- ════════════════════════════════════════════════════════════ -->
                            <!-- MENU 1: LIHAT SOAL                                           -->
                            <!-- ════════════════════════════════════════════════════════════ -->
                            <div class="tab-pane fade show active" id="content-soal-manual" role="tabpanel" aria-labelledby="tab-soal-manual">
                                <div class="section-toolbar mb-4">
                                    <div>
                                        <h5 class="font-bold section-toolbar-title">
                                            <i class="fas fa-book-open"></i> Daftar Paket &amp; Judul Soal: {{ $mapel }}
                                        </h5>
                                        <span class="section-toolbar-subtitle">
                                            Klik pada salah satu Judul Soal di bawah untuk melihat detail pertanyaan latihan.
                                        </span>
                                    </div>
                                    <div class="section-toolbar-actions">
                                        <input type="text" id="searchSoalInput" class="form-control form-control-sm toolbar-search"
                                            placeholder="Cari judul / soal..." onkeyup="filterSoalList()">
                                        <button class="btn btn-sm btn-outline-purple font-bold toolbar-print-btn" onclick="window.print()">
                                            <i class="fas fa-print mr-1"></i> Cetak
                                        </button>
                                    </div>
                                </div>

                                @if ($kategoriList->isEmpty())
                                    <div class="text-center py-5 bg-white empty-state-box">
                                        <i class="fas fa-folder-open empty-state-icon"></i>
                                        <h6 class="font-bold empty-state-title">Belum ada paket/judul soal terinput untuk {{ $mapel }}.</h6>
                                        <p class="empty-state-text">Buka menu <strong>Input Soal</strong> untuk menambahkan judul &amp; soal latihan baru.</p>
                                    </div>
                                @else
                                    <div class="d-flex flex-column soal-card-list" id="paketSoalList">
                                        @foreach ($kategoriList as $kIndex => $kat)
                                            @php
                                                $soalCount = $kat->bankSoals ? $kat->bankSoals->count() : $kat->bank_soals_count;
                                                $detailUrl = route('guru.list-soal.detail', $kat->id);
                                                $docCountList = count(glob(public_path("uploads/bank_soal_docs/doc_{$kat->id}_*.*")) ?: []);
                                            @endphp
                                            <a href="{{ $detailUrl }}" class="soal-card soal-card-item">
                                                <div class="soal-card-inner">
                                                    <div class="soal-card-left">
                                                        <div class="soal-card-icon">
                                                            <i class="fas fa-file-alt"></i>
                                                        </div>
                                                        <div class="soal-card-content">
                                                            <span class="soal-card-badge-mapel">
                                                                <i class="fas fa-book"></i> {{ $mapel }}
                                                            </span>
                                                            <h6 class="soal-card-heading" title="{{ $kat->deskripsi ?: $kat->nama_kategori }}">
                                                                {{ $kat->deskripsi ?: $kat->nama_kategori }}
                                                            </h6>
                                                            <div class="soal-card-meta">
                                                                <span><i class="fas fa-layer-group"></i> {{ $jenjang }} Kelas {{ $kelas }} ({{ $sub }})</span>
                                                                <span class="soal-card-meta-dot">&bull;</span>
                                                                <span><i class="far fa-clock"></i> {{ $kat->created_at->diffForHumans() }}</span>
                                                            </div>
                                                            <div class="soal-card-badges">
                                                                <span class="soal-card-badge soal-card-badge-manual">
                                                                    <i class="fas fa-list-ol"></i> {{ $soalCount }} Soal Manual
                                                                </span>
                                                                <span class="soal-card-badge soal-card-badge-pdf">
                                                                    <i class="fas fa-file-pdf"></i> {{ $docCountList }} Dokumen PDF
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="soal-card-right">
                                                        <span class="soal-card-btn">
                                                            <i class="fas fa-external-link-alt"></i> Buka Soal &amp; Modul <i class="fas fa-chevron-right"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- ════════════════════════════════════════════════════════════ -->
                            <!-- MENU 2: LIHAT MODUL DOKUMEN / PDF                            -->
                            <!-- ════════════════════════════════════════════════════════════ -->
                            <div class="tab-pane fade" id="content-modul-pdf" role="tabpanel" aria-labelledby="tab-modul-pdf">
                                <div class="section-toolbar mb-4">
                                    <div>
                                        <h5 class="font-bold section-toolbar-title">
                                            <i class="fas fa-file-pdf"></i> Daftar Dokumen Modul PDF: {{ $mapel }}
                                        </h5>
                                        <span class="section-toolbar-subtitle">File dokumen modul pembelajaran yang diunggah untuk dibaca di web</span>
                                    </div>
                                </div>

                                @php $hasAnyDocFiles = false; @endphp

                                <div class="d-flex flex-column doc-group-list">
                                    @foreach ($kategoriList as $kMod)
                                        @php
                                            $gModDocFiles = glob(public_path("uploads/bank_soal_docs/doc_{$kMod->id}_*.*")) ?: [];
                                        @endphp
                                        @if (count($gModDocFiles) > 0)
                                            @php $hasAnyDocFiles = true; @endphp
                                            <div class="doc-group-card">
                                                <div class="doc-group-header">
                                                    <span class="doc-group-title">
                                                        <i class="fas fa-folder"></i> {{ $kMod->deskripsi ?: $kMod->nama_kategori }}
                                                    </span>
                                                    <span class="doc-group-count">{{ count($gModDocFiles) }} File PDF</span>
                                                </div>
                                                <div class="doc-group-body">
                                                    <div class="d-flex flex-column doc-file-list">
                                                        @foreach($gModDocFiles as $gDocIdx => $gDocPath)
                                                            @php
                                                                $gDocFileName = basename($gDocPath);
                                                                $gDocExt = strtolower(pathinfo($gDocFileName, PATHINFO_EXTENSION));
                                                                $gDocDisplay = preg_replace('/^doc_\d+_\d+_/', '', $gDocFileName);
                                                                $gDocUrl = asset("uploads/bank_soal_docs/{$gDocFileName}");
                                                                $isPdf = $gDocExt === 'pdf';
                                                            @endphp
                                                            <div class="doc-file-item">
                                                                <div class="doc-file-left">
                                                                    <div class="doc-file-icon {{ $isPdf ? 'doc-file-icon-pdf' : 'doc-file-icon-word' }}">
                                                                        <i class="fas {{ $isPdf ? 'fa-file-pdf' : 'fa-file-word' }}"></i>
                                                                    </div>
                                                                    <div class="doc-file-info">
                                                                        <div class="doc-file-name-row">
                                                                            <h6 class="doc-file-name" title="{{ $gDocDisplay }}">{{ $gDocDisplay }}</h6>
                                                                            <span class="doc-file-ext {{ $isPdf ? 'doc-file-ext-pdf' : 'doc-file-ext-word' }}">
                                                                                {{ strtoupper($gDocExt) }}
                                                                            </span>
                                                                        </div>
                                                                        <span class="doc-file-sub">File Dokumen Modul Pembelajaran</span>
                                                                    </div>
                                                                </div>
                                                                <div class="doc-file-right">
                                                                    @if($isPdf)
                                                                        <button type="button" class="doc-file-read-btn" data-toggle="modal"
                                                                            data-target="#modalListPreviewDoc_{{ $kMod->id }}_{{ $gDocIdx }}">
                                                                            <i class="fas fa-book-reader"></i> Baca Dokumen PDF
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <!-- MODAL PREVIEW PDF -->
                                                            @if($isPdf)
                                                                <div class="modal fade p-0" id="modalListPreviewDoc_{{ $kMod->id }}_{{ $gDocIdx }}"
                                                                    tabindex="-1" role="dialog" aria-hidden="true" style="padding-right: 0 !important;">
                                                                    <div class="modal-dialog m-0 pdf-modal-dialog" role="document">
                                                                        <div class="modal-content border-0 rounded-0 shadow-none h-100 pdf-modal-content">
                                                                            <div class="pdf-modal-header">
                                                                                <div class="pdf-modal-header-left">
                                                                                    <i class="fas fa-file-pdf"></i>
                                                                                    <div class="min-w-0">
                                                                                        <h5 class="pdf-modal-title" title="{{ $gDocDisplay }}">{{ $gDocDisplay }}</h5>
                                                                                        <span class="pdf-modal-protection">
                                                                                            <i class="fas fa-shield-alt mr-1"></i>Mode Baca Saja (Proteksi Unduh Aktif)
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                                <button type="button" class="pdf-modal-close-btn" data-dismiss="modal" aria-label="Close">
                                                                                    <i class="fas fa-times mr-1"></i> Tutup Reader
                                                                                </button>
                                                                            </div>
                                                                            <div class="pdf-modal-body" oncontextmenu="return false;">
                                                                                <iframe src="{{ $gDocUrl }}#toolbar=0&navpanes=0&scrollbar=1&view=FitH"
                                                                                    class="w-100 h-100 border-0" oncontextmenu="return false;"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach

                                    @if (!$hasAnyDocFiles)
                                        <div class="text-center py-5 bg-white empty-state-box">
                                            <i class="fas fa-file-pdf empty-state-icon"></i>
                                            <h6 class="font-bold empty-state-title">Belum ada dokumen modul PDF terunggah untuk {{ $mapel }}.</h6>
                                            <p class="empty-state-text">Buka menu <strong>Input Soal &gt; Upload PDF / Word</strong> untuk menambahkan dokumen modul.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>

    <!-- MathJax for rendering Mathematical equations -->
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- CUSTOM STYLING (menggantikan class Tailwind yang tidak jalan) -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <style>
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

        /* ============== PAGE HEADER ============== */
        .page-title {
            color: #3b0764;
            font-size: 20px;
        }
        .page-title-icon { color: #9333ea; }
        .page-subtitle { font-size: 13px; color: #64748b; }
        .page-breadcrumb { font-size: 13px; }
        .breadcrumb-link { color: #9333ea; font-weight: 600; }
        .page-breadcrumb .breadcrumb-item.active { color: #64748b; }

        /* ============== FILTER (sama seperti bankSoal.blade.php) ============== */
        .bank-filter-card { border-radius: 18px !important; }

        .filter-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            width: 100%;
        }
        .filter-header-title { display: flex; align-items: center; gap: 12px; min-width: 0; }
        .filter-icon {
            width: 40px; height: 40px; min-width: 40px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%;
            background: #f3e8ff; color: #7e22ce;
            box-shadow: 0 2px 6px rgba(88, 28, 135, 0.08);
        }
        .filter-title-content { min-width: 0; }
        .filter-title { font-size: 15px; line-height: 1.3; color: #3b0764; }
        .filter-subtitle { display: block; margin-top: 3px; font-size: 12px; line-height: 1.4; color: #64748b; }

        .reset-filter-btn {
            flex: 0 0 auto !important;
            width: auto !important;
            white-space: nowrap;
            min-height: 36px;
            border-width: 1px;
        }

        .bank-filter-card .card-body { background: #faf5ff !important; }

        .filter-field { height: 100%; }
        .filter-field label {
            display: flex; align-items: center;
            margin-bottom: 7px;
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.04em;
            color: #3b0764;
        }
        .filter-field label i { width: 18px; margin-right: 5px; color: #9333ea; }

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
        .filter-select:hover { border-color: #c084fc !important; }
        .filter-select:focus {
            border-color: #9333ea !important;
            box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.10), 0 2px 5px rgba(15, 23, 42, 0.05) !important;
            outline: none !important;
        }
        .filter-select:disabled { background-color: #f1f5f9 !important; color: #94a3b8 !important; cursor: not-allowed; opacity: 0.85; }

        .active-filter {
            display: flex; align-items: center; flex-wrap: wrap;
            gap: 7px;
            margin-top: 18px; padding-top: 14px;
            border-top: 1px solid #ede9fe;
        }
        .active-filter-label { margin-right: 3px; font-size: 11px; font-weight: 700; color: #64748b; }
        .filter-badge {
            display: inline-flex; align-items: center;
            padding: 6px 9px;
            border-radius: 8px;
            background: #f3e8ff; border: 1px solid #ddd6fe;
            color: #581c87;
            font-size: 11px; font-weight: 700;
        }
        .filter-badge i { margin-right: 5px; color: #9333ea; }
        .filter-badge-active { background: #581c87; border-color: #581c87; color: #ffffff; }
        .filter-badge-active i { color: #facc15; }

        /* ============== EMPTY NOTICE (belum pilih filter) ============== */
        .empty-notice-card { border-radius: 18px !important; }
        .empty-notice-icon {
            width: 56px; height: 56px;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 50%;
            background: #faf5ff; color: #9333ea;
            font-size: 22px;
            margin-bottom: 14px;
            box-shadow: 0 2px 6px rgba(88, 28, 135, 0.08);
        }
        .empty-notice-title { color: #3b0764; font-size: 16px; margin-bottom: 8px; }
        .empty-notice-text { color: #64748b; font-size: 13px; max-width: 480px; margin: 0 auto; }

        /* ============== TABS ============== */
        .tab-nav-list { gap: 8px; }
        .tab-nav-link {
            font-weight: 700;
            font-size: 13px;
            padding: 10px 8px;
            border-radius: 10px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
            transition: all 0.2s ease;
            text-align: center;
            display: flex; align-items: center; justify-content: center; gap: 6px;
        }
        .tab-nav-soal { border: 2px solid #6b21a8; }
        .tab-nav-soal i { color: #f59e0b; }
        .tab-nav-modul { border: 2px solid #0284c7; }
        .tab-nav-modul i { color: #f43f5e; }

        /* ============== SECTION TOOLBAR (header di dalam tab) ============== */
        .section-toolbar {
            display: flex;
            flex-direction: column;
            gap: 12px;
            justify-content: space-between;
            align-items: stretch;
            padding: 14px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        }
        .section-toolbar-title {
            color: #3b0764;
            font-size: 15px;
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 2px;
        }
        .section-toolbar-title i { color: #9333ea; }
        .section-toolbar-subtitle { font-size: 12px; color: #64748b; }
        .section-toolbar-actions { display: flex; gap: 8px; }
        .toolbar-search { border-radius: 10px; font-size: 12px; flex: 1; }
        .toolbar-print-btn { border-radius: 10px; font-size: 12px; padding: 6px 12px; flex-shrink: 0; }

        @media (min-width: 576px) {
            .section-toolbar { flex-direction: row; align-items: center; }
            .section-toolbar-actions { width: auto; }
        }

        /* ============== EMPTY STATE (dalam tab) ============== */
        .empty-state-box {
            border: 2px dashed #e2e8f0;
            border-radius: 16px;
            padding: 20px;
        }
        .empty-state-icon { color: #cbd5e1; font-size: 42px; margin-bottom: 12px; display: block; }
        .empty-state-title { color: #334155; font-size: 14px; margin-bottom: 4px; }
        .empty-state-text { color: #94a3b8; font-size: 12px; margin-bottom: 12px; }

        /* ============== KARTU JUDUL SOAL (gradient ungu) ============== */
        .soal-card-list { gap: 10px; }
        .soal-card {
            display: block;
            border-radius: 20px;
            overflow: hidden;
            text-decoration: none;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }
        .soal-card:hover {
            box-shadow: 0 8px 20px rgba(88, 28, 135, 0.25);
            transform: translateY(-1px);
        }
        .soal-card-inner {
            display: flex;
            flex-direction: column;
            gap: 14px;
            justify-content: space-between;
            align-items: stretch;
            padding: 16px;
            color: #ffffff;
            background: linear-gradient(135deg, #581c87, #3b0764);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        .soal-card-left { display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1; }
        .soal-card-icon {
            width: 48px; height: 48px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.25);
            font-size: 20px;
            color: #fcd34d;
            flex-shrink: 0;
        }
        .soal-card-content { min-width: 0; flex: 1; }
        .soal-card-badge-mapel {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px;
            border-radius: 8px;
            background-color: #facc15;
            color: #3b0764;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .soal-card-heading {
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .soal-card-meta {
            display: flex; align-items: center; flex-wrap: wrap; gap: 8px;
            color: #d8b4fe;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 10px;
        }
        .soal-card-meta i { color: #fcd34d; margin-right: 4px; }
        .soal-card-meta-dot { color: #a855f7; }
        .soal-card-badges { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }
        .soal-card-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            background-color: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
        .soal-card-badge-manual { color: #fde047; }
        .soal-card-badge-pdf { color: #38bdf8; }
        .soal-card-right { display: flex; align-items: center; justify-content: flex-end; flex-shrink: 0; }
        .soal-card-btn {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            width: 100%;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            background: #ffffff;
            color: #3b0764;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.1);
        }
        .soal-card-btn i:first-child { color: #7e22ce; }

        @media (min-width: 576px) {
            .soal-card-inner { flex-direction: row; align-items: center; }
            .soal-card-btn { width: auto; }
        }

        /* ============== DAFTAR DOKUMEN PDF ============== */
        .doc-group-list { gap: 12px; }
        .doc-group-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        }
        .doc-group-header {
            display: flex; align-items: center; justify-content: space-between;
            background: #f1f5f9;
            padding: 10px 14px;
            border-bottom: 1px solid #e2e8f0;
        }
        .doc-group-title { font-size: 12px; font-weight: 700; color: #3b0764; }
        .doc-group-title i { color: #9333ea; margin-right: 6px; }
        .doc-group-count {
            background: #581c87; color: #ffffff;
            font-weight: 700; font-size: 11px;
            padding: 4px 10px; border-radius: 6px;
        }
        .doc-group-body { padding: 12px; background: #f8fafc; }
        .doc-file-list { gap: 8px; }

        .doc-file-item {
            display: flex;
            flex-direction: column;
            gap: 10px;
            justify-content: space-between;
            align-items: stretch;
            padding: 14px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            transition: border-color 0.2s ease;
        }
        .doc-file-item:hover { border-color: #c084fc; }
        .doc-file-left { display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1; }
        .doc-file-icon {
            width: 42px; height: 42px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 12px;
            font-size: 18px;
            flex-shrink: 0;
        }
        .doc-file-icon-pdf { background: #ffe4e6; color: #e11d48; }
        .doc-file-icon-word { background: #dbeafe; color: #2563eb; }
        .doc-file-info { min-width: 0; flex: 1; }
        .doc-file-name-row { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-bottom: 2px; }
        .doc-file-name {
            font-size: 13px; font-weight: 700; color: #3b0764;
            margin-bottom: 0;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .doc-file-ext {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            padding: 2px 6px; border-radius: 4px; border: 1px solid;
        }
        .doc-file-ext-pdf { background: #fff1f2; color: #be123c; border-color: #fecdd3; }
        .doc-file-ext-word { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
        .doc-file-sub { font-size: 11px; color: #94a3b8; display: block; }
        .doc-file-right { display: flex; align-items: center; flex-shrink: 0; }
        .doc-file-read-btn {
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 12px; font-weight: 700;
            color: #ffffff; border: none;
            background: linear-gradient(135deg, #6b21a8, #4c1d95);
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
        }
        .doc-file-read-btn i { color: #fcd34d; }

        @media (min-width: 576px) {
            .doc-file-item { flex-direction: row; align-items: center; }
            .doc-file-read-btn { width: auto; }
        }

        /* ============== MODAL PREVIEW PDF ============== */
        .pdf-modal-dialog { max-width: 100vw; width: 100vw; height: 100vh; margin: 0; }
        .pdf-modal-content { background: #0f172a; }
        .pdf-modal-header {
            display: flex; flex-direction: row; align-items: center; justify-content: space-between; gap: 10px;
            background: #3b0764;
            color: #ffffff;
            padding: 10px 14px;
            border-bottom: 1px solid #581c87;
        }
        .pdf-modal-header-left { display: flex; align-items: center; gap: 10px; min-width: 0; }
        .pdf-modal-header-left > i { color: #fb7185; font-size: 18px; flex-shrink: 0; }
        .pdf-modal-title {
            font-size: 13px; font-weight: 700; color: #ffffff; margin-bottom: 0;
            max-width: 60vw;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .pdf-modal-protection { font-size: 10px; color: #fcd34d; display: block; font-weight: 500; }
        .pdf-modal-close-btn {
            flex-shrink: 0;
            background: #dc2626; color: #ffffff; border: none;
            font-weight: 700; font-size: 12px;
            border-radius: 8px; padding: 6px 12px;
        }
        .pdf-modal-body { padding: 0; background: #0f172a; overflow: hidden; height: calc(100vh - 58px); user-select: none; }

        /* ============== MOBILE ============== */
        @media (max-width: 767.98px) {
            .filter-header { align-items: flex-start; flex-direction: column; gap: 12px; }
            .filter-header-title { width: 100%; }
            .filter-title { font-size: 14px; }
            .filter-subtitle { font-size: 11px; }
            .reset-filter-btn { width: 100% !important; }
            .filter-select { height: 42px !important; }
            .active-filter { align-items: flex-start; }
        }
    </style>

    <script>
        function handleJenjangChange(selectObj) {
            var val = selectObj.value;
            var url = new URL(window.location.href);
            if (val) { url.searchParams.set('jenjang', val); } else { url.searchParams.delete('jenjang'); }
            url.searchParams.delete('kelas');
            url.searchParams.delete('sub_kategori');
            url.searchParams.delete('mapel');
            url.searchParams.delete('kategori_id');
            window.location.href = url.toString();
        }

        function handleKelasChange(selectObj) {
            var val = selectObj.value;
            var url = new URL(window.location.href);
            if (val) { url.searchParams.set('kelas', val); } else { url.searchParams.delete('kelas'); }
            url.searchParams.delete('sub_kategori');
            url.searchParams.delete('mapel');
            url.searchParams.delete('kategori_id');
            window.location.href = url.toString();
        }

        function handleSubChange(selectObj) {
            var val = selectObj.value;
            var url = new URL(window.location.href);
            if (val) { url.searchParams.set('sub_kategori', val); } else { url.searchParams.delete('sub_kategori'); }
            url.searchParams.delete('mapel');
            url.searchParams.delete('kategori_id');
            window.location.href = url.toString();
        }

        function filterSoalList() {
            var input = document.getElementById("searchSoalInput");
            var filter = input.value.toLowerCase();
            var cards = document.getElementsByClassName("soal-card-item");

            for (var i = 0; i < cards.length; i++) {
                var text = cards[i].innerText || cards[i].textContent;
                cards[i].style.display = text.toLowerCase().indexOf(filter) > -1 ? "" : "none";
            }
        }
    </script>
@endsection