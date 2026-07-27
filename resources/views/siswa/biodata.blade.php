<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formulir Pendaftaran Siswa — Paradise of Math</title>
<style>
  :root{
    --purple-900:#2e1065;
    --purple-700:#5b21b6;
    --purple-500:#7c3aed;
    --purple-100:#ede9fe;
    --orange-500:#f59e0b;
    --orange-600:#d97706;
    --ink:#1e1b2e;
    --muted:#6b7280;
    --bg:#eef0fb;
    --card:#ffffff;
    --border:#e5e0f5;
    --green-bg:#eafaf1;
    --green-text:#166534;
    --green-border:#bbf0d3;
  }
  *{box-sizing:border-box;}
  body{
    margin:0;
    font-family:'Segoe UI', system-ui, -apple-system, sans-serif;
    background:var(--bg);
    color:var(--ink);
    padding:32px 16px 64px;
  }

  .container{
    max-width:920px;
    margin:0 auto;
    background:var(--card);
    border-radius:20px;
    box-shadow:0 10px 40px rgba(46,16,101,0.08);
    overflow:hidden;
  }
  .header{
    padding:28px 36px 8px;
    display:flex;
    align-items:center;
    gap:10px;
  }
  .logo-mark{
    width:34px;height:34px;border-radius:8px;
    background:var(--purple-700);
    color:#fff;font-weight:800;font-size:13px;
    display:flex;align-items:center;justify-content:center;
  }
  .brand{font-weight:800;font-size:18px;color:var(--purple-900);}
  .brand span{color:var(--orange-500);}

  /* mini progress bar for 7 bagian */
  .progress-wrap{padding:14px 36px 0;}
  .progress-track{
    height:6px;border-radius:4px;background:#e6e1f7;overflow:hidden;
  }
  .progress-fill{
    height:100%;background:linear-gradient(90deg,var(--purple-500),var(--purple-900));
    border-radius:4px;transition:width .3s ease;
  }
  .progress-text{
    font-size:12px;color:var(--muted);margin-top:8px;font-weight:600;
  }

  .content{padding:20px 36px 36px;}

  .notice{
    background:var(--green-bg);
    border:1px solid var(--green-border);
    color:var(--green-text);
    padding:12px 16px;
    border-radius:10px;
    font-size:14px;
    margin:0 0 22px;
  }

  .section-tag{
    display:inline-block;
    background:var(--purple-100);
    color:var(--purple-700);
    font-size:11.5px;
    font-weight:700;
    padding:5px 12px;
    border-radius:999px;
    margin-bottom:14px;
  }

  h1{font-size:24px;margin:4px 0 4px;color:var(--purple-900);}
  .sub{color:var(--muted);font-size:14px;margin:0 0 26px;}

  .field{margin-bottom:20px;}
  .field label{
    display:block;font-size:12.5px;font-weight:700;
    letter-spacing:.03em;color:#4b4560;margin-bottom:8px;
    text-transform:uppercase;
  }
  .field label .req{color:var(--orange-600);margin-left:2px;}
  .field .hint{font-size:12px;color:var(--muted);font-weight:500;margin-top:6px;text-transform:none;letter-spacing:normal;}

  input[type=text], input[type=tel], input[type=number], select, textarea{
    width:100%;
    padding:12px 14px;
    border-radius:10px;
    border:1px solid var(--border);
    background:#faf9fd;
    font-size:14.5px;
    color:var(--ink);
    outline:none;
    transition:border-color .15s, box-shadow .15s;
    font-family:inherit;
  }
  input:focus, select:focus, textarea:focus{
    border-color:var(--purple-500);
    box-shadow:0 0 0 3px rgba(124,58,237,0.15);
    background:#fff;
  }
  textarea{resize:vertical;min-height:70px;}

  .row2{display:grid;grid-template-columns:1fr 1fr;gap:18px;}
  .row3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:18px;}
  @media (max-width:640px){.row2,.row3{grid-template-columns:1fr;}}

  /* Custom radio / checkbox */
  .choice-group{display:flex;flex-direction:column;gap:10px;}
  .choice{
    display:flex;align-items:flex-start;gap:10px;
    padding:11px 14px;border:1px solid var(--border);
    border-radius:10px;background:#faf9fd;cursor:pointer;
    transition:border-color .15s, background .15s;
    font-size:14px;
  }
  .choice:hover{border-color:var(--purple-500);}
  .choice input{width:16px;height:16px;margin-top:2px;accent-color:var(--purple-700);flex-shrink:0;}
  .choice-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
  @media (max-width:640px){.choice-grid{grid-template-columns:1fr;}}

  .btn{
    border:none;
    padding:14px 24px;
    border-radius:12px;
    font-size:15px;
    font-weight:700;
    cursor:pointer;
    display:flex;align-items:center;justify-content:center;gap:8px;
    transition:filter .15s, transform .05s;
  }
  .btn:active{transform:translateY(1px);}
  .btn-primary{
    background:linear-gradient(90deg, var(--purple-500), var(--purple-900));
    color:#fff;flex:1;
  }
  .btn-primary:hover{filter:brightness(1.08);}
  .btn-ghost{
    background:#f1eefb;color:var(--purple-700);
  }
  .btn-ghost:hover{background:#e6e1f7;}

  .nav-row{display:flex;gap:14px;margin-top:8px;}

  .footer{
    text-align:center;font-size:12px;color:#a39cc0;
    margin-top:26px;padding-top:20px;border-top:1px solid var(--border);
  }

  .error{color:#b91c1c;font-size:12px;margin-top:6px;display:none;}
  .field.invalid input, .field.invalid select, .field.invalid textarea{border-color:#f87171;background:#fff5f5;}
  .field.invalid .error{display:block;}
  .field.invalid.choice-error .error{display:block;}

  .step-panel{display:none;}
  .step-panel.active{display:block;animation:fadeIn .25s ease;}
  @keyframes fadeIn{from{opacity:0;transform:translateY(6px);}to{opacity:1;transform:translateY(0);}}

  .done-screen{text-align:center;padding:30px 10px;}
  .done-screen .check{
    width:64px;height:64px;border-radius:50%;background:var(--green-bg);
    color:var(--green-text);font-size:30px;display:flex;align-items:center;justify-content:center;
    margin:0 auto 18px;border:1px solid var(--green-border);
  }
</style>
</head>
<body>

  <div class="container">
    <div class="header">
      <div class="logo-mark">PM</div>
      <div class="brand">Paradise <span>of Math</span></div>
    </div>

    <div class="progress-wrap">
      <div class="progress-track"><div class="progress-fill" id="progressFill" style="width:14.3%"></div></div>
      <div class="progress-text" id="progressText">Bagian 2 dari 7</div>
    </div>

    <div class="content">
      @if (session('success'))
        <div class="notice" style="background:#eafaf1; border-color:#bbf0d3; color:#166534;">
          {{ session('success') }}
        </div>
      @endif

      @if (session('error'))
        <div class="notice" style="background:#fef2f2; border-color:#fecaca; color:#991b1b;">
          {{ session('error') }}
        </div>
      @endif

      <form id="wizardForm" action="{{ route('siswa.biodata.submit') }}" method="POST" novalidate>
        @csrf

        <!-- BAGIAN 2 — DATA SISWA -->
        <section class="step-panel active" data-step="2">
          <span class="section-tag">Bagian 2 dari 7</span>
          <h1>Data Siswa</h1>
          <p class="sub">Isi data diri siswa dengan lengkap dan benar sesuai identitas resmi.</p>

          <div class="field" data-required="true">
            <label>Nama Lengkap Siswa<span class="req">*</span></label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', auth()->guard('siswa')->user()?->name) }}" placeholder="Contoh: Muhammad Iqbal Ramadhan">
            <div class="error">Nama lengkap wajib diisi.</div>
          </div>

          <div class="row2">
            <div class="field" data-required="true">
              <label>Nama Panggilan<span class="req">*</span></label>
              <input type="text" name="nama_panggilan" placeholder="Contoh: Iqbal">
              <div class="error">Nama panggilan wajib diisi.</div>
            </div>
            <div class="field" data-required="true">
              <label>No. HP Siswa<span class="req">*</span></label>
              <input type="tel" name="no_hp" placeholder="08xx-xxxx-xxxx">
              <div class="error">Nomor HP wajib diisi.</div>
            </div>
          </div>

          <div class="row2">
            <div class="field" data-required="true">
              <label>Tempat Lahir<span class="req">*</span></label>
              <input type="text" name="tempat_lahir" placeholder="Contoh: Pekalongan">
              <div class="error">Tempat lahir wajib diisi.</div>
            </div>
            <div class="field" data-required="true">
              <label>Tanggal Lahir<span class="req">*</span></label>
              <input type="date" name="tanggal_lahir">
              <div class="error">Tanggal lahir wajib diisi.</div>
            </div>
          </div>

          <div class="row2">
            <div class="field" data-required="true">
              <label>Kelas<span class="req">*</span></label>
              <select name="kelas">
                <option value="">Pilih kelas</option>
                <optgroup label="SD">
                  <option>Kelas 1 SD</option><option>Kelas 2 SD</option><option>Kelas 3 SD</option>
                  <option>Kelas 4 SD</option><option>Kelas 5 SD</option><option>Kelas 6 SD</option>
                </optgroup>
                <optgroup label="SMP">
                  <option>Kelas 7 SMP</option><option>Kelas 8 SMP</option><option>Kelas 9 SMP</option>
                </optgroup>
                <optgroup label="SMA">
                  <option>Kelas 10 SMA</option><option>Kelas 11 SMA</option><option>Kelas 12 SMA</option>
                </optgroup>
              </select>
              <div class="error">Kelas wajib dipilih.</div>
            </div>
            <div class="field" data-required="true">
              <label>Sekolah<span class="req">*</span></label>
              <input type="text" name="sekolah" placeholder="Nama sekolah asal">
              <div class="error">Nama sekolah wajib diisi.</div>
            </div>
          </div>

          <div class="field" data-required="false">
            <label>Jurusan (Untuk SMA)</label>
            <select name="jurusan">
              <option value="">— Tidak berlaku / pilih jurusan —</option>
              <option>IPA</option><option>IPS</option><option>Bahasa</option>
            </select>
            <div class="hint">Kosongkan jika belum SMA.</div>
          </div>

          <div class="field" data-required="true">
            <label>Alamat Rumah<span class="req">*</span></label>
            <textarea name="alamat" placeholder="Alamat lengkap tempat tinggal"></textarea>
            <div class="error">Alamat rumah wajib diisi.</div>
          </div>

          <div class="field" data-required="true">
            <label>Nama Akun Sosmed (Instagram &amp; TikTok)<span class="req">*</span></label>
            <input type="text" name="sosmed_umum" placeholder="Contoh: IG @nama_siswa · TikTok @nama_siswa">
            <div class="error">Nama akun sosmed wajib diisi.</div>
          </div>
        </section>

        <!-- BAGIAN 3 — MINAT & SUMBER INFO -->
        <section class="step-panel" data-step="3">
          <span class="section-tag">Bagian 3 dari 7</span>
          <h1>Minat Belajar &amp; Sumber Informasi</h1>
          <p class="sub">Ceritakan bagaimana kamu mengenal PM dan pelajaran apa yang ingin di les kan.</p>

          <div class="field" data-required="true">
            <label>Mengetahui info PM dari mana?<span class="req">*</span></label>
            <div class="choice-group">
              <label class="choice"><input type="radio" name="sumber_info" value="Sosial media (TikTok, Instagram, dll)"><span>Sosial media (TikTok, Instagram, dll)</span></label>
              <label class="choice"><input type="radio" name="sumber_info" value="Ajakan teman yang sudah join di PM"><span>Ajakan teman yang sudah join di PM (isikan nama temannya di kolom bawah)</span></label>
              <label class="choice"><input type="radio" name="sumber_info" value="Yang lain"><span>Yang lain</span></label>
            </div>
            <div class="error">Silakan pilih salah satu sumber info.</div>
          </div>

          <div class="field" data-required="false">
            <label>Nama Teman / Keterangan Lainnya</label>
            <input type="text" name="sumber_info_detail" placeholder="Isi jika memilih ajakan teman / yang lain">
          </div>

          <div class="row3">
            <div class="field" data-required="false">
              <label>Instagram</label>
              <input type="text" name="ig_siswa" placeholder="@username">
            </div>
            <div class="field" data-required="false">
              <label>TikTok</label>
              <input type="text" name="tiktok_siswa" placeholder="@username">
            </div>
            <div class="field" data-required="false">
              <label>Facebook</label>
              <input type="text" name="fb_siswa" placeholder="Nama akun Facebook">
            </div>
          </div>

          <div class="field" data-required="true">
            <label>Mata Pelajaran &amp; Jumlah Minimal Shift/Minggu<span class="req">*</span></label>
            <div class="hint" style="margin-bottom:10px;">Pilih semua pelajaran yang ingin di les kan di PM.</div>
            <div class="choice-grid">
              <label class="choice"><input type="checkbox" name="mapel" value="Matematika Wajib 2x"><span>Matematika Wajib 2x</span></label>
              <label class="choice"><input type="checkbox" name="mapel" value="Matematika Lanjut 3x"><span>Matematika Lanjut 3x</span></label>
              <label class="choice"><input type="checkbox" name="mapel" value="Matematika Wajib + Lanjut 4x"><span>Matematika Wajib + Matematika Lanjut 4x</span></label>
              <label class="choice"><input type="checkbox" name="mapel" value="Fisika 2x"><span>Fisika 2x</span></label>
              <label class="choice"><input type="checkbox" name="mapel" value="Kimia 2x"><span>Kimia 2x</span></label>
              <label class="choice"><input type="checkbox" name="mapel" value="Biologi 2x"><span>Biologi 2x</span></label>
              <label class="choice"><input type="checkbox" name="mapel" value="Bahasa Inggris 2x"><span>Bahasa Inggris 2x</span></label>
              <label class="choice"><input type="checkbox" name="mapel" value="Bahasa Indonesia 1x"><span>Bahasa Indonesia 1x</span></label>
              <label class="choice"><input type="checkbox" name="mapel" value="Bahasa Indonesia 2x"><span>Bahasa Indonesia 2x</span></label>
              <label class="choice"><input type="checkbox" name="mapel" value="Sejarah 1x"><span>Sejarah 1x</span></label>
              <label class="choice"><input type="checkbox" name="mapel" value="Sejarah 2x"><span>Sejarah 2x</span></label>
              <label class="choice"><input type="checkbox" name="mapel" value="Matematika TKA 2x"><span>Matematika TKA 2x</span></label>
              <label class="choice"><input type="checkbox" name="mapel" value="Bahasa Indonesia TKA 2x"><span>Bahasa Indonesia TKA 2x</span></label>
              <label class="choice"><input type="checkbox" name="mapel" value="Bahasa Inggris TKA 2x"><span>Bahasa Inggris TKA 2x</span></label>
            </div>
            <div class="error">Pilih minimal satu mata pelajaran.</div>
          </div>

          <div class="field" data-required="true">
            <label>Nilai UN / Rapor Terakhir Sesuai Pelajaran yang Akan Di Les kan<span class="req">*</span></label>
            <textarea name="nilai_terakhir" placeholder="Contoh: Matematika 85, Fisika 78, ..."></textarea>
            <div class="error">Kolom ini wajib diisi.</div>
          </div>

          <div class="field" data-required="false">
            <label>Pilihan Guru Matematika</label>
            <div class="choice-group">
              <label class="choice"><input type="radio" name="pilihan_guru" value="Kak Ika (Master)"><span>Kak Ika (Master)</span></label>
              <label class="choice"><input type="radio" name="pilihan_guru" value="Kak Angel (Co Master)"><span>Kak Angel (Co Master)</span></label>
              <label class="choice"><input type="radio" name="pilihan_guru" value="Kak Sofia (Co Master)"><span>Kak Sofia (Co Master)</span></label>
              <label class="choice"><input type="radio" name="pilihan_guru" value="Keyawan"><span>Keyawan</span></label>
            </div>
          </div>
        </section>

        <!-- BAGIAN 4 — JADWAL PULANG SEKOLAH -->
        <section class="step-panel" data-step="4">
          <span class="section-tag">Bagian 4 dari 7</span>
          <h1>Jadwal Pulang Sekolah</h1>
          <p class="sub">Isi jam pulang sekolah siswa untuk setiap hari.</p>

          <div class="row2">
            <div class="field" data-required="true">
              <label>Senin<span class="req">*</span></label>
              <input type="text" name="pulang_senin" placeholder="Contoh: 15.00">
              <div class="error">Jam pulang Senin wajib diisi.</div>
            </div>
            <div class="field" data-required="true">
              <label>Selasa<span class="req">*</span></label>
              <input type="text" name="pulang_selasa" placeholder="Contoh: 15.00">
              <div class="error">Jam pulang Selasa wajib diisi.</div>
            </div>
          </div>
          <div class="row2">
            <div class="field" data-required="true">
              <label>Rabu<span class="req">*</span></label>
              <input type="text" name="pulang_rabu" placeholder="Contoh: 15.00">
              <div class="error">Jam pulang Rabu wajib diisi.</div>
            </div>
            <div class="field" data-required="true">
              <label>Kamis<span class="req">*</span></label>
              <input type="text" name="pulang_kamis" placeholder="Contoh: 15.00">
              <div class="error">Jam pulang Kamis wajib diisi.</div>
            </div>
          </div>
          <div class="row2">
            <div class="field" data-required="true">
              <label>Jumat<span class="req">*</span></label>
              <input type="text" name="pulang_jumat" placeholder="Contoh: 11.00">
              <div class="error">Jam pulang Jumat wajib diisi.</div>
            </div>
            <div class="field" data-required="true">
              <label>Sabtu<span class="req">*</span></label>
              <input type="text" name="pulang_sabtu" placeholder="Contoh: 12.00 / Libur">
              <div class="error">Jam pulang Sabtu wajib diisi.</div>
            </div>
          </div>
        </section>

        <!-- BAGIAN 5 — KEGIATAN RUTIN -->
        <section class="step-panel" data-step="5">
          <span class="section-tag">Bagian 5 dari 7</span>
          <h1>Kegiatan Rutin Selain Jadwal Sekolah</h1>
          <p class="sub">Contoh: les lain, ekstrakurikuler, ngaji, olahraga, dll.</p>

          <div class="field" data-required="true">
            <label>Hari Apa dari Jam Berapa Sampai Jam Berapa?<span class="req">*</span></label>
            <textarea name="kegiatan_rutin" placeholder="Contoh: Selasa & Kamis les renang 16.00-17.30, Sabtu ngaji 08.00-10.00" style="min-height:110px;"></textarea>
            <div class="error">Kolom ini wajib diisi. Isi 'Tidak ada' jika tidak ada kegiatan rutin.</div>
          </div>
        </section>

        <!-- BAGIAN 6 — DATA IBU -->
        <section class="step-panel" data-step="6">
          <span class="section-tag">Bagian 6 dari 7</span>
          <h1>Data Ibu</h1>
          <p class="sub">Isi data diri ibu kandung / wali siswa.</p>

          <div class="field" data-required="true">
            <label>Nama Lengkap Ibu<span class="req">*</span></label>
            <input type="text" name="ibu_nama_lengkap" placeholder="Nama lengkap ibu">
            <div class="error">Nama lengkap ibu wajib diisi.</div>
          </div>
          <div class="row2">
            <div class="field" data-required="true">
              <label>Nama Panggilan Ibu<span class="req">*</span></label>
              <input type="text" name="ibu_nama_panggilan" placeholder="Nama panggilan ibu">
              <div class="error">Nama panggilan ibu wajib diisi.</div>
            </div>
            <div class="field" data-required="true">
              <label>No. HP Ibu<span class="req">*</span></label>
              <input type="tel" name="ibu_no_hp" placeholder="08xx-xxxx-xxxx">
              <div class="error">No. HP ibu wajib diisi.</div>
            </div>
          </div>
          <div class="row2">
            <div class="field" data-required="true">
              <label>Umur Ibu<span class="req">*</span></label>
              <input type="number" name="ibu_umur" placeholder="Contoh: 42" min="15" max="90">
              <div class="error">Umur ibu wajib diisi.</div>
            </div>
            <div class="field" data-required="true">
              <label>Pekerjaan Ibu<span class="req">*</span></label>
              <input type="text" name="ibu_pekerjaan" placeholder="Contoh: Ibu Rumah Tangga / PNS">
              <div class="error">Pekerjaan ibu wajib diisi.</div>
            </div>
          </div>
          <div class="field" data-required="false">
            <label>Akun Instagram Ibu</label>
            <input type="text" name="ibu_instagram" placeholder="@username (opsional)">
          </div>
        </section>

        <!-- BAGIAN 7 — DATA AYAH -->
        <section class="step-panel" data-step="7">
          <span class="section-tag">Bagian 7 dari 7</span>
          <h1>Data Ayah</h1>
          <p class="sub">Isi data diri ayah kandung / wali siswa.</p>

          <div class="field" data-required="true">
            <label>Nama Lengkap Ayah<span class="req">*</span></label>
            <input type="text" name="ayah_nama_lengkap" placeholder="Nama lengkap ayah">
            <div class="error">Nama lengkap ayah wajib diisi.</div>
          </div>
          <div class="row2">
            <div class="field" data-required="true">
              <label>Nama Panggilan Ayah<span class="req">*</span></label>
              <input type="text" name="ayah_nama_panggilan" placeholder="Nama panggilan ayah">
              <div class="error">Nama panggilan ayah wajib diisi.</div>
            </div>
            <div class="field" data-required="true">
              <label>No. HP Ayah<span class="req">*</span></label>
              <input type="tel" name="ayah_no_hp" placeholder="08xx-xxxx-xxxx">
              <div class="error">No. HP ayah wajib diisi.</div>
            </div>
          </div>
          <div class="row2">
            <div class="field" data-required="true">
              <label>Umur Ayah<span class="req">*</span></label>
              <input type="number" name="ayah_umur" placeholder="Contoh: 45" min="15" max="95">
              <div class="error">Umur ayah wajib diisi.</div>
            </div>
            <div class="field" data-required="true">
              <label>Pekerjaan Ayah<span class="req">*</span></label>
              <input type="text" name="ayah_pekerjaan" placeholder="Contoh: Wiraswasta / Karyawan Swasta">
              <div class="error">Pekerjaan ayah wajib diisi.</div>
            </div>
          </div>
          <div class="field" data-required="false">
            <label>Akun Instagram Ayah</label>
            <input type="text" name="ayah_instagram" placeholder="@username (opsional)">
          </div>
        </section>

        <!-- SELESAI -->
        <section class="step-panel" data-step="done">
          <div class="done-screen">
            <div class="check">✓</div>
            <h1>Formulir Berhasil Dikirim</h1>
            <p class="sub">Terima kasih! Data pendaftaran akan segera diproses oleh tim Paradise of Math.</p>
          </div>
        </section>

        <div class="nav-row" id="navRow">
          <button type="button" class="btn btn-ghost" id="btnBack">← Kembali</button>
          <button type="button" class="btn btn-primary" id="btnNext">Lanjut →</button>
        </div>
      </form>

      <div class="footer">© 2026 · Paradise of Math — Sistem Manajemen Registrasi Siswa</div>
    </div>
  </div>

<script>
  const steps = ['2','3','4','5','6','7','done'];
  let current = 0;
  const totalBagian = 7;

  const panels = document.querySelectorAll('.step-panel');
  const btnBack = document.getElementById('btnBack');
  const btnNext = document.getElementById('btnNext');
  const progressFill = document.getElementById('progressFill');
  const progressText = document.getElementById('progressText');
  const navRow = document.getElementById('navRow');
  const form = document.getElementById('wizardForm');

  function renderStep(){
    panels.forEach(p => p.classList.toggle('active', p.dataset.step === steps[current]));

    const isDone = steps[current] === 'done';
    btnBack.style.display = current === 0 ? 'none' : 'inline-flex';
    btnNext.textContent = (current === steps.length - 2) ? 'Kirim Formulir ✓' : 'Lanjut →';
    navRow.style.display = isDone ? 'none' : 'flex';

    const bagianNumber = isDone ? totalBagian : parseInt(steps[current]);
    progressFill.style.width = ((bagianNumber) / totalBagian * 100) + '%';
    progressText.textContent = isDone ? 'Selesai — 7 dari 7 bagian' : `Bagian ${bagianNumber} dari ${totalBagian}`;

    window.scrollTo({top:0, behavior:'smooth'});
  }

  function validateCurrentStep(){
    const panel = document.querySelector(`.step-panel[data-step="${steps[current]}"]`);
    let valid = true;

    panel.querySelectorAll('.field[data-required="true"]').forEach(field=>{
      const radios = field.querySelectorAll('input[type=radio]');
      const checks = field.querySelectorAll('input[type=checkbox]');
      let filled = true;

      if(radios.length){
        filled = Array.from(radios).some(r=>r.checked);
      } else if(checks.length){
        filled = Array.from(checks).some(c=>c.checked);
      } else {
        const input = field.querySelector('input, select, textarea');
        filled = !!(input && input.value.trim());
      }

      if(!filled){
        field.classList.add('invalid');
        valid = false;
      } else {
        field.classList.remove('invalid');
      }
    });

    if(!valid){
      const firstInvalid = panel.querySelector('.field.invalid');
      if(firstInvalid) firstInvalid.scrollIntoView({behavior:'smooth', block:'center'});
    }
    return valid;
  }

  btnNext.addEventListener('click', ()=>{
    if(steps[current] === 'done') return;
    if(!validateCurrentStep()) return;

    if(current === steps.length - 2){
      // last real step before 'done' -> submit form to proceed to regisKategory
      form.submit();
    } else {
      current++;
      renderStep();
    }
  });

  btnBack.addEventListener('click', ()=>{
    if(current > 0){
      current--;
      renderStep();
    }
  });

  // clear invalid state as user fixes fields
  form.addEventListener('input', (e)=>{
    const field = e.target.closest('.field');
    if(!field || field.dataset.required !== 'true') return;

    const radios = field.querySelectorAll('input[type=radio]');
    const checks = field.querySelectorAll('input[type=checkbox]');
    let filled;
    if(radios.length) filled = Array.from(radios).some(r=>r.checked);
    else if(checks.length) filled = Array.from(checks).some(c=>c.checked);
    else filled = e.target.value.trim().length > 0;

    if(filled) field.classList.remove('invalid');
  });

  form.addEventListener('change', (e)=>{
    if(e.target.type === 'radio' || e.target.type === 'checkbox'){
      const field = e.target.closest('.field');
      if(field) field.classList.remove('invalid');
    }
  });

  renderStep();
</script>
</body>
</html>