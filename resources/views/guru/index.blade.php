@extends('layout.app')

@section('title', 'Dashboard Guru · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-teal-950">Dashboard Guru</h1>
                    <p class="text-sm text-muted mb-0">Selamat datang di portal manajemen pengajar Paradise of Math.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="/" class="text-teal-600">Home</a></li>
                        <li class="breadcrumb-item active">Guru Dashboard</li>
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

            @if(isset($isBiodataComplete) && !$isBiodataComplete)
                <!-- ══════ INCOMPLETE BIODATA ALERT BANNER ══════ -->
                <div class="card mb-4 border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #fffbe6 0%, #fef3c7 100%); border-left: 5px solid #f59e0b !important;">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-9">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-amber-500 text-white px-2.5 py-1 text-xs font-bold uppercase rounded-md mr-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> Perhatian
                                    </span>
                                    <h5 class="mb-0 font-weight-bold text-amber-950">Biodata Pengajar Anda Belum Lengkap!</h5>
                                </div>
                                <p class="text-amber-900 text-sm mb-0">
                                    Silakan lengkapi informasi biodata Anda (seperti lulusan/pendidikan terakhir, nomor WhatsApp, spesialisasi mengajar, dan alamat domisili). Data kualifikasi yang lengkap membantu siswa mengenal latar belakang pengajar mereka secara detail.
                                </p>
                            </div>
                            <div class="col-md-3 text-right mt-3 mt-md-0">
                                <a href="{{ route('guru.biodata') }}" class="btn btn-warning font-weight-bold text-amber-950 px-4 py-2.5 rounded-xl shadow-sm border-0">
                                    <i class="fas fa-edit mr-1.5"></i> Lengkapi Biodata
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Welcome Banner -->
            <div class="card mb-4 overflow-hidden border-0 shadow-sm" style="background: linear-gradient(135deg, #0f766e 0%, #115e59 45%, #1e3a8a 100%);">
                <div class="card-body p-4 text-white relative">
                    <div class="row align-items-center">
                        <div class="col-md-9 position-relative" style="z-index: 2;">
                            <span class="px-3 py-1 bg-emerald-400 text-teal-950 text-xs font-bold uppercase tracking-wider rounded-full shadow-sm">
                                Status: Pengajar Aktif
                            </span>
                            <h2 class="font-serif mt-3 text-3xl font-bold">Halo, {{ Auth::user()->name }}!</h2>
                            <p class="text-teal-100 mt-2 mb-0 max-w-xl">
                                Ruang pengajar Anda sudah siap. Di sini Anda dapat memantau jadwal bimbingan belajar, mengunggah modul/materi ajar, dan memantau perkembangan belajar siswa bimbingan Anda.
                            </p>
                        </div>
                        <div class="col-md-3 text-right d-none d-md-block position-relative" style="z-index: 2;">
                            <i class="fas fa-chalkboard-teacher fa-7x text-white-50 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guru Metrics -->
            <div class="row">
                <!-- Metric 1: Total Kelas -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white p-3">
                        <div class="inner">
                            <h3 class="font-weight-bold text-teal-950">12</h3>
                            <p class="text-muted mb-1">Kelas Aktif</p>
                        </div>
                        <div class="icon text-teal"><i class="fas fa-school"></i></div>
                        <span class="text-teal text-xs font-semibold pt-2 border-top d-block">
                            Bimbingan bulan ini
                        </span>
                    </div>
                </div>
                <!-- Metric 2: Jam Mengajar -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white p-3">
                        <div class="inner">
                            <h3 class="font-weight-bold text-teal-950">48 <span class="text-sm font-weight-normal text-muted">Jam</span></h3>
                            <p class="text-muted mb-1">Total Durasi</p>
                        </div>
                        <div class="icon text-primary"><i class="fas fa-clock"></i></div>
                        <span class="text-primary text-xs font-semibold pt-2 border-top d-block">
                            Akumulasi mengajar
                        </span>
                    </div>
                </div>
                <!-- Metric 3: Siswa Bimbingan -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white p-3">
                        <div class="inner">
                            <h3 class="font-weight-bold text-teal-950">{{ isset($assignedStudents) ? $assignedStudents->count() : 0 }} <span class="text-sm font-weight-normal text-muted">Orang</span></h3>
                            <p class="text-muted mb-1">Siswa Bimbingan</p>
                        </div>
                        <div class="icon text-purple"><i class="fas fa-user-graduate"></i></div>
                        <span class="text-purple text-xs font-semibold pt-2 border-top d-block">
                            Siswa aktif saat ini
                        </span>
                    </div>
                </div>
                <!-- Metric 4: Jam Mengajar -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white p-3">
                        <div class="inner">
                            <h3 class="font-weight-bold text-teal-950">Aktif</h3>
                            <p class="text-muted mb-1">Status Pengajar</p>
                        </div>
                        <div class="icon text-warning"><i class="fas fa-check-circle"></i></div>
                        <span class="text-warning text-xs font-semibold pt-2 border-top d-block">
                            Terverifikasi Admin
                        </span>
                    </div>
                </div>
            </div>

            <!-- Detailed Section -->
            <div class="row">
                <!-- Left Column: Assigned Students -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                            <h3 class="card-title font-weight-bold text-teal-950 mb-0">Siswa Bimbingan Ditugaskan Admin</h3>
                            <span class="badge bg-teal-50 text-teal-700 border border-teal-200 px-3 py-1.5 rounded-lg text-xs">
                                <i class="fas fa-user-graduate mr-1"></i> Total: {{ isset($assignedStudents) ? $assignedStudents->count() : 0 }} Siswa
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="bg-light text-muted text-xs uppercase">
                                            <th>Siswa</th>
                                            <th>Kontak &amp; Sekolah</th>
                                            <th>Paket Belajar</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($assignedStudents ?? [] as $s)
                                            <tr>
                                                <td>
                                                    <div class="font-weight-bold text-purple-950">{{ $s->name }}</div>
                                                    <span class="text-xs text-muted">{{ $s->email }}</span>
                                                </td>
                                                <td>
                                                    <div class="font-weight-semibold text-sm">{{ $s->whatsapp ?? '-' }}</div>
                                                    <span class="text-xs text-muted">{{ $s->sekolah ?? 'Sekolah belum diisi' }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-purple-100 text-purple-800 font-weight-semibold">
                                                        {{ $s->paket ? $s->paket->nama_paket : 'Paket Privat' }}
                                                    </span>
                                                    <div class="text-[11px] text-muted mt-1">{{ $s->tipe_paket ?? '-' }}</div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-emerald-100 text-emerald-800 font-weight-bold px-2.5 py-1">
                                                        <i class="fas fa-check-circle mr-1"></i> Ditugaskan oleh Admin
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5 text-muted">
                                                    <i class="fas fa-user-clock fa-2x mb-2 d-block opacity-50"></i>
                                                    Belum ada siswa yang ditugaskan oleh Admin untuk Anda saat ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Profile & Fast Actions -->
                <div class="col-md-4">
                    <!-- Profile Card -->
                    <div class="card mb-4">
                        <div class="card-header bg-white py-3">
                            <h3 class="card-title font-weight-bold text-teal-950 mb-0">Informasi Pengajar</h3>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0f766e&color=fff&size=100&bold=true" class="rounded-circle img-thumbnail shadow-sm mb-3" alt="Avatar">
                                <h5 class="font-weight-bold text-teal-950 mb-0">{{ Auth::user()->name }}</h5>
                                <span class="text-xs text-muted uppercase tracking-wider font-semibold">Tutor Matematika</span>
                            </div>
                            <hr class="my-3">
                            <div class="space-y-3">
                                <div>
                                    <span class="text-xs text-muted d-block">Spesialisasi</span>
                                    <strong class="text-sm text-teal-950">
                                        {{ Auth::user()->guruProfile->spesialisasi ?? 'Matematika SMA / Wajib' }}
                                    </strong>
                                </div>
                                <div>
                                    <span class="text-xs text-muted d-block">Email Terdaftar</span>
                                    <strong class="text-sm text-teal-950">{{ Auth::user()->email }}</strong>
                                </div>
                                <div>
                                    <span class="text-xs text-muted d-block">No. Telepon / WA</span>
                                    <strong class="text-sm text-teal-950">
                                        {{ Auth::user()->guruProfile->no_telp ?? 'Belum dilengkapi' }}
                                    </strong>
                                </div>
                                <div>
                                    <span class="text-xs text-muted d-block">Alamat</span>
                                    <strong class="text-sm text-teal-950">
                                        {{ Auth::user()->guruProfile->alamat ?? 'Belum dilengkapi' }}
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fast Actions -->
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h3 class="card-title font-weight-bold text-teal-950 mb-0">Pintasan Menu</h3>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-grid gap-2">
                                <a href="#" class="btn btn-outline-teal text-left py-2 px-3 mb-2 rounded-xl text-sm font-medium d-flex align-items-center justify-content-between hover:bg-teal-50 hover:text-teal-800">
                                    <span><i class="fas fa-book-open mr-2 text-teal-500"></i> Kelola Materi Ajar</span>
                                    <i class="fas fa-chevron-right text-xs opacity-50"></i>
                                </a>
                                <a href="#" class="btn btn-outline-teal text-left py-2 px-3 mb-2 rounded-xl text-sm font-medium d-flex align-items-center justify-content-between hover:bg-teal-50 hover:text-teal-800">
                                    <span><i class="fas fa-calendar-alt mr-2 text-primary-500"></i> Atur Jadwal Libur</span>
                                    <i class="fas fa-chevron-right text-xs opacity-50"></i>
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-full py-2.5 rounded-xl text-sm font-bold shadow-sm d-flex align-items-center justify-content-center">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Keluar Aplikasi
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Custom inline styles for bootstrap buttons inside card -->
    <style>
        .btn-outline-teal {
            border: 1px solid #e2e8f0;
            color: #334155;
            background: #fff;
            transition: all 0.2s ease;
        }
        .btn-brand {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border: none;
            color: #40206b;
            font-weight: 700;
        }
        .space-y-3 > * + * {
            margin-top: 0.75rem;
        }
    </style>
@endsection
