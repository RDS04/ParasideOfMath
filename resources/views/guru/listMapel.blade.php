@php
	$groupedMapel = collect($categories ?? [])
		->filter(fn ($item) => (int) ($item->bank_soals_count ?? 0) > 0)
		->groupBy('nama_kategori')
		->map(function ($items) use ($jenjang, $kelas, $sub, $prefixRoute) {
			$items = $items->values();
			$first = $items->first();

			return (object) [
				'nama_kategori' => $first->nama_kategori,
				'bank_soals_count' => $items->sum('bank_soals_count'),
				'representative_id' => $first->id,
				'jenjang' => $jenjang,
				'kelas' => $kelas,
				'sub_kategori' => $sub,
				'url' => route($prefixRoute . '.index', [
					'jenjang' => $jenjang,
					'kelas' => $kelas,
					'sub_kategori' => $sub,
					'mapel' => $first->nama_kategori,
					'kategori_id' => $first->id,
				]),
			];
		})
		->values();

	if (!empty($mapel)) {
		$groupedMapel = $groupedMapel->filter(function ($item) use ($mapel) {
			return strcasecmp($item->nama_kategori, $mapel) === 0;
		})->values();
	}
@endphp

@if ($groupedMapel->isNotEmpty())
	<div class="collapse mb-4" id="panelListMapel">
		<div class="card border-0 shadow-sm rounded-2xl bg-white overflow-hidden">
			<div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
				<div class="d-flex align-items-center">
					<div class="rounded-circle bg-purple-100 text-purple-900 d-flex align-items-center justify-content-center mr-3 shadow-xs" style="width: 38px; height: 38px;">
						<i class="fas fa-book-open"></i>
					</div>
					<div>
						<h5 class="card-title font-bold text-purple-950 mb-0 text-base">List Mapel yang Sudah Ada Soal</h5>
						<span class="text-xs text-slate-500">Klik salah satu mapel untuk membuka semua soal yang tersimpan</span>
					</div>
				</div>
				<span class="badge bg-purple-100 text-purple-900 font-bold px-3 py-1.5 rounded-lg text-xs">
					{{ $groupedMapel->count() }} Mapel
				</span>
			</div>
			<div class="card-body p-4">
				<div class="row g-3">
					@foreach ($groupedMapel as $mapelItem)
						<div class="col-md-4 col-sm-6 mb-2">
							<a href="{{ $mapelItem->url }}" class="card border-2 border-slate-200 rounded-xl text-decoration-none transition-all hover:border-purple-400 hover:shadow-md h-100 overflow-hidden">
								<div class="card-body p-3.5 d-flex align-items-center">
									<div class="rounded-circle d-flex align-items-center justify-content-center mr-3 shadow-xs bg-purple-100" style="width: 44px; height: 44px; flex-shrink: 0;">
										<i class="fas fa-book text-purple-700 fa-lg"></i>
									</div>
									<div class="flex-1">
										<span class="d-block text-sm font-extrabold text-purple-950 leading-tight mb-0.5">{{ $mapelItem->nama_kategori }}</span>
										<span class="d-block text-xs text-slate-500">
											<i class="fas fa-question-circle text-purple-400 mr-1"></i>
											{{ $mapelItem->bank_soals_count }} soal
										</span>
									</div>
									<i class="fas fa-chevron-right text-slate-400 ml-2"></i>
								</div>
							</a>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
@endif
