@extends('layouts.app')
@section('title', 'Laporan Persediaan ATK')

@section('content')
    <div class="container-fluid">

        {{-- HEADER + FILTER --}}
        <div class="text-center mb-3">
            <h4 class="fw-bold text-uppercase text-primary">Laporan Persediaan Inventaris ATK</h4>
            <p class="text-muted mb-2">Diperbarui pada: {{ now()->translatedFormat('d F Y') }}</p>

            <form method="GET" class="row g-2 justify-content-center mb-3">
                <div class="col-auto">
                    <select name="bulan" class="form-select">
                        @foreach (range(1, 12) as $b)
                            <option value="{{ $b }}" {{ (int) $summary['bulan'] === $b ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select name="tahun" class="form-select">
                        @foreach (range(now()->year - 5, now()->year) as $y)
                            <option value="{{ $y }}" {{ (int) $summary['tahun'] === (int) $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Terapkan
                    </button>
                </div>
            </form>

            <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('atk.rekap.export') }}" class="btn btn-success btn-sm shadow-sm px-3 rounded-3">
                    <i class="fas fa-file-excel me-1"></i> Excel
                </a>
                <a href="{{ route('atk.rekap.pdf') }}" class="btn btn-danger btn-sm shadow-sm px-3 rounded-3">
                    <i class="fas fa-file-pdf me-1"></i> PDF
                </a>
            </div>

            <hr class="border-3 border-primary opacity-75" style="width: 220px; margin:auto;">
        </div>

        {{-- TABEL LAPORAN --}}
        <div class="card shadow-lg border-0 rounded-3 mb-4">
            <div class="card-body px-4 py-4">
                @if ($items->count())
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="bg-primary text-white text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Satuan</th>
                                    <th>Masuk</th>
                                    <th>Keluar</th>
                                    <th>Sisa Stok</th>
                                    <th>Harga Satuan</th>
                                    <th>Total Nilai (IDR)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($items as $index => $i)
                                    @php
                                        $totalMasuk = $i->masuk->sum('jumlah_masuk');
                                        $totalKeluar = $i->keluar->sum('jumlah_keluar');
                                        $hargaSatuanAkhir = optional($i->masuk->last())->harga_satuan ?? 0;
                                        $totalNilai = ($i->stok ?? 0) * $hargaSatuanAkhir;
                                    @endphp
                                    <tr class="fw-semibold">
                                        <td>{{ $index + 1 }}</td>
                                        <td class="text-start">{{ $i->nama_barang }}</td>
                                        <td>{{ $i->satuan ?? '-' }}</td>
                                        <td class="text-primary">{{ $totalMasuk }}</td>
                                        <td class="text-danger">{{ $totalKeluar }}</td>
                                        <td>
                                            <span
                                                class="badge fs-6 px-3 py-2
                                            @if (($i->stok ?? 0) == 0) bg-dark
                                            @elseif(($i->stok ?? 0) < 5) bg-danger
                                            @else bg-success @endif">
                                                {{ $i->stok ?? 0 }}
                                            </span>
                                        </td>
                                        <td>Rp {{ number_format($hargaSatuanAkhir, 0, ',', '.') }}</td>
                                        <td class="text-primary fw-bold">
                                            Rp {{ number_format($totalNilai, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if (($i->stok ?? 0) == 0)
                                                <span class="badge bg-dark">Habis</span>
                                            @elseif (($i->stok ?? 0) < 5)
                                                <span class="badge bg-danger">Menipis</span>
                                            @else
                                                <span class="badge bg-info text-dark">Aman</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{-- Total nilai keseluruhan ditampilkan di summary bawah agar alur: detail → ringkasan --}}
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-box-open fa-3x mb-3"></i>
                        <p>Data persediaan belum tersedia.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- SUMMARY CARD DI BAWAH TABEL --}}
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-body px-4 py-3">
                <h6 class="fw-bold text-secondary mb-3">Ringkasan Laporan</h6>
                <div class="row row-cols-1 row-cols-md-5 g-3">

                    <div class="col">
                        <div class="p-3 bg-light rounded text-center h-100">
                            <h6 class="text-muted mb-1">Total Barang</h6>
                            <h4 class="fw-bold text-primary mb-0">{{ $summary['total_items'] }}</h4>
                        </div>
                    </div>

                    <div class="col">
                        <div class="p-3 bg-light rounded text-center h-100">
                            <h6 class="text-muted mb-1">Total Nilai Persediaan</h6>
                            <h5 class="fw-bold text-success mb-0">
                                Rp {{ number_format($summary['total_value'], 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>

                    <div class="col">
                        <div class="p-3 bg-light rounded text-center h-100">
                            <h6 class="text-muted mb-1">Barang Menipis</h6>
                            <h4 class="fw-bold text-danger mb-0">{{ $summary['critical_items'] }}</h4>
                        </div>
                    </div>

                    <div class="col">
                        <div class="p-3 bg-light rounded text-center h-100">
                            <h6 class="text-muted mb-1">Pemakaian
                                ({{ \Carbon\Carbon::create()->month($summary['bulan'])->translatedFormat('F') }}
                                {{ $summary['tahun'] }})</h6>
                            <h4 class="fw-bold text-dark mb-0">{{ $summary['monthly_usage'] }}</h4>
                        </div>
                    </div>

                    <div class="col">
                        <div class="p-3 bg-light rounded text-center h-100">
                            <h6 class="text-muted mb-1">Tren Pemakaian</h6>
                            <h4
                                class="fw-bold mb-0
                            @if ($summary['trend'] === 'NAIK') text-danger
                            @elseif($summary['trend'] === 'TURUN') text-success
                            @else text-secondary @endif">
                                {{ $summary['trend'] }}
                            </h4>
                        </div>
                    </div>

                </div>

                {{-- Idle items --}}
                @if (isset($idleItems) && $idleItems->count())
                    <div class="mt-3">
                        <h6 class="fw-bold text-secondary mb-2">Barang Tidak Bergerak (≥ 90 hari)</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th class="text-center">Stok</th>
                                        <th class="text-center">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($idleItems as $it)
                                        <tr>
                                            <td>{{ $it->nama_barang }}</td>
                                            <td class="text-center">{{ $it->stok ?? 0 }}</td>
                                            <td class="text-center"><span class="badge bg-warning text-dark">Idle ≥ 90
                                                    hari</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        {{-- PRINT BUTTON --}}
        <div class="text-end mb-4">
            <button onclick="window.print()" class="btn btn-primary btn-lg shadow-sm px-4">
                <i class="fas fa-print me-2"></i> Cetak
            </button>
        </div>

    </div>

    <style>
        @media print {

            nav,
            #sidebarMenu,
            .btn,
            footer {
                display: none !important;
            }

            body {
                background: #fff;
                font-size: 12px;
            }

            table {
                border: 1px solid #000 !important;
            }

            thead {
                background: #000 !important;
                color: #fff !important;
            }
        }
    </style>
@endsection
