@extends('layout.app')

@section('title', 'Persetujuan Pendaftaran Guru · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Persetujuan Pendaftaran Guru / Tutor</h1>
                    <p class="text-sm text-muted mb-0">Daftar permohonan registrasi pengajar baru yang berstatus PENDING dan membutuhkan verifikasi Admin.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.guru.daftar.index') }}" class="text-purple-600">Daftar Tutor</a></li>
                        <li class="breadcrumb-item active">Approval Guru</li>
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

            <!-- Banner Info Status Pending -->
            <div class="card shadow-sm border-0 mb-4 rounded-2xl overflow-hidden bg-amber-50 border border-amber-200">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-amber-100 text-amber-700 p-3 mr-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                                <i class="fas fa-hourglass-half fa-xl"></i>
                            </div>
                            <div>
                                <h5 class="font-weight-bold text-amber-950 mb-1">
                                    Antrean Approval Registrasi Guru
                                </h5>
                                <p class="text-xs text-amber-900 mb-0">
                                    Halaman ini khusus menampilkan pendaftar guru baru yang berstatus <span class="font-weight-bold text-amber-950">PENDING</span>. Setelah Anda menyetujui (Approve) atau menolak (Reject), pendaftar akan berpindah ke halaman utama <a href="{{ route('admin.guru.daftar.index') }}" class="font-weight-bold text-purple-700 underline">Daftar Tutor</a>.
                                </p>
                            </div>
                        </div>

                        <div class="text-md-right min-w-max">
                            <span class="badge bg-amber-500 text-white px-3.5 py-2 text-xs font-bold uppercase rounded-pill shadow-xs" style="background-color: #f59e0b !important;">
                                <i class="fas fa-clock mr-1"></i> {{ $pendingGurus->count() }} Permohonan Pending
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card shadow-sm border-light">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <h3 class="card-title font-weight-bold text-purple-950 mb-0">
                        <i class="fas fa-user-clock text-amber-500 mr-2"></i> Pengajuan Guru Menunggu Persetujuan
                    </h3>
                </div>
                <div class="card-body p-0">
                    @if ($pendingGurus->isEmpty())
                        <div class="text-center py-5">
                            <div class="rounded-circle bg-slate-100 text-slate-400 p-4 mx-auto mb-3 d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px;">
                                <i class="fas fa-check-circle fa-3x text-emerald-500"></i>
                            </div>
                            <h5 class="font-weight-bold text-slate-700 mb-1">Tidak Ada Antrean Approval</h5>
                            <p class="text-slate-500 text-sm mb-0">Saat ini tidak ada pendaftaran guru baru yang berstatus PENDING.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-slate-500">
                                    <tr>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Nama Tutor</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Email</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">No. Telepon / WA</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Spesialisasi</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Tanggal Daftar</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Detail</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Status</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Aksi Approval</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingGurus as $guru)
                                        <tr>
                                            <td class="px-4 py-3 font-weight-bold text-purple-950">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar bg-amber-100 text-amber-800 font-bold d-flex justify-content-center align-items-center rounded-circle mr-2" style="width: 32px; height: 32px; font-size: 14px;">
                                                        {{ strtoupper(substr($guru->user->name ?? 'G', 0, 1)) }}
                                                    </div>
                                                    <span>{{ $guru->user->name ?? 'Guru / Tutor' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-slate-600">{{ $guru->user->email ?? '-' }}</td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($guru->no_telp)
                                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $guru->no_telp) }}" target="_blank" class="btn btn-xs btn-outline-success font-weight-bold rounded-lg px-2 py-1 text-xs">
                                                        <i class="fab fa-whatsapp mr-1 text-emerald-600"></i> Hubungi ({{ $guru->no_telp }})
                                                    </a>
                                                @else
                                                    <span class="text-xs text-slate-400 font-italic">Belum diisi</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-slate-700 font-weight-semibold text-xs">
                                                <span class="badge bg-purple-50 text-purple-700 border border-purple-200 px-2 py-1 rounded">
                                                    {{ $guru->spesialisasi ?? 'Matematika' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center text-xs text-slate-500">
                                                {{ $guru->created_at ? $guru->created_at->format('d M Y H:i') : '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <a href="{{ route('admin.guru.detail', $guru->id) }}" class="btn btn-xs btn-info rounded-lg font-weight-bold px-2.5 py-1.5">
                                                    <i class="fas fa-eye mr-1"></i> Detail
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="badge bg-amber-100 text-amber-800 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-amber-300">
                                                    <i class="fas fa-hourglass-half mr-1"></i> Pending Approval
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-1">
                                                    <form action="{{ route('admin.guru.approve', $guru->id) }}" method="POST" class="m-0 mr-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs btn-success font-weight-bold rounded-lg px-2.5 py-1 text-[11px]" title="Setujui (Approve) Guru" onclick="return confirm('Apakah Anda yakin ingin menyetujui pendaftaran akun guru ini?')">
                                                            <i class="fas fa-check mr-1"></i> Setujui
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.guru.reject', $guru->id) }}" method="POST" class="m-0">
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs btn-danger font-weight-bold rounded-lg px-2.5 py-1 text-[11px]" title="Tolak (Reject) Guru" onclick="return confirm('Apakah Anda yakin ingin menolak pendaftaran akun guru ini?')">
                                                            <i class="fas fa-times mr-1"></i> Tolak
                                                        </button>
                                                    </form>
                                                </div>
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
@endsection
