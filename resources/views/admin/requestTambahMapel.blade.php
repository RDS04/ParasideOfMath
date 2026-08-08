@extends('layout.app')

@section('title', 'Approve Request Tambah Mapel · Paradise of Math')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Approve Request Tambah Mapel</h1>
                    <p class="text-sm text-muted mb-0">Lihat siswa aktif yang mengajukan permintaan tambahan mata pelajaran.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600">Dashboard</a></li>
                        <li class="breadcrumb-item active">Approve Request Mapel</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

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

            <div class="card shadow-sm border-light">
                <div class="card-header bg-white py-3">
                    <h3 class="card-title font-weight-bold text-purple-950 mb-0">Daftar Request Tambah Mapel</h3>
                </div>
                <div class="card-body p-0">
                    @if ($students->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-book-medical text-slate-300 fa-3x mb-3"></i>
                            <p class="text-slate-500 text-sm mb-0">Tidak ada request tambahan mapel dari siswa aktif.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-slate-500">
                                    <tr>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Nama</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Email</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Mapel Request</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Sesi</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $student)
                                        @php
                                            $bio = $student->biodata ?? [];
                                            $pendingMapels = $bio['pending_mapel_jadwal'] ?? [];
                                            $pendingSesi = $bio['pending_sesi_per_mapel'] ?? [];
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-3 font-weight-bold text-purple-950">{{ $student->name }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ $student->email }}</td>
                                            <td class="px-4 py-3 text-slate-700 text-xs">
                                                {{ implode(', ', $pendingMapels) }}
                                            </td>
                                            <td class="px-4 py-3 text-slate-700 text-xs">
                                                {{ implode(', ', array_map(function ($sesi, $idx) { return $sesi . ' sesi'; }, $pendingSesi, array_keys($pendingSesi))) }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <form action="{{ route('admin.siswa.requests.approve', $student->id) }}" method="POST" class="m-0">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success rounded-lg font-weight-bold px-3">
                                                            <i class="fas fa-check mr-1"></i> Approve
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.siswa.requests.reject', $student->id) }}" method="POST" class="m-0">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger rounded-lg font-weight-bold px-3">
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
