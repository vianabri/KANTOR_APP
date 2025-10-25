@extends('layouts.app')
@section('title', 'Manajemen Role')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-primary">Manajemen Role</h3>
                <small class="text-muted">Kelola role dan hak akses permission sistem</small>
            </div>
            <a href="{{ route('roles.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-2"></i> Tambah Role
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body table-responsive">

                <table id="rolesTable" class="table table-hover align-middle table-sm">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>Nama Role</th>
                            <th>Permissions</th>
                            <th style="width:120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td class="text-start">
                                    <i class="fas fa-user-tag text-primary me-2"></i>
                                    <strong>{{ ucfirst($role->name) }}</strong>
                                </td>

                                <td class="text-start">
                                    @forelse ($role->permissions as $perm)
                                        <span
                                            class="badge bg-info text-dark mb-1">{{ str_replace('_', ' ', $perm->name) }}</span>
                                    @empty
                                        <span class="badge bg-secondary">Tidak ada</span>
                                    @endforelse
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if ($role->name !== 'admin')
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button onclick="return confirm('Yakin hapus role ini?')"
                                                class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            $('#rolesTable').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                }
            });
        });
    </script>
@endpush
