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
            <div class="row align-items-center">
                <div class="col-sm-7">
                    <h1 class="m-0 font-weight-bold text-purple-950 d-flex align-items-center">
                        <i class="fas fa-folder-open text-purple-600 mr-2.5"></i> Bank Soal &amp; Latihan
                    </h1>
                    <p class="text-sm text-slate-500 mb-0 mt-1">
                        Kelola soal secara terstruktur: Jenjang → Kelas → Semester/TKA → Mata Pelajaran → Soal.
                    </p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right text-sm bg-transparent p-0 m-0">
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
                    <i class="fas fa-check-circle fa-lg mr-3 text-emerald-500"></i>
                    <div>
                        <strong class="font-bold">Berhasil!</strong> {{ session('success') }}
                    </div>
                    <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" class="text-emerald-700">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert"
                    style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444;">
                    <strong class="font-bold"><i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert"
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
            <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-purple-100 text-purple-900 d-flex align-items-center justify-content-center mr-3 shadow-xs"
                            style="width: 38px; height: 38px;">
                            <i class="fas fa-filter text-purple-700"></i>
                        </div>
                        <div>
                            <h5 class="card-title font-bold text-purple-950 mb-0 text-base">Filter Bank Soal &amp; Latihan
                            </h5>
                            <span class="text-xs text-slate-500">Pilih Jenjang, Kelas, Semester, dan Mata Pelajaran untuk
                                memfilter soal</span>
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
                    <form id="filterBankSoalForm" action="{{ route($prefixRoute . '.index') }}" method="GET">
                        <div class="row g-3">

                            <!-- Dropdown 1: Jenjang -->
                            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                                <label
                                    class="form-label text-xs font-bold text-purple-950 uppercase tracking-wider mb-1.5 d-flex align-items-center">
                                    <i class="fas fa-graduation-cap text-purple-600 mr-1.5"></i> 1. Jenjang Pendidikan
                                </label>
                                <select name="jenjang" id="filterJenjang"
                                    class="form-control custom-select rounded-xl font-semibold text-sm border-purple-200 focus:border-purple-500 shadow-xs"
                                    onchange="handleJenjangChange(this)">
                                    <option value="">-- Pilih Jenjang --</option>
                                    <option value="SD" {{ $jenjang === 'SD' ? 'selected' : '' }}>SD (Sekolah Dasar)</option>
                                    <option value="SMP" {{ $jenjang === 'SMP' ? 'selected' : '' }}>SMP (Sekolah Menengah
                                        Pertama)</option>
                                    <option value="SMA" {{ $jenjang === 'SMA' ? 'selected' : '' }}>SMA (Sekolah Menengah Atas)
                                    </option>
                                </select>
                            </div>

                            <!-- Dropdown 2: Kelas -->
                            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                                <label
                                    class="form-label text-xs font-bold text-purple-950 uppercase tracking-wider mb-1.5 d-flex align-items-center">
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
                                <label
                                    class="form-label text-xs font-bold text-purple-950 uppercase tracking-wider mb-1.5 d-flex align-items-center">
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
                                <label
                                    class="form-label text-xs font-bold text-purple-950 uppercase tracking-wider mb-1.5 d-flex align-items-center">
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
                                <span
                                    class="badge bg-purple-100 text-purple-900 border border-purple-200 font-bold px-3 py-1.5 rounded-lg text-xs d-inline-flex align-items-center">
                                    <i class="fas fa-graduation-cap text-purple-600 mr-1.5"></i> Jenjang: {{ $jenjang }}
                                </span>
                            @endif

                            @if($kelas)
                                <span
                                    class="badge bg-purple-100 text-purple-900 border border-purple-200 font-bold px-3 py-1.5 rounded-lg text-xs d-inline-flex align-items-center">
                                    <i class="fas fa-users text-purple-600 mr-1.5"></i> Kelas {{ $kelas }}
                                </span>
                            @endif

                            @if($sub)
                                <span
                                    class="badge bg-purple-100 text-purple-900 border border-purple-200 font-bold px-3 py-1.5 rounded-lg text-xs d-inline-flex align-items-center">
                                    <i class="fas fa-bookmark text-purple-600 mr-1.5"></i> {{ $sub }}
                                </span>
                            @endif

                            @if($mapel)
                                <span
                                    class="badge bg-purple-900 text-white font-bold px-3 py-1.5 rounded-lg text-xs d-inline-flex align-items-center shadow-xs">
                                    <i class="fas fa-book text-amber-300 mr-1.5"></i> {{ $mapel }}
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
                        class="card-header bg-white py-3 px-4 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center">
                            <span
                                class="badge bg-purple-900 text-white rounded-circle mr-2.5 d-flex align-items-center justify-content-center"
                                style="width: 24px; height: 24px; font-size: 12px;">5</span>
                            <h5 class="card-title font-bold text-purple-950 mb-0 text-base">
                                Daftar Soal {{ $mapel }}
                                <span
                                    class="badge bg-purple-100 text-purple-900 font-bold ml-2 px-2.5 py-0.5 text-xs">{{ $jenjang }}
                                    - Kelas {{ $kelas }} - {{ $sub }}</span>
                            </h5>
                            @if($kategoriId)
                                <span class="badge bg-purple-100 text-purple-900 font-bold ml-3 px-3 py-1 rounded-full text-xs">
                                    <i class="fas fa-check-circle text-purple-600 mr-1"></i> Terpilih
                                </span>
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-purple font-bold rounded-xl px-3 py-1.5 text-xs shadow-xs"
                            data-toggle="modal" data-target="#modalTambahKategori">
                            <i class="fas fa-plus-circle mr-1"></i> Buat Paket Soal Baru
                        </button>
                    </div>
                    <div class="card-body p-4">
                        @if ($kategoriList->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-folder-open text-slate-300 fa-3x mb-3"></i>
                                <p class="text-slate-500 font-semibold mb-1">Belum ada paket soal untuk {{ $mapel }}.</p>
                                <p class="text-xs text-slate-400 mb-3">Klik tombol di bawah ini untuk membuat paket/judul soal baru
                                    untuk mata pelajaran ini.</p>
                                <button type="button" class="btn btn-sm btn-purple font-bold rounded-xl px-4 py-2 text-xs shadow-xs"
                                    data-toggle="modal" data-target="#modalTambahKategori">
                                    <i class="fas fa-plus-circle mr-1.5"></i> Buat Paket Soal {{ $mapel }} Baru
                                </button>
                            </div>
                        @else
                            <div class="row g-3">
                                @foreach ($kategoriList as $kat)
                                    @php
                                        $isSelected = $kategoriId == $kat->id;
                                        $url = route($prefixRoute . '.index', [
                                            'jenjang' => $jenjang,
                                            'kelas' => $kelas,
                                            'sub_kategori' => $sub,
                                            'mapel' => $mapel,
                                            'kategori_id' => $kat->id,
                                        ]);
                                    @endphp
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <a href="{{ $url }}"
                                            class="card text-decoration-none rounded-2xl overflow-hidden h-100 transition-all shadow-sm"
                                            style="{{ $isSelected ? 'background: linear-gradient(135deg, #4c1d95, #6b21a8) !important; color: #ffffff !important; border: 2px solid #facc15 !important;' : 'background-color: #ffffff !important; color: #1e1b4b !important; border: 2px solid #e2e8f0 !important;' }}">
                                            <div class="card-body p-3.5">
                                                <div class="d-flex align-items-start gap-3">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-xs"
                                                        style="width: 40px; height: 40px; background: {{ $isSelected ? 'rgba(255,255,255,0.2)' : '#f3e8ff' }};">
                                                        <i
                                                            class="fas fa-file-alt {{ $isSelected ? 'text-amber-300' : 'text-purple-700' }}"></i>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <h6 class="font-bold mb-1 text-sm text-truncate" style="color: {{ $isSelected ? '#ffffff' : '#3b0764' }} !important;" title="{{ $kat->deskripsi ?: $kat->nama_kategori }}">
                                                            {{ $kat->deskripsi ?: $kat->nama_kategori }}
                                                        </h6>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="badge font-bold px-2 py-0.5 rounded text-xs" style="{{ $isSelected ? 'background-color: rgba(255, 255, 255, 0.2) !important; color: #fef08a !important;' : 'background-color: #f3e8ff !important; color: #6b21a8 !important;' }}">
                                                                <i class="fas fa-list-ol mr-1"></i> {{ $kat->bank_soals_count }} Soal
                                                            </span>
                                                            <span class="text-xs" style="color: {{ $isSelected ? '#e9d5ff' : '#64748b' }} !important;">
                                                                {{ $kat->created_at->diffForHumans() }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @if ($isSelected)
                                                        <i class="fas fa-check-circle fa-lg mt-1 shrink-0" style="color: #facc15 !important;"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- ════════════════════════════════════════════════════════════ -->
            <!-- LANGKAH 6: DETAIL SOAL (muncul saat kategori dipilih)      -->
            <!-- ════════════════════════════════════════════════════════════ -->
            @if ($selectedCategory)

                <!-- Active Combo Detail Header Card -->
                <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-gradient-to-r from-purple-900 to-indigo-900 text-white">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span
                                        class="badge bg-purple-500/30 text-purple-200 border border-purple-400/30 px-2.5 py-1 rounded-md text-xs font-bold uppercase">
                                        Jenjang {{ $jenjang }}
                                    </span>
                                    <span
                                        class="badge bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 px-2.5 py-1 rounded-md text-xs font-bold">
                                        Kelas {{ $kelas }}
                                    </span>
                                    <span
                                        class="badge bg-emerald-500/30 text-emerald-200 border border-emerald-400/30 px-2.5 py-1 rounded-md text-xs font-bold">
                                        {{ $sub }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-xl mb-0">
                                    {{ $selectedCategory->deskripsi ?: $selectedCategory->nama_kategori }}
                                </h3>
                            </div>
                            <form action="{{ route($prefixRoute . '.kategori.delete', $selectedCategory->id) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua soal ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-light font-bold rounded-lg px-2.5 py-1.5">
                                    <i class="fas fa-trash-alt mr-1"></i> Hapus Semua Soal Ini
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Excel Import Session Preview Banner -->
                @if (session('import_preview_soals'))
                    <div class="card border-0 shadow-lg rounded-2xl mb-4 bg-white overflow-hidden"
                        style="border-left: 5px solid #10b981;">
                        <div
                            class="card-header bg-emerald-50/80 py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h5 class="card-title font-bold text-emerald-950 mb-0 d-flex align-items-center text-base">
                                <i class="fas fa-file-excel text-emerald-600 mr-2 fa-lg"></i> Pratinjau Impor Soal dari Excel
                                ({{ count(session('import_preview_soals')) }} Soal)
                            </h5>
                            <div class="d-flex align-items-center gap-2">
                                <form action="{{ route($prefixRoute . '.import.cancel') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-danger font-bold rounded-xl px-3 py-1.5 text-xs">
                                        <i class="fas fa-times mr-1"></i> Batal
                                    </button>
                                </form>
                                <form action="{{ route($prefixRoute . '.import.confirm') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-sm btn-success font-bold rounded-xl px-4 py-1.5 text-xs shadow-sm"
                                        style="background-color: #059669; border-color: #059669;">
                                        <i class="fas fa-check-circle mr-1"></i> Konfirmasi & Simpan ke Database
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive" style="max-height: 380px;">
                                <table class="table table-hover table-striped table-bordered text-xs mb-0">
                                    <thead class="bg-emerald-100 text-emerald-950 font-bold sticky-top">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">No</th>
                                            <th style="min-width: 250px;">Pertanyaan Soal</th>
                                            <th>Opsi A</th>
                                            <th>Opsi B</th>
                                            <th>Opsi C</th>
                                            <th>Opsi D</th>
                                            <th class="text-center" style="width: 70px;">Kunci</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (session('import_preview_soals') as $pSoal)
                                            <tr>
                                                <td class="font-bold text-center bg-slate-50">{{ $pSoal['nomor'] }}</td>
                                                <td class="font-medium whitespace-pre-line">{{ $pSoal['soal'] }}</td>
                                                <td
                                                    class="{{ $pSoal['kunci_jawaban'] === 'A' ? 'font-bold text-emerald-800 bg-emerald-50' : '' }}">
                                                    {{ $pSoal['opsi_a'] }}
                                                </td>
                                                <td
                                                    class="{{ $pSoal['kunci_jawaban'] === 'B' ? 'font-bold text-emerald-800 bg-emerald-50' : '' }}">
                                                    {{ $pSoal['opsi_b'] }}
                                                </td>
                                                <td
                                                    class="{{ $pSoal['kunci_jawaban'] === 'C' ? 'font-bold text-emerald-800 bg-emerald-50' : '' }}">
                                                    {{ $pSoal['opsi_c'] }}
                                                </td>
                                                <td
                                                    class="{{ $pSoal['kunci_jawaban'] === 'D' ? 'font-bold text-emerald-800 bg-emerald-50' : '' }}">
                                                    {{ $pSoal['opsi_d'] }}
                                                </td>
                                                <td class="text-center"><span
                                                        class="badge bg-emerald-600 text-white font-extrabold px-2 py-1 rounded-md text-xs">{{ $pSoal['kunci_jawaban'] }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <!-- SEBELAH KIRI: FORM INPUT SOAL (2 MENU TAB) -->
                    <div class="col-lg-5 mb-4">
                        <div class="card border-0 shadow-sm rounded-2xl bg-white overflow-hidden h-100">
                            <!-- TAB HEADER NAV -->
                            <style>
                                .bank-soal-tab-btn {
                                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                                    font-weight: 800 !important;
                                }

                                .bank-soal-tab-btn.active {
                                    background: linear-gradient(135deg, #4c1d95, #6b21a8) !important;
                                    color: #ffffff !important;
                                    border: 2px solid #facc15 !important;
                                    box-shadow: 0 4px 12px rgba(76, 29, 149, 0.35) !important;
                                }

                                .bank-soal-tab-btn.active span,
                                .bank-soal-tab-btn.active i {
                                    color: #ffffff !important;
                                }

                                .bank-soal-tab-btn.active i.fa-edit {
                                    color: #facc15 !important;
                                }

                                .bank-soal-tab-btn:not(.active) {
                                    background-color: #f3e8ff !important;
                                    color: #581c87 !important;
                                    border: 2px solid #e9d5ff !important;
                                }

                                .bank-soal-tab-btn:not(.active) span {
                                    color: #581c87 !important;
                                }

                                .bank-soal-tab-btn:not(.active):hover {
                                    background-color: #e9d5ff !important;
                                    color: #3b0764 !important;
                                }
                            </style>
                            <div class="card-header bg-white p-2 border-bottom">
                                <ul class="nav nav-pills nav-fill font-bold text-xs gap-2" id="tabMenuBankSoal" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button
                                            class="nav-link bank-soal-tab-btn active rounded-xl py-2.5 px-3 d-flex align-items-center justify-content-center gap-2"
                                            id="tab-manual-btn" data-toggle="pill" data-target="#tab-manual-content"
                                            type="button" role="tab" aria-controls="tab-manual-content" aria-selected="true">
                                            <i class="fas fa-edit text-amber-400"></i>
                                            <span>Buat Soal Manual</span>
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button
                                            class="nav-link bank-soal-tab-btn rounded-xl py-2.5 px-3 d-flex align-items-center justify-content-center gap-2"
                                            id="tab-upload-btn" data-toggle="pill" data-target="#tab-upload-content"
                                            type="button" role="tab" aria-controls="tab-upload-content" aria-selected="false">
                                            <i class="fas fa-file-upload text-purple-700"></i>
                                            <span>Upload PDF / Word</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body p-4 bg-slate-50/40 tab-content" id="tabMenuBankSoalContent">
                                <!-- ══════════════ MENU 1: BUAT SOAL MANUAL ══════════════ -->
                                <div class="tab-pane fade show active" id="tab-manual-content" role="tabpanel"
                                    aria-labelledby="tab-manual-btn">
                                    <form action="{{ route($prefixRoute . '.soal.store') }}" method="POST"
                                        id="formInputSoalLive">
                                        @csrf
                                        <input type="hidden" name="kategori_soal_id" value="{{ $selectedCategory->id }}">

                                        <div class="row g-3">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label text-xs font-bold text-slate-700 uppercase">No.
                                                    Soal</label>
                                                <input type="number" name="nomor" id="input_nomor"
                                                    class="form-control rounded-xl font-bold text-sm border-slate-200"
                                                    value="{{ old('nomor', $selectedCategory->bankSoals->max('nomor') + 1 ?: 1) }}"
                                                    min="1" required oninput="updateLivePreview()">
                                            </div>
                                            <div class="col-md-8 mb-3">
                                                <label
                                                    class="form-label text-xs font-bold text-slate-700 uppercase mb-2 d-block">Kunci
                                                    Jawaban</label>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach (['A', 'B', 'C', 'D'] as $kunci)
                                                        <div class="form-check form-check-inline m-0">
                                                            <input class="form-check-input d-none" type="radio" name="kunci_jawaban"
                                                                id="input_kunci_{{ $kunci }}" value="{{ $kunci }}" {{ old('kunci_jawaban', 'A') === $kunci ? 'checked' : '' }} required
                                                                onchange="updateLivePreview()">
                                                            <label
                                                                class="btn {{ old('kunci_jawaban', 'A') === $kunci ? 'btn-purple text-white shadow-sm' : 'btn-outline-purple' }} text-xs font-bold px-3 py-1.5 rounded-xl cursor-pointer"
                                                                for="input_kunci_{{ $kunci }}">
                                                                Opsi {{ $kunci }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="col-12 mb-3">
                                                <label class="form-label text-xs font-bold text-slate-700 uppercase">Pertanyaan
                                                    / Soal</label>
                                                <textarea name="soal" id="input_soal"
                                                    class="form-control rounded-xl font-medium text-sm border-slate-200"
                                                    rows="3" placeholder="Tuliskan teks pertanyaan soal..." required
                                                    oninput="updateLivePreview()">{{ old('soal') }}</textarea>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label text-xs font-bold text-slate-700 uppercase">Pilihan
                                                    A</label>
                                                <textarea name="opsi_a" id="input_opsi_a"
                                                    class="form-control rounded-xl text-xs border-slate-200" rows="2"
                                                    placeholder="Jawaban A..." required
                                                    oninput="updateLivePreview()">{{ old('opsi_a') }}</textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label text-xs font-bold text-slate-700 uppercase">Pilihan
                                                    B</label>
                                                <textarea name="opsi_b" id="input_opsi_b"
                                                    class="form-control rounded-xl text-xs border-slate-200" rows="2"
                                                    placeholder="Jawaban B..." required
                                                    oninput="updateLivePreview()">{{ old('opsi_b') }}</textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label text-xs font-bold text-slate-700 uppercase">Pilihan
                                                    C</label>
                                                <textarea name="opsi_c" id="input_opsi_c"
                                                    class="form-control rounded-xl text-xs border-slate-200" rows="2"
                                                    placeholder="Jawaban C..." required
                                                    oninput="updateLivePreview()">{{ old('opsi_c') }}</textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label text-xs font-bold text-slate-700 uppercase">Pilihan
                                                    D</label>
                                                <textarea name="opsi_d" id="input_opsi_d"
                                                    class="form-control rounded-xl text-xs border-slate-200" rows="2"
                                                    placeholder="Jawaban D..." required
                                                    oninput="updateLivePreview()">{{ old('opsi_d') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="pt-3 border-top mt-2 d-flex justify-content-end">
                                            <button type="submit"
                                                class="btn btn-purple font-bold rounded-xl px-4 py-2 text-sm shadow-sm transition-all">
                                                <i class="fas fa-plus-circle mr-1.5"></i> Simpan Soal ke Database
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- ══════════════ MENU 2: UPLOAD FILE PDF / WORD / EXCEL ══════════════ -->
                                <div class="tab-pane fade" id="tab-upload-content" role="tabpanel"
                                    aria-labelledby="tab-upload-btn">
                                    <form action="{{ route($prefixRoute . '.import.preview') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="kategori_soal_id" value="{{ $selectedCategory->id }}">

                                        <div
                                            class="alert alert-purple bg-purple-50 border border-purple-200 rounded-xl p-3 mb-3 text-xs">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <i class="fas fa-info-circle text-purple-700 fa-lg"></i>
                                                <strong class="text-purple-950">Upload Dokumen Soal (PDF / Word /
                                                    Excel)</strong>
                                            </div>
                                            <p class="text-purple-800 mb-0 leading-relaxed">
                                                Unggah file bank soal dalam format <strong>PDF (.pdf)</strong>, <strong>Word
                                                    (.doc, .docx)</strong>, atau <strong>Excel (.xlsx, .xls)</strong> untuk
                                                mengimpor atau menyimpan dokumen soal secara cepat.
                                            </p>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label text-xs font-bold text-slate-700 uppercase">Kategori &amp;
                                                Mapel Terpilih</label>
                                            <input type="text"
                                                class="form-control rounded-xl text-xs font-bold bg-white text-purple-900 border-purple-200"
                                                value="{{ $selectedCategory->deskripsi ?: $selectedCategory->nama_kategori }} ({{ $mapel }} - {{ $jenjang }} Kelas {{ $kelas }})"
                                                readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label
                                                class="form-label text-xs font-bold text-slate-700 uppercase d-flex justify-content-between align-items-center">
                                                <span>File Dokumen Soal (PDF / Word / Excel)</span>
                                                <a href="{{ route($prefixRoute . '.template') }}"
                                                    class="text-purple-700 text-xs font-bold hover:underline">
                                                    <i class="fas fa-download mr-1"></i> Format Template
                                                </a>
                                            </label>
                                            <div
                                                class="border-2 border-dashed border-purple-300 rounded-2xl p-4 text-center bg-white hover:border-purple-500 transition-colors">
                                                <i class="fas fa-file-pdf text-rose-500 fa-2x mx-1"></i>
                                                <i class="fas fa-file-word text-blue-600 fa-2x mx-1"></i>
                                                <i class="fas fa-file-excel text-emerald-600 fa-2x mx-1"></i>
                                                <p class="text-xs text-slate-600 font-medium my-2">
                                                    Pilih file dokumen PDF / Word / Excel dari komputer Anda
                                                </p>
                                                <input type="file" name="file_excel"
                                                    class="form-control form-control-file text-xs"
                                                    accept=".pdf,.doc,.docx,.xlsx,.xls,.csv" required>
                                                <small class="text-slate-400 d-block mt-2">Ukuran maksimal file: 5MB</small>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label text-xs font-bold text-slate-700 uppercase">Instruksi /
                                                Catatan Pengerjaan (Opsional)</label>
                                            <textarea name="catatan_dokumen"
                                                class="form-control rounded-xl text-xs border-slate-200" rows="2"
                                                placeholder="Tuliskan petunjuk pengerjaan atau instruksi soal untuk siswa..."></textarea>
                                        </div>

                                        <div class="pt-3 border-top mt-2 d-flex justify-content-end gap-2">
                                            <a href="{{ route($prefixRoute . '.template') }}"
                                                class="btn btn-outline-secondary font-bold rounded-xl px-3 py-2 text-xs">
                                                <i class="fas fa-download mr-1"></i> Template Excel
                                            </a>
                                            <button type="submit"
                                                class="btn btn-purple font-bold rounded-xl px-4 py-2 text-sm shadow-sm transition-all">
                                                <i class="fas fa-upload mr-1.5"></i> Upload &amp; Impor Dokumen
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEBELAH KANAN: LIVE PREVIEW & DAFTAR SOAL TERSIMPAN -->
                    <div class="col-lg-7 mb-4">
                        @php
                            $guruCatDocFiles = glob(public_path("uploads/bank_soal_docs/doc_{$selectedCategory->id}_*.*")) ?: [];
                        @endphp

                        @if(count($guruCatDocFiles) > 0)
                            <!-- DAFTAR DOKUMEN (PDF / WORD) TERSIMPAN -->
                            <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                                <div class="card-header py-3 px-4 d-flex justify-content-between align-items-center"
                                    style="background: linear-gradient(135deg, #4c1d95, #6b21a8) !important; color: #ffffff !important; border: none !important;">
                                    <h5 class="card-title font-bold text-white mb-0 text-sm d-flex align-items-center"
                                        style="color: #ffffff !important;">
                                        <i class="fas fa-file-pdf text-amber-300 mr-2 fa-lg"></i> Dokumen Soal (PDF / Word)
                                        Tersimpan ({{ count($guruCatDocFiles) }} File)
                                    </h5>
                                    <span class="badge text-xs px-2.5 py-1 rounded-md font-bold"
                                        style="background-color: rgba(255, 255, 255, 0.2) !important; color: #fef08a !important; border: 1px solid rgba(255, 255, 255, 0.3) !important;">
                                        Server Storage
                                    </span>
                                </div>
                                <div class="card-body p-2 p-sm-3 bg-slate-50/50">
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($guruCatDocFiles as $gDocIdx => $gDocPath)
                                            @php
                                                $gDocFileName = basename($gDocPath);
                                                $gDocExt = strtolower(pathinfo($gDocFileName, PATHINFO_EXTENSION));
                                                $gDocDisplay = preg_replace('/^doc_\d+_\d+_/', '', $gDocFileName);
                                                $gDocUrl = asset("uploads/bank_soal_docs/{$gDocFileName}");
                                                $isPdf = $gDocExt === 'pdf';
                                                $isWord = in_array($gDocExt, ['doc', 'docx']);
                                            @endphp
                                            <div
                                                class="p-3 rounded-xl border bg-white d-flex flex-column flex-sm-row items-stretch items-sm-center justify-content-between gap-2.5 hover:border-purple-300 transition-all shadow-xs">
                                                <div class="d-flex items-center gap-3 min-w-0 flex-1">
                                                    <div
                                                        class="rounded-xl p-2.5 d-flex items-center justify-center shrink-0 {{ $isPdf ? 'bg-rose-100 text-rose-600' : 'bg-blue-100 text-blue-600' }}">
                                                        <i class="fas {{ $isPdf ? 'fa-file-pdf' : 'fa-file-word' }} fa-lg sm:fa-xl"></i>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <div class="d-flex items-center gap-2 flex-wrap mb-0.5">
                                                            <h6 class="font-bold text-xs sm:text-sm text-purple-950 text-truncate mb-0"
                                                                title="{{ $gDocDisplay }}">{{ $gDocDisplay }}</h6>
                                                            <span
                                                                class="badge {{ $isPdf ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-blue-50 text-blue-700 border-blue-200' }} border px-2 py-0.5 rounded text-[10px] uppercase font-bold">
                                                                DOKUMEN {{ strtoupper($gDocExt) }}
                                                            </span>
                                                        </div>
                                                        <span class="text-[11px] text-slate-400 d-block text-truncate">File Dokumen Bank
                                                            Soal</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex items-center gap-2 shrink-0">
                                                    @if($isPdf)
                                                        <button type="button"
                                                            class="btn btn-xs font-bold rounded-xl px-3.5 py-2 d-flex items-center justify-center gap-1.5 text-xs text-white shadow-xs"
                                                            data-toggle="modal" data-target="#modalGuruPreviewDoc_{{ $gDocIdx }}"
                                                            style="background: linear-gradient(135deg, #6b21a8, #4c1d95) !important; color: #ffffff !important; border: none !important;">
                                                            <i class="fas fa-book-reader text-amber-300"></i> Baca Dokumen PDF
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- MODAL PREVIEW PDF DOKUMEN GURU (PROTEKSI UNDUH & FULLSCREEN) -->
                                            @if($isPdf)
                                                <div class="modal fade p-0" id="modalGuruPreviewDoc_{{ $gDocIdx }}" tabindex="-1"
                                                    role="dialog" aria-hidden="true" style="padding-right: 0 !important;">
                                                    <div class="modal-dialog m-0" role="document"
                                                        style="max-width: 100vw; width: 100vw; height: 100vh; margin: 0;">
                                                        <div class="modal-content border-0 rounded-0 shadow-none h-100 bg-slate-900">
                                                            <div
                                                                class="modal-header bg-purple-950 text-white p-2.5 p-sm-3 border-bottom border-purple-800 d-flex flex-row justify-content-between align-items-center gap-2">
                                                                <div class="d-flex align-items-center gap-2 min-w-0">
                                                                    <i class="fas fa-file-pdf text-rose-400 fa-lg shrink-0"></i>
                                                                    <div class="min-w-0">
                                                                        <h5 class="modal-title font-bold text-xs sm:text-sm text-white mb-0 text-truncate"
                                                                            style="max-width: 60vw;">{{ $gDocDisplay }}</h5>
                                                                        <span class="text-[10px] text-amber-300 d-block font-medium">
                                                                            <i class="fas fa-shield-alt mr-1"></i>Mode Baca Saja (Proteksi Unduh Aktif)
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center gap-1.5 shrink-0">
                                                                    <button type="button"
                                                                        class="btn btn-xs btn-danger font-bold rounded-lg px-3 py-1.5 text-xs shadow-xs"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <i class="fas fa-times mr-1"></i> Tutup Reader
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="modal-body p-0 bg-slate-900 overflow-hidden"
                                                                style="height: calc(100vh - 58px); user-select: none;"
                                                                oncontextmenu="return false;">
                                                                <iframe src="{{ $gDocUrl }}#toolbar=0&navpanes=0&scrollbar=1&view=FitH"
                                                                    class="w-100 h-100 border-0" style="min-height: 100%;"
                                                                    oncontextmenu="return false;"></iframe>
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

                        <!-- DAFTAR SOAL TERSIMPAN -->
                        <div class="card border-0 shadow-sm rounded-2xl bg-white overflow-hidden">
                            <div
                                class="card-header bg-white py-3 px-4 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <h5 class="card-title font-bold text-purple-950 mb-0 d-flex align-items-center text-base">
                                    <i class="fas fa-list-ol text-purple-600 mr-2"></i> Soal Tersimpan
                                </h5>
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span
                                        class="badge bg-purple-100 text-purple-900 font-bold px-3 py-1.5 rounded-full text-xs">
                                        {{ $selectedCategory->bankSoals->count() }} Soal
                                    </span>
                                    <a href="{{ route($prefixRoute . '.template') }}"
                                        class="btn btn-sm btn-outline-info font-bold rounded-xl px-3 py-1.5 text-xs shadow-xs">
                                        <i class="fas fa-download mr-1"></i> Template Excel
                                    </a>
                                    <button type="button"
                                        class="btn btn-sm btn-outline-success font-bold rounded-xl px-3 py-1.5 text-xs shadow-xs"
                                        data-toggle="modal" data-target="#modalImportExcel">
                                        <i class="fas fa-file-excel mr-1"></i> Import Excel
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                @if ($selectedCategory->bankSoals->isEmpty())
                                    <div class="text-center py-5">
                                        <i class="fas fa-question-circle text-slate-300 fa-3x mb-3"></i>
                                        <p class="text-slate-500 font-semibold mb-0">Belum ada soal di dalam kategori ini.</p>
                                    </div>
                                @else
                                    <div class="space-y-4">
                                        @foreach ($selectedCategory->bankSoals as $soalItem)
                                            <div
                                                class="card border border-slate-200 rounded-xl shadow-xs overflow-hidden transition-all hover:border-purple-300 mb-3">
                                                <div
                                                    class="card-header bg-slate-50 py-2.5 px-3.5 d-flex justify-content-between align-items-center border-bottom">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span
                                                            class="badge bg-purple-900 text-white font-extrabold px-2.5 py-1 rounded-lg text-xs">
                                                            No. {{ $soalItem->nomor }}
                                                        </span>
                                                        <span
                                                            class="badge bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded text-[11px] border border-emerald-200">
                                                            Kunci: {{ $soalItem->kunci_jawaban }}
                                                        </span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-1.5">
                                                        <button type="button"
                                                            class="btn btn-xs btn-outline-warning font-bold rounded-lg px-2.5 py-1 text-xs"
                                                            data-toggle="modal" data-target="#modalEditSoal{{ $soalItem->id }}">
                                                            <i class="fas fa-edit mr-1"></i> Edit
                                                        </button>
                                                        <form action="{{ route($prefixRoute . '.soal.delete', $soalItem->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal No. {{ $soalItem->nomor }} ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-xs btn-outline-danger font-bold rounded-lg px-2.5 py-1 text-xs">
                                                                <i class="fas fa-trash-alt mr-1"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="card-body p-3.5">
                                                    <p class="font-bold text-slate-900 mb-3 text-sm whitespace-pre-line">
                                                        {{ $soalItem->soal }}
                                                    </p>
                                                    <div class="row g-2">
                                                        @foreach (['A' => $soalItem->opsi_a, 'B' => $soalItem->opsi_b, 'C' => $soalItem->opsi_c, 'D' => $soalItem->opsi_d] as $optKey => $optVal)
                                                            @php $isCorrect = $soalItem->kunci_jawaban === $optKey; @endphp
                                                            <div class="col-md-6 mb-2">
                                                                <div
                                                                    class="p-2.5 rounded-xl border text-xs font-semibold d-flex align-items-start {{ $isCorrect ? 'bg-emerald-50 border-emerald-300 text-emerald-950 font-bold' : 'bg-slate-50 border-slate-200 text-slate-700' }}">
                                                                    <span
                                                                        class="badge {{ $isCorrect ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700' }} mr-2 px-2 py-1 rounded-md text-xs font-bold">{{ $optKey }}</span>
                                                                    <span class="flex-1 mt-0.5">{{ $optVal }}</span>
                                                                    @if ($isCorrect)
                                                                        <i class="fas fa-check-circle text-emerald-600 ml-1.5 mt-0.5"></i>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Edit Soal -->
                                            <div class="modal fade" id="modalEditSoal{{ $soalItem->id }}" tabindex="-1" role="dialog"
                                                aria-labelledby="modalEditSoalLabel{{ $soalItem->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                                    <div class="modal-content border-0 shadow-lg rounded-2xl overflow-hidden">
                                                        <div class="modal-header bg-purple-900 text-white py-3 px-4">
                                                            <h5 class="modal-title font-bold text-base d-flex align-items-center"
                                                                id="modalEditSoalLabel{{ $soalItem->id }}">
                                                                <i class="fas fa-edit text-amber-300 mr-2"></i> Edit Soal No.
                                                                {{ $soalItem->nomor }}
                                                            </h5>
                                                            <button type="button" class="close text-white opacity-80 hover:opacity-100"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route($prefixRoute . '.soal.update', $soalItem->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body p-4 bg-slate-50/50">
                                                                <div class="row g-3">
                                                                    <div class="col-md-3 mb-3">
                                                                        <label
                                                                            class="form-label text-xs font-bold text-slate-700 uppercase">Nomor
                                                                            Soal</label>
                                                                        <input type="number" name="nomor"
                                                                            class="form-control rounded-xl font-semibold text-sm"
                                                                            value="{{ old('nomor', $soalItem->nomor) }}" min="1"
                                                                            required>
                                                                    </div>
                                                                    <div class="col-md-9 mb-3">
                                                                        <label
                                                                            class="form-label text-xs font-bold text-slate-700 uppercase mb-2 d-block">Kunci
                                                                            Jawaban</label>
                                                                        <div class="d-flex flex-wrap gap-2">
                                                                            @foreach (['A', 'B', 'C', 'D'] as $kunci)
                                                                                <div class="form-check form-check-inline m-0">
                                                                                    <input class="form-check-input d-none" type="radio"
                                                                                        name="kunci_jawaban"
                                                                                        id="kunci_{{ $soalItem->id }}_{{ $kunci }}"
                                                                                        value="{{ $kunci }}" {{ old('kunci_jawaban', $soalItem->kunci_jawaban) === $kunci ? 'checked' : '' }} required>
                                                                                    <label
                                                                                        class="btn {{ old('kunci_jawaban', $soalItem->kunci_jawaban) === $kunci ? 'btn-purple text-white shadow-sm' : 'btn-outline-purple' }} text-xs font-bold px-3 py-1.5 rounded-xl cursor-pointer"
                                                                                        for="kunci_{{ $soalItem->id }}_{{ $kunci }}">
                                                                                        Opsi {{ $kunci }}
                                                                                    </label>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-3">
                                                                        <label
                                                                            class="form-label text-xs font-bold text-slate-700 uppercase">Pertanyaan
                                                                            / Soal</label>
                                                                        <textarea name="soal"
                                                                            class="form-control rounded-xl font-medium text-sm" rows="3"
                                                                            required>{{ old('soal', $soalItem->soal) }}</textarea>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label
                                                                            class="form-label text-xs font-bold text-slate-700 uppercase">Pilihan
                                                                            A</label>
                                                                        <textarea name="opsi_a" class="form-control rounded-xl text-xs"
                                                                            rows="2"
                                                                            required>{{ old('opsi_a', $soalItem->opsi_a) }}</textarea>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label
                                                                            class="form-label text-xs font-bold text-slate-700 uppercase">Pilihan
                                                                            B</label>
                                                                        <textarea name="opsi_b" class="form-control rounded-xl text-xs"
                                                                            rows="2"
                                                                            required>{{ old('opsi_b', $soalItem->opsi_b) }}</textarea>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label
                                                                            class="form-label text-xs font-bold text-slate-700 uppercase">Pilihan
                                                                            C</label>
                                                                        <textarea name="opsi_c" class="form-control rounded-xl text-xs"
                                                                            rows="2"
                                                                            required>{{ old('opsi_c', $soalItem->opsi_c) }}</textarea>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label
                                                                            class="form-label text-xs font-bold text-slate-700 uppercase">Pilihan
                                                                            D</label>
                                                                        <textarea name="opsi_d" class="form-control rounded-xl text-xs"
                                                                            rows="2"
                                                                            required>{{ old('opsi_d', $soalItem->opsi_d) }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-white py-2.5 px-4 border-top">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-light font-bold rounded-xl px-3"
                                                                    data-dismiss="modal">Batal</button>
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-purple font-bold rounded-xl px-4">
                                                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Tambah Soal Manual -->
                <div class="modal fade" id="modalTambahSoal" tabindex="-1" role="dialog" aria-labelledby="modalTambahSoalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content border-0 shadow-lg rounded-2xl overflow-hidden">
                            <div class="modal-header bg-purple-900 text-white py-3 px-4">
                                <h5 class="modal-title font-bold text-base d-flex align-items-center" id="modalTambahSoalLabel">
                                    <i class="fas fa-plus-circle text-amber-300 mr-2"></i> Tambah Soal Baru
                                </h5>
                                <button type="button" class="close text-white opacity-80 hover:opacity-100" data-dismiss="modal"
                                    aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route($prefixRoute . '.soal.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="kategori_soal_id" value="{{ $selectedCategory->id }}">
                                <div class="modal-body p-4 bg-slate-50/50">
                                    <div class="row g-3">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label text-xs font-bold text-slate-700 uppercase">Nomor
                                                Soal</label>
                                            <input type="number" name="nomor"
                                                class="form-control rounded-xl font-semibold text-sm"
                                                value="{{ old('nomor', $selectedCategory->bankSoals->max('nomor') + 1 ?: 1) }}"
                                                min="1" required>
                                        </div>
                                        <div class="col-md-9 mb-3">
                                            <label
                                                class="form-label text-xs font-bold text-slate-700 uppercase mb-2 d-block">Kunci
                                                Jawaban</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach (['A', 'B', 'C', 'D'] as $kunci)
                                                    <div class="form-check form-check-inline m-0">
                                                        <input class="form-check-input d-none" type="radio" name="kunci_jawaban"
                                                            id="kunci_new_{{ $kunci }}" value="{{ $kunci }}" {{ old('kunci_jawaban', 'A') === $kunci ? 'checked' : '' }} required>
                                                        <label
                                                            class="btn {{ old('kunci_jawaban', 'A') === $kunci ? 'btn-purple text-white shadow-sm' : 'btn-outline-purple' }} text-xs font-bold px-3 py-1.5 rounded-xl cursor-pointer"
                                                            for="kunci_new_{{ $kunci }}">
                                                            Opsi {{ $kunci }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label text-xs font-bold text-slate-700 uppercase">Pertanyaan /
                                                Soal</label>
                                            <textarea name="soal" class="form-control rounded-xl font-medium text-sm" rows="3"
                                                placeholder="Tuliskan pertanyaan soal..." required>{{ old('soal') }}</textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-xs font-bold text-slate-700 uppercase">Pilihan
                                                A</label>
                                            <textarea name="opsi_a" class="form-control rounded-xl text-xs" rows="2"
                                                placeholder="Teks Jawaban A" required>{{ old('opsi_a') }}</textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-xs font-bold text-slate-700 uppercase">Pilihan
                                                B</label>
                                            <textarea name="opsi_b" class="form-control rounded-xl text-xs" rows="2"
                                                placeholder="Teks Jawaban B" required>{{ old('opsi_b') }}</textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-xs font-bold text-slate-700 uppercase">Pilihan
                                                C</label>
                                            <textarea name="opsi_c" class="form-control rounded-xl text-xs" rows="2"
                                                placeholder="Teks Jawaban C" required>{{ old('opsi_c') }}</textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-xs font-bold text-slate-700 uppercase">Pilihan
                                                D</label>
                                            <textarea name="opsi_d" class="form-control rounded-xl text-xs" rows="2"
                                                placeholder="Teks Jawaban D" required>{{ old('opsi_d') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer bg-white py-2.5 px-4 border-top">
                                    <button type="button" class="btn btn-sm btn-light font-bold rounded-xl px-3"
                                        data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-sm btn-purple font-bold rounded-xl px-4">
                                        <i class="fas fa-plus mr-1"></i> Simpan Soal
                                    </button>
                                </div>
                            </form>
                        </div>
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

    <!-- Modal Import Soal Excel / CSV -->
    @if ($selectedCategory)
        <div class="modal fade" id="modalImportExcel" tabindex="-1" role="dialog" aria-labelledby="modalImportExcelLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow-lg rounded-2xl overflow-hidden">
                    <div class="modal-header bg-emerald-800 text-white py-3 px-4">
                        <h5 class="modal-title font-bold text-base d-flex align-items-center" id="modalImportExcelLabel">
                            <i class="fas fa-file-excel text-amber-300 mr-2"></i> Import Soal dari File Excel / CSV
                        </h5>
                        <button type="button" class="close text-white opacity-80 hover:opacity-100" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route($prefixRoute . '.import.preview') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="kategori_soal_id" value="{{ $selectedCategory->id }}">
                        <div class="modal-body p-4 bg-slate-50/50">
                            <div
                                class="alert alert-emerald bg-emerald-50 border border-emerald-200 rounded-xl p-3 mb-3 text-xs text-emerald-950">
                                <strong class="font-bold d-block mb-1"><i class="fas fa-table mr-1"></i> Format Kolom Excel
                                    (.xlsx, .xls, .csv):</strong>
                                <ol class="mb-0 pl-3">
                                    <li>Kolom 1: <code>no</code> (Nomor Soal)</li>
                                    <li>Kolom 2: <code>soal</code> (Teks Pertanyaan Soal)</li>
                                    <li>Kolom 3: <code>jawaban_a</code> (Opsi Jawaban A)</li>
                                    <li>Kolom 4: <code>jawaban_b</code> (Opsi Jawaban B)</li>
                                    <li>Kolom 5: <code>jawaban_c</code> (Opsi Jawaban C)</li>
                                    <li>Kolom 6: <code>jawaban_d</code> (Opsi Jawaban D)</li>
                                    <li>Kolom 7: <code>kunci_jawaban</code> (A / B / C / D)</li>
                                </ol>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-xs font-bold text-slate-700 uppercase">Pilih File Excel /
                                    CSV</label>
                                <input type="file" name="file_excel"
                                    class="form-control-file border rounded-xl p-2 bg-white w-100 text-xs"
                                    accept=".xlsx,.xls,.csv,.txt" required>
                                <span class="text-xs text-slate-400 mt-1 d-block">Format didukung: .xlsx, .xls, .csv (Maksimal
                                    5MB)</span>
                            </div>
                            <div class="text-center pt-2">
                                <a href="{{ route($prefixRoute . '.template') }}"
                                    class="btn btn-xs btn-outline-info font-bold rounded-lg px-3 py-1.5">
                                    <i class="fas fa-download mr-1"></i> Download File Template Contoh CSV/Excel
                                </a>
                            </div>
                        </div>
                        <div class="modal-footer bg-white py-2.5 px-4 border-top">
                            <button type="button" class="btn btn-sm btn-light font-bold rounded-xl px-3"
                                data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-success font-bold rounded-xl px-4"
                                style="background-color: #059669; border-color: #059669;">
                                <i class="fas fa-upload mr-1"></i> Upload & Pratinjau
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