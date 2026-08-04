@extends('layout.app')

@section('title', 'Kuitansi & Invoice Pembayaran · Paradise of Math')

@section('content')
<div class="content-header no-print">
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-purple-950">Invoice Pembayaran</h1>
                <p class="text-sm text-muted mb-0">Tinjau dan cetak bukti pembayaran bimbingan belajar Anda.</p>
            </div>
            <div class="col-sm-6 text-sm-right mt-2 mt-sm-0">
                <button onclick="window.print()" class="btn btn-sm btn-primary rounded-lg font-weight-bold px-3 mr-2" style="background-color: #7c3aed; border-color: #7c3aed;">
                    <i class="fas fa-print mr-1.5"></i> Cetak / Simpan PDF
                </button>
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-sm btn-light border rounded-lg font-weight-bold text-purple-950 px-3">
                    <i class="fas fa-arrow-left mr-1.5"></i> Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
        <!-- Accounting Sheet Container -->
        <div class="invoice-container bg-white shadow-sm border p-4 p-md-5 mx-auto rounded-2xl" style="max-width: 900px; font-family: 'Arial', 'Helvetica Neue', Helvetica, sans-serif; color: #000; border-radius: 16px;">
            
            <!-- Top Invoice Header -->
            <div class="row mb-4 align-items-center">
                <!-- Logo Left -->
                <div class="col-3 text-left">
                    <div class="d-flex align-items-center">
                        <!-- Stylized PM Logo mimicking the hand-drawn font in the image -->
                        <div style="font-family: 'Impact', 'Arial Black', sans-serif; font-size: 48px; color: #ea580c; line-height: 1; letter-spacing: -2px; text-shadow: 2px 2px 0px #000, -1px -1px 0px #000, 1px -1px 0px #000, -1px 1px 0px #000;">
                            <img src="{{ asset('images/logoPM.webp') }}" alt="" style="height: 50px; object-fit: cover;">
                        </div>
                    </div>
                </div>
                
                <!-- Center Brand Text -->
                <div class="col-6 text-center">
                    <h5 class="font-weight-bold mb-0" style="color: #b91c1c; font-size: 16px; font-family: 'Arial', sans-serif; letter-spacing: 0.5px;">PARADISE OF MATH</h5>
                    <h6 class="font-weight-bold mb-0" style="font-size: 10px; font-family: 'Arial', sans-serif;">PUSAT BIMBINGAN BELAJAR DAN PRIVAT</h6>
                    <h6 class="font-weight-bold mb-1" style="font-size: 9px; font-family: 'Arial', sans-serif;">SD, SMP, SMA, SBMPTN</h6>
                    <p class="mb-0" style="font-size: 8px; line-height: 1.2;">Jln. Jati 1 No. 19, Padang Telp. (0751) 812050</p>
                    <p class="mb-0 text-muted" style="font-size: 7.5px; line-height: 1.2;">Hp. 08126762341 (Owner), 08116612050 (Pimpinan-K' Ika), 082386720060 (K' Angel)</p>
                </div>

                <!-- Right Student Header -->
                <div class="col-3">
                    <table class="w-100 text-left text-xs table-bordered" style="border: 1px solid #cbd5e1; font-size: 10px;">
                        <tr>
                            <td class="p-1 text-muted" style="border: 1px solid #cbd5e1; font-size: 9px;">NAMA :</td>
                            <td class="p-1 font-weight-bold text-uppercase" style="border: 1px solid #cbd5e1;">{{ $siswa->name }}</td>
                        </tr>
                        <tr>
                            <td class="p-1 text-muted" style="border: 1px solid #cbd5e1; font-size: 9px;">KLS/SKLH:</td>
                            <td class="p-1 font-weight-bold text-uppercase" style="border: 1px solid #cbd5e1;">{{ $siswa->biodata['kelas'] ?? 'Siswa' }}</td>
                        </tr>
                        <tr>
                            <td class="p-1 text-muted" style="border: 1px solid #cbd5e1; font-size: 9px;">PERIODE :</td>
                            <td class="p-1 font-weight-bold text-uppercase" style="border: 1px solid #cbd5e1;">
                                {{ $tanggalMulai ? strtoupper(date('M\'y', strtotime($tanggalMulai))) : strtoupper(date('M\'y')) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Ledger Table -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered text-center mb-0" style="border: 1.5px solid #000; font-size: 13px; font-weight: normal;">
                    <thead>
                        <tr style="border-bottom: 1.5px solid #000; background-color: #fafafa;">
                            <th class="py-2" style="border: 1px solid #000; color: #b91c1c; font-weight: bold; width: 18%;">STUDY</th>
                            <th class="py-2" style="border: 1px solid #000; color: #b91c1c; font-weight: bold; width: 18%;">GURU</th>
                            <th class="py-2" style="border: 1px solid #000; color: #b91c1c; font-weight: bold; width: 24%;" colspan="4">HARI</th>
                            <th class="py-2" style="border: 1px solid #000; color: #b91c1c; font-weight: bold; width: 10%;">KODE</th>
                            <th class="py-2" style="border: 1px solid #000; color: #b91c1c; font-weight: bold; width: 8%;">SHIFT</th>
                            <th class="py-2" style="border: 1px solid #000; color: #b91c1c; font-weight: bold; width: 11%;">@ RP</th>
                            <th class="py-2" style="border: 1px solid #000; color: #b91c1c; font-weight: bold; width: 11%;">JUMLAH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalSesi = $jumlahPertemuan ?: 9;
                            $listMapels = count($mapels) > 0 ? $mapels : ['MATH'];
                            $listGurus = count($gurus) > 0 ? $gurus : ['YULIA'];
                            $numMapels = count($listMapels);
                            
                            // Distribute sessions among mapels
                            $sessionsPerMapel = [];
                            $baseSesi = floor($totalSesi / $numMapels);
                            $remainder = $totalSesi % $numMapels;
                            for ($i = 0; $i < $numMapels; $i++) {
                                $sessionsPerMapel[$i] = $baseSesi + ($i < $remainder ? 1 : 0);
                            }
                            
                            // Map day strings to checkbox cells (e.g. SB, SL, RB, etc.)
                            $daysFormatted = '';
                            if (!empty($hariPertemuan)) {
                                $daysFormatted = implode(' & ', array_map(function($d) {
                                    return strtoupper(substr($d, 0, 2));
                                }, $hariPertemuan));
                            } else {
                                $daysFormatted = 'SB'; // Default Sabtu
                            }
                        @endphp

                        @for($i = 0; $i < $numMapels; $i++)
                            @php
                                $mapelName = strtoupper($listMapels[$i]);
                                // Strip subjects prefix from guru names
                                $guruName = isset($listGurus[$i]) ? preg_replace('/^(math|english|ipa|ips):\s*/i', '', $listGurus[$i]) : (isset($listGurus[0]) ? preg_replace('/^(math|english|ipa|ips):\s*/i', '', $listGurus[0]) : 'TUTOR');
                                $guruName = strtoupper($guruName);
                                $sesiCount = $sessionsPerMapel[$i];
                                $subtotal = $hargaPerSesi * $sesiCount;
                            @endphp
                            <tr>
                                <td class="py-2 font-weight-bold" style="border: 1px solid #000;">{{ $mapelName }}</td>
                                <td class="py-2" style="border: 1px solid #000;">{{ $guruName }}</td>
                                <td class="py-2" style="border: 1px solid #000; width: 6%;">{{ $daysFormatted }}</td>
                                <td class="py-2" style="border: 1px solid #000; width: 6%;"></td>
                                <td class="py-2" style="border: 1px solid #000; width: 6%;"></td>
                                <td class="py-2" style="border: 1px solid #000; width: 6%;"></td>
                                <td class="py-2 font-weight-bold" style="border: 1px solid #000;">S</td>
                                <td class="py-2" style="border: 1px solid #000;">{{ $sesiCount }}</td>
                                <td class="py-2 text-right pr-2" style="border: 1px solid #000;">{{ number_format($hargaPerSesi) }}</td>
                                <td class="py-2 text-right pr-2" style="border: 1px solid #000;">{{ number_format($subtotal) }}</td>
                            </tr>
                        @endfor

                        <!-- Pad remaining blank rows like standard ledger sheets -->
                        @for($j = $numMapels; $j < 5; $j++)
                            <tr>
                                <td class="py-2" style="border: 1px solid #000; height: 32px;"></td>
                                <td class="py-2" style="border: 1px solid #000;"></td>
                                <td class="py-2" style="border: 1px solid #000;"></td>
                                <td class="py-2" style="border: 1px solid #000;"></td>
                                <td class="py-2" style="border: 1px solid #000;"></td>
                                <td class="py-2" style="border: 1px solid #000;"></td>
                                <td class="py-2" style="border: 1px solid #000;"></td>
                                <td class="py-2" style="border: 1px solid #000;"></td>
                                <td class="py-2" style="border: 1px solid #000;"></td>
                                <td class="py-2" style="border: 1px solid #000;"></td>
                            </tr>
                        @endfor

                        <!-- Grand Total Row -->
                        <tr style="border-top: 1.5px solid #000;">
                            <td colspan="7" class="border-0"></td>
                            <td colspan="2" class="py-2 font-weight-bold text-center" style="border: 1.5px solid #000; color: #1d4ed8;">TOTAL</td>
                            <td class="py-2 text-right pr-2 font-weight-bold" style="border: 1.5px solid #000; color: #1d4ed8; font-size: 12px;">
                                {{ number_format($totalHarga) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Notes & Receipt Verification Blocks -->
            <div class="row pt-2 align-items-end">
                <!-- Notes Left -->
                <div class="col-md-7 col-12 mb-4 mb-md-0">
                    <div class="p-3" style="border: 1.5px solid #000; border-radius: 8px; font-size: 9px; line-height: 1.4; position: relative;">
                        <!-- Scroll effect top header -->
                        <div class="font-weight-bold text-decoration-underline mb-2" style="color: #b91c1c;">CATATAN:</div>
                        <ul class="pl-3 mb-0" style="list-style-type: disc;">
                            <li>Uang yang telah disetor, tidak dapat ditarik kembali, dengan alasan apapun.</li>
                            <li>Biaya bulanan, dibayar paling lambat tanggal 10.</li>
                            <li>Pembatalan privat, paling lambat 5 jam sebelum les.</li>
                            <li>Hubungi K' Ika / K' Angel / Admin / Guru masing-masing.</li>
                        </ul>
                    </div>
                </div>

                <!-- Receipt Stamp Block Right -->
                <div class="col-md-5 col-12 text-right">
                    <div class="d-inline-flex flex-column align-items-end">
                        
                        <!-- Bank Logo & Paid Stamp -->
                        <div class="d-flex align-items-center justify-content-end mb-3 mr-2">
                            <!-- Mandiri Logo layout -->
                            <div class="d-flex flex-column align-items-start mr-3 text-left">
                                <div style="font-family: 'Arial Black', sans-serif; font-size: 14px; font-style: italic; color: #1e3a8a; line-height: 1; letter-spacing: -1px;">
                                    mandırı
                                </div>
                                <div style="width: 35px; height: 3px; background-color: #fbbf24; border-radius: 2px; margin-top: 1px;"></div>
                            </div>
                            
                            <!-- Large Amount & Date Box -->
                            <div class="d-flex flex-column align-items-end">
                                <!-- Price Box -->
                                <div class="px-3 py-1.5 text-center font-weight-bold" style="border: 2px solid #000; background-color: #fff; font-size: 16px; color: #e11d48; width: 140px;">
                                    {{ number_format($totalHarga) }}
                                </div>
                                <!-- Date Box -->
                                <div class="px-3 py-0.5 text-center font-weight-bold" style="border: 2px solid #000; border-top: 0; background-color: #fff; font-size: 10px; width: 140px;">
                                    {{ $tanggalMulai ? date('j-m-y', strtotime($tanggalMulai)) : date('j-m-y') }}
                                </div>
                            </div>
                        </div>

                        <!-- Receiver Sign -->
                        <div class="text-right pr-2" style="font-size: 9px; font-family: 'Arial', sans-serif; font-weight: bold;">
                            PENERIMA : <span class="text-uppercase">ADMIN PM</span>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Custom styling for print support and ledger grid styling -->
<style>
    @media print {
        /* Hide navbar, sidebar, footer, back buttons, and headers during printing */
        .main-header, .main-sidebar, .main-footer, .no-print, .breadcrumb, .btn {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }
        body {
            background-color: #ffffff !important;
        }
        .invoice-container {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 auto !important;
            width: 100% !important;
            max-width: 100% !important;
        }
    }
</style>
@endsection
