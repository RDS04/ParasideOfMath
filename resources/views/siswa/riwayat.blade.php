@extends('layout.app')

@section('title', 'Riwayat Pembayaran · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Riwayat Pembayaran</h1>
                    <p class="text-sm text-muted mb-0">Lihat semua riwayat transaksi pembayaran bimbingan belajar Anda.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}" class="text-purple-600">Home</a></li>
                        <li class="breadcrumb-item active">Riwayat Pembayaran</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-xl shadow-sm border-0 mb-4" role="alert">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <!-- Single Column: Full Width Table -->
                <div class="col-12 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h3 class="card-title font-weight-bold text-purple-950 mb-0">Daftar Transaksi</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-purple-950">
                                        <tr>
                                            <th class="border-0 px-4 py-3 text-xs font-bold uppercase tracking-wider">No. Transaksi</th>
                                            <th class="border-0 px-4 py-3 text-xs font-bold uppercase tracking-wider">Tanggal</th>
                                            <th class="border-0 px-4 py-3 text-xs font-bold uppercase tracking-wider">Paket</th>
                                            <th class="border-0 px-4 py-3 text-xs font-bold uppercase tracking-wider text-right">Total Bayar</th>
                                            <th class="border-0 px-4 py-3 text-xs font-bold uppercase tracking-wider text-center">Status</th>
                                            <th class="border-0 px-4 py-3 text-xs font-bold uppercase tracking-wider text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-slate-700">
                                         @if((isset($riwayatList) && $riwayatList->count() > 0) || $siswa->bukti_transfer)
                                             @if(isset($riwayatList) && $riwayatList->count() > 0)
                                                 @foreach($riwayatList as $rItem)
                                                     <tr>
                                                         <td class="px-4 py-3.5 align-middle font-mono text-xs font-semibold text-purple-900">
                                                             POM-TRX-{{ str_pad($rItem->id, 5, '0', STR_PAD_LEFT) }}
                                                         </td>
                                                         <td class="px-4 py-3.5 align-middle text-xs">
                                                             {{ $rItem->created_at ? $rItem->created_at->format('d M Y, H:i') : '-' }}
                                                         </td>
                                                         <td class="px-4 py-3.5 align-middle text-sm font-semibold text-purple-950">
                                                             {{ $rItem->tipe_paket_snapshot ?: ($paket ? $paket->nama_paket : 'Pembayaran Bimbingan') }}
                                                             <span class="d-block text-[11px] text-muted font-normal">{{ strtoupper($rItem->payment_method) }}</span>
                                                         </td>
                                                         <td class="px-4 py-3.5 align-middle text-sm font-bold text-purple-950 text-right">
                                                             Rp {{ number_format($rItem->total_harga, 0, ',', '.') }}
                                                         </td>
                                                         <td class="px-4 py-3.5 align-middle text-center">
                                                             @if($rItem->status === 'approved')
                                                                 <span class="badge badge-success px-2.5 py-1.5 rounded-lg text-xs font-bold shadow-sm" style="background-color: #10b981;">Disetujui</span>
                                                             @elseif($rItem->status === 'rejected')
                                                                 <span class="badge badge-danger px-2.5 py-1.5 rounded-lg text-xs font-bold shadow-sm" style="background-color: #ef4444;">Ditolak</span>
                                                             @else
                                                                 <span class="badge badge-warning px-2.5 py-1.5 rounded-lg text-xs font-bold text-white shadow-sm" style="background-color: #f59e0b;">Menunggu Verifikasi</span>
                                                             @endif
                                                         </td>
                                                         <td class="px-4 py-3.5 align-middle text-center">
                                                             <a href="{{ asset($rItem->bukti_transfer) }}" target="_blank" class="btn btn-sm btn-outline-purple py-1 px-2.5 rounded-lg text-xs font-weight-bold">
                                                                 <i class="fas fa-external-link-alt mr-1"></i> Bukti
                                                             </a>
                                                         </td>
                                                     </tr>
                                                 @endforeach
                                             @endif

                                             @if($siswa->bukti_transfer && (!isset($riwayatList) || $riwayatList->isEmpty()))
                                                 <tr>
                                                     <td class="px-4 py-3.5 align-middle font-mono text-xs font-semibold text-purple-900">
                                                         POM-PAY-{{ str_pad($siswa->id, 4, '0', STR_PAD_LEFT) }}
                                                     </td>
                                                     <td class="px-4 py-3.5 align-middle text-sm">
                                                         {{ $siswa->updated_at ? $siswa->updated_at->format('d M Y H:i') : ($siswa->created_at ? $siswa->created_at->format('d M Y H:i') : '-') }}
                                                     </td>
                                                     <td class="px-4 py-3.5 align-middle text-sm font-medium">
                                                         {{ $paket ? $paket->nama_paket : 'Pendaftaran Bimbel' }}
                                                     </td>
                                                     <td class="px-4 py-3.5 align-middle text-sm font-bold text-purple-950 text-right">
                                                         Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                                     </td>
                                                     <td class="px-4 py-3.5 align-middle text-center">
                                                         @if($siswa->status === 'active')
                                                             <span class="badge badge-success px-2.5 py-1.5 rounded-lg text-xs font-bold shadow-sm" style="background-color: #10b981;">Lunas</span>
                                                         @elseif($siswa->status === 'under_review')
                                                             <span class="badge badge-warning px-2.5 py-1.5 rounded-lg text-xs font-bold text-white shadow-sm" style="background-color: #f59e0b;">Peninjauan</span>
                                                         @elseif($siswa->status === 'rejected')
                                                             <span class="badge badge-danger px-2.5 py-1.5 rounded-lg text-xs font-bold shadow-sm" style="background-color: #ef4444;">Ditolak</span>
                                                         @else
                                                             <span class="badge badge-secondary px-2.5 py-1.5 rounded-lg text-xs font-bold shadow-sm">Pending</span>
                                                         @endif
                                                     </td>
                                                     <td class="px-4 py-3.5 align-middle text-center">
                                                         <div class="d-flex justify-content-center align-items-center" style="gap: 6px;">
                                                             <button type="button" class="btn btn-sm btn-brand py-1.5 px-3 rounded-lg text-xs" data-toggle="modal" data-target="#detailModal">
                                                                 <i class="fas fa-info-circle mr-1"></i> Detail
                                                             </button>
                                                             <button type="button" class="btn btn-sm btn-outline-purple py-1.5 px-3 rounded-lg text-xs" data-toggle="modal" data-target="#buktiModal">
                                                                 <i class="fas fa-image mr-1"></i> Bukti
                                                             </button>
                                                         </div>
                                                     </td>
                                                 </tr>
                                             @endif
                                         @else
                                             <tr>
                                                 <td colspan="6" class="text-center py-5 text-muted text-sm">
                                                     <i class="fas fa-receipt d-block mb-2 text-purple-300" style="font-size: 2rem;"></i>
                                                     Belum ada riwayat pembayaran yang diunggah.
                                                 </td>
                                             </tr>
                                         @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ══════ BOOTSTRAP MODAL DETAIL PENDAFTARAN ══════ -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-xl border-0 overflow-hidden shadow-lg">
                <div class="modal-header bg-purple-950 text-white py-3">
                    <h5 class="modal-title font-weight-bold text-sm" id="detailModalLabel"><i class="fas fa-file-alt mr-2"></i> Detail Pendaftaran</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="text-center mb-4">
                        <div class="w-16 h-16 mx-auto rounded-full bg-purple-100 d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                            <i class="fas fa-user-graduate text-purple-700 text-lg"></i>
                        </div>
                        <h5 class="font-weight-bold text-purple-950 mb-0">{{ $siswa->name }}</h5>
                        <span class="text-xs text-muted">{{ $siswa->email }}</span>
                    </div>
                    
                    <div class="card border-0 rounded-xl shadow-xs p-3 bg-white space-y-3">
                        <div class="d-flex justify-content-between text-sm">
                            <span class="text-muted">Paket Bimbingan</span>
                            <strong class="text-purple-950">{{ $paket ? $paket->nama_paket : '-' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between text-sm">
                            <span class="text-muted">Jumlah Pertemuan</span>
                            <strong class="text-purple-950">{{ $jumlahPertemuan ? $jumlahPertemuan . ' Sesi' : '-' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between text-sm">
                            <span class="text-muted">Hari Pertemuan</span>
                            <strong class="text-purple-950 text-right">{{ !empty($hariPertemuan) ? implode(', ', $hariPertemuan) : '-' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between text-sm">
                            <span class="text-muted">Tanggal Mulai</span>
                            <strong class="text-purple-950">{{ $tanggalMulai ? \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') : '-' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between text-sm">
                            <span class="text-muted">Metode Pembayaran</span>
                            <strong class="text-purple-950">Transfer Bank</strong>
                        </div>
                        <div class="d-flex justify-content-between text-sm">
                            <span class="text-muted">Total Pembayaran</span>
                            <strong class="text-purple-950">Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between text-sm align-items-center">
                            <span class="text-muted">Status Akun Belajar</span>
                            @if($siswa->status === 'active')
                                <span class="badge badge-success px-2 py-1 rounded text-xs font-bold" style="background-color: #d1fae5; color: #065f46;">Aktif / Lunas</span>
                            @elseif($siswa->status === 'under_review')
                                <span class="badge badge-warning px-2 py-1 rounded text-xs font-bold" style="background-color: #fef3c7; color: #92400e;">Peninjauan</span>
                            @elseif($siswa->status === 'rejected')
                                <span class="badge badge-danger px-2 py-1 rounded text-xs font-bold" style="background-color: #fee2e2; color: #991b1b;">Ditolak</span>
                            @else
                                <span class="badge badge-secondary px-2 py-1 rounded text-xs font-bold">Pending</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top-0 py-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-lg py-1.5 px-3 text-xs" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @if($siswa->bukti_transfer)
        <!-- ══════ BOOTSTRAP MODAL BUKTI TRANSFER ══════ -->
        <div class="modal fade" id="buktiModal" tabindex="-1" role="dialog" aria-labelledby="buktiModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content rounded-xl border-0 overflow-hidden shadow-lg">
                    <div class="modal-header bg-purple-950 text-white py-3">
                        <h5 class="modal-title font-weight-bold text-sm" id="buktiModalLabel"><i class="fas fa-file-invoice-dollar mr-2"></i> Bukti Transfer Pembayaran</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-3 text-center bg-light">
                        <div class="overflow-hidden rounded-xl border shadow-sm">
                            <img src="{{ asset($siswa->bukti_transfer) }}" class="img-fluid rounded-xl" style="max-height: 70vh;" alt="Bukti Transfer">
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-top-0 py-2">
                        <a href="{{ asset($siswa->bukti_transfer) }}" download="bukti-pembayaran-{{ $siswa->name }}.png" class="btn btn-purple btn-sm rounded-lg py-1.5 px-3 shadow-sm text-xs font-bold">
                            <i class="fas fa-download mr-1"></i> Unduh File
                        </a>
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-lg py-1.5 px-3 text-xs" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        .btn-outline-purple {
            border: 1px solid #7c3aed;
            color: #7c3aed;
            background: #fff;
            transition: all 0.2s ease;
        }
        .btn-outline-purple:hover {
            background-color: rgba(124, 58, 237, 0.08);
            color: #6d28d9;
        }
        .btn-purple {
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: #fff;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-purple:hover {
            opacity: 0.95;
            color: #fff;
        }
        .btn-brand {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border: none;
            color: #40206b;
            font-weight: 700;
        }
        .btn-brand:hover {
            opacity: 0.9;
            color: #40206b;
        }
        .space-y-3 > * + * {
            margin-top: 0.85rem;
        }
        .rounded-xl { border-radius: 12px !important; }
    </style>
@endsection
