@extends('layout.app')

@section('title', 'Kelola Foto Landing Page · Paradise of Math')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-purple-950 text-2xl tracking-tight">Kelola Foto Landing Page (Slider Hero)</h1>
                <p class="text-xs text-muted mb-0">Upload multiple foto siswa/tutor. Foto akan otomatis berganti-ganti (slider) di halaman utama website.</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right text-xs bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600 font-semibold"><i class="fas fa-home mr-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active text-slate-500">Kelola Foto Hero</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
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
                <h6 class="font-weight-bold mb-1"><i class="icon fas fa-exclamation-triangle mr-1"></i> Terjadi Kesalahan:</h6>
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
            <!-- Left Column: Form Upload & Info (6 cols) -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm rounded-2xl overflow-hidden mb-4" style="border-radius: 20px;">
                    <div class="card-header text-white py-3 border-0" style="background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%);">
                        <h5 class="card-title font-weight-bold text-md mb-0 text-white">
                            <i class="fas fa-images text-amber-400 mr-2"></i> Form Upload Foto Slider (Bisa Banyak File)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.foto.hero.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-purple-950 text-xs d-block mb-1">
                                    Pilih Foto-Foto Baru (Siswa / Tutor):
                                </label>
                                <p class="text-xxs text-muted mb-3">Anda bisa memilih sekaligus 1 foto atau banyak foto (Multiple Select). Foto akan dimasukkan ke dalam daftar slider rotasi.</p>
                                
                                <div class="custom-file" style="height: 44px;">
                                    <input type="file" name="hero_images[]" class="custom-file-input" id="heroImagesInput" accept="image/jpeg,image/png,image/jpg,image/webp" multiple required>
                                    <label class="custom-file-label text-xs font-weight-semibold text-slate-600 rounded-xl d-flex align-items-center px-3" for="heroImagesInput" id="heroImagesLabel" style="height: 44px; border-radius: 12px; border-color: #ddd6fe;">
                                        Pilih 1 atau beberapa foto (JPG, PNG, WEBP)...
                                    </label>
                                </div>
                            </div>

                            <div class="p-3 rounded-xl bg-purple-50 border border-purple-100 mb-4">
                                <h6 class="font-weight-bold text-purple-950 text-xs mb-2">
                                    <i class="fas fa-info-circle text-purple-600 mr-1"></i> Ketentuan Foto Slider:
                                </h6>
                                <ul class="text-xxs text-slate-600 pl-3 mb-0 space-y-1">
                                    <li>Format file: <strong>JPG, JPEG, PNG, atau WEBP</strong>.</li>
                                    <li>Ukuran per file maksimal: <strong>5 MB</strong>.</li>
                                    <li>Jika terdapat lebih dari 1 foto, sistem di landing page akan <strong>otomatis berganti foto secara smooth</strong> setiap 3.5 detik.</li>
                                </ul>
                            </div>

                            <button type="submit" class="btn btn-primary rounded-xl font-weight-bold px-4 py-2.5 text-xs shadow-sm w-100" style="background-color: #7c3aed; border-color: #7c3aed; border-radius: 12px;">
                                <i class="fas fa-plus-circle mr-1.5"></i> Tambahkan Foto ke Slider
                            </button>
                        </form>
                    </div>
                </div>

                <!-- DAFTAR FOTO YANG SUDAH DI-UPLOAD -->
                <div class="card border-0 shadow-sm rounded-2xl overflow-hidden" style="border-radius: 20px;">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <h5 class="card-title font-weight-bold text-purple-950 text-sm mb-0">
                            <i class="fas fa-list text-purple-600 mr-2"></i> Daftar Foto Ter-upload ({{ count($heroImages) }})
                        </h5>
                        <span class="badge bg-amber-100 text-amber-900 text-[10px] font-bold px-2.5 py-1 rounded-full">Aktif di Slider</span>
                    </div>
                    <div class="card-body p-3" style="max-height: 380px; overflow-y: auto;">
                        @if(count($heroImages) > 0)
                            <div class="space-y-3">
                                @foreach($heroImages as $index => $img)
                                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-xl border border-slate-100 bg-slate-50/80 hover:bg-purple-50/50 transition-colors">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-lg overflow-hidden border border-purple-200" style="width: 50px; height: 50px; flex-shrink: 0; background-color: #2e1065;">
                                                <img src="{{ $img['url'] }}" alt="Hero Image" class="w-100 h-100" style="object-fit: cover;">
                                            </div>
                                            <div>
                                                <p class="font-weight-bold text-purple-950 text-xs mb-0 text-truncate" style="max-width: 200px;">
                                                    Foto #{{ count($heroImages) - $index }}
                                                </p>
                                                <p class="text-slate-400 font-mono mb-0" style="font-size: 10px;">
                                                    {{ $img['filename'] }} ({{ $img['size'] }})
                                                </p>
                                            </div>
                                        </div>
                                        <div>
                                            <form action="{{ route('admin.foto.hero.delete.single', $img['filename']) }}" method="POST" onsubmit="return confirm('Hapus foto ini dari daftar slider?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-lg p-2 text-xs" title="Hapus Foto">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-image fa-2x mb-2 text-slate-300"></i>
                                <p class="text-xs mb-0 font-weight-bold">Belum ada foto slider yang di-upload.</p>
                                <p class="text-xxs text-slate-400">Silakan upload foto pertama menggunakan form di atas.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Live Mockup Preview with Auto-Slider Simulation (6 cols) -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm rounded-2xl overflow-hidden h-100" style="border-radius: 20px;">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <h5 class="card-title font-weight-bold text-purple-950 mb-0">
                            <i class="fas fa-eye text-teal-500 mr-2"></i> Live Preview Auto-Rotasi Slider
                        </h5>
                        <span class="badge bg-purple-100 text-purple-800 text-[10px] font-bold px-2.5 py-1 rounded-full">Pratinjau Slider Realtime</span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-center align-items-center" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 50%, #4c1d95 100%); min-height: 480px;">
                        
                        <!-- MOCKUP CONTAINER MATCHING LANDING PAGE HERO CARD EXACTLY -->
                        <div class="position-relative" style="width: 100%; max-width: 320px;">
                            
                            <!-- Top Right Floating Badge: Top Ranking -->
                            <div class="position-absolute shadow-lg bg-white rounded-2xl p-2.5 d-flex align-items-center gap-2" style="top: -15px; right: -15px; z-index: 20; border: 1px solid #fef08a; border-radius: 16px;">
                                <div class="bg-amber-400 text-purple-950 font-bold rounded-lg d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 16px;">
                                    🏆
                                </div>
                                <div class="text-left">
                                    <p class="font-weight-extrabold text-purple-950 text-xs mb-0" style="font-size: 11px; color: #2e1065;">Top Ranking</p>
                                    <p class="text-slate-500 mb-0" style="font-size: 9px;">Bimbel No.1</p>
                                </div>
                            </div>

                            <!-- Main Hero Image Frame Card -->
                            <div class="position-relative rounded-3xl overflow-hidden shadow-2xl p-2 text-center d-flex flex-column align-items-center justify-content-center" id="previewFrame" style="background: linear-gradient(180deg, rgba(91, 33, 182, 0.95) 0%, rgba(46, 16, 101, 0.98) 100%); border: 2px solid rgba(255, 255, 255, 0.2); border-radius: 24px; min-height: 380px; width: 100%;">
                                
                                @if(count($heroImages) > 0)
                                    <div class="position-relative overflow-hidden rounded-2xl" id="mockupSliderContainer" style="width: 100%; height: 360px;">
                                        @foreach($heroImages as $idx => $img)
                                            <img src="{{ $img['url'] }}" alt="Slider Image {{ $idx }}" 
                                                 class="mockup-slider-item shadow-sm" 
                                                 style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 20px; transition: opacity 0.8s ease-in-out; opacity: {{ $idx === 0 ? '1' : '0' }}; z-index: {{ $idx === 0 ? '10' : '1' }};">
                                        @endforeach
                                    </div>
                                    <!-- Indicator Dots -->
                                    <div class="position-absolute d-flex gap-1.5 justify-content-center w-100" style="bottom: 15px; left: 0; z-index: 25;" id="mockupDots">
                                        @foreach($heroImages as $idx => $img)
                                            <span class="mockup-dot rounded-circle bg-white shadow-sm" style="width: 8px; height: 8px; opacity: {{ $idx === 0 ? '1' : '0.4' }}; transition: all 0.3s ease; display: inline-block; margin: 0 3px;"></span>
                                        @endforeach
                                    </div>
                                @else
                                    <div id="placeholderPreview" class="h-100 w-100 d-flex flex-column justify-content-center align-items-center py-5 text-white">
                                        <div class="rounded-circle bg-purple-600/50 d-flex justify-content-center align-items-center mb-3 border border-purple-400/40 text-amber-300 mx-auto" style="width: 70px; height: 70px;">
                                            <i class="fas fa-graduation-cap fa-2x text-amber-300"></i>
                                        </div>
                                        <span class="font-weight-bold text-xs text-purple-100 px-3">[ foto siswa/tutor ]</span>
                                        <span class="text-purple-300/70 text-xxs font-mono mt-2">public/images/hero-student.jpg</span>
                                    </div>
                                @endif

                            </div>

                            <!-- Bottom Left Floating Badge: Tutor Stand By -->
                            <div class="position-absolute shadow-lg bg-purple-900/90 text-white rounded-2xl p-2.5 d-flex align-items-center gap-2 backdrop-blur-md" style="bottom: -15px; left: -15px; z-index: 20; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 16px; background-color: rgba(67, 24, 138, 0.92);">
                                <div class="bg-amber-400 text-purple-950 font-bold rounded-lg d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 16px;">
                                    💡
                                </div>
                                <div class="text-left">
                                    <p class="font-weight-bold text-white text-xs mb-0" style="font-size: 11px;">Tutor Stand By</p>
                                    <p class="text-purple-200 mb-0" style="font-size: 9px;">1-on-1 Mentoring</p>
                                </div>
                            </div>

                        </div>

                        <p class="text-purple-200/80 text-xxs font-medium mt-4 mb-0">
                            <i class="fas fa-sync-alt fa-spin text-amber-400 mr-1"></i> Foto di atas berganti secara otomatis tiap 3.5 detik (Persis Tampilan Landing Page)
                        </p>

                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- JS Live Multi-Upload Label & Auto-Slider Preview -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Multi-file label updater
        const fileInput = document.getElementById('heroImagesInput');
        const fileLabel = document.getElementById('heroImagesLabel');

        if (fileInput && fileLabel) {
            fileInput.addEventListener('change', function(e) {
                const count = e.target.files.length;
                if (count === 1) {
                    fileLabel.textContent = e.target.files[0].name;
                } else if (count > 1) {
                    fileLabel.textContent = count + ' foto dipilih untuk di-upload';
                }
            });
        }

        // Live Mockup Auto-Slider
        const items = document.querySelectorAll('.mockup-slider-item');
        const dots = document.querySelectorAll('.mockup-dot');
        if (items.length > 1) {
            let currentIndex = 0;
            setInterval(function() {
                // hide current
                items[currentIndex].style.opacity = '0';
                items[currentIndex].style.zIndex = '1';
                if(dots[currentIndex]) dots[currentIndex].style.opacity = '0.4';

                // next index
                currentIndex = (currentIndex + 1) % items.length;

                // show next
                items[currentIndex].style.opacity = '1';
                items[currentIndex].style.zIndex = '10';
                if(dots[currentIndex]) dots[currentIndex].style.opacity = '1';
            }, 3500);
        }
    });
</script>
@endsection
