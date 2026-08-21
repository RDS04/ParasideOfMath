@extends('layout.app')

@section('title', 'Penugasan Ujian Siswa · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950 text-2xl tracking-tight">Penugasan Ujian Siswa</h1>
                    <p class="text-xs text-muted mb-0">Pilih siswa bimbingan Anda dan tentukan paket soal dari Bank Soal yang wajib dikerjakan siswa.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-xs bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}" class="text-purple-600 font-semibold"><i class="fas fa-home mr-1"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active text-slate-500">Penugasan Ujian</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Flash Notifications -->
            @if (session('success'))
                <div class="alert alert-success bg-emerald-500 text-white border-0 rounded-2xl shadow-sm p-4 mb-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-lg mr-3"></i>
                        <span class="font-semibold text-sm">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="close text-white opacity-80 hover:opacity-100" data-dismiss="alert">&times;</button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger bg-rose-500 text-white border-0 rounded-2xl shadow-sm p-4 mb-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-lg mr-3"></i>
                        <span class="font-semibold text-sm">{{ session('error') }}</span>
                    </div>
                    <button type="button" class="close text-white opacity-80 hover:opacity-100" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <!-- Ringkasan Statistik -->
            <div class="row g-2.5 sm:g-3 mb-4">
                <div class="col-6 col-md-3 mb-2 mb-md-0">
                    <div class="card border-0 shadow-sm rounded-2xl bg-white p-2.5 sm:p-3 d-flex flex-row align-items-center h-100">
                        <div class="rounded-xl sm:rounded-2xl bg-purple-50 text-purple-600 mr-2 sm:mr-3 d-flex align-items-center justify-content-center shrink-0" style="width: 42px; height: 42px;">
                            <i class="fas fa-users text-base sm:text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-[10px] sm:text-xs font-semibold text-slate-400 uppercase text-truncate">Siswa Bimbingan</div>
                            <div class="text-xs sm:text-lg font-bold text-purple-950 text-truncate">{{ count($assignedStudents) }} Siswa</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2 mb-md-0">
                    <div class="card border-0 shadow-sm rounded-2xl bg-white p-2.5 sm:p-3 d-flex flex-row align-items-center h-100">
                        <div class="rounded-xl sm:rounded-2xl bg-amber-50 text-amber-600 mr-2 sm:mr-3 d-flex align-items-center justify-content-center shrink-0" style="width: 42px; height: 42px;">
                            <i class="fas fa-user-check text-base sm:text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-[10px] sm:text-xs font-semibold text-slate-400 uppercase text-truncate">Siswa Terpilih</div>
                            <div class="text-xs sm:text-sm font-bold text-slate-800 text-truncate" title="{{ $selectedSiswa ? $selectedSiswa->name : 'Belum Ada' }}">
                                {{ $selectedSiswa ? $selectedSiswa->name : 'Belum Ada' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2 mb-md-0">
                    <div class="card border-0 shadow-sm rounded-2xl bg-white p-2.5 sm:p-3 d-flex flex-row align-items-center h-100">
                        <div class="rounded-xl sm:rounded-2xl bg-teal-50 text-teal-600 mr-2 sm:mr-3 d-flex align-items-center justify-content-center shrink-0" style="width: 42px; height: 42px;">
                            <i class="fas fa-file-signature text-base sm:text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-[10px] sm:text-xs font-semibold text-slate-400 uppercase text-truncate">Ujian Ditugaskan</div>
                            <div class="text-xs sm:text-lg font-bold text-teal-950 text-truncate">{{ count($assignedExams) }} Paket</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2 mb-md-0">
                    <div class="card border-0 shadow-sm rounded-2xl bg-white p-2.5 sm:p-3 d-flex flex-row align-items-center h-100">
                        <div class="rounded-xl sm:rounded-2xl bg-emerald-50 text-emerald-600 mr-2 sm:mr-3 d-flex align-items-center justify-content-center shrink-0" style="width: 42px; height: 42px;">
                            <i class="fas fa-trophy text-base sm:text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-[10px] sm:text-xs font-semibold text-slate-400 uppercase text-truncate">Ujian Selesai</div>
                            <div class="text-xs sm:text-lg font-bold text-emerald-950 text-truncate">{{ $hasilUjians->count() }} Hasil</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- ════════════════════════════════════════════════════════════ -->
                <!-- KOLOM KIRI: PILIH SISWA & DAFTAR UJIAN TERSIMPAN             -->
                <!-- ════════════════════════════════════════════════════════════ -->
                <div class="col-lg-5 mb-4">
                    <!-- Card 1: Pilih Siswa Bimbingan -->
                    <div class="card border-0 shadow-sm rounded-2xl bg-white mb-4 overflow-hidden">
                        <div class="card-header bg-purple-900 text-white py-3 px-4 d-flex justify-content-between align-items-center">
                            <h5 class="card-title font-bold mb-0 text-base d-flex align-items-center">
                                <i class="fas fa-user-graduate text-amber-300 mr-2"></i> 1. Pilih Siswa Bimbingan
                            </h5>
                            <span class="badge bg-white/20 text-purple-200 text-xs px-2.5 py-1 rounded-md font-bold">
                                {{ count($assignedStudents) }} Siswa
                            </span>
                        </div>
                        <div class="card-body p-3 bg-slate-50/50">
                            @if ($assignedStudents->isEmpty())
                                <div class="text-center py-4 text-slate-400">
                                    <i class="fas fa-user-slash fa-2x mb-2 d-block opacity-40"></i>
                                    <span class="text-xs font-medium">Belum ada siswa bimbingan yang ditugaskan kepada Anda.</span>
                                </div>
                            @else
                                <div class="space-y-2" style="max-height: 280px; overflow-y: auto;">
                                    @foreach ($assignedStudents as $s)
                                        @php
                                            $isSelected = $selectedSiswa && $selectedSiswa->id == $s->id;
                                            $sAssignedExams = $s->biodata['assigned_ujian'] ?? [];
                                        @endphp
                                        <a href="{{ route('guru.ujian.index', ['siswa_id' => $s->id]) }}" class="d-block text-decoration-none">
                                            <div class="p-3 rounded-xl border transition-all d-flex align-items-center justify-content-between {{ $isSelected ? 'bg-purple-100/70 border-purple-400 shadow-sm' : 'bg-white border-slate-200 hover:border-purple-300' }}">
                                                <div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($s->name) }}&background={{ $isSelected ? '581c87' : 'e9d5ff' }}&color={{ $isSelected ? 'ffffff' : '581c87' }}&bold=true" class="rounded-full mr-3 border border-purple-200" style="width: 38px; height: 38px;" alt="Avatar">
                                                    <div>
                                                        <div class="font-bold text-sm {{ $isSelected ? 'text-purple-950' : 'text-slate-800' }}">{{ $s->name }}</div>
                                                        <div class="text-xxs text-slate-500 font-medium">
                                                            {{ $s->sekolah ?: 'Sekolah -' }} • Kelas {{ $s->biodata['kelas'] ?? '-' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <span class="badge {{ count($sAssignedExams) > 0 ? 'bg-teal-600 text-white' : 'bg-slate-200 text-slate-600' }} rounded-full px-2.5 py-1 text-xs font-bold">
                                                        {{ count($sAssignedExams) }} Penugasan
                                                    </span>
                                                    @if ($isSelected)
                                                        <i class="fas fa-check-circle text-purple-700 ml-1.5 align-middle"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($selectedSiswa)
                        <!-- Card 2: Ringkasan Ujian Ditugaskan ke Siswa Terpilih -->
                        <div class="card border-0 shadow-sm rounded-2xl bg-white mb-4 overflow-hidden">
                            <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="card-title font-bold text-purple-950 mb-0 text-base d-flex align-items-center">
                                    <i class="fas fa-tasks text-purple-600 mr-2"></i> Penugasan Aktif — {{ $selectedSiswa->name }}
                                </h5>
                                <span class="badge bg-purple-100 text-purple-900 font-bold px-2.5 py-1 rounded-full text-xs">
                                    {{ count($assignedExams) }} Ditugaskan
                                </span>
                            </div>
                            <div class="card-body p-3">
                                @if (empty($assignedExams))
                                    <div class="text-center py-4 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                        <i class="fas fa-clipboard-list text-slate-300 fa-2x mb-2 d-block"></i>
                                        <p class="text-xs font-semibold text-slate-500 mb-1">Belum ada ujian yang ditugaskan ke {{ $selectedSiswa->name }}.</p>
                                        <span class="text-xxs text-slate-400">Pilih paket soal di sebelah kanan lalu klik "Tugaskan ke Siswa".</span>
                                    </div>
                                @else
                                    <div class="space-y-3">
                                        @foreach ($assignedExams as $ex)
                                            <div class="p-3 rounded-xl border border-slate-200 bg-white shadow-xs">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div>
                                                        <span class="badge bg-purple-100 text-purple-900 font-bold px-2 py-0.5 rounded text-[11px]">
                                                            {{ $ex['jenjang'] ?? 'SD' }} — {{ $ex['nama_kategori'] ?? 'Soal' }}
                                                        </span>
                                                        <h6 class="font-bold text-slate-900 mb-0 text-sm mt-1">
                                                            {{ $ex['deskripsi'] ?: ($ex['nama_kategori'] ?? 'Paket Soal') }}
                                                        </h6>
                                                    </div>
                                                    <form action="{{ route('guru.ujian.unassign') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan penugasan ujian ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="siswa_id" value="{{ $selectedSiswa->id }}">
                                                        <input type="hidden" name="assignment_id" value="{{ $ex['id'] ?? '' }}">
                                                        <button type="submit" class="btn btn-xs btn-outline-danger font-bold rounded-lg px-2 py-1 text-xs" title="Batalkan Penugasan">
                                                            <i class="fas fa-trash-alt mr-1"></i> Batal
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="text-xs text-slate-500 bg-slate-50 p-2 rounded-lg mb-2 border border-slate-100">
                                                    <i class="fas fa-comment-alt text-purple-500 mr-1"></i> Catatan Guru: {{ $ex['catatan'] ?? 'Kerjakan dengan cermat.' }}
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center text-xxs text-slate-400 font-medium">
                                                    <span><i class="far fa-clock mr-1"></i> Ditugaskan: {{ $ex['tanggal_ditugaskan'] ?? '-' }}</span>
                                                    @if (!empty($ex['tgl_deadline']))
                                                        <span class="text-rose-600 font-bold"><i class="fas fa-calendar-times mr-1"></i> Tenggat: {{ date('d M Y', strtotime($ex['tgl_deadline'])) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Card 3: Hasil & Nilai Ujian Siswa -->
                        <div class="card border-0 shadow-sm rounded-2xl bg-white overflow-hidden">
                            <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="card-title font-bold text-purple-950 mb-0 text-base d-flex align-items-center">
                                    <i class="fas fa-trophy text-amber-500 mr-2"></i> Hasil & Nilai Ujian Siswa
                                </h5>
                                <span class="badge bg-emerald-100 text-emerald-800 font-bold px-2.5 py-1 rounded-full text-xs">
                                    {{ $hasilUjians->count() }} Dikerjakan
                                </span>
                            </div>
                            <div class="card-body p-3">
                                @if ($hasilUjians->isEmpty())
                                    <div class="text-center py-4 text-slate-400">
                                        <i class="fas fa-poll-h fa-2x mb-2 d-block opacity-40"></i>
                                        <span class="text-xs font-medium">Siswa ini belum menyelesaikan ujian apa pun.</span>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped text-xs mb-0">
                                            <thead class="bg-slate-100 text-slate-700 font-bold">
                                                <tr>
                                                    <th>Mata Pelajaran / Judul</th>
                                                    <th class="text-center">Benar / Salah</th>
                                                    <th class="text-center">Nilai</th>
                                                    <th class="text-right">Waktu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($hasilUjians as $h)
                                                    <tr>
                                                        <td class="font-bold text-slate-900">
                                                            {{ $h->kategori->deskripsi ?: ($h->kategori->nama_kategori ?? 'Ujian') }}
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-emerald-100 text-emerald-800 font-bold px-1.5 py-0.5 rounded">{{ $h->jumlah_benar }}B</span>
                                                            <span class="badge bg-rose-100 text-rose-800 font-bold px-1.5 py-0.5 rounded">{{ $h->jumlah_salah }}S</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge {{ $h->nilai >= 75 ? 'bg-emerald-600' : ($h->nilai >= 60 ? 'bg-amber-500' : 'bg-rose-600') }} text-white font-extrabold px-2.5 py-1 rounded-md text-xs">
                                                                {{ number_format($h->nilai, 1) }}
                                                            </span>
                                                        </td>
                                                        <td class="text-right text-slate-400 text-xxs">
                                                            {{ $h->created_at ? $h->created_at->format('d M Y H:i') : '-' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- ════════════════════════════════════════════════════════════ -->
                <!-- KOLOM KANAN: BANK SOAL & FORM TUGASKAN SOAL                  -->
                <!-- ════════════════════════════════════════════════════════════ -->
                <div class="col-lg-7 mb-4">
                    <div class="card border-0 shadow-sm rounded-2xl bg-white overflow-hidden">
                        <div class="card-header bg-white py-3 px-4 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <h5 class="card-title font-bold text-purple-950 mb-0 text-base d-flex align-items-center">
                                    <i class="fas fa-book-open text-purple-600 mr-2"></i> 2. Pilih Paket Soal dari Bank Soal
                                </h5>
                                <p class="text-xxs text-slate-400 mb-0">Pilih paket soal yang akan di-ujian-kan ke siswa terpilih.</p>
                            </div>
                            <span class="badge bg-purple-100 text-purple-900 font-bold px-3 py-1.5 rounded-full text-xs">
                                Total {{ $allCategories->count() }} Paket Soal
                            </span>
                        </div>
                        <div class="card-body p-4 bg-slate-50/40">

                            <!-- Client-side filter input & Jenjang pills -->
                            <div class="row g-2 mb-4 align-items-center">
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="kategoriSearchInput" class="form-control rounded-xl border-slate-200 text-xs font-semibold" placeholder="Cari judul paket atau mapel..." style="border-radius: 12px 0 0 12px;">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-purple-900 text-white border-purple-900" style="border-radius: 0 12px 12px 0;"><i class="fas fa-search"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <button type="button" class="btn btn-xs btn-purple active filter-jenjang-btn font-bold px-3 py-1.5" data-jenjang="ALL">Semua</button>
                                        <button type="button" class="btn btn-xs btn-outline-purple filter-jenjang-btn font-bold px-3 py-1.5" data-jenjang="SD">SD</button>
                                        <button type="button" class="btn btn-xs btn-outline-purple filter-jenjang-btn font-bold px-3 py-1.5" data-jenjang="SMP">SMP</button>
                                        <button type="button" class="btn btn-xs btn-outline-purple filter-jenjang-btn font-bold px-3 py-1.5" data-jenjang="SMA">SMA</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Daftar Paket Soal Grid -->
                            @if ($allCategories->isEmpty())
                                <div class="text-center py-5 bg-white rounded-2xl border border-slate-200">
                                    <i class="fas fa-folder-open text-slate-300 fa-3x mb-3"></i>
                                    <p class="text-slate-500 font-semibold mb-1">Bank Soal Masih Kosong.</p>
                                    <p class="text-xs text-slate-400 mb-3">Buat paket soal dan masukkan soal terlebih dahulu di menu Bank Soal.</p>
                                    <a href="{{ route('guru.bank-soal.index') }}" class="btn btn-sm btn-purple font-bold rounded-xl px-4 py-2 text-xs shadow-xs">
                                        <i class="fas fa-plus-circle mr-1"></i> Ke Menu Bank Soal
                                    </a>
                                </div>
                            @else
                                <div class="row g-3" id="kategoriContainer">
                                    @foreach ($allCategories as $cat)
                                        @php
                                            $qCount = $cat->bank_soals_count;
                                            $isAssignedToSelected = false;
                                            if ($selectedSiswa) {
                                                foreach ($assignedExams as $aEx) {
                                                    if (isset($aEx['kategori_soal_id']) && $aEx['kategori_soal_id'] == $cat->id) {
                                                        $isAssignedToSelected = true;
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp
                                        <div class="col-md-6 mb-3 kategori-card-item" data-jenjang="{{ strtoupper($cat->jenjang) }}" data-search="{{ strtolower(($cat->deskripsi ?: '') . ' ' . $cat->nama_kategori . ' ' . $cat->jenjang . ' kelas ' . $cat->kelas) }}">
                                            <div class="card border border-slate-200 rounded-2xl shadow-xs overflow-hidden h-100 bg-white hover:border-purple-400 transition-all">
                                                <div class="card-header bg-slate-50/80 py-2.5 px-3.5 border-bottom d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-purple-900 text-white font-extrabold px-2.5 py-1 rounded-lg text-xs">
                                                        {{ $cat->jenjang }} — Kelas {{ $cat->kelas }}
                                                    </span>
                                                    <span class="badge bg-purple-100 text-purple-900 font-bold px-2 py-0.5 rounded text-[11px]">
                                                        {{ $cat->sub_kategori }}
                                                    </span>
                                                </div>
                                                <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                                                    <div class="mb-3">
                                                        <div class="text-xs font-bold text-purple-700 uppercase tracking-wider mb-1">
                                                            <i class="fas fa-book mr-1"></i> {{ $cat->nama_kategori }}
                                                        </div>
                                                        <h6 class="font-bold text-slate-900 mb-1 text-sm">
                                                            {{ $cat->deskripsi ?: $cat->nama_kategori }}
                                                        </h6>
                                                        <div class="d-flex align-items-center text-xs text-slate-500 font-semibold mt-2">
                                                            <i class="fas fa-list-ol text-purple-500 mr-1.5"></i> Total: <strong>{{ $qCount }} Soal</strong>
                                                        </div>
                                                    </div>

                                                    <div class="pt-2 border-top">
                                                        @if (!$selectedSiswa)
                                                            <button type="button" class="btn btn-sm btn-light w-100 text-slate-400 font-bold rounded-xl text-xs disabled">
                                                                <i class="fas fa-info-circle mr-1"></i> Pilih Siswa di Kiri Dulu
                                                            </button>
                                                        @elseif ($isAssignedToSelected)
                                                            <button type="button" class="btn btn-sm btn-emerald w-100 text-white font-bold rounded-xl text-xs shadow-xs disabled" style="background-color: #059669; border-color: #059669;">
                                                                <i class="fas fa-check-circle mr-1"></i> Sudah Ditugaskan
                                                            </button>
                                                        @elseif ($qCount === 0)
                                                            <button type="button" class="btn btn-sm btn-light w-100 text-slate-400 font-bold rounded-xl text-xs disabled">
                                                                <i class="fas fa-exclamation-triangle mr-1"></i> Soal Belum Diisi
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-purple w-100 font-bold rounded-xl text-xs shadow-xs" data-toggle="modal" data-target="#modalTugaskan{{ $cat->id }}">
                                                                <i class="fas fa-paper-plane mr-1"></i> Tugaskan ke {{ strtok($selectedSiswa->name, " ") }}
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Tugaskan Soal -->
                                        @if ($selectedSiswa && $qCount > 0)
                                            <div class="modal fade" id="modalTugaskan{{ $cat->id }}" tabindex="-1" role="dialog" aria-labelledby="modalTugaskanLabel{{ $cat->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content border-0 shadow-lg rounded-2xl overflow-hidden">
                                                        <div class="modal-header bg-purple-900 text-white py-3 px-4">
                                                            <h5 class="modal-title font-bold text-base d-flex align-items-center" id="modalTugaskanLabel{{ $cat->id }}">
                                                                <i class="fas fa-paper-plane text-amber-300 mr-2"></i> Konfirmasi Penugasan Ujian
                                                            </h5>
                                                            <button type="button" class="close text-white opacity-80 hover:opacity-100" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('guru.ujian.assign') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="siswa_id" value="{{ $selectedSiswa->id }}">
                                                            <input type="hidden" name="kategori_soal_id" value="{{ $cat->id }}">

                                                            <div class="modal-body p-4 bg-slate-50/50">
                                                                <div class="alert alert-purple bg-purple-50 border border-purple-200 rounded-xl p-3 mb-3 text-xs text-purple-950">
                                                                    <div class="d-flex align-items-center mb-1">
                                                                        <i class="fas fa-user-graduate text-purple-600 mr-2 fa-lg"></i>
                                                                        <strong>Siswa Penerima: {{ $selectedSiswa->name }}</strong>
                                                                    </div>
                                                                    <div class="text-slate-600 pl-4">
                                                                        Paket Ujian: <strong>{{ $cat->deskripsi ?: $cat->nama_kategori }}</strong><br>
                                                                        Mata Pelajaran: {{ $cat->nama_kategori }} ({{ $cat->jenjang }} - Kelas {{ $cat->kelas }})<br>
                                                                        Jumlah Pertanyaan: {{ $qCount }} Soal Pilihan Ganda
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label text-xs font-bold text-slate-700 uppercase">Catatan / Instruksi untuk Siswa</label>
                                                                    <textarea name="catatan" class="form-control rounded-xl text-xs border-slate-200" rows="3" placeholder="Contoh: Silakan dikerjakan sebelum pertemuan les berikutnya hari Rabu.">Silakan dikerjakan dengan jujur &amp; cermat.</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-white py-2.5 px-4 border-top">
                                                                <button type="button" class="btn btn-sm btn-light font-bold rounded-xl px-3" data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-sm btn-purple font-bold rounded-xl px-4">
                                                                    <i class="fas fa-check-circle mr-1"></i> Kirim Penugasan Ujian
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Custom Styling -->
    <style>
        .btn-purple {
            background-color: #581c87;
            color: #ffffff;
            border: none;
        }
        .btn-purple:hover, .btn-purple:focus {
            background-color: #3b0764;
            color: #ffffff;
        }
        .btn-outline-purple {
            color: #581c87;
            border-color: #c084fc;
            background-color: #f3e8ff;
        }
        .btn-outline-purple:hover, .btn-outline-purple:focus, .btn-outline-purple.active {
            background-color: #581c87 !important;
            color: #ffffff !important;
            border-color: #581c87 !important;
        }
        .rounded-2xl {
            border-radius: 20px !important;
        }
        .rounded-xl {
            border-radius: 12px !important;
        }
        .text-xxs {
            font-size: 11px;
        }
    </style>

    <!-- JS Client-side Filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('kategoriSearchInput');
            const items = document.querySelectorAll('.kategori-card-item');
            const filterBtns = document.querySelectorAll('.filter-jenjang-btn');

            let currentJenjang = 'ALL';

            function filterItems() {
                const query = searchInput ? searchInput.value.toLowerCase().trim() : '';

                items.forEach(item => {
                    const itemJenjang = item.getAttribute('data-jenjang') || '';
                    const searchData = (item.getAttribute('data-search') || '').toLowerCase();

                    const matchSearch = searchData.includes(query);
                    const matchJenjang = currentJenjang === 'ALL' || itemJenjang === currentJenjang;

                    if (matchSearch && matchJenjang) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterItems);
            }

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterBtns.forEach(b => {
                        b.classList.remove('active', 'btn-purple');
                        b.classList.add('btn-outline-purple');
                    });
                    this.classList.add('active', 'btn-purple');
                    this.classList.remove('btn-outline-purple');

                    currentJenjang = this.getAttribute('data-jenjang');
                    filterItems();
                });
            });
        });
    </script>
@endsection
