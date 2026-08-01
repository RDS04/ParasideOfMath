@extends('layouts.header', ['active_step' => 3])

@section('content')
    <style>
        .step-panel {
            display: none;
        }

        .step-panel.active {
            display: block;
            animation: fadeIn 0.25s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Validation UI State Classes */
        .field.invalid .form-input {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1) !important;
        }

        .field.invalid .choice {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }

        .field.invalid .error {
            display: block !important;
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.375rem;
            font-weight: 500;
        }

        .error {
            display: none;
        }
    </style>
    <div
        class="w-full max-w-6xl bg-white rounded-3xl shadow-xl overflow-hidden border border-purple-100 grid grid-cols-1 lg:grid-cols-12">

        <!-- ══════════════ LEFT COLUMN: REGISTRATION FORM (7 COLS) ══════════════ -->
        <div class="lg:col-span-7 p-6 sm:p-10 flex flex-col justify-between">
            <div>
                <!-- Brand Header -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logoPM.webp') }}" alt="Logo" class="w-10 h-10 object-contain" />
                        <span class="font-display text-lg font-bold text-purple-950">Paradise <span
                                class="text-amber-500">of Math</span></span>
                    </div>
                    <a href="{{ route('siswa.biodata') }}"
                        class="text-xs font-semibold text-purple-700 hover:text-purple-900 transition-colors inline-flex items-center gap-1">
                        <i class="fas fa-arrow-left"></i> Edit Biodata
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-5 p-4 rounded-xl text-sm font-medium border bg-emerald-50 border-emerald-200 text-emerald-800"
                        role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <h1 class="font-display text-2xl sm:text-3xl font-bold text-purple-950 mb-2">Formulir Pendaftaran</h1>
                <p class="text-sm text-slate-500 mb-8">Pilih paket belajar yang ingin Anda ikuti.</p>

                <!-- FORM -->
                <form id="regisForm" action="{{ route('siswa.payment') }}" method="GET" class="space-y-5" novalidate>

                    <!-- STEP 1 — DATA PENDAFTARAN -->
                    <div class="step-panel active space-y-5" data-step="1">
                        <!-- Name (Prefilled or editable) -->
                        <div class="field" data-required="true">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama
                                Lengkap<span class="text-amber-600 ml-1">*</span></label>
                            <input type="text" name="name"
                                value="{{ auth()->guard('siswa')->check() ? auth()->guard('siswa')->user()->name : '' }}"
                                placeholder="Contoh: Budi Santoso" class="form-input" required />
                            <div class="error">Nama lengkap wajib diisi.</div>
                        </div>

                        <!-- Email (Prefilled or editable) -->
                        <div class="field" data-required="true">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat
                                Email<span class="text-amber-600 ml-1">*</span></label>
                            <input type="email" name="email"
                                value="{{ auth()->guard('siswa')->check() ? auth()->guard('siswa')->user()->email : '' }}"
                                placeholder="budi@example.com" class="form-input" required />
                            <div class="error">Alamat email wajib diisi.</div>
                        </div>

                        <!-- Dropdown Package Selector -->
                        <div class="field" data-required="true">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Paket
                                Belajar<span class="text-amber-600 ml-1">*</span></label>
                            <select id="paketSelect" name="paket_id" class="form-input cursor-pointer" required>
                                @foreach(\App\Models\PaketBelajar::all() as $paket)
                                    <option value="{{ $paket->id }}" {{ $loop->first ? 'selected' : '' }}>
                                        {{ $paket->nama_paket }} ({{ $paket->kategori }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="error">Paket belajar wajib dipilih.</div>
                        </div>

                        <!-- Dropdown Tipe Paket Selector (Privat / Kelompok) -->
                        <div class="field" data-required="true">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Tipe
                                Pertemuan (Jumlah Peserta)<span class="text-amber-600 ml-1">*</span></label>
                            <select id="tipeSelect" name="tipe_paket" class="form-input cursor-pointer" required>
                                <!-- Dynamically populated via JS based on selected package -->
                            </select>
                            <div class="error">Tipe pertemuan wajib dipilih.</div>
                        </div>
                    </div>

                    <!-- STEP 2 — PILIH MATA PELAJARAN -->
                    <div class="step-panel space-y-5" data-step="2">
                        <div class="field" data-required="true">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mata
                                Pelajaran
                                &amp; Jumlah Minimal Shift/Minggu<span class="text-amber-600 ml-1">*</span></label>
                            <div class="text-xs text-slate-400 mb-3">Pilih semua pelajaran yang ingin di les kan di PM.
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach(\App\Models\Mapel::all() as $mapel)
                                    <label
                                        class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
                                        <input type="checkbox" name="mapel[]"
                                            value="{{ $mapel->nama_mapel }} {{ $mapel->shift }}x"
                                            class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
                                        <span>{{ $mapel->nama_mapel }} {{ $mapel->shift }}x</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="error">Pilih minimal satu mata pelajaran.</div>
                        </div>

                        <!-- Pilihan Guru Matematika (Dinamis) -->
                        <div class="field hidden mt-4" data-required="false" id="guruMatematikaContainer">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilihan
                                Guru Matematika</label>
                            <div class="flex flex-col gap-3">
                                <label
                                    class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
                                    <input type="radio" name="pilihan_guru" value="Kak Ika (Master)"
                                        class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
                                    <span>Kak Ika (Master)</span>
                                </label>
                                <label
                                    class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
                                    <input type="radio" name="pilihan_guru" value="Kak Angel (Co Master)"
                                        class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
                                    <span>Kak Angel (Co Master)</span>
                                </label>
                                <label
                                    class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
                                    <input type="radio" name="pilihan_guru" value="Kak Sofia (Co Master)"
                                        class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
                                    <span>Kak Sofia (Co Master)</span>
                                </label>
                                <label
                                    class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
                                    <input type="radio" name="pilihan_guru" value="Karyawan"
                                        class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
                                    <span>Karyawan</span>
                                </label>
                            </div>
                        </div>

                        <!-- Pilihan Guru Bahasa Inggris (Dinamis) -->
                        <div class="field hidden mt-4" data-required="false" id="guruInggrisContainer">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilihan
                                Guru Bahasa Inggris</label>
                            <div class="flex flex-col gap-3">
                                <label
                                    class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
                                    <input type="radio" name="pilihan_guru_inggris" value="Kak Angel"
                                        class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
                                    <span>Kak Angel</span>
                                </label>
                                <label
                                    class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
                                    <input type="radio" name="pilihan_guru_inggris" value="Kak Sofia"
                                        class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
                                    <span>Kak Sofia</span>
                                </label>
                                <label
                                    class="choice flex items-start gap-3 p-3.5 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950">
                                    <input type="radio" name="pilihan_guru_inggris" value="Karyawan"
                                        class="w-4 h-4 mt-0.5 accent-purple-700 shrink-0">
                                    <span>Karyawan</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3 — JADWAL & FREKUENSI BELAJAR -->
                    <div class="step-panel space-y-5" data-step="3">
                        <!-- Jumlah Pertemuan (Dinamis) -->
                        <div class="field mt-4" data-required="true" id="pertemuanSemingguField">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jumlah Pertemuan (Ditentukan Berdasarkan Total Shift Pelajaran)<span class="text-amber-600 ml-1">*</span></label>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                                @for($i = 1; $i <= 10; $i++)
                                    <label class="choice flex items-center justify-center p-3 border border-purple-100 rounded-xl bg-slate-50 hover:border-purple-500 cursor-pointer transition-all text-sm text-purple-950 font-bold" id="label-pertemuan-{{ $i }}">
                                        <input type="radio" name="jumlah_pertemuan" value="{{ $i }}" class="sr-only">
                                        <span>{{ $i }}x</span>
                                    </label>
                                @endfor
                            </div>
                            <div class="error" id="pertemuanError">Pilih jumlah pertemuan.</div>
                        </div>

                        <!-- Hari Pertemuan (Statis 2 Dropdown) -->
                        <div class="field hidden mt-4" data-required="true" id="datePickersContainer">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Hari Pertemuan Belajar (Senin s/d Sabtu)<span class="text-amber-600 ml-1">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="field" data-required="true">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Hari Pertemuan Ke-1<span class="text-amber-600 ml-1">*</span></label>
                                    <select name="hari_pertemuan[]" class="form-input text-sm cursor-pointer" required>
                                        <option value="">Pilih hari</option>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                        <option value="Sabtu">Sabtu</option>
                                    </select>
                                    <div class="error">Hari pertemuan wajib dipilih.</div>
                                </div>
                                <div class="field" data-required="true">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Hari Pertemuan Ke-2<span class="text-amber-600 ml-1">*</span></label>
                                    <select name="hari_pertemuan[]" class="form-input text-sm cursor-pointer" required>
                                        <option value="">Pilih hari</option>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                        <option value="Sabtu">Sabtu</option>
                                    </select>
                                    <div class="error">Hari pertemuan wajib dipilih.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Action Buttons -->
                    <div class="flex justify-between items-center gap-4 pt-4 mt-6" id="navRow">
                        <button type="button"
                            class="px-6 py-3 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold transition-all text-sm border border-slate-200 hidden"
                            id="btnBack">
                            ← Kembali
                        </button>
                        <button type="button" class="btn-brand flex-1 font-bold text-md" id="btnNext">
                            Lanjut →
                        </button>
                        <button type="submit" class="btn-brand flex-1 font-bold text-md hidden" id="btnSubmit">
                            Lanjutkan ke Pembayaran <i class="fas fa-arrow-right ml-1"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer Copy -->
            <div class="mt-8 pt-4 border-t border-slate-100 text-center sm:text-left text-xs text-slate-400">
                &copy; 2026 · Paradise of Math — Sistem Manajemen Registrasi Siswa
            </div>
        </div>

        <!-- ══════════════ RIGHT COLUMN: DYNAMIC PRICING PREVIEW (5 COLS) ══════════════ -->
        <div
            class="lg:col-span-5 p-6 sm:p-10 bg-slate-50 border-t lg:border-t-0 lg:border-l border-slate-100 flex flex-col justify-center">

            <div id="priceDetailCard"
                class="detail-card bg-white rounded-3xl border border-violet-100 p-8 shadow-lg shadow-violet-100/30 flex flex-col justify-between min-h-[460px] relative">

                <!-- Popularity Tag Badge -->
                <div id="populerBadge"
                    class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 bg-amber-400 text-purple-950 text-xxs font-black uppercase tracking-wider rounded-full shadow-md d-none">
                    Paling Populer
                </div>

                <div>
                    <!-- Category Badge -->
                    <div class="mb-4">
                        <span id="detailKategori"
                            class="px-3 py-1 bg-violet-50 text-violet-700 text-xs font-bold uppercase rounded-full">Dasar</span>
                    </div>

                    <!-- Package Title -->
                    <h3 id="detailNama" class="text-2xl font-bold text-violet-950 mb-2">SD &amp; SMP</h3>
                    <!-- Package Description -->
                    <p id="detailDeskripsi" class="text-slate-500 text-xs mb-6">Memperkuat konsep matematika dasar
                        sekolah dasar dan menengah.</p>

                    <!-- Pricing range -->
                    <div class="flex items-baseline gap-1 mb-6">
                        <span id="detailHargaMin" class="text-3xl sm:text-4xl font-black text-violet-950">50K</span>
                        <span id="detailHargaMax" class="text-slate-500 text-sm font-semibold">- 80K</span>
                        <span class="text-slate-400 text-xs font-semibold ml-1">/ sesi / org</span>
                    </div>

                    <div class="border-t border-dashed border-slate-100 my-4"></div>

                    <!-- Features -->
                    <h6 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Fasilitas &amp; Tarif
                        Rincian:</h6>
                    <ul id="detailList" class="space-y-3 text-sm text-slate-600 mb-2">
                        <!-- Populated dynamically via JS -->
                    </ul>
                </div>

                <!-- Accent Note -->
                <div class="mt-6 p-3 bg-violet-50/50 rounded-2xl border border-violet-100/50 flex items-start gap-2.5">
                    <i class="fas fa-shield-alt text-violet-600 mt-0.5 text-xs"></i>
                    <p class="text-[10px] text-violet-950 mb-0 leading-relaxed font-medium">
                        Tarif akhir akan menyesuaikan jumlah peserta kelompok dan jumlah sesi yang Anda setujui bersama
                        Tutor.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- JAVASCRIPT FOR DYNAMIC DATA BINDING -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Load packages payload from Laravel
            const packages = @json(\App\Models\PaketBelajar::all());

            const select = document.getElementById('paketSelect');
            const selectTipe = document.getElementById('tipeSelect');
            const card = document.getElementById('priceDetailCard');
            const detailKategori = document.getElementById('detailKategori');
            const detailNama = document.getElementById('detailNama');
            const detailDeskripsi = document.getElementById('detailDeskripsi');
            const detailHargaMin = document.getElementById('detailHargaMin');
            const detailHargaMax = document.getElementById('detailHargaMax');
            const detailList = document.getElementById('detailList');
            const populerBadge = document.getElementById('populerBadge');

            function formatPrice(val) {
                return val >= 1000 ? (val / 1000) + 'K' : val;
            }

            function populateTipe(paketId) {
                const p = packages.find(item => item.id == paketId);
                if (!p) return;

                let optionsHtml = '';
                if (p.detail_1) optionsHtml += `<option value="1">${p.detail_1}</option>`;
                if (p.detail_2) optionsHtml += `<option value="2">${p.detail_2}</option>`;
                if (p.detail_3) optionsHtml += `<option value="3">${p.detail_3}</option>`;
                if (p.detail_4) optionsHtml += `<option value="4">${p.detail_4}</option>`;

                selectTipe.innerHTML = optionsHtml;
                updateTipeHighlight();
            }

            function updateTipeHighlight() {
                const selectedTipe = selectTipe.value; // '1', '2', '3', or '4'
                const items = detailList.querySelectorAll('li');
                items.forEach((item, index) => {
                    // index 0 to 3 correspond to detail_1 to detail_4
                    if (index < 4) {
                        if (index + 1 == selectedTipe) {
                            item.className = "flex items-start gap-2.5 p-2 rounded-lg bg-emerald-50 text-emerald-950 font-bold border border-emerald-200 transition-all duration-200";
                            item.querySelector('i').className = "fas fa-check-circle text-emerald-600";
                        } else {
                            item.className = "flex items-start gap-2.5 p-2 rounded-lg text-slate-500 transition-all duration-200 opacity-60";
                            item.querySelector('i').className = "fas fa-check-circle text-slate-300";
                        }
                    }
                });
            }

            function updatePreview(id) {
                // Find selected package
                const p = packages.find(item => item.id == id);
                if (!p) return;

                // Add fade-in transition
                card.classList.add('animate-fade-change');
                setTimeout(() => {
                    card.classList.remove('animate-fade-change');
                }, 300);

                // Update text elements
                detailNama.textContent = p.nama_paket;
                detailKategori.textContent = p.kategori;
                detailDeskripsi.textContent = p.deskripsi || '';
                detailHargaMin.textContent = formatPrice(p.harga_min);
                detailHargaMax.textContent = '- ' + formatPrice(p.harga_max);

                // Check popularity tag
                if (p.is_populer) {
                    populerBadge.classList.remove('d-none');
                    card.style.borderColor = '#fbbf24';
                    card.style.borderWidth = '2px';
                    detailNama.className = "text-2xl font-bold text-violet-950 mb-2";
                } else {
                    populerBadge.classList.add('d-none');
                    card.style.borderColor = '#ede9fe';
                    card.style.borderWidth = '1px';
                }

                // Render feature list
                let listHtml = '';
                const details = [p.detail_1, p.detail_2, p.detail_3, p.detail_4, p.detail_5];
                details.forEach((det, index) => {
                    if (det) {
                        const icon = index === 4 ? 'fa-clock text-violet-500' : 'fa-check-circle text-emerald-500';
                        listHtml += `
                                        <li class="flex items-start gap-2.5 p-2 rounded-lg transition-all duration-200">
                                            <span class="shrink-0 mt-0.5"><i class="fas ${icon}"></i></span>
                                            <span>${det}</span>
                                        </li>
                                    `;
                    }
                });
                detailList.innerHTML = listHtml;

                // Repopulate Tipe select based on new package
                populateTipe(id);
            }

            // Listen to dropdown changes
            select.addEventListener('change', function () {
                updatePreview(this.value);
            });

            selectTipe.addEventListener('change', function () {
                updateTipeHighlight();
            });

            // Initial render
            if (select.value) {
                updatePreview(select.value);
            }

            // Wizard Step Navigation
            let currentStep = 1;
            const stepPanels = document.querySelectorAll('.step-panel');
            const btnBack = document.getElementById('btnBack');
            const btnNext = document.getElementById('btnNext');
            const btnSubmit = document.getElementById('btnSubmit');
            const form = document.getElementById('regisForm');

            function renderSteps() {
                stepPanels.forEach(panel => {
                    panel.classList.toggle('active', parseInt(panel.dataset.step) === currentStep);
                });

                if (currentStep === 1) {
                    btnBack.classList.add('hidden');
                    btnNext.classList.remove('hidden');
                    btnSubmit.classList.add('hidden');
                } else if (currentStep === 2) {
                    btnBack.classList.remove('hidden');
                    btnNext.classList.remove('hidden');
                    btnSubmit.classList.add('hidden');
                } else if (currentStep === 3) {
                    btnBack.classList.remove('hidden');
                    btnNext.classList.add('hidden');
                    btnSubmit.classList.remove('hidden');
                }
            }

            function validateStep(step) {
                const panel = document.querySelector(`.step-panel[data-step="${step}"]`);
                let valid = true;

                panel.querySelectorAll('.field[data-required="true"]').forEach(field => {
                    const radios = field.querySelectorAll('input[type=radio]');
                    const checks = field.querySelectorAll('input[type=checkbox]');
                    let filled = true;

                    if (radios.length) {
                        filled = Array.from(radios).some(r => r.checked);
                    } else if (checks.length) {
                        filled = Array.from(checks).some(c => c.checked);
                    } else {
                        const input = field.querySelector('input, select, textarea');
                        if (input) {
                            if (input.type === 'email') {
                                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                filled = emailPattern.test(input.value.trim());
                            } else {
                                filled = !!input.value.trim();
                            }
                        }
                    }

                    if (!filled) {
                        field.classList.add('invalid');
                        valid = false;
                    } else {
                        field.classList.remove('invalid');
                    }
                });

                if (!valid) {
                    const firstInvalid = panel.querySelector('.field.invalid');
                    if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return valid;
            }

            btnNext.addEventListener('click', () => {
                if (currentStep === 1) {
                    if (validateStep(1)) {
                        currentStep = 2;
                        renderSteps();
                    }
                } else if (currentStep === 2) {
                    if (validateStep(2)) {
                        currentStep = 3;
                        renderSteps();
                    }
                }
            });

            btnBack.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    renderSteps();
                }
            });

            form.addEventListener('submit', (e) => {
                if (currentStep === 1) {
                    e.preventDefault();
                    if (validateStep(1)) {
                        currentStep = 2;
                        renderSteps();
                    }
                } else if (currentStep === 2) {
                    e.preventDefault();
                    if (validateStep(2)) {
                        currentStep = 3;
                        renderSteps();
                    }
                } else if (currentStep === 3) {
                    if (!validateStep(3)) {
                        e.preventDefault();
                    }
                }
            });

            // Generic function to check if a field is filled
            function isFieldFilled(field) {
                const radios = field.querySelectorAll('input[type=radio]');
                const checks = field.querySelectorAll('input[type=checkbox]');
                if (radios.length) {
                    return Array.from(radios).some(r => r.checked);
                }
                if (checks.length) {
                    return Array.from(checks).some(c => c.checked);
                }
                const input = field.querySelector('input, select, textarea');
                if (input) {
                    if (input.type === 'email') {
                        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        return emailPattern.test(input.value.trim());
                    }
                    return !!input.value.trim();
                }
                return true;
            }

            form.addEventListener('input', (e) => {
                const field = e.target.closest('.field');
                if (field && isFieldFilled(field)) {
                    field.classList.remove('invalid');
                }
            });

            form.addEventListener('change', (e) => {
                const field = e.target.closest('.field');
                if (field && isFieldFilled(field)) {
                    field.classList.remove('invalid');
                }
            });

            // Dynamic teacher selection display based on checked subjects
            const checkboxes = form.querySelectorAll('input[type="checkbox"][name="mapel[]"]');
            const mathContainer = document.getElementById('guruMatematikaContainer');
            const inggrisContainer = document.getElementById('guruInggrisContainer');

            function updateTeacherContainers() {
                let hasMath = false;
                let hasInggris = false;

                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        const val = cb.value.toLowerCase();
                        if (val.includes('matematika')) {
                            hasMath = true;
                        }
                        if (val.includes('inggris')) {
                            hasInggris = true;
                        }
                    }
                });

                if (hasMath) {
                    mathContainer.classList.remove('hidden');
                } else {
                    mathContainer.classList.add('hidden');
                    mathContainer.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
                }

                if (hasInggris) {
                    inggrisContainer.classList.remove('hidden');
                } else {
                    inggrisContainer.classList.add('hidden');
                    inggrisContainer.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
                }
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateTeacherContainers);
            });

            updateTeacherContainers();

            // Dynamic schedule picker logic based on chosen mapel shift count
            const pertemuanRadios = document.querySelectorAll('input[name="jumlah_pertemuan"]');
            const pertemuanField = document.getElementById('pertemuanSemingguField');
            const dateContainer = document.getElementById('datePickersContainer');
            const dateGrid = document.getElementById('dateInputsGrid');

            function getSelectedShiftsCount() {
                let totalShifts = 0;
                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        const val = cb.value;
                        if (val) {
                            const match = val.match(/(\d+)x/);
                            if (match) {
                                totalShifts += parseInt(match[1]);
                            }
                        }
                    }
                });
                return totalShifts;
            }

            function updateMeetingOptions() {
                const minMeetings = getSelectedShiftsCount();
                
                pertemuanRadios.forEach(radio => {
                    const val = parseInt(radio.value);
                    const label = document.getElementById(`label-pertemuan-${val}`);
                    
                    if (val < minMeetings) {
                        radio.disabled = true;
                        label.classList.add('opacity-40', 'cursor-not-allowed');
                        label.classList.remove('hover:border-purple-500');
                        if (radio.checked) {
                            radio.checked = false;
                            label.classList.remove('border-purple-600', 'bg-purple-50');
                        }
                    } else {
                        radio.disabled = false;
                        label.classList.remove('opacity-40', 'cursor-not-allowed');
                        label.classList.add('hover:border-purple-500');
                    }
                });

                // Auto-select minimum option if none is selected or selected is less than minMeetings
                let selectedRadio = document.querySelector('input[name="jumlah_pertemuan"]:checked');
                if (minMeetings > 0) {
                    if (!selectedRadio || parseInt(selectedRadio.value) < minMeetings) {
                        const targetRadio = document.querySelector(`input[name="jumlah_pertemuan"][value="${minMeetings}"]`);
                        if (targetRadio) {
                            targetRadio.checked = true;
                            highlightMeetingRadio(minMeetings);
                        }
                    }
                    dateContainer.classList.remove('hidden');
                } else {
                    if (selectedRadio) {
                        selectedRadio.checked = false;
                    }
                    highlightMeetingRadio(0);
                    dateContainer.classList.add('hidden');
                }
            }

            function highlightMeetingRadio(selectedValue) {
                pertemuanRadios.forEach(radio => {
                    const val = parseInt(radio.value);
                    const label = document.getElementById(`label-pertemuan-${val}`);
                    if (val === selectedValue) {
                        label.classList.add('border-purple-600', 'bg-purple-50', 'ring-2', 'ring-purple-600/20');
                        label.classList.remove('bg-slate-50', 'border-purple-100');
                    } else {
                        label.classList.remove('border-purple-600', 'bg-purple-50', 'ring-2', 'ring-purple-600/20');
                        label.classList.add('bg-slate-50', 'border-purple-100');
                    }
                });
            }

            pertemuanRadios.forEach(radio => {
                radio.addEventListener('change', function () {
                    const val = parseInt(this.value);
                    highlightMeetingRadio(val);
                    dateContainer.classList.remove('hidden');
                    pertemuanField.classList.remove('invalid');
                });
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateMeetingOptions);
            });

            updateMeetingOptions();

            // Initial step setup
            renderSteps();
        });
    </script>
@endsection