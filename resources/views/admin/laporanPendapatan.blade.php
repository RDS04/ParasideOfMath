@extends('layout.app')
@section('title', 'Laporan Pendapatan · Paradise of Math')
@section('content')
	<section class="content-header px-4 pt-4 pb-2">
		<div class="container-fluid">
			<div class="row align-items-center">
				<div class="col-md-8">
					<h1 class="m-0 fw-bold text-purple-950 d-flex align-items-center gap-2">
						<i class="fas fa-chart-line text-purple-600"></i> Laporan Pendapatan
					</h1>
					<p class="text-muted small mb-0 mt-1">Ringkasan uang masuk dari seluruh pembayaran siswa, bukan daftar transaksi satu per satu.</p>
				</div>
                <div class="col-md-4 text-md-start mt-2 mt-md-0">
                    <div class="d-inline-flex align-items-center gap-1 flex-wrap">
                        <select class="form-select form-select-sm" id="filterP" style="width:auto; min-width:70px;">
                            <option value="monthly" {{ ($filter ?? 'monthly') === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            <option value="yearly" {{ ($filter ?? 'monthly') === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                        </select>
                        <select id="yearP" class="form-select form-select-sm" style="width:auto; min-width:65px;">
                            @foreach(($availableYears ?? collect([now()->year])) as $availableYear)
                                <option value="{{ $availableYear }}" {{ ($year ?? now()->year) === $availableYear ? 'selected' : '' }}>{{ $availableYear }}</option>
                            @endforeach
                        </select>
                        <input type="date" id="startDate" class="form-control form-control-sm" style="width:115px;" />
                        <input type="date" id="endDate" class="form-control form-control-sm" style="width:115px;" />
                        <a href="#" id="exportExcelBtn" class="btn btn-sm btn-success px-1">Excel</a>
                        <a href="#" id="exportPdfBtn" class="btn btn-sm btn-danger px-1" target="_blank">PDF</a>
                    </div>
                </div>
			</div>
		</div>
	</section>

	<section class="content px-4 pb-4">
		<div class="container-fluid">

			<div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card p-3 shadow-sm h-100 border-0">
                        <small class="text-uppercase text-muted">Total Pendapatan</small>
                        <div class="h4 fw-bold mt-1 mb-0">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                        <small class="text-success"><i class="fas fa-arrow-up"></i> {{ $targetPercent ?? 0 }}% tercapai</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 shadow-sm h-100 border-0">
                        <small class="text-uppercase text-muted">Transaksi Lunas</small>
                        <div class="h4 fw-bold mt-1 mb-0">{{ $paymentCount ?? 0 }}</div>
                        <small class="text-muted">{{ $paymentCount ?? 0 }} transaksi</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 shadow-sm h-100 border-0">
                        <small class="text-uppercase text-muted">Rata-rata / Transaksi</small>
                        <div class="h4 fw-bold mt-1 mb-0">Rp {{ number_format($avgPerTransaction ?? 0, 0, ',', '.') }}</div>
                        <small class="text-muted">per transaksi</small>
                    </div>
                </div>
            </div>

			<div class="row g-4">
				<div class="col-lg-8">
					<div class="card shadow-sm">
						<div class="card-header bg-white d-flex align-items-center justify-content-between">
							<h5 class="mb-0">Tren Pendapatan</h5>
						</div>
						<div class="card-body" style="height:320px;">
							<canvas id="revenueChartBig"></canvas>

						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="card shadow-sm">
						<div class="card-header bg-white d-flex align-items-center justify-content-between">
							<h5 class="mb-0">Pendapatan per Paket</h5>
						</div>
						<div class="card-body">
							@foreach($packageTotals as $pname => $pval)
								<div class="d-flex justify-content-between align-items-center mb-3">
									<div>
										<div class="fw-semibold">{{ $pname }}</div>
										<small class="text-muted">{{ $pval['count'] }} transaksi</small>
									</div>
									<div class="text-end">
										<div class="fw-bold">Rp {{ number_format($pval['revenue'],0,',','.') }}</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-4">
				<div class="col-12">
					<div class="card shadow-sm">
						<div class="card-header bg-white">
							<h5 class="mb-0">Pendapatan per Tutor</h5>
						</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table mb-0">
									<thead class="bg-light">
										<tr>
											<th>Tutor</th>
											<th>Jumlah Sesi Terbayar</th>
											<th>Jumlah Siswa</th>
											<th class="text-end">Total Pendapatan</th>
										</tr>
									</thead>
									<tbody>
										@foreach($tutorTotals as $tutor => $tv)
											<tr>
												<td>{{ $tutor }}</td>
												<td>{{ $tv['sesi'] }}</td>
												<td>{{ $tv['siswa'] }}</td>
												<td class="text-end">Rp {{ number_format($tv['revenue'],0,',','.') }}</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="mt-3 text-muted small">
				Catatan: ini contoh tampilan laporan pendapatan, data diambil dari siswa yang berstatus aktif dan memiliki bukti transfer.
			</div>

		</div>
	</section>

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const labels = @json($chartLabels ?? []);
			const data = @json($chartData ?? []);

			function buildQuery() {
				const params = new URLSearchParams();
				const filter = document.getElementById('filterP');
				const year = document.getElementById('yearP');
				const start = document.getElementById('startDate');
				const end = document.getElementById('endDate');
				if (filter) params.set('filter', filter.value || 'monthly');
				if (year) params.set('year', year.value || new Date().getFullYear());
				if (start && start.value) params.set('start_date', start.value);
				if (end && end.value) params.set('end_date', end.value);
				return params.toString();
			}

			const excelBtn = document.getElementById('exportExcelBtn');
			const pdfBtn = document.getElementById('exportPdfBtn');
			const filterSelect = document.getElementById('filterP');
			const yearSelect = document.getElementById('yearP');

			if (excelBtn) {
				excelBtn.addEventListener('click', function (ev) {
					ev.preventDefault();
					window.location = '{{ route("admin.laporan-pendapatan.export.excel") }}' + '?' + buildQuery();
				});
			}

			if (pdfBtn) {
				pdfBtn.addEventListener('click', function (ev) {
					ev.preventDefault();
					const url = '{{ route("admin.laporan-pendapatan.export.pdf") }}' + '?' + buildQuery();
					window.open(url, '_blank');
				});
			}

			function refreshReport() {
				window.location.search = buildQuery();
			}

			if (filterSelect) {
				filterSelect.addEventListener('change', refreshReport);
			}
			if (yearSelect) {
				yearSelect.addEventListener('change', refreshReport);
			}

			const ctx = document.getElementById('revenueChartBig');
			if (ctx && labels.length > 0) {
				new Chart(ctx.getContext('2d'), {
					type: 'bar',
					data: {
						labels: labels,
						datasets: [{
							label: 'Pendapatan (Rp)',
							data: data,
							backgroundColor: 'rgba(99,102,241,0.8)'
						}]
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						plugins: { legend: { display: false } },
						scales: {
							y: {
								beginAtZero: true,
								ticks: {
									callback: function (v) { return 'Rp ' + v.toLocaleString('id-ID'); }
								}
							}
						}
					}
				});
			}
		});
	</script>
@endsection