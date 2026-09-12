@extends('layout.app')

@section('title', 'Persetujuan Pendaftaran Siswa · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Persetujuan Pendaftaran &amp; Pembayaran</h1>
                    <p class="text-sm text-muted mb-0">Kelola pendaftaran siswa baru, atur tipe pertemuan, jadwal hari, jam, dan tutor pengajar.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600">Dashboard</a></li>
                        <li class="breadcrumb-item active">Persetujuan Siswa</li>
                    </ol>
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
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold text-purple-950 mb-0">Daftar Pengajuan Registrasi Siswa Baru</h3>
                    <span class="badge bg-purple-100 text-purple-800 font-bold px-3 py-1 text-xs rounded-pill">
                        {{ count($students) }} Menunggu Persetujuan
                    </span>
                </div>
                <div class="card-body p-0">
                    @if ($students->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-user-check text-slate-300 fa-3x mb-3"></i>
                            <p class="text-slate-500 text-sm mb-0">Tidak ada pengajuan registrasi siswa baru yang memerlukan persetujuan saat ini.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-slate-500">
                                    <tr>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Nama Siswa</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Email</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Paket Pilihan</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Sekolah/Kelas</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Status</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-right">Aksi &amp; Setting</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach ($students as $student)
                                        @php
                                            $paket = \App\Models\PaketBelajar::find($student->paket_id) ?? \App\Models\PaketBelajar::first();
                                            $biodata = $student->biodata ?? [];
                                            $panggilan = $biodata['nama_panggilan'] ?? null;
                                            $mapelList = $biodata['mapel_jadwal'] ?? ($biodata['pending_mapel_jadwal'] ?? []);
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-3 font-weight-bold text-purple-950">
                                                {{ $student->name }}
                                                @if($panggilan)
                                                    <span class="text-xs text-purple-600 font-semibold block">({{ $panggilan }})</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-slate-600">{{ $student->email }}</td>
                                            <td class="px-4 py-3 font-weight-medium">
                                                @if ($paket)
                                                    {{ $paket->nama_paket }} <small class="text-muted block text-xxs">({{ $paket->kategori }})</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-slate-500 text-xs">
                                                {{ $student->sekolah ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($student->status === 'under_review')
                                                    <span class="badge bg-amber-100 text-amber-800 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-amber-200 animate-pulse">Menunggu Verifikasi</span>
                                                @else
                                                    <span class="badge bg-purple-100 text-purple-800 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-purple-200">Pendaftaran Baru</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="d-flex justify-content-end align-items-center gap-2" style="gap: 6px;">
                                                    <button type="button" class="btn btn-sm btn-purple rounded-lg font-weight-bold px-3 text-xs shadow-xs" data-toggle="modal" data-target="#settingApprovModal_{{ $student->id }}">
                                                        <i class="fas fa-sliders-h mr-1"></i> Detail &amp; Atur Persetujuan
                                                    </button>
                                                    <form action="{{ route('admin.siswa.reject.submit', $student->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Apakah Anda yakin ingin menolak pendaftaran {{ $student->name }}?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-lg font-weight-bold px-2.5 py-1 text-xs" title="Tolak Pendaftaran">
                                                            <i class="fas fa-times-circle"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- MODAL SETTING PERSETUJUAN SISWA -->
                                        <div class="modal fade text-left" id="settingApprovModal_{{ $student->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel_{{ $student->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                                <div class="modal-content rounded-2xl border-0 shadow-2xl overflow-hidden">
                                                    <div class="modal-header text-white py-3.5 px-4" style="background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%);">
                                                        <h5 class="modal-title font-weight-bold text-base d-flex align-items-center" id="modalLabel_{{ $student->id }}">
                                                            <i class="fas fa-calendar-alt text-amber-400 mr-2"></i> Pengaturan Bimbingan &amp; Penentuan Jadwal Siswa
                                                        </h5>
                                                        <button type="button" class="close text-white opacity-80 hover:opacity-100" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('admin.siswa.approve.submit', $student->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body p-4 bg-slate-50">

                                                            <!-- Brief Student Summary -->
                                                            <div class="p-3.5 bg-white rounded-xl border border-purple-100 mb-4 shadow-2xs">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-7">
                                                                        <h6 class="font-weight-bold text-purple-950 mb-1 text-base">{{ $student->name }} @if($panggilan)<span class="text-purple-600 text-sm">({{ $panggilan }})</span>@endif</h6>
                                                                        <p class="text-xs text-muted mb-0"><i class="far fa-envelope mr-1"></i> {{ $student->email }} &bull; <i class="fas fa-school mr-1"></i> {{ $student->sekolah ?? 'Sekolah -' }}</p>
                                                                    </div>
                                                                    <div class="col-md-5 text-md-right mt-2 mt-md-0">
                                                                        <span class="badge bg-purple-50 text-purple-900 border border-purple-200 px-3 py-1.5 rounded-lg text-xs font-bold">
                                                                            {{ $paket->nama_paket ?? 'Paket Bimbel' }} ({{ $paket->kategori ?? 'Dasar' }})
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- 1. Tipe Pertemuan / Paket -->
                                                            <div class="form-group mb-4">
                                                                <label class="font-weight-bold text-purple-950 text-xs uppercase tracking-wider mb-2 block">
                                                                    <i class="fas fa-users-class text-purple-600 mr-1"></i> 1. Tentukan Tipe Pertemuan / Kelompok Paket<span class="text-danger">*</span>
                                                                </label>
                                                                <select name="tipe_paket" class="form-control rounded-xl border-purple-200 font-weight-bold text-sm text-purple-950 py-2 shadow-2xs" required>
                                                                    @if ($paket)
                                                                        <option value="{{ $paket->detail_1 }}" {{ ($student->tipe_paket == $paket->detail_1) ? 'selected' : '' }}>
                                                                            {{ $paket->detail_1 }} (Privat 1-on-1)
                                                                        </option>
                                                                        <option value="{{ $paket->detail_2 }}" {{ ($student->tipe_paket == $paket->detail_2) ? 'selected' : '' }}>
                                                                            {{ $paket->detail_2 }}
                                                                        </option>
                                                                        <option value="{{ $paket->detail_3 }}" {{ ($student->tipe_paket == $paket->detail_3) ? 'selected' : '' }}>
                                                                            {{ $paket->detail_3 }}
                                                                        </option>
                                                                        <option value="{{ $paket->detail_4 }}" {{ ($student->tipe_paket == $paket->detail_4) ? 'selected' : '' }}>
                                                                            {{ $paket->detail_4 }}
                                                                        </option>
                                                                    @else
                                                                        <option value="Privat 1 Orang: Rp 80.000">Privat 1 Orang: Rp 80.000</option>
                                                                        <option value="Kelompok 2 Orang: Rp 70K/org">Kelompok 2 Orang: Rp 70K/org</option>
                                                                        <option value="Kelompok 3 Orang: Rp 60K/org">Kelompok 3 Orang: Rp 60K/org</option>
                                                                        <option value="Kelompok 4-7 Orang: Rp 50K/org">Kelompok 4-7 Orang: Rp 50K/org</option>
                                                                    @endif
                                                                </select>
                                                            </div>

                                                            <!-- 2. Pengaturan Per-Mata Pelajaran (Hari, Jam & Tutor) -->
                                                            <div class="form-group mb-2">
                                                                <label class="font-weight-bold text-purple-950 text-xs uppercase tracking-wider mb-2 block">
                                                                    <i class="fas fa-calendar-alt text-purple-600 mr-1"></i> 2. Pengaturan Hari, Jam &amp; Tutor Pengajar per Mapel
                                                                </label>

                                                                @if(empty($mapelList))
                                                                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-800 text-xs font-medium">
                                                                        Siswa belum memilih mata pelajaran secara spesifik. Anda tetap dapat menyetujui pendaftaran ini.
                                                                    </div>
                                                                @else
                                                                    @foreach($mapelList as $mIdx => $mName)
                                                                        @php
                                                                            $currentHari = $biodata['hari_per_mapel'][$mIdx] ?? [];
                                                                            if (!is_array($currentHari)) $currentHari = [$currentHari];
                                                                            $currentJam = $biodata['jam_per_mapel'][$mIdx] ?? [];
                                                                            $jamMulai = $currentJam['jam_mulai'] ?? '15:30';
                                                                            $jamSelesai = $currentJam['jam_selesai'] ?? '';
                                                                            if (empty($jamSelesai) || strtotime($jamSelesai) <= strtotime($jamMulai)) {
                                                                                $jamSelesai = date('H:i', strtotime($jamMulai . ' +90 minutes'));
                                                                            }
                                                                            $currentTutor = $biodata['tutor_per_mapel'][$mName] ?? '';
                                                                            $mapelObj = \App\Models\Mapel::where('nama_mapel', $mName)->first();
                                                                            $shiftCount = $mapelObj ? $mapelObj->shift : 2;
                                                                        @endphp
                                                                        <div class="p-3.5 bg-white rounded-xl border border-purple-100 mb-3 shadow-2xs">
                                                                            <h6 class="font-weight-bold text-purple-950 text-sm mb-3 pb-2 border-bottom d-flex align-items-center justify-content-between">
                                                                                <span>
                                                                                    <i class="fas fa-book-reader text-purple-600 mr-1.5"></i> Mata Pelajaran: <strong>{{ $mName }}</strong>
                                                                                    <span class="badge bg-purple-100 text-purple-900 border border-purple-200 text-xs font-bold ml-2 px-2.5 py-1 rounded-pill">
                                                                                        <i class="fas fa-redo-alt text-purple-600 mr-1"></i> {{ $shiftCount }}x / Pekan
                                                                                    </span>
                                                                                </span>
                                                                                <span class="badge bg-purple-50 text-purple-700 text-xxs font-bold px-2 py-0.5 border border-purple-100">Mapel #{{ $mIdx + 1 }}</span>
                                                                            </h6>

                                                                            <div class="row">
                                                                                <!-- Choice Hari (Senin - Sabtu, tanpa Minggu) -->
                                                                                <div class="col-12 mb-3">
                                                                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider d-block mb-1.5">
                                                                                        Hari Bimbingan <span class="text-purple-700 font-semibold lowercase">({{ $shiftCount }}x seminggu)</span>:
                                                                                    </span>
                                                                                    <div class="d-flex flex-wrap gap-2" style="gap: 8px;">
                                                                                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hName)
                                                                                            @php $isChecked = in_array($hName, $currentHari); @endphp
                                                                                            <label class="day-toggle-pill {{ $isChecked ? 'active' : '' }}">
                                                                                                <input type="checkbox" name="hari_per_mapel[{{ $mIdx }}][]" value="{{ $hName }}" class="d-none" {{ $isChecked ? 'checked' : '' }} onchange="this.parentElement.classList.toggle('active', this.checked)">
                                                                                                <i class="fas fa-check text-xxs mr-1 check-ic"></i> {{ $hName }}
                                                                                            </label>
                                                                                        @endforeach
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Jam Mulai & Selesai -->
                                                                                <div class="col-md-6 mb-3">
                                                                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider d-block mb-1.5">
                                                                                        Waktu / Jam Bimbingan <span class="text-purple-600 font-normal lowercase">(otomatis 90 menit)</span>:
                                                                                    </span>
                                                                                    <div class="input-group input-group-sm">
                                                                                        <input type="time" name="jam_per_mapel[{{ $mIdx }}][jam_mulai]" value="{{ $jamMulai }}" class="form-control font-weight-bold rounded-left jam-mulai-input" onchange="autoCalculate90Mins(this)">
                                                                                        <div class="input-group-append input-group-prepend">
                                                                                            <span class="input-group-text text-xxs font-bold bg-slate-100">s/d</span>
                                                                                        </div>
                                                                                        <input type="time" name="jam_per_mapel[{{ $mIdx }}][jam_selesai]" value="{{ $jamSelesai }}" class="form-control font-weight-bold rounded-right jam-selesai-input">
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Tutor Pengajar -->
                                                                                <div class="col-md-6 mb-3">
                                                                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider d-block mb-1.5">Tutor / Guru Pengajar:</span>
                                                                                    <select name="tutor_per_mapel[{{ $mName }}]" class="form-control form-control-sm font-weight-bold rounded-lg text-purple-950">
                                                                                        <option value="">-- Pilih Tutor Pengajar --</option>
                                                                                        <option value="Karyawan" {{ $currentTutor === 'Karyawan' ? 'selected' : '' }}>Karyawan (Tutor Umum)</option>
                                                                                        @foreach($gurus as $g)
                                                                                            <option value="{{ $g->name }}" {{ ($currentTutor === $g->name) ? 'selected' : '' }}>
                                                                                                {{ $g->name }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center">
                                                            <a href="{{ route('admin.siswa.detail', $student->id) }}" class="btn btn-sm btn-light font-weight-bold text-purple-950 border border-slate-200">
                                                                <i class="fas fa-external-link-alt mr-1 text-purple-600"></i> Buka Halaman Detail Penuh
                                                            </a>
                                                            <div class="d-flex align-items-center gap-2" style="gap: 8px;">
                                                                <button type="button" class="btn btn-sm btn-slate font-weight-bold rounded-lg px-3" data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-sm btn-success font-weight-bold rounded-lg px-4 shadow-sm">
                                                                    <i class="fas fa-save mr-1.5"></i> Simpan Pengaturan Jadwal &amp; Lanjut Pembayaran
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                     @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Custom Styling for Interactive Toggle Pills & Purple Brand -->
    <style>
        .btn-purple {
            color: #ffffff;
            background-color: #7c3aed;
            border-color: #7c3aed;
            transition: all 0.2s ease;
        }
        .btn-purple:hover {
            color: #ffffff;
            background-color: #6d28d9;
            border-color: #6d28d9;
        }
        .day-toggle-pill {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            color: #475569;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }
        .day-toggle-pill .check-ic { display: none; }
        .day-toggle-pill.active {
            background-color: #f3e8ff;
            border-color: #c084fc;
            color: #6b21a8;
            font-weight: 700;
            box-shadow: 0 1px 3px rgba(124, 58, 237, 0.15);
        }
        .day-toggle-pill.active .check-ic { display: inline-block; color: #7c3aed; }
        .day-toggle-pill:hover {
            border-color: #a855f7;
        }
        .animate-pulse {
            animation: pulse-glow 2s infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.75; }
        }
    </style>

    <script>
        function autoCalculate90Mins(input) {
            if (!input.value) return;
            const parts = input.value.split(':');
            if (parts.length < 2) return;
            const hours = parseInt(parts[0], 10);
            const minutes = parseInt(parts[1], 10);
            
            let date = new Date();
            date.setHours(hours, minutes, 0, 0);
            date.setMinutes(date.getMinutes() + 90);
            
            const endHours = String(date.getHours()).padStart(2, '0');
            const endMinutes = String(date.getMinutes()).padStart(2, '0');
            
            const group = input.closest('.input-group');
            if (group) {
                const endInput = group.querySelector('.jam-selesai-input');
                if (endInput) {
                    endInput.value = `${endHours}:${endMinutes}`;
                }
            }
        }
    </script>
@endsection
