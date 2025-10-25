@extends('layouts.app')

@section('title', 'Edit Pegawai')

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-gradient" style="background: linear-gradient(90deg, #dfe9ff, #b9d6ff); color:#000;">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-edit me-2 text-warning"></i> Edit Data Pegawai & Riwayat Jabatan
                </h5>
            </div>

            <div class="card-body">
                {{-- FORM DATA PEGAWAI --}}
                <form method="POST" action="{{ route('pegawai.update', $pegawai->id) }}" enctype="multipart/form-data"
                    id="pegawai-form">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIP</label>
                            <input type="text" name="nip" value="{{ old('nip', $pegawai->nip) }}"
                                class="form-control shadow-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $pegawai->nama) }}"
                                class="form-control shadow-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" value="{{ old('email', $pegawai->email) }}"
                                class="form-control shadow-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $pegawai->no_hp) }}"
                                class="form-control shadow-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status Kerja</label>
                            <select name="status_kerja" class="form-select shadow-sm" required>
                                <option value="Tetap" {{ $pegawai->status_kerja == 'Tetap' ? 'selected' : '' }}>Tetap
                                </option>
                                <option value="Kontrak" {{ $pegawai->status_kerja == 'Kontrak' ? 'selected' : '' }}>Kontrak
                                </option>
                                <option value="Magang" {{ $pegawai->status_kerja == 'Magang' ? 'selected' : '' }}>Magang
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" value="{{ $pegawai->tanggal_masuk }}"
                                class="form-control shadow-sm" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea name="alamat" class="form-control shadow-sm" rows="2">{{ $pegawai->alamat }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jabatan Aktif</label>
                            <select name="jabatan_id" class="form-select shadow-sm" required>
                                @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id }}"
                                        {{ $pegawai->jabatan_id == $jabatan->id ? 'selected' : '' }}>
                                        {{ $jabatan->nama_jabatan }} ({{ $jabatan->bagian->nama_bagian }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Foto Pegawai</label>
                            <input type="file" name="foto" class="form-control shadow-sm" accept="image/*">
                            @if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto))
                                <img src="{{ asset('storage/' . $pegawai->foto) }}" class="mt-2 rounded shadow-sm"
                                    width="120">
                            @endif
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-warning text-white px-4">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

                <hr class="my-4">

                {{-- RIWAYAT JABATAN --}}
                <h5 class="fw-bold mb-3 mt-4"><i class="fas fa-briefcase text-primary me-2"></i> Riwayat Jabatan</h5>
                <ul class="timeline list-unstyled" id="riwayat-list">
                    @forelse ($pegawai->riwayatJabatans as $r)
                        <li class="timeline-item mb-4" data-id="{{ $r->id }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $r->jabatan->nama_jabatan }}</h6>
                                    <small class="text-muted">{{ $r->jabatan->bagian->nama_bagian }}</small>
                                    @if ($r->keterangan)
                                        <p class="small text-muted mb-0 mt-1">
                                            <i class="fas fa-info-circle me-1"></i>{{ $r->keterangan }}
                                        </p>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <small class="text-secondary">
                                        {{ \Carbon\Carbon::parse($r->tanggal_mulai)->translatedFormat('d M Y') }}
                                        →
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

                {{-- TAMBAH RIWAYAT --}}
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="fas fa-plus-circle text-primary me-2"></i> Tambah Riwayat Jabatan
                        </h6>
                        <form id="form-riwayat">
                            @csrf
                            <div class="row g-2 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label small text-secondary">Jabatan</label>
                                    <select name="jabatan_id" class="form-select" required>
                                        <option value="">-- Pilih Jabatan --</option>
                                        @foreach ($jabatans as $jabatan)
                                            <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}
                                                ({{ $jabatan->bagian->nama_bagian }})</option>
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
                                <div class="col-md-2 text-end">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <i class="fas fa-save me-1"></i> Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="mt-2">
                                <input type="text" name="keterangan" class="form-control"
                                    placeholder="Keterangan (opsional)">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- STYLES --}}
    <style>
        .timeline {
            position: relative;
            margin-left: 20px;
        }

        .timeline::before {
            content: "";
            position: absolute;
            left: 6px;
            top: 0;
            width: 2px;
            height: 100%;
            background: #d0d7e1;
        }

        .timeline-item {
            padding-left: 25px;
            position: relative;
        }

        .timeline-item::before {
            content: "";
            position: absolute;
            left: -2px;
            top: 12px;
            width: 10px;
            height: 10px;
            background: #0d6efd;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #cfe2ff;
        }

        .timeline-item:hover {
            background: #f8f9fa;
            border-radius: 6px;
        }
    </style>

    {{-- SCRIPT --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('form-riwayat');
                const list = document.getElementById('riwayat-list');

                // Tambah Riwayat
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const formData = new FormData(form);

                    const res = await fetch(`{{ url("pegawai/{$pegawai->id}/riwayat") }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });
                    const data = await res.json();

                    if (data.success) {
                        const r = data.riwayat;
                        const li = document.createElement('li');
                        li.className = 'timeline-item mb-4';
                        li.dataset.id = r.id;
                        li.innerHTML = `
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold mb-0">${r.jabatan.nama_jabatan}</h6>
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
                    }
                });

                // Hapus Riwayat
                list.addEventListener('click', async (e) => {
                    const btn = e.target.closest('.delete-riwayat');
                    if (!btn) return;
                    if (!confirm('Hapus riwayat ini?')) return;
                    const item = btn.closest('.timeline-item');
                    const id = item.dataset.id;

                    const res = await fetch(`{{ url("pegawai/{$pegawai->id}/riwayat/") }}` + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await res.json();
                    if (data.success) item.remove();
                });
            });
        </script>
    @endpush
@endsection
