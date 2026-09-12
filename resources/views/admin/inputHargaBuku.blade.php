@extends('layout.app')

@section('title', 'Kelola Harga Buku · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header py-3">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950 d-flex align-items-center">
                        <i class="fas fa-book-open text-amber-500 mr-2.5"></i> Kelola Harga Buku Pembelajaran
                    </h1>
                    <p class="text-sm text-slate-500 mb-0 mt-1">
                        Atur nominal harga buku modul untuk masing-masing mata pelajaran. Nominal ini otomatis dihitung pada estimasi pembayaran siswa.
                    </p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm bg-transparent p-0 m-0 mt-2 mt-sm-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600 font-semibold">Dashboard</a></li>
                        <li class="breadcrumb-item active text-slate-500">Kelola Harga Buku</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content pb-5">
        <div class="container-fluid">
            
            <!-- Alert success -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 rounded-2xl shadow-sm border-0 d-flex align-items-center" role="alert" style="background-color: #d1fae5; color: #065f46; border-left: 4px solid #10b981;">
                    <i class="fas fa-check-circle fa-lg mr-3 text-emerald-600"></i>
                    <div>
                        <strong class="font-bold">Sukses!</strong> {{ session('success') }}
                    </div>
                    <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" class="text-emerald-800">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Errors alert -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-2xl shadow-sm border-0 d-flex align-items-center" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444;">
                    <i class="fas fa-exclamation-triangle fa-lg mr-3 text-rose-500"></i>
                    <div>
                        <strong class="font-bold">Gagal!</strong> {{ $errors->first() }}
                    </div>
                    <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" class="text-rose-800">&times;</span>
                    </button>
                </div>
            @endif

            @php
                $totalMapel = $mapels->count();
                $avgPrice = $totalMapel > 0 ? (int) $mapels->avg('harga_buku') : 0;
                $maxMapel = $mapels->sortByDesc('harga_buku')->first();
            @endphp

            <!-- Summary Stats Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-sm-6 mb-3 mb-lg-0">
                    <div class="card border-0 shadow-xs rounded-2xl bg-white p-3.5 h-100 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Mapel</span>
                            <h3 class="font-bold text-purple-950 text-2xl mb-0">{{ $totalMapel }} <span class="text-xs font-semibold text-slate-500">Mata Pelajaran</span></h3>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0 border border-purple-100">
                            <i class="fas fa-book font-bold"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 mb-3 mb-lg-0">
                    <div class="card border-0 shadow-xs rounded-2xl bg-white p-3.5 h-100 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Rata-Rata Harga</span>
                            <h3 class="font-bold text-emerald-600 text-2xl mb-0">Rp {{ number_format($avgPrice, 0, ',', '.') }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0 border border-emerald-100">
                            <i class="fas fa-coins font-bold"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 mb-3 mb-sm-0">
                    <div class="card border-0 shadow-xs rounded-2xl bg-white p-3.5 h-100 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Harga Tertinggi</span>
                            <h3 class="font-bold text-purple-900 text-lg mb-0 text-truncate" style="max-width: 150px;">
                                {{ $maxMapel ? $maxMapel->nama_mapel : '-' }}
                            </h3>
                            <span class="text-xs text-purple-600 font-bold">Rp {{ number_format($maxMapel->harga_buku ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0 border border-amber-100">
                            <i class="fas fa-award font-bold"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card border-0 shadow-xs rounded-2xl bg-gradient-to-r from-purple-900 to-indigo-900 text-white p-3.5 h-100 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <span class="text-xs font-bold text-purple-200 uppercase tracking-wider block mb-1">Formula Otomatis</span>
                            <h6 class="font-bold text-amber-300 text-xs mb-1">Wajib + Lanjut Auto Sum</h6>
                            <span class="text-xxs text-purple-100 opacity-90 block">Menjumlahkan otomatis nominal 2 mapel</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/10 text-amber-300 flex items-center justify-center text-lg shrink-0">
                            <i class="fas fa-calculator font-bold"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Guide Banner -->
            <div class="p-4 bg-gradient-to-r from-purple-50 via-white to-purple-50 rounded-2xl border border-purple-100 mb-4 shadow-2xs">
                <div class="d-flex align-items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold shrink-0 shadow-xs">
                        <i class="fas fa-info-circle text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <h6 class="font-bold text-purple-950 mb-1 text-sm">Panduan Pengaturan Nominal Harga Buku:</h6>
                        <p class="text-xs text-slate-600 mb-0 leading-relaxed">
                            Masukkan nominal harga buku modul dalam mata uang Rupiah (Rp). Khusus untuk mapel <strong>"Matematika Wajib + Lanjut"</strong>, kolom bernominal otomatis terkunci dan akan memperbarui nilainya secara <em>real-time</em> dari penjumlahan <strong>Matematika Wajib + Matematika Lanjut</strong>.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form & Table Container Card -->
            <div class="card border-0 shadow-sm rounded-2xl bg-white overflow-hidden">
                <div class="card-header bg-white py-3.5 px-4 border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-800 flex items-center justify-center font-bold text-sm">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-purple-950 text-base mb-0">Daftar Nominal Buku per Mata Pelajaran</h5>
                            <span class="text-xs text-slate-400">Total {{ $totalMapel }} data mata pelajaran terdaftar</span>
                        </div>
                    </div>

                    <!-- Search Filter Box -->
                    <div class="position-relative w-100 w-sm-auto" style="min-width: 240px;">
                        <i class="fas fa-search position-absolute text-slate-400" style="left: 12px; top: 50%; transform: translateY(-50%); font-size: 0.85rem;"></i>
                        <input type="text" id="searchMapelInput" class="form-control form-control-sm pl-4 pr-3 rounded-xl border-purple-200 text-xs font-semibold" placeholder="Cari nama mapel..." onkeyup="filterMapelTable()">
                    </div>
                </div>

                <div class="card-body p-0">
                    @if ($mapels->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-book-open text-slate-300 fa-3x mb-3"></i>
                            <p class="text-slate-500 text-sm mb-0">Belum ada data mata pelajaran saat ini.</p>
                            <a href="{{ route('admin.mapel') }}" class="btn btn-purple btn-sm rounded-xl font-bold mt-3">
                                <i class="fas fa-plus mr-1"></i> Tambah Mata Pelajaran Baru
                            </a>
                        </div>
                    @else
                        <form action="{{ route('admin.harga-buku.update') }}" method="POST" id="hargaBukuForm">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="mapelHargaTable">
                                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-bottom">
                                        <tr>
                                            <th class="px-4 py-3 text-center" style="width: 60px;">No</th>
                                            <th class="px-4 py-3"><i class="fas fa-book text-purple-600 mr-1.5"></i> Mata Pelajaran</th>
                                            <th class="px-4 py-3 text-center" style="width: 140px;"><i class="fas fa-redo-alt text-purple-600 mr-1.5"></i> Pertemuan</th>
                                            <th class="px-4 py-3" style="min-width: 260px;"><i class="fas fa-tag text-purple-600 mr-1.5"></i> Nominal Harga Buku (Rp)</th>
                                            <th class="px-4 py-3 text-right" style="min-width: 200px;"><i class="fas fa-shield-alt text-purple-600 mr-1.5"></i> Aturan Rumus</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach ($mapels as $index => $mapel)
                                            @php
                                                $mapelLower = strtolower($mapel->nama_mapel);
                                                $isGabungan = (stripos($mapel->nama_mapel, 'Matematika Wajib + Lanjut') !== false || stripos($mapel->nama_mapel, 'Wajib + Lanjut') !== false);
                                                $isWajib = (stripos($mapel->nama_mapel, 'Matematika Wajib') !== false && !$isGabungan);
                                                $isLanjut = (stripos($mapel->nama_mapel, 'Matematika Lanjut') !== false && !$isGabungan);
                                            @endphp
                                            <tr class="mapel-row transition-all hover:bg-purple-50/30">
                                                <td class="px-4 py-3 text-center text-slate-400 font-mono text-xs font-bold">{{ $index + 1 }}</td>
                                                <td class="px-4 py-3">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs shrink-0 {{ $isGabungan ? 'bg-amber-100 text-amber-800' : 'bg-purple-50 text-purple-700' }}">
                                                            <i class="fas {{ $isGabungan ? 'fa-calculator' : 'fa-book' }}"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="font-bold text-purple-950 text-sm mb-0 mapel-name">{{ $mapel->nama_mapel }}</h6>
                                                            @if($isGabungan)
                                                                <span class="text-xxs text-amber-700 font-semibold block"><i class="fas fa-sync-alt fa-spin text-amber-500 mr-0.5"></i> Auto calculated from Wajib &amp; Lanjut</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="badge bg-purple-100 text-purple-900 border border-purple-200 px-2.5 py-1 text-xs font-bold rounded-pill">
                                                        {{ $mapel->shift }}x / minggu
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="input-group input-group-sm rounded-xl overflow-hidden border border-purple-200 shadow-2xs focus-within:border-purple-600 focus-within:ring-2 focus-within:ring-purple-100">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text font-bold text-purple-900 bg-purple-50 border-0 px-3">Rp</span>
                                                        </div>
                                                        <input type="number" 
                                                               name="harga_buku[{{ $mapel->id }}]" 
                                                               value="{{ old('harga_buku.'.$mapel->id, $mapel->harga_buku ?? 0) }}" 
                                                               class="form-control font-bold text-purple-950 border-0 py-2 price-input {{ $isWajib ? 'input-wajib' : '' }} {{ $isLanjut ? 'input-lanjut' : '' }} {{ $isGabungan ? 'input-gabungan' : '' }}" 
                                                               placeholder="0" 
                                                               min="0"
                                                               oninput="recalculateGabungan()"
                                                               {{ $isGabungan ? 'readonly style=background-color:#f8fafc;cursor:not-allowed;' : '' }}>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    @if($isGabungan)
                                                        <span class="badge bg-amber-100 text-amber-900 border border-amber-300 px-3 py-1 text-xs font-bold rounded-pill">
                                                            <i class="fas fa-lock mr-1 text-amber-600"></i> Penjumlahan Otomatis
                                                        </span>
                                                    @else
                                                        <span class="badge bg-slate-100 text-slate-600 border border-slate-200 px-2.5 py-1 text-xs font-semibold rounded-pill">
                                                            Harga Standar Mapel
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Footer Save Actions -->
                            <div class="card-footer bg-white p-4 border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                                <span class="text-xs text-slate-500 font-medium">
                                    <i class="fas fa-check-circle mr-1 text-emerald-500"></i> Pastikan seluruh nominal harga buku telah diisi sebelum menyimpan.
                                </span>
                                <button type="submit" class="btn btn-purple font-extrabold rounded-xl px-5 py-2.5 shadow-md text-sm transition-all hover:scale-[1.02]">
                                    <i class="fas fa-save mr-2 text-amber-300"></i> Simpan Perubahan Harga Buku
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Custom Styling & JS Scripts -->
    <style>
        .btn-purple {
            color: #ffffff;
            background-color: #7c3aed;
            border-color: #7c3aed;
        }
        .btn-purple:hover {
            color: #ffffff;
            background-color: #6d28d9;
            border-color: #6d28d9;
        }
    </style>

    <script>
        function filterMapelTable() {
            const input = document.getElementById('searchMapelInput');
            const filter = input.value.toLowerCase();
            const rows = document.querySelectorAll('#mapelHargaTable tbody tr.mapel-row');

            rows.forEach(row => {
                const mapelNameElem = row.querySelector('.mapel-name');
                if (mapelNameElem) {
                    const text = mapelNameElem.textContent || mapelNameElem.innerText;
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }

        function recalculateGabungan() {
            const inputWajib = document.querySelector('.input-wajib');
            const inputLanjut = document.querySelector('.input-lanjut');
            const inputGabungan = document.querySelector('.input-gabungan');

            if (inputWajib && inputLanjut && inputGabungan) {
                const valWajib = parseInt(inputWajib.value, 10) || 0;
                const valLanjut = parseInt(inputLanjut.value, 10) || 0;
                inputGabungan.value = valWajib + valLanjut;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            recalculateGabungan();
        });
    </script>
@endsection
