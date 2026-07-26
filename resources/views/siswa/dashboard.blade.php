@extends('layout.app')

@section('title', 'Dashboard Siswa · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Dashboard Belajar</h1>
                    <p class="text-sm text-muted mb-0">Selamat datang kembali di portal ruang belajar Anda.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="#" class="text-purple-600">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Flash Message Alerts -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-xl shadow-sm border-0 mb-4" role="alert" style="background-color: #d1fae5; border-color: #a7f3d0; color: #065f46;">
                    <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #065f46;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Welcome Banner -->
            <div class="card mb-4 overflow-hidden border-0 shadow-sm" style="background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%);">
                <div class="card-body p-4 text-white relative">
                    <div class="row align-items-center">
                        <div class="col-md-9 position-relative" style="z-index: 2;">
                            <span class="px-3 py-1 bg-amber-400 text-purple-950 text-xs font-bold uppercase tracking-wider rounded-full shadow-sm">
                                Status: Siswa Aktif
                            </span>
                            <h2 class="font-serif mt-3 text-3xl font-bold">Halo, {{ Auth::guard('siswa')->user()->name }}!</h2>
                            <p class="text-purple-200 mt-2 mb-0 max-w-xl">
                                Tetap semangat! Setiap tantangan matematika adalah langkah menuju prestasi terbaikmu. Cek jadwal les terdekat dan materi ajar Anda di bawah ini.
                            </p>
                        </div>
                        <div class="col-md-3 text-right d-none d-md-block position-relative" style="z-index: 2;">
                            <i class="fas fa-user-graduate fa-7x text-white-50 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Metrics -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white p-3">
                        <div class="inner">
                            <h3 class="font-weight-bold text-purple-950">3</h3>
                            <p class="text-muted mb-1">Kelas Terdaftar</p>
                        </div>
                        <div class="icon text-purple"><i class="fas fa-book-open"></i></div>
                        <a href="#" class="small-box-footer text-purple mt-2 pt-2 border-top text-left text-xs font-semibold">
                            Lihat Kelas Saya <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white p-3">
                        <div class="inner">
                            <h3 class="font-weight-bold text-purple-950">2</h3>
                            <p class="text-muted mb-1">Tutor Pendamping</p>
                        </div>
                        <div class="icon text-teal"><i class="fas fa-chalkboard-teacher"></i></div>
                        <a href="#" class="small-box-footer text-teal mt-2 pt-2 border-top text-left text-xs font-semibold">
                            Hubungi Tutor <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white p-3">
                        <div class="inner">
                            <h3 class="font-weight-bold text-purple-950">4</h3>
                            <p class="text-muted mb-1">Sesi Les Minggu Ini</p>
                        </div>
                        <div class="icon text-indigo"><i class="fas fa-calendar-check"></i></div>
                        <a href="#" class="small-box-footer text-indigo mt-2 pt-2 border-top text-left text-xs font-semibold">
                            Lihat Jadwal Les <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white p-3">
                        <div class="inner">
                            <h3 class="font-weight-bold text-purple-950">12</h3>
                            <p class="text-muted mb-1">Kuis Diselesaikan</p>
                        </div>
                        <div class="icon text-warning"><i class="fas fa-wallet"></i></div>
                        <a href="#" class="small-box-footer text-warning mt-2 pt-2 border-top text-left text-xs font-semibold">
                            Lihat Nilai Kuis <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Detailed Section -->
            <div class="row">
                <!-- Left Column: Student Schedule -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h3 class="card-title font-weight-bold text-purple-950 mb-0">Jadwal Belajar Saya Hari Ini</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr class="bg-light text-muted text-xs uppercase">
                                        <th>Waktu</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Tutor Pengajar</th>
                                        <th>Tipe Sesi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="font-weight-semibold">09:00 - 10:30</td>
                                        <td><strong>Aljabar Linear</strong></td>
                                        <td>Ibu Rina Astuti</td>
                                        <td><span class="badge badge-info">Tatap Muka</span></td>
                                        <td><span class="badge badge-success">Selesai</span></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-semibold">13:00 - 14:30</td>
                                        <td><strong>Geometri Bidang</strong></td>
                                        <td>Pak Yusuf Mansur</td>
                                        <td><span class="badge badge-warning text-white">Online</span></td>
                                        <td><span class="badge badge-secondary">Menunggu</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Student Activities / Notes -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h3 class="card-title font-weight-bold text-purple-950 mb-0">Aktivitas Belajar Saya</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <i class="fas fa-check-circle text-success mr-2 mt-1"></i>
                                <div>
                                    <p class="mb-0 text-sm">Menyelesaikan kuis <strong>Persamaan Kuadrat</strong></p>
                                    <small class="text-muted">10 menit lalu</small>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <i class="fas fa-file-upload text-purple mr-2 mt-1"></i>
                                <div>
                                    <p class="mb-0 text-sm">Mengunggah tugas <strong>Geometri Segitiga</strong></p>
                                    <small class="text-muted">2 jam lalu</small>
                                </div>
                            </div>
                            <div class="d-flex">
                                <i class="fas fa-comments text-info mr-2 mt-1"></i>
                                <div>
                                    <p class="mb-0 text-sm">Diskusi materi baru dengan <strong>Ibu Rina</strong></p>
                                    <small class="text-muted">Kemarin</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection