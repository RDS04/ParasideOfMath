@extends('layout.app')

@section('title', 'Daftar Siswa Bimbel · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Daftar Siswa Bimbingan Belajar</h1>
                    <p class="text-sm text-muted mb-0">Kelola dan pantau seluruh data siswa terdaftar beserta status keanggotaan mereka.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600">Dashboard</a></li>
                        <li class="breadcrumb-item active">Daftar Siswa</li>
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
                    <h3 class="card-title font-weight-bold text-purple-950 mb-0">Semua Data Siswa</h3>
                </div>
                <div class="card-body p-0">
                    @if ($students->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-users text-slate-300 fa-3x mb-3"></i>
                            <p class="text-slate-500 text-sm mb-0">Belum ada data siswa terdaftar.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-slate-500">
                                    <tr>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Nama Siswa</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Email</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">No. WhatsApp</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Sekolah/Kelas</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Paket Bimbel</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Status</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Aksi</th>
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
                                            <td class="px-4 py-3 text-center">
                                                @if ($student->whatsapp)
                                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->whatsapp) }}" target="_blank" class="btn btn-xs btn-outline-success font-weight-bold rounded-lg px-2 py-1 text-xs">
                                                        <i class="fab fa-whatsapp mr-1 text-emerald-600"></i> Hubungi
                                                    </a>
                                                @else
                                                    <span class="text-xs text-slate-400 font-italic">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-slate-600 text-xs">
                                                {{ $student->sekolah ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 font-weight-medium">
                                                @if ($paket)
                                                    {{ $paket->nama_paket }} <small class="text-muted block text-xxs">({{ $student->tipe_paket ?? '' }})</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($student->status === 'active')
                                                    <span class="badge bg-emerald-100 text-emerald-800 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-emerald-200">Aktif</span>
                                                @elseif ($student->status === 'under_review')
                                                    <span class="badge bg-amber-100 text-amber-800 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-amber-200 animate-pulse">Menunggu Verifikasi</span>
                                                @else
                                                    <span class="badge bg-slate-100 text-slate-600 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-slate-200">Pilih Paket</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <a href="{{ route('admin.siswa.detail', $student->id) }}" class="btn btn-xs btn-outline-purple font-weight-bold rounded-lg px-2.5 py-1 text-xs">
                                                    <i class="fas fa-eye mr-1"></i> Detail
                                                </a>
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

    <!-- Custom CSS for purple brand elements -->
    <style>
        .btn-outline-success {
            color: #10b981;
            border-color: #a7f3d0;
            background-color: transparent;
            transition: all 0.2s ease;
        }
        .btn-outline-success:hover {
            color: white;
            background-color: #10b981;
            border-color: #10b981;
        }
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
