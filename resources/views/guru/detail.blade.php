@extends('layout.app')

@section('title', 'Detail Guru & Tutor · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Detail Profil Pengajar</h1>
                    <p class="text-sm text-muted mb-0">Lihat biodata lengkap dan daftar siswa bimbingan dari guru/tutor pendamping.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.guru.daftar.index') }}" class="text-purple-600">Daftar Guru</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Action Bar -->
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <a href="{{ route('admin.guru.daftar.index') }}" class="btn btn-sm btn-light border rounded-lg font-weight-bold text-purple-950 px-3">
                    <i class="fas fa-arrow-left mr-1.5"></i> Kembali ke Daftar Guru
                </a>
                <button type="button" class="btn btn-sm btn-danger font-weight-bold px-3 py-1.5 rounded-lg shadow-sm" data-toggle="modal" data-target="#modalHapusGuru">
                    <i class="fas fa-trash-alt mr-1.5"></i> Hapus Akun Guru
                </button>
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

            <div class="row">
                <!-- LEFT COLUMN: Profile Brief & Contact Info (4 cols) -->
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm border-light rounded-2xl overflow-hidden mb-4">
                        <div class="card-header text-white py-3 d-flex align-items-center justify-content-between" style="background-color: #2e1065;">
                            <h5 class="card-title font-weight-bold text-md mb-0 text-white">Status & Kontak</h5>
                            <i class="fas fa-id-card text-purple-200"></i>
                        </div>
                        <div class="card-body p-4 text-center">
                            <!-- Avatar -->
                            @if($guru->foto)
                                <img src="{{ asset($guru->foto) }}" class="rounded-circle shadow-sm mx-auto mb-3" style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #ddd6fe;" alt="Foto Profil">
                            @else
                                <div class="avatar bg-purple-100 text-purple-700 font-bold d-flex justify-content-center align-items-center rounded-circle mx-auto mb-3" style="width: 80px; height: 80px; font-size: 32px; border: 3px solid #ddd6fe;">
                                    {{ strtoupper(substr($guru->user->name ?? 'G', 0, 1)) }}
                                </div>
                            @endif
                            
                            <!-- Name and Gelar -->
                            <h4 class="font-weight-bold text-purple-950 mb-1">
                                {{ $guru->user->name ?? 'Guru / Tutor' }}@if($guru->gelar), {{ $guru->gelar }}@endif
                            </h4>
                            
                            <!-- Spesialisasi -->
                            <div class="mb-3">
                                <span class="badge bg-purple-100 text-purple-800 px-3 py-1.5 text-xs font-bold uppercase rounded-pill border border-purple-200">
                                    {{ $guru->spesialisasi ?? 'Matematika' }}
                                </span>
                            </div>

                            <!-- Account Status Badge & Approval Buttons -->
                            <div class="mb-4">
                                @if (strtolower($guru->status) === 'pending')
                                    <div class="mb-2">
                                        <span class="badge bg-amber-100 text-amber-800 px-3 py-1.5 text-xs font-bold uppercase rounded-pill border border-amber-300">
                                            <i class="fas fa-hourglass-half mr-1"></i> Pending Approval
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-center gap-2 mt-3">
                                        <form action="{{ route('admin.guru.approve', $guru->id) }}" method="POST" class="m-0 mr-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success font-weight-bold px-3 py-1.5 rounded-lg shadow-sm text-xs" onclick="return confirm('Apakah Anda yakin ingin menyetujui pendaftaran akun guru ini?')">
                                                <i class="fas fa-check mr-1"></i> Setujui Akun
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.guru.reject', $guru->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger font-weight-bold px-3 py-1.5 rounded-lg shadow-sm text-xs" onclick="return confirm('Apakah Anda yakin ingin menolak pendaftaran akun guru ini?')">
                                                <i class="fas fa-times mr-1"></i> Tolak Pendaftaran
                                            </button>
                                        </form>
                                    </div>
                                @elseif (strtolower($guru->status) === 'aktif')
                                    <span class="badge bg-emerald-100 text-emerald-800 px-3 py-1.5 text-xs font-bold uppercase rounded-pill border border-emerald-200">
                                        <i class="fas fa-check-circle mr-1"></i> Aktif
                                    </span>
                                @elseif (strtolower($guru->status) === 'ditolak')
                                    <span class="badge bg-rose-100 text-rose-800 px-3 py-1.5 text-xs font-bold uppercase rounded-pill border border-rose-200">
                                        <i class="fas fa-times-circle mr-1"></i> Ditolak
                                    </span>
                                @else
                                    <span class="badge bg-slate-100 text-slate-600 px-3 py-1.5 text-xs font-bold uppercase rounded-pill border border-slate-200">
                                        <i class="fas fa-times-circle mr-1"></i> {{ $guru->status ?? 'Nonaktif' }}
                                    </span>
                                @endif
                            </div>

                            <hr class="my-4 border-slate-100">

                            <!-- Kontak List -->
                            <div class="text-left">
                                <h6 class="font-weight-bold text-purple-950 text-xs mb-3 uppercase tracking-wider text-muted">Informasi Kontak</h6>
                                
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box rounded-lg bg-purple-50 text-purple-700 d-flex justify-content-center align-items-center mr-3" style="width: 36px; height: 36px; flex-shrink: 0;">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="text-truncate">
                                        <small class="text-slate-400 d-block text-xxs font-weight-bold uppercase">Email</small>
                                        <a href="mailto:{{ $guru->user->email }}" class="text-slate-800 font-weight-semibold text-sm">{{ $guru->user->email ?? '-' }}</a>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box rounded-lg bg-emerald-50 text-emerald-700 d-flex justify-content-center align-items-center mr-3" style="width: 36px; height: 36px; flex-shrink: 0;">
                                        <i class="fab fa-whatsapp" style="font-size: 18px;"></i>
                                    </div>
                                    <div>
                                        <small class="text-slate-400 d-block text-xxs font-weight-bold uppercase">WhatsApp / No. Telp</small>
                                        @php $phone = $guru->no_telp ?: ($guru->user->phone ?? null); @endphp
                                        @if($phone)
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $phone) }}" target="_blank" class="text-slate-800 font-weight-semibold text-sm hover:text-emerald-600 transition-colors">
                                                {{ $phone }} <i class="fas fa-external-link-alt ml-1 text-xs text-muted"></i>
                                            </a>
                                        @else
                                            <span class="text-slate-500 font-weight-semibold text-sm">-</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="icon-box rounded-lg bg-purple-50 text-purple-700 d-flex justify-content-center align-items-center mr-3" style="width: 36px; height: 36px; flex-shrink: 0;">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div>
                                        <small class="text-slate-400 d-block text-xxs font-weight-bold uppercase">Alamat Rumah</small>
                                        <span class="text-slate-800 font-weight-semibold text-sm d-block leading-normal">{{ $guru->alamat ?? '-' }}</span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top text-center">
                                    <button type="button" class="btn btn-xs btn-outline-danger btn-block font-weight-bold py-2 rounded-xl" data-toggle="modal" data-target="#modalHapusGuru">
                                        <i class="fas fa-trash-alt mr-1.5"></i> Hapus Akun Guru Ini
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Biodata Details & Students List (8 cols) -->
                <div class="col-lg-8">

                    <!-- CARD: Batas Maksimal Siswa (Kuota) -->
                    <div class="card shadow-sm border-light rounded-2xl overflow-hidden mb-4">
                        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                            <h5 class="card-title font-weight-bold text-purple-950 text-md mb-0">
                                <i class="fas fa-users-cog mr-2 text-purple-700"></i> Batas Maksimal Siswa
                            </h5>
                            @if($guru->max_siswa !== null)
                                <span class="badge bg-purple-100 text-purple-800 border border-purple-200 px-3 py-1 text-xs font-bold rounded-pill">
                                    <i class="fas fa-sliders-h mr-1"></i> Batas Ditentukan
                                </span>
                            @else
                                <span class="badge bg-emerald-100 text-emerald-800 border border-emerald-200 px-3 py-1 text-xs font-bold rounded-pill">
                                    <i class="fas fa-infinity mr-1"></i> Tanpa Batas (∞)
                                </span>
                            @endif
                        </div>
                        <div class="card-body p-4">
                            @php
                                $assignedCount = count($siswaBimbingan);
                                $maxLimit = $guru->max_siswa;
                                $percentUsed = $maxLimit ? min(100, round(($assignedCount / $maxLimit) * 100)) : 0;
                            @endphp

                            <!-- Status Box & Progress Bar -->
                            <div class="p-3.5 rounded-2xl mb-4 border" style="background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%); border-color: #e9d5ff !important;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        <small class="text-slate-500 font-weight-bold uppercase tracking-wider d-block text-xxs">Status Kuota Saat Ini</small>
                                        <span class="font-weight-extrabold text-purple-950 text-base">
                                            {{ $assignedCount }} Siswa Bimbingan
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <small class="text-slate-500 font-weight-bold uppercase tracking-wider d-block text-xxs">Batas Maksimal</small>
                                        <span class="badge {{ ($maxLimit !== null && $assignedCount >= $maxLimit) ? 'bg-rose-100 text-rose-800 border-rose-300' : 'bg-purple-100 text-purple-900 border-purple-200' }} border px-3 py-1 text-xs font-bold rounded-pill">
                                            {{ $maxLimit !== null ? $maxLimit . ' Siswa' : '∞ (Tanpa Batas)' }}
                                        </span>
                                    </div>
                                </div>

                                @if($maxLimit !== null)
                                    <div class="progress mt-2 rounded-pill shadow-inner" style="height: 8px; background-color: #e9d5ff;">
                                        <div class="progress-bar rounded-pill {{ $percentUsed >= 100 ? 'bg-rose-500' : 'bg-purple-600' }}" role="progressbar" style="width: {{ $percentUsed }}%;" aria-valuenow="{{ $percentUsed }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1.5 text-xxs font-weight-semibold text-purple-900">
                                        <span><i class="fas fa-chart-pie mr-1 text-purple-600"></i> Penggunaan: {{ $percentUsed }}%</span>
                                        <span>Sisa Kuota: {{ max(0, $maxLimit - $assignedCount) }} Siswa</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Input Form with Inline Group -->
                            <form action="{{ route('admin.guru.update-max-siswa', $guru->id) }}" method="POST">
                                @csrf
                                <label class="font-weight-bold text-xs text-purple-950 uppercase tracking-wider mb-2 d-block">
                                    Atur / Ubah Batas Siswa
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-purple-50 text-purple-700 border-purple-200">
                                            <i class="fas fa-user-friends text-xs"></i>
                                        </span>
                                    </div>
                                    <input type="number" name="max_siswa" class="form-control font-weight-bold text-purple-950 border-purple-200" min="0" placeholder="Kosongkan jika Tanpa Batas (∞)" value="{{ $guru->max_siswa }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-purple font-weight-bold text-white px-4 shadow-sm" style="background-color: #6b21a8; border-color: #6b21a8;">
                                            <i class="fas fa-save mr-1.5"></i> Simpan Kuota
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-2 text-xs">
                                    <i class="fas fa-info-circle text-purple-600 mr-1"></i>
                                    Kosongkan kolom inputan jika ingin memberikan jumlah siswa bimbingan tanpa batas.
                                </small>
                            </form>
                        </div>
                    </div>

                    <!-- Card: Biodata Lengkap -->
                    <div class="card shadow-sm border-light rounded-2xl overflow-hidden mb-4">
                        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                            <h5 class="card-title font-weight-bold text-md text-purple-950 mb-0">Biodata Lengkap Pengajar</h5>
                            <i class="fas fa-user-tie text-purple-500"></i>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-xs text-slate-400 font-weight-bold uppercase tracking-wider mb-1">Nama Lengkap</label>
                                    <p class="text-sm font-weight-semibold text-slate-800 bg-light p-2.5 rounded-xl border border-slate-100 mb-0">
                                        {{ $guru->user->name ?? '-' }}
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-xs text-slate-400 font-weight-bold uppercase tracking-wider mb-1">Gelar Akademik</label>
                                    <p class="text-sm font-weight-semibold text-slate-800 bg-light p-2.5 rounded-xl border border-slate-100 mb-0">
                                        {{ $guru->gelar ?? '-' }}
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-xs text-slate-400 font-weight-bold uppercase tracking-wider mb-1">Pendidikan Terakhir</label>
                                    <p class="text-sm font-weight-semibold text-slate-800 bg-light p-2.5 rounded-xl border border-slate-100 mb-0">
                                        {{ $guru->pendidikan_terakhir ?? '-' }}
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-xs text-slate-400 font-weight-bold uppercase tracking-wider mb-1">Spesialisasi Mengajar</label>
                                    <p class="text-sm font-weight-semibold text-slate-800 bg-light p-2.5 rounded-xl border border-slate-100 mb-0">
                                        {{ $guru->spesialisasi ?? '-' }}
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-xs text-slate-400 font-weight-bold uppercase tracking-wider mb-1">Pengalaman Mengajar</label>
                                    <p class="text-sm font-weight-semibold text-slate-800 bg-light p-2.5 rounded-xl border border-slate-100 mb-0">
                                        {{ $guru->pengalaman_mengajar ?? '-' }}
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-xs text-slate-400 font-weight-bold uppercase tracking-wider mb-1">No. WhatsApp / HP</label>
                                    <p class="text-sm font-weight-semibold text-slate-800 bg-light p-2.5 rounded-xl border border-slate-100 mb-0">
                                        {{ ($guru->no_telp ?: ($guru->user->phone ?? null)) ?? '-' }}
                                    </p>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="text-xs text-slate-400 font-weight-bold uppercase tracking-wider mb-1">Alamat Domisili</label>
                                    <p class="text-sm font-weight-semibold text-slate-800 bg-light p-2.5 rounded-xl border border-slate-100 mb-0">
                                        {{ $guru->alamat ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="text-xs text-slate-400 font-weight-bold uppercase tracking-wider mb-1">Bio Singkat &amp; Kompetensi</label>
                                <div class="text-sm text-slate-700 bg-light p-3 rounded-xl border border-slate-100 leading-relaxed font-weight-medium">
                                    {!! nl2br(e($guru->bio_singkat ?? 'Belum ada deskripsi biodata singkat.')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card: Daftar Siswa Bimbingan -->
                    <div class="card shadow-sm border-light rounded-2xl overflow-hidden mb-4">
                        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                            <h5 class="card-title font-weight-bold text-md text-purple-950 mb-0">
                                <i class="fas fa-user-graduate mr-1.5 text-purple-600"></i> Siswa Bimbingan saat Ini
                            </h5>
                            <span class="badge bg-purple-600 text-white px-2.5 py-1 text-xs font-bold rounded-pill">
                                {{ count($siswaBimbingan) }} Siswa
                            </span>
                        </div>
                        <div class="card-body p-0">
                            @if(count($siswaBimbingan) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light text-slate-500">
                                            <tr>
                                                <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Nama Siswa</th>
                                                <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Kelas</th>
                                                <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Paket Belajar</th>
                                                <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Hari Pertemuan</th>
                                                <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Mulai Les</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($siswaBimbingan as $s)
                                                @php
                                                    $sBio = $s->biodata ?? [];
                                                    $sDays = $sBio['hari_pertemuan'] ?? [];
                                                    if (empty($sDays) && $s->tipe_paket) {
                                                        if (preg_match('/Hari:\s*([^)|]+)/i', $s->tipe_paket, $matches)) {
                                                            $sDays = array_map('trim', explode(',', $matches[1]));
                                                        }
                                                    }
                                                    $sStart = $sBio['tanggal_mulai'] ?? null;
                                                @endphp
                                                <tr>
                                                    <td class="px-4 py-3 font-weight-bold text-purple-950">
                                                        <a href="{{ route('admin.siswa.detail', $s->id) }}" class="text-purple-950 hover:text-purple-700">
                                                            {{ $s->name }}
                                                        </a>
                                                    </td>
                                                    <td class="px-4 py-3 text-center text-slate-600 text-sm">
                                                        <span class="badge bg-slate-100 text-slate-700 px-2 py-1 rounded">
                                                            {{ $sBio['kelas'] ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-slate-700 text-xs font-weight-semibold leading-normal" style="max-width: 200px;">
                                                        <div class="font-weight-bold text-purple-900">{{ $s->paket->nama_paket ?? 'Paket' }}</div>
                                                    </td>
                                                    <td class="px-4 py-3 text-slate-600 text-xs">
                                                        @if(!empty($sDays))
                                                            @foreach($sDays as $day)
                                                                <span class="badge bg-purple-50 text-purple-700 border border-purple-100 px-2 py-0.5 rounded mr-1 mb-1 d-inline-block">
                                                                    {{ $day }}
                                                                </span>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted font-italic">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 text-center text-slate-500 text-xs font-weight-semibold">
                                                        {{ $sStart ? date('d-m-Y', strtotime($sStart)) : '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-user-graduate text-slate-300 fa-2x mb-2 d-block"></i>
                                    <span class="text-xs text-muted font-italic">Belum ada siswa bimbingan yang ditugaskan kepada pengajar ini.</span>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Modal Confirm Hapus Guru -->
    <div class="modal fade" id="modalHapusGuru" tabindex="-1" role="dialog" aria-labelledby="modalHapusGuruLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg rounded-2xl overflow-hidden" style="border-radius: 16px;">
                <div class="modal-header bg-danger text-white py-3 px-4" style="background-color: #dc2626 !important;">
                    <h5 class="modal-title font-bold text-base d-flex align-items-center text-white" id="modalHapusGuruLabel">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Konfirmasi Hapus Akun Guru
                    </h5>
                    <button type="button" class="close text-white opacity-80 hover:opacity-100" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4 bg-white text-center">
                    <div class="rounded-circle bg-rose-100 text-rose-600 p-3 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px; background-color: #ffe4e6; color: #e11d48;">
                        <i class="fas fa-user-slash fa-2x"></i>
                    </div>
                    <h5 class="font-weight-bold text-purple-950 mb-2">Hapus Akun Pengajar?</h5>
                    <p class="text-sm text-muted mb-0">
                        Apakah Anda yakin ingin menghapus akun guru <strong>{{ $guru->user->name ?? 'Pengajar' }}</strong> secara permanen?
                        Tindakan ini tidak dapat dibatalkan dan seluruh data profil pengajar ini akan dihapus dari sistem.
                    </p>
                </div>
                <div class="modal-footer bg-slate-50 py-2.5 px-4 border-top d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-light border font-weight-bold rounded-lg px-3" data-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.guru.delete', $guru->id) }}" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger font-weight-bold rounded-lg px-4 text-white">
                            <i class="fas fa-trash-alt mr-1.5"></i> Ya, Hapus Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .text-purple-950 {
            color: #2e1065 !important;
        }
        .text-xxs {
            font-size: 0.7rem;
        }
        .leading-normal {
            line-height: 1.4 !important;
        }
        .rounded-2xl {
            border-radius: 1rem !important;
        }
        .rounded-xl {
            border-radius: 0.75rem !important;
        }
    </style>
@endsection
