@extends('layouts.app')
@section('title', 'Edit Bagian')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">
                <i class="fas fa-pen-to-square text-primary me-2"></i> Edit Bagian
            </h3>
            <a href="{{ route('bagian.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header text-white fw-semibold" style="background: linear-gradient(90deg, #007bff, #00c6ff);">
                <i class="fas fa-pen me-2"></i> Formulir Edit Bagian
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('bagian.update', $bagian->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="nama_bagian" class="form-label fw-semibold">
                            <i class="fas fa-layer-group me-1 text-primary"></i> Nama Bagian
                        </label>
                        <input type="text" name="nama_bagian" id="nama_bagian"
                            class="form-control form-control-lg rounded-3 shadow-sm @error('nama_bagian') is-invalid @enderror"
                            value="{{ old('nama_bagian', $bagian->nama_bagian) }}" placeholder="Masukkan nama bagian..."
                            required autofocus>
                        @error('nama_bagian')
                            <div class="invalid-feedback mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('bagian.index') }}" class="btn btn-outline-secondary me-2 px-4 shadow-sm">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-gradient-primary px-4 shadow-sm">
                            <i class="fas fa-save me-1"></i> Perbarui
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

            .form-control-lg {
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
