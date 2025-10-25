@extends('layouts.app')
@section('title', 'Riwayat Jabatan - ' . $pegawai->nama)

@section('content')
    <div class="container mt-4">
        <h4 class="mb-3">Riwayat Jabatan: {{ $pegawai->nama }}</h4>
        <a href="{{ route('pegawai.index') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>

        @if ($riwayats->isEmpty())
            <p class="text-muted fst-italic">Belum ada riwayat jabatan.</p>
        @else
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Jabatan</th>
                        <th>Bagian</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($riwayats as $r)
                        <tr>
                            <td>{{ $r->jabatan?->nama_jabatan }}</td>
                            <td>{{ $r->jabatan?->bagian?->nama_bagian }}</td>
                            <td>{{ $r->tanggal_mulai }}</td>
                            <td>{{ $r->tanggal_selesai ?? 'Sekarang' }}</td>
                            <td>{{ $r->keterangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
