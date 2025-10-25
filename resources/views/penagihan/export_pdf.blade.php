<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        table { width:100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border:1px solid #444; padding:6px; }
        th { background:#efefef; }
        .center { text-align:center; }
        .right { text-align:right; }
    </style>
</head>
<body>
    <div class="center">
        <h3 style="margin:0;">KSP CREDIT UNION LIKKU ABA</h3>
        <h4 style="margin:2px 0;">LAPORAN PENAGIHAN STAF</h4>
        <small>Periode: {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</small>
    </div>

    <table>
        <thead>
            <tr class="center">
                <th>Tanggal</th>
                <th>CIF</th>
                <th>Nama</th>
                <th>Wilayah</th>
                <th>Ditagih</th>
                <th>Dibayar</th>
                <th>Status</th>
                <th>Janji</th>
                <th>Kendala</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $r)
                <tr class="center">
                    <td>{{ $r->tanggal_kunjungan->format('d/m/Y') }}</td>
                    <td>{{ $r->cif }}</td>
                    <td style="text-align:left">{{ $r->nama_anggota }}</td>
                    <td>{{ $r->wilayah }}</td>
                    <td class="right">{{ number_format($r->nominal_ditagih,0,',','.') }}</td>
                    <td class="right">{{ number_format($r->nominal_dibayar,0,',','.') }}</td>
                    <td>{{ $r->status }}</td>
                    <td>{{ optional($r->tanggal_janji)?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $r->kendala ?? '-' }}</td>
                    <td>{{ $r->user->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
