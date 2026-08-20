@extends('layout.app')

@section('title', 'Kelola Link YouTube Tutorial · Paradise of Math')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-purple-950 text-2xl tracking-tight">
                    <i class="fab fa-youtube text-red-600 mr-2"></i>Kelola Link YouTube Tutorial
                </h1>
                <p class="text-xs text-muted mb-0">Tambah, edit, dan atur video tutorial YouTube yang tampil di halaman depan (landing page).</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right text-xs bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600 font-semibold"><i class="fas fa-home mr-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active text-slate-500">Kelola Link YouTube</li>
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

        <div class="row">
            <!-- Form Input Link Baru -->
            <div class="col-lg-4 mb-4">
                <div class="card card-purple card-outline shadow-sm rounded-xl overflow-hidden border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h3 class="card-title font-weight-bold text-sm text-purple-950 mb-0">
                            <i class="fas fa-plus-circle text-purple-600 mr-1.5"></i> Tambah Video YouTube Baru
                        </h3>
                    </div>
                    <form action="{{ route('admin.link.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-slate-700">Judul Video Tutorial <span class="text-danger">*</span></label>
                                <input type="text" name="judul" class="form-control form-control-sm text-xs rounded-lg" placeholder="Contoh: Trik Cepat Hitung Aljabar" required />
                            </div>

                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-slate-700">URL / Link YouTube <span class="text-danger">*</span></label>
                                <input type="url" name="youtube_url" class="form-control form-control-sm text-xs rounded-lg" placeholder="https://www.youtube.com/watch?v=XXXXX" required />
                                <small class="text-muted text-[11px]">Bisa memasukkan link full YouTube, link pendek (youtu.be), atau ID video.</small>
                            </div>

                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-slate-700">Kategori Video</label>
                                <select name="kategori" class="form-control form-control-sm text-xs rounded-lg">
                                    <option value="Trik Hitung">Trik Hitung</option>
                                    <option value="Aljabar & Bangun Ruang">Aljabar &amp; Bangun Ruang</option>
                                    <option value="Bedah Soal">Bedah Soal Ujian</option>
                                    <option value="Tips Belajar">Tips Belajar &amp; Motivasi</option>
                                    <option value="Umum">Umum / Tutorial</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-slate-700">Urutan Tampil</label>
                                <input type="number" name="urutan" class="form-control form-control-sm text-xs rounded-lg" value="1" min="1" />
                            </div>

                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-slate-700">Deskripsi Singkat</label>
                                <textarea name="deskripsi" rows="3" class="form-control form-control-sm text-xs rounded-lg" placeholder="Penjelasan singkat mengenai isi video..."></textarea>
                            </div>
                        </div>
                        <div class="card-footer bg-slate-50 border-top py-3 text-right">
                            <button type="submit" class="btn btn-primary btn-sm px-4 font-weight-bold rounded-lg shadow-sm" style="background-color: #6b21a8; border-color: #581c87;">
                                <i class="fas fa-save mr-1"></i> Simpan Video
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Daftar Link YouTube -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm rounded-xl overflow-hidden border-0">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h3 class="card-title font-weight-bold text-sm text-purple-950 mb-0">
                            <i class="fab fa-youtube text-red-600 mr-1.5"></i> Daftar Video YouTube Terdaftar ({{ $youtubeLinks->count() }})
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        @if($youtubeLinks->isEmpty())
                            <div class="text-center py-5">
                                <i class="fab fa-youtube text-slate-300 text-5xl mb-3"></i>
                                <p class="text-slate-500 font-semibold text-sm mb-1">Belum ada video YouTube yang ditambahkan.</p>
                                <p class="text-slate-400 text-xs">Gunakan form di samping untuk menginputkan link video tutorial pertama Anda.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 text-xs">
                                    <thead class="bg-slate-50 text-slate-700 border-bottom">
                                        <tr>
                                            <th class="py-3 px-3 text-center" style="width: 50px;">Urutan</th>
                                            <th class="py-3 px-3" style="width: 140px;">Preview</th>
                                            <th class="py-3 px-3">Judul &amp; Informasi</th>
                                            <th class="py-3 px-3 text-center" style="width: 120px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach($youtubeLinks as $link)
                                            <tr>
                                                <td class="text-center align-middle font-weight-bold text-purple-900">
                                                    <span class="badge badge-secondary px-2 py-1">{{ $link->urutan }}</span>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="position-relative rounded-lg overflow-hidden border bg-dark" style="aspect-ratio: 16/9; width: 120px;">
                                                        <img src="https://img.youtube.com/vi/{{ $link->youtube_id }}/hqdefault.jpg" alt="{{ $link->judul }}" class="w-100 h-100 object-cover" />
                                                        <a href="https://www.youtube.com/watch?v={{ $link->youtube_id }}" target="_blank" class="position-absolute inset-0 d-flex align-items-center justify-content-center bg-dark-50 text-white hover:text-red-500">
                                                            <i class="fab fa-youtube text-xl"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="font-weight-bold text-slate-900 text-sm mb-1">{{ $link->judul }}</div>
                                                    <div class="d-flex items-center gap-2 mb-1">
                                                        <span class="badge badge-info px-2 py-0.5 text-[10px]">{{ $link->kategori }}</span>
                                                        <span class="text-muted font-mono text-[11px]">ID: {{ $link->youtube_id }}</span>
                                                    </div>
                                                    <p class="text-slate-500 text-xs mb-0 text-truncate" style="max-width: 350px;">{{ $link->deskripsi ?: '-' }}</p>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-outline-warning rounded-lg mr-1" data-toggle="modal" data-target="#editModal{{ $link->id }}" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('admin.link.delete', $link->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus video tutorial ini?');" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-lg" title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>

                                                    <!-- Modal Edit Video -->
                                                    <div class="modal fade text-left" id="editModal{{ $link->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content rounded-xl border-0 shadow-lg">
                                                                <div class="modal-header bg-purple-900 text-white">
                                                                    <h5 class="modal-title font-weight-bold text-sm">
                                                                        <i class="fas fa-edit mr-1"></i> Edit Video Tutorial
                                                                    </h5>
                                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('admin.link.update', $link->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        <div class="form-group mb-3">
                                                                            <label class="text-xs font-weight-bold text-slate-700">Judul Video Tutorial <span class="text-danger">*</span></label>
                                                                            <input type="text" name="judul" class="form-control form-control-sm text-xs rounded-lg" value="{{ $link->judul }}" required />
                                                                        </div>

                                                                        <div class="form-group mb-3">
                                                                            <label class="text-xs font-weight-bold text-slate-700">URL / Link YouTube <span class="text-danger">*</span></label>
                                                                            <input type="url" name="youtube_url" class="form-control form-control-sm text-xs rounded-lg" value="{{ $link->youtube_url }}" required />
                                                                        </div>

                                                                        <div class="form-group mb-3">
                                                                            <label class="text-xs font-weight-bold text-slate-700">Kategori Video</label>
                                                                            <select name="kategori" class="form-control form-control-sm text-xs rounded-lg">
                                                                                <option value="Trik Hitung" {{ $link->kategori == 'Trik Hitung' ? 'selected' : '' }}>Trik Hitung</option>
                                                                                <option value="Aljabar & Bangun Ruang" {{ $link->kategori == 'Aljabar & Bangun Ruang' ? 'selected' : '' }}>Aljabar &amp; Bangun Ruang</option>
                                                                                <option value="Bedah Soal" {{ $link->kategori == 'Bedah Soal' ? 'selected' : '' }}>Bedah Soal Ujian</option>
                                                                                <option value="Tips Belajar" {{ $link->kategori == 'Tips Belajar' ? 'selected' : '' }}>Tips Belajar &amp; Motivasi</option>
                                                                                <option value="Umum" {{ $link->kategori == 'Umum' ? 'selected' : '' }}>Umum / Tutorial</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="form-group mb-3">
                                                                            <label class="text-xs font-weight-bold text-slate-700">Urutan Tampil</label>
                                                                            <input type="number" name="urutan" class="form-control form-control-sm text-xs rounded-lg" value="{{ $link->urutan }}" min="1" />
                                                                        </div>

                                                                        <div class="form-group mb-3">
                                                                            <label class="text-xs font-weight-bold text-slate-700">Deskripsi Singkat</label>
                                                                            <textarea name="deskripsi" rows="3" class="form-control form-control-sm text-xs rounded-lg">{{ $link->deskripsi }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer bg-slate-50 border-top py-2">
                                                                        <button type="button" class="btn btn-secondary btn-sm rounded-lg" data-dismiss="modal">Batal</button>
                                                                        <button type="submit" class="btn btn-primary btn-sm rounded-lg font-weight-bold px-3" style="background-color: #6b21a8; border-color: #581c87;">
                                                                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
