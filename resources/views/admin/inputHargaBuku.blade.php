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
                        Atur nominal harga buku modul untuk masing-masing mata pelajaran. Nominal ini akan otomatis dihitung dalam perkiraan biaya dan pembayaran siswa.
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

            <!-- Information Card -->
            <div class="card border-0 shadow-xs rounded-2xl mb-4 bg-amber-50/70 border-amber-200">
                <div class="card-body p-4 text-xs text-amber-950">
                    <div class="d-flex align-items-start gap-3">
                        <div class="p-2 bg-amber-400 text-purple-950 rounded-xl font-bold shrink-0">
                            <i class="fas fa-lightbulb fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="font-bold text-amber-950 mb-1 text-sm">Informasi Penting Mengenai Harga Buku:</h6>
                            <ul class="mb-0 pl-3 space-y-1 text-slate-700">
                                <li>Isi nominal harga buku modul untuk setiap mata pelajaran dalam rupiah (Rp).</li>
                                <li>Khusus untuk <strong>"Matematika Wajib + Lanjut"</strong>, harga bukunya secara otomatis dihitung dari penjumlahan <strong>Harga Buku Matematika Wajib + Harga Buku Matematika Lanjut</strong>.</li>
                                <li>Perubahan harga buku akan langsung diperbarui pada estimasi ringkasan harga dan pembayaran siswa.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card border-0 shadow-sm rounded-2xl bg-white overflow-hidden">
                <div class="card-header bg-gradient-to-r from-purple-900 to-indigo-900 text-white p-4 d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-bold text-base mb-0 d-flex align-items-center text-white">
                        <i class="fas fa-list-ol text-amber-300 mr-2"></i> Daftar Nominal Harga Buku per Mapel
                    </h5>
                    <span class="badge bg-white/20 text-white font-bold px-3 py-1 rounded-full text-xs">
                        {{ $mapels->count() }} Mata Pelajaran
                    </span>
                </div>
                <div class="card-body p-0">
                    @if ($mapels->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-book-open text-slate-300 fa-3x mb-3"></i>
                            <p class="text-slate-500 text-sm mb-0">Belum ada data mata pelajaran.</p>
                            <a href="{{ route('admin.mapel') }}" class="btn btn-purple btn-sm rounded-xl font-bold mt-3">
                                <i class="fas fa-plus mr-1"></i> Tambah Mata Pelajaran Baru
                            </a>
                        </div>
                    @else
                        <form action="{{ route('admin.harga-buku.update') }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-slate-100 text-slate-600 text-xs uppercase font-bold">
                                        <tr>
                                            <th class="px-4 py-3">No</th>
                                            <th class="px-4 py-3">Nama Mata Pelajaran</th>
                                            <th class="px-4 py-3">Shift</th>
                                            <th class="px-4 py-3" style="min-width: 250px;">Nominal Harga Buku (Rp)</th>
                                            <th class="px-4 py-3 text-right">Keterangan Aturan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach ($mapels as $index => $mapel)
                                            @php
                                                $isGabungan = (stripos($mapel->nama_mapel, 'Matematika Wajib + Lanjut') !== false || stripos($mapel->nama_mapel, 'Wajib + Lanjut') !== false);
                                            @endphp
                                            <tr>
                                                <td class="px-4 py-3 text-slate-500 font-mono text-sm">{{ $index + 1 }}</td>
                                                <td class="px-4 py-3 font-bold text-purple-950">
                                                    {{ $mapel->nama_mapel }}
                                                    @if($isGabungan)
                                                        <span class="badge bg-amber-100 text-amber-900 border border-amber-300 px-2 py-0.5 rounded-md text-[10px] ml-1 font-bold">Gabungan Auto</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="badge bg-purple-100 text-purple-800 px-2.5 py-1 text-[11px] font-bold rounded-pill">
                                                        {{ $mapel->shift }}x / minggu
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text font-bold text-slate-600 bg-slate-100 border-slate-200">Rp</span>
                                                        </div>
                                                        <input type="number" 
                                                               name="harga_buku[{{ $mapel->id }}]" 
                                                               value="{{ old('harga_buku.'.$mapel->id, $mapel->harga_buku ?? 0) }}" 
                                                               class="form-control font-bold text-slate-800 border-slate-200 rounded-right" 
                                                               placeholder="0" 
                                                               min="0"
                                                               {{ $isGabungan ? 'readonly style=background-color:#f8fafc;' : '' }}>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-right text-xs text-slate-500">
                                                    @if($isGabungan)
                                                        <span class="text-amber-700 font-semibold"><i class="fas fa-calculator mr-1"></i> Penjumlahan Wajib + Lanjut</span>
                                                    @else
                                                        <span class="text-slate-400">Harga Standar Mapel</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer bg-white p-4 border-top d-flex justify-content-between align-items-center">
                                <span class="text-xs text-slate-500">
                                    <i class="fas fa-info-circle mr-1 text-purple-600"></i> Klik tombol di sebelah kanan untuk menyimpan seluruh perubahan nominal harga buku.
                                </span>
                                <button type="submit" class="btn btn-purple font-extrabold rounded-xl px-5 py-2.5 shadow-md text-sm">
                                    <i class="fas fa-save mr-1.5 text-amber-300"></i> Simpan Perubahan Harga Buku
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
