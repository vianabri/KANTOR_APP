@extends('layouts.app')
@section('title', 'Data Pegawai')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">
                <i class="fas fa-users text-primary me-2"></i> Data Pegawai
            </h3>
            <a href="{{ route('pegawai.create') }}" class="btn btn-gradient-primary shadow">
                <i class="fas fa-user-plus me-1"></i> Tambah Pegawai
            </a>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Filter dan pencarian --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('pegawai.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Cari Pegawai</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control shadow-sm"
                            placeholder="Cari nama atau NIP...">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted">Filter Bagian</label>
                        <select name="bagian_id" class="form-select shadow-sm" onchange="this.form.submit()">
                            <option value="">Semua Bagian</option>
                            @foreach ($bagians as $bagian)
                                <option value="{{ $bagian->id }}"
                                    {{ request('bagian_id') == $bagian->id ? 'selected' : '' }}>
                                    {{ $bagian->nama_bagian }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted">Filter Jabatan</label>
                        <select name="jabatan_id" class="form-select shadow-sm" onchange="this.form.submit()">
                            <option value="">Semua Jabatan</option>
                            @foreach ($jabatans as $jabatan)
                                <option value="{{ $jabatan->id }}"
                                    {{ request('jabatan_id') == $jabatan->id ? 'selected' : '' }}>
                                    {{ $jabatan->nama_jabatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn btn-outline-primary shadow-sm w-100">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Card tabel utama --}}
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-gradient fw-semibold"
                style="background: linear-gradient(90deg, #dfe9ff, #b9d6ff); color: #000;">
                <i class="fas fa-database me-2 text-primary"></i> Daftar Pegawai
            </div>

            <div class="table-scroll-wrapper">
                <table class="table table-hover align-middle mb-0 table-custom">
                    <thead class="text-uppercase text-secondary small fw-bold">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Foto</th>
                            <th class="text-center">NIP</th>
                            <th class="ps-4">Nama</th>
                            <th class="text-center">Jabatan</th>
                            <th class="text-center">Bagian</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pegawais as $p)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">
                                    <img src="{{ $p->foto ? asset('storage/' . $p->foto) : asset('images/default-user.png') }}"
                                        class="rounded-circle shadow-sm border" width="55" height="55"
                                        style="object-fit: cover;">
                                </td>
                                <td class="text-center fw-semibold">{{ $p->nip }}</td>
                                <td class="fw-semibold ps-4">{{ $p->nama }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark px-3 py-2 shadow-sm">
                                        {{ $p->jabatan->nama_jabatan ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark px-3 py-2 shadow-sm">
                                        {{ $p->jabatan->bagian->nama_bagian ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('pegawai.profil', $p->id) }}"
                                            class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-id-card"></i>
                                        </a>
                                        <a href="{{ route('pegawai.edit', $p->id) }}"
                                            class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('pegawai.destroy', $p->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus pegawai ini?')"
                                                class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fas fa-user-slash fa-3x mb-3 text-primary"></i>
                                    <h6 class="fw-semibold">Belum ada data pegawai</h6>
                                    <p class="small text-secondary">Tambahkan data baru untuk mulai mengelola pegawai.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($pegawais->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $pegawais->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            .btn-gradient-primary {
                background: linear-gradient(90deg, #007bff, #00c6ff);
                color: #fff;
                border: none;
                transition: 0.3s;
            }

            .btn-gradient-primary:hover {
                background: linear-gradient(90deg, #0062cc, #00a2ff);
                transform: translateY(-2px);
            }

            .table-scroll-wrapper {
                overflow-x: auto;
            }

            .table-custom thead {
                background: #f1f5f9;
            }
        </style>
    @endpush
@endsection
