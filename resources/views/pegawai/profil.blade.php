@extends('layouts.app')
@section('title', 'Profil Pegawai')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        {{-- KOLOM KIRI — PROFIL PEGAWAI --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center p-4">
                <img src="{{ $pegawai->foto ? asset('storage/' . $pegawai->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($pegawai->nama) . '&background=0D6EFD&color=fff' }}"
                     class="rounded-circle mb-3 shadow-sm" width="180" height="180" style="object-fit: cover;">
                <h5 class="fw-bold mb-1">{{ $pegawai->nama }}</h5>
                <p class="text-muted mb-1">{{ $pegawai->jabatan->nama_jabatan ?? '-' }}</p>
                <p class="small text-secondary">{{ $pegawai->jabatan->bagian->nama_bagian ?? '-' }}</p>
            </div>
        </div>

        {{-- KOLOM KANAN — DATA PEGAWAI & RIWAYAT --}}
        <div class="col-md-8">

            {{-- DATA PEGAWAI --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fas fa-id-card me-2 text-primary"></i> Data Pegawai</h5>
                    <table class="table table-borderless small">
                        <tr><th>NIP</th><td>{{ $pegawai->nip }}</td></tr>
                        <tr><th>Email</th><td>{{ $pegawai->email ?? '—' }}</td></tr>
                        <tr><th>No. HP</th><td>{{ $pegawai->no_hp ?? '—' }}</td></tr>
                        <tr><th>Status Kerja</th><td>{{ $pegawai->status_kerja }}</td></tr>
                        <tr>
                            <th>Tanggal Masuk</th>
                            <td>{{ $pegawai->tanggal_masuk ? \Carbon\Carbon::parse($pegawai->tanggal_masuk)->translatedFormat('d M Y') : '—' }}</td>
                        </tr>
                        <tr><th>Alamat</th><td>{{ $pegawai->alamat ?? '—' }}</td></tr>
                    </table>
                </div>
            </div>

            {{-- RIWAYAT JABATAN --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fas fa-briefcase me-2 text-primary"></i> Riwayat Jabatan</h5>
                    <ul class="timeline list-unstyled" id="riwayat-list">
                        @forelse ($riwayats as $r)
                            @php
                                $warna = match($r->jenis_perubahan) {
                                    'Promosi' => 'success',
                                    'Mutasi' => 'primary',
                                    'Demosi' => 'danger',
                                    'Penugasan' => 'warning',
                                    default => 'secondary',
                                };
                                $ikon = match($r->jenis_perubahan) {
                                    'Promosi' => 'fa-arrow-up',
                                    'Mutasi' => 'fa-arrows-alt-h',
                                    'Demosi' => 'fa-arrow-down',
                                    'Penugasan' => 'fa-briefcase',
                                    default => 'fa-circle',
                                };
                            @endphp
                            <li class="timeline-item mb-4 border-start border-3 border-{{ $warna }} ps-3" data-id="{{ $r->id }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-{{ $warna }}">
                                            <i class="fas {{ $ikon }} me-1"></i> {{ $r->jabatan->nama_jabatan }}
                                        </h6>
                                        <small class="text-muted">{{ $r->jabatan->bagian->nama_bagian }}</small>
                                        @if ($r->keterangan)
                                            <p class="small text-muted mb-0 mt-1">
                                                <i class="fas fa-info-circle me-1"></i>{{ $r->keterangan }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <small class="text-secondary">
                                            {{ \Carbon\Carbon::parse($r->tanggal_mulai)->translatedFormat('d M Y') }} →
                                            {{ $r->tanggal_selesai ? \Carbon\Carbon::parse($r->tanggal_selesai)->translatedFormat('d M Y') : 'Sekarang' }}
                                        </small>
                                        <button class="btn btn-sm btn-outline-danger delete-riwayat ms-2">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <p class="text-muted fst-italic">Belum ada riwayat jabatan.</p>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- FORM TAMBAH RIWAYAT --}}
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-plus-circle text-primary me-2"></i> Tambah Riwayat Jabatan
                    </h6>
                    <form id="form-riwayat">
                        @csrf
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small text-secondary">Jenis Perubahan</label>
                                <select name="jenis_perubahan" class="form-select">
                                    <option value="Promosi">Promosi</option>
                                    <option value="Mutasi">Mutasi</option>
                                    <option value="Demosi">Demosi</option>
                                    <option value="Penugasan">Penugasan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-secondary">Jabatan</label>
                                <select name="jabatan_id" class="form-select" required>
                                    <option value="">-- Pilih Jabatan --</option>
                                    @foreach ($jabatans as $jabatan)
                                        <option value="{{ $jabatan->id }}">
                                            {{ $jabatan->nama_jabatan }} ({{ $jabatan->bagian->nama_bagian }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-secondary">Mulai</label>
                                <input type="date" name="tanggal_mulai" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-secondary">Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control">
                            </div>
                        </div>
                        <div class="mt-2">
                            <input type="text" name="keterangan" class="form-control" placeholder="Keterangan (opsional)">
                        </div>
                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>

        </div>
    </div>
</div>

{{-- STYLE --}}
<style>
.timeline { position: relative; margin-left: 20px; }
.timeline::before {
    content: ""; position: absolute; left: 6px; top: 0; width: 2px;
    height: 100%; background: #d0d7e1;
}
.timeline-item { padding-left: 25px; position: relative; }
.timeline-item:hover { background: #f8f9fa; border-radius: 6px; }
</style>

{{-- SCRIPT --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-riwayat');
    const list = document.getElementById('riwayat-list');

    // === TAMBAH RIWAYAT ===
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);

        const res = await fetch(`{{ url('pegawai/' . $pegawai->id . '/riwayat') }}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        });

        const data = await res.json();
        if (data.success) {
            const r = data.riwayat;
            const warnaMap = {
                'Promosi': 'success', 'Mutasi': 'primary', 'Demosi': 'danger',
                'Penugasan': 'warning', 'Lainnya': 'secondary'
            };
            const ikonMap = {
                'Promosi': 'fa-arrow-up', 'Mutasi': 'fa-arrows-alt-h', 'Demosi': 'fa-arrow-down',
                'Penugasan': 'fa-briefcase', 'Lainnya': 'fa-circle'
            };

            const warna = warnaMap[r.jenis_perubahan] ?? 'secondary';
            const ikon = ikonMap[r.jenis_perubahan] ?? 'fa-circle';

            const li = document.createElement('li');
            li.className = `timeline-item mb-4 border-start border-3 border-${warna} ps-3`;
            li.dataset.id = r.id;
            li.innerHTML = `
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold mb-0 text-${warna}">
                            <i class="fas ${ikon} me-1"></i> ${r.jabatan.nama_jabatan}
                        </h6>
                        <small class="text-muted">${r.jabatan.bagian.nama_bagian}</small>
                        ${r.keterangan ? `<p class="small text-muted mb-0 mt-1"><i class="fas fa-info-circle me-1"></i>${r.keterangan}</p>` : ''}
                    </div>
                    <div class="text-end">
                        <small class="text-secondary">${r.tanggal_mulai} → ${r.tanggal_selesai ?? 'Sekarang'}</small>
                        <button class="btn btn-sm btn-outline-danger delete-riwayat ms-2"><i class="fas fa-trash"></i></button>
                    </div>
                </div>`;
            list.prepend(li);
            form.reset();
        } else {
            alert('Gagal menambah riwayat.');
        }
    });

    // === HAPUS RIWAYAT ===
    list.addEventListener('click', async (e) => {
        const btn = e.target.closest('.delete-riwayat');
        if (!btn) return;
        if (!confirm('Hapus riwayat ini?')) return;

        const item = btn.closest('.timeline-item');
        const id = item.dataset.id;

        const res = await fetch(`{{ url('pegawai/' . $pegawai->id . '/riwayat') }}/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });

        const data = await res.json();
        if (data.success) item.remove();
    });
});
</script>
@endpush
@endsection
