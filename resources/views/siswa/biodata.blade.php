@extends('layouts.header', ['active_step' => 2])

@section('content')
  <div
    class="w-full max-w-7xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden border border-purple-100 p-6 sm:p-10">

    <!-- Brand Header -->
    <div class="flex items-center justify-between mb-8 pb-6 border-b border-purple-100">
      <div class="flex items-center gap-3">
        <img src="{{ asset('images/logoPM.webp') }}" alt="Logo" class="w-10 h-10 object-contain" />
        <span class="font-display text-lg font-bold text-purple-950">Paradise <span class="text-amber-500">of
            Math</span></span>
      </div>
    </div>

    <!-- Mini Progress Bar for 6 Bagian -->
    <div class="mb-8">
      <div class="h-2 w-full bg-purple-100 rounded-full overflow-hidden">
        <div class="h-full bg-gradient-to-r from-purple-500 to-purple-900 rounded-full transition-all duration-300"
          id="progressFill" style="width:16.7%"></div>
      </div>
      <div class="text-xs font-bold text-slate-500 mt-2" id="progressText">Bagian 1 dari 6</div>
    </div>

    <!-- Notifications -->
    @if (session('success'))
      <div class="mb-5 p-4 rounded-xl text-sm font-medium border bg-emerald-50 border-emerald-200 text-emerald-800"
        role="alert">
        {{ session('success') }}
      </div>
    @endif

    @if (session('error'))
      <div class="mb-5 p-4 rounded-xl text-sm font-medium border bg-red-50 border-red-200 text-red-800" role="alert">
        {{ session('error') }}
      </div>
    @endif

    <form id="wizardForm" action="{{ route('siswa.biodata.submit') }}" method="POST" novalidate>
      @csrf

      <!-- BAGIAN 1 — DATA SISWA -->
      <section class="step-panel active space-y-5" data-step="1">
        <span
          class="inline-block bg-purple-100 text-purple-700 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Bagian
          1 dari 6</span>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-purple-950">Data Siswa</h1>
        <p class="text-sm text-slate-500">Isi data diri siswa dengan lengkap dan benar sesuai identitas resmi.</p>

        <div class="field" data-required="true">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap Siswa<span
              class="text-amber-600 ml-1">*</span></label>
          <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', auth()->guard('siswa')->user()?->name) }}"
            placeholder="Contoh: Muhammad Iqbal Ramadhan" class="form-input">
          <div class="error">Nama lengkap wajib diisi.</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Panggilan<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="nama_panggilan" placeholder="Contoh: Iqbal" class="form-input">
            <div class="error">Nama panggilan wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. HP Siswa (WA)<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="tel" name="no_hp" value="{{ old('no_hp', auth()->guard('siswa')->user()?->whatsapp) }}"
              placeholder="08xx-xxxx-xxxx" class="form-input">
            <div class="error">Nomor HP wajib diisi.</div>
          </div>
          <div class="field" data-required="false">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. Telepon Rumah</label>
            <input type="tel" name="no_telp" placeholder="Contoh: 0285-xxxxxx / 08xx" class="form-input">
            <div class="error">No. Telepon Rumah wajib diisi jika diperlukan.</div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tempat Lahir<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="tempat_lahir" placeholder="Contoh: Pekalongan" class="form-input">
            <div class="error">Tempat lahir wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Lahir<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="date" name="tanggal_lahir" class="form-input">
            <div class="error">Tanggal lahir wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jenis Kelamin<span
                class="text-amber-600 ml-1">*</span></label>
            <select name="jenis_kelamin" class="form-input cursor-pointer">
              <option value="">Pilih jenis kelamin</option>
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
            <div class="error">Jenis kelamin wajib dipilih.</div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kelas<span
                class="text-amber-600 ml-1">*</span></label>
            <select name="kelas" id="kelasSelect" class="form-input cursor-pointer">
              <option value="">Pilih kelas</option>
              <optgroup label="SD" id="kelas_sd">
                <option>Kelas 1 SD</option>
                <option>Kelas 2 SD</option>
                <option>Kelas 3 SD</option>
                <option>Kelas 4 SD</option>
                <option>Kelas 5 SD</option>
                <option>Kelas 6 SD</option>
              </optgroup>
              <optgroup label="SMP" id="kelas_smp">
                <option>Kelas 7 SMP</option>
                <option>Kelas 8 SMP</option>
                <option>Kelas 9 SMP</option>
              </optgroup>
              <optgroup label="SMA" id="kelas_sma">
                <option>Kelas 10 SMA</option>
                <option>Kelas 11 SMA</option>
                <option>Kelas 12 SMA</option>
              </optgroup>
              <option value="lainnya">Lainnya</option>
            </select>
            <input type="text" id="kelasLainnya" placeholder="Tulis kelas kamu" class="form-input hidden mt-3">
            <div class="error">Kelas wajib dipilih.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Sekolah<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="sekolah" value="{{ old('sekolah', auth()->guard('siswa')->user()?->sekolah) }}"
              placeholder="Nama sekolah asal" class="form-input">
            <div class="error">Nama sekolah wajib diisi.</div>
          </div>
        </div>

        <div class="field" data-required="false">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jurusan (Untuk SMA)</label>
          <select name="jurusan" class="form-input cursor-pointer">
            <option value="">— Tidak berlaku / pilih jurusan —</option>
            <option>IPA</option>
            <option>IPS</option>
            <option>Bahasa</option>
          </select>
          <div class="text-xs text-slate-400 mt-1">Kosongkan jika belum SMA.</div>
        </div>

        <div class="field" data-required="true">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Rumah<span
              class="text-amber-600 ml-1">*</span></label>
          <textarea name="alamat" placeholder="Alamat lengkap tempat tinggal" class="form-input min-h-[90px]"></textarea>
          <div class="error">Alamat rumah wajib diisi.</div>
        </div>

        <div class="field" data-required="true">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Akun Sosmed (Instagram
            &amp; TikTok)<span class="text-amber-600 ml-1">*</span></label>
          <input type="text" name="sosmed_umum" placeholder="Contoh: IG @nama_siswa · TikTok @nama_siswa"
            class="form-input">
          <div class="error">Nama akun sosmed wajib diisi.</div>
        </div>
      </section>

      <!-- BAGIAN 2 — MINAT & SUMBER INFO -->
      <section class="step-panel space-y-5" data-step="2">
        <span
          class="inline-block bg-purple-100 text-purple-700 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Bagian
          2 dari 6</span>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-purple-950">Minat Belajar &amp; Sumber Informasi</h1>
        <p class="text-sm text-slate-500">Ceritakan bagaimana kamu mengenal PM dan pelajaran apa yang ingin di les kan.
        </p>

        <div class="field" data-required="true">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mengetahui info PM dari
            mana?<span class="text-amber-600 ml-1">*</span></label>
          <div class="flex flex-col gap-3">
            <label
              class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="radio" name="sumber_info" value="Sosial media (TikTok, Instagram, dll)"
                class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Sosial media (TikTok, Instagram, dll)</span>
            </label>
            <label
              class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="radio" name="sumber_info" value="Ajakan teman yang sudah join di PM"
                class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Ajakan teman yang sudah join di PM (isikan nama temannya di kolom bawah)</span>
            </label>
            <label
              class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="radio" name="sumber_info" value="Yang lain" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Yang lain</span>
            </label>
          </div>
          <div class="error">Silakan pilih salah satu sumber info.</div>
        </div>

        <div class="field" data-required="false">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Teman / Keterangan
            Lainnya</label>
          <input type="text" name="sumber_info_detail" placeholder="Isi jika memilih ajakan teman / yang lain"
            class="form-input">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
          <div class="field" data-required="false">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Instagram</label>
            <input type="text" name="ig_siswa" placeholder="@username" class="form-input">
          </div>
          <div class="field" data-required="false">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">TikTok</label>
            <input type="text" name="tiktok_siswa" placeholder="@username" class="form-input">
          </div>
          <div class="field" data-required="false">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Facebook</label>
            <input type="text" name="fb_siswa" placeholder="Nama akun Facebook" class="form-input">
          </div>
        </div>



        <div class="field" data-required="true">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nilai UN / Rapor Terakhir
            Sesuai Pelajaran yang Akan Di Les kan<span class="text-amber-600 ml-1">*</span></label>
          <textarea name="nilai_terakhir" placeholder="Contoh: Matematika 85, Fisika 78, ..."
            class="form-input min-h-[90px]"></textarea>
          <div class="error">Kolom ini wajib diisi.</div>
        </div>

      </section>

      <!-- BAGIAN 3 — JADWAL PULANG SEKOLAH -->
      <section class="step-panel space-y-5" data-step="3">
        <span
          class="inline-block bg-purple-100 text-purple-700 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Bagian
          3 dari 6</span>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-purple-950">Jadwal Pulang Sekolah</h1>
        <p class="text-sm text-slate-500">Isi jam pulang sekolah siswa untuk setiap hari.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Senin<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="time" name="pulang_senin" class="form-input">
            <div class="error">Jam pulang Senin wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Selasa<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="time" name="pulang_selasa" class="form-input">
            <div class="error">Jam pulang Selasa wajib diisi.</div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Rabu<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="time" name="pulang_rabu" class="form-input">
            <div class="error">Jam pulang Rabu wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kamis<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="time" name="pulang_kamis" class="form-input">
            <div class="error">Jam pulang Kamis wajib diisi.</div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jumat<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="time" name="pulang_jumat" class="form-input">
            <div class="error">Jam pulang Jumat wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Sabtu<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="time" name="pulang_sabtu" class="form-input">
            <div class="error">Jam pulang Sabtu wajib diisi.</div>
          </div>
        </div>
      </section>

      <!-- BAGIAN 4 — KEGIATAN RUTIN -->
      <section class="step-panel space-y-5" data-step="4">
        <span
          class="inline-block bg-purple-100 text-purple-700 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Bagian
          4 dari 6</span>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-purple-950">Kegiatan Rutin Selain Jadwal Sekolah</h1>
        <p class="text-sm text-slate-500">Contoh: les lain, ekstrakurikuler, ngaji, olahraga, dll.</p>

        <div class="field" data-required="true">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Hari Apa dari Jam Berapa
            Sampai Jam Berapa?<span class="text-amber-600 ml-1">*</span></label>
          <textarea name="kegiatan_rutin"
            placeholder="Contoh: Selasa & Kamis les renang 16.00-17.30, Sabtu ngaji 08.00-10.00"
            class="form-input min-h-[120px]"></textarea>
          <div class="error">Kolom ini wajib diisi. Isi 'Tidak ada' jika tidak ada kegiatan rutin.</div>
        </div>
      </section>

      <!-- BAGIAN 5 — DATA IBU -->
      <section class="step-panel space-y-5" data-step="5">
        <span
          class="inline-block bg-purple-100 text-purple-700 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Bagian
          5 dari 6</span>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-purple-950">Data Ibu</h1>
        <p class="text-sm text-slate-500">Isi data diri ibu kandung / wali siswa.</p>

        <div class="field" data-required="true">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap Ibu<span
              class="text-amber-600 ml-1">*</span></label>
          <input type="text" name="ibu_nama_lengkap" placeholder="Nama lengkap ibu" class="form-input">
          <div class="error">Nama lengkap ibu wajib diisi.</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Panggilan Ibu<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="ibu_nama_panggilan" placeholder="Nama panggilan ibu" class="form-input">
            <div class="error">Nama panggilan ibu wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. HP Ibu<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="tel" name="ibu_no_hp" placeholder="08xx-xxxx-xxxx" class="form-input">
            <div class="error">No. HP ibu wajib diisi.</div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Umur Ibu<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="number" name="ibu_umur" placeholder="Contoh: 42" min="15" max="90" class="form-input">
            <div class="error">Umur ibu wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pekerjaan Ibu<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="ibu_pekerjaan" placeholder="Contoh: Ibu Rumah Tangga / PNS" class="form-input">
            <div class="error">Pekerjaan ibu wajib diisi.</div>
          </div>
        </div>

        <div class="field" data-required="false">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Akun Instagram Ibu</label>
          <input type="text" name="ibu_instagram" placeholder="@username (opsional)" class="form-input">
        </div>
      </section>

      <!-- BAGIAN 6 — DATA AYAH -->
      <section class="step-panel space-y-5" data-step="6">
        <span
          class="inline-block bg-purple-100 text-purple-700 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Bagian
          6 dari 6</span>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-purple-950">Data Ayah</h1>
        <p class="text-sm text-slate-500">Isi data diri ayah kandung / wali siswa.</p>

        <div class="field" data-required="true">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap Ayah<span
              class="text-amber-600 ml-1">*</span></label>
          <input type="text" name="ayah_nama_lengkap" placeholder="Nama lengkap ayah" class="form-input">
          <div class="error">Nama lengkap ayah wajib diisi.</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Panggilan Ayah<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="ayah_nama_panggilan" placeholder="Nama panggilan ayah" class="form-input">
            <div class="error">Nama panggilan ayah wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. HP Ayah<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="tel" name="ayah_no_hp" placeholder="08xx-xxxx-xxxx" class="form-input">
            <div class="error">No. HP ayah wajib diisi.</div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Umur Ayah<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="number" name="ayah_umur" placeholder="Contoh: 45" min="15" max="95" class="form-input">
            <div class="error">Umur ayah wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pekerjaan Ayah<span
                class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="ayah_pekerjaan" placeholder="Contoh: Wiraswasta / Karyawan Swasta"
              class="form-input">
            <div class="error">Pekerjaan ayah wajib diisi.</div>
          </div>
        </div>

        <div class="field" data-required="false">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Akun Instagram Ayah</label>
          <input type="text" name="ayah_instagram" placeholder="@username (opsional)" class="form-input">
        </div>
      </section>

      <!-- SELESAI -->
      <section class="step-panel text-center py-12" data-step="done">
        <div
          class="w-16 h-16 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-600 text-3xl flex items-center justify-center mx-auto mb-6 shadow-sm">
          ✓
        </div>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-purple-950 mb-3">Formulir Berhasil Dikirim</h1>
        <p class="text-slate-500 max-w-md mx-auto">Terima kasih! Data pendaftaran akan segera diproses oleh tim Paradise
          of Math.</p>
      </section>

      <!-- Action Navigation Buttons -->
      <div class="flex justify-between items-center gap-4 pt-6 border-t border-purple-100 mt-8" id="navRow">
        <button type="button"
          class="px-8 py-3 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold transition-all text-sm border border-slate-200"
          id="btnBack">
          ← Kembali
        </button>
        <button type="button" class="btn-brand px-8 py-3 text-sm flex-1 md:flex-none" id="btnNext">
          Lanjut →
        </button>
      </div>
    </form>

    <div class="text-center text-xs text-slate-400 mt-8 pt-5 border-t border-purple-100">
      © 2026 · Paradise of Math — Sistem Manajemen Registrasi Siswa
    </div>
  </div>

  <!-- Custom Confirmation Modal -->
  <div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <!-- Backdrop with blur -->
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs opacity-0 transition-opacity duration-300"></div>

    <!-- Modal Card -->
    <div
      class="relative bg-white rounded-3xl shadow-2xl border border-purple-100 max-w-md w-full p-6 text-center transform scale-95 opacity-0 transition-all duration-300 ease-out"
      id="modalCard">
      <!-- Icon -->
      <div
        class="w-16 h-16 bg-amber-50 border border-amber-200 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
        <i class="fas fa-exclamation-triangle text-2xl animate-bounce"></i>
      </div>

      <!-- Title -->
      <h3 class="font-display text-xl font-bold text-purple-950 mb-2">Konfirmasi Kirim Data</h3>
      <!-- Message -->
      <p class="text-sm text-slate-500 leading-relaxed mb-6">
        Pastikan data sudah terisi dengan benar dan tersimpan kedalam database.
      </p>

      <!-- Actions -->
      <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <button type="button" id="btnCancelSubmit"
          class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition-all text-sm border border-slate-200 order-2 sm:order-1">
          Periksa Kembali
        </button>
        <button type="button" id="btnConfirmSubmit"
          class="px-5 py-3 rounded-xl bg-gradient-to-r from-purple-700 to-purple-900 hover:from-purple-800 hover:to-purple-950 text-white font-bold transition-all text-sm shadow-md shadow-purple-900/20 order-1 sm:order-2 flex-1">
          Kirim & Lanjut
        </button>
      </div>
    </div>
  </div>
  <style>
    .step-panel {
      display: none;
    }

    .step-panel.active {
      display: block;
      animation: fadeIn .25s ease-in-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(6px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Validation UI State Classes */
    .field.invalid .form-input {
      border-color: #ef4444 !important;
      background-color: #fef2f2 !important;
      box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1) !important;
    }

    .field.invalid .choice {
      border-color: #ef4444 !important;
      background-color: #fef2f2 !important;
    }

    .field.invalid .error {
      display: block !important;
      color: #ef4444;
      font-size: 0.75rem;
      margin-top: 0.375rem;
      font-weight: 500;
    }

    .error {
      display: none;
    }

    .backdrop-blur-xs {
      backdrop-filter: blur(2px);
    }
  </style>

  <script>
    const steps = ['1', '2', '3', '4', '5', '6', 'done'];
    let current = 0;
    const totalBagian = 6;

    const panels = document.querySelectorAll('.step-panel');
    const btnBack = document.getElementById('btnBack');
    const btnNext = document.getElementById('btnNext');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const navRow = document.getElementById('navRow');
    const form = document.getElementById('wizardForm');

    // LocalStorage keys prefix
    const STORAGE_PREFIX = 'siswa_biodata_';

    // Function to save form data to LocalStorage
    function saveFormData() {
      const elements = form.querySelectorAll('input, select, textarea');
      elements.forEach(el => {
        if (!el.name || el.name === '_token') return;

        if (el.type === 'radio') {
          if (el.checked) {
            localStorage.setItem(STORAGE_PREFIX + el.name, el.value);
          }
        } else if (el.type === 'checkbox') {
          const checkboxes = form.querySelectorAll(`input[name="${el.name}"][type="checkbox"]`);
          const checkedValues = Array.from(checkboxes).filter(c => c.checked).map(c => c.value);
          localStorage.setItem(STORAGE_PREFIX + el.name, JSON.stringify(checkedValues));
        } else {
          localStorage.setItem(STORAGE_PREFIX + el.name, el.value);
        }
      });
      // Also save current step
      localStorage.setItem('siswa_biodata_step', current);
    }

    // Function to load form data from LocalStorage
    function loadFormData() {
      const elements = form.querySelectorAll('input, select, textarea');

      // Load and set the current step
      const savedStep = localStorage.getItem('siswa_biodata_step');
      if (savedStep !== null) {
        const stepNum = parseInt(savedStep);
        if (!isNaN(stepNum) && stepNum >= 0 && stepNum < steps.length) {
          current = stepNum;
        }
      }

      elements.forEach(el => {
        if (!el.name || el.name === '_token') return;

        const savedValue = localStorage.getItem(STORAGE_PREFIX + el.name);
        if (savedValue === null) return;

        if (el.id === 'kelasSelect') {
          const isStandardOption = Array.from(el.options).some(opt => opt.value === savedValue);
          if (isStandardOption) {
            el.value = savedValue;
            toggleKelasLainnya();
          } else if (savedValue) {
            el.value = 'lainnya';
            toggleKelasLainnya();
            kelasLainnya.value = savedValue;
            kelasLainnya.dispatchEvent(new Event('input', { bubbles: true }));
          }
          return;
        }

        if (el.type === 'radio') {
          if (el.value === savedValue) {
            el.checked = true;
            // Dispatch change event to clean up errors if needed
            el.dispatchEvent(new Event('change', { bubbles: true }));
          }
        } else if (el.type === 'checkbox') {
          try {
            const checkedValues = JSON.parse(savedValue);
            if (Array.isArray(checkedValues)) {
              el.checked = checkedValues.includes(el.value);
              el.dispatchEvent(new Event('change', { bubbles: true }));
            }
          } catch (e) {
            console.error('Error parsing checkbox value', e);
          }
        } else {
          el.value = savedValue;
          el.dispatchEvent(new Event('input', { bubbles: true }));
        }
      });
    }

    // Function to clear form data from LocalStorage
    function clearFormData() {
      const keys = [];
      for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        if (key && (key.startsWith(STORAGE_PREFIX) || key === 'siswa_biodata_step')) {
          keys.push(key);
        }
      }
      keys.forEach(key => localStorage.removeItem(key));
    }

    function renderStep() {
      panels.forEach(p => p.classList.toggle('active', p.dataset.step === steps[current]));

      const isDone = steps[current] === 'done';
      btnBack.style.display = current === 0 ? 'none' : 'inline-flex';

      // On the 6th step (index 5), the next button should say "Kirim Formulir ✓"
      btnNext.textContent = (current === steps.length - 2) ? 'Kirim Formulir ✓' : 'Lanjut →';
      navRow.style.display = isDone ? 'none' : 'flex';

      const bagianNumber = isDone ? totalBagian : parseInt(steps[current]);
      progressFill.style.width = ((bagianNumber) / totalBagian * 100) + '%';
      progressText.textContent = isDone ? 'Selesai — 6 dari 6 bagian' : `Bagian ${bagianNumber} dari ${totalBagian}`;

      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateCurrentStep() {
      const panel = document.querySelector(`.step-panel[data-step="${steps[current]}"]`);
      let valid = true;

      panel.querySelectorAll('.field[data-required="true"]').forEach(field => {
        const radios = field.querySelectorAll('input[type=radio]');
        const checks = field.querySelectorAll('input[type=checkbox]');
        let filled = true;

        if (radios.length) {
          filled = Array.from(radios).some(r => r.checked);
        } else if (checks.length) {
          filled = Array.from(checks).some(c => c.checked);
        } else {
          const input = field.querySelector('input[name], select[name], textarea[name]');
          filled = !!(input && input.value.trim());
        }

        if (!filled) {
          field.classList.add('invalid');
          valid = false;
        } else {
          field.classList.remove('invalid');
        }
      });

      if (!valid) {
        const firstInvalid = panel.querySelector('.field.invalid');
        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
      return valid;
    }

    function validateAllSteps() {
      let allValid = true;
      panels.forEach(panel => {
        panel.querySelectorAll('.field[data-required="true"]').forEach(field => {
          const radios = field.querySelectorAll('input[type=radio]');
          const checks = field.querySelectorAll('input[type=checkbox]');
          let filled = true;

          if (radios.length) {
            filled = Array.from(radios).some(r => r.checked);
          } else if (checks.length) {
            filled = Array.from(checks).some(c => c.checked);
          } else {
            const input = field.querySelector('input[name], select[name], textarea[name]');
            filled = !!(input && input.value.trim());
          }

          if (!filled) {
            field.classList.add('invalid');
            allValid = false;
          } else {
            field.classList.remove('invalid');
          }
        });
      });
      return allValid;
    }

    // Modal & Loading Elements
    const confirmModal = document.getElementById('confirmModal');
    const modalCard = document.getElementById('modalCard');
    const btnCancelSubmit = document.getElementById('btnCancelSubmit');
    const btnConfirmSubmit = document.getElementById('btnConfirmSubmit');

    function showConfirmationModal() {
      confirmModal.classList.remove('hidden');
      confirmModal.classList.add('flex');
      setTimeout(() => {
        confirmModal.querySelector('.absolute').classList.remove('opacity-0');
        confirmModal.querySelector('.absolute').classList.add('opacity-100');
        modalCard.classList.remove('scale-95', 'opacity-0');
        modalCard.classList.add('scale-100', 'opacity-100');
      }, 10);
    }

    function hideConfirmationModal() {
      confirmModal.querySelector('.absolute').classList.remove('opacity-100');
      confirmModal.querySelector('.absolute').classList.add('opacity-0');
      modalCard.classList.remove('scale-100', 'opacity-100');
      modalCard.classList.add('scale-95', 'opacity-0');
      setTimeout(() => {
        confirmModal.classList.remove('flex');
        confirmModal.classList.add('hidden');
      }, 300);
    }

    btnCancelSubmit.addEventListener('click', hideConfirmationModal);

    btnConfirmSubmit.addEventListener('click', () => {
      hideConfirmationModal();

      if (!validateAllSteps()) {
        let targetStepIndex = 0;
        for (let i = 0; i < steps.length - 1; i++) {
          const panel = document.querySelector(`.step-panel[data-step="${steps[i]}"]`);
          if (panel.querySelector('.field.invalid')) {
            targetStepIndex = i;
            break;
          }
        }
        current = targetStepIndex;
        renderStep();
        alert('Ada data wajib yang belum Anda isi di Bagian ' + steps[current] + '. Silakan lengkapi terlebih dahulu.');
        return;
      }

      // Show global loading overlay
      if (window.showLoading) {
        window.showLoading('Menyimpan data dan mengalihkan ke menu pilih paket...');
      }

      // Clear LocalStorage draft once successfully sent to database
      clearFormData();

      // Submit the form
      setTimeout(() => {
        form.submit();
      }, 1000);
    });

    btnNext.addEventListener('click', () => {
      if (steps[current] === 'done') return;
      if (!validateCurrentStep()) return;

      if (current === steps.length - 2) {
        // Last data step (Step 6) -> show confirmation modal
        showConfirmationModal();
      } else {
        current++;
        renderStep();
        saveFormData(); // Save current step
      }
    });

    btnBack.addEventListener('click', () => {
      if (current > 0) {
        current--;
        renderStep();
        saveFormData(); // Save current step
      }
    });

    // Save data automatically on input/change events
    form.addEventListener('input', (e) => {
      saveFormData();

      const field = e.target.closest('.field');
      if (!field || field.dataset.required !== 'true') return;

      const radios = field.querySelectorAll('input[type=radio]');
      const checks = field.querySelectorAll('input[type=checkbox]');
      let filled;
      if (radios.length) filled = Array.from(radios).some(r => r.checked);
      else if (checks.length) filled = Array.from(checks).some(c => c.checked);
      else filled = e.target.value.trim().length > 0;

      if (filled) field.classList.remove('invalid');
    });

    form.addEventListener('change', (e) => {
      saveFormData();

      if (e.target.type === 'radio' || e.target.type === 'checkbox') {
        const field = e.target.closest('.field');
        if (field) field.classList.remove('invalid');
      }
    });
    const kelasSelect = document.getElementById('kelasSelect');
    const kelasLainnya = document.getElementById('kelasLainnya');

    function toggleKelasLainnya() {
      if (kelasSelect.value === 'lainnya') {
        kelasLainnya.classList.remove('hidden');
        kelasLainnya.name = 'kelas';       // input teks yang jadi sumber value
        kelasSelect.removeAttribute('name'); // select dilepas dari pengiriman form
        kelasLainnya.required = true;
      } else {
        kelasLainnya.classList.add('hidden');
        kelasLainnya.removeAttribute('name');
        kelasSelect.name = 'kelas';         // select jadi sumber value lagi
        kelasLainnya.value = '';
        kelasLainnya.required = false;
      }
    }

    kelasSelect.addEventListener('change', toggleKelasLainnya);
    toggleKelasLainnya(); // jalankan sekali saat halaman dimuat (untuk kasus reload dari LocalStorage)

    // Initialization
    loadFormData();
    renderStep();
  </script>
@endsection