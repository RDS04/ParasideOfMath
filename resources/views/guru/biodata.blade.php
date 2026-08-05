@extends('layout.app')

@section('title', 'Profil & Biodata Pengajar · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-teal-950">Profil &amp; Biodata Saya</h1>
                    <p class="text-sm text-muted mb-0">Lihat kualifikasi akademik dan informasi profil publik Anda.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}" class="text-teal-600">Dashboard</a></li>
                        <li class="breadcrumb-item active">Profil &amp; Biodata</li>
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
                    <i class="fas fa-check-circle mr-2 text-lg align-middle"></i> 
                    <span class="align-middle font-medium text-sm">{{ session('success') }}</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #15803d; opacity: 0.8;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8 col-md-10 mx-auto mb-5">
                    
                    <!-- Main Profile Summary Card -->
                    <div class="card border-0 shadow-sm overflow-hidden mb-4 rounded-2xl">
                        <div class="card-body p-4 text-center text-teal-950" style="background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);">
                            <div class="position-relative d-inline-block mb-3">
                                @if($guruProfile->foto)
                                    <img src="{{ asset($guruProfile->foto) }}" class="rounded-circle shadow-md border border-white" style="width: 130px; height: 130px; object-fit: cover; border-width: 4px !important;" alt="Foto Profil">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0f766e&color=fff&size=130&bold=true" class="rounded-circle shadow-md border border-white" style="width: 130px; height: 130px; border-width: 4px !important;" alt="Avatar">
                                @endif
                            </div>
                            <h3 class="font-weight-extrabold text-teal-950 mb-1">
                                {{ $user->name }}{{ $guruProfile->gelar ? ', ' . $guruProfile->gelar : '' }}
                            </h3>
                            <span class="badge bg-teal-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-xs">
                                <i class="fas fa-certificate mr-1"></i> {{ $guruProfile->spesialisasi ?: 'Tutor Paradise of Math' }}
                            </span>
                            
                            @if($guruProfile->bio_singkat)
                                <p class="text-xs text-teal-800 italic mt-3 mb-0 max-w-lg mx-auto" style="line-height: 1.5;">
                                    "{{ $guruProfile->bio_singkat }}"
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Details Card -->
                    <div class="card border-0 shadow-sm rounded-2xl mb-4">
                        <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
                            <div class="p-2 rounded-xl bg-teal-50 text-teal-600 mr-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                <i class="fas fa-id-card-alt"></i>
                            </div>
                            <h5 class="font-weight-bold text-teal-950 mb-0">Rincian Informasi &amp; Kualifikasi</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row" style="row-gap: 1.25rem;">
                                <!-- Left Column: Biodata Akademik -->
                                <div class="col-md-6 border-right pr-md-4">
                                    <h6 class="font-weight-bold text-teal-900 mb-3 border-bottom pb-2 text-sm">
                                        <i class="fas fa-graduation-cap mr-2 text-teal-600"></i> Kualifikasi Akademik
                                    </h6>
                                    
                                    <div class="mb-3">
                                        <span class="text-xxs text-muted d-block uppercase font-bold tracking-wider">Nama Lengkap</span>
                                        <strong class="text-sm text-teal-950">{{ $user->name }}</strong>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-xxs text-muted d-block uppercase font-bold tracking-wider">Gelar Akademik</span>
                                        <strong class="text-sm text-teal-950">{{ $guruProfile->gelar ?: '-' }}</strong>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-xxs text-muted d-block uppercase font-bold tracking-wider">Pendidikan Terakhir</span>
                                        <strong class="text-sm text-teal-950">{{ $guruProfile->pendidikan_terakhir ?: '-' }}</strong>
                                    </div>
                                    <div class="mb-0">
                                        <span class="text-xxs text-muted d-block uppercase font-bold tracking-wider">Pengalaman Mengajar</span>
                                        <strong class="text-sm text-teal-950">{{ $guruProfile->pengalaman_mengajar ?: '-' }}</strong>
                                    </div>
                                </div>

                                <!-- Right Column: Kontak & Alamat -->
                                <div class="col-md-6 pl-md-4">
                                    <h6 class="font-weight-bold text-teal-900 mb-3 border-bottom pb-2 text-sm">
                                        <i class="fas fa-address-book mr-2 text-teal-600"></i> Kontak &amp; Domisili
                                    </h6>

                                    <div class="mb-3">
                                        <span class="text-xxs text-muted d-block uppercase font-bold tracking-wider">Spesialisasi</span>
                                        <strong class="text-sm text-teal-950">{{ $guruProfile->spesialisasi ?: '-' }}</strong>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-xxs text-muted d-block uppercase font-bold tracking-wider">Email Akun</span>
                                        <strong class="text-sm text-teal-950">{{ $user->email }}</strong>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-xxs text-muted d-block uppercase font-bold tracking-wider">No. WhatsApp / HP</span>
                                        <strong class="text-sm text-teal-950">{{ $guruProfile->no_telp ?: '-' }}</strong>
                                    </div>
                                    <div class="mb-0">
                                        <span class="text-xxs text-muted d-block uppercase font-bold tracking-wider">Alamat Domisili</span>
                                        <p class="text-sm text-teal-950 mb-0 leading-relaxed font-semibold">{{ $guruProfile->alamat ?: '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card border-0 shadow-sm rounded-2xl">
                        <div class="card-body p-3 d-flex flex-column sm:flex-row align-items-center justify-content-between gap-3 flex-wrap">
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('guru.dashboard') }}" class="btn btn-light btn-sm px-3 py-2 rounded-xl text-xs font-bold" style="color: #64748b;">
                                    <i class="fas fa-arrow-left mr-1"></i> Dashboard
                                </a>
                            </div>
                            <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                                <a href="{{ route('guru.profil') }}" class="btn btn-outline-teal px-4 py-2.5 rounded-xl font-weight-bold text-xs shadow-xs">
                                    <i class="fas fa-user-edit mr-1.5"></i> Edit Gambar, Nama &amp; Bio
                                </a>
                                <a href="{{ route('guru.biodata.edit') }}" class="btn btn-teal px-4 py-2.5 rounded-xl font-weight-bold text-xs text-white shadow-sm" style="background-color: #0f766e;">
                                    <i class="fas fa-id-card-alt mr-1.5"></i> Edit Biodata Akademik
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- Custom CSS styles matching the dashboard -->
    <style>
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
        .btn-teal {
            background: linear-gradient(135deg, #0d9488, #0f766e);
            border: none;
            transition: all 0.2s ease;
        }
        .btn-teal:hover {
            opacity: 0.92;
            transform: translateY(-1px);
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
        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
@endsection
