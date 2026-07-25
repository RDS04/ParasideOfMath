<!-- ══════ SIDEBAR ══════ -->
@php
    $currentUser = auth()->guard('siswa')->user() ?? auth()->guard('web')->user();
    $currentName = $currentUser ? $currentUser->name : 'Guest';
    $isSiswa = auth()->guard('siswa')->check();
    $dashboardRoute = $isSiswa 
        ? route('siswa.dashboard') 
        : ($currentUser && $currentUser->isAdmin() ? route('admin.dashboard') : route('guru.dashboard'));
@endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <img src="https://placehold.co/32x32/fbbf24/4c1d95?text=PoM" alt="Paradise of Math"
            class="brand-image img-circle elevation-3" style="opacity:.9">
        <span class="brand-text">Paradise <span style="color:#fbbf24">of Math</span></span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($currentName) }}&background=fbbf24&color=40206b&bold=true"
                    class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ $currentName }}</a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ $dashboardRoute }}" class="nav-link active">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @if ($isSiswa)
                    <!-- ══════ STUDENT NAVIGATION ══════ -->
                    <li class="nav-header">BELAJAR</li>
                    
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book-reader"></i>
                            <p>Kelas Saya</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Jadwal Belajar</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-award"></i>
                            <p>Materi & Tugas</p>
                        </a>
                    </li>

                    <li class="nav-header">KEUANGAN</li>
                    
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-file-invoice-dollar"></i>
                            <p>Tagihan Belajar</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Riwayat Pembayaran</p>
                        </a>
                    </li>

                @else
                    <!-- ══════ ADMIN / TUTOR NAVIGATION ══════ -->
                    <li class="nav-header">AKADEMIK</li>

                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="nav-icon fas fa-user-graduate"></i>
                            <p>Siswa <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                    <p>Daftar Siswa</p>
                                </a></li>
                            <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                    <p>Tambah Siswa</p>
                                </a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>Tutor <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                    <p>Daftar Tutor</p>
                                </a></li>
                            <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                    <p>Tambah Tutor</p>
                                </a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Jadwal Les <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                    <p>Kalender Jadwal</p>
                                </a></li>
                            <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                    <p>Tambah Jadwal</p>
                                </a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="nav-icon fas fa-book-open"></i>
                            <p>Materi &amp; Soal <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                    <p>Bank Soal</p>
                                </a></li>
                            <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                    <p>Kategori Materi</p>
                                </a></li>
                        </ul>
                    </li>

                    <li class="nav-header">KEUANGAN</li>

                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="nav-icon fas fa-wallet"></i>
                            <p>Pembayaran <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                    <p>Riwayat Pembayaran</p>
                                </a></li>
                            <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                    <p>Invoice</p>
                                </a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="nav-icon fas fa-chart-line"></i>
                            <p>Laporan <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                    <p>Laporan Pendapatan</p>
                                </a></li>
                            <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                    <p>Laporan Kehadiran</p>
                                </a></li>
                        </ul>
                    </li>

                    <li class="nav-header">LAINNYA</li>

                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="nav-icon fas fa-users-cog"></i>
                            <p>Pengguna &amp; Peran <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                    <p>Daftar Pengguna</p>
                                </a></li>
                            <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                    <p>Peran &amp; Izin</p>
                                </a></li>
                        </ul>
                    </li>
                @endif

                <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon fas fa-cog"></i>
                        <p>Pengaturan</p>
                    </a></li>
            </ul>
        </nav>
    </div>
</aside>