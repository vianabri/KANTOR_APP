<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-size: 12px;
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }

        th {
            background: #eee;
        }
    </style>
</head>

<body>

    <h3>Histori Pembayaran KLPK</h3>
    <p><strong>Nama:</strong> {{ $member->full_name }}</p>
    <p><strong>CIF:</strong> {{ $member->cif_number }}</p>
    <p><strong>NIK:</strong> {{ $member->id_number }}</p>
    <p><strong>Sisa Pokok:</strong> Rp {{ number_format($member->principal_remaining, 0, ',', '.') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal Bayar</th>
                <th>Nominal</th>
                <th>Metode</th>
                <th>Petugas</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($member->payments as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->payment_date }}</td>
                    <td>Rp {{ number_format($p->payment_amount, 0, ',', '.') }}</td>
                    <td>{{ $p->payment_method }}</td>
                    <td>{{ $p->officer_in_charge }}</td>
                    <td>{{ $p->notes }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <small>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</small>

</body>

</html>
