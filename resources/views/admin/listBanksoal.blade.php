@extends('layout.app')

@section('title', 'Bank Soal · Paradise of Math')

@php
    $prefixRoute = 'admin.bank-soal';
    $dashRoute = route('admin.dashboard');
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

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444;">
                    <strong class="font-bold"><i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
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
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-purple-100 text-purple-900 d-flex align-items-center justify-content-center mr-3 shadow-xs" style="width: 38px; height: 38px;">
                            <i class="fas fa-filter text-purple-700"></i>
                        </div>
                        <div>
                            <h5 class="card-title font-bold text-purple-950 mb-0 text-base">Filter Bank Soal &amp; Latihan</h5>
                            <span class="text-xs text-slate-500">Pilih Jenjang, Kelas, Semester, dan Mata Pelajaran untuk memfilter soal</span>
                        </div>
                    </div>
                    @if($jenjang || $kelas || $sub || $mapel)
                        <a href="{{ route($prefixRoute . '.index') }}" class="btn btn-sm btn-outline-danger font-bold rounded-xl px-3 py-1.5 text-xs transition-all shadow-xs">
                            <i class="fas fa-undo mr-1"></i> Reset Filter
                        </a>
                    @endif
                </div>

                <div class="card-body p-4 bg-purple-50/30">
                    <form id="filterBankSoalForm" action="{{ route($prefixRoute . '.index') }}" method="GET">
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

            <!-- Notice if filter is incomplete -->
            @if (!($jenjang && $kelas && $sub && $mapel))
                <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                    <div class="card-body p-5 text-center">
                        <div class="rounded-circle bg-purple-50 text-purple-600 d-inline-flex align-items-center justify-content-center mb-3 shadow-xs" style="width: 64px; height: 64px;">
                            <i class="fas fa-filter fa-2x"></i>
                        </div>
                        <h5 class="font-bold text-purple-950 mb-2 text-lg">Silakan Lengkapi Filter Soal</h5>
                        <p class="text-slate-500 text-sm max-w-md mx-auto mb-0">
                            Pilih <span class="font-bold text-purple-900">Jenjang</span>, <span class="font-bold text-purple-900">Kelas</span>, <span class="font-bold text-purple-900">Semester/TKA</span>, dan <span class="font-bold text-purple-900">Mata Pelajaran</span> pada filter dropdown di atas untuk menampilkan daftar bank soal.
                        </p>
                    </div>
                </div>
            @endif

            <!-- ════════════════════════════════════════════════════════════ -->
            <!-- LANGKAH 5: DAFTAR JUDUL SOAL (DESKRIPSI KATEGORI)          -->
            <!-- ════════════════════════════════════════════════════════════ -->
            @if ($jenjang && $kelas && $sub && $mapel)
                <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                    <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center">
                        <span class="badge bg-purple-900 text-white rounded-circle mr-2.5 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 12px;">5</span>
                        <h5 class="card-title font-bold text-purple-950 mb-0 text-base">
                            Daftar Soal {{ $mapel }}
                            <span class="badge bg-purple-100 text-purple-900 font-bold ml-2 px-2.5 py-0.5 text-xs">{{ $jenjang }} - Kelas {{ $kelas }} - {{ $sub }}</span>
                        </h5>
                        @if($kategoriId)
                            <span class="badge bg-purple-100 text-purple-900 font-bold ml-3 px-3 py-1 rounded-full text-xs">
                                <i class="fas fa-check-circle text-purple-600 mr-1"></i> Terpilih
                            </span>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        @if ($kategoriList->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-folder-open text-slate-300 fa-3x mb-3"></i>
                                <p class="text-slate-500 font-semibold mb-0">Belum ada soal yang diupload untuk {{ $mapel }}.</p>
                                <p class="text-xs text-slate-400">Guru belum mengupload soal untuk kombinasi ini.</p>
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
                                        <a href="{{ $url }}" class="card border-2 text-decoration-none rounded-xl overflow-hidden h-100 transition-all {{ $isSelected ? 'border-purple-800 bg-purple-900 text-white shadow-lg' : 'border-slate-200 bg-white text-slate-700 hover:border-purple-400 hover:shadow-md' }}">
                                            <div class="card-body p-3.5">
                                                <div class="d-flex align-items-start gap-3">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-xs"
                                                         style="width: 40px; height: 40px; background: {{ $isSelected ? 'rgba(255,255,255,0.2)' : '#f3e8ff' }};">
                                                        <i class="fas fa-file-alt {{ $isSelected ? 'text-amber-300' : 'text-purple-700' }}"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <h6 class="font-bold mb-1 text-sm {{ $isSelected ? 'text-white' : 'text-purple-950' }}">
                                                            {{ $kat->deskripsi ?: $kat->nama_kategori }}
                                                        </h6>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="badge {{ $isSelected ? 'bg-white/20 text-purple-200' : 'bg-purple-100 text-purple-800' }} font-bold px-2 py-0.5 rounded text-xs">
                                                                <i class="fas fa-list-ol mr-1"></i> {{ $kat->bank_soals_count }} Soal
                                                            </span>
                                                            <span class="text-xs {{ $isSelected ? 'text-purple-300' : 'text-slate-400' }}">
                                                                {{ $kat->created_at->diffForHumans() }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @if ($isSelected)
                                                        <i class="fas fa-check-circle text-amber-300 fa-lg mt-1"></i>
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
                                    <span class="badge bg-purple-500/30 text-purple-200 border border-purple-400/30 px-2.5 py-1 rounded-md text-xs font-bold uppercase">
                                        Jenjang {{ $jenjang }}
                                    </span>
                                    <span class="badge bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 px-2.5 py-1 rounded-md text-xs font-bold">
                                        Kelas {{ $kelas }}
                                    </span>
                                    <span class="badge bg-emerald-500/30 text-emerald-200 border border-emerald-400/30 px-2.5 py-1 rounded-md text-xs font-bold">
                                        {{ $sub }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-xl mb-0">{{ $selectedCategory->deskripsi ?: $selectedCategory->nama_kategori }}</h3>
                            </div>
                            <form action="{{ route($prefixRoute . '.kategori.delete', $selectedCategory->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua soal ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-light font-bold rounded-lg px-2.5 py-1.5">
                                    <i class="fas fa-trash-alt mr-1"></i> Hapus Semua Soal Ini
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- DAFTAR SOAL TERSIMPAN -->
                    <div class="col-12 mb-4">
                        <div class="card border-0 shadow-sm rounded-2xl bg-white">
                            <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="card-title font-bold text-purple-950 mb-0 d-flex align-items-center text-base">
                                    <i class="fas fa-list-ol text-purple-600 mr-2"></i> Daftar Soal — {{ $selectedCategory->deskripsi ?: $selectedCategory->nama_kategori }}
                                </h5>
                                <span class="badge bg-purple-100 text-purple-900 font-bold px-3 py-1 rounded-full text-xs">
                                    {{ $selectedCategory->bankSoals->count() }} Soal
                                </span>
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
                                                </div>
                                                <div class="card-body p-3.5">
                                                    <p class="font-bold text-slate-900 mb-3 text-sm whitespace-pre-line">{{ $soalItem->soal }}</p>
                                                    <div class="row g-2">
                                                        @foreach (['A' => $soalItem->opsi_a, 'B' => $soalItem->opsi_b, 'C' => $soalItem->opsi_c, 'D' => $soalItem->opsi_d] as $optKey => $optVal)
                                                            @php $isCorrect = $soalItem->kunci_jawaban === $optKey; @endphp
                                                            <div class="col-md-6 mb-2">
                                                                <div class="p-2.5 rounded-xl border text-xs font-semibold d-flex align-items-start {{ $isCorrect ? 'bg-emerald-50 border-emerald-300 text-emerald-950 font-bold' : 'bg-slate-50 border-slate-200 text-slate-700' }}">
                                                                    <span class="badge {{ $isCorrect ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700' }} mr-2 px-2 py-1 rounded-md text-xs font-bold">{{ $optKey }}</span>
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
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

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
            const radioButtons = document.querySelectorAll('input[name="kunci_jawaban"]');
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    const groupName = this.getAttribute('name');
                    const form = this.closest('form');
                    form.querySelectorAll(`input[name="${groupName}"]`).forEach(r => {
                        const parentLabel = r.closest('label');
                        if (r.checked) {
                            parentLabel.classList.add('btn-purple', 'shadow-sm', 'text-white');
                            parentLabel.classList.remove('btn-outline-purple');
                        } else {
                            parentLabel.classList.remove('btn-purple', 'shadow-sm', 'text-white');
                            parentLabel.classList.add('btn-outline-purple');
                        }
                    });
                });
            });
        });
    </script>
@endsection