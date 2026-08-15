@extends('layout.app')

@section('title', 'Transkip Nilai · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header py-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-7">
                    <h1 class="m-0 font-weight-bold text-purple-950 d-flex align-items-center page-title-mobile">
                        <i class="fas fa-file-alt text-purple-600 mr-2.5"></i> Transkip Nilai
                    </h1>
                    <p class="text-sm text-slate-500 mb-0 mt-1">
                        Rekap nilai hasil ujian Anda berdasarkan semester &amp; mata pelajaran.
                    </p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right text-sm bg-transparent p-0 m-0 mt-2 mt-sm-0">
                        <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}" class="text-purple-600 font-semibold">Dashboard</a></li>
                        <li class="breadcrumb-item active text-slate-500">Transkip Nilai</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content pb-5">
        <div class="container-fluid">

            <!-- Filter Semester (Dropdown) -->
            <div class="card border-0 shadow-sm rounded-2xl mb-3 bg-white">
                <div class="card-body p-3.5 d-flex flex-wrap align-items-center gap-3">
                    <label for="semesterSelect" class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-0 mr-1">
                        <i class="fas fa-filter text-purple-600 mr-1"></i> Pilih Semester:
                    </label>
                    <select id="semesterSelect" class="form-control font-semibold text-sm rounded-xl" style="max-width: 260px; border-color: #ddd6fe;" onchange="window.location.href = '{{ route('siswa.transkip-nilai') }}?semester=' + encodeURIComponent(this.value);">
                        @foreach ($availableSemesters as $sem)
                            <option value="{{ $sem }}" {{ $semester === $sem ? 'selected' : '' }}>{{ $sem }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if ($rekapMapel->isEmpty())
                <!-- Empty State -->
                <div class="card border-0 shadow-sm rounded-2xl text-center py-5 px-4 bg-white">
                    <div class="card-body">
                        <div class="mx-auto mb-3 rounded-full bg-purple-50 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="fas fa-file-alt text-purple-400 fa-2x"></i>
                        </div>
                        <h5 class="font-bold text-purple-950 mb-1">Belum Ada Nilai untuk {{ $semester }}</h5>
                        <p class="text-slate-500 text-sm max-w-md mx-auto mb-3">
                            Anda belum mengerjakan ujian apapun pada kategori <strong>{{ $semester }}</strong>. Kerjakan latihan soal terlebih dahulu untuk melihat rekap nilai di sini.
                        </p>
                        <a href="{{ route('siswa.ujian') }}" class="btn btn-purple font-bold rounded-xl px-4 py-2 text-xs shadow-sm">
                            <i class="fas fa-pencil-alt mr-1.5"></i> Kerjakan Latihan Soal
                        </a>
                    </div>
                </div>
            @else
                <!-- Summary Row (Compact, tetap 3 kolom sejajar meskipun di mobile) -->
                <div class="row mb-4 g-2 summary-row">
                    <div class="col-4">
                        <div class="card border-0 shadow-sm rounded-2xl bg-white h-100 summary-card">
                            <div class="card-body p-2.5 p-md-3.5 d-flex flex-column flex-md-row align-items-center text-center text-md-left">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mb-1.5 mb-md-0 mr-md-3 summary-icon" style="background: #ede9fe;">
                                    <i class="fas fa-book text-purple-700"></i>
                                </div>
                                <div>
                                    <span class="d-block font-black text-purple-950 summary-value">{{ $rekapMapel->count() }}</span>
                                    <span class="d-block text-slate-500 font-bold uppercase summary-label">Mapel</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card border-0 shadow-sm rounded-2xl bg-white h-100 summary-card">
                            <div class="card-body p-2.5 p-md-3.5 d-flex flex-column flex-md-row align-items-center text-center text-md-left">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mb-1.5 mb-md-0 mr-md-3 summary-icon" style="background: #fef3c7;">
                                    <i class="fas fa-chart-line text-amber-600"></i>
                                </div>
                                <div>
                                    <span class="d-block font-black text-purple-950 summary-value">{{ number_format($rataRata, 1) }}</span>
                                    <span class="d-block text-slate-500 font-bold uppercase summary-label">Rata-Rata</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card border-0 shadow-sm rounded-2xl bg-white h-100 summary-card">
                            <div class="card-body p-2.5 p-md-3.5 d-flex flex-column flex-md-row align-items-center text-center text-md-left">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mb-1.5 mb-md-0 mr-md-3 summary-icon" style="background: #d1fae5;">
                                    <i class="fas fa-bookmark text-emerald-600"></i>
                                </div>
                                <div>
                                    <span class="d-block font-black text-purple-950 summary-value summary-value-text">{{ $semester }}</span>
                                    <span class="d-block text-slate-500 font-bold uppercase summary-label">Kategori</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daftar Nilai per Mata Pelajaran -->
                <div class="card border-0 shadow-sm rounded-2xl bg-white overflow-hidden">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h5 class="card-title font-bold text-purple-950 mb-0 d-flex align-items-center" style="font-size: 0.95rem;">
                            <i class="fas fa-list-alt text-purple-600 mr-2"></i> Rekap Nilai — {{ $semester }}
                        </h5>
                    </div>

                    <!-- ── Tampilan Tabel (Desktop / Tablet ke atas) ── -->
                    <div class="card-body p-0 d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-slate-500 text-xs uppercase font-bold">
                                    <tr>
                                        <th class="px-4 py-3">Mata Pelajaran</th>
                                        <th class="px-4 py-3 text-center">Percobaan</th>
                                        <th class="px-4 py-3 text-center">Nilai Terakhir</th>
                                        <th class="px-4 py-3 text-center">Nilai Terbaik</th>
                                        <th class="px-4 py-3 text-center">Predikat</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @foreach ($rekapMapel as $rk)
                                        @php $nilaiTerbaik = $rk['nilai_terbaik']; @endphp
                                        <tr>
                                            <td class="px-4 py-3 font-bold text-purple-950">
                                                {{ $rk['kategori']->nama_kategori ?? 'Mata Pelajaran' }}
                                                <span class="d-block text-[11px] text-slate-500 font-normal">
                                                    {{ $rk['kategori']->jenjang ?? '' }} • {{ $rk['kategori']->deskripsi ?? '' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center font-bold text-slate-700">
                                                {{ $rk['jumlah_percobaan'] }}x
                                            </td>
                                            <td class="px-4 py-3 text-center font-bold text-slate-700">
                                                {{ number_format($rk['nilai_terakhir'], 1) }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="font-extrabold text-base {{ $nilaiTerbaik >= 75 ? 'text-emerald-600' : ($nilaiTerbaik >= 60 ? 'text-amber-600' : 'text-rose-600') }}">
                                                    {{ number_format($nilaiTerbaik, 1) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($nilaiTerbaik >= 90)
                                                    <span class="badge bg-emerald-100 text-emerald-800 border border-emerald-200 px-2.5 py-1 rounded-pill font-bold">Sangat Baik 🌟</span>
                                                @elseif ($nilaiTerbaik >= 75)
                                                    <span class="badge bg-blue-100 text-blue-800 border border-blue-200 px-2.5 py-1 rounded-pill font-bold">Baik 👍</span>
                                                @elseif ($nilaiTerbaik >= 60)
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

                    <!-- ── Tampilan Kartu (Mobile) ── -->
                    <div class="card-body p-3 d-block d-md-none">
                        <div class="d-flex flex-column gap-2">
                            @foreach ($rekapMapel as $rk)
                                @php $nilaiTerbaik = $rk['nilai_terbaik']; @endphp
                                <div class="mapel-mobile-card p-3 rounded-2xl border" style="border-color: #ede9fe; background-color: #faf9fd;">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="pr-2">
                                            <span class="d-block font-bold text-purple-950 text-sm">
                                                {{ $rk['kategori']->nama_kategori ?? 'Mata Pelajaran' }}
                                            </span>
                                            <span class="d-block text-[11px] text-slate-500 font-normal mt-0.5">
                                                {{ $rk['kategori']->jenjang ?? '' }} • {{ $rk['kategori']->deskripsi ?? '' }}
                                            </span>
                                        </div>
                                        <span class="font-extrabold text-xl shrink-0 {{ $nilaiTerbaik >= 75 ? 'text-emerald-600' : ($nilaiTerbaik >= 60 ? 'text-amber-600' : 'text-rose-600') }}">
                                            {{ number_format($nilaiTerbaik, 1) }}
                                        </span>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2 border-top" style="border-color: #ede9fe !important;">
                                        <div class="d-flex gap-3">
                                            <span class="text-[11px] text-slate-500 font-semibold">
                                                <i class="fas fa-redo text-purple-400 mr-1"></i>{{ $rk['jumlah_percobaan'] }}x Percobaan
                                            </span>
                                            <span class="text-[11px] text-slate-500 font-semibold">
                                                <i class="fas fa-history text-purple-400 mr-1"></i>Terakhir: {{ number_format($rk['nilai_terakhir'], 1) }}
                                            </span>
                                        </div>
                                        @if ($nilaiTerbaik >= 90)
                                            <span class="badge bg-emerald-100 text-emerald-800 border border-emerald-200 px-2 py-1 rounded-pill font-bold text-[10px]">Sangat Baik 🌟</span>
                                        @elseif ($nilaiTerbaik >= 75)
                                            <span class="badge bg-blue-100 text-blue-800 border border-blue-200 px-2 py-1 rounded-pill font-bold text-[10px]">Baik 👍</span>
                                        @elseif ($nilaiTerbaik >= 60)
                                            <span class="badge bg-amber-100 text-amber-800 border border-amber-200 px-2 py-1 rounded-pill font-bold text-[10px]">Cukup ⚡</span>
                                        @else
                                            <span class="badge bg-rose-100 text-rose-800 border border-rose-200 px-2 py-1 rounded-pill font-bold text-[10px]">Perlu Belajar 💪</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>

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

        /* ── Summary Cards (Desktop default) ── */
        .summary-icon {
            width: 46px;
            height: 46px;
        }
        .summary-value {
            font-size: 1.25rem;
        }
        .summary-value-text {
            font-size: 1rem;
        }
        .summary-label {
            font-size: 11px;
        }

        /* ── Responsive Mobile Tweaks ── */
        @media (max-width: 576px) {
            .page-title-mobile {
                font-size: 1.15rem;
                line-height: 1.3;
            }
            .content-header {
                padding-top: 0.75rem !important;
                padding-bottom: 0.75rem !important;
            }

            /* Perkecil kartu ringkasan biar 3 kolom tetap muat sejajar */
            .summary-icon {
                width: 32px;
                height: 32px;
                font-size: 0.75rem;
            }
            .summary-value {
                font-size: 1rem;
                line-height: 1.2;
            }
            .summary-value-text {
                font-size: 0.7rem;
                line-height: 1.2;
            }
            .summary-label {
                font-size: 9px;
                letter-spacing: 0.02em;
            }
            .summary-row {
                margin-left: -4px;
                margin-right: -4px;
            }
            .summary-row > .col-4 {
                padding-left: 4px;
                padding-right: 4px;
            }

            .mapel-mobile-card {
                padding: 0.85rem !important;
            }
        }
    </style>
@endsection