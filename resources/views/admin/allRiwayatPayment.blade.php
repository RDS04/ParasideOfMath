@extends('layout.app')

@section('title', 'Riwayat Pembayaran Siswa · Paradise of Math')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-purple-950">Riwayat Pembayaran Siswa</h1>
                    <p class="text-sm text-muted mb-0">Kelola, verifikasi, dan pantau seluruh transaksi pembayaran bimbingan belajar siswa.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-purple-600">Dashboard</a></li>
                        <li class="breadcrumb-item active">Riwayat Pembayaran</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Stats Counters Row -->
            <div class="row mb-4">
                <div class="col-lg-3 col-sm-6 mb-3 mb-lg-0">
                    <div class="card border-0 shadow-sm rounded-xl overflow-hidden h-100">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="rounded-xl bg-purple-50 text-purple-600 mr-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; flex-shrink: 0;">
                                <i class="fas fa-receipt text-lg"></i>
                            </div>
                            <div>
                                <small class="text-xs text-muted d-block font-weight-bold uppercase">Total Transaksi</small>
                                <span class="h4 font-weight-extrabold text-purple-950 mb-0 d-block">{{ count($payments) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mb-3 mb-lg-0">
                    <div class="card border-0 shadow-sm rounded-xl overflow-hidden h-100">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="rounded-xl bg-warning-50 text-warning d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; flex-shrink: 0; background-color: #fffbeb; color: #d97706 !important;">
                                <i class="fas fa-clock text-lg"></i>
                            </div>
                            <div>
                                <small class="text-xs text-muted d-block font-weight-bold uppercase">Peninjauan</small>
                                <span class="h4 font-weight-extrabold text-amber-600 mb-0 d-block">{{ count($payments->where('status', 'under_review')) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mb-3 mb-lg-0">
                    <div class="card border-0 shadow-sm rounded-xl overflow-hidden h-100">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="rounded-xl bg-emerald-50 text-emerald-600 d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; flex-shrink: 0; background-color: #ecfdf5; color: #059669 !important;">
                                <i class="fas fa-check-circle text-lg"></i>
                            </div>
                            <div>
                                <small class="text-xs text-muted d-block font-weight-bold uppercase">Lunas (Aktif)</small>
                                <span class="h4 font-weight-extrabold text-emerald-600 mb-0 d-block">{{ count($payments->where('status', 'active')) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mb-3 mb-lg-0">
                    <div class="card border-0 shadow-sm rounded-xl overflow-hidden h-100">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="rounded-xl bg-rose-50 text-rose-600 d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; flex-shrink: 0; background-color: #fff1f2; color: #e11d48 !important;">
                                <i class="fas fa-times-circle text-lg"></i>
                            </div>
                            <div>
                                <small class="text-xs text-muted d-block font-weight-bold uppercase">Ditolak</small>
                                <span class="h4 font-weight-extrabold text-rose-600 mb-0 d-block">{{ count($payments->where('status', 'rejected')) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table & Search Filter -->
            <div id="printArea" class="card border-0 shadow-sm rounded-2xl overflow-hidden mb-5">
                <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 no-print">
                    <div class="d-flex align-items-center">
                        <h5 class="font-weight-bold text-purple-950 mb-0">Semua Data Transaksi</h5>
                    </div>
                    <!-- Export & Filter Controls -->
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <!-- Search Box -->
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input type="text" id="searchInput" class="form-control rounded-left-lg" placeholder="Cari nama / email...">
                            <div class="input-group-append">
                                <span class="input-group-text bg-light border-left-0"><i class="fas fa-search text-muted"></i></span>
                            </div>
                        </div>
                        <!-- Status Filter -->
                        <select id="statusFilter" class="custom-select custom-select-sm" style="width: 130px;">
                            <option value="">Semua Status</option>
                            <option value="active">Lunas (Aktif)</option>
                            <option value="under_review">Peninjauan</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                        <!-- Action Export Buttons -->
                        <button type="button" onclick="exportExcel()" class="btn btn-sm btn-emerald font-weight-bold text-xs rounded-lg px-3 text-white" style="background-color: #059669; border: none; height: 31px;">
                            <i class="fas fa-file-excel mr-1"></i> Excel
                        </button>
                        <button type="button" onclick="exportPDF()" class="btn btn-sm btn-rose font-weight-bold text-xs rounded-lg px-3 text-white" style="background-color: #e11d48; border: none; height: 31px;">
                            <i class="fas fa-file-pdf mr-1"></i> PDF / Print
                        </button>
                    </div>
                </div>
                
                <!-- Table area -->
                <div class="card-body p-0">
                    @if($payments->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-receipt text-slate-300 fa-3x mb-3"></i>
                            <p class="text-slate-500 text-sm mb-0">Belum ada riwayat pembayaran yang terdaftar.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table id="paymentTable" class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-slate-500">
                                    <tr>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">No. Transaksi</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Tanggal</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Nama Siswa</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold">Paket &amp; Sesi</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-right">Total Bayar</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center">Status</th>
                                        <th class="px-4 py-3 text-xs uppercase tracking-wider font-bold text-center no-print">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-slate-700">
                                    @foreach($payments as $pay)
                                        <tr class="payment-row" data-name="{{ strtolower($pay->name) }}" data-email="{{ strtolower($pay->email) }}" data-status="{{ $pay->status }}">
                                            <!-- Transaction No -->
                                            <td class="px-4 py-3.5 font-mono text-xs font-semibold text-purple-900 align-middle">
                                                POM-PAY-{{ str_pad($pay->id, 4, '0', STR_PAD_LEFT) }}
                                            </td>
                                            
                                            <!-- Date -->
                                            <td class="px-4 py-3.5 text-xs text-slate-500 align-middle">
                                                {{ $pay->tanggal }}
                                            </td>
                                            
                                            <!-- Student Info -->
                                            <td class="px-4 py-3.5 align-middle">
                                                <div class="font-weight-bold text-purple-950">{{ $pay->name }}</div>
                                                <small class="text-slate-400 font-mono">{{ $pay->email }}</small>
                                            </td>
                                            
                                            <!-- Package details -->
                                            <td class="px-4 py-3.5 text-xs align-middle">
                                                <div class="font-weight-semibold text-slate-800">{{ $pay->nama_paket }}</div>
                                                <small class="text-slate-400">{{ $pay->jumlah_pertemuan }} Sesi · Rp {{ number_format($pay->harga_sesi, 0, ',', '.') }}/sesi</small>
                                            </td>
                                            
                                            <!-- Total Pay -->
                                            <td class="px-4 py-3.5 text-right font-weight-bold text-purple-950 align-middle">
                                                Rp {{ number_format($pay->total_bayar, 0, ',', '.') }}
                                            </td>
                                            
                                            <!-- Status -->
                                            <td class="px-4 py-3.5 text-center align-middle">
                                                @if($pay->status === 'active')
                                                    <span class="badge badge-success px-2.5 py-1.5 rounded-lg text-xs font-bold shadow-xs" style="background-color: #10b981;">Lunas</span>
                                                @elseif($pay->status === 'under_review')
                                                    <span class="badge badge-warning px-2.5 py-1.5 rounded-lg text-xs font-bold text-white shadow-xs" style="background-color: #f59e0b;">Peninjauan</span>
                                                @elseif($pay->status === 'rejected')
                                                    <span class="badge badge-danger px-2.5 py-1.5 rounded-lg text-xs font-bold shadow-xs" style="background-color: #ef4444;">Ditolak</span>
                                                @else
                                                    <span class="badge badge-secondary px-2.5 py-1.5 rounded-lg text-xs font-bold shadow-xs">Pending</span>
                                                @endif
                                            </td>
                                            
                                            <!-- Actions -->
                                            <td class="px-4 py-3.5 text-center align-middle no-print">
                                                <div class="d-flex justify-content-center align-items-center gap-1.5">
                                                    <button type="button" class="btn btn-xs btn-purple text-xs font-weight-bold rounded-lg px-2.5 py-1.5"
                                                        onclick="showPaymentDetail(this)"
                                                        data-id="POM-PAY-{{ str_pad($pay->id, 4, '0', STR_PAD_LEFT) }}"
                                                        data-nama="{{ $pay->name }}"
                                                        data-email="{{ $pay->email }}"
                                                        data-whatsapp="{{ $pay->whatsapp }}"
                                                        data-paket="{{ $pay->nama_paket }}"
                                                        data-tipe="{{ $pay->tipe_paket }}"
                                                        data-sesi="{{ $pay->jumlah_pertemuan }}"
                                                        data-harga="Rp {{ number_format($pay->harga_sesi, 0, ',', '.') }}"
                                                        data-total="Rp {{ number_format($pay->total_bayar, 0, ',', '.') }}"
                                                        data-tanggal="{{ $pay->tanggal }}"
                                                        data-status="{{ $pay->status }}"
                                                        data-bukti="{{ asset($pay->bukti_transfer) }}">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                    </button>
                                                    <a href="{{ asset($pay->bukti_transfer) }}" target="_blank" class="btn btn-xs btn-outline-purple text-xs font-weight-bold rounded-lg px-2.5 py-1.5">
                                                        <i class="fas fa-image mr-1"></i> Bukti
                                                    </a>
                                                </div>
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

    <!-- Detail Payment Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #2e1065 0%, #4c1d95 100%); border-top-left-radius: 20px; border-top-right-radius: 20px;">
                    <h5 class="modal-title font-weight-bold" id="detailModalLabel">
                        <i class="fas fa-file-invoice-dollar mr-2"></i> Rincian Transaksi Pembayaran
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <!-- Left Side: Invoice Text info -->
                        <div class="col-md-6 border-right">
                            <h6 class="font-weight-bold text-purple-900 border-bottom pb-2 mb-3">Informasi Pembayaran</h6>
                            
                            <table class="table table-borderless table-sm text-sm">
                                <tr>
                                    <td class="text-muted" style="width: 140px;">No. Transaksi:</td>
                                    <td id="mTransId" class="font-weight-bold text-purple-950 font-mono"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tanggal:</td>
                                    <td id="mTanggal" class="font-weight-semibold"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td id="mStatus"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nama Siswa:</td>
                                    <td id="mNama" class="font-weight-bold"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email:</td>
                                    <td id="mEmail" class="font-mono text-xs"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No. WhatsApp:</td>
                                    <td id="mWhatsapp" class="font-weight-semibold"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Paket Belajar:</td>
                                    <td id="mPaket" class="font-weight-semibold"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Rincian Pertemuan:</td>
                                    <td id="mTipe" class="text-xs"></td>
                                </tr>
                                <tr class="border-top pt-2">
                                    <td class="text-muted font-weight-bold">Tarif / Sesi:</td>
                                    <td id="mHarga" class="font-weight-bold"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted font-weight-bold text-purple-950">Total Bayar:</td>
                                    <td id="mTotal" class="font-weight-extrabold text-purple-950 text-lg"></td>
                                </tr>
                            </table>
                        </div>

                        <!-- Right Side: Bukti Transfer Image -->
                        <div class="col-md-6 text-center pl-md-4 mt-3 mt-md-0">
                            <h6 class="font-weight-bold text-purple-900 border-bottom pb-2 mb-3 text-left">Unggahan Bukti Transfer</h6>
                            
                            <div class="position-relative d-inline-block rounded-xl overflow-hidden border bg-light shadow-xs p-2 w-100" style="max-height: 280px;">
                                <a id="mBuktiLink" href="" target="_blank">
                                    <img id="mBuktiImg" src="" class="img-fluid rounded-lg" style="max-height: 260px; object-fit: contain; width: 100%;" alt="Bukti Transfer">
                                </a>
                            </div>
                            <small class="text-muted d-block mt-2"><i class="fas fa-search-plus mr-1"></i> Klik gambar untuk memperbesar bukti</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                    <button type="button" class="btn btn-secondary px-4 rounded-lg font-weight-semibold" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SheetJS Library for Excel Export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        // Search & Filter Logic
        document.getElementById('searchInput').addEventListener('keyup', filterTable);
        document.getElementById('statusFilter').addEventListener('change', filterTable);

        function filterTable() {
            var searchQuery = document.getElementById('searchInput').value.toLowerCase();
            var statusQuery = document.getElementById('statusFilter').value;
            var rows = document.querySelectorAll('.payment-row');

            rows.forEach(function(row) {
                var name = row.getAttribute('data-name');
                var email = row.getAttribute('data-email');
                var status = row.getAttribute('data-status');

                var matchesSearch = name.includes(searchQuery) || email.includes(searchQuery);
                var matchesStatus = statusQuery === "" || status === statusQuery;

                if (matchesSearch && matchesStatus) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }        // Modal Populate data
        function showPaymentDetail(button) {
            // Extract values using vanilla JS to be completely safe from script-loading timing
            var transId = button.getAttribute('data-id');
            var nama = button.getAttribute('data-nama');
            var email = button.getAttribute('data-email');
            var whatsapp = button.getAttribute('data-whatsapp');
            var paket = button.getAttribute('data-paket');
            var tipe = button.getAttribute('data-tipe');
            var sesi = button.getAttribute('data-sesi');
            var harga = button.getAttribute('data-harga');
            var total = button.getAttribute('data-total');
            var tanggal = button.getAttribute('data-tanggal');
            var status = button.getAttribute('data-status');
            var bukti = button.getAttribute('data-bukti');

            // Populate fields in modal
            document.getElementById('mTransId').textContent = transId;
            document.getElementById('mTanggal').textContent = tanggal;
            document.getElementById('mNama').textContent = nama;
            document.getElementById('mEmail').textContent = email;
            document.getElementById('mWhatsapp').textContent = whatsapp && whatsapp !== 'null' ? whatsapp : '-';
            document.getElementById('mPaket').textContent = paket;
            document.getElementById('mTipe').textContent = tipe ? tipe : sesi + ' Sesi';
            document.getElementById('mHarga').textContent = harga;
            document.getElementById('mTotal').textContent = total;
            document.getElementById('mBuktiImg').setAttribute('src', bukti);
            document.getElementById('mBuktiLink').setAttribute('href', bukti);

            // Render status badge inside modal
            var statusBadge = '';
            if (status === 'active') {
                statusBadge = '<span class="badge badge-success px-2 py-1 rounded" style="background-color: #10b981;">Lunas (Aktif)</span>';
            } else if (status === 'under_review') {
                statusBadge = '<span class="badge badge-warning px-2 py-1 rounded text-white" style="background-color: #f59e0b;">Peninjauan</span>';
            } else if (status === 'rejected') {
                statusBadge = '<span class="badge badge-danger px-2 py-1 rounded" style="background-color: #ef4444;">Ditolak</span>';
            } else {
                statusBadge = '<span class="badge badge-secondary px-2 py-1 rounded">Pending</span>';
            }
            document.getElementById('mStatus').innerHTML = statusBadge;

            // Trigger modal display via jQuery (safe as jQuery is fully loaded by click time)
            $('#detailModal').modal('show');
        }

        // Excel Export
        function exportExcel() {
            // Clone table and remove the 'Aksi' column which has buttons
            var table = document.getElementById("paymentTable");
            var clonedTable = table.cloneNode(true);
            
            // Remove 'Aksi' header column
            var headers = clonedTable.querySelectorAll("thead th");
            if(headers.length > 0) {
                headers[headers.length - 1].remove(); 
            }
            
            // Remove 'Aksi' body columns
            var rows = clonedTable.querySelectorAll("tbody tr");
            rows.forEach(function(row) {
                var cells = row.querySelectorAll("td");
                if(cells.length > 0) {
                    cells[cells.length - 1].remove(); 
                }
            });

            var wb = XLSX.utils.table_to_book(clonedTable, {sheet: "Riwayat Pembayaran"});
            XLSX.writeFile(wb, "Riwayat_Pembayaran_Siswa_" + new Date().toISOString().slice(0,10) + ".xlsx");
        }

        // PDF Print Export
        function exportPDF() {
            window.print();
        }
    </script>

    <!-- Custom CSS Styles -->
    <style>
        .btn-purple {
            background-color: #4c1d95;
            color: #fff;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-purple:hover {
            background-color: #3b0764;
            color: #fff;
        }
        .btn-outline-purple {
            border: 1px solid #4c1d95;
            color: #4c1d95;
            background: #fff;
            transition: all 0.2s ease;
        }
        .btn-outline-purple:hover {
            background-color: #f3e8ff;
            color: #3b0764;
            border-color: #3b0764;
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
        .text-xxs {
            font-size: 0.65rem;
        }
        .gap-1.5 {
            gap: 6px;
        }
        .gap-2 {
            gap: 8px;
        }
        .gap-3 {
            gap: 12px;
        }

        /* Printable area styles */
        @media print {
            /* Hide general layout elements */
            .main-sidebar, 
            .main-header, 
            .content-header, 
            .breadcrumb, 
            .no-print, 
            .btn, 
            .main-footer,
            .modal {
                display: none !important;
            }
            .content-wrapper {
                margin-left: 0 !important;
                padding: 0 !important;
                background-color: #fff !important;
            }
            body, .content-wrapper, .container-fluid {
                background: #white !important;
                color: #000 !important;
            }
            /* Style printable table */
            #printArea {
                box-shadow: none !important;
                border: none !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            #paymentTable {
                width: 100% !important;
                border-collapse: collapse !important;
            }
            #paymentTable th, #paymentTable td {
                border: 1px solid #ddd !important;
                padding: 8px !important;
                font-size: 11px !important;
            }
        }
    </style>
@endsection
