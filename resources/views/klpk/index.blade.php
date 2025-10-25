@extends('layouts.app')

@section('title', 'Daftar KLPK')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fas fa-user-slash me-2 text-primary"></i>Daftar Anggota KLPK</h4>
        @can('manage kredit lalai')
            <a href="{{ route('klpk.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Data
            </a>
        @endcan
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            {{-- Filter Status --}}
            <div class="mb-2">
                <label class="form-label small">Filter Status Penagihan:</label>
                <select id="filterStatus" class="form-select form-select-sm" style="width:auto; display:inline-block;">
                    <option value="">Semua</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Somasi">Somasi</option>
                    <option value="Hukum">Hukum</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>

            <table id="klpkTable" class="table table-hover table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>CIF</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Telepon</th>
                        <th>Data Kredit</th>
                        <th>Petugas</th>
                        <th>Status</th>
                        <th width="170">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $m)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $m->cif_number }}</td>
                            <td>{{ $m->full_name }}</td>
                            <td>{{ $m->id_number }}</td>
                            <td>{{ $m->phone_number }}</td>

                            {{-- Kredit Detail --}}
                            <td>
                                {{-- Sisa Pokok --}}
                                <strong>Rp {{ number_format($m->principal_remaining, 0, ',', '.') }}</strong><br>

                                {{-- Pokok Saat KLPK --}}
                                <small class="text-muted">Awal: Rp
                                    {{ number_format($m->principal_start, 0, ',', '.') }}</small><br>

                                {{-- Progress Persentase --}}
                                @php
                                    $progress =
                                        $m->principal_start > 0
                                            ? round(
                                                (($m->principal_start - $m->principal_remaining) /
                                                    $m->principal_start) *
                                                    100,
                                                2,
                                            )
                                            : 0;
                                @endphp

                                <span class="badge bg-info">{{ $progress }}%</span><br>

                                {{-- Aging --}}
                                @if ($m->last_payment_date)
                                    <span
                                        class="badge 
                                @if ($m->days_aging < 30) bg-success
                                @elseif($m->days_aging <= 90) bg-warning text-dark
                                @else bg-danger @endif">
                                        {{ $m->days_aging }} hari
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Belum Bayar</span>
                                @endif
                            </td>

                            <td>{{ $m->officer_in_charge }}</td>

                            {{-- Status Kredit --}}
                            <td>
                                <span
                                    class="badge 
                            @if ($m->status_penagihan == 'Aktif') bg-success
                            @elseif($m->status_penagihan == 'Somasi') bg-warning text-dark
                            @elseif($m->status_penagihan == 'Hukum') bg-danger
                            @else bg-secondary @endif">
                                    {{ $m->status_penagihan }}
                                </span>
                            </td>

                            {{-- ACTION BUTTONS --}}
                            <td class="text-nowrap">
                                @can('view all kredit lalai')
                                    <a href="{{ route('klpk.payment.history', $m->klpk_id) }}"
                                        class="btn btn-sm btn-outline-primary" title="Histori Pembayaran">
                                        <i class="fas fa-receipt"></i>
                                    </a>
                                @endcan

                                @can('manage kredit lalai')
                                    <a href="{{ route('klpk.followup.create', $m->klpk_id) }}"
                                        class="btn btn-sm btn-outline-warning" title="Tindak Lanjut">
                                        <i class="fas fa-phone"></i>
                                    </a>

                                    <a href="{{ route('klpk.payment.create', $m->klpk_id) }}"
                                        class="btn btn-sm btn-outline-success" title="Input Pembayaran">
                                        <i class="fas fa-money-bill"></i>
                                    </a>
                                @endcan
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Belum ada data KLPK.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            let table = $('#klpkTable').DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                }
            });

            // Filter Status kolom ke-7
            $('#filterStatus').on('change', function() {
                table.column(7).search(this.value).draw();
            });
        });
    </script>
@endpush
