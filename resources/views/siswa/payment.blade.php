<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Kelas · Paradise of Math</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f4f2fa 0%, #ece9f5 100%);
        }

        .font-display {
            font-family: 'Fraunces', Georgia, serif;
        }

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

        .btn-brand {
            background: linear-gradient(135deg, #7c3aed, #4c1d95);
            border: none;
            color: white;
            font-weight: 700;
            padding: 14px;
            border-radius: 12px;
            box-shadow: 0 8px 24px -10px rgba(76, 29, 149, 0.45);
            transition: all 0.2s ease;
            cursor: pointer;
            width: 100%;
            text-align: center;
            display: inline-block;
        }

        .btn-brand:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -10px rgba(76, 29, 149, 0.55);
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
</head>

<body class="min-h-screen flex flex-col items-center justify-center p-4 sm:p-8">

    <!-- ══════ STEP INDICATOR ══════ -->
    <div class="w-full max-w-5xl mb-8">
        <div class="flex items-center justify-between max-w-xl mx-auto px-4">
            <!-- Step 1: Register -->
            <div class="flex flex-col items-center">
                <div class="w-9 h-9 rounded-full bg-purple-700 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="3"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="text-[10px] sm:text-xs font-bold text-purple-700 mt-2">Daftar Akun</span>
            </div>

            <div class="flex-1 h-1 bg-purple-700 mx-2 mb-4"></div>

            <!-- Step 2: Category -->
            <div class="flex flex-col items-center">
                <div class="w-9 h-9 rounded-full bg-purple-700 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="3"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="text-[10px] sm:text-xs font-bold text-purple-700 mt-2">Pilih Paket</span>
            </div>

            <div class="flex-1 h-1 bg-purple-700 mx-2 mb-4"></div>

            <!-- Step 3: Payment -->
            <div class="flex flex-col items-center">
                <div
                    class="w-9 h-9 rounded-full bg-purple-700 text-white flex items-center justify-center font-bold shadow-md ring-4 ring-purple-100">
                    3
                </div>
                <span class="text-[10px] sm:text-xs font-bold text-purple-700 mt-2">Pembayaran</span>
            </div>

            <div class="flex-1 h-1 bg-slate-200 mx-2 mb-4"></div>

            <!-- Step 4: Finish -->
            <div class="flex flex-col items-center">
                <div
                    class="w-9 h-9 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center font-bold">
                    4
                </div>
                <span class="text-[10px] sm:text-xs font-semibold text-slate-400 mt-2">Selesai</span>
            </div>
        </div>
    </div>

    <!-- ══════ MAIN CARD CONTAINER (FORM WRAPPED) ══════ -->
    <form action="{{ route('siswa.payment.submit') }}" method="POST" enctype="multipart/form-data"
        class="w-full max-w-5xl bg-white rounded-3xl shadow-xl overflow-hidden border border-purple-100 grid grid-cols-1 lg:grid-cols-12 payment-container">
        @csrf
        <input type="hidden" name="paket_id" value="{{ $paket->id }}">
        <input type="hidden" name="tipe_paket" value="{{ request('tipe_paket', '1') }}">

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
                    <h3 class="text-2xl font-bold text-violet-950 mb-2">Rincian Tagihan</h3>
                    <p class="text-slate-500 text-xs mb-6">Ulasan tagihan transaksi Anda yang sah dan tercatat.</p>

                    <!-- Details List -->
                    <div class="space-y-4 text-sm text-slate-600 mb-6">
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <span class="d-block font-bold text-violet-950">Bimbel {{ $paket->nama_paket }}</span>
                                <small
                                    class="text-slate-400 text-xxs block mt-0.5">({{ explode(':', $detailString)[0] }})</small>
                            </div>
                            <span class="font-bold text-violet-950">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-slate-100 my-4"></div>

                    <!-- Total Tagihan -->
                    <div class="flex justify-between items-baseline mb-6">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Tagihan</span>
                        <span class="text-3xl font-black text-violet-950">Rp
                            {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Submit Button Area -->
                <div>
                    <button type="submit" id="btnBayar" class="btn-brand font-bold text-md mb-4 border-0">
                        Konfirmasi &amp; Bayar <i class="fas fa-lock ml-1.5"></i>
                    </button>

                    <div class="p-3 bg-violet-50/50 rounded-2xl border border-violet-100/50 flex items-start gap-2.5">
                        <i class="fas fa-shield-alt text-violet-600 mt-0.5 text-xs"></i>
                        <p class="text-[10px] text-violet-950 mb-0 leading-relaxed font-medium">
                            Semua transaksi terenkripsi dengan aman secara otomatis. Hubungi CS jika Anda butuh bantuan.
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
</body>

</html>