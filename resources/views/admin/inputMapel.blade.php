@extends('layout.app')

@section('title', 'Kelola Mata Pelajaran · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Kelola Mata Pelajaran</h1>
                    <p class="text-sm text-muted mb-0">Kelola daftar mata pelajaran beserta jumlah shift pertemuan per minggu untuk bimbel.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kelola Mata Pelajaran</li>
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
                <!-- LEFT COLUMN: Subjects Table -->
                <div class="col-md-7 mb-4">
                    <div class="card h-100 shadow-sm border-light">
                        <div class="card-header bg-white py-3">
                            <h3 class="card-title font-weight-bold text-purple-950 mb-0">Daftar Mata Pelajaran</h3>
                        </div>
                        <div class="card-body p-0">
                            @if ($mapels->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-book text-slate-300 fa-3x mb-3"></i>
                                    <p class="text-slate-500 text-sm mb-0">Belum ada data mata pelajaran.</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light text-slate-500">
                                            <tr>
                                                <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">No</th>
                                                <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Nama Mata Pelajaran</th>
                                                <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Jumlah Shift / Minggu</th>
                                                <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-right">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($mapels as $index => $mapel)
                                                <tr>
                                                    <td class="px-4 py-3 text-slate-500 font-mono text-sm">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-3 font-weight-bold text-purple-950">{{ $mapel->nama_mapel }}</td>
                                                    <td class="px-4 py-3">
                                                        <span class="badge bg-purple-100 text-purple-800 px-2.5 py-1 text-[11px] font-bold rounded-pill">
                                                            {{ $mapel->shift }}x / minggu
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-right">
                                                        <button type="button" class="btn btn-sm btn-info rounded-lg mr-1 edit-btn" 
                                                                data-id="{{ $mapel->id }}"
                                                                data-nama="{{ $mapel->nama_mapel }}"
                                                                data-shift="{{ $mapel->shift }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        
                                                        <form action="{{ route('admin.mapel.delete', $mapel->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')">
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
                            <h3 class="card-title font-weight-bold text-purple-950 mb-0" id="formTitle">Tambah Mata Pelajaran</h3>
                        </div>
                        <div class="card-body">
                            <form id="mapelForm" action="{{ route('admin.mapel.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="_method" id="formMethod" value="POST">

                                <!-- Nama Mata Pelajaran -->
                                <div class="form-group">
                                    <label class="font-weight-bold text-purple-950 text-xs uppercase tracking-wider mb-2">Nama Mata Pelajaran</label>
                                    <input type="text" name="nama_mapel" id="inputNama" class="form-control rounded-xl py-3 px-3 border-light bg-light" placeholder="Contoh: Bahasa Indonesia, Matematika Wajib" required>
                                </div>

                                <!-- Jumlah Shift -->
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-purple-950 text-xs uppercase tracking-wider mb-2">Jumlah Shift / Minggu</label>
                                    <input type="number" name="shift" id="inputShift" class="form-control rounded-xl py-3 px-3 border-light bg-light" placeholder="Contoh: 2" min="1" required>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" id="submitBtn" class="btn btn-brand btn-block py-3 rounded-xl font-weight-bold shadow-md">
                                    Simpan Mapel <i class="fas fa-save ml-1.5"></i>
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
            background: linear-gradient(135deg, #6d28d9, #4c1d95);
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
            const form = document.getElementById('mapelForm');
            const formMethod = document.getElementById('formMethod');
            const formTitle = document.getElementById('formTitle');
            const submitBtn = document.getElementById('submitBtn');
            const cancelBtn = document.getElementById('cancelBtn');

            const inputNama = document.getElementById('inputNama');
            const inputShift = document.getElementById('inputShift');

            const editButtons = document.querySelectorAll('.edit-btn');

            editButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const nama = this.dataset.nama;
                    const shift = this.dataset.shift;

                    // Set input values
                    inputNama.value = nama;
                    inputShift.value = shift;

                    // Set form state to EDIT
                    form.action = `/admin/mapel/${id}`;
                    formMethod.value = 'PUT';
                    formTitle.textContent = 'Edit Mata Pelajaran';
                    submitBtn.innerHTML = 'Update Mapel <i class="fas fa-check ml-1.5"></i>';
                    cancelBtn.classList.remove('d-none');
                    
                    // Scroll to form on small screens
                    form.scrollIntoView({ behavior: 'smooth' });
                });
            });

            cancelBtn.addEventListener('click', function () {
                // Reset inputs
                form.reset();

                // Set form state to CREATE
                form.action = "{{ route('admin.mapel.store') }}";
                formMethod.value = 'POST';
                formTitle.textContent = 'Tambah Mata Pelajaran';
                submitBtn.innerHTML = 'Simpan Mapel <i class="fas fa-save ml-1.5"></i>';
                cancelBtn.classList.add('d-none');
            });
        });
    </script>
@endsection
