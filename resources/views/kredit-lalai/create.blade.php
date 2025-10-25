@extends('layouts.app')
@section('title', 'Input Kredit Lalai Harian')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <h4 class="fw-bold text-primary mb-4">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> Input Kredit Lalai Harian
                </h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('kredit-lalai.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}"
                                class="form-control @error('tanggal') is-invalid @enderror" required>
                            @error('tanggal')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Wilayah (opsional)</label>
                            <input type="text" name="wilayah" value="{{ old('wilayah') }}" class="form-control"
                                placeholder="Kosongkan = Global">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Total Piutang Beredar <span
                                    class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="total_piutang"
                                value="{{ old('total_piutang') }}"
                                class="form-control @error('total_piutang') is-invalid @enderror" required>
                            @error('total_piutang')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Total Kredit Lalai <span
                                    class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="total_lalai"
                                value="{{ old('total_lalai') }}"
                                class="form-control @error('total_lalai') is-invalid @enderror" required>
                            @error('total_lalai')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan (opsional)</label>
                            <textarea name="keterangan" rows="2" class="form-control" placeholder="Catatan singkat">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('kredit-lalai.index') }}" class="btn btn-secondary me-2">
                            <i class="fa-solid fa-arrow-left me-1"></i> Batal
                        </a>
                        <button class="btn btn-success">
                            <i class="fa-solid fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
