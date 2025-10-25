@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h4>Tambah Riwayat Jabatan untuk {{ $pegawai->nama }}</h4>
    <form action="{{ route('riwayat.store', $pegawai->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Jabatan</label>
            <select name="jabatan_id" class="form-select" required>
                @foreach($jabatans as $jab)
                    <option value="{{ $jab->id }}">
                        {{ $jab->nama_jabatan }} ({{ $jab->bagian->nama_bagian }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label>Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control" required>
            </div>
            <div class="col">
                <label>Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control">
            </div>
        </div>
        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control"></textarea>
        </div>
        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('riwayat.index', $pegawai->id) }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
