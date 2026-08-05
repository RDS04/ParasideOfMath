@extends('layout.app')

@section('title', 'Lengkapi Biodata Pengajar · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-teal-950">Biodata Pengajar</h1>
                    <p class="text-sm text-muted mb-0">Lengkapi data diri Anda agar siswa dapat mengenal kualifikasi dan pengalaman mengajar Anda.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}" class="text-teal-600">Dashboard</a></li>
                        <li class="breadcrumb-item active">Biodata Pengajar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-xl shadow-sm border-0 mb-4" role="alert">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-xl shadow-sm border-0 mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i> <strong>Mohon periksa kembali inputan Anda:</strong>
                    <ul class="mb-0 mt-1 pl-4 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8 col-md-10 mx-auto">
                    <div class="card border-0 shadow-sm overflow-hidden mb-5">
                        <div class="card-header p-4" style="background: linear-gradient(135deg, #4c1d95 0%, #2e1065 100%); color: white;">
                            <div class="d-flex align-items-center">
                                <div class="bg-amber-400 text-purple-950 rounded-circle d-flex align-items-center justify-content-center mr-3 font-weight-bold" style="width: 48px; height: 48px; font-size: 1.25rem;">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1 font-weight-bold">Formulir Profil &amp; Biodata Guru</h4>
                                    <p class="text-purple-200 text-xs mb-0">Informasi ini akan menjadi kualifikasi resmi Anda di sistem Paradise of Math.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4 bg-white">
                            <form action="{{ route('guru.biodata.update') }}" method="POST">
                                @csrf

                                <!-- ── SEC 1: IDENTITAS UTAMA ── -->
                                <h5 class="text-purple-900 font-weight-bold border-bottom pb-2 mb-3">
                                    <i class="fas fa-user text-purple-600 mr-2"></i>Identitas Pengajar
                                </h5>

                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group mb-3">
                                            <label for="name" class="font-weight-semibold text-sm">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Contoh: Budi Santoso" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group mb-3">
                                            <label for="gelar" class="font-weight-semibold text-sm">Gelar Akademik</label>
                                            <input type="text" class="form-control rounded-lg @error('gelar') is-invalid @enderror" id="gelar" name="gelar" value="{{ old('gelar', $guruProfile->gelar) }}" placeholder="Contoh: S.Pd., M.Si.">
                                            <small class="form-text text-muted">Opsional (misal: S.Pd / M.Si)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="pendidikan_terakhir" class="font-weight-semibold text-sm">Pendidikan Terakhir / Lulusan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-lg @error('pendidikan_terakhir') is-invalid @enderror" id="pendidikan_terakhir" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $guruProfile->pendidikan_terakhir) }}" placeholder="Contoh: S1 Pendidikan Matematika - Universitas Negeri Surabaya" required>
                                    <small class="form-text text-muted">Tuliskan jenjang dan nama perguruan tinggi tempat Anda menempuh pendidikan.</small>
                                    @error('pendidikan_terakhir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- ── SEC 2: KEAHLIAN & PENGALAMAN ── -->
                                <h5 class="text-purple-900 font-weight-bold border-bottom pb-2 mb-3 mt-4">
                                    <i class="fas fa-graduation-cap text-purple-600 mr-2"></i>Keahlian &amp; Pengalaman
                                </h5>

                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group mb-3">
                                            <label for="spesialisasi" class="font-weight-semibold text-sm">Spesialisasi Mengajar <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-lg @error('spesialisasi') is-invalid @enderror" id="spesialisasi" name="spesialisasi" value="{{ old('spesialisasi', $guruProfile->spesialisasi) }}" placeholder="Contoh: Matematika SMA, Kalkulus, UTBK" required>
                                            @error('spesialisasi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group mb-3">
                                            <label for="pengalaman_mengajar" class="font-weight-semibold text-sm">Pengalaman Mengajar</label>
                                            <input type="text" class="form-control rounded-lg @error('pengalaman_mengajar') is-invalid @enderror" id="pengalaman_mengajar" name="pengalaman_mengajar" value="{{ old('pengalaman_mengajar', $guruProfile->pengalaman_mengajar) }}" placeholder="Contoh: 3 Tahun / 5 Tahun">
                                        </div>
                                    </div>
                                </div>

                                <!-- ── SEC 3: KONTAK & ALAMAT ── -->
                                <h5 class="text-purple-900 font-weight-bold border-bottom pb-2 mb-3 mt-4">
                                    <i class="fas fa-address-book text-purple-600 mr-2"></i>Kontak &amp; Alamat Domisili
                                </h5>

                                <div class="form-group mb-3">
                                    <label for="no_telp" class="font-weight-semibold text-sm">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light text-muted font-weight-bold">+62 / 0</span>
                                        </div>
                                        <input type="text" class="form-control rounded-right-lg @error('no_telp') is-invalid @enderror" id="no_telp" name="no_telp" value="{{ old('no_telp', $guruProfile->no_telp) }}" placeholder="081234567890" required>
                                    </div>
                                    @error('no_telp')
                                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label for="alamat" class="font-weight-semibold text-sm">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control rounded-lg @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" placeholder="Tuliskan alamat tinggal Anda saat ini..." required>{{ old('alamat', $guruProfile->alamat) }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- ── SEC 4: BIO RINGKAS ── -->
                                <h5 class="text-purple-900 font-weight-bold border-bottom pb-2 mb-3 mt-4">
                                    <i class="fas fa-comment-alt text-purple-600 mr-2"></i>Perkenalan / Deskripsi Singkat
                                </h5>

                                <div class="form-group mb-4">
                                    <label for="bio_singkat" class="font-weight-semibold text-sm">Bio / Catatan untuk Siswa</label>
                                    <textarea class="form-control rounded-lg @error('bio_singkat') is-invalid @enderror" id="bio_singkat" name="bio_singkat" rows="4" placeholder="Ceritakan singkat metode mengajar Anda atau pesan hangat untuk siswa bimbingan Anda...">{{ old('bio_singkat', $guruProfile->bio_singkat) }}</textarea>
                                    <small class="form-text text-muted">Deskripsi ini akan membantu siswa merasa nyaman dan mengenal pendekatan mengajar Anda.</small>
                                </div>

                                <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                                    <a href="{{ route('guru.dashboard') }}" class="btn btn-outline-secondary px-4 py-2 rounded-xl font-weight-semibold">
                                        <i class="fas fa-arrow-left mr-1"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-brand px-5 py-2.5 rounded-xl font-weight-bold shadow-sm" style="background: linear-gradient(135deg, #7c3aed, #4c1d95); color: white; border: none;">
                                        <i class="fas fa-save mr-2"></i> Simpan Biodata Guru
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
