@extends('layout.app')

@section('title', 'Data Akademik Saya · Paradise of Math')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-purple-950">Data Akademik Saya</h1>
                <p class="text-sm text-muted mb-0">Lihat dan tinjau rincian biodata registrasi bimbingan belajar Anda.</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right text-sm">
                    <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}" class="text-purple-600">Home</a></li>
                    <li class="breadcrumb-item active">Data Akademik</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('siswa.dashboard') }}" class="btn btn-sm btn-light border rounded-lg font-weight-bold text-purple-950 px-3">
                <i class="fas fa-arrow-left mr-1.5"></i> Kembali ke Dashboard
            </a>
        </div>

        @php
            $bio = $siswa->biodata ?? [];
            $namaPanggilan = $bio['nama_panggilan'] ?? '-';
            $noTelp = $bio['no_telp'] ?? '-';
            $jenisKelamin = $bio['jenis_kelamin'] ?? '-';
            $tempatLahir = $bio['tempat_lahir'] ?? '-';
            $tanggalLahir = isset($bio['tanggal_lahir']) && $bio['tanggal_lahir'] ? date('d-m-Y', strtotime($bio['tanggal_lahir'])) : '-';
            $kelas = $bio['kelas'] ?? '-';
            $jurusan = $bio['jurusan'] ?? '-';
            $alamat = $bio['alamat'] ?? '-';
            $sosmedUmum = $bio['sosmed_umum'] ?? '-';
            $sumberInfo = $bio['sumber_info'] ?? '-';
            $sumberInfoDetail = $bio['sumber_info_detail'] ?? '-';
            $igSiswa = $bio['ig_siswa'] ?? '-';
            $tiktokSiswa = $bio['tiktok_siswa'] ?? '-';
            $fbSiswa = $bio['fb_siswa'] ?? '-';
            $nilaiTerakhir = $bio['nilai_terakhir'] ?? '-';

            $pulangSenin = $bio['pulang_senin'] ?? '-';
            $pulangSelasa = $bio['pulang_selasa'] ?? '-';
            $pulangRabu = $bio['pulang_rabu'] ?? '-';
            $pulangKamis = $bio['pulang_kamis'] ?? '-';
            $pulangJumat = $bio['pulang_jumat'] ?? '-';
            $pulangSabtu = $bio['pulang_sabtu'] ?? '-';
            $kegiatanRutin = $bio['kegiatan_rutin'] ?? '-';

            $ibuNamaLengkap = $bio['ibu_nama_lengkap'] ?? '-';
            $ibuNamaPanggilan = $bio['ibu_nama_panggilan'] ?? '-';
            $ibuNoHp = $bio['ibu_no_hp'] ?? '-';
            $ibuUmur = isset($bio['ibu_umur']) && $bio['ibu_umur'] ? $bio['ibu_umur'] . ' Tahun' : '-';
            $ibuPekerjaan = $bio['ibu_pekerjaan'] ?? '-';
            $ibuInstagram = $bio['ibu_instagram'] ?? '-';

            $ayahNamaLengkap = $bio['ayah_nama_lengkap'] ?? '-';
            $ayahNamaPanggilan = $bio['ayah_nama_panggilan'] ?? '-';
            $ayahNoHp = $bio['ayah_no_hp'] ?? '-';
            $ayahUmur = isset($bio['ayah_umur']) && $bio['ayah_umur'] ? $bio['ayah_umur'] . ' Tahun' : '-';
            $ayahPekerjaan = $bio['ayah_pekerjaan'] ?? '-';
            $ayahInstagram = $bio['ayah_instagram'] ?? '-';
        @endphp

        <div class="row">
            <!-- Left Card: Profile Overview & Contact -->
            <div class="col-lg-4 col-12 mb-4">
                <div class="card border-0 shadow-sm rounded-2xl overflow-hidden mb-4">
                    <div class="text-center py-5 px-3 bg-purple-950 text-white position-relative" style="background-color: #2e1065;">
                        <div class="avatar-container mx-auto mb-3 bg-white text-purple-900 rounded-circle d-flex align-items-center justify-content-center shadow-lg" style="width: 80px; height: 80px;">
                            <i class="fas fa-user-graduate fa-3x"></i>
                        </div>
                        <h5 class="font-weight-bold text-white mb-1">{{ $siswa->name }}</h5>
                        <p class="text-xxs font-mono text-purple-200 mb-0">{{ $siswa->email }}</p>
                        <span class="badge bg-purple-900 text-purple-200 px-3 py-1.5 rounded-pill text-xxs font-bold uppercase mt-3" style="border: 1px solid rgba(216, 211, 232, 0.2);">{{ $siswa->sekolah ?? 'Paradise Student' }}</span>
                    </div>
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-purple-950 uppercase text-xs tracking-wider mb-3 pb-2 border-bottom"><i class="fas fa-address-book mr-1.5 text-purple-600"></i> Kontak &amp; Sosial Media</h6>
                        
                        <div class="mb-3">
                            <span class="text-xxs text-muted d-block">Nomor WhatsApp</span>
                            <span class="font-weight-semibold text-slate-800 text-xs d-block"><i class="fab fa-whatsapp text-emerald-500 mr-1"></i> {{ $siswa->whatsapp ?? '-' }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-xxs text-muted d-block">No. Telepon Rumah</span>
                            <span class="font-weight-semibold text-slate-800 text-xs d-block"><i class="fas fa-phone-alt text-slate-400 mr-1"></i> {{ $noTelp }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-xxs text-muted d-block">Instagram Pribadi</span>
                            <span class="font-weight-semibold text-slate-800 text-xs d-block"><i class="fab fa-instagram text-rose-500 mr-1"></i> {{ $igSiswa !== '-' ? '@'.ltrim($igSiswa, '@') : '-' }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-xxs text-muted d-block">TikTok</span>
                            <span class="font-weight-semibold text-slate-800 text-xs d-block"><i class="fab fa-tiktok text-dark mr-1"></i> {{ $tiktokSiswa !== '-' ? '@'.ltrim($tiktokSiswa, '@') : '-' }}</span>
                        </div>
                        <div class="mb-0">
                            <span class="text-xxs text-muted d-block">Facebook</span>
                            <span class="font-weight-semibold text-slate-800 text-xs d-block"><i class="fab fa-facebook text-primary mr-1"></i> {{ $fbSiswa }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Academic Details, Schedule, Parents (8 cols) -->
            <div class="col-lg-8 col-12">
                <!-- Part 1: Biodata & Minat Belajar -->
                <div class="card border-0 shadow-sm rounded-2xl overflow-hidden mb-4">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-purple-950 uppercase text-xs tracking-wider mb-3 pb-2 border-bottom text-purple-700"><i class="fas fa-user-circle mr-1.5"></i> 1. Profil &amp; Minat Belajar</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <span class="text-xxs text-muted d-block">Nama Lengkap</span>
                                <span class="font-weight-semibold text-slate-800 text-xs">{{ $siswa->name }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="text-xxs text-muted d-block">Nama Panggilan</span>
                                <span class="font-weight-bold text-purple-950 text-xs">{{ $namaPanggilan }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="text-xxs text-muted d-block">Tempat &amp; Tanggal Lahir</span>
                                <span class="font-weight-semibold text-slate-800 text-xs">{{ $tempatLahir }}, {{ $tanggalLahir }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="text-xxs text-muted d-block">Jenis Kelamin</span>
                                <span class="font-weight-semibold text-slate-800 text-xs">{{ $jenisKelamin }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="text-xxs text-muted d-block">Tingkat Kelas &amp; Jurusan</span>
                                <span class="font-weight-semibold text-slate-800 text-xs">{{ $kelas }} @if($jurusan && $jurusan !== '— Tidak berlaku / pilih jurusan —') ({{ $jurusan }}) @endif</span>
                            </div>
                            <div class="col-12 mb-3">
                                <span class="text-xxs text-muted d-block">Alamat Rumah</span>
                                <span class="font-weight-semibold text-slate-800 text-xs">{{ $alamat }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="text-xxs text-muted d-block">Nilai UN / Rapor Terakhir Terkait</span>
                                <span class="font-weight-bold text-purple-950 text-xs">{{ $nilaiTerakhir }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="text-xxs text-muted d-block">Sumber Informasi PM</span>
                                <span class="font-weight-semibold text-slate-800 text-xs">{{ $sumberInfo }} @if($sumberInfoDetail) ({{ $sumberInfoDetail }}) @endif</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Part 2: Jadwal Sekolah & Rutinitas -->
                <div class="card border-0 shadow-sm rounded-2xl overflow-hidden mb-4">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-purple-950 uppercase text-xs tracking-wider mb-3 pb-2 border-bottom text-purple-700"><i class="fas fa-calendar-alt mr-1.5"></i> 2. Jadwal Sekolah &amp; Kegiatan Rutin</h6>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <span class="text-xxs text-muted d-block mb-2 font-weight-bold">Jam Pulang Sekolah</span>
                                <div class="row row-cols-3 row-cols-md-6 g-2">
                                    <div class="col mb-2">
                                        <div class="p-2 text-center rounded" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                            <span class="d-block text-[9px] text-muted">Senin</span>
                                            <strong class="text-purple-950 text-xs">{{ $pulangSenin }}</strong>
                                        </div>
                                    </div>
                                    <div class="col mb-2">
                                        <div class="p-2 text-center rounded" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                            <span class="d-block text-[9px] text-muted">Selasa</span>
                                            <strong class="text-purple-950 text-xs">{{ $pulangSelasa }}</strong>
                                        </div>
                                    </div>
                                    <div class="col mb-2">
                                        <div class="p-2 text-center rounded" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                            <span class="d-block text-[9px] text-muted">Rabu</span>
                                            <strong class="text-purple-950 text-xs">{{ $pulangRabu }}</strong>
                                        </div>
                                    </div>
                                    <div class="col mb-2">
                                        <div class="p-2 text-center rounded" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                            <span class="d-block text-[9px] text-muted">Kamis</span>
                                            <strong class="text-purple-950 text-xs">{{ $pulangKamis }}</strong>
                                        </div>
                                    </div>
                                    <div class="col mb-2">
                                        <div class="p-2 text-center rounded" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                            <span class="d-block text-[9px] text-muted">Jumat</span>
                                            <strong class="text-purple-950 text-xs">{{ $pulangJumat }}</strong>
                                        </div>
                                    </div>
                                    <div class="col mb-2">
                                        <div class="p-2 text-center rounded" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                            <span class="d-block text-[9px] text-muted">Sabtu</span>
                                            <strong class="text-purple-950 text-xs">{{ $pulangSabtu }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-0">
                                <span class="text-xxs text-muted d-block">Kegiatan Rutin Selain Sekolah</span>
                                <span class="font-weight-semibold text-slate-800 text-xs">{{ $kegiatanRutin }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Part 3: Orang Tua (Wali) -->
                <div class="card border-0 shadow-sm rounded-2xl overflow-hidden mb-4">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-purple-950 uppercase text-xs tracking-wider mb-3 pb-2 border-bottom text-purple-700"><i class="fas fa-user-friends mr-1.5"></i> 3. Orang Tua / Wali</h6>
                        <div class="row">
                            <!-- Data Ibu -->
                            <div class="col-md-6 mb-4 mb-md-0 pr-md-4" style="border-right: 1px solid #f1f5f9;">
                                <span class="badge bg-purple-50 text-purple-700 font-bold mb-3 text-[10px] px-2 py-0.5" style="border: 1px solid #e9d5ff;">DATA IBU</span>
                                <div class="mb-2.5">
                                    <span class="text-[10px] text-muted d-block">Nama Lengkap</span>
                                    <strong class="text-slate-800 text-xs">{{ $ibuNamaLengkap }} ({{ $ibuNamaPanggilan }})</strong>
                                </div>
                                <div class="mb-2.5">
                                    <span class="text-[10px] text-muted d-block">No. HP Ibu</span>
                                    <span class="text-slate-700 text-xs"><i class="fab fa-whatsapp text-emerald-500 mr-1"></i> {{ $ibuNoHp }}</span>
                                </div>
                                <div class="mb-2.5">
                                    <span class="text-[10px] text-muted d-block">Umur / Pekerjaan</span>
                                    <span class="text-slate-700 text-xs">{{ $ibuUmur }} / {{ $ibuPekerjaan }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-muted d-block">Instagram Ibu</span>
                                    <span class="text-slate-700 text-xs font-mono">{{ $ibuInstagram }}</span>
                                </div>
                            </div>

                            <!-- Data Ayah -->
                            <div class="col-md-6 pl-md-4">
                                <span class="badge bg-purple-50 text-purple-700 font-bold mb-3 text-[10px] px-2 py-0.5" style="border: 1px solid #e9d5ff;">DATA AYAH</span>
                                <div class="mb-2.5">
                                    <span class="text-[10px] text-muted d-block">Nama Lengkap</span>
                                    <strong class="text-slate-800 text-xs">{{ $ayahNamaLengkap }} ({{ $ayahNamaPanggilan }})</strong>
                                </div>
                                <div class="mb-2.5">
                                    <span class="text-[10px] text-muted d-block">No. HP Ayah</span>
                                    <span class="text-slate-700 text-xs"><i class="fab fa-whatsapp text-emerald-500 mr-1"></i> {{ $ayahNoHp }}</span>
                                </div>
                                <div class="mb-2.5">
                                    <span class="text-[10px] text-muted d-block">Umur / Pekerjaan</span>
                                    <span class="text-slate-700 text-xs">{{ $ayahUmur }} / {{ $ayahPekerjaan }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-muted d-block">Instagram Ayah</span>
                                    <span class="text-slate-700 text-xs font-mono">{{ $ayahInstagram }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<style>
    .rounded-2xl {
        border-radius: 16px !important;
    }
</style>
@endsection
