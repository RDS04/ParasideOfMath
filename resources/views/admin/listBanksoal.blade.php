@extends('layout.app')

@section('title', 'Bank Soal · Paradise of Math')

@php
    $prefixRoute = 'admin.bank-soal';
    $dashRoute = route('admin.dashboard');
@endphp

@section('content')
    <!-- Content Header -->
    <div class="content-header py-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-7">
                    <h1 class="m-0 font-weight-bold text-purple-950 d-flex align-items-center">
                        <i class="fas fa-folder-open text-purple-600 mr-2.5"></i> Bank Soal &amp; Latihan
                    </h1>
                    <p class="text-sm text-slate-500 mb-0 mt-1">
                        Kelola soal secara terstruktur: Mata Pelajaran → Jenjang → Kelas → Semester/TKA.
                    </p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right text-sm bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ $dashRoute }}" class="text-purple-600 font-semibold">Dashboard</a></li>
                        <li class="breadcrumb-item active text-slate-500">Bank Soal</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content pb-5">
        <div class="container-fluid">

            <!-- Alert Flash Notification -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0 d-flex align-items-center" role="alert" style="background-color: #ecfdf5; color: #065f46; border-left: 4px solid #10b981;">
                    <i class="fas fa-check-circle fa-lg mr-3 text-emerald-500"></i>
                    <div>
                        <strong class="font-bold">Berhasil!</strong> {{ session('success') }}
                    </div>
                    <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" class="text-emerald-700">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444;">
                    <strong class="font-bold"><i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444;">
                    <strong class="font-bold"><i class="fas fa-exclamation-circle mr-2"></i> Terdapat kesalahan input:</strong>
                    <ul class="mb-0 mt-1 pl-4 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- ════════════════════════════════════════════════════════════ -->
            <!-- LANGKAH 1: PILIH MATA PELAJARAN                              -->
            <!-- ════════════════════════════════════════════════════════════ -->
            <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center">
                    <span class="badge bg-purple-900 text-white rounded-circle mr-2.5 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 12px;">1</span>
                    <h5 class="card-title font-bold text-purple-950 mb-0 text-base">Pilih Mata Pelajaran</h5>
                    @if($mapel)
                        <span class="badge bg-purple-100 text-purple-900 font-bold ml-3 px-3 py-1 rounded-full text-xs">
                            <i class="fas fa-check-circle text-purple-600 mr-1"></i> {{ $mapel }}
                        </span>
                    @endif
                </div>
                <div class="card-body p-3.5 bg-purple-50/40">
                    @if ($mapelList->isEmpty())
                        <div class="text-center py-4 px-3 bg-purple-50/50 rounded-xl border border-dashed border-purple-200">
                            <i class="fas fa-book text-purple-400 fa-2x mb-2"></i>
                            <h6 class="font-bold text-purple-950 mb-1">Belum Ada Data Mata Pelajaran</h6>
                            <p class="text-slate-500 text-xs max-w-md mx-auto mb-3">
                                Silakan tambahkan mata pelajaran terlebih dahulu lewat menu "Kelola Mata Pelajaran".
                            </p>
                            <a href="{{ route('admin.mapel') }}" class="btn btn-purple btn-sm shadow-sm rounded-xl font-bold px-3.5 py-2 text-xs">
                                <i class="fas fa-plus-circle mr-1"></i> Kelola Mata Pelajaran
                            </a>
                        </div>
                    @else
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            @foreach ($mapelList as $m)
                                @php
                                    $url = route($prefixRoute . '.index', ['mapel' => $m]);
                                @endphp
                                <a href="{{ $url }}"
                                   class="btn font-bold rounded-xl px-4 py-2.5 text-xs transition-all {{ $mapel === $m ? 'btn-purple shadow-md text-white' : 'btn-white border border-slate-300 text-slate-700 hover:bg-purple-100 hover:text-purple-900' }}">
                                    <i class="fas fa-book text-purple-500 mr-2"></i>
                                    {{ $m }}
                                    @if ($mapel === $m)
                                        <i class="fas fa-check ml-1.5 text-amber-300"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- ════════════════════════════════════════════════════════════ -->
            <!-- LANGKAH 2: PILIH JENJANG (muncul jika mapel sudah dipilih)  -->
            <!-- ════════════════════════════════════════════════════════════ -->
            @if($mapel)
                <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                    <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center">
                        <span class="badge bg-purple-900 text-white rounded-circle mr-2.5 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 12px;">2</span>
                        <h5 class="card-title font-bold text-purple-950 mb-0 text-base">
                            Pilih Jenjang Pendidikan <span class="badge bg-purple-100 text-purple-900 font-bold ml-2 px-2.5 py-0.5 text-xs">{{ $mapel }}</span>
                        </h5>
                        @if($jenjang)
                            <span class="badge bg-purple-100 text-purple-900 font-bold ml-3 px-3 py-1 rounded-full text-xs">
                                <i class="fas fa-check-circle text-purple-600 mr-1"></i> {{ $jenjang }}
                            </span>
                        @endif
                    </div>
                    <div class="card-body p-3 bg-purple-50/40">
                        <div class="row g-3">
                            @foreach ([
                                'SD' => ['Sekolah Dasar', 'fa-child'],
                                'SMP' => ['Sekolah Menengah Pertama', 'fa-user-graduate'],
                                'SMA' => ['Sekolah Menengah Atas', 'fa-university']
                            ] as $key => $info)
                                @php
                                    $url = route($prefixRoute . '.index', ['mapel' => $mapel, 'jenjang' => $key]);
                                @endphp
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <a href="{{ $url }}"
                                       class="card border-2 transition-all duration-200 text-decoration-none rounded-xl overflow-hidden h-100 {{ $jenjang === $key ? 'border-purple-800 bg-purple-900 text-white shadow-md' : 'border-slate-200 bg-white text-slate-700 hover:border-purple-400 hover:bg-purple-50' }}">
                                        <div class="card-body p-3.5 d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 shadow-xs"
                                                 style="width: 44px; height: 44px; background: {{ $jenjang === $key ? 'rgba(255,255,255,0.2)' : '#f3e8ff' }}; flex-shrink: 0;">
                                                <i class="fas {{ $info[1] }} fa-lg {{ $jenjang === $key ? 'text-amber-300' : 'text-purple-700' }}"></i>
                                            </div>
                                            <div>
                                                <span class="d-block text-base font-extrabold leading-tight mb-0.5">Jenjang {{ $key }}</span>
                                                <span class="d-block text-xs opacity-90 {{ $jenjang === $key ? 'text-purple-200' : 'text-slate-500' }}">{{ $info[0] }}</span>
                                            </div>
                                            @if ($jenjang === $key)
                                                <i class="fas fa-check-circle ml-auto text-amber-300 fa-lg"></i>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- ════════════════════════════════════════════════════════════ -->
            <!-- LANGKAH 3: PILIH KELAS                                       -->
            <!-- ════════════════════════════════════════════════════════════ -->
            @if($mapel && $jenjang)
                <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                    <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center">
                        <span class="badge bg-purple-900 text-white rounded-circle mr-2.5 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 12px;">3</span>
                        <h5 class="card-title font-bold text-purple-950 mb-0 text-base">
                            Pilih Kelas <span class="badge bg-purple-100 text-purple-900 font-bold ml-2 px-2.5 py-0.5 text-xs">Jenjang {{ $jenjang }}</span>
                        </h5>
                        @if($kelas)
                            <span class="badge bg-purple-100 text-purple-900 font-bold ml-3 px-3 py-1 rounded-full text-xs">
                                <i class="fas fa-check-circle text-purple-600 mr-1"></i> Kelas {{ $kelas }}
                            </span>
                        @endif
                    </div>
                    <div class="card-body p-3.5 bg-slate-50">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            @foreach ($availableClasses as $cls)
                                @php
                                    $url = route($prefixRoute . '.index', ['mapel' => $mapel, 'jenjang' => $jenjang, 'kelas' => $cls]);
                                @endphp
                                <a href="{{ $url }}"
                                   class="btn font-bold rounded-xl px-4 py-2.5 text-xs transition-all {{ (string)$kelas === (string)$cls ? 'btn-purple shadow-md text-white' : 'btn-white border border-slate-300 text-slate-700 hover:bg-purple-100 hover:text-purple-900' }}">
                                    <i class="fas fa-users text-purple-500 mr-2"></i>
                                    Kelas {{ $cls }}
                                    @if ((string)$kelas === (string)$cls)
                                        <i class="fas fa-check ml-1.5 text-amber-300"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- ════════════════════════════════════════════════════════════ -->
            <!-- LANGKAH 4: PILIH SEMESTER / TKA                              -->
            <!-- ════════════════════════════════════════════════════════════ -->
            @if($mapel && $jenjang && $kelas)
                <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white overflow-hidden">
                    <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center">
                        <span class="badge bg-purple-900 text-white rounded-circle mr-2.5 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 12px;">4</span>
                        <h5 class="card-title font-bold text-purple-950 mb-0 text-base">
                            Pilih Semester / TKA <span class="badge bg-purple-100 text-purple-900 font-bold ml-2 px-2.5 py-0.5 text-xs">Kelas {{ $kelas }}</span>
                        </h5>
                        @if($sub)
                            <span class="badge bg-purple-100 text-purple-900 font-bold ml-3 px-3 py-1 rounded-full text-xs">
                                <i class="fas fa-check-circle text-purple-600 mr-1"></i> {{ $sub }}
                            </span>
                        @endif
                    </div>
                    <div class="card-body p-3.5 bg-slate-50">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            @foreach ($availableSubs as $subItem)
                                @php
                                    $url = route($prefixRoute . '.index', ['mapel' => $mapel, 'jenjang' => $jenjang, 'kelas' => $kelas, 'sub_kategori' => $subItem]);
                                @endphp
                                <a href="{{ $url }}"
                                   class="btn font-bold rounded-xl px-4 py-2.5 text-xs transition-all {{ $sub === $subItem ? 'btn-purple shadow-md text-white' : 'btn-white border border-slate-300 text-slate-700 hover:bg-purple-100 hover:text-purple-900' }}">
                                    <i class="fas {{ $subItem === 'TKA' ? 'fa-star text-amber-400' : 'fa-bookmark text-purple-500' }} mr-2"></i>
                                    {{ $subItem }}
                                    @if ($sub === $subItem)
                                        <i class="fas fa-check ml-1.5 text-amber-300"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- ════════════════════════════════════════════════════════════ -->
            <!-- LANGKAH 5: FORM INPUT SOAL & DAFTAR SOAL                     -->
            <!-- ════════════════════════════════════════════════════════════ -->
            @if ($selectedCategory)

                <!-- Active Combo Detail Header Card -->
                <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-gradient-to-r from-purple-900 to-indigo-900 text-white">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-purple-500/30 text-purple-200 border border-purple-400/30 px-2.5 py-1 rounded-md text-xs font-bold uppercase">
                                        Jenjang {{ $jenjang }}
                                    </span>
                                    <span class="badge bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 px-2.5 py-1 rounded-md text-xs font-bold">
                                        Kelas {{ $kelas }}
                                    </span>
                                    <span class="badge bg-emerald-500/30 text-emerald-200 border border-emerald-400/30 px-2.5 py-1 rounded-md text-xs font-bold">
                                        {{ $sub }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-xl mb-0">{{ $mapel }}</h3>
                            </div>
                            <form action="{{ route($prefixRoute . '.kategori.delete', $selectedCategory->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua soal {{ $mapel }} untuk kombinasi Jenjang {{ $jenjang }} - Kelas {{ $kelas }} - {{ $sub }} ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-light font-bold rounded-lg px-2.5 py-1.5">
                                    <i class="fas fa-trash-alt mr-1"></i> Hapus Semua Soal Kombinasi Ini
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- FORM TAMBAH SOAL -->
                    <div class="col-lg-5 mb-4">
                        <div class="card border-0 shadow-sm rounded-2xl bg-white sticky-top" style="top: 20px; z-index: 10;">
                            <div class="card-header bg-white py-3 px-4 border-bottom">
                                <h5 class="card-title font-bold text-purple-950 mb-0 d-flex align-items-center text-base">
                                    <i class="fas fa-plus-circle text-purple-600 mr-2"></i> Form Input Soal Baru
                                </h5>
                                <p class="text-xs text-slate-400 mb-0 mt-0.5">Masukkan pertanyaan, 4 opsi jawaban, dan kunci jawaban.</p>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route($prefixRoute . '.soal.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="kategori_soal_id" value="{{ $selectedCategory->id }}">

                                    <!-- Nomor Soal -->
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Nomor Urut Soal <span class="text-danger">*</span></label>
                                        @php
                                            $nextNo = ($selectedCategory->bankSoals->max('nomor') ?? 0) + 1;
                                        @endphp
                                        <input type="number" name="nomor" value="{{ old('nomor', $nextNo) }}" min="1" class="form-control rounded-xl border-slate-300 font-bold" required>
                                    </div>

                                    <!-- Pertanyaan -->
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Isi Pertanyaan <span class="text-danger">*</span></label>
                                        <textarea name="soal" rows="4" class="form-control rounded-xl border-slate-300 text-sm" placeholder="Tuliskan pertanyaan / isi soal..." required>{{ old('soal') }}</textarea>
                                    </div>

                                    <!-- Opsi A - D -->
                                    <div class="space-y-2 mb-3">
                                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 d-block">Pilihan Jawaban (A - D) <span class="text-danger">*</span></label>

                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-purple-100 text-purple-900 font-bold rounded-l-xl border-slate-300">A</span>
                                            </div>
                                            <input type="text" name="opsi_a" value="{{ old('opsi_a') }}" class="form-control border-slate-300 text-sm" placeholder="Jawaban A" required>
                                        </div>

                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-purple-100 text-purple-900 font-bold rounded-l-xl border-slate-300">B</span>
                                            </div>
                                            <input type="text" name="opsi_b" value="{{ old('opsi_b') }}" class="form-control border-slate-300 text-sm" placeholder="Jawaban B" required>
                                        </div>

                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-purple-100 text-purple-900 font-bold rounded-l-xl border-slate-300">C</span>
                                            </div>
                                            <input type="text" name="opsi_c" value="{{ old('opsi_c') }}" class="form-control border-slate-300 text-sm" placeholder="Jawaban C" required>
                                        </div>

                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-purple-100 text-purple-900 font-bold rounded-l-xl border-slate-300">D</span>
                                            </div>
                                            <input type="text" name="opsi_d" value="{{ old('opsi_d') }}" class="form-control border-slate-300 text-sm" placeholder="Jawaban D" required>
                                        </div>
                                    </div>

                                    <!-- Kunci Jawaban -->
                                    <div class="form-group mb-4">
                                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 d-block">Kunci Jawaban Benar <span class="text-danger">*</span></label>
                                        <div class="row text-center">
                                            @foreach (['A', 'B', 'C', 'D'] as $key)
                                                <div class="col-3">
                                                    <label class="btn btn-outline-purple btn-block py-2 font-extrabold rounded-xl mb-0 cursor-pointer shadow-xs transition-all">
                                                        <input type="radio" name="kunci_jawaban" value="{{ $key }}" {{ old('kunci_jawaban', 'A') === $key ? 'checked' : '' }} required class="d-none">
                                                        {{ $key }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-purple btn-block font-bold py-2.5 rounded-xl shadow-md transition-all">
                                        <i class="fas fa-save mr-1.5"></i> Simpan Soal ke Database
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- DAFTAR SOAL -->
                    <div class="col-lg-7 mb-4">
                        <div class="card border-0 shadow-sm rounded-2xl bg-white">
                            <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="card-title font-bold text-purple-950 mb-0 d-flex align-items-center text-base">
                                    <i class="fas fa-list-ol text-purple-600 mr-2"></i> Daftar Soal Tersimpan
                                </h5>
                                <span class="badge bg-purple-100 text-purple-900 font-bold px-3 py-1 rounded-full text-xs">
                                    {{ $selectedCategory->bankSoals->count() }} Soal
                                </span>
                            </div>
                            <div class="card-body p-4">
                                @if ($selectedCategory->bankSoals->isEmpty())
                                    <div class="text-center py-5">
                                        <i class="fas fa-question-circle text-slate-300 fa-3x mb-3"></i>
                                        <p class="text-slate-500 font-semibold mb-0">Belum ada soal.</p>
                                        <p class="text-xs text-slate-400">Gunakan form di sebelah kiri untuk menambahkan soal pertama.</p>
                                    </div>
                                @else
                                    <div class="space-y-4">
                                        @foreach ($selectedCategory->bankSoals as $soalItem)
                                            <div class="card border border-slate-200 rounded-xl shadow-xs overflow-hidden transition-all hover:border-purple-300 mb-3">
                                                <div class="card-header bg-slate-50 py-2.5 px-3.5 d-flex justify-content-between align-items-center border-bottom">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-purple-900 text-white font-extrabold px-2.5 py-1 rounded-lg text-xs">
                                                            No. {{ $soalItem->nomor }}
                                                        </span>
                                                        <span class="badge bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded text-[11px] border border-emerald-200">
                                                            Kunci: {{ $soalItem->kunci_jawaban }}
                                                        </span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <button type="button" class="btn btn-xs btn-outline-info rounded-lg font-bold px-2 py-1" data-toggle="modal" data-target="#modalEditSoal{{ $soalItem->id }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <form action="{{ route($prefixRoute . '.soal.delete', $soalItem->id) }}" method="POST" onsubmit="return confirm('Hapus soal no. {{ $soalItem->nomor }}?');" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-xs btn-outline-danger rounded-lg font-bold px-2 py-1">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="card-body p-3.5">
                                                    <p class="font-bold text-slate-900 mb-3 text-sm whitespace-pre-line">{{ $soalItem->soal }}</p>
                                                    <div class="row g-2">
                                                        @foreach (['A' => $soalItem->opsi_a, 'B' => $soalItem->opsi_b, 'C' => $soalItem->opsi_c, 'D' => $soalItem->opsi_d] as $optKey => $optVal)
                                                            @php
                                                                $isCorrect = $soalItem->kunci_jawaban === $optKey;
                                                            @endphp
                                                            <div class="col-md-6 mb-2">
                                                                <div class="p-2.5 rounded-xl border text-xs font-semibold d-flex align-items-start {{ $isCorrect ? 'bg-emerald-50 border-emerald-300 text-emerald-950 font-bold' : 'bg-slate-50 border-slate-200 text-slate-700' }}">
                                                                    <span class="badge {{ $isCorrect ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700' }} mr-2 px-2 py-1 rounded-md text-xs font-bold">
                                                                        {{ $optKey }}
                                                                    </span>
                                                                    <span class="flex-1 mt-0.5">{{ $optVal }}</span>
                                                                    @if ($isCorrect)
                                                                        <i class="fas fa-check-circle text-emerald-600 ml-1.5 mt-0.5"></i>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- MODAL EDIT SOAL -->
                                            <div class="modal fade" id="modalEditSoal{{ $soalItem->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                                    <div class="modal-content border-0 shadow-lg rounded-2xl">
                                                        <div class="modal-header bg-purple-900 text-white rounded-t-2xl py-3 px-4">
                                                            <h5 class="modal-title font-bold text-base"><i class="fas fa-edit mr-2"></i> Edit Soal No. {{ $soalItem->nomor }}</h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route($prefixRoute . '.soal.update', $soalItem->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body p-4 text-left">
                                                                <div class="form-group mb-3">
                                                                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Nomor Urut Soal <span class="text-danger">*</span></label>
                                                                    <input type="number" name="nomor" value="{{ old('nomor', $soalItem->nomor) }}" min="1" class="form-control rounded-xl font-bold" required>
                                                                </div>

                                                                <div class="form-group mb-3">
                                                                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Isi Pertanyaan <span class="text-danger">*</span></label>
                                                                    <textarea name="soal" rows="4" class="form-control rounded-xl text-sm" required>{{ old('soal', $soalItem->soal) }}</textarea>
                                                                </div>

                                                                <div class="space-y-2 mb-3">
                                                                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1 d-block">Pilihan Jawaban (A - D) <span class="text-danger">*</span></label>

                                                                    <div class="input-group mb-2">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text bg-purple-100 text-purple-900 font-bold">A</span>
                                                                        </div>
                                                                        <input type="text" name="opsi_a" value="{{ old('opsi_a', $soalItem->opsi_a) }}" class="form-control text-sm" required>
                                                                    </div>

                                                                    <div class="input-group mb-2">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text bg-purple-100 text-purple-900 font-bold">B</span>
                                                                        </div>
                                                                        <input type="text" name="opsi_b" value="{{ old('opsi_b', $soalItem->opsi_b) }}" class="form-control text-sm" required>
                                                                    </div>

                                                                    <div class="input-group mb-2">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text bg-purple-100 text-purple-900 font-bold">C</span>
                                                                        </div>
                                                                        <input type="text" name="opsi_c" value="{{ old('opsi_c', $soalItem->opsi_c) }}" class="form-control text-sm" required>
                                                                    </div>

                                                                    <div class="input-group mb-2">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text bg-purple-100 text-purple-900 font-bold">D</span>
                                                                        </div>
                                                                        <input type="text" name="opsi_d" value="{{ old('opsi_d', $soalItem->opsi_d) }}" class="form-control text-sm" required>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group mb-3">
                                                                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 d-block">Kunci Jawaban Benar <span class="text-danger">*</span></label>
                                                                    <div class="row text-center">
                                                                        @foreach (['A', 'B', 'C', 'D'] as $key)
                                                                            <div class="col-3">
                                                                                <label class="btn btn-outline-purple btn-block py-2 font-extrabold rounded-xl mb-0 cursor-pointer shadow-xs">
                                                                                    <input type="radio" name="kunci_jawaban" value="{{ $key }}" {{ old('kunci_jawaban', $soalItem->kunci_jawaban) === $key ? 'checked' : '' }} required class="d-none">
                                                                                    {{ $key }}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-slate-50 rounded-b-2xl py-2.5 px-4">
                                                                <button type="button" class="btn btn-light font-bold rounded-xl text-xs px-3 py-2" data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-purple font-bold rounded-xl text-xs px-4 py-2">Simpan Perubahan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Custom styling -->
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
        .btn-outline-purple:hover, .btn-outline-purple:focus {
            background-color: #581c87;
            color: #ffffff;
            border-color: #581c87;
        }
        label.btn-outline-purple input[type="radio"]:checked + span,
        label.btn-outline-purple:has(input[type="radio"]:checked) {
            background-color: #581c87 !important;
            color: #ffffff !important;
            border-color: #581c87 !important;
            box-shadow: 0 4px 6px -1px rgba(88, 28, 135, 0.4);
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const radioButtons = document.querySelectorAll('input[name="kunci_jawaban"]');
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    const groupName = this.getAttribute('name');
                    const form = this.closest('form');
                    form.querySelectorAll(`input[name="${groupName}"]`).forEach(r => {
                        const parentLabel = r.closest('label');
                        if (r.checked) {
                            parentLabel.classList.add('bg-purple-900', 'text-white');
                            parentLabel.classList.remove('bg-purple-100', 'text-purple-900');
                        } else {
                            parentLabel.classList.remove('bg-purple-900', 'text-white');
                        }
                    });
                });
            });
        });
    </script>
@endsection