<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Paket Belajar - Paradise of Math</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #faf9fc; }
        .gradient-bg {
            background: linear-gradient(135deg, #1e293b 0%, #be123c 100%);
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 pb-16">

    <!-- Header bar -->
    <div class="gradient-bg py-8 px-6 text-white shadow-md relative overflow-hidden mb-10">
        <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-rose-600/20 blur-xl"></div>
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
            <div>
                <span class="px-3 py-1 bg-rose-500 text-white text-xs font-bold uppercase tracking-wider rounded-full shadow-md">
                    Manajemen Harga
                </span>
                <h1 class="text-3xl font-serif mt-2">Kelola Paket &amp; Tarif Belajar</h1>
                <p class="text-rose-200 text-sm mt-1">Ubah data harga, deskripsi, dan rincian paket belajar PoM secara realtime.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="/admin" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white text-sm font-bold rounded-xl transition duration-200 border border-white/20">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if (session('success'))
            <div class="mb-8 p-4 rounded-xl text-sm font-medium border bg-emerald-50 border-emerald-200 text-emerald-800 shadow-sm" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($packages as $paket)
            <div class="bg-white rounded-3xl border border-slate-100 shadow-lg shadow-slate-100/40 p-6 flex flex-col justify-between hover:shadow-xl transition-all duration-300 relative {{ $paket->is_populer ? 'ring-2 ring-amber-400' : '' }}">
                @if($paket->is_populer)
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-0.5 bg-amber-400 text-purple-950 text-[10px] font-black uppercase tracking-wider rounded-full shadow-sm">
                    Terpopuler (Highlighted)
                </div>
                @endif

                <form action="{{ route('admin.paket.update', $paket->id) }}" method="POST" class="space-y-4 flex flex-col justify-between h-full">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <!-- Paket Title / Category -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Paket &amp; Kategori</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" name="nama_paket" value="{{ $paket->nama_paket }}" class="w-full text-sm font-bold text-slate-900 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 outline-none" required />
                                <input type="text" name="kategori" value="{{ $paket->kategori }}" class="w-full text-xs font-bold text-slate-600 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 outline-none" required />
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Deskripsi Paket</label>
                            <textarea name="deskripsi" class="w-full text-xs text-slate-600 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 outline-none h-16 resize-none" required>{{ $paket->deskripsi }}</textarea>
                        </div>

                        <!-- Price Ranges -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Rentang Harga (Rupiah)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <span class="text-[10px] text-slate-400 font-semibold">Min</span>
                                    <input type="number" name="harga_min" value="{{ $paket->harga_min }}" class="w-full text-sm font-semibold text-slate-800 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 outline-none" required />
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-400 font-semibold">Max</span>
                                    <input type="number" name="harga_max" value="{{ $paket->harga_max }}" class="w-full text-sm font-semibold text-slate-800 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 outline-none" required />
                                </div>
                            </div>
                        </div>

                        <!-- Details 1 - 5 -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Detail Baris Rincian</label>
                            <input type="text" name="detail_1" value="{{ $paket->detail_1 }}" placeholder="Rincian 1" class="w-full text-xs text-slate-600 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:border-rose-500 outline-none" />
                            <input type="text" name="detail_2" value="{{ $paket->detail_2 }}" placeholder="Rincian 2" class="w-full text-xs text-slate-600 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:border-rose-500 outline-none" />
                            <input type="text" name="detail_3" value="{{ $paket->detail_3 }}" placeholder="Rincian 3" class="w-full text-xs text-slate-600 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:border-rose-500 outline-none" />
                            <input type="text" name="detail_4" value="{{ $paket->detail_4 }}" placeholder="Rincian 4" class="w-full text-xs text-slate-600 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:border-rose-500 outline-none" />
                            <input type="text" name="detail_5" value="{{ $paket->detail_5 }}" placeholder="Rincian 5" class="w-full text-xs text-slate-600 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:border-rose-500 outline-none" />
                        </div>

                        <!-- Toggle Popularity -->
                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" name="is_populer" value="1" id="populer-{{ $paket->id }}" class="w-4 h-4 text-rose-600 border-gray-300 rounded focus:ring-rose-500 cursor-pointer" {{ $paket->is_populer ? 'checked' : '' }}>
                            <label for="populer-{{ $paket->id }}" class="text-xs font-bold text-slate-700 cursor-pointer select-none">Tandai Paling Populer (Warna Ungu)</label>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-5 py-2.5 bg-rose-500 text-white font-bold rounded-xl hover:bg-rose-600 transition duration-200 shadow-md shadow-rose-500/10">
                        Update Data Paket
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>

</body>
</html>
