@extends('layouts.header', ['active_step' => 4])

@section('content')
    <style>
        .payment-option-card {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .payment-option-card:hover {
            transform: translateY(-2px);
            border-color: #7c3aed !important;
        }

        .payment-option-card.active-card {
            border-color: #7c3aed !important;
            background-color: #f5f3ff !important;
        }

        .instruction-box {
            transition: all 0.3s ease;
        }

        .animate-fade-change {
            animation: fadeChange 0.3s ease-in-out;
        }

        @keyframes fadeChange {
            0% {
                opacity: 0;
                transform: translateY(8px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (min-width: 1024px) {
            .payment-container {
                height: 720px;
            }

            .scrollable-left {
                height: 100%;
                overflow-y: auto;
            }

            .fixed-right {
                height: 100%;
                overflow: hidden;
            }
        }

        .scrollable-left::-webkit-scrollbar {
            width: 6px;
        }

        .scrollable-left::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollable-left::-webkit-scrollbar-thumb {
            background: #d8d3e8;
            border-radius: 10px;
        }

        .scrollable-left::-webkit-scrollbar-thumb:hover {
            background: #a78bfa;
        }
    </style>

    <!-- ══════ MAIN CARD CONTAINER (FORM WRAPPED) ══════ -->
    <form action="{{ route('siswa.payment.submit') }}" method="POST" enctype="multipart/form-data"
        class="w-full max-w-6xl bg-white rounded-3xl shadow-xl overflow-hidden border border-purple-100 grid grid-cols-1 lg:grid-cols-12 payment-container">
        @csrf
        <input type="hidden" name="paket_id" value="{{ $paket->id }}">
        <input type="hidden" name="tipe_paket" value="{{ request('tipe_paket', '1') }}">

        {{-- ── Pass chosen per-mapel schedule data ── --}}
        @if(!empty($mapelJadwal))
            @foreach($mapelJadwal as $idx => $namaMapel)
                <input type="hidden" name="mapel_jadwal[{{ $idx }}]" value="{{ $namaMapel }}">
                <input type="hidden" name="sesi[{{ $idx }}]" value="{{ $sesiPerMapel[$idx] ?? 0 }}">
                @if(!empty($hariPerMapel[$idx]))
                    @foreach($hariPerMapel[$idx] as $hariIdx => $hariVal)
                        <input type="hidden" name="hari[{{ $idx }}][{{ $hariIdx }}]" value="{{ $hariVal }}">
                    @endforeach
                @endif
                <input type="hidden" name="tanggal_mulai[{{ $idx }}]" value="{{ $tanggalArr[$idx] ?? '' }}">
            @endforeach
        @else
            {{-- Fallback: old fields from GET request --}}
            @if(request('mapel'))
                @foreach(request('mapel') as $m)
                    <input type="hidden" name="mapel[]" value="{{ is_string($m) ? $m : '' }}">
                @endforeach
            @endif
        @endif

        @if(request('pilihan_guru'))
            <input type="hidden" name="pilihan_guru" value="{{ request('pilihan_guru') }}">
        @endif
        @if(request('pilihan_guru_inggris'))
            <input type="hidden" name="pilihan_guru_inggris" value="{{ request('pilihan_guru_inggris') }}">
        @endif

        <!-- ══════════════ LEFT COLUMN: PAYMENT METHODS (7 COLS) ══════════════ -->
        <div class="lg:col-span-7 p-6 sm:p-10 flex flex-col justify-between scrollable-left">
            <div>
                <!-- Brand Header -->
                <div class="flex items-center gap-3 mb-8">
                    <img src="{{ asset('images/logoPM.webp') }}" alt="Logo" class="w-10 h-10 object-contain" />
                    <span class="font-display text-lg font-bold text-purple-950">Paradise <span
                            class="text-amber-500">of Math</span></span>
                </div>

                <h1 class="font-display text-2xl sm:text-3xl font-bold text-purple-950 mb-2">Metode Pembayaran</h1>
                <p class="text-sm text-slate-500 mb-8">Pilih opsi transfer dan unggah bukti transaksi Anda di bawah ini.
                </p>

                <!-- ERROR / VALIDATION ALERTS -->
                @if ($errors->any())
                    <div class="mb-5 p-4 rounded-xl text-sm font-medium border bg-rose-50 border-rose-200 text-rose-800"
                        role="alert">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- PAYMENT SELECTION GRID -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6" id="paymentMethods">
                    <!-- Bank Transfer Option -->
                    <div class="payment-option-card active-card border-2 border-slate-100 rounded-2xl p-4 flex flex-col justify-between min-h-[110px]"
                        data-method="bank">
                        <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Transfer
                            Bank</span>
                        <div class="flex flex-wrap gap-1 mt-auto">
                            @foreach ($banks as $bank)
                                <span
                                    class="text-[9px] font-black bg-white border border-slate-200 rounded px-1.5 py-0.5 text-blue-700 uppercase">{{ $bank->nama_bank }}</span>
                            @endforeach
                        </div>
                    </div>

                    <!-- E-Wallet Option -->
                    <div class="payment-option-card border-2 border-slate-100 rounded-2xl p-4 flex flex-col justify-between min-h-[110px]"
                        data-method="ewallet">
                        <span
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">E-Wallet</span>
                        <div class="flex flex-wrap gap-1 mt-auto">
                            @foreach ($ewallets as $ewallet)
                                <span
                                    class="text-[9px] font-black bg-white border border-slate-200 rounded px-1.5 py-0.5 text-emerald-600 uppercase">{{ $ewallet->nama_bank }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                <input type="hidden" name="payment_method" id="selectedMethod" value="bank">
                <!-- PAYMENT INSTRUCTIONS PANEL -->
                <div class="p-5 bg-slate-50 border border-slate-100 rounded-2xl instruction-box mb-6">

                    <!-- Bank Instructions -->
                    <div id="inst-bank" class="instruction-content">
                        <h6 class="text-sm font-bold text-violet-950 mb-3"><i
                                class="fas fa-university mr-2 text-violet-600"></i> Transfer Bank Resmi</h6>
                        <p class="text-xs text-slate-500 mb-3">Silakan kirimkan pembayaran Anda ke salah satu rekening
                            resmi berikut:</p>

                        <div class="space-y-2.5 mb-2">
                            @foreach ($banks as $bank)
                                <div
                                    class="p-3 bg-white border border-slate-100 rounded-xl flex items-center justify-between shadow-sm">
                                    <div class="flex items-center gap-2.5">
                                        <span
                                            class="text-[9px] font-black bg-blue-50 text-blue-700 px-1.5 py-0.5 rounded border border-blue-100 uppercase">{{ $bank->nama_bank }}</span>
                                        <div>
                                            <span
                                                class="d-block font-mono text-xs font-bold text-slate-800">{{ $bank->nomor_rekening }}</span>
                                            <small class="text-slate-400 text-[10px] d-block mt-0.5">a/n
                                                {{ $bank->atas_nama }}</small>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="bg-slate-100 hover:bg-slate-200 text-slate-600 rounded px-2.5 py-1 text-[10px] font-bold border-0 transition-all copy-btn"
                                        data-clipboard="{{ str_replace('-', '', $bank->nomor_rekening) }}">
                                        Salin
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- E-Wallet Instructions -->
                    <div id="inst-ewallet" class="instruction-content d-none">
                        <h6 class="text-sm font-bold text-violet-950 mb-3"><i
                                class="fas fa-mobile-alt mr-2 text-violet-600"></i> E-Wallet Provider</h6>
                        <p class="text-xs text-slate-500 mb-3">Silakan kirimkan pembayaran Anda ke akun e-wallet
                            berikut:</p>

                        <div class="space-y-2.5 mb-2">
                            @foreach ($ewallets as $ewallet)
                                <div
                                    class="p-3 bg-white border border-slate-100 rounded-xl flex items-center justify-between shadow-sm">
                                    <div class="flex items-center gap-2.5">
                                        <span
                                            class="text-[9px] font-black bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded border border-emerald-100 uppercase">{{ $ewallet->nama_bank }}</span>
                                        <div>
                                            <span
                                                class="d-block font-mono text-xs font-bold text-slate-800">{{ $ewallet->nomor_rekening }}</span>
                                            <small class="text-slate-400 text-[10px] d-block mt-0.5">a/n
                                                {{ $ewallet->atas_nama }}</small>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="bg-slate-100 hover:bg-slate-200 text-slate-600 rounded px-2.5 py-1 text-[10px] font-bold border-0 transition-all copy-btn"
                                        data-clipboard="{{ str_replace('-', '', $ewallet->nomor_rekening) }}">
                                        Salin
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- ══════ FILE UPLOAD DRAG-AND-DROP AREA ══════ -->
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Upload Bukti
                        Transfer</label>
                    <div class="relative border-2 border-dashed border-slate-200 hover:border-purple-400 rounded-2xl p-6 text-center cursor-pointer transition-all bg-slate-50"
                        id="dropzone">
                        <input type="file" name="bukti_transfer" id="fileInput" accept=".jpg,.jpeg,.png,.pdf"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                        <i class="fas fa-cloud-upload-alt text-slate-400 text-3xl mb-2" id="uploadIcon"></i>
                        <p class="text-xs font-bold text-slate-700 mb-1" id="uploadText">Klik atau seret file bukti
                            transfer Anda ke sini</p>
                        <p class="text-[10px] text-slate-400">Mendukung format JPG, PNG, atau PDF (Maks. 2MB)</p>
                    </div>
                </div>

            </div>

            <!-- Footer Copy -->
            <div class="mt-8 pt-4 border-t border-slate-100 text-center sm:text-left text-xs text-slate-400">
                &copy; 2026 · Paradise of Math — Sistem Transaksi Aman Siswa
            </div>
        </div>

        <!-- ══════════════ RIGHT COLUMN: BILLING INVOICE DETAILS (5 COLS) ══════════════ -->
        <div
            class="lg:col-span-5 p-6 sm:p-10 bg-slate-50 border-t lg:border-t-0 lg:border-l border-slate-100 flex flex-col justify-center fixed-right">

            <div
                class="bg-white rounded-3xl border border-violet-100 p-8 shadow-lg shadow-violet-100/30 flex flex-col justify-between min-h-[460px] relative">
                <div>
                    <!-- Kategori Badge -->
                    <div class="mb-4">
                        <span class="px-3 py-1 bg-violet-50 text-violet-700 text-xs font-bold uppercase rounded-full">
                            {{ $paket->kategori }}
                        </span>
                    </div>

                    <!-- Title -->
                    <h3 class="text-2xl font-bold text-violet-950 mb-2">Perkiraan Biaya Bimbingan</h3>
                    <p class="text-slate-500 text-xs mb-6">Hitung estimasi perkiraan biaya bimbingan belajar berdasarkan paket dan sesi Anda.</p>

                    <!-- Details List -->
                    <div class="space-y-4 text-sm text-slate-600 mb-6">
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <span class="d-block font-bold text-violet-950">Bimbel {{ $paket->nama_paket }}</span>
                                <small class="text-slate-400 text-xxs block mt-0.5">({{ explode(':', $detailString)[0] }})</small>
                            </div>
                            <span class="font-bold text-violet-950">Rp {{ number_format($harga, 0, ',', '.') }} <span class="text-xs text-slate-400 font-normal">/ sesi (est.)</span></span>
                        </div>
                        {{-- ── Ringkasan Jadwal per Mapel ── --}}
                        @if(!empty($mapelJadwal))
                            <div class="pt-2 border-t border-slate-100 space-y-3">
                                <span class="block font-bold text-slate-700 text-xs uppercase tracking-wider mb-2">Jadwal Belajar per Mapel</span>
                                @foreach($mapelJadwal as $idx => $namaMapel)
                                    @php
                                        $sesiIdx  = !empty($sesiPerMapel[$idx]) ? $sesiPerMapel[$idx] : 1;
                                        $hariListMapel = $hariPerMapel[$idx] ?? [];
                                        $hariDisplay = !empty($hariListMapel) ? implode(' & ', array_filter($hariListMapel)) : '-';
                                        $tgl      = $tanggalArr[$idx] ?? null;
                                        $tglStr   = $tgl ? \Carbon\Carbon::parse($tgl)->format('d M Y') : '-';
                                        $hargaMapel = $harga * $sesiIdx;
                                    @endphp
                                    <div class="bg-purple-50/60 rounded-xl p-3 border border-purple-100">
                                        <div class="flex justify-between items-center mb-1.5">
                                            <span class="font-bold text-purple-900 text-xs">{{ $namaMapel }}</span>
                                            <span class="text-[11px] font-semibold text-purple-700 bg-white px-2 py-0.5 rounded-full border border-purple-200">{{ $sesiIdx }}x sesi</span>
                                        </div>
                                        <div class="text-[11px] text-slate-500 space-y-0.5">
                                            <div class="text-right font-semibold text-purple-700 mt-1">
                                                Rp {{ number_format($harga, 0, ',', '.') }} × {{ $sesiIdx }} = Rp {{ number_format($hargaMapel, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif(request('mapel'))
                            {{-- Fallback old display --}}
                            <div class="flex justify-between items-start gap-4 pt-2 border-t border-slate-100">
                                <div>
                                    <span class="d-block font-medium text-slate-700 text-xs">Mata Pelajaran Terpilih</span>
                                    <ul class="list-disc pl-4 text-xxs text-slate-500 mt-1 space-y-1">
                                        @foreach(request('mapel') as $m)
                                            @if(is_string($m))
                                                <li>{{ $m }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-dashed border-slate-100 my-4"></div>

                    <!-- Total Tagihan (Perkiraan) -->
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-xs text-slate-500">
                            <span>Harga per sesi (est.)</span>
                            <span class="font-semibold text-slate-700">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-500">
                            <span>Total estimasi sesi</span>
                            <span class="font-semibold text-slate-700">{{ $totalSesi ?? 1 }}x</span>
                        </div>
                        <div class="flex justify-between items-baseline pt-2 border-t border-slate-100">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Perkiraan Total Biaya</span>
                            <span class="text-3xl font-black text-violet-950">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button Area -->
                <div>
                    <button type="submit" id="btnBayar" class="btn-brand font-bold text-md mb-4 border-0">
                        Konfirmasi &amp; Kirim Bukti <i class="fas fa-arrow-right ml-1.5"></i>
                    </button>

                    <div class="p-3 bg-violet-50/50 rounded-2xl border border-violet-100/50 flex items-start gap-2.5">
                        <i class="fas fa-calculator text-violet-600 mt-0.5 text-xs"></i>
                        <p class="text-[10px] text-violet-950 mb-0 leading-relaxed font-medium">
                            <strong class="text-violet-700">Catatan Perkiraan Harga:</strong> Nominal di atas merupakan estimasi perkiraan biaya bimbingan. Tarif akhir akan dikonfirmasi kembali oleh Admin / Tutor.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </form>

    <!-- JAVASCRIPT FOR INTERACTIVE ACTIONS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.payment-option-card');
            const hiddenInput = document.getElementById('selectedMethod');
            const instructionBox = document.querySelector('.instruction-box');

            const fileInput = document.getElementById('fileInput');
            const uploadIcon = document.getElementById('uploadIcon');
            const uploadText = document.getElementById('uploadText');

            // Toggle active styling on payment selection
            cards.forEach(card => {
                card.addEventListener('click', function () {
                    cards.forEach(c => {
                        c.classList.remove('active-card');
                        c.style.borderColor = '#f1f5f9';
                    });

                    this.classList.add('active-card');
                    this.style.borderColor = '#7c3aed';

                    const method = this.dataset.method;
                    hiddenInput.value = method;

                    // Fade-in effect on transition
                    instructionBox.classList.add('animate-fade-change');
                    setTimeout(() => {
                        instructionBox.classList.remove('animate-fade-change');
                    }, 300);

                    // Toggle corresponding instruction text
                    document.querySelectorAll('.instruction-content').forEach(el => {
                        el.classList.add('d-none');
                    });
                    document.getElementById('inst-' + method).classList.remove('d-none');
                });
            });

            // Update dropzone label on file selection
            fileInput.addEventListener('change', function () {
                if (fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    uploadIcon.className = "fas fa-file-invoice-dollar text-emerald-500 text-3xl mb-2";
                    uploadText.textContent = "File terpilih: " + file.name;
                    uploadText.className = "text-xs font-bold text-emerald-700 mb-1";
                } else {
                    uploadIcon.className = "fas fa-cloud-upload-alt text-slate-400 text-3xl mb-2";
                    uploadText.textContent = "Klik atau seret file bukti transfer Anda ke sini";
                    uploadText.className = "text-xs font-bold text-slate-700 mb-1";
                }
            });

            // Clipboard Copy handler
            const copyBtns = document.querySelectorAll('.copy-btn');
            copyBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const text = this.dataset.clipboard;
                    navigator.clipboard.writeText(text).then(() => {
                        const originalText = this.textContent;
                        this.textContent = 'Tersalin! ✔';
                        this.className = "bg-emerald-50 text-emerald-700 border border-emerald-200 rounded px-2.5 py-1 text-[10px] font-bold transition-all";
                        setTimeout(() => {
                            this.textContent = originalText;
                            this.className = "bg-slate-100 hover:bg-slate-200 text-slate-600 rounded px-2.5 py-1 text-[10px] font-bold border-0 transition-all";
                        }, 2000);
                    });
                });
            });
        });
    </script>
@endsection