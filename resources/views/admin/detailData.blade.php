@extends('layout.app')

@section('title', 'Detail Registrasi Siswa · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Detail Registrasi &amp; Pembayaran</h1>
                    <p class="text-sm text-muted mb-0">Ulas data pendaftaran siswa, verifikasi bukti pembayaran, dan aktivasi akun belajar.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.siswa.approve.index') }}" class="text-purple-600">Persetujuan Siswa</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('admin.siswa.approve.index') }}" class="btn btn-sm btn-light border rounded-lg font-weight-bold text-purple-950 px-3">
                    <i class="fas fa-arrow-left mr-1.5"></i> Kembali ke Daftar Persetujuan
                </a>
            </div>

            <!-- Alert success -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert" style="background-color: #d1fae5; border-color: #a7f3d0; color: #065f46;">
                    <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #065f46;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @php
                $bio = $student->biodata ?? [];
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

                $hariPilihan = $bio['hari_pertemuan'] ?? [];

                // Fallback baru: gabungkan hari dari semua mapel (hari_per_mapel)
                if (empty($hariPilihan) && !empty($bio['hari_per_mapel']) && is_array($bio['hari_per_mapel'])) {
                    foreach ($bio['hari_per_mapel'] as $hariArr) {
                        if (is_array($hariArr)) {
                            foreach ($hariArr as $hari) {
                                if ($hari) $hariPilihan[] = $hari;
                            }
                        }
                    }
                    $hariPilihan = array_values(array_unique($hariPilihan));
                }

                if (empty($hariPilihan) && $student->tipe_paket) {
                    if (preg_match('/Hari:\s*([^)|]+)/i', $student->tipe_paket, $matches)) {
                        $hariPilihan = array_map('trim', explode(',', $matches[1]));
                    }
                }
                $hariPilihanLower = array_map('strtolower', $hariPilihan);
            @endphp

            @if(empty($student->biodata))
                <div class="alert alert-warning alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert" style="background-color: #fffbeb; border-color: #fef3c7; color: #b45309;">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Data Registrasi Belum Lengkap</h5>
                    Siswa ini belum mengisi formulir biodata lengkap. Silakan minta siswa untuk melengkapi datanya terlebih dahulu di dashboard siswa.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #b45309;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <!-- LEFT COLUMN: Profile Brief, Receipt & Approval (4 cols) -->
                <div class="col-lg-4 mb-4">
                    <!-- Profile Card -->
                    <div class="card shadow-sm border-light rounded-2xl overflow-hidden mb-4">
                        <div class="card-header text-white py-3 d-flex align-items-center justify-content-between" style="background-color: #2e1065;">
                            <span class="font-weight-bold mb-0 text-white" style="font-size: 0.95rem;">Informasi Akun</span>
                            <span class="badge {{ $student->status === 'active' ? 'bg-emerald-500' : 'bg-amber-500' }} text-white text-xxs font-bold uppercase px-2 py-0.5">
                                {{ $student->status === 'active' ? 'Aktif' : 'Menunggu' }}
                            </span>
                        </div>
                        <div class="card-body p-3">
                            <table class="table table-borderless table-sm mb-0 align-middle text-sm">
                                <tbody>
                                    <tr class="border-bottom border-light">
                                        <td class="text-muted py-2" style="width: 35%;">Nama Akun</td>
                                        <td class="font-weight-bold text-purple-950 py-2">{{ $student->name }}</td>
                                    </tr>
                                    <tr class="border-bottom border-light">
                                        <td class="text-muted py-2">Email</td>
                                        <td class="text-slate-700 py-2 font-mono text-xs">{{ $student->email }}</td>
                                    </tr>
                                    <tr class="border-bottom border-light">
                                        <td class="text-muted py-2">WhatsApp</td>
                                        <td class="text-slate-700 py-2">{{ $student->whatsapp ?? '-' }}</td>
                                    </tr>
                                    <tr class="border-bottom border-light">
                                        <td class="text-muted py-2">Sekolah</td>
                                        <td class="text-slate-700 py-2">{{ $student->sekolah ?? '-' }}</td>
                                    </tr>
                                    @if ($paket)
                                        <tr class="border-bottom border-light">
                                            <td class="text-muted py-2">Kategori</td>
                                            <td class="py-2"><span class="badge bg-purple-100 text-purple-800 font-bold uppercase text-[9px] px-2 py-0.5">{{ $paket->kategori }}</span></td>
                                        </tr>
                                        <tr class="border-bottom border-light">
                                            <td class="text-muted py-2">Paket Bimbel</td>
                                            <td class="font-weight-bold text-purple-950 py-2">{{ $paket->nama_paket }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted py-2">Detail Pilihan</td>
                                            <td class="text-purple-900 font-weight-bold py-2 text-xs">{{ $student->tipe_paket ?? '-' }}</td>
                                        </tr>
                                        <tr class="border-top border-light">
                                            <td class="text-muted py-2">Jam Bimbel</td>
                                            <td class="py-2">
                                                <div class="d-flex align-items-start justify-content-between">
                                                    <div class="text-xs mr-1">
                                                        @php
                                                            $jamPerMapel = $bio['jam_per_mapel'] ?? [];
                                                            $mapelForJam = $bio['mapel_jadwal'] ?? [];
                                                            if (empty($mapelForJam) && $student->tipe_paket) {
                                                                if (preg_match('/Mapel:\s*([^)|]+)/i', $student->tipe_paket, $matches)) {
                                                                    $mapelForJam = array_map('trim', explode(',', $matches[1]));
                                                                }
                                                            }
                                                        @endphp
                                                        @if(!empty($mapelForJam))
                                                            @foreach($mapelForJam as $idx => $namaMapelJam)
                                                                @php
                                                                    $jamMulai   = $jamPerMapel[$idx]['jam_mulai']   ?? '-';
                                                                    $jamSelesai = $jamPerMapel[$idx]['jam_selesai'] ?? '-';
                                                                @endphp
                                                                <div class="mb-1 d-flex align-items-center gap-1">
                                                                    <span class="badge bg-purple-100 text-purple-800 text-[10px] font-bold px-1.5 py-0.5 rounded">{{ $namaMapelJam }}:</span>
                                                                    <span class="text-slate-700 font-weight-bold text-xs">
                                                                        <i class="far fa-clock text-purple-500 mr-0.5"></i>
                                                                        {{ $jamMulai }} &ndash; {{ $jamSelesai }}
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted font-italic text-xs">Belum diatur</span>
                                                        @endif
                                                    </div>
                                                    <button type="button" class="btn btn-xs btn-outline-primary rounded-lg px-2 py-0.5 font-weight-bold text-[10px] shrink-0" data-toggle="modal" data-target="#editJamBimbelModal" style="border-color: #cbd5e1; color: #475569;">
                                                        <i class="fas fa-clock mr-0.5 text-purple-600"></i> Atur
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="border-top border-light">
                                            <td class="text-muted py-2">Guru Pendamping</td>
                                            <td class="py-2">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="font-weight-bold text-slate-800 text-xs mr-1">
                                                        @php
                                                            $tutorPerMapel = $bio['tutor_per_mapel'] ?? [];
                                                            $currentGurus = [];
                                                            if ($student->tipe_paket && preg_match('/Guru:\s*([^|)]+)/i', $student->tipe_paket, $matches)) {
                                                                $currentGurus = array_map('trim', explode(',', $matches[1]));
                                                            }
                                                            $mapelJadwal = $bio['mapel_jadwal'] ?? [];
                                                            if (empty($mapelJadwal) && $student->tipe_paket) {
                                                                if (preg_match('/Mapel:\s*([^)|]+)/i', $student->tipe_paket, $matches)) {
                                                                    $mapelJadwal = array_map('trim', explode(',', $matches[1]));
                                                                }
                                                            }
                                                        @endphp

                                                        @if(!empty($tutorPerMapel))
                                                            @foreach($tutorPerMapel as $mName => $gName)
                                                                <div class="mb-1 d-flex align-items-center gap-1">
                                                                    <span class="badge bg-purple-100 text-purple-800 text-[10px] font-bold px-1.5 py-0.5 rounded">{{ $mName }}:</span>
                                                                    <span class="text-purple-950 font-weight-bold text-xs">{{ $gName }}</span>
                                                                </div>
                                                            @endforeach
                                                        @elseif(!empty($currentGurus))
                                                            @foreach($currentGurus as $cg)
                                                                <div class="mb-1 d-flex align-items-center gap-1">
                                                                    <i class="fas fa-chalkboard-teacher mr-1 text-purple-600"></i>
                                                                    <span class="text-purple-950 font-weight-bold text-xs">{{ $cg }}</span>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted font-italic text-xs">Belum ditentukan</span>
                                                        @endif
                                                    </div>
                                                    <button type="button" class="btn btn-xs btn-outline-primary rounded-lg px-2 py-0.5 font-weight-bold text-[10px] shrink-0" data-toggle="modal" data-target="#editTutorModal" style="border-color: #cbd5e1; color: #475569;">
                                                        <i class="fas fa-edit mr-0.5 text-purple-600"></i> Atur
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Payment Receipt Card -->
                    <div class="card shadow-sm border-light rounded-2xl overflow-hidden mb-4">
                        <div class="card-header bg-white py-3">
                            <h3 class="card-title font-weight-bold text-purple-950 mb-0" style="font-size: 0.95rem;">Bukti Pembayaran</h3>
                        </div>
                        <div class="card-body p-3 d-flex flex-column align-items-center text-center">
                            @if ($student->bukti_transfer)
                                <div class="w-100 bg-light p-2 rounded-xl border mb-3 d-flex align-items-center justify-center" style="background-color: #faf9fd; border-radius: 12px; border: 1px solid #f3f0fc; min-height: 200px;">
                                    @php
                                        $extension = pathinfo($student->bukti_transfer, PATHINFO_EXTENSION);
                                    @endphp
                                    @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                                        <img src="{{ asset($student->bukti_transfer) }}" alt="Bukti Pembayaran" class="img-fluid rounded-xl shadow-sm" style="object-fit: contain; max-height: 200px; max-width: 100%;" />
                                    @else
                                        <div class="py-4 text-center">
                                            <i class="fas fa-file-pdf text-danger fa-3x mb-2"></i>
                                            <h6 class="font-weight-bold text-slate-700 text-xs">Berkas PDF</h6>
                                        </div>
                                    @endif
                                </div>
                                <div class="w-100 d-flex flex-column gap-2">
                                    <a href="{{ asset($student->bukti_transfer) }}" target="_blank" class="btn btn-xs btn-outline-purple font-weight-bold py-2 rounded-lg text-xs w-100">
                                        <i class="fas fa-external-link-alt mr-1"></i> Lihat Bukti Penuh
                                    </a>
                                    
                                    @if ($student->status === 'under_review')
                                        <form action="{{ route('admin.siswa.approve.submit', $student->id) }}" method="POST" class="w-100 m-0" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pendaftaran dan mengaktifkan akun {{ $student->name }}?')">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-success font-weight-bold py-2 rounded-lg text-xs w-100">
                                                <i class="fas fa-check-circle mr-1"></i> Setujui &amp; Aktifkan Akun
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-xs btn-light border w-100 text-slate-400 font-weight-bold py-2 rounded-lg text-xs" disabled>
                                            <i class="fas fa-check mr-1"></i> Sudah Aktif
                                        </button>
                                    @endif
                                </div>
                            @else
                                <div class="py-4">
                                    <i class="fas fa-exclamation-triangle text-amber-400 fa-2x mb-2"></i>
                                    <p class="text-xs text-slate-500 mb-0">Belum mengunggah bukti pembayaran.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Detailed Student Form Biodata (8 cols) -->
                <div class="col-lg-8 mb-4">
                    <div class="card shadow-sm border-light rounded-2xl overflow-hidden h-100">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h3 class="card-title font-weight-bold text-purple-950 mb-0" style="font-size: 1.05rem;">Isi Formulir Lengkap Biodata</h3>
                        </div>
                        <div class="card-body p-4">
                            @if(empty($bio))
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3 text-slate-300"></i>
                                    <p class="text-sm">Biodata formulir lengkap tidak tersedia untuk siswa ini (biodata didaftarkan sebelum migrasi).</p>
                                </div>
                            @else
                                <!-- Part 1: Biodata Diri & Sosmed -->
                                <div class="mb-4">
                                    <h6 class="font-weight-bold text-purple-950 uppercase text-xs tracking-wider mb-3 pb-2 border-bottom text-purple-700"><i class="fas fa-user-circle mr-1.5"></i> 1. Biodata Pribadi &amp; Kontak</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <span class="text-xs text-muted d-block">Nama Panggilan</span>
                                            <span class="font-weight-bold text-purple-950 text-sm">{{ $namaPanggilan }}</span>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <span class="text-xs text-muted d-block">No. Telepon Rumah</span>
                                            <span class="font-weight-semibold text-slate-700 text-sm">{{ $noTelp }}</span>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <span class="text-xs text-muted d-block">Tempat &amp; Tanggal Lahir</span>
                                            <span class="font-weight-semibold text-slate-700 text-sm">{{ $tempatLahir }}, {{ $tanggalLahir }}</span>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <span class="text-xs text-muted d-block">Jenis Kelamin</span>
                                            <span class="font-weight-semibold text-slate-700 text-sm">{{ $jenisKelamin }}</span>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <span class="text-xs text-muted d-block">Tingkat Kelas &amp; Jurusan</span>
                                            <span class="font-weight-semibold text-slate-700 text-sm">{{ $kelas }} @if($jurusan && $jurusan !== '— Tidak berlaku / pilih jurusan —') ({{ $jurusan }}) @endif</span>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <span class="text-xs text-muted d-block">Alamat Rumah</span>
                                            <span class="font-weight-semibold text-slate-700 text-sm">{{ $alamat }}</span>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <span class="text-xs text-muted d-block">Akun Sosial Media (Umum)</span>
                                            <span class="font-weight-semibold text-slate-700 text-xs font-mono">{{ $sosmedUmum }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Part 2: Minat Belajar & Info -->
                                <div class="mb-4">
                                    <h6 class="font-weight-bold text-purple-950 uppercase text-xs tracking-wider mb-3 pb-2 border-bottom text-purple-700"><i class="fas fa-info-circle mr-1.5"></i> 2. Minat Belajar &amp; Referensi PM</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <span class="text-xs text-muted d-block">Sumber Informasi tentang PM</span>
                                            <span class="font-weight-semibold text-slate-700 text-sm">{{ $sumberInfo }} @if($sumberInfoDetail) ({{ $sumberInfoDetail }}) @endif</span>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <span class="text-xs text-muted d-block">Sosial Media Pribadi</span>
                                            <span class="font-weight-semibold text-slate-700 text-xs font-mono d-block">IG: {{ $igSiswa }} | TikTok: {{ $tiktokSiswa }} | FB: {{ $fbSiswa }}</span>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <span class="text-xs text-muted d-block">Nilai UN / Rapor Terakhir Pelajaran Terkait</span>
                                            <span class="font-weight-bold text-purple-950 text-sm">{{ $nilaiTerakhir }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Part 3: Jadwal Pulang & Kegiatan Rutin -->
                                <div class="mb-4">
                                    <h6 class="font-weight-bold text-purple-950 uppercase text-xs tracking-wider mb-3 pb-2 border-bottom text-purple-700"><i class="fas fa-calendar-alt mr-1.5"></i> 3. Jadwal Sekolah &amp; Rutinitas Lain</h6>
                                    <div class="row">
                                        <!-- Hari Bimbel Pilihan -->
                                        <div class="col-12 mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-xs text-muted font-weight-bold mb-0">Hari Pilihan untuk Bimbel</span>
                                                <button type="button" class="btn btn-xs btn-outline-primary rounded-lg px-2.5 py-1 font-weight-bold text-[10px]" data-toggle="modal" data-target="#editBimbelDaysModal" style="border-color: #cbd5e1; color: #475569;">
                                                    <i class="fas fa-edit mr-1 text-purple-600"></i> Edit Hari Bimbel
                                                </button>
                                            </div>
                                            <div class="d-flex flex-wrap gap-2">
                                                @if(!empty($hariPilihan))
                                                    @foreach($hariPilihan as $h)
                                                        <span class="badge bg-purple-100 text-purple-800 font-bold px-3 py-2 rounded-lg text-xs" style="border: 1px solid #d8d3e8;">
                                                            <i class="fas fa-calendar-check mr-1.5 text-purple-600"></i>{{ $h }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="text-slate-500 text-xs font-italic">Belum memilih hari bimbingan</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Jam Pulang Sekolah -->
                                        <div class="col-12 mb-3">
                                            <span class="text-xs text-muted d-block mb-1.5 font-weight-bold">Jam Pulang Sekolah &amp; Penyelarasan Hari Bimbel</span>
                                            <div class="grid grid-cols-2 md:grid-cols-6 gap-2">
                                                <!-- Senin -->
                                                @php $isSeninBimbel = in_array('senin', $hariPilihanLower); @endphp
                                                <div class="p-2 rounded text-center position-relative" style="background-color: {{ $isSeninBimbel ? '#faf5ff' : '#f8fafc' }}; border: 1px solid {{ $isSeninBimbel ? '#c084fc' : '#e2e8f0' }}; transition: all 0.2s ease;">
                                                    @if($isSeninBimbel)
                                                        <span class="position-absolute" style="top: 2px; right: 4px; font-size: 8px; color: #7c3aed;"><i class="fas fa-star"></i></span>
                                                    @endif
                                                    <span class="d-block text-[10px] text-muted font-weight-bold" style="color: {{ $isSeninBimbel ? '#7c3aed' : '' }} !important;">Senin</span>
                                                    <strong class="text-purple-950 text-xs d-block my-0.5">{{ $pulangSenin }}</strong>
                                                    @if($isSeninBimbel)
                                                        <span class="d-block text-[8px] font-bold text-purple-700 uppercase" style="font-size: 8px; line-height: 1;">Pilihan Bimbel</span>
                                                    @endif
                                                </div>

                                                <!-- Selasa -->
                                                @php $isSelasaBimbel = in_array('selasa', $hariPilihanLower); @endphp
                                                <div class="p-2 rounded text-center position-relative" style="background-color: {{ $isSelasaBimbel ? '#faf5ff' : '#f8fafc' }}; border: 1px solid {{ $isSelasaBimbel ? '#c084fc' : '#e2e8f0' }}; transition: all 0.2s ease;">
                                                    @if($isSelasaBimbel)
                                                        <span class="position-absolute" style="top: 2px; right: 4px; font-size: 8px; color: #7c3aed;"><i class="fas fa-star"></i></span>
                                                    @endif
                                                    <span class="d-block text-[10px] text-muted font-weight-bold" style="color: {{ $isSelasaBimbel ? '#7c3aed' : '' }} !important;">Selasa</span>
                                                    <strong class="text-purple-950 text-xs d-block my-0.5">{{ $pulangSelasa }}</strong>
                                                    @if($isSelasaBimbel)
                                                        <span class="d-block text-[8px] font-bold text-purple-700 uppercase" style="font-size: 8px; line-height: 1;">Pilihan Bimbel</span>
                                                    @endif
                                                </div>

                                                <!-- Rabu -->
                                                @php $isRabuBimbel = in_array('rabu', $hariPilihanLower); @endphp
                                                <div class="p-2 rounded text-center position-relative" style="background-color: {{ $isRabuBimbel ? '#faf5ff' : '#f8fafc' }}; border: 1px solid {{ $isRabuBimbel ? '#c084fc' : '#e2e8f0' }}; transition: all 0.2s ease;">
                                                    @if($isRabuBimbel)
                                                        <span class="position-absolute" style="top: 2px; right: 4px; font-size: 8px; color: #7c3aed;"><i class="fas fa-star"></i></span>
                                                    @endif
                                                    <span class="d-block text-[10px] text-muted font-weight-bold" style="color: {{ $isRabuBimbel ? '#7c3aed' : '' }} !important;">Rabu</span>
                                                    <strong class="text-purple-950 text-xs d-block my-0.5">{{ $pulangRabu }}</strong>
                                                    @if($isRabuBimbel)
                                                        <span class="d-block text-[8px] font-bold text-purple-700 uppercase" style="font-size: 8px; line-height: 1;">Pilihan Bimbel</span>
                                                    @endif
                                                </div>

                                                <!-- Kamis -->
                                                @php $isKamisBimbel = in_array('kamis', $hariPilihanLower); @endphp
                                                <div class="p-2 rounded text-center position-relative" style="background-color: {{ $isKamisBimbel ? '#faf5ff' : '#f8fafc' }}; border: 1px solid {{ $isKamisBimbel ? '#c084fc' : '#e2e8f0' }}; transition: all 0.2s ease;">
                                                    @if($isKamisBimbel)
                                                        <span class="position-absolute" style="top: 2px; right: 4px; font-size: 8px; color: #7c3aed;"><i class="fas fa-star"></i></span>
                                                    @endif
                                                    <span class="d-block text-[10px] text-muted font-weight-bold" style="color: {{ $isKamisBimbel ? '#7c3aed' : '' }} !important;">Kamis</span>
                                                    <strong class="text-purple-950 text-xs d-block my-0.5">{{ $pulangKamis }}</strong>
                                                    @if($isKamisBimbel)
                                                        <span class="d-block text-[8px] font-bold text-purple-700 uppercase" style="font-size: 8px; line-height: 1;">Pilihan Bimbel</span>
                                                    @endif
                                                </div>

                                                <!-- Jumat -->
                                                @php $isJumatBimbel = in_array('jumat', $hariPilihanLower); @endphp
                                                <div class="p-2 rounded text-center position-relative" style="background-color: {{ $isJumatBimbel ? '#faf5ff' : '#f8fafc' }}; border: 1px solid {{ $isJumatBimbel ? '#c084fc' : '#e2e8f0' }}; transition: all 0.2s ease;">
                                                    @if($isJumatBimbel)
                                                        <span class="position-absolute" style="top: 2px; right: 4px; font-size: 8px; color: #7c3aed;"><i class="fas fa-star"></i></span>
                                                    @endif
                                                    <span class="d-block text-[10px] text-muted font-weight-bold" style="color: {{ $isJumatBimbel ? '#7c3aed' : '' }} !important;">Jumat</span>
                                                    <strong class="text-purple-950 text-xs d-block my-0.5">{{ $pulangJumat }}</strong>
                                                    @if($isJumatBimbel)
                                                        <span class="d-block text-[8px] font-bold text-purple-700 uppercase" style="font-size: 8px; line-height: 1;">Pilihan Bimbel</span>
                                                    @endif
                                                </div>

                                                <!-- Sabtu -->
                                                @php $isSabtuBimbel = in_array('sabtu', $hariPilihanLower); @endphp
                                                <div class="p-2 rounded text-center position-relative" style="background-color: {{ $isSabtuBimbel ? '#faf5ff' : '#f8fafc' }}; border: 1px solid {{ $isSabtuBimbel ? '#c084fc' : '#e2e8f0' }}; transition: all 0.2s ease;">
                                                    @if($isSabtuBimbel)
                                                        <span class="position-absolute" style="top: 2px; right: 4px; font-size: 8px; color: #7c3aed;"><i class="fas fa-star"></i></span>
                                                    @endif
                                                    <span class="d-block text-[10px] text-muted font-weight-bold" style="color: {{ $isSabtuBimbel ? '#7c3aed' : '' }} !important;">Sabtu</span>
                                                    <strong class="text-purple-950 text-xs d-block my-0.5">{{ $pulangSabtu }}</strong>
                                                    @if($isSabtuBimbel)
                                                        <span class="d-block text-[8px] font-bold text-purple-700 uppercase" style="font-size: 8px; line-height: 1;">Pilihan Bimbel</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 mb-2">
                                            <span class="text-xs text-muted d-block">Kegiatan Rutin Selain Sekolah</span>
                                            <span class="font-weight-semibold text-slate-700 text-sm">{{ $kegiatanRutin }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Part 4: Data Orang Tua -->
                                <div>
                                    <h6 class="font-weight-bold text-purple-950 uppercase text-xs tracking-wider mb-3 pb-2 border-bottom text-purple-700"><i class="fas fa-users mr-1.5"></i> 4. Informasi Orang Tua (Ibu &amp; Ayah)</h6>
                                    <div class="row">
                                        <!-- Data Ibu -->
                                        <div class="col-md-6 mb-3 pr-md-3" style="border-right: 1px solid #f1f5f9;">
                                            <span class="badge bg-purple-50 text-purple-700 font-bold mb-2 text-[10px] px-2 py-0.5">DATA IBU / WALI</span>
                                            <div class="mb-2">
                                                <span class="text-[10px] text-muted d-block">Nama Lengkap</span>
                                                <strong class="text-slate-800 text-sm">{{ $ibuNamaLengkap }} ({{ $ibuNamaPanggilan }})</strong>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-[10px] text-muted d-block">No. HP Ibu</span>
                                                <span class="text-slate-700 text-xs">{{ $ibuNoHp }}</span>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-[10px] text-muted d-block">Umur / Pekerjaan</span>
                                                <span class="text-slate-700 text-xs">{{ $ibuUmur }} / {{ $ibuPekerjaan }}</span>
                                            </div>
                                            <div>
                                                <span class="text-[10px] text-muted d-block">Instagram Ibu</span>
                                                <span class="text-slate-700 text-xs font-mono">{{ $ibuInstagram }}</span>
                                            </div>
                                        </div>

                                        <!-- Data Ayah -->
                                        <div class="col-md-6 mb-3 pl-md-3">
                                            <span class="badge bg-purple-50 text-purple-700 font-bold mb-2 text-[10px] px-2 py-0.5">DATA AYAH / WALI</span>
                                            <div class="mb-2">
                                                <span class="text-[10px] text-muted d-block">Nama Lengkap</span>
                                                <strong class="text-slate-800 text-sm">{{ $ayahNamaLengkap }} ({{ $ayahNamaPanggilan }})</strong>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-[10px] text-muted d-block">No. HP Ayah</span>
                                                <span class="text-slate-700 text-xs">{{ $ayahNoHp }}</span>
                                            </div>
                                            <div class="mb-2">
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Edit Hari Bimbel Per Mapel -->
    <div class="modal fade" id="editBimbelDaysModal" tabindex="-1" role="dialog" aria-labelledby="editBimbelDaysModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 18px; overflow: hidden;">
                <form action="{{ route('admin.siswa.update-bimbel-days', $student->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-purple-950 text-white border-0 py-3" style="background-color: #2e1065;">
                        <h5 class="modal-title font-weight-bold text-md text-white" id="editBimbelDaysModalLabel" style="color: #fff;">Edit Hari Bimbel Per Mata Pelajaran</h5>
                        <button type="button" class="close text-white border-0 bg-transparent" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; outline: none; color: #fff;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4 text-left">
                        @if(!empty($mapelJadwal))
                            <p class="text-xs text-muted mb-3">Atur hari bimbingan untuk masing-masing mata pelajaran siswa ini.</p>
                            @php $daftarHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; @endphp
                            @foreach($mapelJadwal as $idx => $namaMapel)
                                @php
                                    $hariMapelIni = $bio['hari_per_mapel'][$idx] ?? [];
                                    if (!is_array($hariMapelIni)) $hariMapelIni = [];
                                    $hariMapelIniLower = array_map('strtolower', array_filter($hariMapelIni));
                                @endphp
                                <div class="p-3 mb-3 rounded-xl border" style="border-color: #e2e8f0; background-color: #faf9fd;">
                                    <label class="font-weight-bold text-purple-950 text-xs d-block mb-2">
                                        <i class="fas fa-book-open text-purple-600 mr-1.5"></i> {{ $namaMapel }}
                                    </label>
                                    <div class="row">
                                        @foreach($daftarHari as $hari)
                                            @php $isChecked = in_array(strtolower($hari), $hariMapelIniLower); @endphp
                                            <div class="col-4 mb-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="hari_per_mapel[{{ $idx }}][]" value="{{ $hari }}" class="custom-control-input" id="checkHari{{ $idx }}_{{ $hari }}" {{ $isChecked ? 'checked' : '' }}>
                                                    <label class="custom-control-label text-xs text-slate-700 font-weight-semibold" style="cursor: pointer;" for="checkHari{{ $idx }}_{{ $hari }}">{{ $hari }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-xs text-muted mb-0">Siswa ini belum memiliki data mata pelajaran terdaftar, sehingga jadwal per mapel belum bisa diatur.</p>
                        @endif
                    </div>
                    <div class="modal-footer border-0 bg-light p-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-secondary rounded-lg font-weight-bold px-3" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary rounded-lg font-weight-bold px-3" style="background-color: #7c3aed; border-color: #7c3aed;">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Tutor Pendamping -->
    <div class="modal fade" id="editTutorModal" tabindex="-1" role="dialog" aria-labelledby="editTutorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 18px; overflow: hidden;">
                <form action="{{ route('admin.siswa.assign-tutor', $student->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-purple-950 text-white border-0 py-3" style="background-color: #2e1065;">
                        <h5 class="modal-title font-weight-bold text-md text-white" id="editTutorModalLabel" style="color: #fff;">Atur Guru Pendamping</h5>
                        <button type="button" class="close text-white border-0 bg-transparent" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; outline: none; color: #fff;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4 text-left">
                        @if(!empty($mapelJadwal))
                            <div class="p-3 bg-purple-50 rounded-2xl border border-purple-100 mb-4">
                                <h6 class="font-weight-bold text-purple-950 text-xs mb-1">
                                    <i class="fas fa-layer-group text-purple-600 mr-1.5"></i> Penugasan Guru per Mata Pelajaran
                                </h6>
                                <p class="text-xs text-muted mb-0">Siswa ini mendaftar <strong>{{ count($mapelJadwal) }} Mata Pelajaran</strong> ({{ implode(', ', $mapelJadwal) }}). Silakan pilih guru pendamping untuk masing-masing mata pelajaran:</p>
                            </div>

                            <div class="row">
                                @foreach($mapelJadwal as $idx => $namaMapel)
                                    @php
                                        $selectedGuruForThisMapel = $tutorPerMapel[$namaMapel] ?? '';
                                        // Fallback match by mapel name if tutorPerMapel is empty
                                        if (!$selectedGuruForThisMapel && !empty($currentGurus)) {
                                            foreach($currentGurus as $cg) {
                                                if (str_contains(strtolower($cg), strtolower($namaMapel))) {
                                                    $selectedGuruForThisMapel = preg_replace('/^(math|english|ipa|ips|fisika|kimia|biologi|matematika):\s*/i', '', $cg);
                                                }
                                            }
                                        }
                                    @endphp
                                    <div class="col-md-6 mb-3">
                                        <div class="card border p-3 rounded-xl shadow-xs" style="background-color: #faf9fd; border-color: #ddd6fe !important;">
                                            <label class="font-weight-bold text-purple-950 text-xs d-flex align-items-center justify-content-between mb-2">
                                                <span><i class="fas fa-book-open text-purple-600 mr-1.5"></i> Mapel:</span>
                                                <span class="badge bg-purple-600 text-white font-bold px-2.5 py-1 text-xs rounded-full">{{ $namaMapel }}</span>
                                            </label>
                                            <select name="tutor_per_mapel[{{ $namaMapel }}]" class="form-control text-xs font-weight-semibold rounded-lg" style="height: 40px; border-color: #c4b5fd;">
                                                <option value="">-- Belum Ditentukan --</option>
                                                @if(isset($gurusList) && !$gurusList->isEmpty())
                                                    @foreach($gurusList as $g)
                                                        @php
                                                            $gName = $g->user->name ?? '';
                                                            $spec  = $g->spesialisasi ?? 'Matematika';
                                                            $isSel = (strtolower(trim($selectedGuruForThisMapel)) === strtolower(trim($gName)));
                                                        @endphp
                                                        <option value="{{ $gName }}" {{ $isSel ? 'selected' : '' }}>
                                                            {{ $gName }} (Spesialisasi: {{ $spec }})
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-muted mb-3">Pilih guru-guru pendamping yang ditugaskan untuk mengajar siswa ini. Anda dapat memilih lebih dari satu guru.</p>
                            
                            <!-- Search Box -->
                            <div class="input-group mb-3 shadow-sm" style="border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0 text-muted" style="border: 0;"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" id="searchTutorInput" class="form-control border-left-0 text-xs" placeholder="Cari nama atau spesialisasi guru..." style="height: 38px; border: 0; outline: none; font-size: 0.8rem;">
                            </div>

                            <div class="form-group mb-0">
                                <label class="font-weight-bold text-purple-950 text-xs d-block mb-2">Pilih Guru / Tutor:</label>
                                <div class="row">
                                    @if(isset($gurusList) && !$gurusList->isEmpty())
                                        @foreach($gurusList as $g)
                                            @php
                                                $isChecked = in_array($g->user->name ?? '', $currentGurus);
                                            @endphp
                                            <div class="col-md-6 mb-3 tutor-item-row" data-name="{{ strtolower($g->user->name ?? '') }}" data-spesialisasi="{{ strtolower($g->spesialisasi ?? 'matematika') }}">
                                                <div class="custom-control custom-checkbox p-2 rounded border" style="background-color: #f8fafc; border-color: #e2e8f0; height: 100%;">
                                                    <input type="checkbox" name="tutors[]" value="{{ $g->user->name ?? '' }}" class="custom-control-input" id="checkGuru{{ $g->id }}" {{ $isChecked ? 'checked' : '' }}>
                                                    <label class="custom-control-label text-xs text-slate-700 font-weight-semibold d-flex flex-column" style="cursor: pointer; padding-left: 8px;" for="checkGuru{{ $g->id }}">
                                                        <span class="font-weight-bold text-purple-950">{{ $g->user->name ?? '' }}</span>
                                                        <span class="text-[10px] text-muted">{{ $g->user->email ?? '' }}</span>
                                                        <span class="text-[10px] text-purple-600 mt-1 font-weight-bold">Spesialisasi: {{ $g->spesialisasi ?? 'Matematika' }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-12 text-center py-3">
                                            <span class="text-xs text-muted font-italic">Belum ada guru/tutor terdaftar.</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer border-0 bg-light p-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-secondary rounded-lg font-weight-bold px-3" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary rounded-lg font-weight-bold px-3" style="background-color: #7c3aed; border-color: #7c3aed;">Simpan Penugasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Jam Bimbel Per Mapel -->
    <div class="modal fade" id="editJamBimbelModal" tabindex="-1" role="dialog" aria-labelledby="editJamBimbelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 18px; overflow: hidden;">
                <form action="{{ route('admin.siswa.update-jam-bimbel', $student->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-purple-950 text-white border-0 py-3" style="background-color: #2e1065;">
                        <h5 class="modal-title font-weight-bold text-md text-white" id="editJamBimbelModalLabel" style="color: #fff;">
                            <i class="fas fa-clock mr-2"></i>Atur Jam Bimbel Per Mata Pelajaran
                        </h5>
                        <button type="button" class="close text-white border-0 bg-transparent" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; outline: none; color: #fff;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4 text-left">
                        @php
                            $mapelForJamModal = $bio['mapel_jadwal'] ?? [];
                            if (empty($mapelForJamModal) && $student->tipe_paket) {
                                if (preg_match('/Mapel:\s*([^)|]+)/i', $student->tipe_paket, $matches)) {
                                    $mapelForJamModal = array_map('trim', explode(',', $matches[1]));
                                }
                            }
                            $jamPerMapelModal = $bio['jam_per_mapel'] ?? [];
                        @endphp

                        @if(!empty($mapelForJamModal))
                            <div class="p-3 bg-blue-50 rounded-xl border border-blue-100 mb-4" style="background-color:#eff6ff; border-color:#bfdbfe;">
                                <p class="text-xs mb-0" style="color:#1e40af;">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Atur <strong>Jam Mulai</strong> bimbingan untuk masing-masing mata pelajaran. Jam Berakhir akan otomatis dihitung <strong>+90 menit</strong> dari Jam Mulai.
                                </p>
                            </div>

                            @foreach($mapelForJamModal as $idx => $namaMapelModal)
                            @php
                                $jamMulaiVal   = $jamPerMapelModal[$idx]['jam_mulai']   ?? '';
                                $jamSelesaiVal = $jamMulaiVal ? date('H:i', strtotime($jamMulaiVal . ' + 90 minutes')) : '';
                            @endphp
                            <div class="p-3 mb-3 rounded-xl border" style="border-color: #ddd6fe; background-color: #faf9fd;">
                                <label class="font-weight-bold text-purple-950 text-xs d-flex align-items-center mb-3">
                                    <i class="fas fa-book-open text-purple-600 mr-1.5"></i>
                                    Mata Pelajaran:
                                    <span class="badge bg-purple-600 text-white font-bold px-2.5 py-1 text-xs rounded-full ml-2">{{ $namaMapelModal }}</span>
                                </label>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="text-xs text-muted font-weight-bold mb-1 d-block">
                                            <i class="fas fa-play-circle text-emerald-500 mr-1"></i>Jam Mulai
                                        </label>
                                        <input
                                            type="time"
                                            name="jam_per_mapel[{{ $idx }}][jam_mulai]"
                                            value="{{ $jamMulaiVal }}"
                                            class="form-control text-sm font-weight-bold jam-mulai-input"
                                            data-target="#jamSelesaiPreview{{ $idx }}"
                                            style="height: 40px; border-color: #c4b5fd; border-radius: 10px;"
                                        >
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="text-xs text-muted font-weight-bold mb-1 d-block">
                                            <i class="fas fa-stop-circle text-rose-400 mr-1"></i>Jam Berakhir <span class="text-slate-400 font-normal normal-case">(otomatis, +90 menit)</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="jamSelesaiPreview{{ $idx }}"
                                            value="{{ $jamSelesaiVal ?: '—' }}"
                                            class="form-control text-sm font-weight-bold text-slate-500 bg-light"
                                            style="height: 40px; border-color: #e2e8f0; border-radius: 10px;"
                                            disabled
                                            readonly
                                        >
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-book-open text-slate-300 fa-2x mb-2"></i>
                                <p class="text-xs text-muted mb-0">Siswa ini belum memiliki data mata pelajaran terdaftar.</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer border-0 bg-light p-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-secondary rounded-lg font-weight-bold px-3" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary rounded-lg font-weight-bold px-3" style="background-color: #7c3aed; border-color: #7c3aed;">
                            <i class="fas fa-save mr-1"></i>Simpan Jam Bimbel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchTutorInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase().trim();
                    const items = document.querySelectorAll('.tutor-item-row');
                    items.forEach(item => {
                        const name = item.getAttribute('data-name');
                        const spec = item.getAttribute('data-spesialisasi');
                        if (name.includes(query) || spec.includes(query)) {
                            item.style.setProperty('display', '', 'important');
                        } else {
                            item.style.setProperty('display', 'none', 'important');
                        }
                    });
                });
            }
        });
        document.querySelectorAll('.jam-mulai-input').forEach(function (input) {
            input.addEventListener('input', function () {
                const targetSelector = this.getAttribute('data-target');
                const targetEl = document.querySelector(targetSelector);
                if (!targetEl) return;

                if (!this.value) {
                    targetEl.value = '—';
                    return;
                }

                const [h, m] = this.value.split(':').map(Number);
                const totalMinutes = h * 60 + m + 90;
                const endH = Math.floor((totalMinutes % 1440) / 60).toString().padStart(2, '0');
                const endM = (totalMinutes % 60).toString().padStart(2, '0');
                targetEl.value = `${endH}:${endM}`;
            });
        });
    </script>

    <!-- Custom CSS for purple brand elements -->
    <style>
        .btn-outline-purple {
            color: #7c3aed;
            border-color: #d8d3e8;
            background-color: transparent;
            transition: all 0.2s ease;
        }
        .btn-outline-purple:hover {
            color: white;
            background-color: #7c3aed;
            border-color: #7c3aed;
        }
        .text-xxs {
            font-size: 0.7rem;
        }
        .rounded-2xl {
            border-radius: 16px !important;
        }
    </style>
@endsection
