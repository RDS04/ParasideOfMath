<!-- ══════ TOP NAVBAR ══════ -->
@php
    $currentUser = auth()->guard('siswa')->user() ?? auth()->guard('web')->user();
    $currentName = $currentUser ? $currentUser->name : 'Guest';
    $currentEmail = $currentUser ? $currentUser->email : '';
    $dashboardRoute = auth()->guard('siswa')->check() 
        ? route('siswa.dashboard') 
        : ($currentUser && $currentUser->isAdmin() ? route('admin.dashboard') : route('guru.dashboard'));
    
    $isAdmin = auth()->check() && auth()->user()->isAdmin();
    $notifications = [];
    $unreadCount = 0;
    if ($isAdmin) {
        $notifications = \Illuminate\Support\Facades\DB::table('notifications')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $unreadCount = \Illuminate\Support\Facades\DB::table('notifications')
            ->where('is_read', false)
            ->count();
    }
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
        @if ($isAdmin)
        <li class="nav-item dropdown" id="notifDropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell" style="font-size: 1.15rem;"></i>
                @if ($unreadCount > 0)
                <span id="notifBadge" class="badge badge-warning navbar-badge" style="font-size: 0.65rem; padding: 2px 4px; right: 4px; top: 4px;">{{ $unreadCount }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right rounded-lg shadow-md border-0" style="margin-top: 10px; width: 290px; overflow: hidden; border: 1px solid #ece7f7 !important;">
                <span id="notifHeader" class="dropdown-item dropdown-header font-weight-bold" style="background-color: #faf9fc; color: #2e1065;">{{ $unreadCount }} Notifikasi Baru</span>
                <div class="dropdown-divider" style="border-color: #f0edf9;"></div>
                
                <div style="max-height: 250px; overflow-y: auto;">
                    @forelse ($notifications as $notif)
                    <a href="{{ $notif->link ?? '#' }}" class="dropdown-item py-2.5 d-flex align-items-start {{ !$notif->is_read ? 'bg-purple-50/40' : '' }}" style="border-bottom: 1px solid #f6f4fa; white-space: normal;">
                        <i class="fas fa-user-plus mr-2.5 text-purple mt-1" style="color: #7c3aed; font-size: 0.9rem;"></i>
                        <div style="flex: 1;">
                            <span class="d-block font-weight-bold text-dark text-xs" style="color: #332a4e !important;">{{ $notif->title }}</span>
                            <span class="d-block text-muted text-xxs mt-0.5" style="font-size: 0.72rem; line-height: 1.25;">{{ $notif->message }}</span>
                            <span class="d-block text-muted text-xxs mt-1 text-right" style="font-size: 0.65rem;">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</span>
                        </div>
                    </a>
                    @empty
                    <div class="py-4 text-center text-muted text-xs">
                        <i class="far fa-bell-slash d-block mb-1 text-purple" style="font-size: 1.2rem; color: #a8a2bd;"></i>
                        Tidak ada notifikasi baru
                    </div>
                    @endforelse
                </div>

                <div class="dropdown-divider" style="border-color: #f0edf9;"></div>
                <a href="{{ route('admin.siswa.approve.index') }}" class="dropdown-item dropdown-footer py-2 text-purple font-weight-bold text-center" style="font-size: 0.8rem; color: #7c3aed !important;">Lihat Semua Antrean</a>
            </div>
        </li>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const dropdown = document.getElementById('notifDropdown');
                if (dropdown) {
                    // Gunakan jQuery event selector karena AdminLTE memicu dropdown bootstrap via jQuery
                    $(dropdown).on('show.bs.dropdown', function () {
                        // Mark all as read when dropdown opens
                        fetch('{{ route("admin.notifications.read") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        }).then(r => r.json()).then(data => {
                            const badge = document.getElementById('notifBadge');
                            if (badge) badge.style.display = 'none';
                            const header = document.getElementById('notifHeader');
                            if (header) header.textContent = '0 Notifikasi Baru';
                        }).catch(err => console.error("Gagal membaca notifikasi:", err));
                    });
                }
            });
        </script>
        @endif

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