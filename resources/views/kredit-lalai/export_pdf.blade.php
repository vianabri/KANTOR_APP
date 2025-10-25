<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Export PDF - Kredit Lalai Harian</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        h3,
        h4 {
            margin: 0 0 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px 5px;
            font-size: 11px;
        }

        th {
            background: #f1f1f1;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        small {
            color: #666;
        }
    </style>
</head>

<body>
    <div class="center">
        <h3>Laporan Kredit Lalai Harian</h3>
        <h4>Periode: {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</h4>
        <small>Wilayah: {{ $wilayah ?? 'GLOBAL' }}</small>
    </div>

    <table>
        <thead>
            <tr class="center">
                <th>Tanggal</th>
                <th>Wilayah</th>
                <th>Total Piutang</th>
                <th>Total Lalai</th>
                <th>CDR (%)</th>
                <th>Keterangan</th>
                <th>Input Oleh</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $r)
                <tr>
                    <td class="center">{{ $r->tanggal->format('d/m/Y') }}</td>
                    <td class="center">{{ $r->wilayah ?? 'GLOBAL' }}</td>
                    <td class="right">Rp {{ number_format($r->total_piutang, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($r->total_lalai, 0, ',', '.') }}</td>
                    <td class="center">{{ number_format($r->rasio_lalai, 2) }}</td>
                    <td>{{ $r->keterangan ?? '-' }}</td>
                    <td class="center">{{ optional($r->user)->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
