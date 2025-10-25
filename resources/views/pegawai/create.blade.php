@extends('layouts.app')

@section('title', 'Tambah Pegawai')

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-gradient" style="background: linear-gradient(90deg, #dfe9ff, #b9d6ff); color:#000;">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-user-plus me-2 text-primary"></i> Tambah Pegawai Baru
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('pegawai.store') }}" enctype="multipart/form-data" id="pegawai-form">
                    @csrf

                    {{-- Data Utama --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIP</label>
                            <input type="text" name="nip" class="form-control shadow-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control shadow-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control shadow-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor HP</label>
                            <input type="text" name="no_hp" class="form-control shadow-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status Kerja</label>
                            <select name="status_kerja" class="form-select shadow-sm" required>
                                <option value="Tetap">Tetap</option>
                                <option value="Kontrak">Kontrak</option>
                                <option value="Magang">Magang</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" class="form-control shadow-sm" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea name="alamat" class="form-control shadow-sm" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jabatan</label>
                            <select name="jabatan_id" class="form-select shadow-sm" required>
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}
                                        ({{ $jabatan->bagian->nama_bagian }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Foto Pegawai</label>
                            <input type="file" name="foto" class="form-control shadow-sm" accept="image/*">
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            label {
                font-size: 0.9rem;
                color: #333;
            }
        </style>
    @endpush
@endsection
