@extends('layout.app')

@section('title', 'Approve Pembayaran Siswa · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Approve Pembayaran Siswa</h1>
                    <p class="text-sm text-muted mb-0">Verifikasi dan konfirmasi bukti pembayaran bulanan/SPP &amp; biaya bimbingan dari siswa.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600">Dashboard</a></li>
                        <li class="breadcrumb-item active">Approve Pembayaran</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert" style="background-color: #d1fae5; border-color: #a7f3d0; color: #065f46;">
                    <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #065f46;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert">
                    <h5><i class="icon fas fa-info-circle mr-2"></i> Informasi</h5>
                    {{ session('info') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($pendingPayments->isEmpty())
                <div class="card shadow-sm border-light">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-check-double text-slate-300 fa-3x mb-3"></i>
                        <h6 class="font-weight-bold text-slate-700 mb-1">Semua Pembayaran Telah Diverifikasi</h6>
                        <p class="text-slate-500 text-sm mb-0">Tidak ada pengajuan pembayaran yang membutuhkan persetujuan saat ini.</p>
                    </div>
                </div>
            @else
                <div class="row">
                    @foreach ($pendingPayments as $pay)
                        @php
                            $siswa = $pay->siswa;
                            $paket = $pay->paket ?? ($siswa ? $siswa->paket : null);
                            $extension = $pay->bukti_transfer ? pathinfo($pay->bukti_transfer, PATHINFO_EXTENSION) : null;
                        @endphp
                        <div class="col-lg-6 mb-4">
                            <div class="card border-0 shadow-sm rounded-2xl overflow-hidden h-100">
                                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-700 d-flex align-items-center justify-center font-weight-bold" style="width:40px;height:40px;">
                                            {{ strtoupper(substr($siswa->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="font-weight-bold text-purple-950 mb-0">{{ $siswa->name ?? '(Siswa Terhapus)' }}</h6>
                                            <span class="text-xs text-muted">{{ $siswa->email ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <span class="badge bg-amber-100 text-amber-800 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-amber-200">
                                        <i class="fas fa-clock mr-1"></i> Menunggu Verifikasi
                                    </span>
                                </div>
                                <div class="card-body p-4 pt-1">
                                    <div class="row align-items-center">
                                        <!-- Bukti Bayar Preview -->
                                        <div class="col-md-5 mb-3 mb-md-0 text-center">
                                            @if ($pay->bukti_transfer)
                                                <div class="w-100 bg-light p-2 rounded-xl border mb-2 d-flex align-items-center justify-content-center" style="background-color: #faf9fd; border-radius: 12px; border: 1px solid #f3f0fc; min-height: 140px;">
                                                    @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']))
                                                        <img src="{{ asset($pay->bukti_transfer) }}" alt="Bukti Transfer" class="img-fluid rounded-lg shadow-xs" style="object-fit: contain; max-height: 140px; max-width: 100%;" />
                                                    @else
                                                        <div class="py-4 text-center">
                                                            <i class="fas fa-file-pdf text-danger fa-2x mb-2"></i>
                                                            <h6 class="font-weight-bold text-slate-700 text-xs mb-0">Berkas PDF</h6>
                                                        </div>
                                                    @endif
                                                </div>
                                                <a href="{{ asset($pay->bukti_transfer) }}" target="_blank" class="btn btn-xs btn-outline-purple font-weight-bold py-1.5 rounded-lg text-xs w-100 d-block">
                                                    <i class="fas fa-external-link-alt mr-1"></i> Lihat Struk Penuh
                                                </a>
                                            @else
                                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-center" style="min-height: 140px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                                    <i class="fas fa-money-bill-wave text-slate-300 fa-2x mb-2"></i>
                                                    <span class="text-xs text-slate-500 font-weight-semibold">Pembayaran Tunai / Tanpa Struk</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Rincian Transaksi -->
                                        <div class="col-md-7">
                                            <div class="p-3 bg-purple-50/50 rounded-xl border border-purple-100 mb-3">
                                                <small class="text-xxs font-bold text-purple-600 uppercase tracking-wider d-block mb-1">Deskripsi Pembayaran</small>
                                                <div class="font-weight-bold text-purple-950 text-sm mb-1">
                                                    {{ $pay->tipe_paket_snapshot ?: ($paket->nama_paket ?? 'Pembayaran Bimbel') }}
                                                </div>
                                                <div class="text-xs text-slate-600">
                                                    <strong>Metode:</strong> {{ strtoupper($pay->payment_method ?: 'Bank Transfer') }}
                                                </div>
                                                <div class="text-xs text-slate-600">
                                                    <strong>Jumlah Sesi:</strong> {{ $pay->jumlah_sesi ?: 1 }} Sesi Bimbingan
                                                </div>
                                                <div class="text-xs text-slate-400 mt-1">
                                                    <i class="fas fa-calendar-alt mr-1"></i> {{ $pay->created_at->format('d M Y H:i') }}
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                                                <span class="text-xs font-weight-bold text-slate-500">TOTAL DIBAYAR:</span>
                                                <span class="h5 font-weight-extrabold text-purple-950 mb-0">Rp {{ number_format($pay->total_harga) }}</span>
                                            </div>

                                            <div class="d-flex gap-2">
                                                <form action="{{ route('admin.riwayat-pembayaran.approve', $pay->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Setujui pembayaran senilai Rp {{ number_format($pay->total_harga) }} ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success font-weight-bold rounded-xl w-100 text-xs py-2 shadow-xs">
                                                        <i class="fas fa-check-circle mr-1"></i> Setujui (ACC)
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.riwayat-pembayaran.reject', $pay->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Tolak pembayaran ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold rounded-xl w-100 text-xs py-2">
                                                        <i class="fas fa-times-circle mr-1"></i> Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </section>
@endsection
