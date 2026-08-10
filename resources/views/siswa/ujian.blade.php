@extends('layout.app')

@section('title', 'Latihan Soal & Ujian · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header py-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-7">
                    <h1 class="m-0 font-weight-bold text-purple-950 d-flex align-items-center">
                        <i class="fas fa-pencil-alt text-purple-600 mr-2.5"></i> Latihan Soal &amp; Ujian Online
                    </h1>
                    <p class="text-sm text-slate-500 mb-0 mt-1">
                        Uji pemahaman materi Anda berdasarkan Jenjang (SD, SMP, SMA) dan Sub-Kategori (Semester 1, 2, TKA).
                    </p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right text-sm bg-transparent p-0 m-0">
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

                <!-- 1. TABS JENJANG (SD, SMP, SMA) -->
                <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                    <div class="card-body p-2 bg-purple-50/50">
                        <ul class="nav nav-pills nav-justified gap-2">
                            @foreach (['SD' => ['Sekolah Dasar', 'fa-child'], 'SMP' => ['Sekolah Menengah Pertama', 'fa-user-graduate'], 'SMA' => ['Sekolah Menengah Atas', 'fa-university']] as $key => $info)
                                <li class="nav-item">
                                    <a href="{{ route('siswa.ujian', ['jenjang' => $key, 'sub_kategori' => 'Semester 1']) }}" 
                                       class="nav-link py-3 px-4 font-bold rounded-xl d-flex align-items-center justify-content-center transition-all duration-200 {{ $jenjang === $key ? 'bg-purple-900 text-white shadow-md' : 'text-slate-600 hover:bg-purple-100 hover:text-purple-900' }}">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-2.5 shadow-sm" 
                                             style="width: 32px; height: 32px; background: {{ $jenjang === $key ? 'rgba(255,255,255,0.2)' : '#eef2ff' }};">
                                            <i class="fas {{ $info[1] }} {{ $jenjang === $key ? 'text-white' : 'text-purple-700' }}"></i>
                                        </div>
                                        <div class="text-left">
                                            <span class="d-block text-base leading-tight font-extrabold">Jenjang {{ $key }}</span>
                                            <span class="d-block text-[11px] opacity-80 font-normal">{{ $info[0] }}</span>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- 2. SUB-KATEGORI PILLS -->
                <div class="d-flex align-items-center flex-wrap gap-2 mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-2">Pilih Cabang / Semester:</span>
                    @foreach ($allSubKategori as $sub)
                        <a href="{{ route('siswa.ujian', ['jenjang' => $jenjang, 'sub_kategori' => $sub]) }}"
                           class="btn btn-sm font-semibold rounded-full px-3.5 py-1.5 text-xs transition-all {{ $sub_kategori === $sub ? 'btn-purple shadow-sm text-white' : 'btn-light border border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                            <i class="fas {{ $sub === 'TKA' ? 'fa-star text-amber-400' : 'fa-bookmark text-purple-400' }} mr-1.5"></i>
                            {{ $sub }}
                        </a>
                    @endforeach
                </div>

                <!-- 3. DAFTAR KATEGORI UJIAN TERSEDIA -->
                <div class="mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="font-bold text-purple-950 text-lg mb-0 d-flex align-items-center">
                            <i class="fas fa-layer-group text-purple-600 mr-2"></i> Daftar Paket Ujian (Jenjang {{ $jenjang }} - {{ $sub_kategori }})
                        </h4>
                        <span class="badge bg-purple-100 text-purple-900 font-bold px-3 py-1 rounded-full text-xs">
                            {{ $categories->count() }} Paket Soal
                        </span>
                    </div>

                    @if ($categories->isEmpty())
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
                    @else
                        <div class="row">
                            @foreach ($categories as $cat)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card border-0 shadow-sm rounded-2xl bg-white h-100 transition-all hover:shadow-md hover:-translate-y-1 overflow-hidden d-flex flex-column">
                                        <div class="card-header bg-gradient-to-r from-purple-900 to-indigo-900 text-white p-4 border-0">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
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
