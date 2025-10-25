@extends('layouts.app')
@section('title', 'Barang ATK Keluar')

@section('content')
    <div class="container-fluid">

        {{-- ALERT ERROR --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mt-2">
                <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-lg rounded-3 mt-3">
            <div class="card-body p-4">

                {{-- HEADER FORM --}}
                <div class="mb-4 d-flex align-items-center gap-2">
                    <i class="fas fa-arrow-up text-danger fa-lg"></i>
                    <h4 class="fw-bold text-danger mb-0">Input Barang Keluar</h4>
                </div>

                <form action="{{ route('atk-keluar.store') }}" method="POST">
                    @csrf

                    {{-- Section Pilih Barang --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                            <select name="atk_id" class="form-select @error('atk_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach ($atk as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('atk_id', request('atk_id')) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_barang }} — Stok: {{ $item->stok }}
                                    </option>
                                @endforeach
                            </select>
                            @error('atk_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Section Informasi Keluar --}}
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jumlah Keluar <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_keluar"
                                class="form-control @error('jumlah_keluar') is-invalid @enderror" min="1"
                                placeholder="Qty" value="{{ old('jumlah_keluar') }}" required>
                            @error('jumlah_keluar')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal Keluar <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_keluar"
                                class="form-control @error('tanggal_keluar') is-invalid @enderror"
                                value="{{ old('tanggal_keluar', date('Y-m-d')) }}" required>
                            @error('tanggal_keluar')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    <div class="row g-3 mt-2">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Penerima</label>
                            <input type="text" name="penerima"
                                class="form-control @error('penerima') is-invalid @enderror"
                                placeholder="Nama Pegawai / Divisi" value="{{ old('penerima') }}">
                            @error('penerima')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Keperluan</label>
                            <textarea name="keperluan" class="form-control @error('keperluan') is-invalid @enderror" rows="2"
                                placeholder="contoh: kebutuhan rapat">{{ old('keperluan') }}</textarea>
                            @error('keperluan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('atk-keluar.index') }}" class="btn btn-secondary px-4 rounded-3 me-2">
                            <i class="fas fa-arrow-left me-1"></i> Batal
                        </a>
                        <button class="btn btn-danger px-4 rounded-3" type="submit">
                            <i class="fas fa-share-square me-2"></i> Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
