@extends('layout.app')

@section('title', 'Bank Soal · Paradise of Math')

@php
    $isGuruUser = auth()->guard('web')->check() && auth()->user()->isGuru();
    $prefixRoute = $isGuruUser ? 'guru.bank-soal' : 'guru.bank-soal';
    $dashRoute = $isGuruUser ? route('guru.dashboard') : route('guru.dashboard');
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
                        Kelola data soal secara terstruktur: Jenjang → Kelas → Semester/TKA → Mata Pelajaran → Soal.
                    </p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right text-sm bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ $dashRoute }}" class="text-purple-600 font-semibold">Dashboard</a></li>
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
                <div class="alert alert-success alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0 d-flex align-items-center" role="alert" style="background-color: #ecfdf5; color: #065f46; border-left: 4px solid #10b981;">
                    <i class="fas fa-check-circle fa-lg mr-3 text-emerald-500"></i>
                    <div>
                        <strong class="font-bold">Berhasil!</strong> {{ session('success') }}
                    </div>
                    <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" class="text-emerald-700">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444;">
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
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-purple-100 text-purple-900 d-flex align-items-center justify-content-center mr-3 shadow-xs" style="width: 38px; height: 38px;">
                            <i class="fas fa-filter text-purple-700"></i>
                        </div>
                        <div>
                            <h5 class="card-title font-bold text-purple-950 mb-0 text-base">Filter Bank Soal &amp; Latihan</h5>
                            <span class="text-xs text-slate-500">Pilih Jenjang, Kelas, Semester, dan Mata Pelajaran untuk memfilter soal</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($jenjang && $kelas && $sub)
                            <button type="button" class="btn btn-sm btn-outline-purple font-bold rounded-xl px-3 py-1.5 text-xs transition-all shadow-xs" data-toggle="collapse" data-target="#panelListMapel" aria-expanded="false" aria-controls="panelListMapel">
                                <i class="fas fa-list mr-1"></i> List Mapel
                            </button>
                            <button type="button" class="btn btn-purple btn-sm shadow-sm rounded-xl font-bold px-3.5 py-2 text-xs" data-toggle="modal" data-target="#modalTambahKategori">
                                <i class="fas fa-plus-circle mr-1.5"></i> Tambah Mapel Baru
                            </button>
                        @endif
                        @if($jenjang || $kelas || $sub || $mapel)
                            <a href="{{ route($prefixRoute . '.index') }}" class="btn btn-sm btn-outline-danger font-bold rounded-xl px-3 py-1.5 text-xs transition-all shadow-xs">
                                <i class="fas fa-undo mr-1"></i> Reset Filter
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4 bg-purple-50/30">
                    <form id="filterBankSoalFormGuru" action="{{ route($prefixRoute . '.index') }}" method="GET">
                        <div class="row g-3">

                            <!-- Dropdown 1: Jenjang -->
                            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                                <label class="form-label text-xs font-bold text-purple-950 uppercase tracking-wider mb-1.5 d-flex align-items-center">
                                    <i class="fas fa-graduation-cap text-purple-600 mr-1.5"></i> 1. Jenjang Pendidikan
                                </label>
                                <select name="jenjang" id="filterJenjang" class="form-control custom-select rounded-xl font-semibold text-sm border-purple-200 focus:border-purple-500 shadow-xs" onchange="handleJenjangChange(this)">
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
                                <select name="kelas" id="filterKelas" class="form-control custom-select rounded-xl font-semibold text-sm border-purple-200 focus:border-purple-500 shadow-xs" {{ !$jenjang ? 'disabled' : '' }} onchange="handleKelasChange(this)">
                                    <option value="">-- Pilih Kelas --</option>
                                    @if ($jenjang)
                                        @foreach ($availableClasses as $cls)
                                            <option value="{{ $cls }}" {{ (string)$kelas === (string)$cls ? 'selected' : '' }}>
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
                                <select name="sub_kategori" id="filterSub" class="form-control custom-select rounded-xl font-semibold text-sm border-purple-200 focus:border-purple-500 shadow-xs" {{ !($jenjang && $kelas) ? 'disabled' : '' }} onchange="handleSubChange(this)">
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
                                <select name="mapel" id="filterMapel" class="form-control custom-select rounded-xl font-semibold text-sm border-purple-200 focus:border-purple-500 shadow-xs" {{ !($jenjang && $kelas && $sub) ? 'disabled' : '' }} onchange="this.form.submit()">
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @if ($jenjang && $kelas && $sub)
                                        @foreach ($mapelOptions as $mOption)
                                            <option value="{{ $mOption->nama_mapel }}" {{ $mapel === $mOption->nama_mapel ? 'selected' : '' }}>
                                                {{ $mOption->nama_mapel }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                        </div>
                    </form>

                    <!-- Active Filter Summary Badges -->
                    @if($jenjang || $kelas || $sub || $selectedCategory)
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

                            @if($selectedCategory)
                                <span class="badge bg-purple-900 text-white font-bold px-3 py-1.5 rounded-lg text-xs d-inline-flex align-items-center shadow-xs">
                                    <i class="fas fa-book text-amber-300 mr-1.5"></i> {{ $selectedCategory->nama_kategori }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            @if($jenjang && $kelas && $sub)
                @include('guru.listMapel', ['categories' => $categories, 'jenjang' => $jenjang, 'kelas' => $kelas, 'sub' => $sub, 'mapel' => $mapel, 'prefixRoute' => $prefixRoute])
            @endif

            <!-- Notice if filter is incomplete -->
            @if (!($jenjang && $kelas && $sub))
                <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                    <div class="card-body p-5 text-center">
                        <div class="rounded-circle bg-purple-50 text-purple-600 d-inline-flex align-items-center justify-content-center mb-3 shadow-xs" style="width: 64px; height: 64px;">
                            <i class="fas fa-filter fa-2x"></i>
                        </div>
                        <h5 class="font-bold text-purple-950 mb-2 text-lg">Silakan Lengkapi Filter Soal</h5>
                        <p class="text-slate-500 text-sm max-w-md mx-auto mb-0">
                            Pilih <span class="font-bold text-purple-900">Jenjang</span>, <span class="font-bold text-purple-900">Kelas</span>, <span class="font-bold text-purple-900">Semester/TKA</span>, dan <span class="font-bold text-purple-900">Mata Pelajaran</span> pada filter dropdown di atas untuk mengelola soal.
                        </p>
                    </div>
                </div>
            @elseif ($jenjang && $kelas && $sub && !$selectedCategory)
                {{-- Show existing mapels (categories) that already have questions --}}
                @if ($categories->isNotEmpty())
                    <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                        <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-emerald-100 text-emerald-700 d-flex align-items-center justify-content-center mr-3 shadow-xs" style="width: 38px; height: 38px;">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <div>
                                    <h5 class="card-title font-bold text-purple-950 mb-0 text-base">Mata Pelajaran yang Sudah Memiliki Soal</h5>
                                    <span class="text-xs text-slate-500">Klik salah satu untuk melihat dan mengedit soal</span>
                                </div>
                            </div>
                            <span class="badge bg-purple-100 text-purple-900 font-bold px-3 py-1.5 rounded-lg text-xs">
                                {{ $categories->count() }} Mapel
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                @foreach ($categories as $cat)
                                    @php
                                        $catUrl = route($prefixRoute . '.index', [
                                            'jenjang' => $jenjang,
                                            'kelas' => $kelas,
                                            'sub_kategori' => $sub,
                                            'mapel' => $cat->nama_kategori,
                                            'kategori_id' => $cat->id,
                                        ]);
                                    @endphp
                                    <div class="col-md-4 col-sm-6 mb-2">
                                        <a href="{{ $catUrl }}" class="card border-2 border-slate-200 rounded-xl text-decoration-none transition-all hover:border-purple-400 hover:shadow-md h-100 overflow-hidden">
                                            <div class="card-body p-3.5 d-flex align-items-center">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 shadow-xs bg-purple-100" style="width: 44px; height: 44px; flex-shrink: 0;">
                                                    <i class="fas fa-book text-purple-700 fa-lg"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <span class="d-block text-sm font-extrabold text-purple-950 leading-tight mb-0.5">{{ $cat->nama_kategori }}</span>
                                                    <span class="d-block text-xs text-slate-500">
                                                        <i class="fas fa-question-circle text-purple-400 mr-1"></i>
                                                        {{ $cat->bank_soals_count }} soal
                                                    </span>
                                                </div>
                                                <i class="fas fa-chevron-right text-slate-400 ml-2"></i>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Prompt to pick from dropdown --}}
                <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                    <div class="card-body p-4 text-center">
                        <div class="rounded-circle bg-purple-50 text-purple-600 d-inline-flex align-items-center justify-content-center mb-3 shadow-xs" style="width: 52px; height: 52px;">
                            <i class="fas fa-hand-pointer fa-lg"></i>
                        </div>
                        <h6 class="font-bold text-purple-950 mb-1">Pilih Mata Pelajaran</h6>
                        <p class="text-slate-500 text-xs max-w-md mx-auto mb-0">
                            Pilih mata pelajaran pada dropdown <strong>"4. Mata Pelajaran"</strong> di atas, atau klik salah satu mapel yang sudah memiliki soal di atas.
                        </p>
                    </div>
                </div>
            @endif

            <!-- ════════════════════════════════════════════════════════════ -->
            <!-- LANGKAH 5: FORM INPUT SOAL & DAFTAR SOAL (jika kategori dipilih) -->
            <!-- ════════════════════════════════════════════════════════════ -->
            @if ($selectedCategory)

                <!-- Active Category Detail Header Card -->
                <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-gradient-to-r from-purple-900 to-indigo-900 text-white">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-purple-500/30 text-purple-200 border border-purple-400/30 px-2.5 py-1 rounded-md text-xs font-bold uppercase">
                                        Jenjang {{ $selectedCategory->jenjang }}
                                    </span>
                                    <span class="badge bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 px-2.5 py-1 rounded-md text-xs font-bold">
                                        Kelas {{ $selectedCategory->kelas }}
                                    </span>
                                    <span class="badge bg-emerald-500/30 text-emerald-200 border border-emerald-400/30 px-2.5 py-1 rounded-md text-xs font-bold">
                                        {{ $selectedCategory->sub_kategori }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-xl mb-1">{{ $selectedCategory->nama_kategori }}</h3>
                                <p class="text-sm text-purple-200 mb-0">
                                    {{ $selectedCategory->deskripsi ?: 'Tidak ada judul soal.' }}
                                </p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-xs btn-warning font-bold rounded-lg px-2.5 py-1.5" data-toggle="modal" data-target="#modalEditKategori{{ $selectedCategory->id }}">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                <form action="{{ route($prefixRoute . '.kategori.delete', $selectedCategory->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini beserta seluruh soal di dalamnya?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline-light font-bold rounded-lg px-2.5 py-1.5">
                                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @if (session('import_preview_soals'))
                    @php
                        $previewItems = session('import_preview_soals');
                    @endphp
                    <!-- CARD PRATINJAU / PREVIEW IMPORT SOAL -->
                    <div class="card border-2 border-purple-600 shadow-lg rounded-2xl mb-5 bg-white overflow-hidden" id="previewSoalCard">
                        <div class="card-header bg-purple-900 text-white py-3 px-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title font-bold mb-0 text-base d-flex align-items-center text-white">
                                    <i class="fas fa-eye text-amber-300 mr-2"></i> Pratinjau Soal dari Excel ({{ count($previewItems) }} Soal)
                                </h5>
                                <span class="text-xs text-purple-200">Periksa seluruh isi soal di bawah ini sebelum disimpan secara permanen ke Database.</span>
                            </div>
                            <span class="badge bg-amber-400 text-slate-900 font-extrabold px-3 py-1.5 rounded-full text-xs">
                                <i class="fas fa-exclamation-circle mr-1"></i> Belum Masuk Database
                            </span>
                        </div>
                        <div class="card-body p-4 bg-purple-50/20" style="max-height: 480px; overflow-y: auto;">
                            <div class="space-y-3">
                                @foreach ($previewItems as $pIndex => $pItem)
                                    <div class="card border border-slate-200 rounded-xl p-3 bg-white shadow-xs mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                            <span class="badge bg-purple-900 text-white font-bold text-xs px-2.5 py-1 rounded-md">
                                                Soal No. {{ $pItem['nomor'] }}
                                            </span>
                                            <span class="badge bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold text-xs px-2.5 py-0.5 rounded-md">
                                                Kunci: {{ $pItem['kunci_jawaban'] }}
                                            </span>
                                        </div>
                                        <p class="font-bold text-slate-900 text-sm mb-2.5 whitespace-pre-line">{{ $pItem['soal'] }}</p>
                                        <div class="row g-2 text-xs">
                                            @foreach (['A' => $pItem['opsi_a'], 'B' => $pItem['opsi_b'], 'C' => $pItem['opsi_c'], 'D' => $pItem['opsi_d']] as $pKey => $pVal)
                                                @php $isKunci = $pItem['kunci_jawaban'] === $pKey; @endphp
                                                <div class="col-md-6 mb-1">
                                                    <div class="p-2 rounded-lg border {{ $isKunci ? 'bg-emerald-50 border-emerald-300 text-emerald-950 font-bold' : 'bg-slate-50 border-slate-200 text-slate-700' }} d-flex align-items-center">
                                                        <span class="badge {{ $isKunci ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700' }} mr-2 px-2 py-0.5 rounded text-xs font-bold">{{ $pKey }}</span>
                                                        <span class="flex-1">{{ $pVal }}</span>
                                                        @if($isKunci) <i class="fas fa-check-circle text-emerald-600 ml-1.5"></i> @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer bg-white py-3 px-4 border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                            <div class="text-xs text-slate-500 font-semibold">
                                <i class="fas fa-info-circle text-purple-600 mr-1"></i> Klik tombol di sebelah kanan untuk menyetujui dan menyimpan ke database.
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                                <form action="{{ route('guru.bank-soal.import.cancel') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger font-bold rounded-xl text-xs px-3.5 py-2">
                                        <i class="fas fa-times mr-1"></i> Batal Import
                                    </button>
                                </form>
                                <form action="{{ route('guru.bank-soal.import.confirm') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-success font-weight-bold rounded-xl text-xs px-4 py-2 shadow-md" style="background-color: #059669; border-color: #059669; color: #ffffff;">
                                        <i class="fas fa-check-circle mr-1.5"></i> Konfirmasi &amp; Simpan {{ count($previewItems) }} Soal ke Database
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <!-- LEFT COLUMN: IMPORT EXCEL & FORM MANUAL -->
                    <div class="col-lg-5 mb-4">

                        <!-- CARD IMPORT EXCEL -->
                        <div class="card border-0 shadow-sm rounded-2xl bg-white mb-4 overflow-hidden" style="border: 1px solid #10b981;">
                            <div class="card-header py-3 px-4 d-flex justify-content-between align-items-center" style="background-color: #047857; color: #ffffff;">
                                <h5 class="card-title font-weight-bold mb-0 text-sm d-flex align-items-center text-white">
                                    <i class="fas fa-file-excel mr-2 text-warning"></i> Import Soal via Excel / CSV
                                </h5>
                                <a href="{{ route('guru.bank-soal.template') }}" class="btn btn-warning btn-xs font-weight-bold rounded-lg px-2.5 py-1.5 text-xs shadow-sm" style="background-color: #f59e0b; border-color: #d97706; color: #000000;">
                                    <i class="fas fa-download mr-1"></i> Template Excel
                                </a>
                            </div>
                            <div class="card-body p-3.5" style="background-color: #f0fdf4;">
                                <div class="p-2.5 rounded-xl bg-white border mb-3 text-xs" style="border-color: #a7f3d0;">
                                    <span class="font-weight-bold d-block mb-1 text-emerald-900" style="color: #065f46;"><i class="fas fa-info-circle mr-1" style="color: #059669;"></i> Format Struktur Excel / CSV:</span>
                                    <ol class="mb-0 pl-3 text-slate-600" style="font-size: 11px; line-height: 1.6;">
                                        <li>Kolom 1: <strong>No</strong> (Nomor urut)</li>
                                        <li>Kolom 2: <strong>Soal</strong> (Isi pertanyaan)</li>
                                        <li>Kolom 3: <strong>Jawaban A</strong></li>
                                        <li>Kolom 4: <strong>Jawaban B</strong></li>
                                        <li>Kolom 5: <strong>Jawaban C</strong></li>
                                        <li>Kolom 6: <strong>Jawaban D</strong></li>
                                        <li>Kolom 7: <strong>Kunci Jawaban</strong> (A / B / C / D)</li>
                                    </ol>
                                </div>
                                
                                <form action="{{ route('guru.bank-soal.import.preview') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="kategori_soal_id" value="{{ $selectedCategory->id }}">
                                    
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-slate-700 uppercase tracking-wider mb-1 d-block">Pilih File Excel / CSV <span class="text-danger">*</span></label>
                                        <div class="custom-file">
                                            <input type="file" name="file_excel" class="custom-file-input text-xs" id="customFileExcel" accept=".xlsx,.xls,.csv" required onchange="document.getElementById('fileLabelExcel').innerText = this.files[0] ? this.files[0].name : 'Pilih file .xlsx / .csv ...'">
                                            <label class="custom-file-label text-xs font-semibold text-slate-500 overflow-hidden" for="customFileExcel" id="fileLabelExcel">Pilih file .xlsx / .csv ...</label>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-success btn-block font-weight-bold py-2.5 rounded-xl text-xs shadow-sm" style="background-color: #059669; border-color: #059669; color: #ffffff;">
                                        <i class="fas fa-eye mr-1.5"></i> Upload &amp; Pratinjau Soal
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- FORM TAMBAH SOAL MANUAL -->
                        <div class="card border-0 shadow-sm rounded-2xl bg-white sticky-top" style="top: 20px; z-index: 10;">
                            <div class="card-header bg-white py-3 px-4 border-bottom">
                                <h5 class="card-title font-bold text-purple-950 mb-0 d-flex align-items-center text-base">
                                    <i class="fas fa-plus-circle text-purple-600 mr-2"></i> Form Input Soal Baru
                                </h5>
                                <p class="text-xs text-slate-400 mb-0 mt-0.5">Masukkan pertanyaan, 4 opsi jawaban, dan kunci jawaban secara manual.</p>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route($prefixRoute . '.soal.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="kategori_soal_id" value="{{ $selectedCategory->id }}">

                                    <!-- Nomor Soal -->
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Nomor Urut Soal <span class="text-danger">*</span></label>
                                        @php
                                            $nextNo = ($selectedCategory->bankSoals->max('nomor') ?? 0) + 1;
                                        @endphp
                                        <input type="number" name="nomor" value="{{ old('nomor', $nextNo) }}" min="1" class="form-control rounded-xl border-slate-300 font-bold" required>
                                    </div>

                                    <!-- Pertanyaan -->
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Isi Pertanyaan <span class="text-danger">*</span></label>
                                        <textarea name="soal" rows="4" class="form-control rounded-xl border-slate-300 text-sm" placeholder="Tuliskan pertanyaan / isi soal..." required>{{ old('soal') }}</textarea>
                                    </div>

                                    <!-- Opsi A - D -->
                                    <div class="space-y-2 mb-3">
                                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 d-block">Pilihan Jawaban (A - D) <span class="text-danger">*</span></label>

                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-purple-100 text-purple-900 font-bold rounded-l-xl border-slate-300">A</span>
                                            </div>
                                            <input type="text" name="opsi_a" value="{{ old('opsi_a') }}" class="form-control border-slate-300 text-sm" placeholder="Jawaban A" required>
                                        </div>

                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-purple-100 text-purple-900 font-bold rounded-l-xl border-slate-300">B</span>
                                            </div>
                                            <input type="text" name="opsi_b" value="{{ old('opsi_b') }}" class="form-control border-slate-300 text-sm" placeholder="Jawaban B" required>
                                        </div>

                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-purple-100 text-purple-900 font-bold rounded-l-xl border-slate-300">C</span>
                                            </div>
                                            <input type="text" name="opsi_c" value="{{ old('opsi_c') }}" class="form-control border-slate-300 text-sm" placeholder="Jawaban C" required>
                                        </div>

                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-purple-100 text-purple-900 font-bold rounded-l-xl border-slate-300">D</span>
                                            </div>
                                            <input type="text" name="opsi_d" value="{{ old('opsi_d') }}" class="form-control border-slate-300 text-sm" placeholder="Jawaban D" required>
                                        </div>
                                    </div>

                                    <!-- Kunci Jawaban -->
                                    <div class="form-group mb-4">
                                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 d-block">Kunci Jawaban Benar <span class="text-danger">*</span></label>
                                        <div class="row text-center">
                                            @foreach (['A', 'B', 'C', 'D'] as $key)
                                                <div class="col-3">
                                                    <label class="btn btn-outline-purple btn-block py-2 font-extrabold rounded-xl mb-0 cursor-pointer shadow-xs transition-all">
                                                        <input type="radio" name="kunci_jawaban" value="{{ $key }}" {{ old('kunci_jawaban', 'A') === $key ? 'checked' : '' }} required class="d-none">
                                                        {{ $key }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-purple btn-block font-bold py-2.5 rounded-xl shadow-md transition-all">
                                        <i class="fas fa-save mr-1.5"></i> Simpan Soal ke Database
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- DAFTAR SOAL -->
                    <div class="col-lg-7 mb-4">
                        <div class="card border-0 shadow-sm rounded-2xl bg-white">
                            <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="card-title font-bold text-purple-950 mb-0 d-flex align-items-center text-base">
                                    <i class="fas fa-list-ol text-purple-600 mr-2"></i> Daftar Soal Tersimpan
                                </h5>
                                <span class="badge bg-purple-100 text-purple-900 font-bold px-3 py-1 rounded-full text-xs">
                                    {{ $selectedCategory->bankSoals->count() }} Soal
                                </span>
                            </div>
                            <div class="card-body p-4">
                                @if ($selectedCategory->bankSoals->isEmpty())
                                    <div class="text-center py-5">
                                        <i class="fas fa-question-circle text-slate-300 fa-3x mb-3"></i>
                                        <p class="text-slate-500 font-semibold mb-0">Belum ada soal.</p>
                                        <p class="text-xs text-slate-400">Gunakan form di sebelah kiri untuk menambahkan soal pertama.</p>
                                    </div>
                                @else
                                    <div class="space-y-4">
                                        @foreach ($selectedCategory->bankSoals as $soalItem)
                                            <div class="card border border-slate-200 rounded-xl shadow-xs overflow-hidden transition-all hover:border-purple-300 mb-3">
                                                <div class="card-header bg-slate-50 py-2.5 px-3.5 d-flex justify-content-between align-items-center border-bottom">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-purple-900 text-white font-extrabold px-2.5 py-1 rounded-lg text-xs">
                                                            No. {{ $soalItem->nomor }}
                                                        </span>
                                                        <span class="badge bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded text-[11px] border border-emerald-200">
                                                            Kunci: {{ $soalItem->kunci_jawaban }}
                                                        </span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <button type="button" class="btn btn-xs btn-outline-info rounded-lg font-bold px-2 py-1" data-toggle="modal" data-target="#modalEditSoal{{ $soalItem->id }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <form action="{{ route($prefixRoute . '.soal.delete', $soalItem->id) }}" method="POST" onsubmit="return confirm('Hapus soal no. {{ $soalItem->nomor }}?');" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-xs btn-outline-danger rounded-lg font-bold px-2 py-1">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="card-body p-3.5">
                                                    <p class="font-bold text-slate-900 mb-3 text-sm whitespace-pre-line">{{ $soalItem->soal }}</p>
                                                    <div class="row g-2">
                                                        @foreach (['A' => $soalItem->opsi_a, 'B' => $soalItem->opsi_b, 'C' => $soalItem->opsi_c, 'D' => $soalItem->opsi_d] as $optKey => $optVal)
                                                            @php
                                                                $isCorrect = $soalItem->kunci_jawaban === $optKey;
                                                            @endphp
                                                            <div class="col-md-6 mb-2">
                                                                <div class="p-2.5 rounded-xl border text-xs font-semibold d-flex align-items-start {{ $isCorrect ? 'bg-emerald-50 border-emerald-300 text-emerald-950 font-bold' : 'bg-slate-50 border-slate-200 text-slate-700' }}">
                                                                    <span class="badge {{ $isCorrect ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700' }} mr-2 px-2 py-1 rounded-md text-xs font-bold">
                                                                        {{ $optKey }}
                                                                    </span>
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

                                            <!-- MODAL EDIT SOAL -->
                                            <div class="modal fade" id="modalEditSoal{{ $soalItem->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                                    <div class="modal-content border-0 shadow-lg rounded-2xl">
                                                        <div class="modal-header bg-purple-900 text-white rounded-t-2xl py-3 px-4">
                                                            <h5 class="modal-title font-bold text-base"><i class="fas fa-edit mr-2"></i> Edit Soal No. {{ $soalItem->nomor }}</h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route($prefixRoute . '.soal.update', $soalItem->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body p-4 text-left">
                                                                <div class="form-group mb-3">
                                                                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Nomor Urut Soal <span class="text-danger">*</span></label>
                                                                    <input type="number" name="nomor" value="{{ old('nomor', $soalItem->nomor) }}" min="1" class="form-control rounded-xl font-bold" required>
                                                                </div>

                                                                <div class="form-group mb-3">
                                                                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Isi Pertanyaan <span class="text-danger">*</span></label>
                                                                    <textarea name="soal" rows="4" class="form-control rounded-xl text-sm" required>{{ old('soal', $soalItem->soal) }}</textarea>
                                                                </div>

                                                                <div class="space-y-2 mb-3">
                                                                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 d-block">Pilihan Jawaban (A - D) <span class="text-danger">*</span></label>

                                                                    <div class="input-group mb-2">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text bg-purple-100 text-purple-900 font-bold">A</span>
                                                                        </div>
                                                                        <input type="text" name="opsi_a" value="{{ old('opsi_a', $soalItem->opsi_a) }}" class="form-control text-sm" required>
                                                                    </div>

                                                                    <div class="input-group mb-2">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text bg-purple-100 text-purple-900 font-bold">B</span>
                                                                        </div>
                                                                        <input type="text" name="opsi_b" value="{{ old('opsi_b', $soalItem->opsi_b) }}" class="form-control text-sm" required>
                                                                    </div>

                                                                    <div class="input-group mb-2">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text bg-purple-100 text-purple-900 font-bold">C</span>
                                                                        </div>
                                                                        <input type="text" name="opsi_c" value="{{ old('opsi_c', $soalItem->opsi_c) }}" class="form-control text-sm" required>
                                                                    </div>

                                                                    <div class="input-group mb-2">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text bg-purple-100 text-purple-900 font-bold">D</span>
                                                                        </div>
                                                                        <input type="text" name="opsi_d" value="{{ old('opsi_d', $soalItem->opsi_d) }}" class="form-control text-sm" required>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group mb-3">
                                                                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 d-block">Kunci Jawaban Benar <span class="text-danger">*</span></label>
                                                                    <div class="row text-center">
                                                                        @foreach (['A', 'B', 'C', 'D'] as $key)
                                                                            <div class="col-3">
                                                                                <label class="btn btn-outline-purple btn-block py-2 font-extrabold rounded-xl mb-0 cursor-pointer shadow-xs">
                                                                                    <input type="radio" name="kunci_jawaban" value="{{ $key }}" {{ old('kunci_jawaban', $soalItem->kunci_jawaban) === $key ? 'checked' : '' }} required class="d-none">
                                                                                    {{ $key }}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-slate-50 rounded-b-2xl py-2.5 px-4">
                                                                <button type="button" class="btn btn-light font-bold rounded-xl text-xs px-3 py-2" data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-purple font-bold rounded-xl text-xs px-4 py-2">Simpan Perubahan</button>
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

                <!-- MODAL EDIT KATEGORI -->
                <div class="modal fade" id="modalEditKategori{{ $selectedCategory->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content border-0 shadow-lg rounded-2xl">
                            <div class="modal-header bg-purple-900 text-white rounded-t-2xl py-3 px-4">
                                <h5 class="modal-title font-bold text-base"><i class="fas fa-edit mr-2"></i> Edit Mata Pelajaran</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route($prefixRoute . '.kategori.update', $selectedCategory->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body p-4 text-left">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $selectedCategory->nama_kategori) }}" class="form-control rounded-xl font-bold" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Jenjang <span class="text-danger">*</span></label>
                                        <select name="jenjang" class="form-control rounded-xl font-bold" required>
                                            <option value="SD" {{ $selectedCategory->jenjang == 'SD' ? 'selected' : '' }}>SD</option>
                                            <option value="SMP" {{ $selectedCategory->jenjang == 'SMP' ? 'selected' : '' }}>SMP</option>
                                            <option value="SMA" {{ $selectedCategory->jenjang == 'SMA' ? 'selected' : '' }}>SMA</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Kelas <span class="text-danger">*</span></label>
                                        <input type="number" name="kelas" value="{{ old('kelas', $selectedCategory->kelas) }}" min="1" max="6" class="form-control rounded-xl font-bold" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Sub-Kategori <span class="text-danger">*</span></label>
                                        <input type="text" name="sub_kategori" value="{{ old('sub_kategori', $selectedCategory->sub_kategori) }}" class="form-control rounded-xl text-sm" required placeholder="Semester 1, Semester 2, TKA">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Judul Soal <span class="text-danger">*</span></label>
                                        <input type="text" name="deskripsi" value="{{ old('deskripsi', $selectedCategory->deskripsi) }}" class="form-control rounded-xl text-sm" required maxlength="255" placeholder="Contoh: Pecahan Senilai">
                                    </div>
                                </div>
                                <div class="modal-footer bg-slate-50 rounded-b-2xl py-2.5 px-4">
                                    <button type="button" class="btn btn-light font-bold rounded-xl text-xs px-3 py-2" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-purple font-bold rounded-xl text-xs px-4 py-2">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- ════════════════ MODAL TAMBAH KATEGORI SOAL ════════════════ -->
    <div class="modal fade" id="modalTambahKategori" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg rounded-2xl">
                <div class="modal-header bg-purple-900 text-white rounded-t-2xl py-3 px-4">
                    <h5 class="modal-title font-bold text-base"><i class="fas fa-folder-plus mr-2"></i> Tambah Mata Pelajaran Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route($prefixRoute . '.kategori.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4 text-left">

                        <!-- Jenjang (Readonly + Hidden Input) -->
                        <div class="form-group mb-3">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Jenjang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-xl font-bold bg-slate-100" value="{{ $jenjang }}" readonly>
                            <input type="hidden" name="jenjang" value="{{ $jenjang }}">
                        </div>

                        <!-- Kelas (Readonly + Hidden Input) -->
                        <div class="form-group mb-3">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Kelas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-xl font-bold bg-slate-100" value="Kelas {{ $kelas }}" readonly>
                            <input type="hidden" name="kelas" value="{{ $kelas }}">
                        </div>

                        <!-- Sub-Kategori (Readonly + Hidden Input) -->
                        <div class="form-group mb-3">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Sub-Kategori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-xl text-sm bg-slate-100" value="{{ $sub }}" readonly>
                            <input type="hidden" name="sub_kategori" value="{{ $sub }}">
                            <small class="text-muted text-[11px]">Nilai ini mengikuti pilihan sebelumnya dan tidak dapat diubah.</small>
                        </div>

                        <!-- Nama Mata Pelajaran (bisa diisi user) -->
                        <div class="form-group mb-3">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                            <select name="nama_kategori" class="form-control rounded-xl font-bold" required>
                                <option value="" disabled {{ old('nama_kategori') ? '' : 'selected' }}>-- Pilih Mata Pelajaran --</option>
                                @foreach ($mapelOptions as $mapel)
                                    <option value="{{ $mapel->nama_mapel }}" {{ old('nama_kategori') === $mapel->nama_mapel ? 'selected' : '' }}>
                                        {{ $mapel->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($mapelOptions->isEmpty())
                                <small class="text-danger d-block mt-1">Tidak ada mapel tersedia untuk kombinasi ini.</small>
                            @endif
                        </div>

                        <!-- Judul Soal (wajib) -->
                        <div class="form-group mb-3">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Judul Soal <span class="text-danger">*</span></label>
                            <input type="text" name="deskripsi" value="{{ old('deskripsi') }}" class="form-control rounded-xl text-sm" placeholder="Contoh: Pecahan Senilai" required maxlength="255">
                        </div>
                    </div>
                    <div class="modal-footer bg-slate-50 rounded-b-2xl py-2.5 px-4">
                        <button type="button" class="btn btn-light font-bold rounded-xl text-xs px-3 py-2" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-purple font-bold rounded-xl text-xs px-4 py-2">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Custom styling -->
    <style>
        .btn-purple {
            background-color: #581c87;
            color: #ffffff;
            border: none;
        }
        .btn-purple:hover, .btn-purple:focus {
            background-color: #3b0764;
            color: #ffffff;
        }
        .btn-outline-purple {
            color: #581c87;
            border-color: #c084fc;
            background-color: #f3e8ff;
        }
        .btn-outline-purple:hover, .btn-outline-purple:focus {
            background-color: #581c87;
            color: #ffffff;
            border-color: #581c87;
        }
        label.btn-outline-purple input[type="radio"]:checked + span,
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

        document.addEventListener("DOMContentLoaded", function() {
            // Radio button sync for Kunci Jawaban
            const radioButtons = document.querySelectorAll('input[name="kunci_jawaban"]');
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    const groupName = this.getAttribute('name');
                    const form = this.closest('form');
                    form.querySelectorAll(`input[name="${groupName}"]`).forEach(r => {
                        const parentLabel = r.closest('label');
                        if (r.checked) {
                            parentLabel.classList.add('bg-purple-900', 'text-white');
                            parentLabel.classList.remove('bg-purple-100', 'text-purple-900');
                        } else {
                            parentLabel.classList.remove('bg-purple-900', 'text-white');
                        }
                    });
                });
            });
        });
    </script>
@endsection