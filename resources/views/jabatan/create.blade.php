@extends('layouts.app')
@section('title', 'Tambah Jabatan')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">
                <i class="fas fa-plus-circle text-primary me-2"></i> Tambah Jabatan
            </h3>
            <a href="{{ route('jabatan.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-gradient fw-semibold"
                style="background: linear-gradient(90deg, #dfe9ff, #b9d6ff); color: #000;">
                <i class="fas fa-pen me-2 text-primary"></i> Formulir Tambah Jabatan
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('jabatan.store') }}">
                    @csrf

                    {{-- Nama Jabatan --}}
                    <div class="mb-4">
                        <label for="nama_jabatan" class="form-label fw-semibold">
                            <i class="fas fa-briefcase me-1 text-primary"></i> Nama Jabatan
                        </label>
                        <input type="text" name="nama_jabatan" id="nama_jabatan"
                            class="form-control form-control-lg rounded-3 shadow-sm @error('nama_jabatan') is-invalid @enderror"
                            value="{{ old('nama_jabatan') }}" placeholder="Masukkan nama jabatan..." required>
                        @error('nama_jabatan')
                            <div class="invalid-feedback mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pilihan Bagian --}}
                    <div class="mb-4">
                        <label for="bagian_id" class="form-label fw-semibold">
                            <i class="fas fa-layer-group me-1 text-primary"></i> Bagian
                        </label>
                        <select name="bagian_id" id="bagian_id"
                            class="form-select form-select-lg rounded-3 shadow-sm @error('bagian_id') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih Bagian --</option>
                            @foreach ($bagians as $bagian)
                                <option value="{{ $bagian->id }}" {{ old('bagian_id') == $bagian->id ? 'selected' : '' }}>
                                    {{ $bagian->nama_bagian }}
                                </option>
                            @endforeach
                        </select>
                        @error('bagian_id')
                            <div class="invalid-feedback mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('jabatan.index') }}" class="btn btn-outline-secondary me-2 px-4 shadow-sm">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-gradient-primary px-4 shadow-sm">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
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

            .form-control-lg,
            .form-select-lg {
                font-size: 1rem;
                padding: 0.7rem 1rem;
            }

            .form-label {
                color: #343a40;
            }

            .card {
                transition: all 0.2s ease-in-out;
            }

            .card:hover {
                transform: translateY(-2px);
            }
        </style>
    @endpush
@endsection
