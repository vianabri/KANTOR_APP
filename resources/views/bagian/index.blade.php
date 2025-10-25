@extends('layouts.app')
@section('title', 'Data Bagian')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">
                <i class="fas fa-folder-open text-primary me-2"></i> Data Bagian
            </h3>

            @can('manage bagian')
                <a href="{{ route('bagian.create') }}" class="btn btn-gradient-primary shadow">
                    <i class="fas fa-plus me-1"></i> Tambah Bagian
                </a>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-gradient fw-semibold"
                style="background: linear-gradient(90deg, #dfe9ff, #b9d6ff); color: #000;">
                <i class="fas fa-database me-2 text-primary"></i> Daftar Bagian
            </div>

            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0 table-custom">
                    <thead class="text-uppercase text-secondary small fw-bold">
                        <tr>
                            <th class="text-center" width="6%">No</th>
                            <th class="ps-4">Nama Bagian</th>
                            <th class="text-center" width="22%">Dibuat Oleh</th>
                            <th class="text-center" width="22%">Diperbarui Oleh</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bagians as $bagian)
                            <tr>
                                <td class="text-center fw-semibold text-muted">{{ $loop->iteration }}</td>
                                <td class="fw-semibold ps-4">
                                    <i class="fas fa-layer-group text-primary me-2"></i>
                                    {{ $bagian->nama_bagian }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark px-3 py-2 shadow-sm">
                                        {{ $bagian->creator->name ?? '—' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark px-3 py-2 shadow-sm">
                                        {{ $bagian->updater->name ?? '—' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @can('manage bagian')
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('bagian.edit', $bagian->id) }}"
                                                class="btn btn-sm btn-outline-warning shadow-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('bagian.destroy', $bagian->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Yakin hapus bagian ini?')"
                                                    class="btn btn-sm btn-outline-danger shadow-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fas fa-folder-open fa-3x mb-3 text-primary"></i>
                                    <h6 class="fw-semibold">Belum ada data bagian</h6>
                                    <p class="small text-secondary">Silakan tambahkan data baru untuk mulai mengelola
                                        bagian.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($bagians->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $bagians->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    {{-- Custom styles --}}
    @push('styles')
        <style>
            .btn-gradient-primary {
                background: linear-gradient(90deg, #007bff, #00c6ff);
                color: #fff;
                border: none;
                transition: 0.3s ease-in-out;
            }

            .btn-gradient-primary:hover {
                background: linear-gradient(90deg, #0062cc, #00a2ff);
                transform: translateY(-2px);
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            }

            /* Table styling */
            .table-custom thead {
                background: #f1f5f9;
                border-bottom: 2px solid #dee2e6;
            }

            .table-custom th {
                padding: 0.9rem 1rem;
                vertical-align: middle;
                letter-spacing: 0.5px;
            }

            .table-custom td {
                padding: 0.8rem 1rem;
                vertical-align: middle;
            }

            .table-custom tbody tr:hover {
                background-color: #f0f8ff !important;
                transition: background-color 0.2s ease;
            }

            .table-custom .badge {
                font-size: 0.85rem;
            }
        </style>
    @endpush
@endsection
