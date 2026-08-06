<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan · Paradise of Math</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #6a1b9a;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #4a148c;
            font-size: 24px;
        }
        .header h2 {
            margin: 5px 0 0;
            color: #6a1b9a;
            font-size: 18px;
            font-weight: normal;
        }
        .header .periode {
            font-size: 13px;
            color: #777;
            margin-top: 3px;
        }
        .ringkasan {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 10px;
        }
        .ringkasan .card {
            flex: 1;
            background: #f5f5f5;
            padding: 12px 10px;
            border-radius: 5px;
            text-align: center;
        }
        .ringkasan .card .label {
            font-size: 12px;
            color: #777;
            text-transform: uppercase;
        }
        .ringkasan .card .value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-top: 2px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 20px;
        }
        table th {
            background: #6a1b9a;
            color: white;
            padding: 8px 5px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table td {
            padding: 6px 5px;
            border: 1px solid #ddd;
        }
        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        table tfoot td {
            background: #e8e8e8;
            font-weight: bold;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .sub-title {
            font-size: 14px;
            font-weight: bold;
            color: #4a148c;
            margin: 20px 0 10px;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 11px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>Paradise of Math</h1>
        <h2>Laporan Pendapatan</h2>
        <div class="periode">
            Periode: {{ ucfirst($filter ?? 'bulanan') }}
            @if(!empty($start) && !empty($end))
                | {{ \Carbon\Carbon::parse($start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end)->format('d M Y') }}
            @endif
            <br>
            Dicetak: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}
        </div>
    </div>

    {{-- RINGKASAN --}}
    @php
        // Hitung dari data jika tidak diberikan
        $total = $totalRevenue ?? collect($rows)->sum('total');
        $count = $paymentCount ?? count($rows);
        $avg = $avgPerTransaction ?? ($count > 0 ? $total / $count : 0);
        // Paket totals
        $packageTotals = $packageTotals ?? [];
        if(empty($packageTotals) && !empty($rows)) {
            $packageTotals = collect($rows)->groupBy('paket')->map(function($group) {
                return [
                    'revenue' => $group->sum('total'),
                    'count' => $group->count()
                ];
            })->toArray();
        }
    @endphp

    <div class="ringkasan">
        <div class="card">
            <div class="label">Total Pendapatan</div>
            <div class="value">Rp {{ number_format($total,0,',','.') }}</div>
        </div>
        <div class="card">
            <div class="label">Transaksi Lunas</div>
            <div class="value">{{ $count }}</div>
        </div>
        <div class="card">
            <div class="label">Rata-rata / Transaksi</div>
            <div class="value">Rp {{ number_format($avg,0,',','.') }}</div>
        </div>
    </div>

    {{-- TABEL DETAIL --}}
    <table>
        <thead>
            <tr>
                <th style="width:5%;">#</th>
                <th style="width:20%;">Nama Siswa</th>
                <th style="width:20%;">Email</th>
                <th style="width:15%;">Paket</th>
                <th style="width:10%; text-align:center;">Pertemuan</th>
                <th style="width:15%; text-align:right;">Total</th>
                <th style="width:15%; text-align:center;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $index => $r)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $r['name'] ?? '-' }}</td>
                <td>{{ $r['email'] ?? '-' }}</td>
                <td>{{ $r['paket'] ?? '-' }}</td>
                <td class="text-center">{{ $r['jumlah_pertemuan'] ?? 0 }}</td>
                <td class="text-right">Rp {{ number_format($r['total'] ?? 0,0,',','.') }}</td>
                <td class="text-center">{{ $r['tanggal'] ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">Total Keseluruhan</td>
                <td class="text-right">Rp {{ number_format($total,0,',','.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    {{-- RINGKASAN PER PAKET --}}
    @if(!empty($packageTotals))
    <div>
        <div class="sub-title">Ringkasan per Paket</div>
        <table style="width:50%;">
            <thead>
                <tr>
                    <th style="text-align:left;">Paket</th>
                    <th style="text-align:center;">Jumlah</th>
                    <th style="text-align:right;">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($packageTotals as $pname => $pval)
                <tr>
                    <td>{{ $pname }}</td>
                    <td class="text-center">{{ $pval['count'] }}</td>
                    <td class="text-right">Rp {{ number_format($pval['revenue'],0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- FOOTER --}}
    <div class="footer">
        Laporan ini dibuat secara otomatis oleh sistem Paradise of Math.
    </div>

</body>
</html>