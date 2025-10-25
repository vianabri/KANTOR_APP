@extends('layouts.app')
@section('title', 'Edit Barang ATK')

@section('content')
    <div class="container-fluid">

        <div class="card border-0 shadow-lg rounded-3">
            <div class="card-body p-4">

                <h4 class="fw-bold text-primary mb-4">
                    <i class="fas fa-edit me-2"></i> Edit Barang ATK
                </h4>

                <form action="{{ route('atk.update', $atk->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama Barang --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang"
                            class="form-control @error('nama_barang') is-invalid @enderror"
                            value="{{ old('nama_barang', $atk->nama_barang) }}" placeholder="Nama barang" required>
                        @error('nama_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Satuan --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Satuan</label>
                        <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror"
                            value="{{ old('satuan', $atk->satuan) }}" placeholder="pcs, box, pack, rim">
                        @error('satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3"
                            placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $atk->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tombol --}}
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('atk.index') }}" class="btn btn-secondary px-4 rounded-3 me-2">
                            <i class="fas fa-arrow-left me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-success px-4 rounded-3">
                            <i class="fas fa-save me-2"></i> Perbarui
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
