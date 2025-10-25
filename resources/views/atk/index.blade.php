@extends('layouts.app')
@section('title', 'Master ATK')

@section('content')
    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-primary">Master ATK</h3>
            <a href="{{ route('atk.create') }}" class="btn btn-primary shadow-sm px-3 py-2 rounded-3">
                <i class="fas fa-plus me-2"></i> Tambah Barang
            </a>
        </div>

        {{-- PENCARIAN --}}
        <form method="GET" class="mb-3">
            <div class="input-group shadow-sm rounded-3">
                <input type="text" name="search" class="form-control" placeholder="Cari nama barang..."
                    value="{{ request('search') }}">
                <button class="btn btn-outline-primary">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <a href="{{ route('atk.rekap') }}" class="btn btn-dark btn-sm shadow-sm mb-3 px-3 py-2 rounded-3">
            <i class="fas fa-chart-bar me-2"></i> Rekap Persediaan
        </a>

        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body">

                @if ($items->count())
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle">
                            <thead class="bg-primary text-white text-center">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Satuan</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th width="180">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($items as $i)
                                    <tr class="text-center">

                                        <td class="text-start fw-semibold">{{ $i->nama_barang }}</td>

                                        <td>{{ $i->satuan ?? '-' }}</td>

                                        {{-- Badge stok tegas --}}
                                        <td>
                                            <span
                                                class="badge px-3 py-2 fs-6 fw-bold
                                            @if ($i->stok == 0) bg-dark
                                            @elseif($i->stok < 5) bg-warning text-dark
                                            @else bg-success @endif">
                                                {{ $i->stok }}
                                            </span>
                                        </td>

                                        {{-- Status berdasarkan stok --}}
                                        <td>
                                            @if ($i->stok == 0)
                                                <span class="badge bg-dark">Habis</span>
                                            @elseif ($i->stok < 5)
                                                <span class="badge bg-warning text-dark">Menipis</span>
                                            @else
                                                <span class="badge bg-info text-dark">Aman</span>
                                            @endif
                                        </td>

                                        <td class="text-muted">{{ $i->keterangan ?? '-' }}</td>

                                        {{-- Tombol-tombol aksi dirapikan --}}
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">

                                                <a href="{{ route('atk-masuk.create') }}?atk_id={{ $i->id }}"
                                                    class="btn btn-success btn-sm rounded-3" title="Barang Masuk">
                                                    <i class="fas fa-plus-circle"></i>
                                                </a>

                                                <a href="{{ route('atk-keluar.create') }}?atk_id={{ $i->id }}"
                                                    class="btn btn-danger btn-sm rounded-3" title="Barang Keluar">
                                                    <i class="fas fa-minus-circle"></i>
                                                </a>

                                                <a href="{{ route('atk.edit', $i->id) }}"
                                                    class="btn btn-warning btn-sm rounded-3" title="Edit Barang">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('atk.destroy', $i->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus barang ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-secondary btn-sm rounded-3" title="Hapus Barang">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>

                                            </div>
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
                        <p class="mb-0">Belum ada data ATK.</p>
                    </div>
                @endif

            </div>
        </div>

    </div>
@endsection
