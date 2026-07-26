@extends('layout.app')

@section('title', 'Detail Registrasi Siswa · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Detail Registrasi &amp; Pembayaran</h1>
                    <p class="text-sm text-muted mb-0">Ulas data pendaftaran siswa, verifikasi bukti pembayaran, dan aktivasi akun belajar.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.siswa.approve.index') }}" class="text-purple-600">Persetujuan Siswa</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('admin.siswa.approve.index') }}" class="btn btn-sm btn-light border rounded-lg font-weight-bold text-purple-950 px-3">
                    <i class="fas fa-arrow-left mr-1.5"></i> Kembali ke Daftar Persetujuan
                </a>
            </div>

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

            <div class="row">
                <!-- LEFT CARD: Student Info -->
                <div class="col-lg-5 mb-4">
                    <div class="card h-100 shadow-sm border-light rounded-2xl overflow-hidden">
                        <div class="card-header bg-purple-950 text-white py-3 d-flex align-items-center justify-content-between" style="background-color: #2e1065;">
                            <h3 class="card-title font-weight-bold mb-0 text-md text-white" style="font-size: 1.05rem;">Profil Data Diri Siswa</h3>
                            <span class="badge {{ $student->status === 'active' ? 'bg-emerald-500' : 'bg-amber-500' }} text-white text-xxs font-bold uppercase px-2.5 py-1">
                                {{ $student->status === 'active' ? 'Aktif' : 'Menunggu' }}
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <!-- Section: Personal Info -->
                            <h6 class="font-weight-bold text-purple-950 uppercase text-xs tracking-wider mb-3" style="color: #2e1065;">Biodata Siswa</h6>
                            <table class="table table-borderless table-sm mb-4 align-middle">
                                <tbody>
                                    <tr class="border-bottom border-light">
                                        <td class="text-muted text-xs py-2.5" style="width: 35%;">Nama Lengkap</td>
                                        <td class="font-weight-bold text-purple-950 py-2.5">{{ $student->name }}</td>
                                    </tr>
                                    <tr class="border-bottom border-light">
                                        <td class="text-muted text-xs py-2.5">Alamat Email</td>
                                        <td class="text-slate-700 py-2.5 font-mono">{{ $student->email }}</td>
                                    </tr>
                                    <tr class="border-bottom border-light">
                                        <td class="text-muted text-xs py-2.5">No. WhatsApp</td>
                                        <td class="text-slate-700 py-2.5">{{ $student->whatsapp ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted text-xs py-2.5">Sekolah / Kelas</td>
                                        <td class="text-slate-700 py-2.5">{{ $student->sekolah ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Section: Tutoring Choices -->
                            <h6 class="font-weight-bold text-purple-950 uppercase text-xs tracking-wider mb-3 pt-3" style="color: #2e1065; border-top: 1px solid #f1f5f9;">Pilihan Paket Belajar</h6>
                            @if ($paket)
                                <table class="table table-borderless table-sm mb-0 align-middle">
                                    <tbody>
                                        <tr class="border-bottom border-light">
                                            <td class="text-muted text-xs py-2.5" style="width: 35%;">Kategori Bimbel</td>
                                            <td class="py-2.5"><span class="badge bg-purple-100 text-purple-800 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill">{{ $paket->kategori }}</span></td>
                                        </tr>
                                        <tr class="border-bottom border-light">
                                            <td class="text-muted text-xs py-2.5">Nama Paket</td>
                                            <td class="font-weight-bold text-purple-950 py-2.5">{{ $paket->nama_paket }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted text-xs py-2.5">Opsi Kelas / Harga</td>
                                            <td class="text-purple-900 font-weight-bold py-2.5" style="font-size: 0.95rem;">{{ $student->tipe_paket ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @else
                                <p class="text-xs text-muted font-italic mb-0">Belum ada paket bimbingan yang terpilih.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- RIGHT CARD: Payment Receipt -->
                <div class="col-lg-7 mb-4">
                    <div class="card h-100 shadow-sm border-light rounded-2xl overflow-hidden">
                        <div class="card-header bg-white py-3">
                            <h3 class="card-title font-weight-bold text-purple-950 mb-0" style="font-size: 1.05rem;">Bukti Transfer Pembayaran</h3>
                        </div>
                        <div class="card-body p-4 d-flex flex-column justify-between align-items-center text-center">
                            @if ($student->bukti_transfer)
                                <div class="w-full bg-light p-3 rounded-xl border mb-4 d-flex align-items-center justify-center" style="background-color: #faf9fd; border-radius: 16px; border: 1px solid #f3f0fc; min-height: 320px;">
                                    @php
                                        $extension = pathinfo($student->bukti_transfer, PATHINFO_EXTENSION);
                                    @endphp
                                    @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                                        <img src="{{ asset($student->bukti_transfer) }}" alt="Bukti Pembayaran" class="img-fluid rounded-xl shadow-sm max-h-[360px]" style="object-fit: contain; max-width: 100%;" />
                                    @else
                                        <div class="py-5 text-center">
                                            <i class="fas fa-file-pdf text-danger fa-4x mb-3 animate-bounce"></i>
                                            <h5 class="font-weight-bold text-slate-700">Berkas Bukti Transfer PDF</h5>
                                            <p class="text-xs text-muted">Berkas diunggah dalam format dokumen PDF resmi.</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="w-full d-flex gap-2 justify-content-center">
                                    <a href="{{ asset($student->bukti_transfer) }}" target="_blank" class="btn btn-sm btn-outline-purple font-weight-bold px-4 py-2 rounded-xl text-sm">
                                        <i class="fas fa-external-link-alt mr-1.5"></i> Buka File Asli
                                    </a>
                                    
                                    @if ($student->status === 'under_review')
                                        <form action="{{ route('admin.siswa.approve.submit', $student->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pendaftaran dan mengaktifkan akun {{ $student->name }}?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success font-weight-bold px-4 py-2 rounded-xl text-sm">
                                                <i class="fas fa-check-circle mr-1.5"></i> Approve &amp; Aktifkan Akun
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-sm btn-light border-0 text-slate-400 font-weight-bold px-4 py-2 rounded-xl text-sm" disabled>
                                            <i class="fas fa-check-circle mr-1.5"></i> Sudah Disetujui
                                        </button>
                                    @endif
                                </div>
                            @else
                                <div class="py-5">
                                    <i class="fas fa-exclamation-triangle text-amber-400 fa-3x mb-3"></i>
                                    <h5 class="font-weight-bold text-slate-700">Belum Ada Bukti Pembayaran</h5>
                                    <p class="text-sm text-slate-400 max-w-sm mx-auto mb-0">Siswa bersangkutan belum menyelesaikan transaksi atau belum mengunggah file bukti bayar pendaftarannya.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom CSS for purple brand elements -->
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
