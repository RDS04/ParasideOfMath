@extends('layout.app')

@section('title', 'Persetujuan Pendaftaran Siswa · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Persetujuan Pendaftaran &amp; Pembayaran</h1>
                    <p class="text-sm text-muted mb-0">Verifikasi berkas transfer bukti pembayaran siswa dan aktivasi akun belajar mereka.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600">Dashboard</a></li>
                        <li class="breadcrumb-item active">Persetujuan Siswa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Alert success -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert" style="background-color: #d1fae5; border-color: #a7f3d0; color: #065f46;">
                    <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #065f46;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card shadow-sm border-light">
                <div class="card-header bg-white py-3">
                    <h3 class="card-title font-weight-bold text-purple-950 mb-0">Daftar Pengajuan Registrasi Siswa</h3>
                </div>
                <div class="card-body p-0">
                    @if ($students->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-users text-slate-300 fa-3x mb-3"></i>
                            <p class="text-slate-500 text-sm mb-0">Belum ada data registrasi siswa baru.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-slate-500">
                                    <tr>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Nama Siswa</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Email</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Paket Pilihan</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Pilihan Kelas</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Detail</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Status</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $student)
                                        @php
                                            $paket = \App\Models\PaketBelajar::find($student->paket_id);
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-3 font-weight-bold text-purple-950">{{ $student->name }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ $student->email }}</td>
                                            <td class="px-4 py-3 font-weight-medium">
                                                @if ($paket)
                                                    {{ $paket->nama_paket }} <small class="text-muted block text-xxs">({{ $paket->kategori }})</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-slate-500 text-xs">
                                                {{ $student->tipe_paket ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <!-- Detail Link -->
                                                <a href="{{ route('admin.siswa.detail', $student->id) }}" class="btn btn-xs btn-info rounded-lg font-weight-bold px-2.5 py-1.5">
                                                    <i class="fas fa-eye mr-1"></i> Detail
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($student->status === 'active')
                                                    <span class="badge bg-emerald-100 text-emerald-800 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-emerald-200">Aktif</span>
                                                @elseif ($student->status === 'under_review')
                                                    <span class="badge bg-amber-100 text-amber-800 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-amber-200 animate-pulse">Menunggu Verifikasi</span>
                                                @elseif ($student->status === 'rejected')
                                                    <span class="badge bg-rose-100 text-rose-800 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-rose-200">Ditolak</span>
                                                @else
                                                    <span class="badge bg-slate-100 text-slate-600 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-slate-200">Pilih Paket</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                @if ($student->status === 'under_review')
                                                    <div class="d-flex justify-content-end align-items-center">
                                                        <form action="{{ route('admin.siswa.approve.submit', $student->id) }}" method="POST" class="d-inline mr-2" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pendaftaran dan mengaktifkan akun {{ $student->name }}?')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success rounded-lg font-weight-bold px-3">
                                                                <i class="fas fa-check-circle mr-1"></i> Approve
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.siswa.reject.submit', $student->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Apakah Anda yakin ingin menolak pendaftaran {{ $student->name }}?')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-danger rounded-lg font-weight-bold px-3">
                                                                <i class="fas fa-times-circle mr-1"></i> Tolak
                                                            </button>
                                                        </form>
                                                    </div>
                                                @elseif ($student->status === 'rejected')
                                                    <span class="text-xs text-rose-600 font-weight-bold"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-light border-0 rounded-lg text-slate-400 font-weight-bold px-3" disabled>
                                                        <i class="fas fa-check-circle mr-1"></i> Selesai
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>


                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Custom CSS for purple brand outlines -->
    <style>
        .btn-outline-purple {
            color: #7c3aed;
            border-color: #d8d3e8;
            background-color: transparent;
            transition: all 0.2s ease;
        }
        .btn-outline-purple:hover {
            color: white;
            background-color: #7c3aed;
            border-color: #7c3aed;
        }
        .animate-pulse {
            animation: pulse-glow 2s infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.75; }
        }
    </style>
@endsection
