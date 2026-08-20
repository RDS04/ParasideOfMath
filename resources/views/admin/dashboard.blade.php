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

            <!-- Admin Metrics Cards (Matching Design Image) -->
            <div class="row mb-4">
                <!-- Metric 1: Total Siswa -->
                <div class="col-lg-3 col-md-6 col-12 mb-3 mb-lg-0">
                    <div class="card h-100 border-0 rounded-2xl shadow-sm overflow-hidden bg-white hover:shadow-md transition-all duration-300">
                        <div class="card-body p-4 d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-1">
                                    {{ \App\Models\Siswa::count() }}
                                </h2>
                                <p class="text-slate-500 text-xs sm:text-sm font-medium mb-0">Siswa Terdaftar</p>
                            </div>
                            <div class="text-purple-600 text-4xl opacity-90 pl-2">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.siswa.daftar.index') }}" class="bg-slate-100 hover:bg-slate-200 px-4 py-2 text-xs font-bold text-slate-700 d-flex align-items-center justify-content-between border-top text-decoration-none transition-colors">
                            <span>Kelola Siswa</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Metric 2: Total Guru -->
                <div class="col-lg-3 col-md-6 col-12 mb-3 mb-lg-0">
                    <div class="card h-100 border-0 rounded-2xl shadow-sm overflow-hidden bg-white hover:shadow-md transition-all duration-300">
                        <div class="card-body p-4 d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-1">
                                    {{ \App\Models\User::where('role', 'guru')->count() }}
                                </h2>
                                <p class="text-slate-500 text-xs sm:text-sm font-medium mb-0">Tutor Pengajar (Guru)</p>
                            </div>
                            <div class="text-emerald-500 text-4xl opacity-90 pl-2">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.guru.daftar.index') }}" class="bg-slate-100 hover:bg-slate-200 px-4 py-2 text-xs font-bold text-slate-700 d-flex align-items-center justify-content-between border-top text-decoration-none transition-colors">
                            <span>Kelola Pengajar</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Metric 3: Total Paket Belajar -->
                <div class="col-lg-3 col-md-6 col-12 mb-3 mb-lg-0">
                    <div class="card h-100 border-0 rounded-2xl shadow-sm overflow-hidden bg-white hover:shadow-md transition-all duration-300">
                        <div class="card-body p-4 d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-1">
                                    {{ \App\Models\PaketBelajar::count() }}
                                </h2>
                                <p class="text-slate-500 text-xs sm:text-sm font-medium mb-0">Paket Belajar Aktif</p>
                            </div>
                            <div class="text-purple-600 text-4xl opacity-90 pl-2">
                                <i class="fas fa-tag"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.paket') }}" class="bg-slate-100 hover:bg-slate-200 px-4 py-2 text-xs font-bold text-slate-700 d-flex align-items-center justify-content-between border-top text-decoration-none transition-colors">
                            <span>Kelola Harga Paket</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Metric 4: Total Admin -->
                <div class="col-lg-3 col-md-6 col-12 mb-3 mb-lg-0">
                    <div class="card h-100 border-0 rounded-2xl shadow-sm overflow-hidden bg-white hover:shadow-md transition-all duration-300">
                        <div class="card-body p-4 d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-1">
                                    {{ \App\Models\User::where('role', 'admin')->count() }}
                                </h2>
                                <p class="text-slate-500 text-xs sm:text-sm font-medium mb-0">Administrator Sistem</p>
                            </div>
                            <div class="text-amber-500 text-4xl opacity-90 pl-2">
                                <i class="fas fa-users-cog"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.register') }}" class="bg-slate-100 hover:bg-slate-200 px-4 py-2 text-xs font-bold text-slate-700 d-flex align-items-center justify-content-between border-top text-decoration-none transition-colors">
                            <span>Daftar Admin Baru</span>
                            <i class="fas fa-user-plus text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Revenue Report -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <h5 class="font-weight-bold text-purple-950 mb-1">Laporan Pendapatan</h5>
                                <p class="text-sm text-muted mb-0">Pendapatan siswa aktif berdasarkan filter bulanan atau tahunan.</p>
                            </div>
                            <form action="{{ route('admin.laporan-pendapatan') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="filterSelect" class="mb-0 small fw-semibold">Filter</label>
                                    <select name="filter" id="filterSelect" class="form-select form-select-sm" onchange="this.form.submit()" style="width:auto;">
                                        <option value="monthly" {{ ($filter ?? 'monthly') === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                        <option value="yearly" {{ ($filter ?? 'monthly') === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                                    </select>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <label for="yearSelect" class="mb-0 small fw-semibold">Tahun</label>
                                    <select name="year" id="yearSelect" class="form-select form-select-sm" onchange="this.form.submit()" style="width:auto;">
                                        @foreach(($availableYears ?? collect([now()->year])) as $availableYear)
                                            <option value="{{ $availableYear }}" {{ ($year ?? now()->year) === $availableYear ? 'selected' : '' }}>{{ $availableYear }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-sm btn-brand px-3 py-2">
                                    <i class="fas fa-check me-1"></i>Terapkan
                                </button>
                            </form>
                        </div>
                        <div class="card-body p-3">
                            <div class="chart-container" style="position:relative; height:320px; width:100%;">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const labels = @json($chartLabels ?? []);
            const data = @json($chartData ?? []);
            const ctx = document.getElementById('revenueChart');

            if (ctx && labels.length > 0) {
                const chartCtx = ctx.getContext('2d');
                
                // Create a smooth gradient background for the line chart
                const gradient = chartCtx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(124, 58, 237, 0.35)');
                gradient.addColorStop(1, 'rgba(124, 58, 237, 0.01)');

                new Chart(chartCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: data,
                            borderColor: '#7c3aed',
                            borderWidth: 3,
                            backgroundColor: gradient,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#7c3aed',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointHoverBackgroundColor: '#6d28d9',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#2d1b4e',
                                titleColor: '#fff',
                                bodyColor: '#e2d9f3',
                                cornerRadius: 8,
                                padding: 10,
                                callbacks: {
                                    label: function(context) {
                                        return 'Pendapatan: Rp ' + Number(context.parsed.y).toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: { color: '#5f4b8b', font: { size: 11, weight: '500' } },
                                grid: { display: false }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: '#5f4b8b',
                                    font: { size: 11 },
                                    callback: function(value) {
                                        return value >= 1000 ? 'Rp ' + (value / 1000).toFixed(0) + 'rb' : 'Rp ' + value;
                                    }
                                },
                                grid: { color: 'rgba(108, 85, 210, 0.12)', drawBorder: false }
                            }
                        },
                        layout: { padding: { top: 10, bottom: 5 } }
                    }
                });
            }
        });
    </script>
@endsection