@extends('layout.app')

@section('title', 'Daftar Guru / Tutor · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Daftar Guru / Tutor Pendamping</h1>
                    <p class="text-sm text-muted mb-0">Kelola data pengajar terdaftar, spesialisasi, dan atur batas maksimal siswa (kuota) bimbingan.</p>
                </div>
                <div class="col-sm-6 text-sm-right mt-3 mt-sm-0 d-flex justify-content-sm-end align-items-center gap-2">
                    <button type="button" class="btn btn-purple font-weight-bold px-3 py-2 rounded-lg shadow-sm text-xs text-white d-inline-flex align-items-center" style="background-color: #6b21a8; border: none;" data-toggle="modal" data-target="#modalSettingRegisterGuru">
                        <i class="fas fa-cog mr-2"></i> Pengaturan Pendaftaran Guru
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Alert success -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert" style="background-color: #d1fae5; border-color: #a7f3d0; color: #065f46;">
                    <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #065f46;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card shadow-sm border-light">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <h3 class="card-title font-weight-bold text-purple-950 mb-0">Semua Data Tutor</h3>
                </div>
                <div class="card-body p-0">
                    @if ($gurus->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-chalkboard-teacher text-slate-300 fa-3x mb-3"></i>
                            <p class="text-slate-500 text-sm mb-0">Belum ada data guru/tutor terdaftar.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-slate-500">
                                    <tr>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Nama Tutor</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Email</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">No. Telepon / WA</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Spesialisasi</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Batas Siswa (Kuota)</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Detail</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gurus as $guru)
                                        <tr>
                                            <td class="px-4 py-3 font-weight-bold text-purple-950">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar bg-purple-100 text-purple-700 font-bold d-flex justify-content-center align-items-center rounded-circle mr-2" style="width: 32px; height: 32px; font-size: 14px;">
                                                        {{ strtoupper(substr($guru->user->name ?? 'G', 0, 1)) }}
                                                    </div>
                                                    <span>{{ $guru->user->name ?? 'Guru / Tutor' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-slate-600">{{ $guru->user->email ?? '-' }}</td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($guru->no_telp)
                                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $guru->no_telp) }}" target="_blank" class="btn btn-xs btn-outline-success font-weight-bold rounded-lg px-2 py-1 text-xs">
                                                        <i class="fab fa-whatsapp mr-1 text-emerald-600"></i> Hubungi ({{ $guru->no_telp }})
                                                    </a>
                                                @else
                                                    <span class="text-xs text-slate-400 font-italic">Belum diisi</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-slate-700 font-weight-semibold text-xs">
                                                <span class="badge bg-purple-50 text-purple-700 border border-purple-200 px-2 py-1 rounded">
                                                    {{ $guru->spesialisasi ?? 'Matematika' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <span class="badge {{ ($guru->max_siswa !== null && ($guru->total_siswa_bimbingan ?? 0) >= $guru->max_siswa) ? 'bg-rose-100 text-rose-800 border-rose-300' : 'bg-purple-50 text-purple-800 border-purple-200' }} border px-2.5 py-1 text-xs font-bold rounded-pill">
                                                        <i class="fas fa-users mr-1"></i>
                                                        {{ $guru->total_siswa_bimbingan ?? 0 }} / {{ $guru->max_siswa !== null ? $guru->max_siswa . ' Siswa' : '∞ (Tanpa Batas)' }}
                                                    </span>
                                                    <button type="button" class="btn btn-xs btn-light border ml-1.5 px-2 py-1 rounded-lg" title="Ubah Batas Siswa" data-toggle="modal" data-target="#modalEditMaxSiswa{{ $guru->id }}">
                                                        <i class="fas fa-edit text-purple-700"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-1">
                                                    <a href="{{ route('admin.guru.detail', $guru->id) }}" class="btn btn-xs btn-info rounded-lg font-weight-bold px-2.5 py-1.5 mr-1">
                                                        <i class="fas fa-eye mr-1"></i> Detail
                                                    </a>
                                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-lg font-weight-bold px-2 py-1.5" title="Hapus Akun Guru" data-toggle="modal" data-target="#modalHapusGuruDaftar{{ $guru->id }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if (strtolower($guru->status) === 'pending')
                                                    <div class="d-flex flex-column align-items-center gap-1">
                                                        <span class="badge bg-amber-100 text-amber-800 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-amber-300 mb-1">
                                                            <i class="fas fa-hourglass-half mr-1"></i> Pending Approval
                                                        </span>
                                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                                            <form action="{{ route('admin.guru.approve', $guru->id) }}" method="POST" class="m-0 mr-1">
                                                                @csrf
                                                                <button type="submit" class="btn btn-xs btn-success font-weight-bold rounded-lg px-2 py-1 text-[10px]" title="Setujui (Approve) Guru" onclick="return confirm('Apakah Anda yakin ingin menyetujui pendaftaran akun guru ini?')">
                                                                    <i class="fas fa-check mr-1"></i> Setujui
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('admin.guru.reject', $guru->id) }}" method="POST" class="m-0">
                                                                @csrf
                                                                <button type="submit" class="btn btn-xs btn-danger font-weight-bold rounded-lg px-2 py-1 text-[10px]" title="Tolak (Reject) Guru" onclick="return confirm('Apakah Anda yakin ingin menolak pendaftaran akun guru ini?')">
                                                                    <i class="fas fa-times mr-1"></i> Tolak
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @elseif (strtolower($guru->status) === 'aktif')
                                                    <span class="badge bg-emerald-100 text-emerald-800 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-emerald-200">
                                                        <i class="fas fa-check-circle mr-1"></i> Aktif
                                                    </span>
                                                @elseif (strtolower($guru->status) === 'ditolak')
                                                    <span class="badge bg-rose-100 text-rose-800 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-rose-200">
                                                        <i class="fas fa-times-circle mr-1"></i> Ditolak
                                                    </span>
                                                @else
                                                    <span class="badge bg-slate-100 text-slate-600 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-slate-200">{{ $guru->status ?? 'Aktif' }}</span>
                                                @endif
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
    </section>

    <!-- Modal Setting Status Display Pendaftaran Guru -->
    <div class="modal fade" id="modalSettingRegisterGuru" tabindex="-1" role="dialog" aria-labelledby="modalSettingRegisterGuruLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg rounded-2xl overflow-hidden" style="border-radius: 16px;">
                <div class="modal-header text-white py-3 px-4" style="background-color: #2e1065;">
                    <h5 class="modal-title font-bold text-base d-flex align-items-center text-white" id="modalSettingRegisterGuruLabel">
                        <i class="fas fa-cog mr-2 text-purple-300"></i> Pengaturan Pendaftaran Guru
                    </h5>
                    <button type="button" class="close text-white opacity-80 hover:opacity-100" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <div class="d-flex align-items-start mb-4">
                        <div class="rounded-circle p-3 mr-3 d-flex align-items-center justify-content-center {{ ($guruRegisterEnabled ?? true) ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}" style="width: 52px; height: 52px; min-width: 52px;">
                            <i class="fas {{ ($guruRegisterEnabled ?? true) ? 'fa-eye' : 'fa-eye-slash' }} fa-xl"></i>
                        </div>
                        <div>
                            <h6 class="font-weight-bold text-purple-950 mb-1">
                                Status Tampilan Pendaftaran Guru
                            </h6>
                            <p class="text-xs text-muted mb-0">
                                Atur status mengaktifkan (ON) atau menonaktifkan (OFF) tampilan link 
                                <span class="font-weight-bold text-purple-700">"Ingin bergabung sebagai pengajar? Daftar sebagai Guru"</span> pada halaman login siswa/umum.
                            </p>
                        </div>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 d-flex align-items-center justify-content-between mb-4">
                        <span class="text-xs text-muted font-weight-bold uppercase tracking-wider">Status Saat Ini:</span>
                        @if ($guruRegisterEnabled ?? true)
                            <span class="badge bg-emerald-100 text-emerald-800 border border-emerald-300 px-3 py-1.5 rounded-pill font-weight-bold text-xs">
                                <i class="fas fa-check-circle mr-1"></i> AKTIF (ON)
                            </span>
                        @else
                            <span class="badge bg-rose-100 text-rose-800 border border-rose-300 px-3 py-1.5 rounded-pill font-weight-bold text-xs">
                                <i class="fas fa-times-circle mr-1"></i> NONAKTIF (OFF)
                            </span>
                        @endif
                    </div>

                    <form action="{{ route('admin.guru.toggle-register') }}" method="POST" class="m-0 text-center">
                        @csrf
                        @if ($guruRegisterEnabled ?? true)
                            <button type="submit" class="btn btn-danger btn-block font-weight-bold py-2.5 rounded-xl shadow-sm text-sm" onclick="return confirm('Apakah Anda yakin ingin menonaktifkan (OFF) tampilan link pendaftaran guru di halaman login?')">
                                <i class="fas fa-power-off mr-2"></i> Nonaktifkan Pendaftaran (OFF)
                            </button>
                        @else
                            <button type="submit" class="btn btn-success btn-block font-weight-bold py-2.5 rounded-xl shadow-sm text-sm" onclick="return confirm('Apakah Anda yakin ingin mengaktifkan (ON) tampilan link pendaftaran guru di halaman login?')">
                                <i class="fas fa-toggle-on mr-2"></i> Aktifkan Pendaftaran (ON)
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals Edit Batas Siswa (Max Siswa) per Guru -->
    @foreach ($gurus as $guru)
        <div class="modal fade" id="modalEditMaxSiswa{{ $guru->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditMaxSiswaLabel{{ $guru->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow-lg rounded-2xl overflow-hidden" style="border-radius: 16px;">
                    <div class="modal-header text-white py-3 px-4" style="background-color: #2e1065;">
                        <h5 class="modal-title font-bold text-base d-flex align-items-center text-white" id="modalEditMaxSiswaLabel{{ $guru->id }}">
                            <i class="fas fa-user-graduate mr-2 text-purple-300"></i> Batas Maksimal Siswa: {{ $guru->user->name ?? 'Guru' }}
                        </h5>
                        <button type="button" class="close text-white opacity-80 hover:opacity-100" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.guru.update-max-siswa', $guru->id) }}" method="POST">
                        @csrf
                        <div class="modal-body p-4 bg-white">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-xs text-purple-950 uppercase tracking-wider mb-1">
                                    Jumlah Batas Siswa yang Boleh Diajar
                                </label>
                                <input type="number" name="max_siswa" class="form-control rounded-lg border-purple-200" min="0" placeholder="Kosongkan jika Tanpa Batas (∞)" value="{{ $guru->max_siswa }}">
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-info-circle text-purple-600 mr-1"></i>
                                    Siswa bimbingan saat ini: <strong>{{ $guru->total_siswa_bimbingan ?? 0 }} Siswa</strong>. Kosongkan inputan jika tidak ingin membatasi kuota siswa untuk tutor ini.
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer bg-slate-50 py-2.5 px-4 border-top d-flex justify-content-between">
                            <button type="button" class="btn btn-sm btn-light border font-weight-bold rounded-lg px-3" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-purple font-weight-bold rounded-lg px-4 text-white" style="background-color: #6b21a8;">
                                <i class="fas fa-save mr-1.5"></i> Simpan Batas Siswa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Confirm Hapus Guru dari Daftar -->
        <div class="modal fade" id="modalHapusGuruDaftar{{ $guru->id }}" tabindex="-1" role="dialog" aria-labelledby="modalHapusGuruDaftarLabel{{ $guru->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow-lg rounded-2xl overflow-hidden" style="border-radius: 16px;">
                    <div class="modal-header bg-danger text-white py-3 px-4" style="background-color: #dc2626 !important;">
                        <h5 class="modal-title font-bold text-base d-flex align-items-center text-white" id="modalHapusGuruDaftarLabel{{ $guru->id }}">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Hapus Akun Guru
                        </h5>
                        <button type="button" class="close text-white opacity-80 hover:opacity-100" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4 bg-white text-center">
                        <div class="rounded-circle p-3 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px; background-color: #ffe4e6; color: #e11d48;">
                            <i class="fas fa-user-slash fa-2x"></i>
                        </div>
                        <h5 class="font-weight-bold text-purple-950 mb-2">Hapus Akun Pengajar?</h5>
                        <p class="text-sm text-muted mb-0">
                            Apakah Anda yakin ingin menghapus akun guru <strong>{{ $guru->user->name ?? 'Pengajar' }}</strong> secara permanen?
                            Seluruh data profil pengajar ini akan dihapus dari sistem.
                        </p>
                    </div>
                    <div class="modal-footer bg-slate-50 py-2.5 px-4 border-top d-flex justify-content-between">
                        <button type="button" class="btn btn-sm btn-light border font-weight-bold rounded-lg px-3" data-dismiss="modal">Batal</button>
                        <form action="{{ route('admin.guru.delete', $guru->id) }}" method="POST" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger font-weight-bold rounded-lg px-4 text-white">
                                <i class="fas fa-trash-alt mr-1.5"></i> Ya, Hapus Akun
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Custom CSS for purple brand elements -->
    <style>
        .btn-outline-success {
            color: #10b981;
            border-color: #a7f3d0;
            background-color: transparent;
            transition: all 0.2s ease;
        }
        .btn-outline-success:hover {
            color: white;
            background-color: #10b981;
            border-color: #10b981;
        }
    </style>
@endsection
