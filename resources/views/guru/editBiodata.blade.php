@extends('layout.app')

@section('title', 'Edit Biodata Akademik · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-teal-950">Edit Biodata Akademik</h1>
                    <p class="text-sm text-muted mb-0">Ubah informasi kualifikasi, pengalaman mengajar, dan domisili Anda.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}" class="text-teal-600">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('guru.biodata') }}" class="text-teal-600">Profil &amp; Biodata</a></li>
                        <li class="breadcrumb-item active">Edit Biodata</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
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
                    <div class="card border-0 shadow-sm overflow-hidden mb-5" style="border-radius: 20px;">
                        <div class="card-header p-4" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); color: white;">
                            <div class="d-flex align-items-center">
                                <div class="bg-amber-400 text-teal-950 rounded-circle d-flex align-items-center justify-content-center mr-3 font-weight-bold" style="width: 48px; height: 48px; font-size: 1.25rem; background-color: #fbbf24 !important;">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1 font-weight-bold">Formulir Kualifikasi &amp; Biodata</h4>
                                    <p class="text-teal-100 text-xs mb-0">Informasi ini akan terpublikasi sebagai kualifikasi resmi Anda.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4 bg-white">
                            <form action="{{ route('guru.biodata.update') }}" method="POST">
                                @csrf

                                <!-- ── SEC 1: KUALIFIKASI AKADEMIK ── -->
                                <h5 class="text-teal-900 font-weight-bold border-bottom pb-2 mb-3">
                                    <i class="fas fa-graduation-cap text-teal-600 mr-2"></i>Identitas &amp; Pendidikan
                                </h5>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="gelar" class="font-weight-semibold text-sm">Gelar Akademik</label>
                                            <input type="text" class="form-control rounded-lg @error('gelar') is-invalid @enderror" id="gelar" name="gelar" value="{{ old('gelar', $guruProfile->gelar) }}" placeholder="Contoh: S.Pd., M.Si.">
                                            <small class="form-text text-muted">Tulis gelar Anda (misal: S.Pd / M.Pd / S.Si)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="pendidikan_terakhir" class="font-weight-semibold text-sm">Pendidikan Terakhir / Lulusan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-lg @error('pendidikan_terakhir') is-invalid @enderror" id="pendidikan_terakhir" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $guruProfile->pendidikan_terakhir) }}" placeholder="Contoh: S1 Pendidikan Matematika - Universitas Surabaya" required>
                                    <small class="form-text text-muted">Tuliskan jenjang dan nama perguruan tinggi tempat Anda menempuh pendidikan.</small>
                                    @error('pendidikan_terakhir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- ── SEC 2: KEAHLIAN & PENGALAMAN ── -->
                                <h5 class="text-teal-900 font-weight-bold border-bottom pb-2 mb-3 mt-4">
                                    <i class="fas fa-award text-teal-600 mr-2"></i>Keahlian &amp; Pengalaman
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
                                <h5 class="text-teal-900 font-weight-bold border-bottom pb-2 mb-3 mt-4">
                                    <i class="fas fa-address-book text-teal-600 mr-2"></i>Kontak &amp; Alamat Domisili
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

                                <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                                    <a href="{{ route('guru.biodata') }}" class="btn btn-outline-secondary px-4 py-2 rounded-xl font-weight-semibold">
                                        <i class="fas fa-arrow-left mr-1"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-brand px-5 py-2.5 rounded-xl font-weight-bold shadow-sm">
                                        <i class="fas fa-save mr-2"></i> Simpan Biodata
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
