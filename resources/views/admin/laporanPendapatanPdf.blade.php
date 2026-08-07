<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan · Paradise of Math</title>
    <style>
        @page {
            margin: 25px 30px 35px 30px;
        }
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.4;
        }
        /* HEADER / KOP */
        .kop-container {
            width: 100%;
            border-bottom: 2px solid #6b21a8;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }

        .brand-title {
            font-size: 20px;
            font-weight: bold;
            color: #581c87;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .brand-subtitle {
            font-size: 13px;
            font-weight: bold;
            color: #7e22ce;
            margin-top: 2px;
        }

        .meta-box {
            text-align: right;
            font-size: 10px;
            color: #64748b;
        }

        .meta-badge {
            display: inline-block;
            background-color: #f3e8ff;
            color: #6b21a8;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 3px;
        }

        /* CARDS / RINGKASAN (Table-based for DomPDF compatibility) */
        .cards-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .cards-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .card {
            background-color: #faf5ff;
            border: 1px solid #e9d5ff;
            border-radius: 6px;
            padding: 10px 12px;
            text-align: center;
        }

        .card-label {
            font-size: 9px;
            font-weight: bold;
            color: #6b21a8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .card-value {
            font-size: 15px;
            font-weight: bold;
            color: #1e293b;
        }

        /* TABLES */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #581c87;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        table.data-table th {
            background-color: #6b21a8;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 7px 8px;
            border: 1px solid #581c87;
        }

        table.data-table td {
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            font-size: 10px;
        }

        table.data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        table.data-table tfoot td {
            background-color: #f3e8ff;
            font-weight: bold;
            border-top: 2px solid #6b21a8;
            color: #4c1d95;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* FOOTER */
        .footer-line {
            margin-top: 25px;
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="kop-container">
        <table class="kop-table">
            <tr>
                <td>
                    <div class="brand-title">PARADISE OF MATH</div>
                    <div class="brand-subtitle">Laporan Pendapatan</div>
                </td>
                <td class="meta-box">
                    <span class="meta-badge">Periode: {{ ucfirst($filter ?? 'Bulanan') }}</span>
                    @if(!empty($start) && !empty($end))
                        <br><span>{{ \Carbon\Carbon::parse($start)->format('d M Y') }} -
                            {{ \Carbon\Carbon::parse($end)->format('d M Y') }}</span>
                    @elseif(!empty($year))
                        <br><span>Tahun {{ $year }}</span>
                    @endif
                    <br><span>Dicetak: {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}</span>
                </td>
            </tr>
        </table>
    </div>

    {{-- DATA CALCULATION --}}
    @php
        $total = $totalRevenue ?? collect($rows)->sum('total');
        $count = $paymentCount ?? count($rows);
        $avg = $avgPerTransaction ?? ($count > 0 ? $total / $count : 0);

        $packageTotals = $packageTotals ?? [];
        if (empty($packageTotals) && !empty($rows)) {
            $packageTotals = collect($rows)->groupBy('paket')->map(function ($group) {
                return [
                    'revenue' => $group->sum('total'),
                    'count' => $group->count()
                ];
            })->toArray();
        }
    @endphp

    {{-- STAT CARDS (Table-based horizontal layout) --}}
    <table class="cards-table">
        <tr>
            <td width="32%">
                <div class="card">
                    <div class="card-label">Total Pendapatan</div>
                    <div class="card-value">Rp {{ number_format($total, 0, ',', '.') }}</div>
                </div>
            </td>
            <td width="2%"></td>
            <td width="32%">
                <div class="card">
                    <div class="card-label">Transaksi Lunas</div>
                    <div class="card-value">{{ $count }} Transaksi</div>
                </div>
            </td>
            <td width="2%"></td>
            <td width="32%">
                <div class="card">
                    <div class="card-label">Rata-rata / Transaksi</div>
                    <div class="card-value">Rp {{ number_format($avg, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- MAIN TABLE --}}
    <div class="section-title">Detail Pendapatan Siswa</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">#</th>
                <th style="width: 22%;">Nama Siswa</th>
                <th style="width: 24%;">Email</th>
                <th style="width: 15%;">Paket</th>
                <th style="width: 10%;" class="text-center">Pertemuan</th>
                <th style="width: 13%;" class="text-right">Total</th>
                <th style="width: 12%;" class="text-center">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $index => $r)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $r['name'] ?? '-' }}</td>
                    <td>{{ $r['email'] ?? '-' }}</td>
                    <td>{{ $r['paket'] ?? '-' }}</td>
                    <td class="text-center">{{ $r['jumlah_pertemuan'] ?? 0 }}</td>
                    <td class="text-right">Rp {{ number_format($r['total'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-center">
                        {{ $r['tanggal'] ? \Carbon\Carbon::parse($r['tanggal'])->format('d/m/Y H:i') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 15px; color: #64748b;">Tidak ada data pembayaran.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($rows) > 0)
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right">TOTAL KESELURUHAN</td>
                    <td class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>

    {{-- RINGKASAN PER PAKET --}}
    @if(!empty($packageTotals))
        <div style="margin-top: 15px;">
            <div class="section-title">Ringkasan Pendapatan per Paket</div>
            <table class="data-table" style="width: 55%;">
                <thead>
                    <tr>
                        <th class="text-left" style="width: 50%;">Nama Paket</th>
                        <th class="text-center" style="width: 25%;">Jumlah</th>
                        <th class="text-right" style="width: 25%;">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packageTotals as $pname => $pval)
                        <tr>
                            <td>{{ $pname }}</td>
                            <td class="text-center">{{ $pval['count'] }}</td>
                            <td class="text-right">Rp {{ number_format($pval['revenue'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    {{-- FOOTER --}}
    <div class="footer-line">
        Laporan ini secara otomatis di-generate oleh Sistem Informasi Paradise of Math.
    </div>
    {{-- DomPDF Script for Page Numbering --}}
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            $font = $fontMetrics->get_font("DejaVu Sans", "normal");
            $size = 8;
            $color = array(0.5, 0.5, 0.5);
            $y = $pdf->get_height() - 20;
            $x = $pdf->get_width() - 110;
            $pdf->page_text($x, $y, $text, $font, $size, $color);
        }
    </script>
</body>

</html>