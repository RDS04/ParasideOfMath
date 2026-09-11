@extends('layout.app')

@section('title', 'Kelola Rating & Ulasan Siswa · Paradise of Math')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-purple-950 text-2xl tracking-tight">
                    <i class="fas fa-star text-amber-400 mr-2"></i>Kelola Rating & Ulasan Siswa
                </h1>
                <p class="text-xs text-muted mb-0">Kelola tanggapan, tingkat kepuasan, ulasan, dan penilaian bintang (1-5) dari siswa/orangtua pengunjung.</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right text-xs bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600 font-semibold"><i class="fas fa-home mr-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active text-slate-500">Kelola Rating</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert" style="background-color: #d1fae5; border-color: #a7f3d0; color: #065f46;">
                <h6 class="font-weight-bold mb-1"><i class="icon fas fa-check-circle mr-1"></i> Berhasil!</h6>
                <span class="text-xs">{{ session('success') }}</span>
                <button type="button" class="close text-emerald-900" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert">
                <h6 class="font-weight-bold mb-1"><i class="icon fas fa-exclamation-circle mr-1"></i> Gagal:</h6>
                <span class="text-xs">{{ session('error') }}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm rounded-2xl bg-white overflow-hidden h-100">
                    <div class="card-body p-3 flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl shrink-0 font-bold">
                            ⭐
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-0">Rata-rata Skor Rating</p>
                            <h3 class="text-2xl font-black text-slate-800 mb-0">{{ number_format($avgRating, 1) }} <span class="text-sm font-semibold text-slate-400">/ 5.0</span></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm rounded-2xl bg-white overflow-hidden h-100">
                    <div class="card-body p-3 flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0 font-bold">
                            💬
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-0">Total Ulasan Masuk</p>
                            <h3 class="text-2xl font-black text-slate-800 mb-0">{{ $totalRatings }} <span class="text-sm font-semibold text-slate-400">Ulasan</span></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm rounded-2xl bg-white overflow-hidden h-100">
                    <div class="card-body p-3 flex items-center justify-center gap-4">
                        <div class="text-center">
                            <span class="badge badge-success text-xs px-2.5 py-1 mb-1">Tampil / Aktif</span>
                            <h4 class="font-black text-emerald-700 mb-0 text-xl">{{ $approvedCount }}</h4>
                        </div>
                        <div class="w-px h-10 bg-slate-200"></div>
                        <div class="text-center">
                            <span class="badge badge-secondary text-xs px-2.5 py-1 mb-1">Disembunyikan</span>
                            <h4 class="font-black text-slate-600 mb-0 text-xl">{{ $pendingCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Rating -->
        <div class="card card-purple card-outline shadow-sm rounded-2xl overflow-hidden border-0 mb-5">
            <div class="card-header bg-white py-3 border-bottom flex items-center justify-between">
                <h3 class="card-title font-weight-bold text-sm text-purple-950 mb-0">
                    <i class="fas fa-list text-purple-600 mr-1.5"></i> Daftar Ulasan & Penilaian Bintang
                </h3>
                <span class="text-xs text-muted">Menampilkan semua rating pengunjung</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm">
                        <thead class="bg-purple-50 text-purple-950 text-xs uppercase tracking-wider font-bold">
                            <tr>
                                <th class="text-center py-3" style="width: 50px;">No</th>
                                <th class="py-3">Nama Pengirim</th>
                                <th class="text-center py-3" style="width: 140px;">Rating Bintang</th>
                                <th class="py-3">Ulasan / Deskripsi</th>
                                <th class="py-3" style="width: 150px;">Tanggal</th>
                                <th class="text-center py-3" style="width: 130px;">Status</th>
                                <th class="text-center py-3" style="width: 140px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ratings as $index => $item)
                                <tr>
                                    <td class="text-center align-middle font-weight-bold text-slate-500">{{ $index + 1 }}</td>
                                    <td class="align-middle">
                                        <div class="font-weight-bold text-purple-950">{{ $item->nama }}</div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="text-amber-400 text-sm whitespace-nowrap">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $item->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star text-slate-300"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-xs font-bold text-slate-600">({{ $item->rating }}/5)</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="text-slate-700 text-xs leading-relaxed max-w-md">
                                            "{{ $item->deskripsi }}"
                                        </div>
                                    </td>
                                    <td class="align-middle text-xs text-slate-500">
                                        {{ $item->created_at ? $item->created_at->translatedFormat('d M Y H:i') : '-' }}
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($item->status === 'approved')
                                            <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold inline-block">
                                                <i class="fas fa-check-circle mr-1"></i> Tampil
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-[11px] font-bold inline-block">
                                                <i class="fas fa-eye-slash mr-1"></i> Sembunyi
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group btn-group-sm">
                                            <form action="{{ route('admin.rating.toggle', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @if($item->status === 'approved')
                                                    <button type="submit" class="btn btn-outline-warning btn-sm rounded-lg mr-1" title="Sembunyikan dari landing page">
                                                        <i class="fas fa-eye-slash"></i>
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-outline-success btn-sm rounded-lg mr-1" title="Tampilkan di landing page">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                @endif
                                            </form>
                                            <button type="button" class="btn btn-outline-danger btn-sm rounded-lg" data-toggle="modal" data-target="#deleteModal{{ $item->id }}" title="Hapus Ulasan">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Modal Hapus -->
                                        <div class="modal fade text-left" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                <div class="modal-content rounded-2xl border-0 shadow-lg">
                                                    <div class="modal-body p-4 text-center">
                                                        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3 text-xl">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </div>
                                                        <h5 class="font-bold text-slate-800 text-base mb-1">Hapus Ulasan ini?</h5>
                                                        <p class="text-xs text-slate-500 mb-4">Ulasan dari <strong>"{{ $item->nama }}"</strong> akan dihapus permanen.</p>
                                                        <div class="flex gap-2 justify-center">
                                                            <button type="button" class="btn btn-light btn-sm rounded-xl font-bold px-3" data-dismiss="modal">Batal</button>
                                                            <form action="{{ route('admin.rating.delete', $item->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm rounded-xl font-bold px-3">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <div class="py-4">
                                            <i class="fas fa-star-half-alt text-slate-300 text-4xl mb-3"></i>
                                            <p class="text-sm font-semibold mb-0 text-slate-500">Belum ada ulasan rating dari siswa/orangtua.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
