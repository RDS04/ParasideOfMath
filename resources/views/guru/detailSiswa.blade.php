@extends('layout.app')

@php
    $currentGurus = $currentGurus ?? [];
    $mapelJadwal = $mapelJadwal ?? [];
    $tutorPerMapel = $tutorPerMapel ?? [];
    $bio = $student->biodata ?? [];

    $namaPanggilan = $bio['nama_panggilan'] ?? '-';
    $noTelp = $bio['no_telp'] ?? '-';
    $jenisKelamin = $bio['jenis_kelamin'] ?? '-';
    $tempatLahir = $bio['tempat_lahir'] ?? '-';
    $tanggalLahir = isset($bio['tanggal_lahir']) && $bio['tanggal_lahir'] ? date('d-m-Y', strtotime($bio['tanggal_lahir'])) : '-';
    $kelas = $bio['kelas'] ?? '-';
    $jurusan = $bio['jurusan'] ?? '-';
    $sekolah = $bio['sekolah'] ?? '-';
    $alamat = $bio['alamat'] ?? '-';
    $igSiswa = $bio['ig_siswa'] ?? '-';

    $ibuNamaLengkap = $bio['ibu_nama_lengkap'] ?? '-';
    $ibuNoHp = $bio['ibu_no_hp'] ?? '-';
    $ibuPekerjaan = $bio['ibu_pekerjaan'] ?? '-';

    $ayahNamaLengkap = $bio['ayah_nama_lengkap'] ?? '-';
    $ayahNoHp = $bio['ayah_no_hp'] ?? '-';
    $ayahPekerjaan = $bio['ayah_pekerjaan'] ?? '-';

    $hariPilihan = $bio['hari_pertemuan'] ?? [];
    if (empty($hariPilihan) && !empty($bio['hari_per_mapel']) && is_array($bio['hari_per_mapel'])) {
        foreach ($bio['hari_per_mapel'] as $hariArr) {
            if (is_array($hariArr)) {
                foreach ($hariArr as $hari) {
                    if ($hari) $hariPilihan[] = $hari;
                }
            }
        }
        $hariPilihan = array_values(array_unique($hariPilihan));
    }
    if (empty($hariPilihan) && $student->tipe_paket) {
        if (preg_match('/Hari:\s*([^)|]+)/i', $student->tipe_paket, $matches)) {
            $hariPilihan = array_map('trim', explode(',', $matches[1]));
        }
    }

    $jamBimbel = $bio['jam_bimbel'] ?? '-';

    $statusBadgeMap = [
        'active'       => ['label' => 'Siswa Aktif',   'class' => 'bg-emerald-500 text-white'],
        'nonaktif'     => ['label' => 'Nonaktif',      'class' => 'bg-red-500 text-white'],
        'under_review' => ['label' => 'Menunggu',      'class' => 'bg-amber-500 text-white'],
        'rejected'     => ['label' => 'Ditolak',       'class' => 'bg-red-500 text-white'],
        'pending'      => ['label' => 'Pending',       'class' => 'bg-slate-400 text-white'],
    ];
    $statusBadge = $statusBadgeMap[$student->status] ?? ['label' => 'Aktif', 'class' => 'bg-emerald-500 text-white'];

    $waStudent = preg_replace('/[^0-9]/', '', $noTelp !== '-' ? $noTelp : ($student->whatsapp ?? ''));
    if ($waStudent && str_starts_with($waStudent, '0')) {
        $waStudent = '62' . substr($waStudent, 1);
    }
@endphp

@section('title', 'Profil Siswa · ' . $student->name)

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Detail Profil Siswa</h1>
                    <p class="text-sm text-muted mb-0">Informasi biodata, sekolah, dan jadwal bimbingan belajar siswa.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}" class="text-purple-600">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('guru.siswa') }}" class="text-purple-600">Siswa Bimbingan</a></li>
                        <li class="breadcrumb-item active">Detail Profil</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Action Bar -->
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('guru.dashboard') }}" class="btn btn-sm btn-light border rounded-xl font-weight-bold text-purple-950 px-3 py-2 shadow-xs">
                    <i class="fas fa-arrow-left mr-1.5 text-purple-600"></i> Kembali
                </a>
                @if($waStudent)
                    <a href="https://wa.me/{{ $waStudent }}?text=Halo%20{{ urlencode($student->name) }},%20saya%20tutor%20Anda%20dari%20Paradise%20of%20Math..." target="_blank" class="btn btn-sm btn-emerald font-weight-bold px-3 py-2 rounded-xl shadow-sm text-white" style="background-color: #10b981; border: none;">
                        <i class="fab fa-whatsapp mr-1.5 text-lg align-middle"></i> <span class="align-middle">Hubungi Siswa (WA)</span>
                    </a>
                @endif
            </div>

            <div class="row">
                <!-- LEFT COLUMN: Profile Brief & Contact Info (4 cols) -->
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm border-light rounded-2xl overflow-hidden mb-4">
                        <div class="card-header text-white py-3 d-flex align-items-center justify-content-between" style="background-color: #2e1065;">
                            <h5 class="card-title font-weight-bold text-md mb-0 text-white">Kartu Identitas</h5>
                            <span class="badge {{ $statusBadge['class'] }} text-xs font-bold uppercase px-2.5 py-1 rounded-full">
                                {{ $statusBadge['label'] }}
                            </span>
                        </div>
                        <div class="card-body p-4 text-center">
                            <!-- Avatar -->
                            <div class="avatar bg-purple-100 text-purple-800 font-bold d-flex justify-content-center align-items-center rounded-circle mx-auto mb-3 shadow-xs" style="width: 84px; height: 84px; font-size: 34px; border: 3px solid #ddd6fe;">
                                {{ strtoupper(substr($student->name ?? 'S', 0, 1)) }}
                            </div>

                            <!-- Name -->
                            <h4 class="font-weight-bold text-purple-950 mb-1">
                                {{ $student->name }}
                            </h4>
                            @if($namaPanggilan !== '-')
                                <p class="text-sm text-muted mb-2">Panggilan: <strong>{{ $namaPanggilan }}</strong></p>
                            @endif

                            <div class="d-flex justify-content-center gap-2 mb-3">
                                <span class="badge bg-purple-100 text-purple-800 px-3 py-1 text-xs font-semibold rounded-full border border-purple-200">
                                    <i class="fas fa-graduation-cap mr-1"></i> {{ $kelas !== '-' ? 'Kelas ' . $kelas : 'Siswa' }}
                                </span>
                                @if($jurusan !== '-')
                                    <span class="badge bg-teal-100 text-teal-800 px-3 py-1 text-xs font-semibold rounded-full border border-teal-200">
                                        {{ $jurusan }}
                                    </span>
                                @endif
                            </div>

                            <hr class="my-3 border-slate-100">

                            <!-- Details Table -->
                            <table class="table table-borderless table-sm text-left mb-0 text-sm">
                                <tbody>
                                    <tr>
                                        <td class="text-muted py-1.5" style="width: 40%;"><i class="far fa-envelope mr-1.5 text-purple-500"></i> Email</td>
                                        <td class="font-weight-semibold text-purple-950 py-1.5 text-break">{{ $student->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1.5"><i class="fas fa-phone mr-1.5 text-emerald-500"></i> WhatsApp</td>
                                        <td class="font-weight-semibold text-purple-950 py-1.5">{{ $noTelp !== '-' ? $noTelp : ($student->whatsapp ?? '-') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1.5"><i class="fas fa-school mr-1.5 text-amber-500"></i> Sekolah</td>
                                        <td class="font-weight-semibold text-purple-950 py-1.5">{{ $sekolah !== '-' ? $sekolah : ($student->sekolah ?? '-') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1.5"><i class="fas fa-venus-mars mr-1.5 text-indigo-500"></i> Gender</td>
                                        <td class="font-weight-semibold text-purple-950 py-1.5">{{ $jenisKelamin }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1.5"><i class="far fa-calendar-alt mr-1.5 text-pink-500"></i> TTL</td>
                                        <td class="font-weight-semibold text-purple-950 py-1.5">
                                            {{ $tempatLahir !== '-' ? $tempatLahir . ', ' : '' }}{{ $tanggalLahir }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Paket, Jadwal & Data Orang Tua (8 cols) -->
                <div class="col-lg-8 mb-4">
                    <!-- Paket & Jadwal Bimbingan Card -->
                    <div class="card shadow-sm border-light rounded-2xl overflow-hidden mb-4">
                        <div class="card-header text-white py-3 d-flex align-items-center justify-content-between" style="background-color: #2e1065;">
                            <h5 class="card-title font-weight-bold text-md mb-0 text-white">
                                <i class="fas fa-book-open mr-2 text-amber-300"></i> Paket &amp; Sesi Bimbingan
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="p-3 rounded-xl border border-purple-100 bg-purple-50/50">
                                        <span class="text-xxs font-bold text-purple-600 uppercase tracking-wider d-block mb-1">Paket Belajar</span>
                                        <h5 class="font-weight-bold text-purple-950 mb-1">
                                            {{ $paket->nama_paket ?? ($student->paket->nama_paket ?? 'Paket Bimbingan') }}
                                        </h5>
                                        <span class="text-xs text-muted">
                                            <i class="far fa-clock mr-1 text-purple-500"></i> {{ $paket->durasi ?? '-' }} per Sesi
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-xl border border-emerald-100 bg-emerald-50/50">
                                        <span class="text-xxs font-bold text-emerald-600 uppercase tracking-wider d-block mb-1">Jam & Hari Bimbingan</span>
                                        <h5 class="font-weight-bold text-emerald-950 mb-1">
                                            {{ $jamBimbel !== '-' ? $jamBimbel : 'Belum diatur' }}
                                        </h5>
                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                            @if(!empty($hariPilihan))
                                                @foreach($hariPilihan as $h)
                                                    <span class="badge bg-emerald-200 text-emerald-900 text-xxs font-bold px-2 py-0.5 rounded">
                                                        {{ ucfirst($h) }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-xs text-muted">Hari belum ditentukan</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mapel & Tutor Table -->
                            <h6 class="font-weight-bold text-purple-950 mb-3">
                                <i class="fas fa-chalkboard-teacher mr-1.5 text-purple-600"></i> Mata Pelajaran &amp; Pengajar
                            </h6>
                            @if(!empty($mapelJadwal))
                                <div class="table-responsive rounded-xl border border-slate-100 mb-2">
                                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                                        <thead class="bg-purple-950 text-white text-xs">
                                            <tr>
                                                <th class="py-2.5 px-3">Mata Pelajaran</th>
                                                <th class="py-2.5 px-3">Tutor / Guru Pendamping</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mapelJadwal as $idx => $m)
                                                @php
                                                    $assignedTutor = $tutorPerMapel[$m] ?? ($currentGurus[$idx] ?? '-');
                                                @endphp
                                                <tr>
                                                    <td class="py-2.5 px-3 font-weight-bold text-purple-950">
                                                        <i class="fas fa-book text-purple-400 mr-2"></i> {{ $m }}
                                                    </td>
                                                    <td class="py-2.5 px-3">
                                                        <span class="badge bg-purple-100 text-purple-900 font-semibold px-2.5 py-1 rounded-lg">
                                                            <i class="fas fa-user-check mr-1 text-emerald-600"></i> {{ $assignedTutor }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-sm text-muted bg-slate-50 p-3 rounded-xl border border-slate-200 mb-2">
                                    <em>Belum ada daftar mata pelajaran spesifik yang diatur untuk siswa ini.</em>
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Data Orang Tua Card -->
                    <div class="card shadow-sm border-light rounded-2xl overflow-hidden mb-4">
                        <div class="card-header text-white py-3 d-flex align-items-center justify-content-between" style="background-color: #2e1065;">
                            <h5 class="card-title font-weight-bold text-md mb-0 text-white">
                                <i class="fas fa-users mr-2 text-emerald-300"></i> Informasi Kontak Orang Tua
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- Data Ibu -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded-xl border border-pink-100 bg-pink-50/30">
                                        <h6 class="font-weight-bold text-pink-900 mb-2">
                                            <i class="fas fa-female mr-1.5 text-pink-500"></i> Data Ibu
                                        </h6>
                                        <table class="table table-borderless table-sm mb-0 text-xs">
                                            <tbody>
                                                <tr>
                                                    <td class="text-muted" style="width: 35%;">Nama</td>
                                                    <td class="font-weight-bold text-purple-950">{{ $ibuNamaLengkap }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Pekerjaan</td>
                                                    <td class="font-weight-semibold text-purple-900">{{ $ibuPekerjaan }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">No. HP / WA</td>
                                                    <td class="font-weight-semibold">
                                                        @if($ibuNoHp !== '-')
                                                            @php
                                                                $waIbu = preg_replace('/[^0-9]/', '', $ibuNoHp);
                                                                if (str_starts_with($waIbu, '0')) $waIbu = '62' . substr($waIbu, 1);
                                                            @endphp
                                                            <a href="https://wa.me/{{ $waIbu }}" target="_blank" class="text-emerald-700 font-bold hover:underline">
                                                                <i class="fab fa-whatsapp text-emerald-600 mr-1"></i> {{ $ibuNoHp }}
                                                            </a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Data Ayah -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded-xl border border-blue-100 bg-blue-50/30">
                                        <h6 class="font-weight-bold text-blue-900 mb-2">
                                            <i class="fas fa-male mr-1.5 text-blue-500"></i> Data Ayah
                                        </h6>
                                        <table class="table table-borderless table-sm mb-0 text-xs">
                                            <tbody>
                                                <tr>
                                                    <td class="text-muted" style="width: 35%;">Nama</td>
                                                    <td class="font-weight-bold text-purple-950">{{ $ayahNamaLengkap }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Pekerjaan</td>
                                                    <td class="font-weight-semibold text-purple-900">{{ $ayahPekerjaan }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">No. HP / WA</td>
                                                    <td class="font-weight-semibold">
                                                        @if($ayahNoHp !== '-')
                                                            @php
                                                                $waAyah = preg_replace('/[^0-9]/', '', $ayahNoHp);
                                                                if (str_starts_with($waAyah, '0')) $waAyah = '62' . substr($waAyah, 1);
                                                            @endphp
                                                            <a href="https://wa.me/{{ $waAyah }}" target="_blank" class="text-emerald-700 font-bold hover:underline">
                                                                <i class="fab fa-whatsapp text-emerald-600 mr-1"></i> {{ $ayahNoHp }}
                                                            </a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
