@extends('layouts.app')
@section('title', 'Manajemen Role')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0">Manajemen Role</h3>
                <small class="text-muted">Kelola role dan hak akses permission</small>
            </div>
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Tambah Role
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @if ($roles->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-primary">
                                <tr class="text-center">
                                    <th>Nama Role</th>
                                    <th>Permissions</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr class="text-center">
                                        <td class="text-start">
                                            <i class="fas fa-user-shield text-primary me-2"></i> {{ ucfirst($role->name) }}
                                        </td>
                                        <td class="text-start">
                                            @forelse ($role->permissions as $perm)
                                                <span class="badge bg-info text-dark">{{ $perm->name }}</span>
                                            @empty
                                                <span class="badge bg-secondary">Tidak ada</span>
                                            @endforelse
                                        </td>
                                        <td>
                                            <a href="{{ route('roles.edit', $role->id) }}"
                                                class="btn btn-sm btn-warning me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button onclick="return confirm('Hapus role ini?')"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 d-flex justify-content-center">
                        {{ $roles->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-user-shield fa-2x mb-2"></i>
                        <p class="mb-0">Belum ada data role.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
