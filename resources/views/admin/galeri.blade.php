@extends('layout.app')

@section('title', 'Kelola Foto Fasilitas & Galeri · Paradise of Math')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-purple-950 text-2xl tracking-tight">Kelola Foto Fasilitas &amp; Galeri</h1>
                <p class="text-xs text-muted mb-0">Upload dan kelola foto-foto fasilitas (Kelas, Toilet, Mushala, Gedung Bimbel) yang tampil di bagian Fasilitas Landing Page.</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right text-xs bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600 font-semibold"><i class="fas fa-home mr-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active text-slate-500">Kelola Foto Fasilitas</li>
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

        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-2xl overflow-hidden" style="border-radius: 20px;">
                    <div class="card-header text-white py-3 border-0" style="background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%);">
                        <h6 class="card-title font-weight-bold text-sm mb-0 text-white">
                            <i class="fas fa-plus-circle text-amber-400 mr-2"></i> Tambah Foto Galeri Tambahan
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-lg-7 mb-3 mb-lg-0">
                                <p class="text-xs text-slate-600 mb-2">
                                    Upload lebih banyak foto fasilitas, kegiatan, atau suasana belajar. Foto ini akan muncul di tombol <strong>Lihat Semua Foto</strong> pada halaman landing.
                                </p>
                                <form action="{{ route('admin.galeri.extra.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-purple-950 mb-1 d-block">Pilih Foto Tambahan:</label>
                                        <div class="custom-file" style="height: 38px;">
                                            <input type="file" name="gallery_images[]" class="custom-file-input galeri-input galeri-extra-input" multiple required>
                                            <label class="custom-file-label text-xs font-weight-semibold text-slate-600 rounded-xl px-2" style="height: 38px; border-radius: 10px;">Upload beberapa foto sekaligus...</label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-sm btn-primary font-weight-bold text-xs rounded-xl px-3" style="background-color: #7c3aed; border-color: #7c3aed; border-radius: 10px;">
                                        <i class="fas fa-plus mr-1"></i> Tambah Foto
                                    </button>
                                </form>
                            </div>

                            <div class="col-lg-5">
                                <div class="rounded-2xl bg-purple-50 border border-purple-100 p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h6 class="font-weight-bold text-sm text-purple-950 mb-0">Foto Galeri Tambahan</h6>
                                        <span class="badge badge-pill" style="background-color: #7c3aed; color: #fff;">{{ isset($galleryExtras) ? count($galleryExtras) : 0 }} foto</span>
                                    </div>
                                    <p class="text-xxs text-slate-500 mb-0">Klik hapus untuk mengeluarkan foto dari tampilan “lihat semua”.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-top">
                            @if(!empty($galleryExtras))
                                <div class="row">
                                    @foreach($galleryExtras as $photo)
                                        <div class="col-6 col-md-4 col-lg-3 mb-3">
                                            <div class="card border-0 shadow-sm h-100 overflow-hidden" style="border-radius: 16px;">
                                                <div style="height: 180px; background-color: #2e1065; overflow: hidden;">
                                                    <img src="{{ $photo['url'] }}" alt="{{ $photo['filename'] }}" class="w-100 h-100" style="object-fit: cover;">
                                                </div>
                                                <div class="card-body p-3">
                                                    <p class="text-xs font-weight-bold text-purple-950 mb-2 text-truncate" title="{{ $photo['filename'] }}">{{ $photo['filename'] }}</p>
                                                    <p class="text-xxs text-slate-500 mb-3">{{ $photo['size'] }}</p>
                                                    <form action="{{ route('admin.galeri.delete') }}" method="POST" onsubmit="return confirm('Hapus foto galeri ini?')">
                                                        @csrf
                                                        <input type="hidden" name="key" value="galeri_extra">
                                                        <input type="hidden" name="filename" value="{{ $photo['filename'] }}">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger btn-block text-xs font-weight-bold rounded-lg">
                                                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-images text-slate-300 fa-2x mb-2"></i>
                                    <p class="text-sm text-slate-500 mb-0">Belum ada foto galeri tambahan.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            
            <!-- CARD 1: RUANG KELAS -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm rounded-2xl overflow-hidden h-100" style="border-radius: 20px;">
                    <div class="card-header text-white py-3 border-0" style="background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%);">
                        <h6 class="card-title font-weight-bold text-sm mb-0 text-white">
                            <i class="fas fa-chalkboard text-amber-400 mr-2"></i> Ruang Kelas Ber-AC &amp; WiFi
                        </h6>
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <!-- Mockup Container -->
                            <div class="position-relative rounded-2xl overflow-hidden mb-3 shadow-sm border border-purple-200" style="height: 200px; background-color: #2e1065;">
                                @if(isset($galeri['kelas']) && $galeri['kelas'])
                                    <img id="prev_kelas" src="{{ $galeri['kelas'] }}" class="w-100 h-100" style="object-fit: cover;" alt="Ruang Kelas">
                                @else
                                    <div id="holder_kelas" class="h-100 w-100 d-flex flex-column justify-content-center align-items-center text-white text-center p-3">
                                        <i class="fas fa-laptop-house fa-2x text-amber-300 mb-2"></i>
                                        <span class="text-xs font-bold">[ foto kelas ber-AC &amp; WiFi ]</span>
                                        <span class="text-xxs text-purple-300 font-mono mt-1">public/images/fasilitas-kelas.jpg</span>
                                    </div>
                                    <img id="prev_kelas" src="" class="w-100 h-100 d-none" style="object-fit: cover;" alt="Ruang Kelas">
                                @endif
                                <div class="position-absolute bg-purple-950 text-amber-300 font-weight-bold text-xxs px-2.5 py-1 rounded-lg" style="top: 8px; left: 8px; border: 1px solid rgba(253, 224, 71, 0.4);">
                                    + AC &amp; WiFi
                                </div>
                            </div>

                            <form action="{{ route('admin.galeri.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="key" value="kelas">
                                
                                <div class="form-group mb-3">
                                    <label class="text-xs font-weight-bold text-purple-950 mb-1 d-block">Pilih Foto Kelas Baru:</label>
                                    <div class="custom-file" style="height: 38px;">
                                        <input type="file" name="image" class="custom-file-input galeri-input" data-target="prev_kelas" data-holder="holder_kelas" required>
                                        <label class="custom-file-label text-xs font-weight-semibold text-slate-600 rounded-xl px-2" style="height: 38px; border-radius: 10px;">Upload Foto Kelas...</label>
                                    </div>
                                </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 mt-3 pt-3 border-top">
                            <button type="submit" class="btn btn-sm btn-primary font-weight-bold text-xs rounded-xl px-3" style="background-color: #7c3aed; border-color: #7c3aed; border-radius: 10px;">
                                <i class="fas fa-upload mr-1"></i> Simpan Kelas
                            </button>
                            </form>
                            @if(isset($galeri['kelas']) && $galeri['kelas'])
                                <form action="{{ route('admin.galeri.delete') }}" method="POST" onsubmit="return confirm('Reset foto kelas ke default?')">
                                    @csrf
                                    <input type="hidden" name="key" value="kelas">
                                    <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold text-xs rounded-xl px-2.5" style="border-radius: 10px;">
                                        <i class="fas fa-trash-alt"></i> Reset
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD 2: TOILET BERSIH -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm rounded-2xl overflow-hidden h-100" style="border-radius: 20px;">
                    <div class="card-header text-white py-3 border-0" style="background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%);">
                        <h6 class="card-title font-weight-bold text-sm mb-0 text-white">
                            <i class="fas fa-restroom text-amber-400 mr-2"></i> Toilet Bersih &amp; Higienis
                        </h6>
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <!-- Mockup Container -->
                            <div class="position-relative rounded-2xl overflow-hidden mb-3 shadow-sm border border-purple-200" style="height: 200px; background-color: #2e1065;">
                                @if(isset($galeri['toilet']) && $galeri['toilet'])
                                    <img id="prev_toilet" src="{{ $galeri['toilet'] }}" class="w-100 h-100" style="object-fit: cover;" alt="Toilet">
                                @else
                                    <div id="holder_toilet" class="h-100 w-100 d-flex flex-column justify-content-center align-items-center text-white text-center p-3">
                                        <i class="fas fa-toilet fa-2x text-amber-300 mb-2"></i>
                                        <span class="text-xs font-bold">[ foto toilet ]</span>
                                        <span class="text-xxs text-purple-300 font-mono mt-1">public/images/fasilitas-toilet.jpg</span>
                                    </div>
                                    <img id="prev_toilet" src="" class="w-100 h-100 d-none" style="object-fit: cover;" alt="Toilet">
                                @endif
                                <div class="position-absolute bg-amber-400 text-purple-950 font-weight-bold text-xxs px-2.5 py-1 rounded-lg" style="top: 8px; left: 8px;">
                                    Toilet Bersih
                                </div>
                            </div>

                            <form action="{{ route('admin.galeri.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="key" value="toilet">
                                
                                <div class="form-group mb-3">
                                    <label class="text-xs font-weight-bold text-purple-950 mb-1 d-block">Pilih Foto Toilet Baru:</label>
                                    <div class="custom-file" style="height: 38px;">
                                        <input type="file" name="image" class="custom-file-input galeri-input" data-target="prev_toilet" data-holder="holder_toilet" required>
                                        <label class="custom-file-label text-xs font-weight-semibold text-slate-600 rounded-xl px-2" style="height: 38px; border-radius: 10px;">Upload Foto Toilet...</label>
                                    </div>
                                </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 mt-3 pt-3 border-top">
                            <button type="submit" class="btn btn-sm btn-primary font-weight-bold text-xs rounded-xl px-3" style="background-color: #7c3aed; border-color: #7c3aed; border-radius: 10px;">
                                <i class="fas fa-upload mr-1"></i> Simpan Toilet
                            </button>
                            </form>
                            @if(isset($galeri['toilet']) && $galeri['toilet'])
                                <form action="{{ route('admin.galeri.delete') }}" method="POST" onsubmit="return confirm('Reset foto toilet ke default?')">
                                    @csrf
                                    <input type="hidden" name="key" value="toilet">
                                    <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold text-xs rounded-xl px-2.5" style="border-radius: 10px;">
                                        <i class="fas fa-trash-alt"></i> Reset
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD 3: MUSHALA LUAS -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm rounded-2xl overflow-hidden h-100" style="border-radius: 20px;">
                    <div class="card-header text-white py-3 border-0" style="background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%);">
                        <h6 class="card-title font-weight-bold text-sm mb-0 text-white">
                            <i class="fas fa-mosque text-amber-400 mr-2"></i> Mushala Luas &amp; Suci
                        </h6>
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <!-- Mockup Container -->
                            <div class="position-relative rounded-2xl overflow-hidden mb-3 shadow-sm border border-purple-200" style="height: 200px; background-color: #2e1065;">
                                @if(isset($galeri['mushala']) && $galeri['mushala'])
                                    <img id="prev_mushala" src="{{ $galeri['mushala'] }}" class="w-100 h-100" style="object-fit: cover;" alt="Mushala">
                                @else
                                    <div id="holder_mushala" class="h-100 w-100 d-flex flex-column justify-content-center align-items-center text-white text-center p-3">
                                        <i class="fas fa-kaaba fa-2x text-amber-300 mb-2"></i>
                                        <span class="text-xs font-bold">[ foto mushala ]</span>
                                        <span class="text-xxs text-purple-300 font-mono mt-1">public/images/fasilitas-mushala.jpg</span>
                                    </div>
                                    <img id="prev_mushala" src="" class="w-100 h-100 d-none" style="object-fit: cover;" alt="Mushala">
                                @endif
                                <div class="position-absolute bg-amber-400 text-purple-950 font-weight-bold text-xxs px-2.5 py-1 rounded-lg" style="top: 8px; left: 8px;">
                                    Mushala Luas
                                </div>
                            </div>

                            <form action="{{ route('admin.galeri.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="key" value="mushala">
                                
                                <div class="form-group mb-3">
                                    <label class="text-xs font-weight-bold text-purple-950 mb-1 d-block">Pilih Foto Mushala Baru:</label>
                                    <div class="custom-file" style="height: 38px;">
                                        <input type="file" name="image" class="custom-file-input galeri-input" data-target="prev_mushala" data-holder="holder_mushala" required>
                                        <label class="custom-file-label text-xs font-weight-semibold text-slate-600 rounded-xl px-2" style="height: 38px; border-radius: 10px;">Upload Foto Mushala...</label>
                                    </div>
                                </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 mt-3 pt-3 border-top">
                            <button type="submit" class="btn btn-sm btn-primary font-weight-bold text-xs rounded-xl px-3" style="background-color: #7c3aed; border-color: #7c3aed; border-radius: 10px;">
                                <i class="fas fa-upload mr-1"></i> Simpan Mushala
                            </button>
                            </form>
                            @if(isset($galeri['mushala']) && $galeri['mushala'])
                                <form action="{{ route('admin.galeri.delete') }}" method="POST" onsubmit="return confirm('Reset foto mushala ke default?')">
                                    @csrf
                                    <input type="hidden" name="key" value="mushala">
                                    <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold text-xs rounded-xl px-2.5" style="border-radius: 10px;">
                                        <i class="fas fa-trash-alt"></i> Reset
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD 4: GEDUNG / RUMAH BIMBEL (LINGKUNGAN BELAJAR) -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm rounded-2xl overflow-hidden h-100" style="border-radius: 20px;">
                    <div class="card-header text-white py-3 border-0" style="background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%);">
                        <h6 class="card-title font-weight-bold text-sm mb-0 text-white">
                            <i class="fas fa-building text-amber-400 mr-2"></i> Gedung / Rumah Bimbel (Lingkungan Belajar)
                        </h6>
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <!-- Mockup Container -->
                            <div class="position-relative rounded-2xl overflow-hidden mb-3 shadow-sm border border-purple-200" style="height: 200px; background-color: #2e1065;">
                                @if(isset($galeri['gedung']) && $galeri['gedung'])
                                    <img id="prev_gedung" src="{{ $galeri['gedung'] }}" class="w-100 h-100" style="object-fit: cover;" alt="Gedung Bimbel">
                                @else
                                    <div id="holder_gedung" class="h-100 w-100 d-flex flex-column justify-content-center align-items-center text-white text-center p-3">
                                        <i class="fas fa-home fa-2x text-amber-300 mb-2"></i>
                                        <span class="text-xs font-bold">[ foto gedung/rumah bimbel ]</span>
                                        <span class="text-xxs text-purple-300 font-mono mt-1">public/images/fasilitas-gedung.jpg</span>
                                    </div>
                                    <img id="prev_gedung" src="" class="w-100 h-100 d-none" style="object-fit: cover;" alt="Gedung Bimbel">
                                @endif
                                <div class="position-absolute bg-amber-400 text-purple-950 font-weight-bold text-xxs px-2.5 py-1 rounded-lg" style="top: 8px; left: 8px;">
                                    💡 Kenyamanan 100%
                                </div>
                            </div>

                            <form action="{{ route('admin.galeri.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="key" value="gedung">
                                
                                <div class="form-group mb-3">
                                    <label class="text-xs font-weight-bold text-purple-950 mb-1 d-block">Pilih Foto Gedung / Rumah Baru:</label>
                                    <div class="custom-file" style="height: 38px;">
                                        <input type="file" name="image" class="custom-file-input galeri-input" data-target="prev_gedung" data-holder="holder_gedung" required>
                                        <label class="custom-file-label text-xs font-weight-semibold text-slate-600 rounded-xl px-2" style="height: 38px; border-radius: 10px;">Upload Foto Gedung...</label>
                                    </div>
                                </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 mt-3 pt-3 border-top">
                            <button type="submit" class="btn btn-sm btn-primary font-weight-bold text-xs rounded-xl px-3" style="background-color: #7c3aed; border-color: #7c3aed; border-radius: 10px;">
                                <i class="fas fa-upload mr-1"></i> Simpan Gedung
                            </button>
                            </form>
                            @if(isset($galeri['gedung']) && $galeri['gedung'])
                                <form action="{{ route('admin.galeri.delete') }}" method="POST" onsubmit="return confirm('Reset foto gedung ke default?')">
                                    @csrf
                                    <input type="hidden" name="key" value="gedung">
                                    <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold text-xs rounded-xl px-2.5" style="border-radius: 10px;">
                                        <i class="fas fa-trash-alt"></i> Reset
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- JS Client-side Image Preview -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.galeri-input, .galeri-extra-input');
        inputs.forEach(input => {
            input.addEventListener('change', function(e) {
                const targetId = this.getAttribute('data-target');
                const holderId = this.getAttribute('data-holder');
                const targetImg = document.getElementById(targetId);
                const holderEl  = document.getElementById(holderId);

                const files = Array.from(e.target.files || []);
                if (files.length) {
                    const label = this.nextElementSibling;
                    if (label) {
                        label.textContent = files.length > 1 ? `${files.length} foto dipilih` : files[0].name;
                    }

                    const file = files[0];
                    if (file && targetImg) {
                        const reader = new FileReader();
                        reader.onload = function(evt) {
                            targetImg.src = evt.target.result;
                            targetImg.classList.remove('d-none');
                            if (holderEl) {
                                holderEl.classList.add('d-none');
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
        });
    });
</script>
@endsection
