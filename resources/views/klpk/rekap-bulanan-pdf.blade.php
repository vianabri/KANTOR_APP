<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-size: 12px;
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background: #e9ecef;
        }

        h3,
        h4 {
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>

    <h3>Rekap Bulanan Pemulihan Piutang KLPK</h3>
    <p>Bulan: <strong>{{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}</strong></p>
    <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>CIF</th>
                <th>Nama</th>
                <th>Tanggal Bayar</th>
                <th>Nominal</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->member->cif_number }}</td>
                    <td>{{ $p->member->full_name }}</td>
                    <td>{{ $p->payment_date }}</td>
                    <td>Rp {{ number_format($p->payment_amount, 0, ',', '.') }}</td>
                    <td>{{ $p->officer_in_charge }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 15px;">
        <strong>Total Pemulihan Bulan Ini:</strong>
        Rp {{ number_format($total, 0, ',', '.') }}
    </p>

</body>

</html>
