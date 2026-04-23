@extends('admin.layouts.app')
@section('content')
<main class="main" id="main" role="main">
    <div class="page-header">
        <div class="page-title">
            <h1>Access restricted</h1>
            <p>You do not have permission to open that page.</p>
        </div>
    </div>
    @if(session('error'))
        <div class="alert alert-warning alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="card shadow-sm">
        <div class="card-body">
            <p class="mb-3">Contact a main administrator if you need additional access.</p>
            <a href="{{ \App\Support\AdminPanelAccess::defaultRedirectUrl() }}" class="btn btn-primary">Go to your home page</a>
        </div>
    </div>
</main>
@endsection
