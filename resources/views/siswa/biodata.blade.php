@extends('layouts.header', ['active_step' => 2])

@section('content')
  <div class="w-full max-w-6xl  bg-white rounded-3xl shadow-xl overflow-hidden border border-purple-100 p-6 sm:p-10">
    <!-- Brand Header -->
    <div class="flex items-center justify-between mb-8 pb-6 border-b border-purple-100">
      <div class="flex items-center gap-3">
        <img src="{{ asset('images/logoPM.webp') }}" alt="Logo" class="w-10 h-10 object-contain" />
        <span class="font-display text-lg font-bold text-purple-950">Paradise <span class="text-amber-500">of Math</span></span>
      </div>
    </div>

    <!-- Mini Progress Bar for 7 Bagian -->
    <div class="mb-8">
      <div class="h-2 w-full bg-purple-100 rounded-full overflow-hidden">
        <div class="h-full bg-gradient-to-r from-purple-500 to-purple-900 rounded-full transition-all duration-300" id="progressFill" style="width:14.3%"></div>
      </div>
      <div class="text-xs font-bold text-slate-500 mt-2" id="progressText">Bagian 2 dari 7</div>
    </div>

    <!-- Notifications -->
    @if (session('success'))
      <div class="mb-5 p-4 rounded-xl text-sm font-medium border bg-emerald-50 border-emerald-200 text-emerald-800" role="alert">
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

      <!-- BAGIAN 2 — DATA SISWA -->
      <section class="step-panel active space-y-5" data-step="2">
        <span class="inline-block bg-purple-100 text-purple-700 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Bagian 2 dari 7</span>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-purple-950">Data Siswa</h1>
        <p class="text-sm text-slate-500">Isi data diri siswa dengan lengkap dan benar sesuai identitas resmi.</p>

        <div class="field" data-required="true">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap Siswa<span class="text-amber-600 ml-1">*</span></label>
          <input type="text" name="nama_lengkap"
            value="{{ old('nama_lengkap', auth()->guard('siswa')->user()?->name) }}"
            placeholder="Contoh: Muhammad Iqbal Ramadhan"
            class="form-input">
          <div class="error">Nama lengkap wajib diisi.</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Panggilan<span class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="nama_panggilan" placeholder="Contoh: Iqbal" class="form-input">
            <div class="error">Nama panggilan wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. HP Siswa<span class="text-amber-600 ml-1">*</span></label>
            <input type="tel" name="no_hp" placeholder="08xx-xxxx-xxxx" class="form-input">
            <div class="error">Nomor HP wajib diisi.</div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tempat Lahir<span class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="tempat_lahir" placeholder="Contoh: Pekalongan" class="form-input">
            <div class="error">Tempat lahir wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Lahir<span class="text-amber-600 ml-1">*</span></label>
            <input type="date" name="tanggal_lahir" class="form-input">
            <div class="error">Tanggal lahir wajib diisi.</div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kelas<span class="text-amber-600 ml-1">*</span></label>
            <select name="kelas" class="form-input cursor-pointer">
              <option value="">Pilih kelas</option>
              <optgroup label="SD">
                <option>Kelas 1 SD</option>
                <option>Kelas 2 SD</option>
                <option>Kelas 3 SD</option>
                <option>Kelas 4 SD</option>
                <option>Kelas 5 SD</option>
                <option>Kelas 6 SD</option>
              </optgroup>
              <optgroup label="SMP">
                <option>Kelas 7 SMP</option>
                <option>Kelas 8 SMP</option>
                <option>Kelas 9 SMP</option>
              </optgroup>
              <optgroup label="SMA">
                <option>Kelas 10 SMA</option>
                <option>Kelas 11 SMA</option>
                <option>Kelas 12 SMA</option>
              </optgroup>
            </select>
            <div class="error">Kelas wajib dipilih.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Sekolah<span class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="sekolah" placeholder="Nama sekolah asal" class="form-input">
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
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Rumah<span class="text-amber-600 ml-1">*</span></label>
          <textarea name="alamat" placeholder="Alamat lengkap tempat tinggal" class="form-input min-h-[90px]"></textarea>
          <div class="error">Alamat rumah wajib diisi.</div>
        </div>

        <div class="field" data-required="true">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Akun Sosmed (Instagram &amp; TikTok)<span class="text-amber-600 ml-1">*</span></label>
          <input type="text" name="sosmed_umum" placeholder="Contoh: IG @nama_siswa · TikTok @nama_siswa" class="form-input">
          <div class="error">Nama akun sosmed wajib diisi.</div>
        </div>
      </section>

      <!-- BAGIAN 3 — MINAT & SUMBER INFO -->
      <section class="step-panel space-y-5" data-step="3">
        <span class="inline-block bg-purple-100 text-purple-700 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Bagian 3 dari 7</span>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-purple-950">Minat Belajar &amp; Sumber Informasi</h1>
        <p class="text-sm text-slate-500">Ceritakan bagaimana kamu mengenal PM dan pelajaran apa yang ingin di les kan.</p>

        <div class="field" data-required="true">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mengetahui info PM dari mana?<span class="text-amber-600 ml-1">*</span></label>
          <div class="flex flex-col gap-3">
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="radio" name="sumber_info" value="Sosial media (TikTok, Instagram, dll)" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Sosial media (TikTok, Instagram, dll)</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="radio" name="sumber_info" value="Ajakan teman yang sudah join di PM" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Ajakan teman yang sudah join di PM (isikan nama temannya di kolom bawah)</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="radio" name="sumber_info" value="Yang lain" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Yang lain</span>
            </label>
          </div>
          <div class="error">Silakan pilih salah satu sumber info.</div>
        </div>

        <div class="field" data-required="false">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Teman / Keterangan Lainnya</label>
          <input type="text" name="sumber_info_detail" placeholder="Isi jika memilih ajakan teman / yang lain" class="form-input">
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
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mata Pelajaran &amp; Jumlah Minimal Shift/Minggu<span class="text-amber-600 ml-1">*</span></label>
          <div class="text-xs text-slate-400 mb-3">Pilih semua pelajaran yang ingin di les kan di PM.</div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="checkbox" name="mapel" value="Matematika Wajib 2x" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Matematika Wajib 2x</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="checkbox" name="mapel" value="Matematika Lanjut 3x" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Matematika Lanjut 3x</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="checkbox" name="mapel" value="Matematika Wajib + Lanjut 4x" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Matematika Wajib + Matematika Lanjut 4x</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="checkbox" name="mapel" value="Fisika 2x" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Fisika 2x</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="checkbox" name="mapel" value="Kimia 2x" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Kimia 2x</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="checkbox" name="mapel" value="Biologi 2x" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Biologi 2x</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="checkbox" name="mapel" value="Bahasa Inggris 2x" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Bahasa Inggris 2x</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="checkbox" name="mapel" value="Bahasa Indonesia 1x" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Bahasa Indonesia 1x</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="checkbox" name="mapel" value="Bahasa Indonesia 2x" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Bahasa Indonesia 2x</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="checkbox" name="mapel" value="Sejarah 1x" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Sejarah 1x</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="checkbox" name="mapel" value="Sejarah 2x" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Sejarah 2x</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="checkbox" name="mapel" value="Matematika TKA 2x" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Matematika TKA 2x</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="checkbox" name="mapel" value="Bahasa Indonesia TKA 2x" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Bahasa Indonesia TKA 2x</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="checkbox" name="mapel" value="Bahasa Inggris TKA 2x" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Bahasa Inggris TKA 2x</span>
            </label>
          </div>
          <div class="error">Pilih minimal satu mata pelajaran.</div>
        </div>

        <div class="field" data-required="true">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nilai UN / Rapor Terakhir Sesuai Pelajaran yang Akan Di Les kan<span class="text-amber-600 ml-1">*</span></label>
          <textarea name="nilai_terakhir" placeholder="Contoh: Matematika 85, Fisika 78, ..." class="form-input min-h-[90px]"></textarea>
          <div class="error">Kolom ini wajib diisi.</div>
        </div>

        <div class="field" data-required="false">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilihan Guru Matematika</label>
          <div class="flex flex-col gap-3">
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="radio" name="pilihan_guru" value="Kak Ika (Master)" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Kak Ika (Master)</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="radio" name="pilihan_guru" value="Kak Angel (Co Master)" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Kak Angel (Co Master)</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="radio" name="pilihan_guru" value="Kak Sofia (Co Master)" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Kak Sofia (Co Master)</span>
            </label>
            <label class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
              <input type="radio" name="pilihan_guru" value="Keyawan" class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
              <span>Keyawan</span>
            </label>
          </div>
        </div>
      </section>

      <!-- BAGIAN 4 — JADWAL PULANG SEKOLAH -->
      <section class="step-panel space-y-5" data-step="4">
        <span class="inline-block bg-purple-100 text-purple-700 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Bagian 4 dari 7</span>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-purple-950">Jadwal Pulang Sekolah</h1>
        <p class="text-sm text-slate-500">Isi jam pulang sekolah siswa untuk setiap hari.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Senin<span class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="pulang_senin" placeholder="Contoh: 15.00" class="form-input">
            <div class="error">Jam pulang Senin wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Selasa<span class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="pulang_selasa" placeholder="Contoh: 15.00" class="form-input">
            <div class="error">Jam pulang Selasa wajib diisi.</div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Rabu<span class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="pulang_rabu" placeholder="Contoh: 15.00" class="form-input">
            <div class="error">Jam pulang Rabu wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kamis<span class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="pulang_kamis" placeholder="Contoh: 15.00" class="form-input">
            <div class="error">Jam pulang Kamis wajib diisi.</div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jumat<span class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="pulang_jumat" placeholder="Contoh: 11.00" class="form-input">
            <div class="error">Jam pulang Jumat wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Sabtu<span class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="pulang_sabtu" placeholder="Contoh: 12.00 / Libur" class="form-input">
            <div class="error">Jam pulang Sabtu wajib diisi.</div>
          </div>
        </div>
      </section>

      <!-- BAGIAN 5 — KEGIATAN RUTIN -->
      <section class="step-panel space-y-5" data-step="5">
        <span class="inline-block bg-purple-100 text-purple-700 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Bagian 5 dari 7</span>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-purple-950">Kegiatan Rutin Selain Jadwal Sekolah</h1>
        <p class="text-sm text-slate-500">Contoh: les lain, ekstrakurikuler, ngaji, olahraga, dll.</p>

        <div class="field" data-required="true">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Hari Apa dari Jam Berapa Sampai Jam Berapa?<span class="text-amber-600 ml-1">*</span></label>
          <textarea name="kegiatan_rutin"
            placeholder="Contoh: Selasa & Kamis les renang 16.00-17.30, Sabtu ngaji 08.00-10.00"
            class="form-input min-h-[120px]"></textarea>
          <div class="error">Kolom ini wajib diisi. Isi 'Tidak ada' jika tidak ada kegiatan rutin.</div>
        </div>
      </section>

      <!-- BAGIAN 6 — DATA IBU -->
      <section class="step-panel space-y-5" data-step="6">
        <span class="inline-block bg-purple-100 text-purple-700 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Bagian 6 dari 7</span>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-purple-950">Data Ibu</h1>
        <p class="text-sm text-slate-500">Isi data diri ibu kandung / wali siswa.</p>

        <div class="field" data-required="true">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap Ibu<span class="text-amber-600 ml-1">*</span></label>
          <input type="text" name="ibu_nama_lengkap" placeholder="Nama lengkap ibu" class="form-input">
          <div class="error">Nama lengkap ibu wajib diisi.</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Panggilan Ibu<span class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="ibu_nama_panggilan" placeholder="Nama panggilan ibu" class="form-input">
            <div class="error">Nama panggilan ibu wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. HP Ibu<span class="text-amber-600 ml-1">*</span></label>
            <input type="tel" name="ibu_no_hp" placeholder="08xx-xxxx-xxxx" class="form-input">
            <div class="error">No. HP ibu wajib diisi.</div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Umur Ibu<span class="text-amber-600 ml-1">*</span></label>
            <input type="number" name="ibu_umur" placeholder="Contoh: 42" min="15" max="90" class="form-input">
            <div class="error">Umur ibu wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pekerjaan Ibu<span class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="ibu_pekerjaan" placeholder="Contoh: Ibu Rumah Tangga / PNS" class="form-input">
            <div class="error">Pekerjaan ibu wajib diisi.</div>
          </div>
        </div>

        <div class="field" data-required="false">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Akun Instagram Ibu</label>
          <input type="text" name="ibu_instagram" placeholder="@username (opsional)" class="form-input">
        </div>
      </section>

      <!-- BAGIAN 7 — DATA AYAH -->
      <section class="step-panel space-y-5" data-step="7">
        <span class="inline-block bg-purple-100 text-purple-700 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Bagian 7 dari 7</span>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-purple-950">Data Ayah</h1>
        <p class="text-sm text-slate-500">Isi data diri ayah kandung / wali siswa.</p>

        <div class="field" data-required="true">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap Ayah<span class="text-amber-600 ml-1">*</span></label>
          <input type="text" name="ayah_nama_lengkap" placeholder="Nama lengkap ayah" class="form-input">
          <div class="error">Nama lengkap ayah wajib diisi.</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Panggilan Ayah<span class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="ayah_nama_panggilan" placeholder="Nama panggilan ayah" class="form-input">
            <div class="error">Nama panggilan ayah wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. HP Ayah<span class="text-amber-600 ml-1">*</span></label>
            <input type="tel" name="ayah_no_hp" placeholder="08xx-xxxx-xxxx" class="form-input">
            <div class="error">No. HP ayah wajib diisi.</div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Umur Ayah<span class="text-amber-600 ml-1">*</span></label>
            <input type="number" name="ayah_umur" placeholder="Contoh: 45" min="15" max="95" class="form-input">
            <div class="error">Umur ayah wajib diisi.</div>
          </div>
          <div class="field" data-required="true">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pekerjaan Ayah<span class="text-amber-600 ml-1">*</span></label>
            <input type="text" name="ayah_pekerjaan" placeholder="Contoh: Wiraswasta / Karyawan Swasta" class="form-input">
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
        <div class="w-16 h-16 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-600 text-3xl flex items-center justify-center mx-auto mb-6 shadow-sm">
          ✓
        </div>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-purple-950 mb-3">Formulir Berhasil Dikirim</h1>
        <p class="text-slate-500 max-w-md mx-auto">Terima kasih! Data pendaftaran akan segera diproses oleh tim Paradise of Math.</p>
      </section>

      <!-- Action Navigation Buttons -->
      <div class="flex justify-between items-center gap-4 pt-6 border-t border-purple-100 mt-8" id="navRow">
        <button type="button" class="px-8 py-3 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold transition-all text-sm border border-slate-200" id="btnBack">
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
  </style>

  <script>
    const steps = ['2', '3', '4', '5', '6', '7', 'done'];
    let current = 0;
    const totalBagian = 7;

    const panels = document.querySelectorAll('.step-panel');
    const btnBack = document.getElementById('btnBack');
    const btnNext = document.getElementById('btnNext');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const navRow = document.getElementById('navRow');
    const form = document.getElementById('wizardForm');

    function renderStep() {
      panels.forEach(p => p.classList.toggle('active', p.dataset.step === steps[current]));

      const isDone = steps[current] === 'done';
      btnBack.style.display = current === 0 ? 'none' : 'inline-flex';
      btnNext.textContent = (current === steps.length - 2) ? 'Kirim Formulir ✓' : 'Lanjut →';
      navRow.style.display = isDone ? 'none' : 'flex';

      const bagianNumber = isDone ? totalBagian : parseInt(steps[current]);
      progressFill.style.width = ((bagianNumber) / totalBagian * 100) + '%';
      progressText.textContent = isDone ? 'Selesai — 7 dari 7 bagian' : `Bagian ${bagianNumber} dari ${totalBagian}`;

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
          const input = field.querySelector('input, select, textarea');
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

    btnNext.addEventListener('click', () => {
      if (steps[current] === 'done') return;
      if (!validateCurrentStep()) return;

      if (current === steps.length - 2) {
        // last real step before 'done' -> submit form to proceed to regisKategory
        form.submit();
      } else {
        current++;
        renderStep();
      }
    });

    btnBack.addEventListener('click', () => {
      if (current > 0) {
        current--;
        renderStep();
      }
    });

    // clear invalid state as user fixes fields
    form.addEventListener('input', (e) => {
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
      if (e.target.type === 'radio' || e.target.type === 'checkbox') {
        const field = e.target.closest('.field');
        if (field) field.classList.remove('invalid');
      }
    });

    renderStep();
  </script>
@endsection