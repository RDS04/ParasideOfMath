@extends('layout.app')

@section('title', 'Daftar Guru / Tutor · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Daftar Guru / Tutor Pendamping</h1>
                    <p class="text-sm text-muted mb-0">Kelola dan pantau seluruh data pengajar terdaftar beserta spesialisasi mata pelajaran mereka.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600">Dashboard</a></li>
                        <li class="breadcrumb-item active">Daftar Tutor</li>
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
                    <h3 class="card-title font-weight-bold text-purple-950 mb-0">Semua Data Tutor</h3>
                </div>
                <div class="card-body p-0">
                    @if ($gurus->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-chalkboard-teacher text-slate-300 fa-3x mb-3"></i>
                            <p class="text-slate-500 text-sm mb-0">Belum ada data guru/tutor terdaftar.</p>
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
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Alamat Rumah</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Detail</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gurus as $guru)
                                        <tr>
                                            <td class="px-4 py-3 font-weight-bold text-purple-950">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar bg-purple-100 text-purple-700 font-bold d-flex justify-content-center align-items-center rounded-circle mr-2" style="width: 32px; height: 32px; font-size: 14px;">
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
                                            <td class="px-4 py-3 text-slate-600 text-xs">
                                                {{ $guru->alamat ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <button type="button" class="btn btn-xs btn-info rounded-lg font-weight-bold px-2.5 py-1.5" data-toggle="modal" data-target="#detailGuruModal{{ $guru->id }}">
                                                    <i class="fas fa-eye mr-1"></i> Detail
                                                </button>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if (strtolower($guru->status) === 'aktif')
                                                    <span class="badge bg-emerald-100 text-emerald-800 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-emerald-200">Aktif</span>
                                                @else
                                                    <span class="badge bg-slate-100 text-slate-600 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-slate-200">{{ $guru->status ?? 'Aktif' }}</span>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Modal Detail Guru -->
                                        <div class="modal fade text-left" id="detailGuruModal{{ $guru->id }}" tabindex="-1" role="dialog" aria-labelledby="detailGuruModalLabel{{ $guru->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content border-0 shadow" style="border-radius: 18px; overflow: hidden;">
                                                    <div class="modal-header bg-purple-950 text-white border-0 py-3" style="background-color: #2e1065;">
                                                        <h5 class="modal-title font-weight-bold text-md text-white" id="detailGuruModalLabel{{ $guru->id }}" style="color: #fff;">Profil Lengkap Guru / Tutor</h5>
                                                        <button type="button" class="close text-white border-0 bg-transparent" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; outline: none; color: #fff;">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <div class="text-center mb-4">
                                                            <div class="avatar bg-purple-100 text-purple-700 font-bold d-flex justify-content-center align-items-center rounded-circle mx-auto mb-2.5" style="width: 64px; height: 64px; font-size: 24px;">
                                                                {{ strtoupper(substr($guru->user->name ?? 'G', 0, 1)) }}
                                                            </div>
                                                            <h5 class="font-weight-bold text-purple-950 mb-0.5" style="color: #2e1065;">{{ $guru->user->name ?? 'Guru / Tutor' }}</h5>
                                                            <span class="badge bg-purple-100 text-purple-800 px-2 py-0.5 text-[10px] font-bold uppercase rounded">{{ $guru->spesialisasi ?? 'Matematika' }}</span>
                                                        </div>

                                                        <div class="p-3 bg-purple-50 rounded-2xl text-left border border-purple-100 mb-4 text-xs" style="background-color: #f5f3ff; border: 1px solid #ddd6fe; border-radius: 14px;">
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <span class="text-slate-500">Email:</span>
                                                                <strong class="text-slate-800">{{ $guru->user->email ?? '-' }}</strong>
                                                            </div>
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <span class="text-slate-500">No. HP / WA:</span>
                                                                <strong class="text-slate-800">{{ $guru->no_telp ?? '-' }}</strong>
                                                            </div>
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <span class="text-slate-500">Alamat Rumah:</span>
                                                                <strong class="text-slate-800 text-right" style="max-width: 60%;">{{ $guru->alamat ?? '-' }}</strong>
                                                            </div>
                                                            <div class="d-flex justify-content-between">
                                                                <span class="text-slate-500">Status Akun:</span>
                                                                <span class="badge {{ strtolower($guru->status) === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }} font-bold text-[9px] uppercase px-2 py-0.5 rounded-pill border">
                                                                    {{ $guru->status ?? 'Aktif' }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <!-- Siswa Bimbingan Section -->
                                                        <h6 class="font-weight-bold text-purple-950 text-xs mb-2 uppercase tracking-wide"><i class="fas fa-user-graduate mr-1 text-purple-600"></i> Siswa Bimbingan saat Ini:</h6>
                                                        @php
                                                            $gName = $guru->user->name ?? '';
                                                            $siswaBimbingan = [];
                                                            if ($gName) {
                                                                $siswaBimbingan = \App\Models\Siswa::where('tipe_paket', 'LIKE', '%' . $gName . '%')->get();
                                                            }
                                                        @endphp
                                                        @if(count($siswaBimbingan) > 0)
                                                            <div class="row g-2">
                                                                @foreach($siswaBimbingan as $s)
                                                                    <div class="col-6 mb-2">
                                                                        <div class="p-2 rounded border text-xs" style="background-color: #f8fafc; border-color: #e2e8f0;">
                                                                            <div class="font-weight-bold text-purple-950 text-truncate" title="{{ $s->name }}">{{ $s->name }}</div>
                                                                            <div class="text-[10px] text-muted text-truncate">{{ $s->biodata['kelas'] ?? 'Siswa' }}</div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="p-3 text-center border rounded-2xl bg-white" style="border-radius: 12px; border-style: dashed !important; border-color: #cbd5e1 !important;">
                                                                <span class="text-xxs text-muted font-italic">Belum ada siswa bimbingan yang ditugaskan.</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer border-0 bg-light p-3 d-flex justify-content-end">
                                                        <button type="button" class="btn btn-sm btn-secondary rounded-lg font-weight-bold px-4" data-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
    </style>
@endsection
