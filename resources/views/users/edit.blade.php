@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0 text-primary">Edit User</h3>
                <small class="text-muted">Perbarui data pengguna, role, dan permission</small>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        {{-- CARD --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body px-4 py-4">

                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row g-3">

                        {{-- Nama --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                                required>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                        </div>

                        {{-- Telepon --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $user->phone) }}">
                        </div>

                        {{-- Jabatan --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jabatan</label>
                            <input type="text" name="position" class="form-control"
                                value="{{ old('position', $user->position) }}">
                        </div>

                        {{-- Status --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status Akun</label>
                            <select name="active" class="form-select">
                                <option value="1" {{ $user->active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$user->active ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        {{-- Role Assign --}}
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Hak Akses (Role)</label>
                            <div class="border rounded p-3">
                                @foreach ($roles as $role)
                                    <div class="form-check">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                            id="role_{{ $role->id }}" class="form-check-input"
                                            {{ $user->roles->contains($role) ? 'checked' : '' }}>
                                        <label for="role_{{ $role->id }}" class="form-check-label">
                                            {{ ucfirst($role->name) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>


                    {{-- TOGGLE PERMISSION --}}
                    <div class="mt-4">
                        <a class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" href="#collapsePermission">
                            <i class="fas fa-key me-1"></i> Kelola Permission Tambahan
                        </a>

                        <div class="collapse mt-3" id="collapsePermission">
                            @php
                                $grouped = $permissions->groupBy(fn($p) => explode(' ', $p->name)[0]);
                            @endphp

                            @foreach ($grouped as $group => $perms)
                                <div class="card border-0 shadow-sm mb-3">
                                    <div
                                        class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
                                        {{ strtoupper($group) }}
                                        <button type="button" class="btn btn-sm btn-link text-primary perm-check-all"
                                            data-group="{{ $group }}">
                                            Pilih Semua
                                        </button>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            @foreach ($perms as $permission)
                                                <div class="col-md-3 col-sm-6 mb-2">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input permission-item"
                                                            name="permissions[]" data-group="{{ $group }}"
                                                            id="perm_{{ $permission->id }}"
                                                            value="{{ $permission->name }}"
                                                            {{ $user->permissions->contains($permission) ? 'checked' : '' }}>
                                                        <label for="perm_{{ $permission->id }}" class="form-check-label">
                                                            {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>


                    {{-- Password Change --}}
                    <div class="mt-4">
                        <a class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" href="#collapsePassword">
                            <i class="fas fa-lock me-1"></i> Ubah Password
                        </a>

                        <div class="collapse mt-3" id="collapsePassword">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Password Baru</label>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Masukkan password baru">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Submit --}}
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection


{{-- ✅ Script Perm Group --}}
@push('scripts')
    <script>
        document.querySelectorAll('.perm-check-all').forEach(btn => {
            btn.addEventListener('click', function() {
                const group = this.dataset.group;
                const items = document.querySelectorAll(`.permission-item[data-group="${group}"]`);
                const allChecked = [...items].every(c => c.checked);
                items.forEach(c => c.checked = !allChecked);
                this.textContent = allChecked ? 'Pilih Semua' : 'Batal';
            });
        });
    </script>
@endpush
