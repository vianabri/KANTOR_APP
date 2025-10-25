@extends('layouts.app')

@section('title', 'Laporan Index KLPK')

@section('content')
    <h4 class="mb-3">
        <i class="fas fa-chart-line me-2 text-primary"></i>
        Laporan Index KLPK
    </h4>
    <a href="{{ route('klpk.report.pdf') }}" class="btn btn-danger btn-sm mb-3 float-end">
        <i class="fas fa-file-pdf me-1"></i> Download PDF
    </a>
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped table-hover table-sm">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>CIF</th>
                        <th>Nama</th>
                        <th>Sisa Pokok Awal</th>
                        <th>Total Bayar</th>
                        <th>Sisa Pokok</th>
                        <th>Progress</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $m)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $m->cif_number }}</td>
                            <td>{{ $m->full_name }}</td>
                            <td>Rp {{ number_format($m->principal_start, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($m->total_paid, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($m->principal_remaining, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $m->progress }}%
                                </span>
                            </td>
                            <td>
                                <span
                                    class="badge
                                {{ $m->status_penagihan == 'Aktif'
                                    ? 'bg-success'
                                    : ($m->status_penagihan == 'Somasi'
                                        ? 'bg-warning'
                                        : ($m->status_penagihan == 'Hukum'
                                            ? 'bg-danger'
                                            : 'bg-secondary')) }}">
                                    {{ $m->status_penagihan }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-muted" colspan="8">
                                Belum ada data KLPK.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
