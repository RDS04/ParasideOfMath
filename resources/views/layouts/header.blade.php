<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Kategori Belajar · Paradise of Math</title>
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
            0% {
                opacity: 0;
                transform: translateY(8px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center p-4 sm:p-8">

    <!-- ══════ STEP INDICATOR ══════ -->
    <div class="w-full max-w-7xl mb-8">
        <div class="flex items-center justify-between max-w-3xl mx-auto px-4">
            <!-- Step 1: Registrasi -->
            <div class="flex flex-col items-center">
                @if(($active_step ?? 3) == 1)
                <div class="w-9 h-9 rounded-full bg-purple-700 text-white flex items-center justify-center font-bold shadow-md ring-4 ring-purple-100">
                    1
                </div>
                @elseif(($active_step ?? 3) > 1)
                <div class="w-9 h-9 rounded-full bg-purple-700 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="3"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                @else
                <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center font-bold">
                    1
                </div>
                @endif
                <span class="text-[10px] sm:text-xs font-bold {{ ($active_step ?? 3) >= 1 ? 'text-purple-700' : 'text-slate-400' }} mt-2">Registrasi</span>
            </div>

            <div class="flex-1 h-1 {{ ($active_step ?? 3) > 1 ? 'bg-purple-700' : 'bg-slate-200' }} mx-2 mb-4"></div>

            <!-- Step 2: Isi Biodata -->
            <div class="flex flex-col items-center">
                @if(($active_step ?? 3) == 2)
                <div class="w-9 h-9 rounded-full bg-purple-700 text-white flex items-center justify-center font-bold shadow-md ring-4 ring-purple-100">
                    2
                </div>
                @elseif(($active_step ?? 3) > 2)
                <div class="w-9 h-9 rounded-full bg-purple-700 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="3"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                @else
                <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center font-bold">
                    2
                </div>
                @endif
                <span class="text-[10px] sm:text-xs font-bold {{ ($active_step ?? 3) >= 2 ? 'text-purple-700' : 'text-slate-400' }} mt-2">Isi Biodata</span>
            </div>

            <div class="flex-1 h-1 {{ ($active_step ?? 3) > 2 ? 'bg-purple-700' : 'bg-slate-200' }} mx-2 mb-4"></div>

            <!-- Step 3: Pilih Paket -->
            <div class="flex flex-col items-center">
                @if(($active_step ?? 3) == 3)
                <div class="w-9 h-9 rounded-full bg-purple-700 text-white flex items-center justify-center font-bold shadow-md ring-4 ring-purple-100">
                    3
                </div>
                @elseif(($active_step ?? 3) > 3)
                <div class="w-9 h-9 rounded-full bg-purple-700 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="3"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                @else
                <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center font-bold">
                    3
                </div>
                @endif
                <span class="text-[10px] sm:text-xs font-bold {{ ($active_step ?? 3) >= 3 ? 'text-purple-700' : 'text-slate-400' }} mt-2">Pilih Paket</span>
            </div>

            <div class="flex-1 h-1 {{ ($active_step ?? 3) > 3 ? 'bg-purple-700' : 'bg-slate-200' }} mx-2 mb-4"></div>

            <!-- Step 4: Pembayaran -->
            <div class="flex flex-col items-center">
                @if(($active_step ?? 3) == 4)
                <div class="w-9 h-9 rounded-full bg-purple-700 text-white flex items-center justify-center font-bold shadow-md ring-4 ring-purple-100">
                    4
                </div>
                @elseif(($active_step ?? 3) > 4)
                <div class="w-9 h-9 rounded-full bg-purple-700 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="3"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                @else
                <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center font-bold">
                    4
                </div>
                @endif
                <span class="text-[10px] sm:text-xs font-bold {{ ($active_step ?? 3) >= 4 ? 'text-purple-700' : 'text-slate-400' }} mt-2">Pembayaran</span>
            </div>

            <div class="flex-1 h-1 {{ ($active_step ?? 3) > 4 ? 'bg-purple-700' : 'bg-slate-200' }} mx-2 mb-4"></div>

            <!-- Step 5: Selesai -->
            <div class="flex flex-col items-center">
                @if(($active_step ?? 3) == 5)
                <div class="w-9 h-9 rounded-full bg-purple-700 text-white flex items-center justify-center font-bold shadow-md ring-4 ring-purple-100">
                    5
                </div>
                @elseif(($active_step ?? 3) > 5)
                <div class="w-9 h-9 rounded-full bg-purple-700 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="3"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                @else
                <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center font-bold">
                    5
                </div>
                @endif
                <span class="text-[10px] sm:text-xs font-bold {{ ($active_step ?? 3) >= 5 ? 'text-purple-700' : 'text-slate-400' }} mt-2">Selesai</span>
            </div>
        </div>
    </div>
    <div class="content-wrapper w-full flex justify-center">
        @yield('content')
    </div>
</body>

</html>