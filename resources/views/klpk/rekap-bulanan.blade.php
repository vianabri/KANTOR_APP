@extends('layouts.app')

@section('title', 'Rekap Bulanan KLPK')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            <i class="fas fa-folder-open text-warning me-2"></i>
            Rekap Bulanan – Pemulihan Piutang KLPK
        </h4>

        <a href="{{ route('klpk.rekap.pdf', ['month' => $month, 'year' => $year]) }}" class="btn btn-outline-danger btn-sm"
            title="Download PDF">
            <i class="fas fa-file-pdf me-1"></i> PDF
        </a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Bulan</label>
                    <select name="month" class="form-select form-select-sm">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tahun</label>
                    <select name="year" class="form-select form-select-sm">
                        @for ($y = date('Y') - 2; $y <= date('Y'); $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary btn-sm w-100" type="submit">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-sm table-hover align-middle">
                <thead class="table-light">
                    <tr class="text-center">
                        <th style="width: 40px">#</th>
                        <th>CIF</th>
                        <th>Nama</th>
                        <th>Tgl Bayar</th>
                        <th>Nominal Bayar</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $p->member->cif_number }}</td>
                            <td>{{ $p->member->full_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->payment_date)->format('d/m/Y') }}</td>
                            <td class="text-end">
                                Rp {{ number_format($p->payment_amount, 0, ',', '.') }}
                            </td>
                            <td>{{ $p->officer_in_charge }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Tidak ada pembayaran bulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="alert alert-info mb-0 px-3 py-2">
            <strong>Total Bulan Ini:</strong>
            Rp {{ number_format($total, 0, ',', '.') }}
        </div>

        <button class="btn btn-success btn-sm">
            <i class="fas fa-paper-plane me-1"></i> Kirim Rekap ke Keuangan
        </button>
    </div>
@endsection
