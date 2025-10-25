@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
    <div class="container py-4">
        <h2 class="fw-bold mb-4">📜 Activity Logs</h2>

        <form method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="Cari aktivitas atau pengguna...">
                </div>
                <div class="col-md-3">
                    <input type="number" name="user" value="{{ request('user') }}" class="form-control"
                        placeholder="Filter by User ID">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>


        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Model</th>
                            <th>Properties</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $index => $activity)
                            <tr>
                                <td>{{ $activities->firstItem() + $index }}</td>
                                <td>{{ $activity->causer?->name ?? '-' }}</td>
                                <td>{{ $activity->description }}</td>
                                <td>{{ class_basename($activity->subject_type) ?? '-' }}</td>
                                <td>
                                    <code class="small text-muted">
                                        {{ json_encode($activity->properties->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
                                    </code>
                                </td>
                                <td>{{ $activity->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    Tidak ada aktivitas tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-end">
                    {{ $activities->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
