@extends('layouts.app')
@section('title', 'Edit Jabatan')

@section('content')
    <div class="container mt-4">
        <h3 class="fw-bold mb-3">✏️ Edit Jabatan</h3>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="POST" action="{{ route('jabatan.update', $jabatan->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                        <input type="text" name="nama_jabatan" id="nama_jabatan"
                            class="form-control @error('nama_jabatan') is-invalid @enderror"
                            value="{{ old('nama_jabatan', $jabatan->nama_jabatan) }}" required>
                        @error('nama_jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="bagian_id" class="form-label">Bagian</label>
                        <select name="bagian_id" id="bagian_id" class="form-select @error('bagian_id') is-invalid @enderror"
                            required>
                            @foreach ($bagians as $bagian)
                                <option value="{{ $bagian->id }}"
                                    {{ $jabatan->bagian_id == $bagian->id ? 'selected' : '' }}>
                                    {{ $bagian->nama_bagian }}
                                </option>
                            @endforeach
                        </select>
                        @error('bagian_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('jabatan.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-success">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
