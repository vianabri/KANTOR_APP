@extends('layouts.app')
@section('title', 'Manajemen User')

@section('content')
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0">Manajemen User</h3>
                <small class="text-muted">Kelola data pengguna dan hak akses</small>
            </div>

            @can('manage users')
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i> Tambah User
                </a>
            @endcan
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">

                @if ($users->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Jabatan</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $u)
                                    <tr class="text-center">
                                        <td class="text-start">
                                            <i class="fas fa-user text-primary me-2"></i> {{ $u->name }}
                                        </td>

                                        <td>{{ $u->email }}</td>
                                        <td>{{ $u->phone ?? '-' }}</td>
                                        <td>{{ $u->position ?? '-' }}</td>

                                        {{-- ROLE --}}
                                        <td>
                                            @forelse($u->roles as $role)
                                                <span class="badge bg-info text-dark">
                                                    {{ ucfirst($role->name) }}
                                                </span>
                                            @empty
                                                <span class="badge bg-secondary">Tidak ada role</span>
                                            @endforelse
                                        </td>

                                        {{-- STATUS --}}
                                        <td>
                                            <span class="badge {{ $u->active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $u->active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>

                                        {{-- AKSI --}}
                                        <td>
                                            <div class="d-flex justify-content-center">

                                                {{-- EDIT --}}
                                                @can('manage users')
                                                    @if (auth()->id() !== $u->id)
                                                        <a href="{{ route('users.edit', $u->id) }}"
                                                            class="btn btn-sm btn-warning me-1">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @else
                                                        <button class="btn btn-sm btn-secondary me-1" disabled>
                                                            <i class="fas fa-user-cog"></i>
                                                        </button>
                                                    @endif
                                                @endcan

                                                {{-- DELETE --}}
                                                @can('manage users')
                                                    @if (auth()->id() !== $u->id)
                                                        <form action="{{ route('users.destroy', $u->id) }}" method="POST">
                                                            @csrf @method('DELETE')
                                                            <button onclick="return confirm('Hapus user ini?')"
                                                                class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-sm btn-secondary" disabled>
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    @endif
                                                @endcan

                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 d-flex justify-content-center">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-users-slash fa-2x mb-2"></i>
                        <p class="mb-0">Belum ada data pengguna.</p>
                    </div>
                @endif

            </div>
        </div>

    </div>
@endsection
