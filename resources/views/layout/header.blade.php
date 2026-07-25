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
        <li class="nav-item">
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
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown" style="display: flex !important; align-items: center !important;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($currentName) }}&background=4c1d95&color=fff&bold=true"
                     class="user-image img-circle elevation-2" alt="User Avatar" style="width: 32px; height: 32px; margin-right: 8px; float: none !important; display: inline-block !important; object-fit: cover;">
                <span class="d-none d-md-inline font-weight-bold" style="color: #4b4560;">{{ $currentName }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <li class="user-header" style="background: linear-gradient(135deg, #4c1d95, #2e1065);">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($currentName) }}&background=fbbf24&color=40206b&bold=true"
                         class="img-circle elevation-2" alt="User Avatar">
                    <p>
                        {{ $currentName }}
                        <small>{{ $currentEmail }}</small>
                    </p>
                </li>
                <li class="user-body">
                    <div class="row">
                        <div class ="col-4 text-center"><a href="#">Siswa</a></div>
                        <div class="col-4 text-center"><a href="#">Tutor</a></div>
                        <div class="col-4 text-center"><a href="#">Kelas</a></div>
                    </div>
                </li>
                <li class="user-footer">
                    <a href="" class="btn btn-default btn-flat float-left">Profil</a>
                    <form method="POST" action="{{ route('logout') ?? '#' }}" class="float-right">
                        @csrf
                        <button type="submit" class="btn btn-brand btn-flat">Keluar</button>
                    </form>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>