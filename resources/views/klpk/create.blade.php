@extends('layouts.app')

@section('title', 'Tambah Data KLPK')

@section('content')
    <div class="mb-3">
        <h4><i class="fas fa-plus me-2 text-primary"></i>Tambah Data KLPK</h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('klpk.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Nomor CIF *</label>
                        <input type="text" name="cif_number" class="form-control" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">NIK</label>
                        <input type="text" name="id_number" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone_number" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="address" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tanggal Keluar *</label>
                        <input type="date" name="exit_date" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nomor Pinjaman</label>
                        <input type="text" name="loan_reference" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sisa Pokok Awal *</label>
                        <input type="number" name="principal_start" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Petugas Penanggung Jawab</label>
                        <input type="text" name="officer_in_charge" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tingkat Risiko</label>
                        <select name="risk_level" class="form-select">
                            <option value="">-</option>
                            <option>Rendah</option>
                            <option>Sedang</option>
                            <option>Tinggi</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status Penagihan</label>
                        <select name="status_penagihan" class="form-select">
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                            <option value="Somasi">Somasi</option>
                            <option value="Hukum">Hukum</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Informasi Jaminan</label>
                        <textarea name="collateral_info" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan Awal</label>
                        <textarea name="first_notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('klpk.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
