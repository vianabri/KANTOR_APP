@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0">Edit User</h3>
                <small class="text-muted">Perbarui data pengguna, role, dan permission</small>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        {{-- CARD WRAPPER --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- Nama --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        {{-- Telepon --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', $user->phone) }}">
                            </div>
                        </div>

                        {{-- Jabatan --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jabatan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-briefcase"></i></span>
                                <input type="text" name="position" class="form-control"
                                    value="{{ old('position', $user->position) }}">
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status Akun</label>
                            <select name="active" class="form-select">
                                <option value="1" {{ $user->active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$user->active ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        {{-- Role --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hak Akses (Role)</label>
                            <div class="border rounded p-3">
                                @foreach ($roles as $role)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]"
                                            id="role_{{ $role->id }}" value="{{ $role->name }}"
                                            {{ $user->roles->contains($role) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            {{ ucfirst($role->name) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- PERMISSIONS --}}
                        <div class="col-12">
                            <a class="btn btn-outline-primary btn-sm mt-3" data-bs-toggle="collapse"
                                href="#collapsePermission" role="button" aria-expanded="false"
                                aria-controls="collapsePermission">
                                <i class="fas fa-key me-1"></i> Kelola Permission
                            </a>

                            <div class="collapse mt-3" id="collapsePermission">
                                <div class="border rounded p-3 bg-light">
                                    <div class="row">
                                        @foreach ($permissions as $permission)
                                            <div class="col-md-3 col-sm-6 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="permissions[]"
                                                        id="perm_{{ $permission->id }}" value="{{ $permission->name }}"
                                                        {{ $user->permissions->contains($permission) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                        {{ ucfirst($permission->name) }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- UBAH PASSWORD --}}
                        <div class="col-12">
                            <a class="btn btn-outline-secondary btn-sm mt-3" data-bs-toggle="collapse"
                                href="#collapsePassword" role="button" aria-expanded="false"
                                aria-controls="collapsePassword">
                                <i class="fas fa-lock me-1"></i> Ubah Password
                            </a>

                            <div class="collapse mt-3" id="collapsePassword">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Password Baru</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-key"></i></span>
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Masukkan password baru">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Konfirmasi Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-key"></i></span>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                placeholder="Ulangi password baru">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
