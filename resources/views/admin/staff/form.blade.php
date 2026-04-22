@extends('admin.layouts.app')
@section('content')
<main class="main" id="main" role="main">
    <div class="page-header">
        <div class="page-title">
            <h1>{{ $staff ? 'Edit staff' : 'Add staff' }}</h1>
            <p>Set login details and tick the admin features this user may open.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.staff.index') }}" class="btn-secondary">Back to list</a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="{{ $staff ? route('admin.staff.update', $staff->id) : route('admin.staff.store') }}">
                @csrf
                @if($staff)
                    @method('PUT')
                @endif

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required
                               value="{{ old('name', $staff->name ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required autocomplete="username"
                               value="{{ old('username', $staff->username ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password {{ $staff ? '(leave blank to keep current)' : '' }}</label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password"
                               {{ $staff ? '' : 'required' }}>
                    </div>
                    @if($staff)
                        <div class="col-md-6 d-flex align-items-end">
                            <input type="hidden" name="is_active" value="0">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                                       {{ old('is_active', $staff->is_active ? '1' : '0') === '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Account active</label>
                            </div>
                        </div>
                    @endif
                </div>

                <h5 class="mb-3">Feature permissions</h5>
                <div class="row g-2">
                    @foreach($modules as $key => $label)
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $key }}"
                                       id="perm_{{ $key }}"
                                    {{ in_array($key, old('permissions', $selected), true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="perm_{{ $key }}">{{ $label }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">{{ $staff ? 'Update' : 'Create' }} staff</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
