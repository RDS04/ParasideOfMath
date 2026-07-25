@extends('layout.app')

@section('title', 'Dashboard Admin · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Dashboard Admin</h1>
                    <p class="text-sm text-muted mb-0">Selamat datang kembali di panel kontrol manajemen sistem bimbingan belajar.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="/" class="text-purple-600">Home</a></li>
                        <li class="breadcrumb-item active">Admin Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Welcome Banner -->
            <div class="card mb-4 overflow-hidden border-0 shadow-sm" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 45%, #4c1d95 100%);">
                <div class="card-body p-4 text-white relative">
                    <div class="row align-items-center">
                        <div class="col-md-9 position-relative" style="z-index: 2;">
                            <span class="px-3 py-1 bg-amber-400 text-purple-950 text-xs font-bold uppercase tracking-wider rounded-full shadow-sm">
                                Status: Root Admin
                            </span>
                            <h2 class="font-serif mt-3 text-3xl font-bold">Halo, {{ Auth::user()->name }}!</h2>
                            <p class="text-purple-200 mt-2 mb-0 max-w-xl">
                                Konsol administrasi utama aktif. Gunakan panel ini untuk mengelola harga paket belajar privat, memantau data pengguna (siswa, guru, admin), dan mengelola data operasional bimbingan belajar Paradise of Math.
                            </p>
                        </div>
                        <div class="col-md-3 text-right d-none d-md-block position-relative" style="z-index: 2;">
                            <i class="fas fa-user-shield fa-7x text-white-50 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Metrics -->
            <div class="row">
                <!-- Metric 1: Total Siswa -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white p-3">
                        <div class="inner">
                            <h3 class="font-weight-bold text-purple-950">{{ \App\Models\Siswa::count() }}</h3>
                            <p class="text-muted mb-1">Siswa Terdaftar</p>
                        </div>
                        <div class="icon text-purple"><i class="fas fa-user-graduate"></i></div>
                        <a href="#" class="small-box-footer text-purple mt-2 pt-2 border-top text-left text-xs font-semibold">
                            Kelola Siswa <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <!-- Metric 2: Total Guru -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white p-3">
                        <div class="inner">
                            <h3 class="font-weight-bold text-purple-950">{{ \App\Models\User::where('role', 'guru')->count() }}</h3>
                            <p class="text-muted mb-1">Tutor Pengajar (Guru)</p>
                        </div>
                        <div class="icon text-teal"><i class="fas fa-chalkboard-teacher"></i></div>
                        <a href="#" class="small-box-footer text-teal mt-2 pt-2 border-top text-left text-xs font-semibold">
                            Kelola Pengajar <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <!-- Metric 3: Total Paket Belajar -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white p-3">
                        <div class="inner">
                            <h3 class="font-weight-bold text-purple-950">{{ \App\Models\PaketBelajar::count() }}</h3>
                            <p class="text-muted mb-1">Paket Belajar Aktif</p>
                        </div>
                        <div class="icon text-indigo"><i class="fas fa-tags"></i></div>
                        <a href="{{ route('admin.paket') }}" class="small-box-footer text-indigo mt-2 pt-2 border-top text-left text-xs font-semibold">
                            Kelola Harga Paket <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <!-- Metric 4: Total Admin -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white p-3">
                        <div class="inner">
                            <h3 class="font-weight-bold text-purple-950">{{ \App\Models\User::where('role', 'admin')->count() }}</h3>
                            <p class="text-muted mb-1">Administrator Sistem</p>
                        </div>
                        <div class="icon text-warning"><i class="fas fa-users-cog"></i></div>
                        <a href="{{ route('admin.register') }}" class="small-box-footer text-warning mt-2 pt-2 border-top text-left text-xs font-semibold">
                            Daftar Admin Baru <i class="fas fa-user-plus ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Detailed Section -->
            <div class="row">
                <!-- Left Column: Paket Belajar List -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                            <h3 class="card-title font-weight-bold text-purple-950 mb-0">Paket Bimbingan &amp; Tarif Aktif</h3>
                            <a href="{{ route('admin.paket') }}" class="btn btn-xs btn-brand px-3 py-1.5 rounded-lg shadow-sm">
                                <i class="fas fa-edit mr-1"></i> Edit Paket Harga
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="bg-light text-muted text-xs uppercase">
                                            <th>Nama Paket</th>
                                            <th>Kategori</th>
                                            <th>Rentang Harga</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(\App\Models\PaketBelajar::all() as $paket)
                                        <tr>
                                            <td class="font-weight-semibold"><strong>{{ $paket->nama_paket }}</strong></td>
                                            <td><span class="badge bg-purple-100 text-purple-800">{{ $paket->kategori }}</span></td>
                                            <td class="font-weight-bold">
                                                Rp {{ number_format($paket->harga_min, 0, ',', '.') }} - Rp {{ number_format($paket->harga_max, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @if($paket->is_populer)
                                                    <span class="badge badge-warning text-white"><i class="fas fa-star mr-1"></i> Paling Populer</span>
                                                @else
                                                    <span class="badge badge-secondary">Standar</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Administrative Tools -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h3 class="card-title font-weight-bold text-purple-950 mb-0">Fitur Cepat Admin</h3>
                        </div>
                        <div class="card-body p-4 space-y-3">
                            <p class="text-sm text-muted mb-3">Akses cepat menu pengelolaan administrasi sistem:</p>
                            
                            <a href="{{ route('admin.paket') }}" class="btn btn-block btn-outline-primary py-2.5 rounded-xl font-bold text-sm text-left px-4 d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-tags mr-2 text-purple-600"></i> Kelola Paket Belajar</span>
                                <i class="fas fa-chevron-right text-muted text-xs"></i>
                            </a>

                            <a href="{{ route('admin.register') }}" class="btn btn-block btn-outline-primary py-2.5 rounded-xl font-bold text-sm text-left px-4 d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-user-plus mr-2 text-purple-600"></i> Daftarkan Admin Baru</span>
                                <i class="fas fa-chevron-right text-muted text-xs"></i>
                            </a>

                            <a href="/" class="btn btn-block btn-outline-secondary py-2.5 rounded-xl font-bold text-sm text-left px-4 d-flex justify-content-between align-items-center mt-3">
                                <span><i class="fas fa-home mr-2"></i> Kunjungi Halaman Utama</span>
                                <i class="fas fa-chevron-right text-muted text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection