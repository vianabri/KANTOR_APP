@extends('layouts.app')
@section('title', 'Edit Role')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Edit Role</h3>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Role</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $role->name) }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Permission</label>
                        <div class="row">
                            @foreach ($permissions as $permission)
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                            id="perm_{{ $permission->id }}" class="form-check-input"
                                            {{ $role->permissions->contains($permission) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                            {{ ucfirst($permission->name) }}
                                        </label>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i> Perbarui
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
