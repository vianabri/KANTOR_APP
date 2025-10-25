@extends('layouts.app')

@section('title', 'Input Tindak Lanjut KLPK')

@section('content')
    <h4 class="mb-3">
        <i class="fas fa-phone text-primary me-2"></i>
        Tindak Lanjut - {{ $member->full_name }}
    </h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('klpk.followup.store', $member->klpk_id) }}" method="POST">
                @csrf
                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Jenis Follow-Up *</label>
                        <select name="followup_type" class="form-select" required>
                            <option>Telpon</option>
                            <option>Whatsapp</option>
                            <option>Kunjungan</option>
                            <option>Email</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tanggal Follow-Up *</label>
                        <input type="date" name="followup_date" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Status Follow-Up</label>
                        <select name="followup_status" class="form-select">
                            <option value="">-</option>
                            <option>Janji Bayar</option>
                            <option>Tidak Ditemui</option>
                            <option>Menolak</option>
                            <option>Lainnya</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Follow-Up Berikutnya</label>
                        <input type="date" name="next_followup" class="form-control">
                    </div>

                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('klpk.followup') }}" class="btn btn-secondary me-2">Kembali</a>
                    <button class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
