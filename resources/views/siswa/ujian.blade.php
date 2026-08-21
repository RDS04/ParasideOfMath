@extends('layout.app')

@section('title', 'Latihan Soal & Ujian · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header py-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-7">
                    <h1 class="m-0 font-weight-bold text-purple-950 d-flex align-items-center page-title-mobile">
                        <i class="fas fa-pencil-alt text-purple-600 mr-2.5"></i> Latihan Soal &amp; Ujian Online
                    </h1>
                    <p class="text-sm text-slate-500 mb-0 mt-1">
                        Uji pemahaman materi Anda melalui paket-paket soal latihan yang tersedia.
                    </p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right text-sm bg-transparent p-0 m-0 mt-2 mt-sm-0">
                        <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}" class="text-purple-600 font-semibold">Dashboard</a></li>
                        <li class="breadcrumb-item active text-slate-500">Latihan Ujian</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content pb-5">
        <div class="container-fluid">

            <!-- Alert Notifications -->
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0 d-flex align-items-center" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444;">
                    <i class="fas fa-exclamation-circle fa-lg mr-3 text-red-500"></i>
                    <div>
                        <strong class="font-bold">Perhatian!</strong> {{ session('error') }}
                    </div>
                    <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" class="text-red-700">&times;</span>
                    </button>
                </div>
            @endif

            <!-- ════════════════ MODE 1: KATALOG PILIHAN UJIAN ════════════════ -->
            @if ($mode === 'catalog')

                @if (!empty($assignedExams))
                    <!-- BANNER UJIAN DITUGASKAN OLEH GURU -->
                    <div class="card border-0 shadow-md rounded-2xl mb-4 overflow-hidden bg-white" style="border-left: 5px solid #a855f7;">
                        <div class="card-header bg-gradient-to-r from-purple-900 via-indigo-900 to-slate-900 text-white py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h5 class="card-title font-extrabold mb-0 text-base d-flex align-items-center">
                                <i class="fas fa-thumbtack text-amber-300 mr-2"></i> Ujian Ditugaskan ({{ count($assignedExams) }})
                            </h5>
                            <span class="badge bg-amber-400 text-purple-950 font-extrabold px-3 py-1 rounded-full text-xs">
                                Tugas Wajib
                            </span>
                        </div>
                        <div class="card-body p-4 bg-purple-50/30">
                            <div class="row g-3">
                                @foreach ($assignedExams as $aEx)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border border-purple-200 rounded-2xl shadow-xs overflow-hidden bg-white h-100 hover:border-purple-400 transition-all">
                                            <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                                                <div class="mb-3">
                                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-1 mb-2">
                                                        <span class="badge bg-purple-100 text-purple-900 font-extrabold px-2.5 py-1 rounded-md text-xs">
                                                            {{ $aEx['jenjang'] ?? 'SD' }} • {{ $aEx['nama_kategori'] ?? 'Mata Pelajaran' }}
                                                        </span>
                                                        @if (!empty($aEx['tgl_deadline']))
                                                            <span class="text-xs text-rose-600 font-bold">
                                                                <i class="fas fa-clock mr-1"></i> Deadline: {{ date('d M Y', strtotime($aEx['tgl_deadline'])) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <h5 class="font-bold text-slate-900 mb-2 text-base">
                                                        {{ $aEx['deskripsi'] ?: ($aEx['nama_kategori'] ?? 'Paket Soal Ujian') }}
                                                    </h5>
                                                    <div class="text-xs text-slate-600 bg-purple-50/80 p-2.5 rounded-xl border border-purple-100">
                                                        <i class="fas fa-quote-left text-purple-400 mr-1"></i> {{ $aEx['catatan'] ?? 'Silakan dikerjakan dengan jujur.' }}
                                                        <span class="d-block text-[11px] text-purple-800 font-semibold mt-1.5">— Ditugaskan oleh {{ $aEx['guru_name'] ?? 'Guru Anda' }} ({{ $aEx['tanggal_ditugaskan'] ?? '' }})</span>
                                                    </div>
                                                </div>
                                                <a href="{{ route('siswa.ujian', ['kategori_id' => $aEx['kategori_soal_id']]) }}" class="btn btn-purple btn-block font-extrabold rounded-xl py-2 text-xs shadow-sm">
                                                    <i class="fas fa-pencil-alt mr-1.5"></i> Kerjakan Ujian Ini Sekarang
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif



                <!-- 3. DAFTAR KATEGORI UJIAN TERSEDIA -->
                <div class="mb-5">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                        <h4 class="font-bold text-purple-950 mb-0 d-flex align-items-center exam-list-title">
                            <i class="fas fa-layer-group text-purple-600 mr-2"></i>
                            <span>Daftar Paket Ujian <span class="d-block d-sm-inline text-sm font-normal text-slate-500">({{ $jenjang }} - {{ $sub_kategori }})</span></span>
                        </h4>
                        <span class="badge bg-purple-100 text-purple-900 font-bold px-3 py-1 rounded-full text-xs shrink-0">
                            {{ $categories->count() }} Paket
                        </span>
                    </div>

                    @if ($categories->isEmpty() && empty($assignedExams))
                        <div class="card border-0 shadow-sm rounded-2xl text-center py-5 px-4 bg-white">
                            <div class="card-body">
                                <div class="mx-auto mb-3 rounded-full bg-purple-50 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                    <i class="fas fa-folder-open text-purple-400 fa-2x"></i>
                                </div>
                                <h5 class="font-bold text-purple-950 mb-1">Belum Ada Ujian Tersedia</h5>
                                <p class="text-slate-500 text-sm max-w-md mx-auto mb-0">
                                    Belum terdapat paket soal latihan untuk <strong>Jenjang {{ $jenjang }} - {{ $sub_kategori }}</strong>. Silakan pilih jenjang atau sub-kategori lain di atas.
                                </p>
                            </div>
                        </div>
                    @elseif ($categories->isEmpty())
                    @else
                        @php
                            $allDocFilesCountSiswa = 0;
                            foreach ($categories as $cCheck) {
                                $allDocFilesCountSiswa += count(glob(public_path("uploads/bank_soal_docs/doc_{$cCheck->id}_*.*")) ?: []);
                            }
                        @endphp

                        <!-- ═══════ TAB: SOAL UJIAN (Web) | MODUL PDF ═══════ -->
                        <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                            <div class="card-header p-2 bg-slate-100 border-bottom">
                                <ul class="nav nav-pills nav-justified w-100 tab-nav-list" id="tabUjianSoalModul" role="tablist">
                                    <li class="nav-item flex-1" role="presentation">
                                        <a class="nav-link active tab-nav-link tab-nav-soal" id="tab-soal-web" data-toggle="pill"
                                            href="#content-soal-web" role="tab" aria-controls="content-soal-web" aria-selected="true">
                                            <i class="fas fa-list-ol"></i>
                                            <span>Soal Ujian ({{ $categories->count() }})</span>
                                        </a>
                                    </li>
                                    <li class="nav-item flex-1" role="presentation">
                                        <a class="nav-link tab-nav-link tab-nav-modul" id="tab-modul-pdf-siswa" data-toggle="pill"
                                            href="#content-modul-pdf-siswa" role="tab" aria-controls="content-modul-pdf-siswa" aria-selected="false">
                                            <i class="fas fa-file-pdf"></i>
                                            <span>Modul PDF ({{ $allDocFilesCountSiswa }})</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body p-3 p-md-4 bg-slate-50">
                                <div class="tab-content" id="tabUjianSoalModulContent">

                                    <!-- TAB 1: SOAL UJIAN (dibuat guru lewat web) -->
                                    <div class="tab-pane fade show active" id="content-soal-web" role="tabpanel" aria-labelledby="tab-soal-web">
                                        <div class="row">
                                            @foreach ($categories as $cat)
                                                <div class="col-md-6 col-lg-4 mb-4">
                                                    <div class="card border-0 shadow-sm rounded-2xl bg-white h-100 transition-all hover:shadow-md hover:-translate-y-1 overflow-hidden d-flex flex-column">
                                                        <div class="card-header bg-gradient-to-r from-purple-900 to-indigo-900 text-white p-4 border-0">
                                                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-1 mb-2">
                                                                <span class="badge bg-purple-500/30 text-purple-200 border border-purple-400/30 px-2.5 py-1 rounded-md text-[11px] font-bold">
                                                                    {{ $cat->jenjang }} • {{ $cat->sub_kategori }}
                                                                </span>
                                                                <span class="badge bg-amber-400 text-purple-950 font-extrabold px-2.5 py-1 rounded-full text-xs">
                                                                    <i class="fas fa-question-circle mr-1"></i> {{ $cat->bank_soals_count }} Soal
                                                                </span>
                                                            </div>
                                                            <h5 class="font-bold text-lg mb-1 leading-snug">{{ $cat->nama_kategori }}</h5>
                                                        </div>
                                                        <div class="card-body p-4 d-flex flex-column justify-content-between flex-grow-1">
                                                            <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                                                                {{ $cat->deskripsi ?: 'Latihan soal pilihan ganda untuk menguji pemahaman materi ' . $cat->nama_kategori . '.' }}
                                                            </p>

                                                            @if ($cat->bank_soals_count > 0)
                                                                <a href="{{ route('siswa.ujian', ['kategori_id' => $cat->id]) }}" class="btn btn-purple btn-block font-bold rounded-xl py-2.5 text-sm shadow-sm">
                                                                    <i class="fas fa-play-circle mr-1.5"></i> Mulai Kerjakan Soal
                                                                </a>
                                                            @else
                                                                <button disabled class="btn btn-light btn-block font-bold rounded-xl py-2.5 text-xs text-slate-400">
                                                                    <i class="fas fa-lock mr-1.5"></i> Soal Belum Belum Siap
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- TAB 2: MODUL PDF -->
                                    <div class="tab-pane fade" id="content-modul-pdf-siswa" role="tabpanel" aria-labelledby="tab-modul-pdf-siswa">
                                        @php $hasAnyDocFilesSiswa = false; @endphp

                                        <div class="d-flex flex-column doc-group-list">
                                            @foreach ($categories as $catMod)
                                                @php
                                                    $gModDocFilesSiswa = glob(public_path("uploads/bank_soal_docs/doc_{$catMod->id}_*.*")) ?: [];
                                                @endphp
                                                @if (count($gModDocFilesSiswa) > 0)
                                                    @php $hasAnyDocFilesSiswa = true; @endphp
                                                    <div class="doc-group-card">
                                                        <div class="doc-group-header">
                                                            <span class="doc-group-title">
                                                                <i class="fas fa-folder"></i> {{ $catMod->deskripsi ?: $catMod->nama_kategori }}
                                                            </span>
                                                            <span class="doc-group-count">{{ count($gModDocFilesSiswa) }} File PDF</span>
                                                        </div>
                                                        <div class="doc-group-body">
                                                            <div class="d-flex flex-column doc-file-list">
                                                                @foreach($gModDocFilesSiswa as $sDocIdx => $sDocPath)
                                                                    @php
                                                                        $sDocFileName = basename($sDocPath);
                                                                        $sDocExt = strtolower(pathinfo($sDocFileName, PATHINFO_EXTENSION));
                                                                        $sDocDisplay = preg_replace('/^doc_\d+_\d+_/', '', $sDocFileName);
                                                                        $sDocUrl = asset("uploads/bank_soal_docs/{$sDocFileName}");
                                                                        $sIsPdf = $sDocExt === 'pdf';
                                                                    @endphp
                                                                    <div class="doc-file-item">
                                                                        <div class="doc-file-left">
                                                                            <div class="doc-file-icon {{ $sIsPdf ? 'doc-file-icon-pdf' : 'doc-file-icon-word' }}">
                                                                                <i class="fas {{ $sIsPdf ? 'fa-file-pdf' : 'fa-file-word' }}"></i>
                                                                            </div>
                                                                            <div class="doc-file-info">
                                                                                <div class="doc-file-name-row">
                                                                                    <h6 class="doc-file-name" title="{{ $sDocDisplay }}">{{ $sDocDisplay }}</h6>
                                                                                    <span class="doc-file-ext {{ $sIsPdf ? 'doc-file-ext-pdf' : 'doc-file-ext-word' }}">
                                                                                        {{ strtoupper($sDocExt) }}
                                                                                    </span>
                                                                                </div>
                                                                                <span class="doc-file-sub">File Dokumen Modul Pembelajaran</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="doc-file-right">
                                                                            @if($sIsPdf)
                                                                                <button type="button" class="doc-file-read-btn" data-toggle="modal"
                                                                                    data-target="#modalUjianPreviewDoc_{{ $catMod->id }}_{{ $sDocIdx }}">
                                                                                    <i class="fas fa-book-reader"></i> Baca Dokumen PDF
                                                                                </button>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    @if($sIsPdf)
                                                                        <div class="modal fade p-0" id="modalUjianPreviewDoc_{{ $catMod->id }}_{{ $sDocIdx }}"
                                                                            tabindex="-1" role="dialog" aria-hidden="true" style="padding-right: 0 !important;">
                                                                            <div class="modal-dialog m-0 pdf-modal-dialog" role="document">
                                                                                <div class="modal-content border-0 rounded-0 shadow-none h-100 pdf-modal-content">
                                                                                    <div class="pdf-modal-header">
                                                                                        <div class="pdf-modal-header-left">
                                                                                            <i class="fas fa-file-pdf"></i>
                                                                                            <div class="min-w-0">
                                                                                                <h5 class="pdf-modal-title" title="{{ $sDocDisplay }}">{{ $sDocDisplay }}</h5>
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
                                                                                        <iframe src="{{ $sDocUrl }}#toolbar=0&navpanes=0&scrollbar=1&view=FitH"
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

                                            @if (!$hasAnyDocFilesSiswa)
                                                <div class="text-center py-5 bg-white empty-state-box">
                                                    <i class="fas fa-file-pdf empty-state-icon"></i>
                                                    <h6 class="font-bold empty-state-title">Belum ada dokumen modul PDF terunggah untuk {{ $jenjang }} - {{ $sub_kategori }}.</h6>
                                                    <p class="empty-state-text">Modul PDF akan muncul di sini setelah guru mengunggahnya.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- 4. RIWAYAT HASIL UJIAN SISWA -->
                @if ($riwayatUjian->isNotEmpty())
                    <div class="card border-0 shadow-sm rounded-2xl bg-white overflow-hidden">
                        <div class="card-header bg-white py-3 px-4 border-bottom">
                            <h5 class="card-title font-bold text-purple-950 mb-0 d-flex align-items-center">
                                <i class="fas fa-history text-purple-600 mr-2"></i> Riwayat Hasil Ujian Anda
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-slate-500 text-xs uppercase font-bold">
                                        <tr>
                                            <th class="px-4 py-3">Tanggal Ujian</th>
                                            <th class="px-4 py-3">Kategori Soal</th>
                                            <th class="px-4 py-3 text-center">Jumlah Soal</th>
                                            <th class="px-4 py-3 text-center">Benar / Salah</th>
                                            <th class="px-4 py-3 text-center">Nilai Final</th>
                                            <th class="px-4 py-3 text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
                                        @foreach ($riwayatUjian as $rw)
                                            <tr>
                                                <td class="px-4 py-3 text-slate-600 font-semibold">
                                                    <i class="far fa-calendar-alt text-purple-400 mr-1.5"></i>
                                                    {{ $rw->created_at->format('d M Y, H:i') }}
                                                </td>
                                                <td class="px-4 py-3 font-bold text-purple-950">
                                                    {{ $rw->kategori->nama_kategori ?? 'Kategori Soal' }}
                                                    <span class="d-block text-[11px] text-slate-500 font-normal">
                                                        {{ $rw->kategori->jenjang ?? '' }} • {{ $rw->kategori->sub_kategori ?? '' }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-center font-bold text-slate-700">
                                                    {{ $rw->jumlah_soal }} Soal
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="badge bg-emerald-100 text-emerald-800 font-bold px-2 py-1 rounded">
                                                        {{ $rw->jumlah_benar }} Benar
                                                    </span>
                                                    <span class="badge bg-rose-100 text-rose-800 font-bold px-2 py-1 rounded ml-1">
                                                        {{ $rw->jumlah_salah }} Salah
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="font-extrabold text-base {{ $rw->nilai >= 75 ? 'text-emerald-600' : ($rw->nilai >= 60 ? 'text-amber-600' : 'text-rose-600') }}">
                                                        {{ number_format($rw->nilai, 1) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    @if ($rw->nilai >= 90)
                                                        <span class="badge bg-emerald-100 text-emerald-800 border border-emerald-200 px-2.5 py-1 rounded-pill font-bold">Sangat Baik 🌟</span>
                                                    @elseif ($rw->nilai >= 75)
                                                        <span class="badge bg-blue-100 text-blue-800 border border-blue-200 px-2.5 py-1 rounded-pill font-bold">Baik 👍</span>
                                                    @elseif ($rw->nilai >= 60)
                                                        <span class="badge bg-amber-100 text-amber-800 border border-amber-200 px-2.5 py-1 rounded-pill font-bold">Cukup ⚡</span>
                                                    @else
                                                        <span class="badge bg-rose-100 text-rose-800 border border-rose-200 px-2.5 py-1 rounded-pill font-bold">Perlu Belajar 💪</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

            <!-- ════════════════ MODE 2: LEMBAR PENGERJAAN UJIAN ════════════════ -->
            @elseif ($mode === 'exam' && isset($selectedCategory))

                <!-- Header Exam Info Card -->
                <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-gradient-to-r from-purple-900 to-indigo-900 text-white">
                    <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-purple-500/30 text-purple-200 border border-purple-400/30 px-2.5 py-1 rounded-md text-xs font-bold">
                                    Jenjang {{ $selectedCategory->jenjang }}
                                </span>
                                <span class="badge bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 px-2.5 py-1 rounded-md text-xs font-bold">
                                    {{ $selectedCategory->sub_kategori }}
                                </span>
                            </div>
                            <h3 class="font-bold text-xl mb-1">{{ $selectedCategory->nama_kategori }}</h3>
                            <p class="text-xs text-purple-200 mb-0">Pilihlah salah satu jawaban yang Anda anggap paling benar untuk setiap nomor soal.</p>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-white/10 rounded-xl px-3.5 py-2 text-center border border-white/20">
                                <span class="d-block text-2xl font-extrabold text-amber-300 leading-tight">
                                    {{ $selectedCategory->bankSoals->count() }}
                                </span>
                                <span class="text-[10px] text-purple-200 uppercase font-bold tracking-wider">Total Soal</span>
                            </div>
                            <a href="{{ route('siswa.ujian') }}" onclick="return confirm('Kembali ke katalog? Progres jawaban Anda tidak akan tersimpan.');" class="btn btn-sm btn-outline-light rounded-xl font-bold px-3 py-2 text-xs">
                                <i class="fas fa-times mr-1"></i> Batal Ujian
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('siswa.ujian.submit') }}" method="POST" id="formUjian">
                    @csrf
                    <input type="hidden" name="kategori_soal_id" value="{{ $selectedCategory->id }}">

                    <div class="row">
                        <!-- Kolom Lembar Soal -->
                        <div class="col-lg-8 mb-4">
                            @foreach ($selectedCategory->bankSoals as $index => $soalItem)
                                <div class="card border-0 shadow-sm rounded-2xl bg-white mb-4 soal-card" id="soal_card_{{ $soalItem->id }}">
                                    <div class="card-header bg-slate-50 py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                                        <span class="badge bg-purple-900 text-white font-extrabold px-3 py-1.5 rounded-lg text-xs">
                                            Soal No. {{ $soalItem->nomor }} dari {{ $selectedCategory->bankSoals->count() }}
                                        </span>
                                        <span class="text-xs font-bold text-slate-400">Pilihan Ganda</span>
                                    </div>
                                    <div class="card-body p-4">
                                        <!-- Pertanyaan Soal -->
                                        <h5 class="font-bold text-purple-950 mb-4 leading-relaxed text-base whitespace-pre-line">{{ $soalItem->soal }}</h5>

                                        <!-- Opsi Jawaban Radio Buttons -->
                                        <div class="space-y-3">
                                            @foreach (['A' => $soalItem->opsi_a, 'B' => $soalItem->opsi_b, 'C' => $soalItem->opsi_c, 'D' => $soalItem->opsi_d] as $optKey => $optVal)
                                                <label class="d-flex align-items-start p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-purple-50 hover:border-purple-300 cursor-pointer transition-all duration-150 option-label mb-2"
                                                       style="user-select: none;">
                                                    <input type="radio" name="jawaban[{{ $soalItem->id }}]" value="{{ $optKey }}"
                                                           class="mt-1 mr-3 option-radio"
                                                           data-soal-id="{{ $soalItem->id }}">
                                                    <div class="d-flex align-items-start">
                                                        <span class="badge bg-purple-100 text-purple-950 font-extrabold mr-2.5 px-2.5 py-1 rounded-md text-xs">
                                                            {{ $optKey }}
                                                        </span>
                                                        <span class="text-slate-800 text-sm font-semibold mt-0.5 leading-snug">{{ $optVal }}</span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Kolom Palette Navigasi Nomor Soal -->
                        <div class="col-lg-4 mb-4">
                            <div class="card border-0 shadow-sm rounded-2xl bg-white sticky-top" style="top: 20px;">
                                <div class="card-header bg-white py-3 px-4 border-bottom">
                                    <h5 class="card-title font-bold text-purple-950 mb-0 text-sm d-flex align-items-center">
                                        <i class="fas fa-th text-purple-600 mr-2"></i> Navigasi Nomor Soal
                                    </h5>
                                </div>
                                <div class="card-body p-4 text-center">
                                    <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                                        @foreach ($selectedCategory->bankSoals as $index => $soalItem)
                                            <a href="#soal_card_{{ $soalItem->id }}"
                                               id="nav_btn_{{ $soalItem->id }}"
                                               class="btn btn-outline-secondary font-bold rounded-xl text-xs d-flex align-items-center justify-content-center nav-soal-btn transition-all"
                                               style="width: 42px; height: 42px;">
                                                {{ $soalItem->nomor }}
                                            </a>
                                        @endforeach
                                    </div>

                                    <div class="border-top pt-3 text-left mb-4 text-xs space-y-2">
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-purple-900 text-white mr-2" style="width: 16px; height: 16px; display: inline-block;"></span>
                                            <span class="text-slate-600 font-semibold">Sudah Dijawab</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-white border border-slate-300 mr-2" style="width: 16px; height: 16px; display: inline-block;"></span>
                                            <span class="text-slate-600 font-semibold">Belum Dijawab</span>
                                        </div>
                                    </div>

                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengumpulkan seluruh jawaban ujian ini?');"
                                            class="btn btn-purple btn-block font-extrabold py-3 rounded-xl shadow-md text-sm transition-all">
                                        <i class="fas fa-paper-plane mr-2"></i> Kumpulkan Ujian
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            <!-- ════════════════ MODE 3: HASIL & PEMBAHASAN UJIAN ════════════════ -->
            @elseif ($mode === 'result' && isset($hasil))

                <!-- Hero Score Banner Card -->
                <div class="card border-0 shadow-md rounded-2xl bg-white mb-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5 text-center bg-gradient-to-br from-purple-900 via-indigo-900 to-slate-900 text-white">
                        <div class="inline-block p-3 rounded-full bg-white/10 mb-3 border border-white/20">
                            <i class="fas fa-award text-amber-300 fa-3x"></i>
                        </div>
                        <h4 class="font-bold text-purple-200 text-sm uppercase tracking-widest mb-2">Hasil Ujian Selesai</h4>
                        <h2 class="font-black text-2xl text-white mb-2">{{ $kategori->nama_kategori }}</h2>
                        <span class="badge bg-purple-500/30 text-purple-200 border border-purple-400/30 px-3 py-1 rounded-md text-xs font-bold mb-4">
                            {{ $kategori->jenjang }} • {{ $kategori->sub_kategori }}
                        </span>

                        <!-- Big Score Display -->
                        <div class="my-4">
                            <span class="d-block text-6xl font-black text-amber-300 tracking-tight">
                                {{ number_format($nilai, 1) }}
                            </span>
                            <span class="text-xs text-purple-200 font-bold uppercase tracking-widest">Skor Akhir (Nilai 0 - 100)</span>
                        </div>

                        <!-- Predikat Status -->
                        <div class="mb-4">
                            @if ($nilai >= 90)
                                <span class="badge bg-emerald-500 text-white text-sm font-extrabold px-4 py-2 rounded-full shadow-sm">
                                    <i class="fas fa-star mr-1"></i> Sangat Baik! Luar Biasa!
                                </span>
                            @elseif ($nilai >= 75)
                                <span class="badge bg-blue-500 text-white text-sm font-extrabold px-4 py-2 rounded-full shadow-sm">
                                    <i class="fas fa-thumbs-up mr-1"></i> Baik! Tingkatkan Lagi!
                                </span>
                            @elseif ($nilai >= 60)
                                <span class="badge bg-amber-500 text-white text-sm font-extrabold px-4 py-2 rounded-full shadow-sm">
                                    <i class="fas fa-bolt mr-1"></i> Cukup! Pelajari Kembali Materi.
                                </span>
                            @else
                                <span class="badge bg-rose-500 text-white text-sm font-extrabold px-4 py-2 rounded-full shadow-sm">
                                    <i class="fas fa-book-reader mr-1"></i> Perlu Belajar &amp; Latihan Lagi!
                                </span>
                            @endif
                        </div>

                        <!-- Stats Summary Grid -->
                        <div class="row max-w-lg mx-auto bg-white/10 rounded-2xl p-3 border border-white/10 text-center gap-y-2">
                            <div class="col-4 border-right border-white/10">
                                <span class="d-block text-xl font-black text-white">{{ $totalSoal }}</span>
                                <span class="text-[11px] text-purple-200 font-bold uppercase">Total Soal</span>
                            </div>
                            <div class="col-4 border-right border-white/10">
                                <span class="d-block text-xl font-black text-emerald-400">{{ $jumlahBenar }}</span>
                                <span class="text-[11px] text-emerald-200 font-bold uppercase">Jawaban Benar</span>
                            </div>
                            <div class="col-4">
                                <span class="d-block text-xl font-black text-rose-400">{{ $jumlahSalah }}</span>
                                <span class="text-[11px] text-rose-200 font-bold uppercase">Jawaban Salah</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-slate-50 p-3 text-center border-top">
                        <a href="{{ route('siswa.ujian') }}" class="btn btn-purple font-bold px-4 py-2.5 rounded-xl shadow-sm text-sm">
                            <i class="fas fa-arrow-left mr-1.5"></i> Kembali ke Katalog Ujian
                        </a>
                    </div>
                </div>

                <!-- Pembahasan Lengkap per Soal -->
                <div class="card border-0 shadow-sm rounded-2xl bg-white mb-4">
                    <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="card-title font-bold text-purple-950 mb-0 d-flex align-items-center">
                            <i class="fas fa-check-double text-purple-600 mr-2"></i> Pembahasan Review Soal &amp; Kunci Jawaban
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="space-y-4">
                            @foreach ($reviewData as $rev)
                                @php
                                    $soal = $rev['soal'];
                                    $userAns = $rev['jawaban_siswa'];
                                    $isCorrect = $rev['is_correct'];
                                @endphp
                                <div class="card border rounded-2xl shadow-xs overflow-hidden mb-4 {{ $isCorrect ? 'border-emerald-300' : 'border-rose-300' }}">
                                    <div class="card-header py-2.5 px-3.5 d-flex justify-content-between align-items-center {{ $isCorrect ? 'bg-emerald-50 text-emerald-950' : 'bg-rose-50 text-rose-950' }}">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge {{ $isCorrect ? 'bg-emerald-700' : 'bg-rose-700' }} text-white font-extrabold px-2.5 py-1 rounded-lg text-xs">
                                                Soal No. {{ $soal->nomor }}
                                            </span>
                                            <span class="text-xs font-bold">
                                                Status: {{ $isCorrect ? 'BENAR' : 'SALAH' }}
                                            </span>
                                        </div>
                                        <div>
                                            @if ($isCorrect)
                                                <span class="badge bg-emerald-600 text-white font-extrabold px-2.5 py-1 rounded-full text-xs">
                                                    <i class="fas fa-check mr-1"></i> +1 Benar
                                                </span>
                                            @else
                                                <span class="badge bg-rose-600 text-white font-extrabold px-2.5 py-1 rounded-full text-xs">
                                                    <i class="fas fa-times mr-1"></i> Salah
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="card-body p-4">
                                        <!-- Teks Soal -->
                                        <p class="font-bold text-slate-900 mb-3 text-sm whitespace-pre-line">{{ $soal->soal }}</p>

                                        <!-- Opsi Jawaban dengan Sorotan -->
                                        <div class="row g-2">
                                            @foreach (['A' => $soal->opsi_a, 'B' => $soal->opsi_b, 'C' => $soal->opsi_c, 'D' => $soal->opsi_d] as $optKey => $optVal)
                                                @php
                                                    $isKey = ($soal->kunci_jawaban === $optKey);
                                                    $isSelectedByUser = ($userAns === $optKey);
                                                @endphp
                                                <div class="col-md-6 mb-2">
                                                    <div class="p-3 rounded-xl border text-xs font-semibold d-flex align-items-start
                                                        {{ $isKey ? 'bg-emerald-100 border-emerald-400 text-emerald-950 font-bold' : ($isSelectedByUser && !$isKey ? 'bg-rose-100 border-rose-400 text-rose-950' : 'bg-slate-50 border-slate-200 text-slate-700') }}">

                                                        <span class="badge {{ $isKey ? 'bg-emerald-700 text-white' : ($isSelectedByUser ? 'bg-rose-700 text-white' : 'bg-slate-200 text-slate-700') }} mr-2.5 px-2.5 py-1 rounded-md text-xs font-bold">
                                                            {{ $optKey }}
                                                        </span>

                                                        <div class="flex-1 mt-0.5">
                                                            <span>{{ $optVal }}</span>
                                                            @if ($isKey)
                                                                <span class="d-block text-[11px] text-emerald-700 font-extrabold mt-1">
                                                                    <i class="fas fa-check-circle mr-1"></i> [Kunci Jawaban Benar]
                                                                </span>
                                                            @endif
                                                            @if ($isSelectedByUser && !$isKey)
                                                                <span class="d-block text-[11px] text-rose-700 font-extrabold mt-1">
                                                                    <i class="fas fa-times-circle mr-1"></i> [Jawaban Anda]
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            @endif

        </div>
    </section>

    <!-- Custom CSS & Interactive JavaScript -->
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
        /* Custom radio option selection style */
        .option-label.selected {
            background-color: #f3e8ff !important;
            border-color: #a855f7 !important;
            box-shadow: 0 2px 4px rgba(168, 85, 247, 0.15);
        }
        .nav-soal-btn.answered {
            background-color: #581c87 !important;
            color: #ffffff !important;
            border-color: #581c87 !important;
        }

        /* ── Responsive Mobile Tweaks ── */
        .exam-list-title {
            font-size: 1rem;
        }

        @media (max-width: 576px) {
            .page-title-mobile {
                font-size: 1.15rem;
                line-height: 1.3;
            }
            .content-header {
                padding-top: 0.75rem !important;
                padding-bottom: 0.75rem !important;
            }
            .exam-list-title {
                font-size: 0.95rem;
                width: 100%;
            }
            .card-title {
                font-size: 0.95rem;
            }
            .soal-card .card-body {
                padding: 1rem !important;
            }
            .soal-card h5 {
                font-size: 0.95rem;
            }
            .option-label {
                padding: 0.75rem !important;
            }
            .nav-soal-btn {
                width: 38px !important;
                height: 38px !important;
                font-size: 0.75rem;
            }
            .table-responsive table {
                font-size: 0.75rem;
            }
        }

        /* ============== TABS SOAL / MODUL PDF (siswa) ============== */
        .tab-nav-list { gap: 8px; }
        .tab-nav-link {
            font-weight: 700; font-size: 13px; padding: 10px 8px;
            border-radius: 10px; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
            transition: all 0.2s ease; text-align: center;
            display: flex; align-items: center; justify-content: center; gap: 6px;
        }
        .tab-nav-soal { border: 2px solid #6b21a8; }
        .tab-nav-soal i { color: #f59e0b; }
        .tab-nav-modul { border: 2px solid #0284c7; }
        .tab-nav-modul i { color: #f43f5e; }

        .empty-state-box { border: 2px dashed #e2e8f0; border-radius: 16px; padding: 20px; }
        .empty-state-icon { color: #cbd5e1; font-size: 42px; margin-bottom: 12px; display: block; }
        .empty-state-title { color: #334155; font-size: 14px; margin-bottom: 4px; }
        .empty-state-text { color: #94a3b8; font-size: 12px; margin-bottom: 12px; }

        .doc-group-list { gap: 12px; }
        .doc-group-card { border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; background: #ffffff; box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04); }
        .doc-group-header { display: flex; align-items: center; justify-content: space-between; background: #f1f5f9; padding: 10px 14px; border-bottom: 1px solid #e2e8f0; }
        .doc-group-title { font-size: 12px; font-weight: 700; color: #3b0764; }
        .doc-group-title i { color: #9333ea; margin-right: 6px; }
        .doc-group-count { background: #581c87; color: #ffffff; font-weight: 700; font-size: 11px; padding: 4px 10px; border-radius: 6px; }
        .doc-group-body { padding: 12px; background: #f8fafc; }
        .doc-file-list { gap: 8px; }

        .doc-file-item {
            display: flex; flex-direction: column; gap: 10px; justify-content: space-between; align-items: stretch;
            padding: 14px; border-radius: 16px; border: 1px solid #e2e8f0; background: #ffffff;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04); transition: border-color 0.2s ease;
        }
        .doc-file-item:hover { border-color: #c084fc; }
        .doc-file-left { display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1; }
        .doc-file-icon { width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 18px; flex-shrink: 0; }
        .doc-file-icon-pdf { background: #ffe4e6; color: #e11d48; }
        .doc-file-icon-word { background: #dbeafe; color: #2563eb; }
        .doc-file-info { min-width: 0; flex: 1; }
        .doc-file-name-row { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-bottom: 2px; }
        .doc-file-name { font-size: 13px; font-weight: 700; color: #3b0764; margin-bottom: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .doc-file-ext { font-size: 10px; font-weight: 700; text-transform: uppercase; padding: 2px 6px; border-radius: 4px; border: 1px solid; }
        .doc-file-ext-pdf { background: #fff1f2; color: #be123c; border-color: #fecdd3; }
        .doc-file-ext-word { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
        .doc-file-sub { font-size: 11px; color: #94a3b8; display: block; }
        .doc-file-right { display: flex; align-items: center; flex-shrink: 0; }
        .doc-file-read-btn {
            width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px;
            padding: 10px 14px; border-radius: 10px; font-size: 12px; font-weight: 700;
            color: #ffffff; border: none; background: linear-gradient(135deg, #6b21a8, #4c1d95);
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
        }
        .doc-file-read-btn i { color: #fcd34d; }

        @media (min-width: 576px) {
            .doc-file-item { flex-direction: row; align-items: center; }
            .doc-file-read-btn { width: auto; }
        }

        .pdf-modal-dialog { max-width: 100vw; width: 100vw; height: 100vh; margin: 0; }
        .pdf-modal-content { background: #0f172a; }
        .pdf-modal-header { display: flex; flex-direction: row; align-items: center; justify-content: space-between; gap: 10px; background: #3b0764; color: #ffffff; padding: 10px 14px; border-bottom: 1px solid #581c87; }
        .pdf-modal-header-left { display: flex; align-items: center; gap: 10px; min-width: 0; }
        .pdf-modal-header-left > i { color: #fb7185; font-size: 18px; flex-shrink: 0; }
        .pdf-modal-title { font-size: 13px; font-weight: 700; color: #ffffff; margin-bottom: 0; max-width: 60vw; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .pdf-modal-protection { font-size: 10px; color: #fcd34d; display: block; font-weight: 500; }
        .pdf-modal-close-btn { flex-shrink: 0; background: #dc2626; color: #ffffff; border: none; font-weight: 700; font-size: 12px; border-radius: 8px; padding: 6px 12px; }
        .pdf-modal-body { padding: 0; background: #0f172a; overflow: hidden; height: calc(100vh - 58px); user-select: none; }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Interactive radio option styling & Navigation palette sync
            const radioButtons = document.querySelectorAll('.option-radio');

            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    const soalId = this.getAttribute('data-soal-id');

                    // Reset styling in same question group
                    const parentCard = document.getElementById(`soal_card_${soalId}`);
                    if (parentCard) {
                        parentCard.querySelectorAll('.option-label').forEach(lbl => {
                            lbl.classList.remove('selected');
                        });
                    }

                    // Highlight selected option label
                    const currentLabel = this.closest('.option-label');
                    if (currentLabel && this.checked) {
                        currentLabel.classList.add('selected');
                    }

                    // Highlight navigation button in palette
                    const navBtn = document.getElementById(`nav_btn_${soalId}`);
                    if (navBtn && this.checked) {
                        navBtn.classList.add('answered');
                    }
                });
            });
        });
    </script>
@endsection