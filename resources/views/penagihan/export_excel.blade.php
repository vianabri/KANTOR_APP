<table border="1">
    <thead>
        <tr style="font-weight:bold;text-align:center;">
            <td>Tanggal</td>
            <td>CIF</td>
            <td>Nama Anggota</td>
            <td>Wilayah</td>
            <td>Ditagih</td>
            <td>Dibayar</td>
            <td>Status</td>
            <td>Kendala</td>
            <td>Petugas</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $r)
            <tr>
                <td>{{ $r->tanggal_kunjungan->format('d/m/Y') }}</td>
                <td>{{ $r->cif }}</td>
                <td>{{ $r->nama_anggota }}</td>
                <td>{{ $r->wilayah }}</td>
                <td>{{ number_format($r->nominal_ditagih, 0, ',', '.') }}</td>
                <td>{{ number_format($r->nominal_dibayar, 0, ',', '.') }}</td>
                <td>{{ $r->status }}</td>
                <td>{{ $r->kendala ?? '-' }}</td>
                <td>{{ $r->user->name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
