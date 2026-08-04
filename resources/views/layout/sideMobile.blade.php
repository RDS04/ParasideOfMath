@php
  $isHomeActive = Request::is('siswa') || Request::is('siswa/dashboard');
  $isExploreActive = Request::is('siswa/explore') || Request::is('siswa/explore/*');
  $isJadwalActive = Request::is('siswa/jadwal') || Request::is('siswa/jadwal/*');
  $isChatActive = Request::is('siswa/chat') || Request::is('siswa/chat/*');
  $isAccountActive = Request::is('siswa/account') || Request::is('siswa/account/*') || Request::is('siswa/profile');
@endphp

<!-- Siswa Bottom Navigation (Mobile Only) -->
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