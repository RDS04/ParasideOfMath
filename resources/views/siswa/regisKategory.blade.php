<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Kategori Belajar · Paradise of Math</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f4f2fa 0%, #ece9f5 100%);
        }
        .font-display {
            font-family: 'Fraunces', Georgia, serif;
        }
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1.5px solid #e9e6f4;
            background: #faf9fd;
            font-size: 0.95rem;
            outline: none;
            color: #241b3d;
            transition: all 0.2s ease;
        }
        .form-input:focus {
            background: white;
            border-color: #7c3aed;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.1);
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
        }
        .btn-brand:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -10px rgba(76, 29, 149, 0.55);
        }
        .detail-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .animate-fade-change {
            animation: fadeChange 0.3s ease-in-out;
        }
        @keyframes fadeChange {
            0% { opacity: 0; transform: translateY(8px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-8">

    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl overflow-hidden border border-purple-100 grid grid-cols-1 lg:grid-cols-12">
        
        <!-- ══════════════ LEFT COLUMN: REGISTRATION FORM (7 COLS) ══════════════ -->
        <div class="lg:col-span-7 p-6 sm:p-10 flex flex-col justify-between">
            <div>
                <!-- Brand Header -->
                <div class="flex items-center gap-3 mb-8">
                    <img src="{{ asset('images/logoPM.webp') }}" alt="Logo" class="w-10 h-10 object-contain" />
                    <span class="font-display text-lg font-bold text-purple-950">Paradise <span class="text-amber-500">of Math</span></span>
                </div>

                <h1 class="font-display text-2xl sm:text-3xl font-bold text-purple-950 mb-2">Formulir Pendaftaran</h1>
                <p class="text-sm text-slate-500 mb-8">Silakan lengkapi data diri Anda dan pilih paket belajar yang ingin diikuti.</p>

                <!-- FORM -->
                <form action="{{ route('siswa.payment') }}" method="GET" class="space-y-5">
                    
                    <!-- Name (Prefilled or editable) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input type="text" name="name" 
                               value="{{ auth()->guard('siswa')->check() ? auth()->guard('siswa')->user()->name : '' }}" 
                               placeholder="Contoh: Budi Santoso" class="form-input" required />
                    </div>

                    <!-- Email (Prefilled or editable) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Email</label>
                        <input type="email" name="email" 
                               value="{{ auth()->guard('siswa')->check() ? auth()->guard('siswa')->user()->email : '' }}" 
                               placeholder="budi@example.com" class="form-input" required />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- WhatsApp -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. WhatsApp Aktif</label>
                            <input type="tel" name="whatsapp" placeholder="0812xxxxxxxx" class="form-input" required />
                        </div>

                        <!-- School name -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Asal Sekolah / Kampus</label>
                            <input type="text" name="sekolah" placeholder="SMAN 1 Padang" class="form-input" required />
                        </div>
                    </div>

                    <!-- Dropdown Package Selector -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Paket Belajar</label>
                        <select id="paketSelect" name="paket_id" class="form-input cursor-pointer" required>
                            @foreach(\App\Models\PaketBelajar::all() as $paket)
                                <option value="{{ $paket->id }}" {{ $loop->first ? 'selected' : '' }}>
                                    {{ $paket->nama_paket }} ({{ $paket->kategori }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="btn-brand w-full font-bold text-md">
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
        <div class="lg:col-span-5 p-6 sm:p-10 bg-slate-50 border-t lg:border-t-0 lg:border-l border-slate-100 flex flex-col justify-center">
            
            <div id="priceDetailCard" class="detail-card bg-white rounded-3xl border border-violet-100 p-8 shadow-lg shadow-violet-100/30 flex flex-col justify-between min-h-[460px] relative">
                
                <!-- Popularity Tag Badge -->
                <div id="populerBadge" class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 bg-amber-400 text-purple-950 text-xxs font-black uppercase tracking-wider rounded-full shadow-md d-none">
                    Paling Populer
                </div>

                <div>
                    <!-- Category Badge -->
                    <div class="mb-4">
                        <span id="detailKategori" class="px-3 py-1 bg-violet-50 text-violet-700 text-xs font-bold uppercase rounded-full">Dasar</span>
                    </div>

                    <!-- Package Title -->
                    <h3 id="detailNama" class="text-2xl font-bold text-violet-950 mb-2">SD &amp; SMP</h3>
                    <!-- Package Description -->
                    <p id="detailDeskripsi" class="text-slate-500 text-xs mb-6">Memperkuat konsep matematika dasar sekolah dasar dan menengah.</p>
                    
                    <!-- Pricing range -->
                    <div class="flex items-baseline gap-1 mb-6">
                        <span id="detailHargaMin" class="text-3xl sm:text-4xl font-black text-violet-950">50K</span>
                        <span id="detailHargaMax" class="text-slate-500 text-sm font-semibold">- 80K</span>
                        <span class="text-slate-400 text-xs font-semibold ml-1">/ sesi / org</span>
                    </div>

                    <div class="border-t border-dashed border-slate-100 my-4"></div>

                    <!-- Features -->
                    <h6 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Fasilitas &amp; Tarif Rincian:</h6>
                    <ul id="detailList" class="space-y-3 text-sm text-slate-600 mb-2">
                        <!-- Populated dynamically via JS -->
                    </ul>
                </div>

                <!-- Accent Note -->
                <div class="mt-6 p-3 bg-violet-50/50 rounded-2xl border border-violet-100/50 flex items-start gap-2.5">
                    <i class="fas fa-shield-alt text-violet-600 mt-0.5 text-xs"></i>
                    <p class="text-[10px] text-violet-950 mb-0 leading-relaxed font-medium">
                        Tarif akhir akan menyesuaikan jumlah peserta kelompok dan jumlah sesi yang Anda setujui bersama Tutor.
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
                    // Style as premium violet
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
                            <li class="flex items-start gap-2.5">
                                <span class="shrink-0 mt-0.5"><i class="fas ${icon}"></i></span>
                                <span>${det}</span>
                            </li>
                        `;
                    }
                });
                detailList.innerHTML = listHtml;
            }

            // Listen to dropdown changes
            select.addEventListener('change', function () {
                updatePreview(this.value);
            });

            // Initial render
            if (select.value) {
                updatePreview(select.value);
            }
        });
    </script>
</body>
</html>
