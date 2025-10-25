@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <style>
        .card {
            border-radius: 14px;
            transition: .25s ease-in-out;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            transition: .3s ease-in-out;
        }

        .stat-icon:hover {
            transform: scale(1.15);
        }
    </style>

    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="p-4 bg-primary text-white rounded-4 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1">Dashboard</h2>
                    <p class="mb-0 text-white-50">Overview & insights</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-light btn-sm" id="refreshData">
                        <i class="fas fa-rotate me-1"></i> Refresh
                    </button>
                    <button class="btn btn-outline-light btn-sm">
                        <i class="fas fa-calendar me-2"></i> {{ now()->translatedFormat('d F Y') }}
                    </button>
                </div>
            </div>
        </div>


        {{-- STAT CARDS --}}
        <div class="row g-4 mb-3">
            @php
                $stats = [
                    ['label' => 'Total Users', 'val' => $totalUsers, 'color' => 'primary', 'icon' => 'fa-users'],
                    ['label' => 'Active Roles', 'val' => $totalRoles, 'color' => 'success', 'icon' => 'fa-user-shield'],
                    [
                        'label' => 'Pending Reports',
                        'val' => $pendingReports,
                        'color' => 'danger',
                        'icon' => 'fa-file-lines',
                    ],
                ];
            @endphp

            @foreach ($stats as $st)
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">{{ $st['label'] }}</small>
                                <h3 class="fw-bold text-{{ $st['color'] }} counter" data-target="{{ $st['val'] }}">0
                                </h3>
                            </div>
                            <div class="stat-icon bg-{{ $st['color'] }} bg-opacity-10">
                                <i class="fas {{ $st['icon'] }} text-{{ $st['color'] }} fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        {{-- ATK CARDS --}}
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Jenis ATK</small>
                            <h3 class="fw-bold counter" data-target="{{ $totalAtkItems }}">0</h3>
                        </div>
                        <div class="stat-icon bg-dark bg-opacity-10">
                            <i class="fas fa-boxes fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Total Stok</small>
                            <h3 class="fw-bold text-info counter" data-target="{{ $totalStok }}">0</h3>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10">
                            <i class="fas fa-layer-group text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Nilai Persediaan</small>
                            <h4 class="fw-bold text-warning">{{ number_format($totalNilaiPersediaan, 0, ',', '.') }} IDR
                            </h4>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10">
                            <i class="fas fa-coins text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Stok Rendah (&lt;5)</small>
                            <h3 class="fw-bold {{ $stokRendah > 0 ? 'text-danger' : 'text-success' }} counter"
                                data-target="{{ $stokRendah }}">0</h3>
                        </div>
                        <div class="stat-icon {{ $stokRendah > 0 ? 'bg-danger bg-opacity-10' : 'bg-success bg-opacity-10' }}">
                            <i
                                class="fas fa-exclamation-triangle {{ $stokRendah > 0 ? 'text-danger' : 'text-success' }} fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- KPI --}}
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm text-center h-100">
                    <div class="card-body">
                        <small class="text-muted d-block mb-2">Snapshot CDR</small>
                        <div class="d-flex justify-content-center gap-4">
                            <div>
                                <div class="small text-muted">Hari Ini</div>
                                <h4
                                    class="fw-bold {{ $cdrToday > 20 ? 'text-danger' : ($cdrToday > 10 ? 'text-warning' : 'text-success') }}">
                                    {{ number_format($cdrToday, 2) }}%</h4>
                            </div>
                            <div>
                                <div class="small text-muted">Bulan Lalu</div>
                                <h4
                                    class="fw-bold {{ $cdrLastMonth > 20 ? 'text-danger' : ($cdrLastMonth > 10 ? 'text-warning' : 'text-success') }}">
                                    {{ number_format($cdrLastMonth, 2) }}%</h4>
                            </div>
                        </div>
                        <div class="pt-2">{!! $trendIcon !!}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold text-secondary">Selamat Datang!</h6>
                        <p class="text-muted mb-0">Pantau status kredit & persediaan setiap hari.</p>
                    </div>
                </div>
            </div>
        </div>


        {{-- CHARTS --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-chart-line me-2 text-primary"></i> User Growth
            </div>
            <div class="card-body" style="height:380px;">
                <canvas id="userChart"></canvas>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-exchange-alt me-2 text-danger"></i> ATK Masuk vs Keluar
            </div>
            <div class="card-body" style="height:380px;">
                <canvas id="atkChart"></canvas>
            </div>
        </div>

    </div>


    {{-- === SCRIPT === --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        Chart.defaults.animation.duration = 900;
        Chart.defaults.elements.line.borderJoinStyle = 'round';

        // USER CHART
        new Chart(document.getElementById('userChart'), {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'New Users',
                    data: @json($data),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.1)',
                    fill: true,
                    tension: .4
                }]
            }
        });

        // ATK CHART
        new Chart(document.getElementById('atkChart'), {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                        label: 'ATK Masuk',
                        data: @json($atkMasukData),
                        backgroundColor: 'rgba(25,135,84,.4)',
                        borderColor: 'rgb(25,135,84)'
                    },
                    {
                        label: 'ATK Keluar',
                        data: @json($atkKeluarData),
                        backgroundColor: 'rgba(220,53,69,.4)',
                        borderColor: 'rgb(220,53,69)'
                    },
                ]
            }
        });

        // COUNTER ANIMATION
        document.querySelectorAll('.counter').forEach(counter => {
            let target = +counter.getAttribute('data-target');
            let count = 0;
            let step = target / 50;

            function update() {
                count += step;
                if (count < target) {
                    counter.innerText = Math.floor(count);
                    requestAnimationFrame(update);
                } else {
                    counter.innerText = target;
                }
            }
            update();
        });
    </script>

@endsection
