@extends('layout.app')

@section('title', 'Detail Paket Soal · Paradise of Math')

@php
    $isGuru = auth()->check() && auth()->user()->isGuru();
    $dashRoute = $isGuru ? route('guru.dashboard') : route('admin.dashboard');
    $backRoute = route('guru.list-soal.index', [
        'jenjang' => $jenjang,
        'kelas' => $kelas,
        'sub_kategori' => $sub,
        'mapel' => $mapel
    ]);
    $guruCatDocFiles = glob(public_path("uploads/bank_soal_docs/doc_{$selectedCategory->id}_*.*")) ?: [];
@endphp

@section('content')
    <!-- Content Header -->
    <div class="content-header py-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-7">
                    <div class="mb-2">
                        <a href="{{ $backRoute }}" class="btn btn-sm btn-purple font-bold rounded-xl px-3 py-1.5 text-xs shadow-xs">
                            <i class="fas fa-arrow-left mr-1.5"></i> Kembali ke Daftar Paket Soal
                        </a>
                    </div>
                    <h1 class="m-0 font-weight-bold text-purple-950 d-flex align-items-center text-xl sm:text-2xl">
                        <i class="fas fa-folder-open text-purple-600 mr-2.5"></i> Paket Soal: {{ $selectedCategory->deskripsi ?: $selectedCategory->nama_kategori }}
                    </h1>
                    <p class="text-sm text-slate-500 mb-0 mt-1">
                        {{ $mapel }} &bull; Jenjang {{ $jenjang }} &bull; Kelas {{ $kelas }} &bull; {{ $sub }}
                    </p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right text-sm bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ $dashRoute }}" class="text-purple-600 font-semibold">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ $backRoute }}" class="text-purple-600 font-semibold">List Soal</a></li>
                        <li class="breadcrumb-item active text-slate-500">Detail Paket</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content pb-5">
        <div class="container-fluid">

            <!-- SUMMARY CARD KELOLA PAKET -->
            <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                <div class="card-body p-4" style="background: linear-gradient(135deg, #4c1d95, #6b21a8) !important; color: #ffffff !important;">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                        <div class="d-flex items-center gap-3 min-w-0">
                            <div class="rounded-2xl p-3 d-flex items-center justify-center shrink-0 shadow-xs"
                                style="width: 50px; height: 50px; background: rgba(255,255,255,0.2);">
                                <i class="fas fa-file-alt text-amber-300 fa-2xl"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="badge bg-amber-400 text-purple-950 font-bold px-2.5 py-1 rounded-md text-[10px] uppercase mb-1">
                                    <i class="fas fa-book mr-1"></i> {{ $mapel }}
                                </span>
                                <h4 class="font-bold text-white text-lg sm:text-xl mb-1 text-truncate" title="{{ $selectedCategory->deskripsi ?: $selectedCategory->nama_kategori }}">
                                    {{ $selectedCategory->deskripsi ?: $selectedCategory->nama_kategori }}
                                </h4>
                                <div class="d-flex align-items-center gap-2 flex-wrap text-xs text-purple-200">
                                    <span><i class="fas fa-layer-group text-amber-300 mr-1"></i> {{ $jenjang }} Kelas {{ $kelas }} ({{ $sub }})</span>
                                    &bull;
                                    <span><i class="far fa-clock text-amber-300 mr-1"></i> Dibuat {{ $selectedCategory->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex items-center gap-2 flex-wrap shrink-0">
                            <span class="badge bg-white/20 text-yellow-300 font-bold px-3 py-2 rounded-xl text-xs">
                                <i class="fas fa-list-ol mr-1"></i> {{ $selectedCategory->bankSoals->count() }} Soal Manual
                            </span>
                            <span class="badge bg-white/20 text-sky-300 font-bold px-3 py-2 rounded-xl text-xs">
                                <i class="fas fa-file-pdf mr-1"></i> {{ count($guruCatDocFiles) }} Dokumen PDF
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DUA MENU TAB: MENU 1 (SOAL) | MENU 2 (MODUL PDF) -->
            <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                <div class="card-header p-2 bg-slate-100 border-bottom">
                    <ul class="nav nav-pills nav-justified w-100 gap-2" id="tabDetailSoalModul" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active font-bold text-xs sm:text-sm py-2.5 rounded-xl shadow-xs transition-all text-center d-flex items-center justify-center gap-2"
                                id="tab-menu-soal" data-toggle="pill" href="#menu-soal-content" role="tab"
                                aria-controls="menu-soal-content" aria-selected="true"
                                style="border: 2px solid #6b21a8;">
                                <i class="fas fa-list-ol fa-lg text-amber-500"></i>
                                <span>Menu 1: Soal Latihan ({{ $selectedCategory->bankSoals->count() }} Pertanyaan)</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link font-bold text-xs sm:text-sm py-2.5 rounded-xl shadow-xs transition-all text-center d-flex items-center justify-center gap-2"
                                id="tab-menu-modul" data-toggle="pill" href="#menu-modul-content" role="tab"
                                aria-controls="menu-modul-content" aria-selected="false"
                                style="border: 2px solid #0284c7;">
                                <i class="fas fa-file-pdf fa-lg text-rose-500"></i>
                                <span>Menu 2: Modul PDF ({{ count($guruCatDocFiles) }} File)</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-3 p-sm-4 bg-slate-50/50">
                    <div class="tab-content" id="tabDetailSoalModulContent">

                        <!-- ════════════════════════════════════════════════════════════ -->
                        <!-- MENU 1: DAFTAR SOAL LATIHAN                                -->
                        <!-- ════════════════════════════════════════════════════════════ -->
                        <div class="tab-pane fade show active" id="menu-soal-content" role="tabpanel" aria-labelledby="tab-menu-soal">
                            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                                <h5 class="font-bold text-purple-950 mb-0 text-base sm:text-lg d-flex align-items-center gap-2">
                                    <i class="fas fa-tasks text-purple-600"></i> Daftar Soal ({{ $selectedCategory->bankSoals->count() }} Pertanyaan)
                                </h5>
                                <button class="btn btn-sm btn-outline-purple font-bold rounded-xl text-xs px-3 py-1.5 shrink-0" onclick="window.print()">
                                    <i class="fas fa-print mr-1"></i> Cetak Soal
                                </button>
                            </div>

                            @if ($selectedCategory->bankSoals->isEmpty())
                                <div class="text-center py-5 bg-white rounded-2xl border-2 border-dashed border-slate-200 p-4">
                                    <i class="fas fa-clipboard-list text-slate-300 fa-3x mb-3"></i>
                                    <h6 class="font-bold text-slate-700 mb-1">Belum ada pertanyaan terinput untuk paket soal ini.</h6>
                                    <p class="text-xs text-slate-500 mb-3">Buka menu <strong>Input Soal</strong> untuk menambahkan pertanyaan latihan baru.</p>
                                    <a href="{{ route('guru.bank-soal.index', ['jenjang' => $jenjang, 'kelas' => $kelas, 'sub_kategori' => $sub, 'mapel' => $mapel, 'kategori_id' => $selectedCategory->id]) }}" class="btn btn-sm btn-purple font-bold rounded-xl px-4 py-2 text-xs">
                                        <i class="fas fa-plus-circle mr-1"></i> Input Soal Sekarang
                                    </a>
                                </div>
                            @else
                                <div class="d-flex flex-column gap-3" id="soalDetailContainer">
                                    @foreach ($selectedCategory->bankSoals->sortBy('nomor') as $soalIndex => $soalItem)
                                        <div class="card border border-slate-200 rounded-2xl shadow-xs overflow-hidden bg-white soal-card-item">
                                            <div class="card-header bg-slate-100 py-2.5 px-3.5 d-flex justify-content-between align-items-center border-bottom">
                                                <span class="badge bg-purple-900 text-white font-bold px-2.5 py-1 rounded-lg text-xs">
                                                    Soal No. {{ $soalItem->nomor }}
                                                </span>
                                                <span class="badge bg-emerald-100 text-emerald-800 font-bold px-2.5 py-1 rounded-lg text-xs">
                                                    Kunci Jawaban: <strong>{{ $soalItem->kunci_jawaban }}</strong>
                                                </span>
                                            </div>
                                            <div class="card-body p-3.5">
                                                <div class="font-bold text-slate-900 mb-3 text-sm whitespace-pre-line leading-relaxed soal-text-content">
                                                    {{ $soalItem->soal }}
                                                </div>
                                                <div class="row g-2">
                                                    @foreach (['A' => $soalItem->opsi_a, 'B' => $soalItem->opsi_b, 'C' => $soalItem->opsi_c, 'D' => $soalItem->opsi_d] as $optKey => $optVal)
                                                        @php $isCorrect = $soalItem->kunci_jawaban === $optKey; @endphp
                                                        <div class="col-12 col-sm-6">
                                                            <div class="p-2.5 rounded-xl border text-xs d-flex align-items-start gap-2.5 {{ $isCorrect ? 'border-emerald-500 bg-emerald-50 text-emerald-950 font-bold' : 'border-slate-200 bg-white text-slate-700' }}">
                                                                <span class="badge {{ $isCorrect ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700' }} rounded-circle d-flex items-center justify-center shrink-0" style="width: 22px; height: 22px;">
                                                                    {{ $optKey }}
                                                                </span>
                                                                <span class="flex-1 whitespace-pre-line leading-snug">{{ $optVal }}</span>
                                                                @if($isCorrect)
                                                                    <i class="fas fa-check-circle text-emerald-600 fa-lg shrink-0 mt-0.5"></i>
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

                        <!-- ════════════════════════════════════════════════════════════ -->
                        <!-- MENU 2: DOKUMEN MODUL PDF                                   -->
                        <!-- ════════════════════════════════════════════════════════════ -->
                        <div class="tab-pane fade" id="menu-modul-content" role="tabpanel" aria-labelledby="tab-menu-modul">
                            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                                <h5 class="font-bold text-purple-950 mb-0 text-base sm:text-lg d-flex align-items-center gap-2">
                                    <i class="fas fa-file-pdf text-rose-500"></i> Dokumen Modul PDF Pembelajaran ({{ count($guruCatDocFiles) }} File)
                                </h5>
                            </div>

                            @if (count($guruCatDocFiles) === 0)
                                <div class="text-center py-5 bg-white rounded-2xl border-2 border-dashed border-slate-200 p-4">
                                    <i class="fas fa-file-pdf text-slate-300 fa-3x mb-3"></i>
                                    <h6 class="font-bold text-slate-700 mb-1">Belum ada dokumen modul PDF terunggah untuk paket ini.</h6>
                                    <p class="text-xs text-slate-500 mb-3">Buka menu <strong>Input Soal &gt; Upload PDF / Word</strong> untuk menambahkan dokumen modul.</p>
                                </div>
                            @else
                                <div class="d-flex flex-column gap-2">
                                    @foreach($guruCatDocFiles as $gDocIdx => $gDocPath)
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
                                                    <button type="button" class="btn btn-xs font-bold rounded-xl px-4 py-2 text-xs text-white shadow-xs" data-toggle="modal" data-target="#modalDetailPreviewDoc_{{ $gDocIdx }}" style="background: linear-gradient(135deg, #6b21a8, #4c1d95) !important; color: #ffffff !important; border: none !important;">
                                                        <i class="fas fa-book-reader text-amber-300 mr-1"></i> Baca Dokumen PDF
                                                    </button>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- MODAL PREVIEW PDF (PROTEKSI UNDUH & FULLSCREEN) -->
                                        @if($isPdf)
                                            <div class="modal fade p-0" id="modalDetailPreviewDoc_{{ $gDocIdx }}" tabindex="-1" role="dialog" aria-hidden="true" style="padding-right: 0 !important;">
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
                            @endif
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- MathJax for rendering Mathematical equations -->
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
@endsection
