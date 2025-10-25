@extends('layouts.app')

@section('title', 'Histori Pembayaran KLPK')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            <i class="fas fa-receipt text-success me-2"></i>
            Riwayat Pembayaran – {{ $member->full_name }}
        </h4>

        <a href="{{ route('klpk.payment.history.pdf', $member->klpk_id) }}" class="btn btn-outline-danger btn-sm"
            title="Download PDF">
            <i class="fas fa-file-pdf me-1"></i> PDF
        </a>
    </div>

    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-4">
                    <strong>CIF:</strong> {{ $member->cif_number }}
                </div>
                <div class="col-md-4">
                    <strong>NIK:</strong> {{ $member->id_number ?? '-' }}
                </div>
                <div class="col-md-4">
                    <strong>Sisa Pokok:</strong>
                    <span class="badge bg-primary">
                        Rp {{ number_format($member->principal_remaining, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px">#</th>
                        <th class="text-center">Tanggal Bayar</th>
                        <th class="text-end" style="width: 140px">Nominal Bayar</th>
                        <th class="text-center" style="width: 120px">Metode</th>
                        <th class="text-center" style="width: 140px">Petugas</th>
                        <th>Catatan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($member->payments as $p)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>

                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($p->payment_date)->format('d/m/Y') }}
                            </td>

                            <td class="text-end">
                                Rp {{ number_format($p->payment_amount, 0, ',', '.') }}
                            </td>

                            <td class="text-center">{{ $p->payment_method ?? '-' }}</td>

                            <td class="text-center">{{ $p->officer_in_charge }}</td>

                            <td>{{ $p->notes ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    <div class="mt-3 text-end">
        <a href="{{ route('klpk.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
@endsection
