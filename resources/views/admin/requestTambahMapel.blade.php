@extends('layout.app')

@section('title', 'Approve Request Tambah Mapel · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Approve Request Tambah Mapel</h1>
                    <p class="text-sm text-muted mb-0">Verifikasi bukti transfer dan jadwal sebelum menyetujui permintaan tambahan mata pelajaran dari siswa aktif.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600">Dashboard</a></li>
                        <li class="breadcrumb-item active">Approve Request Mapel</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert" style="background-color: #d1fae5; border-color: #a7f3d0; color: #065f46;">
                    <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #065f46;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert">
                    <h5><i class="icon fas fa-ban mr-2"></i>Gagal!</h5>
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($students->isEmpty())
                <div class="card shadow-sm border-light">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-book-medical text-slate-300 fa-3x mb-3"></i>
                        <p class="text-slate-500 text-sm mb-0">Tidak ada request tambahan mapel dari siswa aktif saat ini.</p>
                    </div>
                </div>
            @else
                @foreach ($students as $student)
                    @php
                        $bio = $student->biodata ?? [];
                        $pendingMapels   = $bio['pending_mapel_jadwal'] ?? [];
                        $pendingSesi     = $bio['pending_sesi_per_mapel'] ?? [];
                        $pendingHari     = $bio['pending_hari_per_mapel'] ?? [];
                        $pendingTanggal  = $bio['pending_tanggal_mulai_per_mapel'] ?? [];
                        $paymentMethod   = $bio['payment_method'] ?? null;

                        $paket = \App\Models\PaketBelajar::find($student->paket_id);

                        // Cari string detail paket yang cocok dengan tipe_paket siswa, untuk hitung harga per sesi
                        $detailString = '';
                        if ($paket && $student->tipe_paket) {
                            if (str_contains($student->tipe_paket, $paket->detail_1 ?? "\0")) $detailString = $paket->detail_1;
                            elseif (str_contains($student->tipe_paket, $paket->detail_2 ?? "\0")) $detailString = $paket->detail_2;
                            elseif (str_contains($student->tipe_paket, $paket->detail_3 ?? "\0")) $detailString = $paket->detail_3;
                            elseif (str_contains($student->tipe_paket, $paket->detail_4 ?? "\0")) $detailString = $paket->detail_4;
                        }

                        $hargaPerSesi = 45000;
                        if (!empty($detailString) && preg_match('/(\d+)\s*K/i', $detailString, $mHarga)) {
                            $hargaPerSesi = (int) $mHarga[1] * 1000;
                        } elseif ($paket) {
                            $hargaPerSesi = $paket->harga_max ?? 45000;
                        }

                        $totalSesiRequest = array_sum(array_map('intval', $pendingSesi));
                        $totalTagihan = $hargaPerSesi * $totalSesiRequest;

                        $extension = $student->bukti_transfer ? pathinfo($student->bukti_transfer, PATHINFO_EXTENSION) : null;
                        $isTunai = $student->bukti_transfer === 'TUNAI_CASH_PAYMENT' || $paymentMethod === 'tunai';
                    @endphp

                    <div class="card shadow-sm border-light rounded-2xl overflow-hidden mb-4">
                        <div class="card-header bg-white py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-700 d-flex align-items-center justify-content-center font-weight-bold" style="width:40px;height:40px;">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="card-title font-weight-bold text-purple-950 mb-0" style="font-size:1rem;">{{ $student->name }}</h3>
                                    <span class="text-xs text-muted">{{ $student->email }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-amber-100 text-amber-800 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill border border-amber-200">
                                    <i class="fas fa-clock mr-1"></i> Menunggu Approve
                                </span>
                                <span class="text-[10px] text-muted">
                                    Diajukan {{ $student->updated_at ? $student->updated_at->diffForHumans() : '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row">
                                <!-- LEFT: Bukti Transfer & Metode -->
                                <div class="col-lg-4 mb-3 mb-lg-0">
                                    <h6 class="text-xxs font-weight-bold text-muted uppercase tracking-wider mb-2">Bukti Pembayaran</h6>

                                    @if ($isTunai)
                                        <div class="p-3 rounded-2xl bg-amber-50 border border-amber-200 text-center" style="min-height: 160px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                            <i class="fas fa-money-bill-wave text-amber-500 fa-2x mb-2"></i>
                                            <span class="text-xs font-weight-bold text-amber-800">Pembayaran Tunai</span>
                                            <span class="text-[10px] text-amber-700 mt-1">Dibayar langsung di tempat / ke tutor</span>
                                        </div>
                                    @elseif ($student->bukti_transfer)
                                        <div class="w-100 bg-light p-2 rounded-xl border mb-2 d-flex align-items-center justify-content-center" style="background-color: #faf9fd; border-radius: 12px; border: 1px solid #f3f0fc; min-height: 160px;">
                                            @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                                                <img src="{{ asset($student->bukti_transfer) }}" alt="Bukti Pembayaran" class="img-fluid rounded-xl shadow-sm" style="object-fit: contain; max-height: 160px; max-width: 100%;" />
                                            @else
                                                <div class="py-4 text-center">
                                                    <i class="fas fa-file-pdf text-danger fa-2x mb-2"></i>
                                                    <h6 class="font-weight-bold text-slate-700 text-xs mb-0">Berkas PDF</h6>
                                                </div>
                                            @endif
                                        </div>
                                        <a href="{{ asset($student->bukti_transfer) }}" target="_blank" class="btn btn-xs btn-outline-purple font-weight-bold py-2 rounded-lg text-xs w-100 d-block text-center">
                                            <i class="fas fa-external-link-alt mr-1"></i> Lihat Bukti Penuh
                                        </a>
                                    @else
                                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200 text-center" style="min-height: 160px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                            <i class="fas fa-exclamation-triangle text-slate-300 fa-2x mb-2"></i>
                                            <span class="text-xs text-slate-500">Belum ada bukti transfer</span>
                                        </div>
                                    @endif

                                    @if ($paymentMethod && !$isTunai)
                                        <div class="mt-2 text-center">
                                            <span class="badge bg-slate-100 text-slate-600 text-[10px] font-bold uppercase px-2 py-1 rounded-pill border">
                                                Metode: {{ $paymentMethod }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- MIDDLE: Rincian Mapel & Jadwal -->
                                <div class="col-lg-5 mb-3 mb-lg-0">
                                    <h6 class="text-xxs font-weight-bold text-muted uppercase tracking-wider mb-2">Mata Pelajaran Diajukan</h6>

                                    @if (empty($pendingMapels))
                                        <p class="text-xs text-muted font-italic mb-0">Tidak ada data mapel.</p>
                                    @else
                                        <div class="d-flex flex-column gap-2">
                                            @foreach ($pendingMapels as $idx => $mapelName)
                                                @php
                                                    $sesi = $pendingSesi[$idx] ?? 8;
                                                    $hari1 = $pendingHari[$idx][1] ?? null;
                                                    $hari2 = $pendingHari[$idx][2] ?? null;
                                                    $tglMulai = $pendingTanggal[$idx] ?? null;
                                                    $tglStr = $tglMulai ? \Carbon\Carbon::parse($tglMulai)->format('d M Y') : null;
                                                @endphp
                                                <div class="p-2.5 bg-purple-50/60 rounded-xl border border-purple-100">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span class="font-weight-bold text-purple-900 text-xs">{{ $mapelName }}</span>
                                                        <span class="text-[10px] font-weight-bold text-purple-700 bg-white px-2 py-0.5 rounded-pill border border-purple-200">{{ $sesi }}x sesi</span>
                                                    </div>
                                                    <div class="text-[10px] text-slate-500">
                                                        @if ($hari1 || $hari2)
                                                            <div><i class="fas fa-calendar-week mr-1 text-purple-400"></i>Hari: <strong class="text-slate-700">{{ $hari1 ?? '-' }}{{ $hari2 ? ' & '.$hari2 : '' }}</strong></div>
                                                        @endif
                                                        @if ($tglStr)
                                                            <div><i class="fas fa-calendar-alt mr-1 text-purple-400"></i>Mulai: <strong class="text-slate-700">{{ $tglStr }}</strong></div>
                                                        @endif
                                                        @if (!$hari1 && !$hari2 && !$tglStr)
                                                            <span class="font-italic">Detail jadwal belum tersedia.</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <!-- RIGHT: Estimasi Tagihan & Aksi -->
                                <div class="col-lg-3">
                                    <h6 class="text-xxs font-weight-bold text-muted uppercase tracking-wider mb-2">Estimasi Tagihan</h6>
                                    <div class="p-3 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 mb-3">
                                        <div class="d-flex justify-content-between text-[10px] text-emerald-700 mb-1">
                                            <span>Harga/sesi</span>
                                            <span class="font-weight-bold">Rp {{ number_format($hargaPerSesi, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between text-[10px] text-emerald-700 mb-2">
                                            <span>Total sesi</span>
                                            <span class="font-weight-bold">{{ $totalSesiRequest }}x</span>
                                        </div>
                                        <div class="border-top border-emerald-200 pt-2">
                                            <span class="text-[10px] text-emerald-700 font-weight-bold uppercase d-block">Total</span>
                                            <span class="font-weight-bold text-emerald-950" style="font-size:1.05rem;">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column gap-2">
                                        <form action="{{ route('admin.siswa.requests.approve', $student->id) }}" method="POST" class="m-0" onsubmit="return confirm('Setujui request tambah mapel untuk {{ $student->name }}? Mata pelajaran akan langsung aktif di jadwal siswa.')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success rounded-lg font-weight-bold w-100">
                                                <i class="fas fa-check mr-1"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.siswa.requests.reject', $student->id) }}" method="POST" class="m-0" onsubmit="return confirm('Tolak request tambah mapel dari {{ $student->name }}? Data pengajuan ini akan dihapus dan siswa perlu mengajukan ulang.')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-lg font-weight-bold w-100">
                                                <i class="fas fa-times mr-1"></i> Tolak
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.siswa.detail', $student->id) }}" class="btn btn-sm btn-light border rounded-lg font-weight-bold w-100 text-slate-600">
                                            <i class="fas fa-user mr-1"></i> Profil Siswa
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>

    <!-- Custom CSS for purple brand outlines -->
    <style>
        .btn-outline-purple {
            color: #7c3aed;
            border-color: #d8d3e8;
            background-color: transparent;
            transition: all 0.2s ease;
        }
        .btn-outline-purple:hover {
            color: white;
            background-color: #7c3aed;
            border-color: #7c3aed;
        }
        .text-xxs {
            font-size: 0.7rem;
        }
        .rounded-2xl {
            border-radius: 16px !important;
        }
    </style>
@endsection