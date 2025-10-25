@extends('layouts.app')
@section('title', 'Tambah ATK Masuk')

@section('content')
    <div class="container-fluid">

        <div class="card border-0 shadow-lg rounded-3 mt-3">
            <div class="card-body p-4">

                {{-- HEADER --}}
                <div class="mb-4 d-flex align-items-center gap-2">
                    <i class="fas fa-arrow-down text-success fa-lg"></i>
                    <h4 class="fw-bold text-success mb-0">Input Barang Masuk</h4>
                </div>

                <form action="{{ route('atk-masuk.store') }}" method="POST">
                    @csrf

                    {{-- ROW 1 --}}
                    <div class="row g-3 mb-2">

                        {{-- Pilihan Barang --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                            <select name="atk_id" class="form-select @error('atk_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach ($atk as $item)
                                    <option value="{{ $item->id }}" {{ old('atk_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                            @error('atk_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Jumlah Masuk --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah Masuk <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_masuk"
                                class="form-control @error('jumlah_masuk') is-invalid @enderror"
                                placeholder="Masukkan jumlah masuk" min="1" value="{{ old('jumlah_masuk') }}"
                                required>
                            @error('jumlah_masuk')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    {{-- ROW 2 --}}
                    <div class="row g-3">

                        {{-- Harga Satuan --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga Satuan <span class="text-danger">*</span></label>
                            <input type="number" name="harga_satuan"
                                class="form-control @error('harga_satuan') is-invalid @enderror" placeholder="Contoh: 3000"
                                min="0" value="{{ old('harga_satuan') }}" required>
                            @error('harga_satuan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Tanggal Masuk --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Masuk <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_masuk"
                                class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                            @error('tanggal_masuk')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('atk-masuk.index') }}" class="btn btn-secondary px-4 rounded-3 me-2">
                            <i class="fas fa-arrow-left me-1"></i> Batal
                        </a>
                        <button class="btn btn-success px-4 rounded-3" type="submit">
                            <i class="fas fa-save me-2"></i> Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
