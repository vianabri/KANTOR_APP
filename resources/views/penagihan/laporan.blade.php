@extends('layouts.app')
@section('title', 'Laporan Penagihan Staf')

@section('content')
    <div class="container-fluid">

        <div class="text-center mb-3">
            <h4 class="fw-bold text-uppercase text-primary">Laporan Penagihan Staf Lapangan</h4>

            <form method="GET" class="row g-2 justify-content-center mb-2">
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
                            <option value="{{ $b }}"
                                {{ (int) request('bulan', $bulan) === $b ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select name="tahun" class="form-select">
                        @foreach (range(now()->year - 3, now()->year) as $y)
                            <option value="{{ $y }}"
                                {{ (int) request('tahun', $tahun) === (int) $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <input type="text" name="wilayah" class="form-control" placeholder="Filter wilayah"
                        value="{{ $wilayah }}">
                </div>

                <div class="col-auto">
                    <button class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Terapkan
                    </button>
                </div>
            </form>

            @if (request('filter') === 'followup')
                <div class="alert alert-warning shadow-sm text-center fw-semibold py-2">
                    Menampilkan Follow-Up yang terlambat ⚠
                </div>
            @endif

            <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('penagihan.export.excel', request()->query()) }}"
                    class="btn btn-success btn-sm shadow-sm px-3 rounded-3">
                    <i class="fas fa-file-excel me-1"></i> Excel
                </a>
                <a href="{{ route('penagihan.export.pdf', request()->query()) }}"
                    class="btn btn-danger btn-sm shadow-sm px-3 rounded-3">
                    <i class="fas fa-file-pdf me-1"></i> PDF
                </a>
            </div>

            <hr class="border-3 border-primary opacity-75" style="width:240px;margin:auto;">
        </div>

        {{-- SUMMARY KPI --}}
        <div class="row row-cols-1 row-cols-md-6 g-3 mb-4">
            <div class="col">
                <div class="card shadow-sm border-0 text-center py-3 h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Kunjungan</h6>
                        <h4 class="fw-bold text-primary">{{ $totalVisit }}</h4>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm border-0 text-center py-3 h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Ditagih</h6>
                        <h5 class="fw-bold">Rp {{ number_format($totalTagih, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm border-0 text-center py-3 h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Dibayar</h6>
                        <h5 class="fw-bold text-success">Rp {{ number_format($totalBayar, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm border-0 text-center py-3 h-100">
                    <div class="card-body">
                        <h6 class="text-muted">% Keberhasilan</h6>
                        <h4
                            class="fw-bold {{ $successRate >= 60 ? 'text-success' : ($successRate >= 30 ? 'text-warning' : 'text-danger') }}">
                            {{ $successRate }}%</h4>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm border-0 text-center py-3 h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Janji Bayar</h6>
                        <h4 class="fw-bold text-dark">{{ $janjiCount }}</h4>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm border-0 text-center py-3 h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Tren</h6>
                        <h4
                            class="fw-bold {{ $trend === 'NAIK' ? 'text-danger' : ($trend === 'TURUN' ? 'text-success' : 'text-secondary') }}">
                            {{ $trend }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- KENDALA TOP --}}
        @if ($kendalaRank->count())
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold text-secondary mb-2">Kendala Teratas</h6>
                    <ul class="list-group list-group-flush">
                        @foreach ($kendalaRank as $k => $c)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $k ?: 'Tidak diisi' }}</span>
                                <span class="badge bg-warning text-dark">{{ $c }} kasus</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- TABEL DETAIL --}}
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body px-4 py-4">
                @if ($items->count())
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
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
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($items as $r)
                                    <tr
                                        class="{{ $r->status === 'BAYAR' ? 'table-success' : ($r->status === 'GAGAL' ? 'table-danger-subtle' : '') }}">
                                        <td>{{ $r->tanggal_kunjungan->format('d/m/Y') }}</td>
                                        <td class="fw-semibold">{{ $r->cif }}</td>
                                        <td class="text-start">{{ $r->nama_anggota }}</td>
                                        <td>{{ $r->wilayah }}</td>
                                        <td>Rp {{ number_format($r->nominal_ditagih, 0, ',', '.') }}</td>
                                        <td class="fw-bold text-success">Rp
                                            {{ number_format($r->nominal_dibayar, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($r->status === 'BAYAR')
                                                <span class="badge bg-success">BAYAR</span>
                                            @elseif ($r->status === 'JANJI')
                                                <span class="badge bg-info text-dark">JANJI</span>
                                            @else
                                                <span class="badge bg-danger">GAGAL</span>
                                            @endif
                                        </td>
                                        <td>{{ optional($r->tanggal_janji)?->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ $r->kendala ?? '-' }}</td>
                                        <td>{{ $r->user->name }}</td>
                                        <td class="text-nowrap">
                                            @if ($r->status === 'JANJI')
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#fu{{ $r->id }}">
                                                    <i class="fas fa-phone"></i> Follow-Up
                                                </button>
                                                @if ($r->nomor_wa)
                                                    <a href="https://wa.me/{{ $r->nomor_wa }}?text={{ urlencode(
                                                        'Halo ' .
                                                            $r->nama_anggota .
                                                            ', saya dari CU Likku Aba. ' .
                                                            'Mengonfirmasi janji pembayaran pada ' .
                                                            (optional($r->tanggal_janji)?->format('d/m/Y') ?? '-') .
                                                            '. ' .
                                                            'Apakah dapat diproses hari ini? Terima kasih 🙏',
                                                    ) }}"
                                                        target="_blank" class="btn btn-sm btn-success">
                                                        <i class="fab fa-whatsapp"></i>
                                                    </a>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- MODAL FOLLOW-UP --}}
                                    <div class="modal fade" id="fu{{ $r->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST"
                                                    action="{{ route('penagihan.followup.store', $r->id) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Follow-Up: {{ $r->nama_anggota }}
                                                            ({{ $r->cif }})
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-2 text-muted small">
                                                            Ditagih: <strong>Rp
                                                                {{ number_format($r->nominal_ditagih, 0, ',', '.') }}</strong>
                                                            —
                                                            Dibayar: <strong>Rp
                                                                {{ number_format($r->nominal_dibayar, 0, ',', '.') }}</strong>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Hasil</label>
                                                            <select name="hasil" class="form-select"
                                                                id="hasil{{ $r->id }}" required>
                                                                <option value="BAYAR">BAYAR</option>
                                                                <option value="JANJI" selected>JANJI</option>
                                                                <option value="GAGAL">GAGAL</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3 d-none" id="bayar{{ $r->id }}">
                                                            <label class="form-label fw-semibold">Nominal Dibayar</label>
                                                            <input type="number" name="nominal_dibayar"
                                                                class="form-control" min="0">
                                                        </div>

                                                        <div class="mb-3 d-none" id="janji{{ $r->id }}">
                                                            <label class="form-label fw-semibold">Tanggal Janji
                                                                Baru</label>
                                                            <input type="date" name="tanggal_janji"
                                                                class="form-control"
                                                                value="{{ date('Y-m-d', strtotime('+3 days')) }}">
                                                        </div>

                                                        <div class="mb-3 d-none" id="gagal{{ $r->id }}">
                                                            <label class="form-label fw-semibold">Kendala</label>
                                                            <input type="text" name="kendala" class="form-control"
                                                                placeholder="Alasan gagal bayar">
                                                        </div>

                                                        <div class="mb-2">
                                                            <label class="form-label fw-semibold">Catatan
                                                                (opsional)</label>
                                                            <input type="text" name="catatan" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                        <button class="btn btn-primary" type="submit">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- SCRIPT TOGGLE FIELD --}}
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const sel = document.getElementById('hasil{{ $r->id }}');
                                            const bayar = document.getElementById('bayar{{ $r->id }}');
                                            const janji = document.getElementById('janji{{ $r->id }}');
                                            const gagal = document.getElementById('gagal{{ $r->id }}');

                                            function refresh() {
                                                bayar.classList.toggle('d-none', sel.value !== 'BAYAR');
                                                janji.classList.toggle('d-none', sel.value !== 'JANJI');
                                                gagal.classList.toggle('d-none', sel.value !== 'GAGAL');
                                            }
                                            sel.addEventListener('change', refresh);
                                            refresh();
                                        });
                                    </script>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                        <p>Belum ada data penagihan pada periode ini.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
