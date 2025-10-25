@extends('layouts.app')
@section('title', 'Tambah Barang ATK')

@section('content')
    <div class="container-fluid">

        <div class="card border-0 shadow-lg rounded-3 mt-3">
            <div class="card-body p-4">

                {{-- HEADER --}}
                <div class="mb-4 d-flex align-items-center gap-2">
                    <i class="fas fa-box-open text-primary fa-lg"></i>
                    <h4 class="fw-bold text-primary mb-0">Tambah Barang ATK</h4>
                </div>

                <form action="{{ route('atk.store') }}" method="POST">
                    @csrf

                    {{-- INPUT FIELDS --}}
                    <div class="row g-3">

                        {{-- Nama Barang --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Nama Barang <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama_barang"
                                class="form-control @error('nama_barang') is-invalid @enderror"
                                placeholder="Contoh: Pulpen Snowman, Kertas A4" value="{{ old('nama_barang') }}" required>
                            @error('nama_barang')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Satuan --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Satuan</label>
                            <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror"
                                placeholder="pcs, box, pack, rim" value="{{ old('satuan') }}">
                            @error('satuan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Keterangan --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror"
                                placeholder="Opsional, contoh: Jenis tinta hitam, ukuran F4">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    {{-- AKSI --}}
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('atk.index') }}" class="btn btn-secondary px-4 me-2 rounded-3">
                            <i class="fas fa-arrow-left me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-success px-4 rounded-3">
                            <i class="fas fa-save me-2"></i> Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
