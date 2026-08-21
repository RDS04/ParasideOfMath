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
                            <div class="d-flex flex-column gap-2.5">
                                @foreach ($kategoriList as $kat)
                                    @php
                                        $docCount = count(glob(public_path("uploads/bank_soal_docs/doc_{$kat->id}_*.*")) ?: []);
                                        $url = route($prefixRoute . '.kelola', $kat->id);
                                    @endphp
                                    <a href="{{ $url }}"
                                        class="p-3.5 rounded-2xl text-decoration-none d-flex flex-column flex-sm-row items-stretch items-sm-center justify-content-between gap-3 transition-all hover:shadow-md shadow-xs"
                                        style="background: linear-gradient(135deg, #ffffff, #f8fafc) !important; color: #0f172a !important; border: 2px solid #e2e8f0 !important;">
                                        <div class="d-flex items-center gap-3 min-w-0 flex-1">
                                            <div class="rounded-2xl p-3 d-flex items-center justify-center shrink-0 shadow-xs"
                                                style="width: 46px; height: 46px; background: #f1f5f9; border: 1px solid #cbd5e1;">
                                                <i class="fas fa-folder-open fa-lg text-purple-700"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                                    <h6 class="font-bold text-sm sm:text-base text-truncate mb-0 text-slate-900"
                                                        title="{{ $kat->deskripsi ?: $kat->nama_kategori }}">
                                                        {{ $kat->deskripsi ?: $kat->nama_kategori }}
                                                    </h6>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                    <span class="badge font-bold px-2.5 py-1 rounded-lg text-xs"
                                                        style="background-color: #f3e8ff !important; color: #6b21a8 !important; border: 1px solid #d8b4fe !important;">
                                                        <i class="fas fa-list-ol mr-1"></i> {{ $kat->bank_soals_count }} Soal Manual
                                                    </span>
                                                    <span class="badge font-bold px-2.5 py-1 rounded-lg text-xs"
                                                        style="background-color: #e0f2fe !important; color: #0369a1 !important; border: 1px solid #bae6fd !important;">
                                                        <i class="fas fa-file-pdf mr-1"></i> {{ $docCount }} Dokumen/PDF
                                                    </span>
                                                    <span class="text-xs ml-sm-auto text-slate-500 font-medium">
                                                        <i class="far fa-clock mr-1"></i> Dibuat {{ $kat->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex items-center justify-content-end gap-2 shrink-0">
                                            <span class="btn btn-xs font-bold rounded-xl px-3.5 py-2 text-xs shadow-xs text-white"
                                                style="background: linear-gradient(135deg, #6b21a8, #4c1d95) !important; color: #ffffff !important; border: none !important;">
                                                <i class="fas fa-edit mr-1 text-amber-300"></i> Kelola Soal &amp; Modul <i
                                                    class="fas fa-chevron-right ml-1"></i>
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