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
            <div class="row mb-4 align-items-center invoice-header-row">
                <!-- Logo Left -->
                <div class="col-12 col-md-3 text-center text-md-left mb-3 mb-md-0 logo-col">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <img src="{{ asset('images/logoPM.webp') }}" alt="Logo PM" style="height: 52px; object-fit: contain;">
                    </div>
                </div>
                
                <!-- Center Brand Text -->
                <div class="col-12 col-md-6 text-center mb-3 mb-md-0 title-col">
                    <h5 class="font-weight-bold mb-0 brand-title" style="color: #b91c1c; font-size: 16px; font-family: 'Arial', sans-serif; letter-spacing: 0.5px;">PARADISE OF MATH</h5>
                    <h6 class="font-weight-bold mb-0 subtitle-1" style="font-size: 10px; font-family: 'Arial', sans-serif;">PUSAT BIMBINGAN BELAJAR DAN PRIVAT</h6>
                    <h6 class="font-weight-bold mb-1 subtitle-2" style="font-size: 9px; font-family: 'Arial', sans-serif;">SD, SMP, SMA, SBMPTN</h6>
                    <p class="mb-0 address-text" style="font-size: 8px; line-height: 1.2;">Jln. Jati 1 No. 19, Padang Telp. (0751) 812050</p>
                    <p class="mb-0 text-muted phone-text" style="font-size: 7.5px; line-height: 1.2;">Hp. 08126762341 (Owner), 08116612050 (Pimpinan-K' Ika), 082386720060 (K' Angel)</p>
                </div>

                <!-- Right Student Header -->
                <div class="col-12 col-md-3 student-info-col">
                    <table class="w-100 text-left text-xs table-bordered student-info-table" style="border: 1px solid #cbd5e1; font-size: 10px;">
                        <tr>
                            <td class="p-1 text-muted" style="border: 1px solid #cbd5e1; font-size: 9px; width: 40%;">NAMA :</td>
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

            <!-- Ledger Table Container -->
            <div class="table-responsive mb-4 shadow-2xs rounded-lg">
                <table class="table table-bordered text-center mb-0 ledger-table" style="border: 1.5px solid #000; font-size: 12px; font-weight: normal; min-width: 580px;">
                    <thead>
                        <tr style="border-bottom: 1.5px solid #000; background-color: #fafafa;">
                            <th class="py-2" style="border: 1px solid #000; color: #b91c1c; font-weight: bold; width: 18%;">STUDY</th>
                            <th class="py-2" style="border: 1px solid #000; color: #b91c1c; font-weight: bold; width: 18%;">GURU</th>
                            <th class="py-2" style="border: 1px solid #000; color: #b91c1c; font-weight: bold; width: 22%;">HARI</th>
                            <th class="py-2" style="border: 1px solid #000; color: #b91c1c; font-weight: bold; width: 10%;">KODE</th>
                            <th class="py-2" style="border: 1px solid #000; color: #b91c1c; font-weight: bold; width: 10%;">SHIFT</th>
                            <th class="py-2 text-right pr-2" style="border: 1px solid #000; color: #b91c1c; font-weight: bold; width: 11%;">@ RP</th>
                            <th class="py-2 text-right pr-2" style="border: 1px solid #000; color: #b91c1c; font-weight: bold; width: 11%;">JUMLAH</th>
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
                        @endphp

                        @for($i = 0; $i < $numMapels; $i++)
                            @php
                                $mapelName = strtoupper($listMapels[$i]);
                                // Strip subjects prefix from guru names
                                $guruName = isset($listGurus[$i]) ? preg_replace('/^(math|english|ipa|ips):\s*/i', '', $listGurus[$i]) : (isset($listGurus[0]) ? preg_replace('/^(math|english|ipa|ips):\s*/i', '', $listGurus[0]) : 'TUTOR');
                                $guruName = strtoupper($guruName);
                                $sesiCount = $sessionsPerMapel[$i];
                                $subtotal = $hargaPerSesi * $sesiCount;

                                // Format hari khusus mapel ini jika ada per-mapel data
                                $daysFormatted = '';
                                if (!empty($hariPerMapel[$i])) {
                                    $daysFormatted = implode(' & ', array_map(function($d) {
                                        return strtoupper(substr(trim($d), 0, 3));
                                    }, array_filter($hariPerMapel[$i])));
                                } elseif (!empty($hariPertemuan)) {
                                    $daysFormatted = implode(' & ', array_map(function($d) {
                                        return strtoupper(substr(trim($d), 0, 3));
                                    }, $hariPertemuan));
                                } else {
                                    $daysFormatted = 'SEN & RAB';
                                }
                            @endphp
                            <tr>
                                <td class="py-2 font-weight-bold align-middle" style="border: 1px solid #000;">{{ $mapelName }}</td>
                                <td class="py-2 align-middle" style="border: 1px solid #000;">{{ $guruName }}</td>
                                <td class="py-2 align-middle font-weight-bold text-slate-800" style="border: 1px solid #000; white-space: nowrap;">{{ $daysFormatted }}</td>
                                <td class="py-2 font-weight-bold align-middle" style="border: 1px solid #000;">S</td>
                                <td class="py-2 align-middle" style="border: 1px solid #000;">{{ $sesiCount }}</td>
                                <td class="py-2 text-right pr-2 align-middle" style="border: 1px solid #000; white-space: nowrap;">{{ number_format($hargaPerSesi) }}</td>
                                <td class="py-2 text-right pr-2 align-middle font-weight-semibold" style="border: 1px solid #000; white-space: nowrap;">{{ number_format($subtotal) }}</td>
                            </tr>
                        @endfor

                        <!-- Pad remaining blank rows like standard ledger sheets -->
                        @for($j = $numMapels; $j < 4; $j++)
                            <tr>
                                <td class="py-2" style="border: 1px solid #000; height: 32px;"></td>
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
                            <td colspan="4" class="border-0"></td>
                            <td colspan="2" class="py-2 font-weight-bold text-center" style="border: 1.5px solid #000; color: #1d4ed8;">TOTAL</td>
                            <td class="py-2 text-right pr-2 font-weight-bold" style="border: 1.5px solid #000; color: #1d4ed8; font-size: 13px; white-space: nowrap;">
                                {{ number_format($totalHarga) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Notes & Receipt Verification Blocks -->
            <div class="row pt-2 align-items-end flex-column-reverse flex-md-row invoice-footer-row">
                <!-- Notes Left -->
                <div class="col-md-7 col-12 mb-4 mb-md-0 notes-col">
                    <div class="p-3 bg-rose-50/30" style="border: 1.5px solid #000; border-radius: 8px; font-size: 9.5px; line-height: 1.5; position: relative;">
                        <div class="font-weight-bold text-decoration-underline mb-1.5" style="color: #b91c1c;">CATATAN:</div>
                        <ul class="pl-3 mb-0 text-slate-800" style="list-style-type: disc;">
                            <li>Uang yang telah disetor, tidak dapat ditarik kembali, dengan alasan apapun.</li>
                            <li>Biaya bulanan, dibayar paling lambat tanggal 10 setiap bulannya.</li>
                            <li>Pembatalan privat, paling lambat 5 jam sebelum jadwal les dimulai.</li>
                            <li>Hubungi K' Ika / K' Angel / Admin / Guru masing-masing untuk info lebih lanjut.</li>
                        </ul>
                    </div>
                </div>

                <!-- Receipt Stamp Block Right -->
                <div class="col-md-5 col-12 text-center text-md-right mb-4 mb-md-0 stamp-col">
                    <div class="d-inline-flex flex-column align-items-center align-items-md-end">
                        
                        <!-- Bank Logo & Paid Stamp -->
                        <div class="d-flex align-items-center justify-content-center justify-content-md-end mb-3">
                            <!-- Mandiri Logo layout -->
                            <div class="d-flex flex-column align-items-start mr-3 text-left">
                                <div style="font-family: 'Arial Black', sans-serif; font-size: 14px; font-style: italic; color: #1e3a8a; line-height: 1; letter-spacing: -1px;">
                                    mandiri
                                </div>
                                <div style="width: 35px; height: 3px; background-color: #fbbf24; border-radius: 2px; margin-top: 1px;"></div>
                            </div>
                            
                            <!-- Large Amount & Date Box -->
                            <div class="d-flex flex-column align-items-end">
                                <!-- Price Box -->
                                <div class="px-3 py-1.5 text-center font-weight-bold shadow-xs" style="border: 2px solid #000; background-color: #fff; font-size: 16px; color: #e11d48; min-width: 140px;">
                                    {{ number_format($totalHarga) }}
                                </div>
                                <!-- Date Box -->
                                <div class="px-3 py-0.5 text-center font-weight-bold" style="border: 2px solid #000; border-top: 0; background-color: #fff; font-size: 10px; min-width: 140px;">
                                    {{ $tanggalMulai ? date('j-m-y', strtotime($tanggalMulai)) : date('j-m-y') }}
                                </div>
                            </div>
                        </div>

                        <!-- Receiver Sign -->
                        <div class="text-center text-md-right pr-md-2" style="font-size: 9.5px; font-family: 'Arial', sans-serif; font-weight: bold;">
                            PENERIMA : <span class="text-uppercase text-purple-950">ADMIN PM</span>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Custom styling for print support and responsive mobile ledger layout -->
<style>
    /* Responsive screen styling (Mobile & Tablet) */
    @media (max-width: 767.98px) {
        .invoice-container {
            padding: 1.25rem !important;
            border-radius: 16px !important;
        }
        .invoice-header-row .logo-col img {
            height: 44px !important;
        }
        .invoice-header-row .brand-title {
            font-size: 15px !important;
        }
        .invoice-header-row .student-info-table {
            font-size: 10px !important;
        }
        .ledger-table {
            font-size: 11px !important;
        }
        .ledger-table th, .ledger-table td {
            padding: 6px 8px !important;
        }
    }

    /* Print view styling (Preserves physical paper print design & hides app shell) */
    @media print {
        @page {
            size: A4 portrait;
            margin: 12mm 10mm;
        }
        
        /* Hide all outer app shell elements (navbar, sidebar, footer, bottom navigation) */
        body * {
            visibility: hidden !important;
        }
        
        /* Show only invoice-container and its contents */
        .invoice-container, .invoice-container * {
            visibility: visible !important;
        }
        
        .invoice-container {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            background: #ffffff !important;
        }

        /* Force 3-column header row in print view */
        .invoice-header-row {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            align-items: center !important;
            justify-content: space-between !important;
            margin-bottom: 18px !important;
            width: 100% !important;
        }
        
        .logo-col {
            flex: 0 0 20% !important;
            max-width: 20% !important;
            text-align: left !important;
            margin-bottom: 0 !important;
        }

        .logo-col img {
            height: 50px !important;
        }

        .title-col {
            flex: 0 0 55% !important;
            max-width: 55% !important;
            text-align: center !important;
            margin-bottom: 0 !important;
        }

        .title-col .brand-title {
            font-size: 16px !important;
            color: #b91c1c !important;
        }

        .student-info-col {
            flex: 0 0 25% !important;
            max-width: 25% !important;
            margin-bottom: 0 !important;
        }

        .student-info-table {
            font-size: 9.5px !important;
            width: 100% !important;
        }

        /* Table styles for print view */
        .table-responsive {
            overflow: visible !important;
        }

        .ledger-table {
            width: 100% !important;
            min-width: 100% !important;
            font-size: 11px !important;
            border: 1.5px solid #000 !important;
        }

        .ledger-table th, .ledger-table td {
            padding: 4px 6px !important;
            border: 1px solid #000 !important;
            font-size: 11px !important;
        }

        /* Force 2-column footer row (Catatan & Stempel) in print view */
        .invoice-footer-row {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            align-items: flex-end !important;
            justify-content: space-between !important;
            width: 100% !important;
            margin-top: 15px !important;
        }

        .notes-col {
            flex: 0 0 55% !important;
            max-width: 55% !important;
            margin-bottom: 0 !important;
            text-align: left !important;
        }

        .stamp-col {
            flex: 0 0 40% !important;
            max-width: 40% !important;
            margin-bottom: 0 !important;
            text-align: right !important;
        }

        .stamp-col .d-inline-flex {
            align-items: flex-end !important;
        }

        .stamp-col .text-center {
            text-align: right !important;
        }
    }
</style>
@endsection
