@extends('layouts.app')
@section('title', 'Tambah Role')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-primary">Tambah Role</h3>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary shadow-sm px-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        {{-- Form Card --}}
        <div class="card border-0 shadow-lg rounded-3">
            <div class="card-body px-4 py-4">

                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf

                    {{-- Nama Role --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Role <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            placeholder="Contoh: Admin, Manager, Staff" value="{{ old('name') }}" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Permissions --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Hak Akses Role</label>

                        @php
                            $grouped = $permissions->groupBy(fn($p) => explode(' ', $p->name)[0]);
                        @endphp

                        @foreach ($grouped as $group => $perms)
                            <div class="card border-0 shadow-sm mb-3">
                                <div
                                    class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
                                    {{ strtoupper($group) }}
                                    <button type="button" class="btn btn-sm btn-link text-primary check-all"
                                        data-group="{{ $group }}">
                                        Pilih Semua
                                    </button>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        @foreach ($perms as $permission)
                                            <div class="col-md-3 col-sm-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                                        name="permissions[]" value="{{ $permission->name }}"
                                                        data-group="{{ $group }}" id="perm_{{ $permission->id }}">
                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
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

                    {{-- Tombol --}}
                    <div class="d-flex justify-content-end pt-3">
                        <button type="submit" class="btn btn-success px-4 rounded-3">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection


{{-- ✅ Script Dipindah ke Stack JS --}}
@push('scripts')
    <script>
        document.querySelectorAll('.check-all').forEach(button => {
            button.addEventListener('click', function() {
                let group = this.dataset.group;
                let checkboxes = document.querySelectorAll(
                    `.permission-checkbox[data-group="${group}"]`
                );
                let allChecked = Array.from(checkboxes).every(cb => cb.checked);

                checkboxes.forEach(cb => cb.checked = !allChecked);
                this.textContent = allChecked ? 'Pilih Semua' : 'Batal';
            });
        });
    </script>
@endpush
