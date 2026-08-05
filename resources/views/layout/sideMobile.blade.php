@php
  $isSiswa = auth()->guard('siswa')->check();
  $currentUser = auth()->guard('siswa')->user() ?? auth()->guard('web')->user();
  $isGuru = $currentUser && $currentUser->isGuru();

  // Active state flags for Siswa
  $isHomeActive = Request::is('siswa') || Request::is('siswa/dashboard');
  $isExploreActive = Request::is('siswa/explore') || Request::is('siswa/explore/*');
  $isJadwalActive = Request::is('siswa/jadwal') || Request::is('siswa/jadwal/*');
  $isChatActive = Request::is('siswa/chat') || Request::is('siswa/chat/*');
  $isAccountActive = Request::is('siswa/account') || Request::is('siswa/account/*') || Request::is('siswa/profile');

  // Active state flags for Guru
  $isGuruDashboardActive = Request::is('guru') || Request::is('guru/dashboard');
  $isGuruBiodataActive = Request::is('guru/biodata') || Request::is('guru/biodata/*');
  $isGuruJadwalActive = Request::is('guru/jadwal') || Request::is('guru/jadwal/*');
@endphp

@if ($isGuru)
  <!-- ══════════════ GURU BOTTOM NAVIGATION (MOBILE ONLY) ══════════════ -->
  <div class="md:hidden"
    style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 9999; background-color: #ffffff; border-top: 1px solid #ece7f7; padding-bottom: env(safe-area-inset-bottom, 12px); padding-top: 10px; box-shadow: 0 -4px 20px rgba(46, 16, 101, 0.08);">
    <div style="max-width: 480px; margin: 0 auto; width: 100%;">
      <div
        style="display: flex !important; flex-direction: row !important; justify-content: space-around !important; align-items: center !important; padding-left: 12px; padding-right: 12px;">

        <!-- Home Dashboard Guru -->
        <a href="{{ route('guru.dashboard') }}"
          style="display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; text-decoration: none !important; width: 72px; color: {{ $isGuruDashboardActive ? '#7c3aed' : '#94a3b8' }}; transition: all 0.25s ease;">
          <div
            style="display: flex; align-items: center; justify-content: center; width: 44px; height: 30px; border-radius: 14px; background-color: {{ $isGuruDashboardActive ? 'rgba(124, 58, 237, 0.08)' : 'transparent' }}; transition: all 0.25s ease; margin-bottom: 2px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
              style="width: 20px; height: 20px; transition: transform 0.25s ease; transform: {{ $isGuruDashboardActive ? 'scale(1.08)' : 'scale(1)' }};">
              <path d="M12 2.5 1.5 11h3V21h6v-6h3v6h6V11h3L12 2.5z" />
            </svg>
          </div>
          <span
            style="font-size: 10px; font-weight: {{ $isGuruDashboardActive ? '700' : '500' }}; font-family: 'Inter', sans-serif; transition: font-weight 0.25s;">Dashboard</span>
        </a>

        <!-- Biodata Guru -->
        <a href="{{ route('guru.biodata') }}"
          style="display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; text-decoration: none !important; width: 72px; color: {{ $isGuruBiodataActive ? '#7c3aed' : '#94a3b8' }}; transition: all 0.25s ease;">
          <div
            style="display: flex; align-items: center; justify-content: center; width: 44px; height: 30px; border-radius: 14px; background-color: {{ $isGuruBiodataActive ? 'rgba(124, 58, 237, 0.08)' : 'transparent' }}; transition: all 0.25s ease; margin-bottom: 2px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
              style="width: 20px; height: 20px; transition: transform 0.25s ease; transform: {{ $isGuruBiodataActive ? 'scale(1.08)' : 'scale(1)' }};">
              <path
                d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.78c0-2.33 4.67-3.5 7-3.5s7 1.17 7 3.5V19z" />
            </svg>
          </div>
          <span
            style="font-size: 10px; font-weight: {{ $isGuruBiodataActive ? '700' : '500' }}; font-family: 'Inter', sans-serif; white-space: nowrap; transition: font-weight 0.25s;">Biodata</span>
        </a>

        <!-- Jadwal Bimbingan -->
        <a href="{{ route('guru.dashboard') }}"
          style="display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; text-decoration: none !important; width: 72px; color: {{ $isGuruJadwalActive ? '#7c3aed' : '#94a3b8' }}; transition: all 0.25s ease;">
          <div
            style="display: flex; align-items: center; justify-content: center; width: 44px; height: 30px; border-radius: 14px; background-color: {{ $isGuruJadwalActive ? 'rgba(124, 58, 237, 0.08)' : 'transparent' }}; transition: all 0.25s ease; margin-bottom: 2px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
              style="width: 20px; height: 20px; transition: transform 0.25s ease; transform: {{ $isGuruJadwalActive ? 'scale(1.08)' : 'scale(1)' }};">
              <path
                d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z" />
            </svg>
          </div>
          <span
            style="font-size: 10px; font-weight: {{ $isGuruJadwalActive ? '700' : '500' }}; font-family: 'Inter', sans-serif; transition: font-weight 0.25s;">Jadwal</span>
        </a>

        <!-- Keluar / Logout -->
        <form action="{{ route('logout') }}" method="POST" id="mobileGuruLogoutForm" style="display: inline;">
          @csrf
          <button type="submit"
            style="background: none; border: none; padding: 0; cursor: pointer; display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; width: 72px; color: #ef4444; transition: all 0.25s ease;">
            <div
              style="display: flex; align-items: center; justify-content: center; width: 44px; height: 30px; border-radius: 14px; background-color: rgba(239, 68, 68, 0.08); transition: all 0.25s ease; margin-bottom: 2px;">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                style="width: 20px; height: 20px;">
                <path
                  d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" />
              </svg>
            </div>
            <span style="font-size: 10px; font-weight: 600; font-family: 'Inter', sans-serif;">Keluar</span>
          </button>
        </form>
      </div>
    </div>
  </div>

@elseif ($isSiswa)
  <!-- ══════════════ SISWA BOTTOM NAVIGATION (MOBILE ONLY) ══════════════ -->
  <div class="md:hidden"
    style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 9999; background-color: #ffffff; border-top: 1px solid #ece7f7; padding-bottom: env(safe-area-inset-bottom, 12px); padding-top: 10px; box-shadow: 0 -4px 20px rgba(46, 16, 101, 0.08);">
    <div style="max-width: 480px; margin: 0 auto; width: 100%;">
      <div
        style="display: flex !important; flex-direction: row !important; justify-content: space-around !important; align-items: center !important; padding-left: 12px; padding-right: 12px;">
        <!-- Home -->
        <a href="{{ route('siswa.dashboard') }}"
          style="display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; text-decoration: none !important; width: 68px; color: {{ $isHomeActive ? '#7c3aed' : '#94a3b8' }}; transition: all 0.25s ease;">
          <div
            style="display: flex; align-items: center; justify-content: center; width: 44px; height: 30px; border-radius: 14px; background-color: {{ $isHomeActive ? 'rgba(124, 58, 237, 0.08)' : 'transparent' }}; transition: all 0.25s ease; margin-bottom: 2px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
              style="width: 20px; height: 20px; transition: transform 0.25s ease; transform: {{ $isHomeActive ? 'scale(1.08)' : 'scale(1)' }};">
              <path d="M12 2.5 1.5 11h3V21h6v-6h3v6h6V11h3L12 2.5z" />
            </svg>
          </div>
          <span
            style="font-size: 10px; font-weight: {{ $isHomeActive ? '700' : '500' }}; font-family: 'Inter', sans-serif; transition: font-weight 0.25s;">Home</span>
        </a>

        <!-- Class Room -->
        <a href="{{ route('siswa.jadwal') }}"
          style="display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; text-decoration: none !important; width: 68px; color: {{ $isJadwalActive ? '#7c3aed' : '#94a3b8' }}; transition: all 0.25s ease;">
          <div
            style="display: flex; align-items: center; justify-content: center; width: 44px; height: 30px; border-radius: 14px; background-color: {{ $isJadwalActive ? 'rgba(124, 58, 237, 0.08)' : 'transparent' }}; transition: all 0.25s ease; margin-bottom: 2px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
              style="width: 20px; height: 20px; transition: transform 0.25s ease; transform: {{ $isJadwalActive ? 'scale(1.08)' : 'scale(1)' }};">
              <circle cx="12" cy="6" r="2.3" />
              <circle cx="5" cy="8" r="2" />
              <circle cx="19" cy="8" r="2" />
              <path d="M12 10c-2.2 0-4 1.4-4 3.2V16h8v-2.8c0-1.8-1.8-3.2-4-3.2z" />
              <path d="M5 11.3c-1.8 0-3.3 1.2-3.3 2.7V16h3v-2.3c0-.8.3-1.5.8-2.1A4 4 0 0 0 5 11.3z" />
              <path d="M19 11.3a4 4 0 0 0-.5.3c.5.6.8 1.3.8 2.1V16h3v-2c0-1.5-1.5-2.7-3.3-2.7z" />
            </svg>
          </div>
          <span
            style="font-size: 10px; font-weight: {{ $isJadwalActive ? '700' : '500' }}; font-family: 'Inter', sans-serif; white-space: nowrap; transition: font-weight 0.25s;">Class
            Room</span>
        </a>

        <!-- Explore -->
        <a href="#"
          style="display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; text-decoration: none !important; width: 68px; color: {{ $isExploreActive ? '#7c3aed' : '#94a3b8' }}; transition: all 0.25s ease;">
          <div
            style="display: flex; align-items: center; justify-content: center; width: 44px; height: 30px; border-radius: 14px; background-color: {{ $isExploreActive ? 'rgba(124, 58, 237, 0.08)' : 'transparent' }}; transition: all 0.25s ease; margin-bottom: 2px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
              style="width: 20px; height: 20px; transition: transform 0.25s ease; transform: {{ $isExploreActive ? 'scale(1.08)' : 'scale(1)' }};">
              <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
            </svg>
          </div>
          <span
            style="font-size: 10px; font-weight: {{ $isExploreActive ? '700' : '500' }}; font-family: 'Inter', sans-serif; transition: font-weight 0.25s;">ADD</span>
        </a>

        <!-- Chat -->
        <a href="#"
          style="display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; text-decoration: none !important; width: 68px; color: {{ $isChatActive ? '#7c3aed' : '#94a3b8' }}; transition: all 0.25s ease;">
          <div
            style="display: flex; align-items: center; justify-content: center; width: 44px; height: 30px; border-radius: 14px; background-color: {{ $isChatActive ? 'rgba(124, 58, 237, 0.08)' : 'transparent' }}; transition: all 0.25s ease; margin-bottom: 2px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
              style="width: 20px; height: 20px; transition: transform 0.25s ease; transform: {{ $isChatActive ? 'scale(1.08)' : 'scale(1)' }};">
              <path d="M4 4h13a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H9l-4 3.5V15H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"
                opacity="0.55" />
              <path d="M9 8h11a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2h-1v2.8L16 18h-7a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2z" />
            </svg>
          </div>
          <span
            style="font-size: 10px; font-weight: {{ $isChatActive ? '700' : '500' }}; font-family: 'Inter', sans-serif; transition: font-weight 0.25s;">Chat</span>
        </a>

        <!-- Account -->
        <a href="#"
          style="display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; text-decoration: none !important; width: 68px; color: {{ $isAccountActive ? '#7c3aed' : '#94a3b8' }}; transition: all 0.25s ease;">
          <div
            style="display: flex; align-items: center; justify-content: center; width: 44px; height: 30px; border-radius: 14px; background-color: {{ $isAccountActive ? 'rgba(124, 58, 237, 0.08)' : 'transparent' }}; transition: all 0.25s ease; margin-bottom: 2px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
              style="width: 20px; height: 20px; transition: transform 0.25s ease; transform: {{ $isAccountActive ? 'scale(1.08)' : 'scale(1)' }};">
              <circle cx="12" cy="8" r="4" />
              <path d="M4 20c0-3.9 3.6-7 8-7s8 3.1 8 7v1H4v-1z" />
            </svg>
          </div>
          <span
            style="font-size: 10px; font-weight: {{ $isAccountActive ? '700' : '500' }}; font-family: 'Inter', sans-serif; transition: font-weight 0.25s;">Account</span>
        </a>

      </div>
    </div>
  </div>
@endif