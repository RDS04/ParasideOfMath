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
            <div class="row align-items-center">
                <div class="col-sm-7">
                    <h1 class="m-0 font-weight-bold text-purple-950 d-flex align-items-center">
                        <i class="fas fa-list-alt text-purple-600 mr-2.5"></i> List Soal &amp; Modul Terinput
                    </h1>
                    <p class="text-sm text-slate-500 mb-0 mt-1">
                        Pilih jenjang, kelas, semester, dan mata pelajaran untuk melihat daftar soal serta dokumen modul PDF.
                    </p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right text-sm bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ $dashRoute }}"
                                class="text-purple-600 font-semibold">Dashboard</a></li>
                        <li class="breadcrumb-item active text-slate-500">List Soal &amp; Modul</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content pb-5">
        <div class="container-fluid">

            <!-- ════════════════════════════════════════════════════════════ -->
            <!-- FILTER BANK SOAL: JENJANG, KELAS, SEMESTER, & MAPEL          -->
            <!-- ════════════════════════════════════════════════════════════ -->
            <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-purple-100 text-purple-900 d-flex align-items-center justify-content-center mr-3 shadow-xs"
                            style="width: 38px; height: 38px;">
                            <i class="fas fa-filter text-purple-700"></i>
                        </div>
                        <div>
                            <h5 class="card-title font-bold text-purple-950 mb-0 text-base">Filter Pencarian Soal &amp; Modul</h5>
                            <span class="text-xs text-slate-500">Pilih Jenjang, Kelas, Semester, dan Mata Pelajaran untuk memfilter soal</span>
                        </div>
                    </div>
                    @if($jenjang || $kelas || $sub || $mapel)
                        <a href="{{ route($prefixRoute . '.index') }}"
                            class="btn btn-sm btn-outline-danger font-bold rounded-xl px-3 py-1.5 text-xs transition-all shadow-xs">
                            <i class="fas fa-undo mr-1"></i> Reset Filter
                        </a>
                    @endif
                </div>

                <div class="card-body p-4 bg-purple-50/30">
                    <form id="filterListSoalForm" action="{{ route($prefixRoute . '.index') }}" method="GET">
                        <div class="row g-3">

                            <!-- Dropdown 1: Jenjang -->
                            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                                <label class="form-label text-xs font-bold text-purple-950 uppercase tracking-wider mb-1.5 d-flex align-items-center">
                                    <i class="fas fa-graduation-cap text-purple-600 mr-1.5"></i> 1. Jenjang Pendidikan
                                </label>
                                <select name="jenjang" id="filterJenjang"
                                    class="form-control custom-select rounded-xl font-semibold text-sm border-purple-200 focus:border-purple-500 shadow-xs"
                                    onchange="handleJenjangChange(this)">
                                    <option value="">-- Pilih Jenjang --</option>
                                    <option value="SD" {{ $jenjang === 'SD' ? 'selected' : '' }}>SD (Sekolah Dasar)</option>
                                    <option value="SMP" {{ $jenjang === 'SMP' ? 'selected' : '' }}>SMP (Sekolah Menengah Pertama)</option>
                                    <option value="SMA" {{ $jenjang === 'SMA' ? 'selected' : '' }}>SMA (Sekolah Menengah Atas)</option>
                                </select>
                            </div>

                            <!-- Dropdown 2: Kelas -->
                            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                                <label class="form-label text-xs font-bold text-purple-950 uppercase tracking-wider mb-1.5 d-flex align-items-center">
                                    <i class="fas fa-users text-purple-600 mr-1.5"></i> 2. Kelas
                                </label>
                                <select name="kelas" id="filterKelas"
                                    class="form-control custom-select rounded-xl font-semibold text-sm border-purple-200 focus:border-purple-500 shadow-xs"
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

                            <!-- Dropdown 3: Semester / TKA -->
                            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                                <label class="form-label text-xs font-bold text-purple-950 uppercase tracking-wider mb-1.5 d-flex align-items-center">
                                    <i class="fas fa-bookmark text-purple-600 mr-1.5"></i> 3. Semester / TKA
                                </label>
                                <select name="sub_kategori" id="filterSub"
                                    class="form-control custom-select rounded-xl font-semibold text-sm border-purple-200 focus:border-purple-500 shadow-xs"
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

                            <!-- Dropdown 4: Mata Pelajaran -->
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label text-xs font-bold text-purple-950 uppercase tracking-wider mb-1.5 d-flex align-items-center">
                                    <i class="fas fa-book text-purple-600 mr-1.5"></i> 4. Mata Pelajaran
                                </label>
                                <select name="mapel" id="filterMapel"
                                    class="form-control custom-select rounded-xl font-semibold text-sm border-purple-200 focus:border-purple-500 shadow-xs"
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
                    </form>

                    <!-- Active Filter Summary Badges -->
                    @if($jenjang || $kelas || $sub || $mapel)
                        <div class="d-flex flex-wrap align-items-center gap-2 mt-3 pt-3 border-top border-purple-100">
                            <span class="text-xs font-bold text-slate-500 mr-1">Filter Aktif:</span>

                            @if($jenjang)
                                <span class="badge bg-purple-100 text-purple-900 border border-purple-200 font-bold px-3 py-1.5 rounded-lg text-xs d-inline-flex align-items-center">
                                    <i class="fas fa-graduation-cap text-purple-600 mr-1.5"></i> Jenjang: {{ $jenjang }}
                                </span>
                            @endif

                            @if($kelas)
                                <span class="badge bg-purple-100 text-purple-900 border border-purple-200 font-bold px-3 py-1.5 rounded-lg text-xs d-inline-flex align-items-center">
                                    <i class="fas fa-users text-purple-600 mr-1.5"></i> Kelas {{ $kelas }}
                                </span>
                            @endif

                            @if($sub)
                                <span class="badge bg-purple-100 text-purple-900 border border-purple-200 font-bold px-3 py-1.5 rounded-lg text-xs d-inline-flex align-items-center">
                                    <i class="fas fa-bookmark text-purple-600 mr-1.5"></i> {{ $sub }}
                                </span>
                            @endif

                            @if($mapel)
                                <span class="badge bg-purple-900 text-white font-bold px-3 py-1.5 rounded-lg text-xs d-inline-flex align-items-center shadow-xs">
                                    <i class="fas fa-book text-amber-300 mr-1.5"></i> {{ $mapel }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- NOTICE JIKA FILTER BELUM LENGKAP -->
            @if (!($jenjang && $kelas && $sub && $mapel))
                <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                    <div class="card-body p-5 text-center">
                        <div class="rounded-circle bg-purple-50 text-purple-600 d-inline-flex align-items-center justify-content-center mb-3 shadow-xs"
                            style="width: 64px; height: 64px;">
                            <i class="fas fa-search fa-2x"></i>
                        </div>
                        <h5 class="font-bold text-purple-950 mb-2 text-lg">Silakan Pilih Filter Mata Pelajaran</h5>
                        <p class="text-slate-500 text-sm max-w-md mx-auto mb-0">
                            Pilih <span class="font-bold text-purple-900">Jenjang</span>, <span class="font-bold text-purple-900">Kelas</span>, <span class="font-bold text-purple-900">Semester/TKA</span>, dan <span class="font-bold text-purple-900">Mata Pelajaran</span> pada filter di atas untuk menampilkan daftar soal &amp; modul PDF.
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

                <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                    <div class="card-header p-2 bg-slate-100 border-bottom">
                        <ul class="nav nav-pills nav-justified w-100 gap-2" id="tabListSoalModul" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active font-bold text-xs sm:text-sm py-2.5 rounded-xl shadow-xs transition-all text-center d-flex items-center justify-center gap-2"
                                    id="tab-soal-manual" data-toggle="pill" href="#content-soal-manual" role="tab"
                                    aria-controls="content-soal-manual" aria-selected="true"
                                    style="border: 2px solid #6b21a8;">
                                    <i class="fas fa-list-ol fa-lg text-amber-500"></i>
                                    <span>Menu 1: Lihat Soal ({{ $mapel }})</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link font-bold text-xs sm:text-sm py-2.5 rounded-xl shadow-xs transition-all text-center d-flex items-center justify-center gap-2"
                                    id="tab-modul-pdf" data-toggle="pill" href="#content-modul-pdf" role="tab"
                                    aria-controls="content-modul-pdf" aria-selected="false"
                                    style="border: 2px solid #0284c7;">
                                    <i class="fas fa-file-pdf fa-lg text-rose-500"></i>
                                    <span>Menu 2: Lihat Modul PDF ({{ $allDocFilesCount }} File)</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-3 p-sm-4 bg-slate-50/50">
                        <div class="tab-content" id="tabListSoalModulContent">

                            <!-- ════════════════════════════════════════════════════════════ -->
                            <!-- MENU 1: LIHAT SOAL (DAFTAR KARTU JUDUL SOAL → EXPAND SOAL)  -->
                            <!-- ════════════════════════════════════════════════════════════ -->
                            <div class="tab-pane fade show active" id="content-soal-manual" role="tabpanel" aria-labelledby="tab-soal-manual">
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4 pb-3 border-bottom bg-white p-3.5 rounded-2xl border shadow-xs">
                                    <div>
                                        <h5 class="font-bold text-purple-950 mb-1 text-base sm:text-lg d-flex align-items-center gap-2">
                                            <i class="fas fa-book-open text-purple-600"></i> Daftar Paket &amp; Judul Soal: {{ $mapel }}
                                        </h5>
                                        <span class="text-xs text-slate-500">
                                            Klik pada salah satu Judul Soal di bawah untuk menampilkan daftar pertanyaan latihan.
                                        </span>
                                    </div>
                                    <div class="d-flex items-center gap-2 w-100 w-sm-auto">
                                        <input type="text" id="searchSoalInput" class="form-control form-control-sm rounded-xl text-xs" placeholder="Cari judul / soal..." onkeyup="filterSoalList()">
                                        <button class="btn btn-sm btn-outline-purple font-bold rounded-xl text-xs px-3 py-1.5 shrink-0" onclick="window.print()">
                                            <i class="fas fa-print mr-1"></i> Cetak
                                        </button>
                                    </div>
                                </div>

                                @if ($kategoriList->isEmpty())
                                    <div class="text-center py-5 bg-white rounded-2xl border-2 border-dashed border-slate-200 p-4">
                                        <i class="fas fa-folder-open text-slate-300 fa-3x mb-3"></i>
                                        <h6 class="font-bold text-slate-700 mb-1">Belum ada paket/judul soal terinput untuk {{ $mapel }}.</h6>
                                        <p class="text-xs text-slate-500 mb-3">Buka menu <strong>Input Soal</strong> untuk menambahkan judul &amp; soal latihan baru.</p>
                                    </div>
                                @else
                                    <!-- LIST CARD DAFTAR JUDUL SOAL (KLIK UNTUK BUKA HALAMAN DETAIL DEDIKASI) -->
                                    <div class="d-flex flex-column gap-3" id="paketSoalList">
                                        @foreach ($kategoriList as $kIndex => $kat)
                                            @php
                                                $soalCount = $kat->bankSoals ? $kat->bankSoals->count() : $kat->bank_soals_count;
                                                $detailUrl = route('guru.list-soal.detail', $kat->id);
                                            @endphp
                                            <a href="{{ $detailUrl }}"
                                                class="card border border-slate-200 rounded-2xl shadow-xs overflow-hidden text-decoration-none transition-all hover:shadow-md soal-card-item">
                                                <div class="card-body p-3.5 d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-3"
                                                    style="background: linear-gradient(135deg, #ffffff, #f8fafc) !important; color: #0f172a !important;">
                                                    
                                                    <div class="d-flex items-center gap-3 min-w-0 flex-1">
                                                        <div class="rounded-2xl p-2.5 d-flex items-center justify-center shrink-0 shadow-xs"
                                                            style="width: 44px; height: 44px; background: #f1f5f9; border: 1px solid #cbd5e1;">
                                                            <i class="fas fa-folder-open fa-lg text-purple-700"></i>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                                                <span class="badge font-bold px-2.5 py-1 rounded-md text-[11px] uppercase shadow-xs" style="background-color: #f3e8ff !important; color: #6b21a8 !important; border: 1px solid #d8b4fe !important;">
                                                                    <i class="fas fa-book mr-1"></i> {{ $mapel }}
                                                                </span>
                                                                <h6 class="font-bold text-sm sm:text-base text-truncate mb-0 text-slate-900" title="{{ $kat->deskripsi ?: $kat->nama_kategori }}">
                                                                    {{ $kat->deskripsi ?: $kat->nama_kategori }}
                                                                </h6>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                                <span class="badge font-bold px-2.5 py-1 rounded-lg text-xs" style="background-color: #f3e8ff !important; color: #6b21a8 !important; border: 1px solid #d8b4fe !important;">
                                                                    <i class="fas fa-list-ol mr-1"></i> {{ $soalCount }} Soal Manual
                                                                </span>
                                                                <span class="text-xs text-slate-500 font-medium">
                                                                    <i class="far fa-clock mr-1"></i> Dibuat {{ $kat->created_at->diffForHumans() }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex items-center justify-content-end gap-2 shrink-0">
                                                        <span class="btn btn-xs font-bold rounded-xl px-3.5 py-2 text-xs shadow-xs text-white" style="background: linear-gradient(135deg, #6b21a8, #4c1d95) !important; color: #ffffff !important; border: none !important;">
                                                            <i class="fas fa-external-link-alt mr-1 text-amber-300"></i> Buka Soal &amp; Modul <i class="fas fa-chevron-right ml-1"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- ════════════════════════════════════════════════════════════ -->
                            <!-- MENU 2: LIHAT MODUL DOKUMEN / PDF TERSIMPAN                -->
                            <!-- ════════════════════════════════════════════════════════════ -->
                            <div class="tab-pane fade" id="content-modul-pdf" role="tabpanel" aria-labelledby="tab-modul-pdf">
                                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom bg-white p-3.5 rounded-2xl border shadow-xs">
                                    <div>
                                        <h5 class="font-bold text-purple-950 mb-1 text-base sm:text-lg d-flex align-items-center gap-2">
                                            <i class="fas fa-file-pdf text-rose-500"></i> Daftar Dokumen Modul PDF: {{ $mapel }}
                                        </h5>
                                        <span class="text-xs text-slate-500">File dokumen modul pembelajaran yang diunggah untuk dibaca di web</span>
                                    </div>
                                </div>

                                @php
                                    $hasAnyDocFiles = false;
                                @endphp

                                <div class="d-flex flex-column gap-3">
                                    @foreach ($kategoriList as $kMod)
                                        @php
                                            $gModDocFiles = glob(public_path("uploads/bank_soal_docs/doc_{$kMod->id}_*.*")) ?: [];
                                        @endphp
                                        @if (count($gModDocFiles) > 0)
                                            @php $hasAnyDocFiles = true; @endphp
                                            <div class="card border border-slate-200 rounded-2xl shadow-xs overflow-hidden bg-white">
                                                <div class="card-header bg-slate-100 py-2.5 px-3.5 d-flex justify-content-between align-items-center border-bottom">
                                                    <span class="font-bold text-xs text-purple-950">
                                                        <i class="fas fa-folder text-purple-600 mr-1.5"></i> {{ $kMod->deskripsi ?: $kMod->nama_kategori }}
                                                    </span>
                                                    <span class="badge bg-purple-900 text-white font-bold px-2.5 py-1 rounded-md text-[11px]">
                                                        {{ count($gModDocFiles) }} File PDF
                                                    </span>
                                                </div>
                                                <div class="card-body p-3 bg-slate-50/50">
                                                    <div class="d-flex flex-column gap-2">
                                                        @foreach($gModDocFiles as $gDocIdx => $gDocPath)
                                                            @php
                                                                $gDocFileName = basename($gDocPath);
                                                                $gDocExt = strtolower(pathinfo($gDocFileName, PATHINFO_EXTENSION));
                                                                $gDocDisplay = preg_replace('/^doc_\d+_\d+_/', '', $gDocFileName);
                                                                $gDocUrl = asset("uploads/bank_soal_docs/{$gDocFileName}");
                                                                $isPdf = $gDocExt === 'pdf';
                                                            @endphp
                                                            <div class="p-3.5 rounded-2xl border bg-white d-flex flex-column flex-sm-row items-stretch items-sm-center justify-content-between gap-3 hover:border-purple-300 transition-all shadow-xs">
                                                                <div class="d-flex items-center gap-3 min-w-0 flex-1">
                                                                    <div class="rounded-2xl p-3 d-flex items-center justify-center shrink-0 {{ $isPdf ? 'bg-rose-100 text-rose-600' : 'bg-blue-100 text-blue-600' }}">
                                                                        <i class="fas {{ $isPdf ? 'fa-file-pdf' : 'fa-file-word' }} fa-xl"></i>
                                                                    </div>
                                                                    <div class="min-w-0 flex-1">
                                                                        <div class="d-flex items-center gap-2 flex-wrap mb-1">
                                                                            <h6 class="font-bold text-sm text-purple-950 text-truncate mb-0" title="{{ $gDocDisplay }}">{{ $gDocDisplay }}</h6>
                                                                            <span class="badge {{ $isPdf ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-blue-50 text-blue-700 border-blue-200' }} border px-2 py-0.5 rounded text-[10px] uppercase font-bold">
                                                                                DOKUMEN {{ strtoupper($gDocExt) }}
                                                                            </span>
                                                                        </div>
                                                                        <span class="text-xs text-slate-400 d-block text-truncate">File Modul Pembelajaran Web</span>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex items-center gap-2 shrink-0">
                                                                    @if($isPdf)
                                                                        <button type="button" class="btn btn-xs font-bold rounded-xl px-4 py-2 text-xs text-white shadow-xs" data-toggle="modal" data-target="#modalListPreviewDoc_{{ $kMod->id }}_{{ $gDocIdx }}" style="background: linear-gradient(135deg, #6b21a8, #4c1d95) !important; color: #ffffff !important; border: none !important;">
                                                                            <i class="fas fa-book-reader text-amber-300 mr-1"></i> Baca Dokumen PDF
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <!-- MODAL PREVIEW PDF (PROTEKSI UNDUH & FULLSCREEN) -->
                                                            @if($isPdf)
                                                                <div class="modal fade p-0" id="modalListPreviewDoc_{{ $kMod->id }}_{{ $gDocIdx }}" tabindex="-1" role="dialog" aria-hidden="true" style="padding-right: 0 !important;">
                                                                    <div class="modal-dialog m-0" role="document" style="max-width: 100vw; width: 100vw; height: 100vh; margin: 0;">
                                                                        <div class="modal-content border-0 rounded-0 shadow-none h-100 bg-slate-900">
                                                                            <div class="modal-header bg-purple-950 text-white p-2.5 p-sm-3 border-bottom border-purple-800 d-flex flex-row justify-content-between align-items-center gap-2">
                                                                                <div class="d-flex align-items-center gap-2 min-w-0">
                                                                                    <i class="fas fa-file-pdf text-rose-400 fa-lg shrink-0"></i>
                                                                                    <div class="min-w-0">
                                                                                        <h5 class="modal-title font-bold text-xs sm:text-sm text-white mb-0 text-truncate" style="max-width: 60vw;">{{ $gDocDisplay }}</h5>
                                                                                        <span class="text-[10px] text-amber-300 d-block font-medium">
                                                                                            <i class="fas fa-shield-alt mr-1"></i>Mode Baca Saja (Proteksi Unduh Aktif)
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="d-flex align-items-center gap-1.5 shrink-0">
                                                                                    <button type="button" class="btn btn-xs btn-danger font-bold rounded-lg px-3 py-1.5 text-xs shadow-xs" data-dismiss="modal" aria-label="Close">
                                                                                        <i class="fas fa-times mr-1"></i> Tutup Reader
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-body p-0 bg-slate-900 overflow-hidden" style="height: calc(100vh - 58px); user-select: none;" oncontextmenu="return false;">
                                                                                <iframe src="{{ $gDocUrl }}#toolbar=0&navpanes=0&scrollbar=1&view=FitH" class="w-100 h-100 border-0" style="min-height: 100%;" oncontextmenu="return false;"></iframe>
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
                                        <div class="text-center py-5 bg-white rounded-2xl border-2 border-dashed border-slate-200 p-4">
                                            <i class="fas fa-file-pdf text-slate-300 fa-3x mb-3"></i>
                                            <h6 class="font-bold text-slate-700 mb-1">Belum ada dokumen modul PDF terunggah untuk {{ $mapel }}.</h6>
                                            <p class="text-xs text-slate-500 mb-3">Buka menu <strong>Input Soal &gt; Upload PDF / Word</strong> untuk menambahkan dokumen modul.</p>
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

    <script>
        function handleJenjangChange(selectObj) {
            var val = selectObj.value;
            var url = new URL(window.location.href);
            if (val) {
                url.searchParams.set('jenjang', val);
            } else {
                url.searchParams.delete('jenjang');
            }
            url.searchParams.delete('kelas');
            url.searchParams.delete('sub_kategori');
            url.searchParams.delete('mapel');
            url.searchParams.delete('kategori_id');
            window.location.href = url.toString();
        }

        function handleKelasChange(selectObj) {
            var val = selectObj.value;
            var url = new URL(window.location.href);
            if (val) {
                url.searchParams.set('kelas', val);
            } else {
                url.searchParams.delete('kelas');
            }
            url.searchParams.delete('sub_kategori');
            url.searchParams.delete('mapel');
            url.searchParams.delete('kategori_id');
            window.location.href = url.toString();
        }

        function handleSubChange(selectObj) {
            var val = selectObj.value;
            var url = new URL(window.location.href);
            if (val) {
                url.searchParams.set('sub_kategori', val);
            } else {
                url.searchParams.delete('sub_kategori');
            }
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
                if (text.toLowerCase().indexOf(filter) > -1) {
                    cards[i].style.display = "";
                } else {
                    cards[i].style.display = "none";
                }
            }
        }
    </script>
@endsection
