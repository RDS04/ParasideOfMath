@extends('layout.app')

@section('title', 'Kelola Rekening Pembayaran · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Kelola Nomor Rekening &amp; E-Wallet</h1>
                    <p class="text-sm text-muted mb-0">Kelola rekening bank dan nomor e-wallet yang akan ditampilkan pada checkout siswa.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kelola Rekening</li>
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

            <!-- Errors alert -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-xl shadow-sm border-0" role="alert">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Gagal!</h5>
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <!-- LEFT COLUMN: Accounts Table -->
                <div class="col-md-7 mb-4">
                    <div class="card h-100 shadow-sm border-light">
                        <div class="card-header bg-white py-3">
                            <h3 class="card-title font-weight-bold text-purple-950 mb-0">Daftar Akun Pembayaran</h3>
                        </div>
                        <div class="card-body p-0">
                            @if ($rekening->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-university text-slate-300 fa-3x mb-3"></i>
                                    <p class="text-slate-500 text-sm mb-0">Belum ada data rekening atau e-wallet.</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light text-slate-500">
                                            <tr>
                                                <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Tipe</th>
                                                <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Nama Bank/Wallet</th>
                                                <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Nomor Rekening</th>
                                                <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Atas Nama</th>
                                                <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-right">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rekening as $rek)
                                                <tr>
                                                    <td class="px-4 py-3">
                                                        @if ($rek->tipe === 'bank')
                                                            <span class="badge bg-blue-100 text-blue-800 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill">Bank</span>
                                                        @else
                                                            <span class="badge bg-emerald-100 text-emerald-800 px-2.5 py-1 text-[10px] font-bold uppercase rounded-pill">E-Wallet</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 font-weight-bold text-purple-950">{{ $rek->nama_bank }}</td>
                                                    <td class="px-4 py-3 font-mono text-slate-600">{{ $rek->nomor_rekening }}</td>
                                                    <td class="px-4 py-3 text-slate-700">{{ $rek->atas_nama }}</td>
                                                    <td class="px-4 py-3 text-right">
                                                        <button type="button" class="btn btn-sm btn-info rounded-lg mr-1 edit-btn" 
                                                                data-id="{{ $rek->id }}"
                                                                data-tipe="{{ $rek->tipe }}"
                                                                data-nama="{{ $rek->nama_bank }}"
                                                                data-nomor="{{ $rek->nomor_rekening }}"
                                                                data-atas="{{ $rek->atas_nama }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        
                                                        <form action="{{ route('admin.rekening.delete', $rek->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekening ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger rounded-lg">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
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

                <!-- RIGHT COLUMN: Add/Edit Form -->
                <div class="col-md-5 mb-4">
                    <div class="card shadow-sm border-light">
                        <div class="card-header bg-white py-3">
                            <h3 class="card-title font-weight-bold text-purple-950 mb-0" id="formTitle">Tambah Akun Pembayaran</h3>
                        </div>
                        <div class="card-body">
                            <form id="rekeningForm" action="{{ route('admin.rekening.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="_method" id="formMethod" value="POST">

                                <!-- Tipe -->
                                <div class="form-group">
                                    <label class="font-weight-bold text-purple-950 text-xs uppercase tracking-wider mb-2">Tipe Akun</label>
                                    <select name="tipe" id="inputTipe" class="form-control rounded-xl py-2 px-3 border-light bg-light" required>
                                        <option value="bank">Transfer Bank</option>
                                        <option value="ewallet">E-Wallet (Dana / GoPay / OVO)</option>
                                    </select>
                                </div>

                                <!-- Nama Bank -->
                                <div class="form-group">
                                    <label class="font-weight-bold text-purple-950 text-xs uppercase tracking-wider mb-2">Nama Bank / Provider Wallet</label>
                                    <input type="text" name="nama_bank" id="inputNama" class="form-control rounded-xl py-3 px-3 border-light bg-light" placeholder="Contoh: BCA, BSI, DANA, GoPay" required>
                                </div>

                                <!-- Nomor Rekening -->
                                <div class="form-group">
                                    <label class="font-weight-bold text-purple-950 text-xs uppercase tracking-wider mb-2">Nomor Rekening / E-Wallet</label>
                                    <input type="text" name="nomor_rekening" id="inputNomor" class="form-control rounded-xl py-3 px-3 border-light bg-light" placeholder="Masukkan nomor rekening atau HP" required>
                                </div>

                                <!-- Atas Nama -->
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-purple-950 text-xs uppercase tracking-wider mb-2">Atas Nama Rekening</label>
                                    <input type="text" name="atas_nama" id="inputAtas" class="form-control rounded-xl py-3 px-3 border-light bg-light" placeholder="Contoh: LBB Paradise of Math" required>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" id="submitBtn" class="btn btn-brand btn-block py-3 rounded-xl font-weight-bold shadow-md">
                                    Simpan Akun <i class="fas fa-save ml-1.5"></i>
                                </button>
                                
                                <button type="button" id="cancelBtn" class="btn btn-outline-secondary btn-block py-3 rounded-xl font-weight-bold d-none mt-2">
                                    Batal Edit
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom CSS for purple brand buttons -->
    <style>
        .btn-brand {
            background: linear-gradient(135deg, #7c3aed, #4c1d95);
            border: none;
            color: white;
        }
        .btn-brand:hover {
            background: linear-gradient(135deg, #6d28d9, #431407);
            color: white;
            transform: translateY(-1px);
        }
        .form-control:focus {
            border-color: #a78bfa !important;
            background-color: white !important;
            box-shadow: 0 0 0 3px rgba(167, 139, 250, 0.15) !important;
        }
    </style>

    <!-- Interactive script to handle Edit/Create modes -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('rekeningForm');
            const formMethod = document.getElementById('formMethod');
            const formTitle = document.getElementById('formTitle');
            const submitBtn = document.getElementById('submitBtn');
            const cancelBtn = document.getElementById('cancelBtn');

            const inputTipe = document.getElementById('inputTipe');
            const inputNama = document.getElementById('inputNama');
            const inputNomor = document.getElementById('inputNomor');
            const inputAtas = document.getElementById('inputAtas');

            const editButtons = document.querySelectorAll('.edit-btn');

            editButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const tipe = this.dataset.tipe;
                    const nama = this.dataset.nama;
                    const nomor = this.dataset.nomor;
                    const atas = this.dataset.atas;

                    // Set input values
                    inputTipe.value = tipe;
                    inputNama.value = nama;
                    inputNomor.value = nomor;
                    inputAtas.value = atas;

                    // Set form state to EDIT
                    form.action = `/admin/rekening/${id}`;
                    formMethod.value = 'PUT';
                    formTitle.textContent = 'Edit Akun Pembayaran';
                    submitBtn.innerHTML = 'Update Akun <i class="fas fa-check ml-1.5"></i>';
                    cancelBtn.classList.remove('d-none');
                    
                    // Scroll to form on small screens
                    form.scrollIntoView({ behavior: 'smooth' });
                });
            });

            cancelBtn.addEventListener('click', function () {
                // Reset inputs
                form.reset();

                // Set form state to CREATE
                form.action = "{{ route('admin.rekening.store') }}";
                formMethod.value = 'POST';
                formTitle.textContent = 'Tambah Akun Pembayaran';
                submitBtn.innerHTML = 'Simpan Akun <i class="fas fa-save ml-1.5"></i>';
                cancelBtn.classList.add('d-none');
            });
        });
    </script>
@endsection
