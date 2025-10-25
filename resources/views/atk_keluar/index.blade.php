@extends('layouts.app')
@section('title', 'Riwayat ATK Keluar')

@section('content')
    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-danger">Riwayat ATK Keluar</h4>
            <a href="{{ route('atk-keluar.create') }}" class="btn btn-danger shadow-sm px-3 py-2 rounded-3">
                <i class="fas fa-minus-circle me-2"></i> Barang Keluar
            </a>
        </div>

        <div class="card border-0 shadow-lg rounded-3">
            <div class="card-body">

                @if ($items->count())
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="bg-danger text-white text-center">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah Keluar</th>
                                    <th>Penerima</th>
                                    <th>Keperluan</th>
                                    <th>Tanggal Keluar</th>
                                    <th width="90">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($items as $keluar)
                                    <tr class="text-center fw-semibold">
                                        <td class="text-start text-dark">
                                            {{ $keluar->atk->nama_barang }}
                                        </td>

                                        <td>
                                            <span class="badge bg-warning text-dark px-3 py-2 fs-6">
                                                {{ $keluar->jumlah_keluar }}
                                            </span>
                                        </td>

                                        <td>{{ $keluar->penerima ?? '-' }}</td>
                                        <td class="text-muted">{{ $keluar->keperluan ?? '-' }}</td>

                                        <td class="text-muted">
                                            {{ \Carbon\Carbon::parse($keluar->tanggal_keluar)->translatedFormat('d F Y') }}
                                        </td>

                                        <td>
                                            <form action="{{ route('atk-keluar.destroy', $keluar->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button onclick="return confirm('Hapus riwayat ini?')"
                                                    class="btn btn-sm btn-outline-danger rounded-3" title="Hapus Data">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
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
                        <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                        <p class="mb-0">Belum ada data barang keluar.</p>
                    </div>
                @endif

            </div>
        </div>

    </div>
@endsection
