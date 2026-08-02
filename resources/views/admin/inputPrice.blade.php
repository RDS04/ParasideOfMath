@extends('layout.app')

@section('title', 'Kelola Paket Belajar · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Kelola Paket &amp; Tarif Belajar</h1>
                    <p class="text-sm text-muted mb-0">Ubah data harga, deskripsi, dan rincian paket belajar secara realtime.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kelola Paket</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert">
                    <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                @foreach($packages as $paket)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm hover:shadow-md transition duration-200 border-light" style="{{ $paket->is_populer ? 'border: 2px solid var(--pom-amber-light);' : '' }}">
                        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                            <span class="font-weight-bold text-purple-950 text-md">{{ $paket->nama_paket }}</span>
                            @if($paket->is_populer)
                                <span class="badge bg-amber-100 text-amber-800 font-bold uppercase text-[10px] px-2.5 py-1">Paling Populer</span>
                            @endif
                        </div>

                        <div class="card-body">
                            <form action="{{ route('admin.paket.update', $paket->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group mb-3">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Paket &amp; Kategori</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="text" name="nama_paket" value="{{ $paket->nama_paket }}" class="form-control form-control-sm font-weight-bold" required />
                                        </div>
                                        <div class="col-6">
                                            <input type="text" name="kategori" value="{{ $paket->kategori }}" class="form-control form-control-sm" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Deskripsi Paket</label>
                                    <textarea name="deskripsi" class="form-control form-control-sm" rows="3" required>{{ $paket->deskripsi }}</textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Rentang Harga (Rupiah)</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="text-[10px] text-muted font-semibold d-block mb-1">Min (Rp)</span>
                                            <input type="number" name="harga_min" value="{{ $paket->harga_min }}" class="form-control form-control-sm font-weight-semibold" required />
                                        </div>
                                        <div class="col-6">
                                            <span class="text-[10px] text-muted font-semibold d-block mb-1">Max (Rp)</span>
                                            <input type="number" name="harga_max" value="{{ $paket->harga_max }}" class="form-control form-control-sm font-weight-semibold" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Detail Baris Rincian</label>
                                    <div class="space-y-1">
                                        <input type="text" name="detail_1" value="{{ $paket->detail_1 }}" placeholder="Rincian 1" class="form-control form-control-sm mb-1 text-xs" />
                                        <input type="text" name="detail_2" value="{{ $paket->detail_2 }}" placeholder="Rincian 2" class="form-control form-control-sm mb-1 text-xs" />
                                        <input type="text" name="detail_3" value="{{ $paket->detail_3 }}" placeholder="Rincian 3" class="form-control form-control-sm mb-1 text-xs" />
                                        <input type="text" name="detail_4" value="{{ $paket->detail_4 }}" placeholder="Rincian 4" class="form-control form-control-sm mb-1 text-xs" />
                                        <input type="text" name="detail_5" value="{{ $paket->detail_5 }}" placeholder="Rincian 5" class="form-control form-control-sm text-xs" />
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Jam Mulai Default</label>
                                    <input type="time" name="jam_mulai" value="{{ $paket->jam_mulai ?? '15:30' }}" class="form-control form-control-sm" required />
                                </div>

                                <div class="custom-control custom-checkbox mb-4">
                                    <input type="checkbox" name="is_populer" value="1" class="custom-control-input" id="populer-{{ $paket->id }}" {{ $paket->is_populer ? 'checked' : '' }}>
                                    <label class="custom-control-label text-xs font-weight-bold text-slate-700 cursor-pointer" for="populer-{{ $paket->id }}">Tandai Paling Populer (Kartu Ungu)</label>
                                </div>

                                <button type="submit" class="btn btn-brand btn-block btn-sm py-2 rounded-lg font-weight-bold shadow-sm">
                                    Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>
@endsection
