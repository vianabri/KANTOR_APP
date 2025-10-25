<table border="1" width="100%" cellspacing="0" cellpadding="6">
    <tr>
        <td colspan="9" align="center">
            <strong style="font-size:18px;">KSP CREDIT UNION LIKKU ABA</strong><br>
            <span style="font-size:16px; text-transform: uppercase;">
                Laporan Persediaan Barang ATK
            </span><br>
            <span>Per {{ today()->translatedFormat('d F Y') }}</span>
        </td>
    </tr>

    <tr style="background:#e3e3e3; font-weight:bold; text-align:center;">
        <td>No</td>
        <td>Nama Barang</td>
        <td>Satuan</td>
        <td>Masuk</td>
        <td>Keluar</td>
        <td>Sisa</td>
        <td>Harga Satuan</td>
        <td>Total Nilai</td>
        <td>Status</td>
    </tr>

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

        <tr align="center">
            <td>{{ $idx + 1 }}</td>
            <td align="left">{{ $i->nama_barang }}</td>
            <td>{{ $i->satuan }}</td>
            <td>{{ $masuk }}</td>
            <td>{{ $keluar }}</td>
            <td>{{ $stok }}</td>
            <td>{{ number_format($harga, 0) }}</td>
            <td>{{ number_format($total, 0) }}</td>
            <td>
                @if ($stok == 0)
                    HABIS
                @elseif($stok < 5)
                    MENIPIS
                @else
                    AMAN
                @endif
            </td>
        </tr>
    @endforeach

    <tr style="font-weight:bold; background:#dff0d8;">
        <td colspan="7" align="right">TOTAL NILAI :</td>
        <td colspan="2">{{ number_format($grandTotal, 0) }} IDR</td>
    </tr>
</table>
