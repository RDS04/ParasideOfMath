@extends('layout.app')

@section('title', 'Tambah Pelajaran · Paradise of Math')

@section('content')
    <style>
        .wizard-step-panel {
            display: none;
        }

        .wizard-step-panel.active {
            display: block;
            animation: fadeIn 0.2s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .wizard-invalid {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }
    </style>

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6 col-12">
                    <h1 class="m-0 font-weight-bold text-purple-950">Tambah Pelajaran</h1>
                    <p class="text-xs text-muted mb-0">Pilih mata pelajaran bimbingan belajar dan lakukan konfirmasi pembayaran.</p>
                </div>
                <div class="col-sm-6 col-12 text-sm-right mt-2 mt-sm-0">
                    <div id="live-clock-container"
                        class="inline-block px-3 py-1.5 bg-white rounded-xl shadow-sm border border-purple-100 text-xs font-semibold text-purple-950">
                        <i class="far fa-clock text-purple-600 mr-1.5"></i> <span id="live-clock">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Flash Message Alerts -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-xl shadow-sm border-0 mb-4" role="alert"
                    style="background-color: #d1fae5; border-color: #a7f3d0; color: #065f46;">
                    <h5><i class="icon fas fa-check mr-2"></i>Sukses!</h5>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #065f46;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-xl shadow-sm border-0 mb-4" role="alert">
                    <h5><i class="icon fas fa-ban mr-2"></i>Gagal!</h5>
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Section Mata Pelajaran Siswa -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 pt-4 pb-2 d-flex flex-column flex-sm-row justify-between align-items-sm-center gap-3">
                    <div>
                        <h5 class="card-title font-weight-bold text-purple-950 mb-1">
                            <i class="fas fa-book-open text-purple-600 mr-2"></i>Daftar Mata Pelajaran Bimbingan
                        </h5>
                        <p class="text-xs text-muted mb-0">Kelola mata pelajaran les Anda dan selesaikan pembayaran bimbingan belajar.</p>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <a href="#wizardTambahPelajaran" class="btn btn-sm btn-primary rounded-xl font-weight-bold shadow-xs px-3.5 py-2 d-inline-flex align-items-center"
                           style="background: linear-gradient(135deg, #7c3aed, #6d28d9); border: none;">
                            <i class="fas fa-plus-circle mr-1.5"></i> Tambah Pelajaran
                        </a>
                        <a href="#wizardTambahPelajaran" class="btn btn-sm btn-success rounded-xl font-weight-bold shadow-xs px-3.5 py-2 d-inline-flex align-items-center"
                           style="background: linear-gradient(135deg, #10b981, #059669); border: none;">
                            <i class="fas fa-credit-card mr-1.5"></i> Lanjut ke Pembayaran
                        </a>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <div id="wizardTambahPelajaran" class="border rounded-2xl p-4 mb-4" style="background: linear-gradient(135deg, #faf5ff 0%, #fdf2f8 100%); border-color: #e9d5ff;">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge badge-purple text-xxs font-weight-bold px-2.5 py-1" style="background-color: #ede9fe; color: #6d28d9;">Alur Baru</span>
                                    <span class="badge badge-light border text-xxs font-weight-bold px-2.5 py-1" style="color: #6d28d9; border-color: #e9d5ff;">Langkah <span id="wizardCurrentStep">1</span> dari 2</span>
                                </div>
                                <h5 class="font-weight-bold text-purple-950 mb-1">Tambah Pelajaran Lewat Wizard</h5>
                                <p class="text-xs text-muted mb-0">Pilih pelajaran tambahan, atur jadwal, lalu lanjutkan ke pembayaran.</p>
                            </div>
                            <div class="text-lg-right">
                                <div class="text-xxs font-weight-bold text-uppercase text-purple-600 mb-1">Progress</div>
                                <div class="progress" style="height: 8px; width: 220px; border-radius: 999px;">
                                    <div id="wizardProgressBar" class="progress-bar" role="progressbar" style="width: 50%; background: linear-gradient(90deg, #7c3aed, #a855f7);"></div>
                                </div>
                            </div>
                        </div>

                        <form id="wizardTambahPelajaranForm" action="{{ route('siswa.payment') }}" method="GET" novalidate>
                            <input type="hidden" name="paket_id" value="{{ $siswa->paket_id ?? ($paket->id ?? 1) }}">
                            <input type="hidden" name="tipe_paket" value="1">

                            <div class="wizard-step-panel active" data-step="1">
                                <div id="mapelSelectionField" class="p-3 rounded-2xl border" style="border-color: #e9d5ff; background: rgba(255,255,255,0.7);">
                                    <label class="font-weight-bold text-purple-950 mb-2 d-block">Pilih pelajaran yang ingin ditambahkan</label>
                                    <div class="row">
                                        @foreach ($availableMapels as $m)
                                            <div class="col-md-6 col-12 mb-3">
                                                <div class="card h-100 border shadow-xs transition-all" style="border-radius: 16px; border-color: #e9d5ff;">
                                                    <div class="card-body p-3">
                                                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between">
                                                            <input type="checkbox" class="custom-control-input mapel-checkbox" id="wizard_mapel_{{ $loop->index }}" name="mapel[]" value="{{ $m->nama_mapel }}">
                                                            <label class="custom-control-label font-weight-bold text-purple-950 text-sm cursor-pointer w-100 pl-2 mb-0" for="wizard_mapel_{{ $loop->index }}">
                                                                {{ $m->nama_mapel }}
                                                            </label>
                                                            <span class="badge badge-purple text-xxs font-semibold ml-2" style="background-color: #f3e8ff; color: #6d28d9;">
                                                                {{ $m->shift ?? 'Bimbel' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div id="mapelError" class="text-danger text-xs mt-2" style="display:none;">Pilih minimal satu pelajaran untuk melanjutkan.</div>
                                </div>
                            </div>

                            <div class="wizard-step-panel" data-step="2">
                                <div class="p-3 rounded-2xl border mb-3" style="border-color: #e9d5ff; background: rgba(255,255,255,0.7);">
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="fas fa-calendar-alt text-purple-600 mt-1"></i>
                                        <div>
                                            <div class="font-weight-bold text-purple-950">Atur jadwal untuk setiap pelajaran</div>
                                            <div class="text-xs text-muted">Tentukan jumlah sesi, hari, dan tanggal mulai untuk tiap pelajaran yang dipilih.</div>
                                        </div>
                                    </div>
                                </div>
                                <div id="wizardScheduleContainer"></div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4 gap-2">
                                <button type="button" id="wizardBtnBack" class="btn btn-light rounded-xl px-4 py-2 text-xs font-weight-bold text-slate-600" style="display:none;">← Kembali</button>
                                <div class="ml-auto d-flex gap-2">
                                    <button type="button" id="wizardBtnNext" class="btn btn-primary rounded-xl px-4 py-2 text-xs font-weight-bold shadow-sm" style="background: linear-gradient(135deg, #7c3aed, #6d28d9); border: none;">
                                        Lanjut ke Jadwal →
                                    </button>
                                    <button type="submit" id="wizardBtnSubmit" class="btn btn-success rounded-xl px-4 py-2 text-xs font-weight-bold shadow-sm" style="display:none; background: linear-gradient(135deg, #10b981, #059669); border: none;">
                                        <i class="fas fa-arrow-right mr-1.5"></i> Lanjut ke Pembayaran
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if (!empty($activeMapels))
                        <div class="mb-4 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-slate-700">
                            <div class="d-flex align-items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 d-flex align-items-center justify-center">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold text-slate-900 mb-1">Mata Pelajaran Aktif</h6>
                                    <p class="text-xs text-muted mb-1">Mata pelajaran yang sudah disetujui oleh admin tidak ditampilkan di daftar permintaan baru.</p>
                                    <p class="text-xs text-slate-800 mb-0 font-semibold">{{ implode(', ', $activeMapels) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (empty($mapels) || count($mapels) === 0)
                        <!-- Empty State -->
                        <div class="text-center py-5 px-3 bg-purple-50/50 rounded-2xl border border-dashed border-purple-200">
                            <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-full d-inline-flex align-items-center justify-content-center mb-3 shadow-xs">
                                <i class="fas fa-book-open fa-2x"></i>
                            </div>
                            <h6 class="font-weight-bold text-purple-950 mb-1 text-base">Belum Ada Permintaan Pelajaran Baru</h6>
                            <p class="text-xs text-muted max-w-md mx-auto mb-4">
                                Anda sudah memiliki pelajaran aktif. Tekan tombol <strong>Tambah Pelajaran</strong> untuk meminta pelajaran tambahan tanpa mengganggu jadwal yang sudah berjalan.
                            </p>
                            <div class="d-flex justify-center gap-2">
                                <a href="#wizardTambahPelajaran" class="btn btn-primary rounded-xl font-weight-bold shadow-sm px-4 py-2 text-xs"
                                   style="background: linear-gradient(135deg, #7c3aed, #6d28d9); border: none;">
                                    <i class="fas fa-plus-circle mr-1.5"></i> Tambah Pelajaran Baru
                                </a>
                            </div>
                        </div>
                    @else
                        @if ($isPending)
                            <div class="mb-4 p-4 rounded-2xl bg-yellow-50 border border-yellow-200 text-slate-700">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-700 d-flex align-items-center justify-center">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-weight-bold text-slate-900 mb-1">Menunggu Konfirmasi Admin</h6>
                                        <p class="text-xs text-muted mb-0">Mata pelajaran yang Anda pilih sudah tersimpan, tetapi jadwal belum aktif sampai admin menyetujui pembayaran.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- Display Selected Mapel Requests -->
                        <div class="row">
                            @foreach ($mapels as $idx => $mName)
                                @php
                                    $sesiCount = $sesiPerMapel[$idx] ?? 8;
                                    $bgColors = ['bg-purple-50 border-purple-200 text-purple-900', 'bg-indigo-50 border-indigo-200 text-indigo-900', 'bg-teal-50 border-teal-200 text-teal-900', 'bg-amber-50 border-amber-200 text-amber-900'];
                                    $badgeColor = $bgColors[$idx % count($bgColors)];
                                @endphp
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <div class="p-3.5 rounded-2xl border transition-all hover:shadow-md {{ $badgeColor }}">
                                        <div class="d-flex justify-between align-items-start">
                                            <div>
                                                <span class="badge badge-purple mb-1.5 text-xxs px-2.5 py-1 font-semibold uppercase tracking-wider" style="background-color: {{ $isPending ? 'rgba(245, 158, 11, 0.15)' : 'rgba(124, 58, 237, 0.15)'}}; color: {{ $isPending ? '#b45309' : '#6d28d9' }};">
                                                    {{ $isPending ? 'Menunggu Konfirmasi' : 'Aktif Bimbingan' }}
                                                </span>
                                                <h6 class="font-weight-bold mb-1 text-base text-purple-950">{{ $mName }}</h6>
                                                <p class="text-xs text-muted mb-0">
                                                    <i class="far fa-clock mr-1"></i> {{ $sesiCount }} Sesi Bimbingan
                                                </p>
                                            </div>
                                            <div class="w-12 h-12 rounded-2xl bg-white text-purple-700 shadow-xs d-flex align-items-center justify-center font-bold text-lg flex-shrink-0">
                                                <i class="fas fa-book"></i>
                                            </div>
                                        </div>

                                        <!-- Aksi: Edit & Hapus -->
                                        <div class="d-flex gap-2 mt-3 pt-2 border-top" style="border-color: rgba(0,0,0,0.06) !important;">
                                            <button type="button"
                                                    class="btn btn-sm btn-light rounded-xl text-xxs font-weight-bold flex-fill d-inline-flex align-items-center justify-content-center py-1.5"
                                                    style="background-color: rgba(255,255,255,0.7); border: 1px solid rgba(0,0,0,0.08);"
                                                    data-toggle="modal"
                                                    data-target="#modalEditPelajaran"
                                                    data-mapel="{{ $mName }}"
                                                    data-sesi="{{ $sesiCount }}">
                                                <i class="fas fa-pen mr-1.5 text-purple-600"></i> Edit
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm btn-light rounded-xl text-xxs font-weight-bold flex-fill d-inline-flex align-items-center justify-content-center py-1.5"
                                                    style="background-color: rgba(255,255,255,0.7); border: 1px solid rgba(0,0,0,0.08);"
                                                    data-toggle="modal"
                                                    data-target="#modalHapusPelajaran"
                                                    data-mapel="{{ $mName }}">
                                                <i class="fas fa-trash-alt mr-1.5 text-red-500"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </section>

    <!-- Modal Pop-Up Tambah Pelajaran -->
    <div class="modal fade" id="modalTambahPelajaran" tabindex="-1" role="dialog" aria-labelledby="modalTambahPelajaranLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden;">
                <div class="modal-header border-0 px-4 pt-4 pb-2 text-white" style="background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%);">
                    <h5 class="modal-title font-weight-bold text-white d-flex align-items-center text-lg" id="modalTambahPelajaranLabel">
                        <i class="fas fa-plus-circle text-amber-400 mr-2.5"></i> Pilih Mata Pelajaran Bimbingan
                    </h5>
                    <button type="button" class="close text-white opacity-75 hover:opacity-100" data-dismiss="modal" aria-label="Close" style="color: #fff; outline: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('siswa.tambah-mapel') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                        <p class="text-xs text-muted mb-4">
                            Pilih mata pelajaran yang ingin Anda pelajari dan tentukan perkiraan jumlah sesi bimbingan yang diinginkan.
                        </p>

                        <div class="row">
                            @foreach ($availableMapels as $m)
                                @php
                                    $isAlreadySelected = in_array($m->nama_mapel, $mapels);
                                @endphp
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="card h-100 border shadow-xs transition-all mapel-select-card {{ $isAlreadySelected ? 'border-purple-500 bg-purple-50/40' : 'border-slate-200' }}" style="border-radius: 16px;">
                                        <div class="card-body p-3">
                                            <div class="custom-control custom-checkbox d-flex align-items-center justify-between">
                                                <input type="checkbox" class="custom-control-input mapel-checkbox" 
                                                       id="mapel_cb_{{ $loop->index }}" 
                                                       name="mapel[]" 
                                                       value="{{ $m->nama_mapel }}" 
                                                       {{ $isAlreadySelected ? 'checked' : '' }}>
                                                <label class="custom-control-label font-weight-bold text-purple-950 text-sm cursor-pointer w-100 pl-2 mb-0" for="mapel_cb_{{ $loop->index }}">
                                                    {{ $m->nama_mapel }}
                                                </label>
                                                <span class="badge badge-purple text-xxs font-semibold ml-2" style="background-color: #f3e8ff; color: #6d28d9;">
                                                    {{ $m->shift ?? 'Bimbel' }}
                                                </span>
                                            </div>

                                            <div class="mt-3 pt-2 border-top">
                                                <label class="text-xxs font-bold text-muted uppercase tracking-wider mb-1 d-block">Jumlah Sesi Belajar</label>
                                                <select name="sesi[{{ $m->nama_mapel }}]" class="form-control form-control-sm rounded-lg text-xs" style="border-color: #e2e8f0;">
                                                    <option value="4">4 Sesi (1 Bulan Dasar)</option>
                                                    <option value="8" selected>8 Sesi (2 Bulan Intensif)</option>
                                                    <option value="12">12 Sesi (3 Bulan Penguasaan)</option>
                                                    <option value="16">16 Sesi (4 Bulan Master)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 bg-slate-50 d-flex justify-between align-items-center">
                        <button type="button" class="btn btn-light rounded-xl px-4 py-2 text-xs font-weight-bold text-slate-600" data-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary rounded-xl px-4 py-2 text-xs font-weight-bold shadow-sm" style="background: linear-gradient(135deg, #7c3aed, #6d28d9); border: none;">
                            <i class="fas fa-check-circle mr-1.5"></i> Simpan & Tampilkan Pelajaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Pop-Up Edit Pelajaran -->
    <div class="modal fade" id="modalEditPelajaran" tabindex="-1" role="dialog" aria-labelledby="modalEditPelajaranLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden;">
                <div class="modal-header border-0 px-4 pt-4 pb-2 text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%);">
                    <h5 class="modal-title font-weight-bold text-white d-flex align-items-center text-lg" id="modalEditPelajaranLabel">
                        <i class="fas fa-pen text-amber-300 mr-2.5"></i> Ubah Jumlah Sesi
                    </h5>
                    <button type="button" class="close text-white opacity-75 hover:opacity-100" data-dismiss="modal" aria-label="Close" style="color: #fff; outline: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('siswa.edit-mapel') }}" method="POST" id="formEditPelajaran">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="mapel" id="edit_mapel_name">
                    <div class="modal-body p-4">
                        <p class="text-xs text-muted mb-3">
                            Mengubah jumlah sesi untuk mata pelajaran
                            <strong id="edit_mapel_label" class="text-purple-950">-</strong>.
                        </p>
                        <label class="text-xxs font-bold text-muted uppercase tracking-wider mb-1 d-block">Jumlah Sesi Belajar</label>
                        <select name="sesi" id="edit_sesi_select" class="form-control rounded-lg text-sm" style="border-color: #e2e8f0;">
                            <option value="4">4 Sesi (1 Bulan Dasar)</option>
                            <option value="8">8 Sesi (2 Bulan Intensif)</option>
                            <option value="12">12 Sesi (3 Bulan Penguasaan)</option>
                            <option value="16">16 Sesi (4 Bulan Master)</option>
                        </select>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 bg-slate-50 d-flex justify-between align-items-center">
                        <button type="button" class="btn btn-light rounded-xl px-4 py-2 text-xs font-weight-bold text-slate-600" data-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary rounded-xl px-4 py-2 text-xs font-weight-bold shadow-sm" style="background: linear-gradient(135deg, #1d4ed8, #1e40af); border: none;">
                            <i class="fas fa-save mr-1.5"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Pop-Up Hapus Pelajaran -->
    <div class="modal fade" id="modalHapusPelajaran" tabindex="-1" role="dialog" aria-labelledby="modalHapusPelajaranLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden;">
                <div class="modal-header border-0 px-4 pt-4 pb-2 text-white" style="background: linear-gradient(135deg, #7f1d1d 0%, #b91c1c 100%);">
                    <h5 class="modal-title font-weight-bold text-white d-flex align-items-center text-lg" id="modalHapusPelajaranLabel">
                        <i class="fas fa-trash-alt text-amber-300 mr-2.5"></i> Hapus Mata Pelajaran
                    </h5>
                    <button type="button" class="close text-white opacity-75 hover:opacity-100" data-dismiss="modal" aria-label="Close" style="color: #fff; outline: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('siswa.hapus-mapel') }}" method="POST" id="formHapusPelajaran">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="mapel" id="hapus_mapel_name">
                    <div class="modal-body p-4">
                        <p class="text-sm text-slate-700 mb-0">
                            Apakah Anda yakin ingin menghapus rencana mata pelajaran
                            <strong id="hapus_mapel_label" class="text-red-700">-</strong> dari daftar bimbingan Anda?
                            Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 bg-slate-50 d-flex justify-between align-items-center">
                        <button type="button" class="btn btn-light rounded-xl px-4 py-2 text-xs font-weight-bold text-slate-600" data-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger rounded-xl px-4 py-2 text-xs font-weight-bold shadow-sm">
                            <i class="fas fa-trash-alt mr-1.5"></i> Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Pop-Up Pembayaran -->
    <div class="modal fade" id="modalBayarPelajaran" tabindex="-1" role="dialog" aria-labelledby="modalBayarPelajaranLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden;">
                <div class="modal-header border-0 px-4 pt-4 pb-2 text-white" style="background: linear-gradient(135deg, #065f46 0%, #047857 100%);">
                    <h5 class="modal-title font-weight-bold text-white d-flex align-items-center text-lg" id="modalBayarPelajaranLabel">
                        <i class="fas fa-credit-card text-emerald-300 mr-2.5"></i> Pembayaran Bimbingan Belajar
                    </h5>
                    <button type="button" class="close text-white opacity-75 hover:opacity-100" data-dismiss="modal" aria-label="Close" style="color: #fff; outline: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <form action="{{ route('siswa.payment.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="paket_id" value="{{ $paket->id ?? 1 }}">
                    <input type="hidden" name="tipe_paket" value="1">
                    
                    <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                        <!-- Ringkasan Biaya -->
                        @php
                            $singleRate = 45000;
                            if ($paket) {
                                if (preg_match('/(\d+)\s*K/i', $paket->detail_1 ?? '', $m)) {
                                    $singleRate = (int)$m[1] * 1000;
                                }
                            }
                            $totalSesiCalc = array_sum(array_map('intval', $sesiPerMapel));
                            if ($totalSesiCalc === 0) $totalSesiCalc = 8;
                            $totalPrice = $singleRate * $totalSesiCalc;
                        @endphp

                        <div class="p-3.5 mb-4 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 d-flex flex-column flex-sm-row justify-between align-items-sm-center gap-2">
                            <div>
                                <span class="text-xxs font-bold text-emerald-700 uppercase tracking-wider">Total Tagihan Les</span>
                                <h4 class="font-weight-bold text-emerald-950 mb-0">Rp {{ number_format($totalPrice, 0, ',', '.') }}</h4>
                                <p class="text-xxs text-emerald-600 mb-0 mt-0.5">
                                    {{ count($mapels) > 0 ? implode(', ', $mapels) : 'Pelajaran Bimbingan' }} &bull; {{ $totalSesiCalc }} Sesi (Rp {{ number_format($singleRate, 0, ',', '.') }}/sesi)
                                </p>
                            </div>
                            <span class="badge badge-emerald px-3 py-2 text-xs font-bold rounded-xl" style="background-color: #d1fae5; color: #065f46;">
                                Status: Siap Bayar
                            </span>
                        </div>

                        <!-- Tabs Pilih Metode Pembayaran -->
                        <label class="font-weight-bold text-purple-950 text-sm mb-2 d-block">Pilih Metode Pembayaran:</label>

                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="payment-method-option active p-3 rounded-2xl border text-center cursor-pointer transition-all border-purple-500 bg-purple-50/60" 
                                     id="opt_rekening_btn" onclick="switchPaymentMethod('bank')">
                                    <i class="fas fa-university text-xl text-purple-600 mb-1 d-block"></i>
                                    <span class="font-weight-bold text-xs d-block text-purple-950">Transfer Rekening</span>
                                    <span class="text-xxs text-muted">Bank &amp; E-Wallet</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="payment-method-option p-3 rounded-2xl border text-center cursor-pointer transition-all border-slate-200" 
                                     id="opt_tunai_btn" onclick="switchPaymentMethod('tunai')">
                                    <i class="fas fa-money-bill-wave text-xl text-emerald-600 mb-1 d-block"></i>
                                    <span class="font-weight-bold text-xs d-block text-purple-950">Bayar Tunai</span>
                                    <span class="text-xxs text-muted">Bayar di Tempat</span>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="payment_method" id="input_payment_method" value="bank">

                        <!-- Content Panel 1: Transfer Rekening -->
                        <div id="panel_rekening" class="payment-panel">
                            <div class="p-3 mb-3 bg-purple-50/50 rounded-2xl border border-purple-100">
                                <h6 class="font-weight-bold text-xs text-purple-950 mb-2">Daftar Rekening Tujuan Transfer:</h6>
                                <div class="row">
                                    @forelse ($rekeningBanks as $bank)
                                        <div class="col-sm-6 col-12 mb-2">
                                            <div class="p-2.5 bg-white rounded-xl border border-purple-100 text-xs">
                                                <span class="font-weight-bold text-purple-900 d-block">{{ $bank->nama_bank }}</span>
                                                <span class="font-mono text-slate-800 text-sm font-bold d-block">{{ $bank->nomor_rekening }}</span>
                                                <span class="text-xxs text-muted">a.n {{ $bank->atas_nama }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="p-2.5 bg-white rounded-xl border border-purple-100 text-xs">
                                                <span class="font-weight-bold text-purple-900 d-block">Bank BCA</span>
                                                <span class="font-mono text-slate-800 text-sm font-bold d-block">1234 5678 90</span>
                                                <span class="text-xxs text-muted">a.n Paradise of Math</span>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label class="font-weight-bold text-xs text-slate-700 mb-1">Unggah Bukti Transfer <span class="text-danger">*</span></label>
                                <input type="file" name="bukti_transfer" id="input_bukti_transfer" class="form-control-file p-2 bg-white rounded-xl border text-xs" accept="image/*,.pdf" required>
                                <small class="form-text text-muted text-xxs mt-1">Format yang didukung: JPG, PNG, PDF (Maks. 2MB)</small>
                            </div>
                        </div>

                        <!-- Content Panel 2: Tunai -->
                        <div id="panel_tunai" class="payment-panel d-none">
                            <div class="p-3 bg-amber-50/70 rounded-2xl border border-amber-200 text-xs text-amber-900 mb-2">
                                <div class="d-flex items-center gap-2 mb-1">
                                    <i class="fas fa-info-circle text-amber-600 text-base"></i>
                                    <span class="font-weight-bold">Instruksi Pembayaran Tunai (Cash)</span>
                                </div>
                                <p class="mb-0 text-xxs leading-relaxed">
                                    Pembayaran secara tunai dapat dilakukan secara langsung di kantor/lokasi bimbingan belajar <strong>Paradise of Math</strong> atau diserahkan kepada tutor pengajar pada sesi pertemuan pertama.
                                </p>
                            </div>
                            <p class="text-xxs text-muted mb-0">
                                Setelah Anda menekan tombol konfirmasi di bawah, pesanan bimbingan belajar Anda akan terdaftar sebagai opsi tunai dan diproses oleh admin.
                            </p>
                        </div>

                    </div>

                    <div class="modal-footer border-0 px-4 pb-4 pt-0 bg-slate-50 d-flex justify-between align-items-center">
                        <button type="button" class="btn btn-light rounded-xl px-4 py-2 text-xs font-weight-bold text-slate-600" data-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-success rounded-xl px-4 py-2 text-xs font-weight-bold shadow-sm" style="background: linear-gradient(135deg, #10b981, #059669); border: none;">
                            <i class="fas fa-paper-plane mr-1.5"></i> Konfirmasi & Kirim Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const wizardForm = document.getElementById('wizardTambahPelajaranForm');
        const wizardSteps = Array.from(document.querySelectorAll('.wizard-step-panel'));
        const wizardBtnBack = document.getElementById('wizardBtnBack');
        const wizardBtnNext = document.getElementById('wizardBtnNext');
        const wizardBtnSubmit = document.getElementById('wizardBtnSubmit');
        const wizardProgressBar = document.getElementById('wizardProgressBar');
        const wizardCurrentStep = document.getElementById('wizardCurrentStep');
        const wizardScheduleContainer = document.getElementById('wizardScheduleContainer');
        const mapelError = document.getElementById('mapelError');
        const wizardMapelCheckboxes = Array.from(wizardForm.querySelectorAll('input[name="mapel[]"]'));
        let wizardCurrentStepIndex = 1;

        function renderWizardSteps() {
            wizardSteps.forEach(step => {
                const stepNumber = parseInt(step.dataset.step);
                step.classList.toggle('active', stepNumber === wizardCurrentStepIndex);
            });

            wizardBtnBack.style.display = wizardCurrentStepIndex === 1 ? 'none' : 'inline-block';
            wizardBtnNext.style.display = wizardCurrentStepIndex === 1 ? 'inline-block' : 'none';
            wizardBtnSubmit.style.display = wizardCurrentStepIndex === 2 ? 'inline-block' : 'none';

            const progressPercent = wizardCurrentStepIndex === 1 ? 50 : 100;
            wizardProgressBar.style.width = `${progressPercent}%`;
            wizardCurrentStep.textContent = wizardCurrentStepIndex;

            if (wizardCurrentStepIndex === 2) {
                generateWizardScheduleCards();
            }
        }

        function validateWizardStep(step) {
            if (step === 1) {
                const hasSelection = wizardMapelCheckboxes.some(cb => cb.checked);
                const selectionField = document.getElementById('mapelSelectionField');
                if (!hasSelection) {
                    selectionField.classList.add('wizard-invalid');
                    mapelError.style.display = 'block';
                    selectionField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }
                selectionField.classList.remove('wizard-invalid');
                mapelError.style.display = 'none';
                return true;
            }

            if (step === 2) {
                let valid = true;
                const cards = wizardScheduleContainer.querySelectorAll('.card');
                if (cards.length === 0) {
                    return false;
                }

                cards.forEach(card => {
                    const sesiSelect = card.querySelector('select[name^="sesi"]');
                    const hariSelects = card.querySelectorAll('select[name^="hari"]');
                    const dateInput = card.querySelector('input[type="date"]');

                    if (sesiSelect && !sesiSelect.value) {
                        valid = false;
                        sesiSelect.classList.add('wizard-invalid');
                    } else if (sesiSelect) {
                        sesiSelect.classList.remove('wizard-invalid');
                    }

                    hariSelects.forEach(sel => {
                        if (!sel.value) {
                            valid = false;
                            sel.classList.add('wizard-invalid');
                        } else {
                            sel.classList.remove('wizard-invalid');
                        }
                    });

                    if (dateInput && !dateInput.value) {
                        valid = false;
                        dateInput.classList.add('wizard-invalid');
                    } else if (dateInput) {
                        dateInput.classList.remove('wizard-invalid');
                    }
                });

                if (!valid) {
                    const firstInvalid = wizardScheduleContainer.querySelector('.wizard-invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }

                return valid;
            }

            return true;
        }

        function getSelectedWizardMapels() {
            return wizardMapelCheckboxes.filter(cb => cb.checked).map(cb => cb.value);
        }

        function generateWizardScheduleCards() {
            const selectedMapels = getSelectedWizardMapels();
            const now = new Date();
            const todayStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;
            wizardScheduleContainer.innerHTML = '';

            if (!selectedMapels.length) {
                wizardScheduleContainer.innerHTML = '<div class="p-3 rounded-2xl border text-xs text-muted" style="border-color: #e9d5ff;">Pilih minimal satu pelajaran pada langkah sebelumnya.</div>';
                return;
            }

            selectedMapels.forEach((mapel, idx) => {
                const card = document.createElement('div');
                card.className = 'card mb-3 border-0 shadow-sm';
                card.style.borderRadius = '18px';
                card.innerHTML = `
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="text-xxs font-weight-bold text-uppercase text-purple-500">Jadwal Belajar</div>
                                <div class="font-weight-bold text-purple-950">${mapel}</div>
                            </div>
                            <span class="badge badge-purple text-xxs font-weight-bold px-2.5 py-1" style="background-color: #f3e8ff; color: #6d28d9;">Pelajaran ${idx + 1}</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4 col-12">
                                <label class="text-xxs font-weight-bold text-muted uppercase tracking-wider mb-1 d-block">Jumlah Sesi</label>
                                <select name="sesi[${idx}]" class="form-control form-control-sm rounded-lg" required>
                                    <option value="">Pilih sesi</option>
                                    ${[4, 8, 12, 16].map(n => `<option value="${n}">${n} Sesi</option>`).join('')}
                                </select>
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="text-xxs font-weight-bold text-muted uppercase tracking-wider mb-1 d-block">Hari Pertemuan 1</label>
                                <select name="hari[${idx}][1]" class="form-control form-control-sm rounded-lg" required>
                                    <option value="">Pilih hari</option>
                                    <option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option><option>Sabtu</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="text-xxs font-weight-bold text-muted uppercase tracking-wider mb-1 d-block">Hari Pertemuan 2</label>
                                <select name="hari[${idx}][2]" class="form-control form-control-sm rounded-lg" required>
                                    <option value="">Pilih hari</option>
                                    <option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option><option>Sabtu</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="text-xxs font-weight-bold text-muted uppercase tracking-wider mb-1 d-block">Tanggal Mulai Les</label>
                                <input type="date" name="tanggal_mulai[${idx}]" class="form-control form-control-sm rounded-lg" required min="${todayStr}">
                            </div>
                        </div>
                        <input type="hidden" name="mapel_jadwal[${idx}]" value="${mapel}">
                    </div>
                `;
                wizardScheduleContainer.appendChild(card);
            });
        }

        wizardBtnNext.addEventListener('click', function () {
            if (validateWizardStep(1)) {
                wizardCurrentStepIndex = 2;
                renderWizardSteps();
            }
        });

        wizardBtnBack.addEventListener('click', function () {
            if (wizardCurrentStepIndex > 1) {
                wizardCurrentStepIndex = 1;
                renderWizardSteps();
            }
        });

        wizardMapelCheckboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                if (this.checked) {
                    document.getElementById('mapelSelectionField').classList.remove('wizard-invalid');
                    mapelError.style.display = 'none';
                }
            });
        });

        wizardForm.addEventListener('submit', function (event) {
            if (!validateWizardStep(2)) {
                event.preventDefault();
            }
        });

        renderWizardSteps();

        function switchPaymentMethod(method) {
            document.getElementById('input_payment_method').value = method;
            const reqBtn = document.getElementById('opt_rekening_btn');
            const tunBtn = document.getElementById('opt_tunai_btn');
            const reqPanel = document.getElementById('panel_rekening');
            const tunPanel = document.getElementById('panel_tunai');
            const buktiInput = document.getElementById('input_bukti_transfer');

            if (method === 'bank') {
                reqBtn.classList.add('border-purple-500', 'bg-purple-50/60');
                reqBtn.classList.remove('border-slate-200');
                tunBtn.classList.remove('border-purple-500', 'bg-purple-50/60');
                tunBtn.classList.add('border-slate-200');
                reqPanel.classList.remove('d-none');
                tunPanel.classList.add('d-none');
                if (buktiInput) buktiInput.required = true;
            } else {
                tunBtn.classList.add('border-purple-500', 'bg-purple-50/60');
                tunBtn.classList.remove('border-slate-200');
                reqBtn.classList.remove('border-purple-500', 'bg-purple-50/60');
                reqBtn.classList.add('border-slate-200');
                tunPanel.classList.remove('d-none');
                reqPanel.classList.add('d-none');
                if (buktiInput) buktiInput.required = false;
            }
        }

        // Isi data modal Edit & Hapus saat tombol ditekan
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-target="#modalEditPelajaran"]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const mapel = btn.dataset.mapel || '';
                    const sesi = btn.dataset.sesi || '';
                    const editMapelInput = document.getElementById('edit_mapel_name');
                    const editMapelLabel = document.getElementById('edit_mapel_label');
                    const editSesiSelect = document.getElementById('edit_sesi_select');

                    if (editMapelInput) editMapelInput.value = mapel;
                    if (editMapelLabel) editMapelLabel.textContent = mapel || '-';
                    if (editSesiSelect && sesi) editSesiSelect.value = sesi;
                });
            });

            document.querySelectorAll('[data-target="#modalHapusPelajaran"]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const mapel = btn.dataset.mapel || '';
                    const hapusMapelInput = document.getElementById('hapus_mapel_name');
                    const hapusMapelLabel = document.getElementById('hapus_mapel_label');

                    if (hapusMapelInput) hapusMapelInput.value = mapel;
                    if (hapusMapelLabel) hapusMapelLabel.textContent = mapel || '-';
                });
            });
        });
    </script>
@endsection