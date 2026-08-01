<!-- ══════ TOP NAVBAR ══════ -->
@php
    $currentUser = auth()->guard('siswa')->user() ?? auth()->guard('web')->user();
    $currentName = $currentUser ? $currentUser->name : 'Guest';
    $currentEmail = $currentUser ? $currentUser->email : '';
    $dashboardRoute = auth()->guard('siswa')->check() 
        ? route('siswa.dashboard') 
        : ($currentUser && $currentUser->isAdmin() ? route('admin.dashboard') : route('guru.dashboard'));
@endphp
<nav class="main-header navbar navbar-expand navbar-light">

    <ul class="navbar-nav">
        <li class="nav-item {{ auth()->guard('siswa')->check() ? 'd-none d-md-inline-block' : '' }}">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ $dashboardRoute }}" class="nav-link">Dashboard</a>
        </li>
    </ul>

    <!-- search -->
    <form class="form-inline ml-3 d-none d-md-flex">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Cari siswa, tutor, transaksi…" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <ul class="navbar-nav ml-auto">

        <!-- notifications -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">4</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">4 Notifikasi Baru</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user-plus mr-2 text-purple"></i> Siswa baru mendaftar
                    <span class="float-right text-muted text-sm">5 menit</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-calendar-check mr-2 text-purple"></i> Jadwal les diperbarui
                    <span class="float-right text-muted text-sm">1 jam</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">Lihat semua notifikasi</a>
            </div>
        </li>

        <!-- user menu -->
        <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown" style="display: flex !important; align-items: center !important;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($currentName) }}&background=4c1d95&color=fff&bold=true"
                     class="user-image img-circle elevation-2" alt="User Avatar" style="width: 32px; height: 32px; margin-right: 8px; float: none !important; display: inline-block !important; object-fit: cover;">
                <span class="d-none d-md-inline font-weight-bold" style="color: #4b4560;">{{ $currentName }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right rounded-lg shadow-md border-0 py-2" style="margin-top: 10px; width: 220px; overflow: hidden; border: 1px solid #ece7f7 !important;">
                <!-- Header / User Info -->
                <div class="px-3 py-2 border-bottom" style="background-color: #faf9fc;">
                    <span class="d-block font-weight-bold text-purple-950" style="font-size: 0.9rem; color: #2e1065;">{{ $currentName }}</span>
                    <span class="d-block text-muted text-xs truncate" style="max-width: 190px; font-size: 0.75rem;">{{ $currentEmail }}</span>
                </div>
                
                <!-- Links -->
                <a href="#" class="dropdown-item py-2 text-dark font-weight-medium d-flex align-items-center" style="font-size: 0.85rem; color: #4b4560 !important;">
                    <i class="fas fa-user-circle mr-2 text-purple" style="color: #7c3aed; width: 20px;"></i> Profil Saya
                </a>
                <div class="dropdown-divider" style="border-color: #f0edf9;"></div>
                
                <a href="#" class="dropdown-item py-2 text-dark font-weight-medium d-flex align-items-center" style="font-size: 0.85rem; color: #4b4560 !important;">
                    <i class="fas fa-user-graduate mr-2 text-purple" style="color: #7c3aed; width: 20px;"></i> Siswa
                </a>
                <a href="#" class="dropdown-item py-2 text-dark font-weight-medium d-flex align-items-center" style="font-size: 0.85rem; color: #4b4560 !important;">
                    <i class="fas fa-chalkboard-teacher mr-2 text-purple" style="color: #7c3aed; width: 20px;"></i> Tutor
                </a>
                <a href="#" class="dropdown-item py-2 text-dark font-weight-medium d-flex align-items-center" style="font-size: 0.85rem; color: #4b4560 !important;">
                    <i class="fas fa-book-open mr-2 text-purple" style="color: #7c3aed; width: 20px;"></i> Kelas
                </a>
                
                <div class="dropdown-divider" style="border-color: #f0edf9;"></div>
                
                <!-- Logout -->
                <form method="POST" action="{{ route('logout') ?? '#' }}" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item py-2 text-danger font-weight-bold d-flex align-items-center" style="font-size: 0.85rem; border: none; background: transparent; width: 100%; text-align: left;">
                        <i class="fas fa-sign-out-alt mr-2 text-danger" style="width: 20px;"></i> Keluar
                    </button>
                </form>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>