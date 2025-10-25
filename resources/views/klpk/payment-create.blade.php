@extends('layouts.app')

@section('title', 'Input Pembayaran KLPK')

@section('content')
    <h4 class="mb-3">
        <i class="fas fa-money-bill-wave me-2 text-success"></i>
        Input Pembayaran - {{ $member->full_name }}
    </h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('klpk.payment.store', $member->klpk_id) }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Pembayaran</label>
                        <input type="date" name="payment_date" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nominal Pembayaran</label>
                        <input type="number" name="payment_amount" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Metode</label>
                        <select name="payment_method" class="form-select">
                            <option>Tunai</option>
                            <option>Transfer</option>
                            <option>Potong Gaji</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('klpk.index') }}" class="btn btn-secondary me-2">Kembali</a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
