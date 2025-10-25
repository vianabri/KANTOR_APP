@extends('layouts.app')

@section('title', 'Dashboard KLPK')

@section('content')
    <h4 class="mb-4">
        <i class="fas fa-chart-bar me-2 text-success"></i>Dashboard KLPK
    </h4>
    @if ($reminderCount > 0)
        <div class="alert alert-warning shadow-sm mb-3">
            🔔 Ada <strong>{{ $reminderCount }}</strong> anggota yang perlu follow-up hari ini!
            <a href="{{ route('klpk.followup') }}" class="btn btn-warning btn-sm ms-2">
                Tindak Lanjut Sekarang
            </a>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-danger text-white shadow-sm">
                <div class="card-body">
                    <h6>Total Outstanding</h6>
                    <h3>Rp {{ number_format($outstanding, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <h6>Total Telah Dibayar</h6>
                    <h3>Rp {{ number_format($paid, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <h6>Recovery Rate</h6>
                    <h3>{{ $recoveryRate }}%</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-center mb-3">Status Penagihan</h6>
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-center mb-3">Tingkat Risiko</h6>
                    <canvas id="riskChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const statusData = {!! json_encode($statusData) !!};
        const riskData = {!! json_encode($riskData) !!};

        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: Object.keys(statusData),
                datasets: [{
                    data: Object.values(statusData),
                    backgroundColor: ['#198754', '#ffc107', '#dc3545', '#6c757d']
                }]
            }
        });

        new Chart(document.getElementById('riskChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(riskData),
                datasets: [{
                    data: Object.values(riskData),
                    backgroundColor: ['#0dcaf0', '#ffc107', '#dc3545']
                }]
            }
        });
    </script>

@endsection
