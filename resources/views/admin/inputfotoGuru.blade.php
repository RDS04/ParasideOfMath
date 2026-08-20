@extends('layout.app')

@section('title', 'Kelola Foto Guru & Banner Landing · Paradise of Math')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-purple-950 text-2xl tracking-tight">
                    <i class="fas fa-chalkboard-teacher text-indigo-600 mr-2"></i>Kelola Foto Guru &amp; Banner Landing
                </h1>
                <p class="text-xs text-muted mb-0">Upload foto banner slide untuk landing page dan update foto profil masing-masing tutor/guru.</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right text-xs bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600 font-semibold"><i class="fas fa-home mr-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active text-slate-500">Foto Guru &amp; Banner</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        {{-- Alerts Notifications --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert" style="background-color: #d1fae5; border-color: #a7f3d0; color: #065f46;">
                <h6 class="font-weight-bold mb-1"><i class="icon fas fa-check-circle mr-1"></i> Berhasil!</h6>
                <span class="text-xs">{{ session('success') }}</span>
                <button type="button" class="close text-emerald-900" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert">
                <h6 class="font-weight-bold mb-1"><i class="icon fas fa-exclamation-circle mr-1"></i> Gagal:</h6>
                <span class="text-xs">{{ session('error') }}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert">
                <h6 class="font-weight-bold mb-1"><i class="icon fas fa-exclamation-triangle mr-1"></i> Terjadi Kesalahan Input:</h6>
                <ul class="mb-0 text-xs pl-3">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            {{-- Section 1: Form Upload Foto Banner Landing --}}
            <div class="col-lg-5 mb-4">
                <div class="card border-0 shadow-sm rounded-2xl overflow-hidden">
                    <div class="card-header bg-gradient-to-r from-violet-900 via-indigo-900 to-violet-950 text-white py-3 px-4">
                        <h5 class="card-title font-weight-bold text-sm mb-0">
                            <i class="fas fa-image mr-1 text-amber-300"></i> Form Upload Foto Banner Landing
                        </h5>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                            Foto banner yang diunggah di sini akan otomatis tampil pada <strong>Banner Slider Tim Pengajar &amp; Sertifikat</strong> di Halaman Utama (Landing Page).
                        </p>

                        <form action="{{ route('admin.foto-guru.banner.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-4">
                                <label class="text-xs font-weight-bold text-slate-700 mb-2 block">
                                    Pilih File Foto Banner <span class="text-danger">*</span>
                                </label>
                                <div class="custom-file">
                                    <input type="file" name="foto_banner" class="custom-file-input text-xs" id="fotoBannerInput" accept="image/jpeg,image/png,image/jpg,image/webp" required>
                                    <label class="custom-file-label text-xs text-slate-500 overflow-hidden" for="fotoBannerInput" id="fotoBannerLabel">Pilih file gambar (JPG, PNG, WEBP max 5MB)...</label>
                                </div>
                                <small class="form-text text-muted text-[11px] mt-1">
                                    Rekomendasi rasio gambar lanskap (misal: 16:9 atau 21:9) untuk tampilan banner maksimal.
                                </small>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block font-weight-bold text-xs py-2.5 rounded-xl shadow-md transition-all">
                                <i class="fas fa-cloud-upload-alt mr-1"></i> Unggah Foto Banner
                            </button>
                        </form>
                    </div>
                </div>

                
            </div>

            {{-- Section 2: Upload Foto Profil Tutor / Guru --}}
            <div class="col-lg-7 mb-4">
              {{-- Daftar Foto Banner Yang Aktif --}}
                <div class="card border-0 shadow-sm rounded-2xl overflow-hidden mt-4">
                    <div class="card-header bg-slate-100 border-bottom py-3 px-4">
                        <h6 class="card-title font-weight-bold text-xs text-slate-800 mb-0">
                            <i class="fas fa-images mr-1 text-purple-600"></i> Foto Banner Aktif ({{ count($bannerFiles) }})
                        </h6>
                    </div>
                    <div class="card-body p-3 bg-slate-50/50">
                        @if(count($bannerFiles) > 0)
                            <div class="row gap-2">
                                @foreach($bannerFiles as $bf)
                                    <div class="col-12 mb-3">
                                        <div class="relative rounded-xl overflow-hidden border border-slate-200 shadow-sm bg-white p-2 flex items-center justify-between gap-3">
                                            <img src="{{ $bf['url'] }}" alt="Foto Banner {{ $bf['filename'] }}" class="w-24 h-16 object-cover rounded-lg shrink-0 border" />
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-weight-bold text-slate-800 truncate mb-0">{{ $bf['filename'] }}</p>
                                                <span class="badge badge-success text-[10px]">Tampil di Slider</span>
                                            </div>
                                            <form action="{{ route('admin.foto-guru.banner.delete', $bf['filename']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto banner ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-lg text-xs" title="Hapus Foto Banner">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-slate-400">
                                <i class="fas fa-image fa-2x mb-2 text-slate-300"></i>
                                <p class="text-xs mb-0">Belum ada foto banner custom diunggah.</p>
                                <span class="text-[11px] text-slate-400">Banner default sistem sedang aktif di landing page.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('fotoBannerInput');
        const label = document.getElementById('fotoBannerLabel');
        if (input && label) {
            input.addEventListener('change', function (e) {
                if (e.target.files && e.target.files.length > 0) {
                    label.innerText = e.target.files[0].name;
                }
            });
        }
    });
</script>
@endsection
