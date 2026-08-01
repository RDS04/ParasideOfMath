@php
    $isHomeActive = Request::is('siswa') || Request::is('siswa/dashboard');
@endphp

<!-- Siswa Bottom Navigation (Mobile Only) -->
<div class="md:hidden" style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 9999; background-color: #ffffff; border-top: 1px solid #ece7f7; padding-bottom: env(safe-area-inset-bottom, 12px); padding-top: 10px; box-shadow: 0 -4px 20px rgba(46, 16, 101, 0.08);">
  <div style="max-width: 480px; margin: 0 auto; width: 100%;">
    <div style="display: flex !important; flex-direction: row !important; justify-content: space-around !important; align-items: center !important; padding-left: 12px; padding-right: 12px;">

      <!-- Home -->
      <a href="{{ route('siswa.dashboard') }}" style="display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; text-decoration: none !important; width: 64px; color: {{ $isHomeActive ? '#7c3aed' : '#94a3b8' }}; transition: color 0.2s;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 24px; height: 24px;">
          <path d="M12 2.5 1.5 11h3V21h6v-6h3v6h6V11h3L12 2.5z"/>
        </svg>
        <span style="font-size: 11px; font-weight: 600; margin-top: 3px; font-family: 'Inter', sans-serif;">Home</span>
      </a>

      <!-- Explore -->
      <a href="#" style="display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; text-decoration: none !important; width: 64px; color: #94a3b8; transition: color 0.2s;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 24px; height: 24px;">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm3.6 6.4-2.13 5.53a1 1 0 0 1-.57.57L7.37 16.6a.5.5 0 0 1-.64-.64l2.13-5.53a1 1 0 0 1 .57-.57l5.53-2.13a.5.5 0 0 1 .64.64z"/>
        </svg>
        <span style="font-size: 11px; font-weight: 500; margin-top: 3px; font-family: 'Inter', sans-serif;">Explore</span>
      </a>

      <!-- Class Room -->
      <a href="#" style="display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; text-decoration: none !important; width: 64px; color: #94a3b8; transition: color 0.2s;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 24px; height: 24px;">
          <circle cx="12" cy="6" r="2.3"/>
          <circle cx="5" cy="8" r="2"/>
          <circle cx="19" cy="8" r="2"/>
          <path d="M12 10c-2.2 0-4 1.4-4 3.2V16h8v-2.8c0-1.8-1.8-3.2-4-3.2z"/>
          <path d="M5 11.3c-1.8 0-3.3 1.2-3.3 2.7V16h3v-2.3c0-.8.3-1.5.8-2.1A4 4 0 0 0 5 11.3z"/>
          <path d="M19 11.3a4 4 0 0 0-.5.3c.5.6.8 1.3.8 2.1V16h3v-2c0-1.5-1.5-2.7-3.3-2.7z"/>
        </svg>
        <span style="font-size: 11px; font-weight: 500; margin-top: 3px; font-family: 'Inter', sans-serif; white-space: nowrap;">Class Room</span>
      </a>

      <!-- Chat -->
      <a href="#" style="display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; text-decoration: none !important; width: 64px; color: #94a3b8; transition: color 0.2s;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 24px; height: 24px;">
          <path d="M4 4h13a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H9l-4 3.5V15H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z" opacity="0.55"/>
          <path d="M9 8h11a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2h-1v2.8L16 18h-7a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2z"/>
        </svg>
        <span style="font-size: 11px; font-weight: 500; margin-top: 3px; font-family: 'Inter', sans-serif;">Chat</span>
      </a>

      <!-- Account -->
      <a href="#" style="display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; text-decoration: none !important; width: 64px; color: #94a3b8; transition: color 0.2s;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 24px; height: 24px;">
          <circle cx="12" cy="8" r="4"/>
          <path d="M4 20c0-3.9 3.6-7 8-7s8 3.1 8 7v1H4v-1z"/>
        </svg>
        <span style="font-size: 11px; font-weight: 500; margin-top: 3px; font-family: 'Inter', sans-serif;">Account</span>
      </a>

    </div>
  </div>
</div>