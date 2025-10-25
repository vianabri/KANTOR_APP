@extends('layouts.app')

@section('title', 'Follow-Up KLPK')

@section('content')
    <h4 class="mb-3">
        <i class="fas fa-bell text-danger me-2"></i>
        Daftar Follow-Up KLPK
    </h4>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-sm table-hover align-middle">
                <thead class="table-danger">
                    <tr>
                        <th>#</th>
                        <th>CIF</th>
                        <th>Nama</th>
                        <th>Sisa Pokok</th>
                        <th>Terakhir Bayar</th>
                        <th>Aging (hari)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $m)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $m->cif_number }}</td>
                            <td>{{ $m->full_name }}</td>
                            <td>Rp {{ number_format($m->principal_remaining, 0, ',', '.') }}</td>
                            <td>{{ $m->last_payment_date ?? '-' }}</td>
                            <td><strong>{{ $m->days_aging ?? '-' }}</strong></td>
                            <td>
                                <a href="{{ route('klpk.payment.create', $m->klpk_id) }}"
                                    class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-money-bill"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada yang perlu di follow-up</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
