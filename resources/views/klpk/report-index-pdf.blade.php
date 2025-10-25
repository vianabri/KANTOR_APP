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
            text-align: left;
        }

        th {
            background: #eee;
        }

        h3 {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <h3>Laporan Index KLPK</h3>
    <p>Tanggal Cetak : {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>CIF</th>
                <th>Nama</th>
                <th>Sisa Pokok Awal</th>
                <th>Total Bayar</th>
                <th>Sisa Pokok</th>
                <th>Progress</th>
                <th>Status Penagihan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($members as $m)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $m->cif_number }}</td>
                    <td>{{ $m->full_name }}</td>
                    <td>Rp {{ number_format($m->principal_start, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($m->total_paid, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($m->principal_remaining, 0, ',', '.') }}</td>
                    <td>{{ $m->progress }}%</td>
                    <td>{{ $m->status_penagihan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
