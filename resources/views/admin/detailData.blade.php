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
                                        <div class="col-12 mb-3">
                                            <span class="text-xs text-muted d-block mb-1.5 font-weight-bold">Jam Pulang Sekolah</span>
                                            <div class="grid grid-cols-2 md:grid-cols-6 gap-2">
                                                <div class="p-2 bg-light rounded text-center" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                                    <span class="d-block text-[10px] text-muted">Senin</span>
                                                    <strong class="text-purple-950 text-xs">{{ $pulangSenin }}</strong>
                                                </div>
                                                <div class="p-2 bg-light rounded text-center" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                                    <span class="d-block text-[10px] text-muted">Selasa</span>
                                                    <strong class="text-purple-950 text-xs">{{ $pulangSelasa }}</strong>
                                                </div>
                                                <div class="p-2 bg-light rounded text-center" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                                    <span class="d-block text-[10px] text-muted">Rabu</span>
                                                    <strong class="text-purple-950 text-xs">{{ $pulangRabu }}</strong>
                                                </div>
                                                <div class="p-2 bg-light rounded text-center" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                                    <span class="d-block text-[10px] text-muted">Kamis</span>
                                                    <strong class="text-purple-950 text-xs">{{ $pulangKamis }}</strong>
                                                </div>
                                                <div class="p-2 bg-light rounded text-center" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                                    <span class="d-block text-[10px] text-muted">Jumat</span>
                                                    <strong class="text-purple-950 text-xs">{{ $pulangJumat }}</strong>
                                                </div>
                                                <div class="p-2 bg-light rounded text-center" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                                    <span class="d-block text-[10px] text-muted">Sabtu</span>
                                                    <strong class="text-purple-950 text-xs">{{ $pulangSabtu }}</strong>
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
