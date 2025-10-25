@php
    $isEdit = isset($pegawai) && $pegawai->exists;
@endphp

<form method="POST" action="{{ $isEdit ? route('pegawai.update', $pegawai->id) : route('pegawai.store') }}"
    enctype="multipart/form-data" class="row g-3">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    {{-- NIP --}}
    <div class="col-md-6">
        <label for="nip" class="form-label fw-semibold text-secondary">
            <i class="fas fa-id-card me-1 text-primary"></i> NIP
        </label>
        <input type="text" id="nip" name="nip"
            class="form-control elegant-input @error('nip') is-invalid @enderror"
            value="{{ old('nip', $pegawai->nip ?? '') }}" required>
        @error('nip')
            <div class="invalid-feedback mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Nama --}}
    <div class="col-md-6">
        <label for="nama" class="form-label fw-semibold text-secondary">
            <i class="fas fa-user me-1 text-primary"></i> Nama Pegawai
        </label>
        <input type="text" id="nama" name="nama"
            class="form-control elegant-input @error('nama') is-invalid @enderror"
            value="{{ old('nama', $pegawai->nama ?? '') }}" required>
        @error('nama')
            <div class="invalid-feedback mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Jabatan --}}
    <div class="col-md-6">
        <label for="jabatan_id" class="form-label fw-semibold text-secondary">
            <i class="fas fa-briefcase me-1 text-primary"></i> Jabatan
        </label>
        <select id="jabatan_id" name="jabatan_id"
            class="form-select elegant-input @error('jabatan_id') is-invalid @enderror" required>
            <option value="">-- Pilih Jabatan --</option>
            @foreach ($jabatans as $jabatan)
                <option value="{{ $jabatan->id }}"
                    {{ old('jabatan_id', $pegawai->jabatan_id ?? '') == $jabatan->id ? 'selected' : '' }}>
                    {{ $jabatan->nama_jabatan }} ({{ $jabatan->bagian->nama_bagian }})
                </option>
            @endforeach
        </select>
        @error('jabatan_id')
            <div class="invalid-feedback mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Tanggal Masuk --}}
    <div class="col-md-6">
        <label for="tanggal_masuk" class="form-label fw-semibold text-secondary">
            <i class="fas fa-calendar-day me-1 text-primary"></i> Tanggal Masuk
        </label>
        <input type="date" id="tanggal_masuk" name="tanggal_masuk" class="form-control elegant-input"
            value="{{ old('tanggal_masuk', $pegawai->tanggal_masuk ?? '') }}">
    </div>

    {{-- Status Kerja --}}
    <div class="col-md-6">
        <label for="status_kerja" class="form-label fw-semibold text-secondary">
            <i class="fas fa-user-check me-1 text-primary"></i> Status Kerja
        </label>
        <select id="status_kerja" name="status_kerja" class="form-select elegant-input">
            @foreach (['Tetap', 'Kontrak', 'Magang'] as $status)
                <option value="{{ $status }}"
                    {{ old('status_kerja', $pegawai->status_kerja ?? 'Tetap') == $status ? 'selected' : '' }}>
                    {{ $status }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Email --}}
    <div class="col-md-6">
        <label for="email" class="form-label fw-semibold text-secondary">
            <i class="fas fa-envelope me-1 text-primary"></i> Email
        </label>
        <input type="email" id="email" name="email" class="form-control elegant-input"
            value="{{ old('email', $pegawai->email ?? '') }}">
    </div>

    {{-- No HP --}}
    <div class="col-md-6">
        <label for="no_hp" class="form-label fw-semibold text-secondary">
            <i class="fas fa-phone-alt me-1 text-primary"></i> No. HP
        </label>
        <input type="text" id="no_hp" name="no_hp" class="form-control elegant-input"
            value="{{ old('no_hp', $pegawai->no_hp ?? '') }}">
    </div>

    {{-- Alamat --}}
    <div class="col-12">
        <label for="alamat" class="form-label fw-semibold text-secondary">
            <i class="fas fa-map-marker-alt me-1 text-primary"></i> Alamat
        </label>
        <textarea id="alamat" name="alamat" rows="3" class="form-control elegant-input">{{ old('alamat', $pegawai->alamat ?? '') }}</textarea>
    </div>

    {{-- Foto --}}
    <div class="col-12">
        <label class="form-label fw-semibold text-secondary">
            <i class="fas fa-image me-1 text-primary"></i> Foto Pegawai
        </label>
        <div class="d-flex align-items-center gap-3">
            <img id="preview-foto"
                src="{{ $pegawai->foto ?? false ? asset('storage/' . $pegawai->foto) : asset('images/default-user.png') }}"
                alt="Foto Pegawai" class="rounded border shadow-sm" width="90" height="90"
                style="object-fit: cover;">
            <div class="flex-fill">
                <input type="file" id="foto" name="foto" class="form-control elegant-input" accept="image/*"
                    onchange="previewImage(event)">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
            </div>
        </div>
    </div>

    {{-- =============================
        RIWAYAT JABATAN PEGAWAI
    ============================== --}}
    <div class="col-12 mt-5">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header fw-semibold py-3"
                style="background: linear-gradient(90deg, #e0ebff, #d0d9ff); color: #333;">
                <i class="fas fa-history me-2 text-primary"></i> Riwayat Jabatan Pegawai
            </div>

            <div class="card-body" id="riwayat-container">
                @php
                    $riwayats = old('riwayat', $pegawai->riwayatJabatans ?? []);
                @endphp

                @forelse ($riwayats as $i => $r)
                    <div class="riwayat-item border p-3 mb-3 rounded-3 position-relative bg-light">
                        <input type="hidden" name="riwayat[{{ $i }}][id]"
                            value="{{ $r['id'] ?? ($r->id ?? '') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label text-secondary small">Jabatan</label>
                                <select name="riwayat[{{ $i }}][jabatan_id]"
                                    class="form-select elegant-input" required>
                                    <option value="">-- Pilih Jabatan --</option>
                                    @foreach ($jabatans as $jabatan)
                                        <option value="{{ $jabatan->id }}"
                                            {{ ($r['jabatan_id'] ?? ($r->jabatan_id ?? '')) == $jabatan->id ? 'selected' : '' }}>
                                            {{ $jabatan->nama_jabatan }} ({{ $jabatan->bagian->nama_bagian }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label text-secondary small">Tanggal Mulai</label>
                                <input type="date" name="riwayat[{{ $i }}][tanggal_mulai]"
                                    class="form-control elegant-input"
                                    value="{{ $r['tanggal_mulai'] ?? ($r->tanggal_mulai ?? '') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label text-secondary small">Tanggal Selesai</label>
                                <input type="date" name="riwayat[{{ $i }}][tanggal_selesai]"
                                    class="form-control elegant-input"
                                    value="{{ $r['tanggal_selesai'] ?? ($r->tanggal_selesai ?? '') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label text-secondary small">Keterangan</label>
                                <input type="text" name="riwayat[{{ $i }}][keterangan]"
                                    class="form-control elegant-input"
                                    value="{{ $r['keterangan'] ?? ($r->keterangan ?? '') }}">
                            </div>
                        </div>

                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 mt-2 me-2"
                            onclick="this.closest('.riwayat-item').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @empty
                    <p class="text-muted fst-italic small">Belum ada riwayat jabatan yang ditambahkan.</p>
                @endforelse
            </div>

            <div class="card-footer text-end bg-light">
                <button type="button" id="add-riwayat" class="btn btn-outline-primary btn-sm px-3 rounded-pill">
                    <i class="fas fa-plus me-1"></i> Tambah Riwayat Jabatan
                </button>
            </div>
        </div>
    </div>

    {{-- Tombol --}}
    <div class="d-flex justify-content-end mt-4">
        <a href="{{ route('pegawai.index') }}"
            class="btn btn-outline-secondary me-3 px-4 py-2 rounded-pill shadow-sm">
            <i class="fas fa-times me-1"></i> Batal
        </a>
        <button type="submit" class="btn btn-gradient-primary px-4 py-2 rounded-pill shadow-sm">
            <i class="fas fa-save me-1"></i> {{ $isEdit ? 'Perbarui Data' : 'Simpan Data' }}
        </button>
    </div>
</form>

@push('scripts')
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('preview-foto').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('riwayat-container');
            document.getElementById('add-riwayat').addEventListener('click', () => {
                const index = container.querySelectorAll('.riwayat-item').length;
                const html = `
        <div class="riwayat-item border p-3 mb-3 rounded-3 position-relative bg-light">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label text-secondary small">Jabatan</label>
                    <select name="riwayat[${index}][jabatan_id]" class="form-select elegant-input" required>
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach ($jabatans as $jabatan)
                            <option value="{{ $jabatan->id }}">
                                {{ $jabatan->nama_jabatan }} ({{ $jabatan->bagian->nama_bagian }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary small">Tanggal Mulai</label>
                    <input type="date" name="riwayat[${index}][tanggal_mulai]" class="form-control elegant-input">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary small">Tanggal Selesai</label>
                    <input type="date" name="riwayat[${index}][tanggal_selesai]" class="form-control elegant-input">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-secondary small">Keterangan</label>
                    <input type="text" name="riwayat[${index}][keterangan]" class="form-control elegant-input">
                </div>
            </div>
            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 mt-2 me-2"
                onclick="this.closest('.riwayat-item').remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>`;
                container.insertAdjacentHTML('beforeend', html);
            });
        });
    </script>
@endpush
