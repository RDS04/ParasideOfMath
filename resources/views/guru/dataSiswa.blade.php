@extends('layout.app')

@section('title', 'Daftar Siswa Bimbingan · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-teal-950 text-2xl tracking-tight">Daftar Siswa Bimbingan</h1>
                    <p class="text-xs text-muted mb-0">Kelola dan pantau seluruh siswa bimbingan aktif yang ditugaskan kepada Anda.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-xs bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}" class="text-teal-600 font-semibold"><i class="fas fa-home mr-1"></i> Home</a></li>
                        <li class="breadcrumb-item active text-slate-500">Siswa Anda</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Student List Card -->
            <div class="card border-0 shadow-sm rounded-2xl">
                <div class="card-header bg-white py-3 border-0 d-flex flex-column sm:flex-row align-items-start sm:align-items-center justify-content-between" style="gap: 12px;">
                    <div class="d-flex align-items-center">
                        <div class="p-2 rounded-xl bg-teal-50 text-teal-600 mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div>
                            <h3 class="card-title font-weight-bold text-teal-950 mb-0 text-lg">Siswa Aktif Bimbingan Anda</h3>
                            <p class="text-xxs text-muted mb-0">Daftar kontak, sekolah, dan paket belajar yang diambil siswa bimbingan Anda.</p>
                        </div>
                    </div>
                    
                    <!-- Client-side Search Filter -->
                    <div class="d-flex align-items-center flex-wrap w-full sm:w-auto" style="gap: 10px;">
                        <div class="input-group input-group-sm" style="width: 240px; max-width: 100%;">
                            <input type="text" id="siswaSearchInput" class="form-control rounded-xl border-light text-xs" placeholder="Cari nama, sekolah, paket..." style="border-radius: 10px 0 0 10px; font-family: 'Inter', sans-serif;">
                            <div class="input-group-append">
                                <span class="input-group-text bg-light border-light text-teal-600" style="border-radius: 0 10px 10px 0;"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                        <span class="badge bg-teal-50 text-teal-700 border border-teal-200 px-3 py-2 rounded-xl text-xs font-bold shadow-xs">
                            Total: {{ count($assignedStudents) }} Siswa
                        </span>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="bg-light/60 text-teal-950 text-xs font-bold uppercase tracking-wider">
                                    <th class="px-4 py-3 border-0">Siswa</th>
                                    <th class="px-4 py-3 border-0">Asal Sekolah &amp; Kontak</th>
                                    <th class="px-4 py-3 border-0">Paket Belajar &amp; Kelas</th>
                                    <th class="px-4 py-3 border-0">Mata Pelajaran Anda</th>
                                    <th class="px-4 py-3 border-0 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="siswaTableBody" class="text-slate-700 text-sm">
                                @forelse($assignedStudents as $s)
                                    @php
                                        $sBio = $s->biodata ?? [];
                                        $tutorNames = $sBio['tutor_names'] ?? [];
                                        $tutorSubjects = $sBio['tutor_subjects'] ?? [];
                                        
                                        // Find index of logged in teacher
                                        $teacherIndex = is_array($tutorNames) ? array_search(Auth::user()->name, $tutorNames) : false;
                                        $assignedMapel = ($teacherIndex !== false && isset($tutorSubjects[$teacherIndex])) ? $tutorSubjects[$teacherIndex] : '—';
                                    @endphp
                                    <tr>
                                        <!-- Siswa Column -->
                                        <td class="px-4 py-3.5 align-middle">
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($s->name) }}&background=ccfbf1&color=0f766e&bold=true" class="rounded-full mr-3 border border-teal-100 shadow-xs" style="width: 40px; height: 40px;" alt="Avatar">
                                                <div>
                                                    <div class="font-weight-bold text-teal-950 student-name-cell">{{ $s->name }}</div>
                                                    <span class="text-xs text-muted student-email-cell">{{ $s->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- Asal Sekolah & Kontak Column -->
                                        <td class="px-4 py-3.5 align-middle">
                                            <div class="d-flex align-items-center mb-1 text-slate-700 font-semibold student-school-cell">
                                                <i class="fas fa-school text-teal-500 mr-2 text-xs" style="width: 14px;"></i>
                                                <span>{{ $s->sekolah ?? '-' }}</span>
                                            </div>
                                            <div class="d-flex align-items-center text-xs text-muted student-phone-cell">
                                                <i class="fab fa-whatsapp text-emerald-500 mr-2 text-xs" style="width: 14px;"></i>
                                                <span>{{ $s->whatsapp ?? '-' }}</span>
                                            </div>
                                        </td>
                                        
                                        <!-- Paket Belajar Column -->
                                        <td class="px-4 py-3.5 align-middle">
                                            <div class="mb-1">
                                                <span class="badge px-2.5 py-1.5 rounded-lg text-xs font-bold shadow-xs student-paket-cell" style="background-color: #f3e8ff; color: #6b21a8; border: 1px solid #e9d5ff;">
                                                    <i class="fas fa-graduation-cap mr-1"></i> {{ $s->paket ? $s->paket->nama_paket : 'Paket Belajar' }}
                                                </span>
                                            </div>
                                            <div class="text-xxs font-semibold text-slate-500 pl-1">
                                                Tingkat Kelas: <strong class="text-purple-950">{{ $sBio['kelas'] ?? '—' }}</strong>
                                            </div>
                                        </td>
                                        
                                        <!-- Mata Pelajaran Column -->
                                        <td class="px-4 py-3.5 align-middle">
                                            <span class="badge px-2.5 py-1.5 rounded-lg text-xs font-bold shadow-xs student-subject-cell" style="background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;">
                                                <i class="fas fa-book-open mr-1"></i> {{ $assignedMapel }}
                                            </span>
                                            @php
                                                $sSesi = $sBio['jumlah_pertemuan'] ?? null;
                                                if (!$sSesi && $s->tipe_paket) {
                                                    if (preg_match('/Sesi:\s*(\d+)x/i', $s->tipe_paket, $matches)) {
                                                        $sSesi = $matches[1];
                                                    }
                                                }
                                            @endphp
                                            @if($sSesi)
                                                <div class="text-xxs text-muted mt-1 font-semibold pl-1">
                                                    Target: {{ $sSesi }} Sesi / Bulan
                                                </div>
                                            @endif
                                        </td>
                                        
                                        <!-- Aksi Column -->
                                        <td class="px-4 py-3.5 align-middle text-center">
                                            <div class="d-flex justify-content-center align-items-center" style="gap: 6px;">
                                                @if($s->whatsapp)
                                                    @php
                                                        $waClean = preg_replace('/[^0-9]/', '', $s->whatsapp);
                                                        $waFormatted = str_starts_with($waClean, '0') ? '62' . substr($waClean, 1) : $waClean;
                                                    @endphp
                                                    <a href="https://wa.me/{{ $waFormatted }}?text=Halo%20{{ urlencode($s->name) }},%20saya%20tutor%20Anda%20dari%20Paradise%20of%20Math..." target="_blank" class="btn btn-sm btn-outline-emerald px-3 py-1.5 rounded-xl font-bold text-xs" style="border-radius: 10px;">
                                                        <i class="fab fa-whatsapp mr-1 text-sm align-middle"></i> <span class="align-middle">Hubungi</span>
                                                    </a>
                                                @endif
                                                <a href="{{ route('admin.siswa.detail', $s->id) }}" class="btn btn-sm btn-brand px-3 py-1.5 rounded-xl text-xs" style="border-radius: 10px;">
                                                    <i class="fas fa-id-card mr-1 align-middle"></i> <span class="align-middle">Detail</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-user-slash fa-3x mb-3 d-block opacity-40 text-teal-300"></i>
                                            Belum ada siswa bimbingan yang ditugaskan kepada Anda saat ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Custom CSS styles matching the premium dashboard -->
    <style>
        .btn-outline-emerald {
            border: 1px solid #10b981;
            color: #10b981;
            background: #fff;
            transition: all 0.2s ease;
        }
        .btn-outline-emerald:hover {
            background-color: #ecfdf5;
            color: #047857;
            border-color: #059669;
        }
        .btn-brand {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border: none;
            color: #40206b;
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .btn-brand:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            color: #40206b;
        }
        .rounded-2xl {
            border-radius: 20px !important;
        }
        .rounded-xl {
            border-radius: 12px !important;
        }
        .shadow-xs {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>

    <!-- JS search filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('siswaSearchInput');
            const rows = document.querySelectorAll('#siswaTableBody tr');

            if (searchInput && rows.length > 0) {
                searchInput.addEventListener('input', function() {
                    const query = searchInput.value.toLowerCase().trim();
                    rows.forEach(row => {
                        const name = row.querySelector('.student-name-cell')?.textContent.toLowerCase() || '';
                        const email = row.querySelector('.student-email-cell')?.textContent.toLowerCase() || '';
                        const school = row.querySelector('.student-school-cell')?.textContent.toLowerCase() || '';
                        const phone = row.querySelector('.student-phone-cell')?.textContent.toLowerCase() || '';
                        const paket = row.querySelector('.student-paket-cell')?.textContent.toLowerCase() || '';
                        const subject = row.querySelector('.student-subject-cell')?.textContent.toLowerCase() || '';

                        if (
                            name.includes(query) || 
                            email.includes(query) || 
                            school.includes(query) || 
                            phone.includes(query) ||
                            paket.includes(query) ||
                            subject.includes(query)
                        ) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
@endsection
