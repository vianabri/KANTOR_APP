@extends('layouts.app')
@section('title', 'Riwayat ATK Masuk')

@section('content')
<div class="container-fluid">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary">Riwayat ATK Masuk</h4>
        <a href="{{ route('atk-masuk.create') }}" class="btn btn-primary shadow-sm px-3 py-2 rounded-3">
            <i class="fas fa-plus me-2"></i> Tambah Stok Masuk
        </a>
    </div>

    <div class="card border-0 shadow-lg rounded-3">
        <div class="card-body">

            @if ($items->count())
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="bg-primary text-white text-center">
                            <tr>
                                <th>Barang</th>
                                <th>Jumlah Masuk</th>
                                <th>Harga Satuan</th>
                                <th>Total Harga</th>
                                <th>Tanggal Masuk</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($items as $masuk)
                                <tr class="text-center fw-semibold">
                                    <td class="text-start text-dark">{{ $masuk->atk->nama_barang }}</td>

                                    <td>
                                        <span class="badge bg-success px-3 py-2 fs-6">
                                            {{ $masuk->jumlah_masuk }}
                                        </span>
                                    </td>

                                    <td>Rp {{ number_format($masuk->harga_satuan, 0, ',', '.') }}</td>

                                    <td class="text-primary fw-bold">
                                        Rp {{ number_format($masuk->total_harga, 0, ',', '.') }}
                                    </td>

                                    <td class="text-muted">
                                        {{ \Carbon\Carbon::parse($masuk->tanggal_masuk)->translatedFormat('d F Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-center">
                    {{ $items->links('pagination::bootstrap-5') }}
                </div>

            @else
                <div class="text-center text-muted py-5">
                    <i class="fas fa-box-open fa-3x mb-3"></i>
                    <p class="mb-0">Belum ada transaksi barang masuk.</p>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection
