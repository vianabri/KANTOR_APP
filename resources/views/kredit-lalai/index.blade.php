@extends('layouts.app')
@section('title', 'Laporan Kredit Lalai Harian')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="text-center mb-4">
            <h4 class="fw-bold text-primary text-uppercase">Laporan Kredit Lalai Harian</h4>

            <form method="GET" class="row g-2 justify-content-center mb-2">
                <div class="col-auto">
                    <select class="form-select" name="bulan">
                        @foreach (range(1, 12) as $b)
                            <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select class="form-select" name="tahun">
                        @foreach (range(now()->year - 3, now()->year) as $y)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <input type="text" class="form-control" name="wilayah" placeholder="Wilayah (opsional)"
                        value="{{ $wilayah }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary"><i class="fa-solid fa-filter"></i></button>
                </div>
            </form>

            <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('kredit-lalai.export.excel', request()->query()) }}" class="btn btn-success btn-sm">
                    <i class="fa-solid fa-file-excel me-1"></i> Excel
                </a>
                <a href="{{ route('kredit-lalai.export.pdf', request()->query()) }}" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-file-pdf me-1"></i> PDF
                </a>
            </div>
        </div>

        {{-- KPI Snapshot --}}
        <div class="row row-cols-1 row-cols-md-4 g-3 mb-4">

            @component('components.kpi-box', [
                'label' => 'Snapshot Piutang',
                'value' => 'Rp ' . number_format($snapPiutang, 0, ',', '.'),
                'class' => 'text-dark',
            ])
            @endcomponent

            @component('components.kpi-box', [
                'label' => 'Snapshot Kredit Lalai',
                'value' => 'Rp ' . number_format($snapLalai, 0, ',', '.'),
                'class' => 'text-danger',
            ])
            @endcomponent

            @php
                $color = $snapRasio < 10 ? 'text-success' : ($snapRasio <= 20 ? 'text-warning' : 'text-danger');
            @endphp
            @component('components.kpi-box', [
                'label' => 'Snapshot CDR',
                'value' => number_format($snapRasio, 2) . '%',
                'small' => '(Berdasarkan data terakhir bulan ini)',
                'class' => $color,
            ])
            @endcomponent

            @component('components.kpi-box', [
                'label' => 'Trend vs Bulan Lalu',
                'value' => $trendText,
                'class' => $trendColor,
            ])
            @endcomponent

        </div>

        {{-- Notifikasi Data Minim --}}
        @if ($items->count() < 5)
            <div class="alert alert-info text-center small">
                Data harian belum cukup banyak untuk membaca tren akurat.
            </div>
        @endif

        {{-- Grafik --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold text-secondary mb-3">Trend Harian CDR Bulanan</h6>
                <canvas id="rasioChart" height="120"></canvas>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="card shadow-sm">
            <div class="card-body">
                @if ($items->count())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center align-middle">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Wilayah</th>
                                    <th>Piutang</th>
                                    <th>Lalai</th>
                                    <th>CDR</th>
                                    <th>Keterangan</th>
                                    <th>Input Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $r)
                                    @php
                                        $badge =
                                            $r->rasio_lalai < 10
                                                ? 'bg-success'
                                                : ($r->rasio_lalai <= 20
                                                    ? 'bg-warning text-dark'
                                                    : 'bg-danger');
                                    @endphp
                                    <tr>
                                        <td>{{ $r->tanggal->format('d/m/Y') }}</td>
                                        <td>{{ $r->wilayah ?? 'GLOBAL' }}</td>
                                        <td class="text-end">Rp {{ number_format($r->total_piutang, 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($r->total_lalai, 0, ',', '.') }}</td>
                                        <td><span
                                                class="badge {{ $badge }}">{{ number_format($r->rasio_lalai, 2) }}%</span>
                                        </td>
                                        <td class="text-start">{{ $r->keterangan ?? '-' }}</td>
                                        <td>{{ optional($r->user)->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        Tidak ada data pada periode ini.
                    </div>
                @endif
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = @json($items->pluck('tanggal')->map(fn($d) => $d->format('d M')));
        const dataRasio = @json($items->pluck('rasio_lalai')->map(fn($v) => round($v, 2)));

        new Chart(document.getElementById('rasioChart'), {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'CDR (%)',
                    data: dataRasio,
                    borderColor: '#007bff',
                    borderWidth: 2,
                    tension: 0.25
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `CDR: ${ctx.raw}%`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => v + '%'
                        }
                    }
                }
            }
        });
    </script>
@endsection
