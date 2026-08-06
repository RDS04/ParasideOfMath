<!-- ══════ SIDEBAR ══════ -->
@php
    $currentUser = auth()->guard('siswa')->user() ?? auth()->guard('web')->user();
    $currentName = $currentUser ? $currentUser->name : 'Guest';
    $isSiswa = auth()->guard('siswa')->check();
    $dashboardRoute = $isSiswa 
        ? route('siswa.dashboard') 
        : ($currentUser && $currentUser->isAdmin() ? route('admin.dashboard') : route('guru.dashboard'));
@endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4 {{ ($isSiswa || ($currentUser && $currentUser->isGuru())) ? 'hidden md:block' : '' }}">
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
                    <a href="{{ $dashboardRoute }}" class="nav-link {{ Route::is('siswa.dashboard') || Route::is('admin.dashboard') || Route::is('guru.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt text-purple-400"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @if ($isSiswa)
                    <!-- ══════ STUDENT NAVIGATION ══════ -->
                    <li class="nav-header">BELAJAR</li>
                    <li class="nav-item">
                        <a href="{{ route('siswa.jadwal') }}" class="nav-link {{ Route::is('siswa.jadwal') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt text-amber-400"></i>
                            <p>Jadwal Belajar</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-file-alt text-teal-400"></i>
                            <p>Materi & Tugas</p>
                        </a>
                    </li>

                    <li class="nav-header">KEUANGAN</li>
                    
                    <li class="nav-item">
                        <a href="{{ route('siswa.invoice') }}" class="nav-link">
                            <i class="nav-icon fas fa-receipt text-rose-400"></i>
                            <p>Tagihan Belajar</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('siswa.riwayat') }}" class="nav-link {{ Route::is('siswa.riwayat') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history text-emerald-400"></i>
                            <p>Riwayat Pembayaran</p>
                        </a>
                    </li>

                @elseif ($currentUser && $currentUser->isAdmin())
                    <!-- ══════ ADMIN NAVIGATION ══════ -->
                    <li class="nav-header">AKADEMIK</li>

                    <li class="nav-item {{ Route::is('admin.siswa.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ Route::is('admin.siswa.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-graduate text-purple-400"></i>
                            <p>Data Siswa <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.siswa.daftar.index') }}" class="nav-link {{ Route::is('admin.siswa.daftar.index') ? 'active' : '' }}">
                                    <i class="fas fa-users nav-icon text-purple-400"></i>
                                    <p>Daftar Siswa</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.siswa.tambah.index') }}" class="nav-link {{ Route::is('admin.siswa.tambah.index') ? 'active' : '' }}">
                                    <i class="fas fa-user-plus nav-icon text-success"></i>
                                    <p>Tambah Siswa</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.siswa.approve.index') }}" class="nav-link {{ Route::is('admin.siswa.approve.index') ? 'active' : '' }}">
                                    <i class="fas fa-user-check nav-icon text-warning"></i>
                                    <p>Approve Siswa</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item {{ Route::is('admin.guru.daftar.index') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ Route::is('admin.guru.daftar.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chalkboard-teacher text-amber-400"></i>
                            <p>Tutor <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.guru.daftar.index') }}" class="nav-link {{ Route::is('admin.guru.daftar.index') ? 'active' : '' }}">
                                    <i class="fas fa-id-card nav-icon text-amber-400"></i>
                                    <p>Daftar Tutor</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-plus-circle nav-icon text-success"></i>
                                    <p>Tambah Tutor</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt text-teal-400"></i>
                            <p>Jadwal Les <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-calendar-week nav-icon text-teal-400"></i>
                                    <p>Kalender Jadwal</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-calendar-plus nav-icon text-success"></i>
                                    <p>Tambah Jadwal</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book-open text-info"></i>
                            <p>Materi &amp; Soal <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-folder-open nav-icon text-warning"></i>
                                    <p>Bank Soal</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-tags nav-icon text-info"></i>
                                    <p>Kategori Materi</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-header">KEUANGAN</li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-wallet text-emerald-400"></i>
                            <p>Pembayaran <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.riwayat-pembayaran') }}" class="nav-link {{ Route::is('admin.riwayat-pembayaran') ? 'active' : '' }}">
                                    <i class="fas fa-history nav-icon text-emerald-400"></i>
                                    <p>Riwayat Pembayaran</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-file-invoice nav-icon text-rose-400"></i>
                                    <p>Invoice</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-line text-rose-400"></i>
                            <p>Laporan <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.laporan-pendapatan') }}" class="nav-link {{ Route::is('admin.laporan-pendapatan') ? 'active' : '' }}">
                                    <i class="fas fa-chart-bar nav-icon text-success"></i>
                                    <p>Laporan Pendapatan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-clipboard-list nav-icon text-primary"></i>
                                    <p>Laporan Kehadiran</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-header">PENGELOLAAN HARGA</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.paket') }}" class="nav-link {{ Route::is('admin.paket') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box-open text-amber-400"></i>
                            <p>Kelola Paket Belajar</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.rekening') }}" class="nav-link {{ Route::is('admin.rekening') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-credit-card text-teal-400"></i>
                            <p>Kelola Rekening</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.mapel') }}" class="nav-link {{ Route::is('admin.mapel') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book text-info"></i>
                            <p>Kelola Mata Pelajaran</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.chat') }}" class="nav-link {{ Route::is('admin.chat') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-comments text-rose-400"></i>
                            <p>Chat Realtime</p>
                        </a>
                    </li>

                    <li class="nav-header">LAINNYA</li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users-cog text-slate-400"></i>
                            <p>Pengguna &amp; Peran <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-users nav-icon text-slate-400"></i>
                                    <p>Daftar Pengguna</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-user-shield nav-icon text-rose-400"></i>
                                    <p>Peran &amp; Izin</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @elseif ($currentUser && $currentUser->isGuru())
                    <!-- ══════ GURU (TUTOR) NAVIGATION ══════ -->
                    <li class="nav-header">MENGAJAR</li>
                    <li class="nav-item">
                        <a href="{{ route('guru.jadwal') }}" class="nav-link {{ Route::is('guru.jadwal') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-check text-purple-400"></i>
                            <p>Jadwal Mengajar</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chalkboard-teacher text-amber-400"></i>
                            <p>Kelas Bimbingan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('guru.siswa') }}" class="nav-link {{ Route::is('guru.siswa') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users text-teal-400"></i>
                            <p>Daftar Siswa Anda</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book-reader text-rose-400"></i>
                            <p>Materi &amp; Modul</p>
                        </a>
                    </li>
                    <li class="nav-header">LAINNYA</li>
                    <li class="nav-item">
                        <a href="{{ route('guru.biodata') }}" class="nav-link {{ Route::is('guru.biodata') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-id-card text-info"></i>
                            <p>Biodata Guru</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-circle text-teal-400"></i>
                            <p>Profil Pengajar</p>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cog text-slate-300"></i>
                        <p>Pengaturan</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<!-- ══════ STYLE UNTUK SUB-MENU MENJOROK KE DALAM ══════ -->
<style>
    .nav-sidebar .nav-treeview {
        padding-left: 15px !important;
    }
</style>