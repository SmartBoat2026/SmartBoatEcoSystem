@extends('admin.layouts.app')
@section('content')
<main class="main" id="main" role="main">
    <div class="page-header">
        <div class="page-title">
            <h1>Staff management</h1>
            <p>Create staff logins with access only to selected admin features.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.staff.create') }}" class="btn-primary">
                <i class="bi bi-plus"></i> Add staff
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Features</th>
                        <th>Status</th>
                        <th style="width:200px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staff as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->username }}</td>
                            <td><small class="text-muted">{{ count($row->permissions ?? []) }} selected</small></td>
                            <td>
                                @if($row->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.staff.edit', $row->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.staff.destroy', $row->id) }}" method="post" class="d-inline"
                                      onsubmit="return confirm('Delete this staff account?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No staff yet. Click &quot;Add staff&quot; to create one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
