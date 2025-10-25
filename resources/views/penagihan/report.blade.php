@extends('layouts.app')
@section('title', 'Laporan Penagihan Staf')

@section('content')
    <div class="container-fluid">

        {{-- HEADER + FILTER --}}
        <div class="text-center mb-4">
            <h4 class="fw-bold text-uppercase text-primary">Laporan Penagihan Staf Lapangan</h4>
            <p class="text-muted">
                Periode: {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
            </p>

            <form method="GET" class="row g-2 justify-content-center">
                @if ($canViewAll)
                    <div class="col-auto">
                        <select name="staf_id" class="form-select">
                            <option value="0">Semua Staf</option>
                            @foreach ($stafList as $s)
                                <option value="{{ $s->id }}" {{ (int) $stafId === (int) $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-auto">
                    <select name="bulan" class="form-select">
                        @foreach (range(1, 12) as $b)
                            <option value="{{ $b }}" {{ (int) $bulan === $b ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <select name="tahun" class="form-select">
                        @foreach (range(now()->year - 3, now()->year) as $y)
                            <option value="{{ $y }}" {{ (int) $tahun === (int) $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <input type="text" name="wilayah" value="{{ request('wilayah') }}" class="form-control"
                        placeholder="Filter wilayah">
                </div>

                <div class="col-auto">
                    <button class="btn btn-primary shadow-sm">
                        <i class="fas fa-filter me-1"></i> Terapkan
                    </button>
                </div>
            </form>

            <hr class="border-primary opacity-75 mt-3" style="width:260px;margin:auto;">
        </div>


        {{-- DETAIL TABEL --}}
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body px-4 py-4">

                @if ($items->count())

                    {{-- Toolbar Export --}}
                    <div class="d-flex justify-content-end gap-2 mb-3">
                        <a href="{{ route('penagihan.export.excel', request()->query()) }}"
                            class="btn btn-success btn-sm rounded-pill shadow">
                            <i class="fas fa-file-excel me-1"></i> Excel
                        </a>
                        <a href="{{ route('penagihan.export.pdf', request()->query()) }}"
                            class="btn btn-danger btn-sm rounded-pill shadow">
                            <i class="fas fa-file-pdf me-1"></i> PDF
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle">
                            <thead class="bg-primary text-white text-center">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>CIF</th>
                                    <th>Nama Anggota</th>
                                    <th>Wilayah</th>
                                    <th>Ditagih</th>
                                    <th>Dibayar</th>
                                    <th>Status</th>
                                    <th>Janji Bayar</th>
                                    <th>Kendala</th>
                                    <th>Petugas</th>
                                </tr>
                            </thead>

                            <tbody class="text-center">
                                @foreach ($items as $r)
                                    <tr class="fw-semibold">
                                        <td>{{ $r->tanggal_kunjungan->format('d/m/Y') }}</td>

                                        <td class="text-primary fw-bold">{{ $r->cif }}</td>

                                        <td class="text-start">{{ $r->nama_anggota }}</td>
                                        <td>{{ $r->wilayah }}</td>

                                        <td class="text-primary fw-bold">
                                            Rp {{ number_format($r->nominal_ditagih, 0, ',', '.') }}
                                        </td>

                                        <td class="text-success fw-bold">
                                            Rp {{ number_format($r->nominal_dibayar, 0, ',', '.') }}
                                        </td>

                                        <td>
                                            @if ($r->status === 'BAYAR')
                                                <span class="badge bg-success rounded-pill px-3 py-2">BAYAR</span>
                                            @elseif ($r->status === 'JANJI')
                                                @if ($r->tanggal_janji && $r->tanggal_janji->isPast())
                                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                                        TERLAMBAT FOLLOW-UP
                                                    </span>
                                                    <br>
                                                    <button class="btn btn-sm btn-outline-primary rounded-pill mt-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalFollowUp{{ $r->id }}">
                                                        <i class="fas fa-headset me-1"></i> Follow-Up
                                                    </button>
                                                @else
                                                    <span
                                                        class="badge bg-info text-dark rounded-pill px-3 py-2">JANJI</span>
                                                @endif
                                            @else
                                                <span class="badge bg-danger rounded-pill px-3 py-2">GAGAL</span>
                                            @endif
                                        </td>

                                        <td>{{ optional($r->tanggal_janji)?->format('d/m/Y') ?? '-' }}</td>
                                        <td class="text-danger">{{ $r->kendala ?? '-' }}</td>
                                        <td class="text-primary fw-bold">{{ $r->user->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                        <p class="fs-6 fw-semibold">Belum ada data penagihan pada periode ini.</p>
                    </div>
                @endif

            </div>
        </div>

        {{-- MODAL FOLLOW-UP --}}
        @foreach ($items as $r)
            @if ($r->status === 'JANJI' && $r->tanggal_janji && $r->tanggal_janji->isPast())
                <div class="modal fade" id="modalFollowUp{{ $r->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('penagihan.followup.store', $r->id) }}" method="POST"
                            class="modal-content shadow wow slideInDown">
                            @csrf

                            <div class="modal-header bg-primary text-white">
                                <h6 class="modal-title fw-bold">Follow-Up Penagihan</h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <p class="small text-muted mb-2">
                                    Anggota: <strong>{{ $r->nama_anggota }}</strong><br>
                                    CIF: <strong class="text-primary">{{ $r->cif }}</strong><br>
                                    Wilayah: <strong>{{ $r->wilayah }}</strong>
                                </p>

                                <hr>

                                <label class="fw-semibold mb-1">Hasil Follow-Up</label>
                                <select name="hasil" class="form-select" id="hasilSelect{{ $r->id }}" required>
                                    <option value="">-- Pilih Hasil --</option>
                                    <option value="BAYAR">Bayar</option>
                                    <option value="JANJI">Janji Bayar Lagi</option>
                                    <option value="GAGAL">Gagal</option>
                                </select>

                                <div class="mt-3" id="nominalWrap{{ $r->id }}" style="display:none;">
                                    <label class="fw-semibold">Nominal Dibayar</label>
                                    <input type="number" min="1" name="nominal_dibayar" class="form-control"
                                        placeholder="Masukkan nominal">
                                </div>

                                <div class="mt-3" id="janjiWrap{{ $r->id }}" style="display:none;">
                                    <label class="fw-semibold">Tanggal Janji Baru</label>
                                    <input type="date" name="tanggal_janji" class="form-control">
                                </div>

                                <div class="mt-3" id="kendalaWrap{{ $r->id }}" style="display:none;">
                                    <label class="fw-semibold">Kendala</label>
                                    <input type="text" name="kendala" class="form-control"
                                        placeholder="Sebutkan kendala">
                                </div>

                                <div class="mt-3">
                                    <label class="fw-semibold">Catatan Tambahan (Opsional)</label>
                                    <textarea name="catatan" rows="3" class="form-control"></textarea>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-success rounded-pill px-4">Simpan</button>
                                <button type="button" class="btn btn-secondary rounded-pill px-3"
                                    data-bs-dismiss="modal">Batal</button>
                            </div>

                        </form>
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const sel = document.getElementById("hasilSelect{{ $r->id }}");
                        const bayar = document.getElementById("nominalWrap{{ $r->id }}");
                        const janji = document.getElementById("janjiWrap{{ $r->id }}");
                        const gagal = document.getElementById("kendalaWrap{{ $r->id }}");

                        function toggleFields() {
                            bayar.style.display = sel.value === "BAYAR" ? "" : "none";
                            janji.style.display = sel.value === "JANJI" ? "" : "none";
                            gagal.style.display = sel.value === "GAGAL" ? "" : "none";
                        }

                        sel.addEventListener("change", toggleFields);
                    });
                </script>
            @endif
        @endforeach

    </div>

    <style>
        table tbody tr:hover {
            background-color: #eef4ff !important;
            transition: 0.25s ease-in-out;
        }
    </style>

@endsection
