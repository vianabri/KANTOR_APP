@extends('layouts.app')
@section('title','Input Penagihan')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-lg rounded-3">
        <div class="card-body p-4">
            <h4 class="fw-bold text-primary mb-4">
                <i class="fas fa-hand-holding-dollar me-2"></i> Input Penagihan Lapangan
            </h4>

            <form action="{{ route('penagihan.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tanggal Kunjungan</label>
                        <input type="date" name="tanggal_kunjungan" class="form-control" value="{{ old('tanggal_kunjungan', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">CIF</label>
                        <input type="text" name="cif" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Anggota</label>
                        <input type="text" name="nama_anggota" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Wilayah</label>
                        <input type="text" name="wilayah" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Nomor WA (Opsional)</label>
                        <input type="text" name="nomor_wa" class="form-control" placeholder="62xxxxxxxxxxx">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Nominal Ditagih</label>
                        <input type="number" name="nominal_ditagih" class="form-control" min="1000" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Nominal Dibayar (jika ada)</label>
                        <input type="number" name="nominal_dibayar" class="form-control" min="0">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kendala (jika tidak bayar)</label>
                        <input type="text" name="kendala" class="form-control" placeholder="contoh: Tidak di tempat / kesulitan usaha">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tanggal Janji (opsional)</label>
                        <input type="date" name="tanggal_janji" class="form-control">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('penagihan.laporan') }}" class="btn btn-secondary px-4 me-2 rounded-3">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
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
