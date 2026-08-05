@extends('layout.app')

@section('title', 'Lengkapi Profil Pengajar · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-teal-950">Profil Pengajar</h1>
                    <p class="text-sm text-muted mb-0">Lengkapi data diri dan foto profil Anda agar siswa dapat mengenal kualifikasi mengajar Anda.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}" class="text-teal-600">Dashboard</a></li>
                        <li class="breadcrumb-item active">Profil Guru</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-xl shadow-sm border-0 mb-4" role="alert" style="background-color: #f0fdf4; color: #15803d; border-left: 5px solid #22c55e !important;">
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
                    <div class="card border-0 shadow-sm overflow-hidden mb-5" style="border-radius: 20px;">
                        <div class="card-header p-4" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); color: white;">
                            <div class="d-flex align-items-center">
                                <div class="bg-amber-400 text-teal-950 rounded-circle d-flex align-items-center justify-content-center mr-3 font-weight-bold" style="width: 48px; height: 48px; font-size: 1.25rem; background-color: #fbbf24 !important;">
                                    <i class="fas fa-id-card-alt"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1 font-weight-bold">Formulir Profil &amp; Biodata Guru</h4>
                                    <p class="text-teal-100 text-xs mb-0">Kelola informasi publik dan foto identitas resmi Anda.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4 bg-white">
                            <form action="{{ route('guru.profil.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- ── SEC 0: FOTO PROFIL GURU ── -->
                                <h5 class="text-teal-900 font-weight-bold border-bottom pb-2 mb-3">
                                    <i class="fas fa-camera text-teal-600 mr-2"></i>Foto Profil Pengajar
                                </h5>

                                <div class="row align-items-center mb-4 bg-light/30 p-3 rounded-xl border border-light">
                                    <div class="col-md-3 text-center mb-3 mb-md-0">
                                        <div class="position-relative d-inline-block">
                                            <label for="foto" class="position-relative d-inline-block mb-0" style="cursor: pointer;" title="Klik untuk mengubah foto">
                                                @if($guruProfile->foto)
                                                    <img id="avatarPreview" src="{{ asset($guruProfile->foto) }}" class="rounded-circle img-thumbnail shadow-sm" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #0f766e;" alt="Foto Profil">
                                                @else
                                                    <img id="avatarPreview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0f766e&color=fff&size=120&bold=true" class="rounded-circle img-thumbnail shadow-sm" style="width: 120px; height: 120px; object-fit: cover;" alt="Avatar">
                                                @endif
                                                <!-- Overlay Camera -->
                                                <div class="avatar-overlay">
                                                    <i class="fas fa-camera text-white text-lg"></i>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group mb-0">
                                            <label class="font-weight-bold text-teal-950 text-sm mb-1 d-block">Foto Profil</label>
                                            <p class="text-xs text-muted mb-3" style="font-family: 'Inter', sans-serif;">Format: JPG, JPEG, PNG, WEBP. Ukuran Maksimal 2 MB.</p>
                                            
                                            <!-- Styled Upload Button -->
                                            <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                                                <label for="foto" class="btn btn-outline-teal btn-sm px-4 py-2.5 rounded-xl font-weight-bold mb-0 align-middle shadow-xs" style="cursor: pointer;">
                                                    <i class="fas fa-cloud-upload-alt mr-2 text-sm"></i> Pilih File Foto
                                                </label>
                                                <!-- File Name Indicator -->
                                                <span id="fileNameLabel" class="text-xs text-muted font-italic">Belum ada file dipilih</span>
                                            </div>
                                            
                                            <!-- Hidden File Input -->
                                            <input type="file" class="d-none @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*">
                                            
                                            @error('foto')
                                                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- ── SEC 1: IDENTITAS UTAMA ── -->
                                <h5 class="text-teal-900 font-weight-bold border-bottom pb-2 mb-3 mt-4">
                                    <i class="fas fa-user text-teal-600 mr-2"></i>Identitas Pengajar
                                </h5>

                                <div class="form-group mb-4">
                                    <label for="name" class="font-weight-semibold text-sm">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Contoh: Budi Santoso" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- ── SEC 2: BIO RINGKAS ── -->
                                <h5 class="text-teal-900 font-weight-bold border-bottom pb-2 mb-3 mt-4">
                                    <i class="fas fa-comment-alt text-teal-600 mr-2"></i>Perkenalan / Deskripsi Singkat
                                </h5>

                                <div class="form-group mb-4">
                                    <label for="bio_singkat" class="font-weight-semibold text-sm">Bio / Catatan untuk Siswa</label>
                                    <textarea class="form-control rounded-lg @error('bio_singkat') is-invalid @enderror" id="bio_singkat" name="bio_singkat" rows="4" placeholder="Ceritakan singkat metode mengajar Anda atau pesan hangat untuk siswa bimbingan Anda...">{{ old('bio_singkat', $guruProfile->bio_singkat) }}</textarea>
                                    <small class="form-text text-muted">Deskripsi ini akan membantu siswa merasa nyaman dan mengenal pendekatan mengajar Anda.</small>
                                </div>

                                <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                                    <a href="{{ route('guru.biodata') }}" class="btn btn-outline-secondary px-4 py-2 rounded-xl font-weight-semibold">
                                        <i class="fas fa-arrow-left mr-1"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-brand px-5 py-2.5 rounded-xl font-weight-bold shadow-sm">
                                        <i class="fas fa-save mr-2"></i> Simpan Profil
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- JS Client-side Photo Preview -->
    <script>
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Update file name label
                document.getElementById('fileNameLabel').textContent = file.name;
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('avatarPreview').src = event.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
    <style>
        .avatar-overlay {
            position: absolute;
            top: 4px;
            left: 4px;
            width: 120px;
            height: 120px;
            background: rgba(0, 0, 0, 0.45);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.25s ease;
        }
        label[for="foto"]:hover .avatar-overlay {
            opacity: 1;
        }
        .btn-outline-teal {
            border: 1px solid #0f766e;
            color: #0f766e;
            background: #fff;
            transition: all 0.2s ease;
        }
        .btn-outline-teal:hover {
            background-color: #ecfdf5;
            color: #0d9488;
            border-color: #0d9488;
        }
        .btn-brand {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border: none;
            color: #40206b;
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .btn-brand:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            color: #40206b;
        }
        .rounded-2xl {
            border-radius: 20px !important;
        }
        .rounded-xl {
            border-radius: 12px !important;
        }
        .shadow-xs {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection
