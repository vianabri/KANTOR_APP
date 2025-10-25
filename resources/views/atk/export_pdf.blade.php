<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header img {
            margin-bottom: 6px;
        }

        h3 {
            font-size: 16px;
            margin: 2px 0;
            font-weight: bold;
        }

        h4 {
            font-size: 14px;
            margin: 2px 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        small {
            font-size: 11px;
            letter-spacing: .5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px 4px;
        }

        th {
            background: #f1f1f1;
            font-size: 11px;
            text-transform: uppercase;
        }

        td {
            font-size: 11px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        tfoot td {
            background: #f6f8fa;
            font-weight: bold;
        }

        .status-habis {
            color: #b30000;
            font-weight: bold;
        }

        .status-menipis {
            color: #d17d00;
            font-weight: bold;
        }

        .status-aman {
            color: #006b2d;
            font-weight: bold;
        }

        .signature {
            width: 100%;
            margin-top: 35px;
        }

        .signature,
        .signature td {
            border: none !important;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('logo.png') }}" width="65">
        <h3>KSP CREDIT UNION LIKKU ABA</h3>
        <h4>Laporan Persediaan ATK</h4>
        <small>Per {{ today()->translatedFormat('d F Y') }}</small>
    </div>

    <table>
        <thead>
            <tr class="center">
                <th>No</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Sisa</th>
                <th>Harga Satuan</th>
                <th>Total Nilai (IDR)</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach ($items as $idx => $i)
                @php
                    $masuk = $i->masuk->sum('jumlah_masuk');
                    $keluar = $i->keluar->sum('jumlah_keluar');
                    $stok = $i->stok;
                    $harga = $i->masuk->last()?->harga_satuan ?? 0;
                    $total = $stok * $harga;
                    $grandTotal += $total;
                @endphp

                <tr class="center">
                    <td>{{ $idx + 1 }}</td>
                    <td style="text-align:left;">{{ $i->nama_barang }}</td>
                    <td>{{ $i->satuan ?? '-' }}</td>
                    <td>{{ $masuk }}</td>
                    <td>{{ $keluar }}</td>
                    <td><strong>{{ $stok }}</strong></td>
                    <td class="right">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                    <td class="right"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>

                    <td>
                        @if ($stok == 0)
                            <span class="status-habis">HABIS</span>
                        @elseif ($stok < 5)
                            <span class="status-menipis">MENIPIS</span>
                        @else
                            <span class="status-aman">AMAN</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td colspan="7" class="right">TOTAL NILAI PERSEDIAAN:</td>
                <td colspan="2" class="center">
                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>

    </table>

    <table class="signature">
        <tr>
            <td>
                Mengetahui,<br><br><br><br>
                _______________________
            </td>
            <td>
                Penyusun,<br><br><br><br>
                <strong>{{ Auth::user()->name }}</strong>
            </td>
        </tr>
    </table>

</body>

</html>
