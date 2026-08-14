@extends('layouts.app')

@section('title', 'Active Sessions')
@section('page-title', 'Active Sessions')

@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center">
        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="mb-0">Active Sessions</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('profile.edit') }}">Profile</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sessions</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header fw-600">
        <i class="bi bi-laptop me-2 text-primary"></i> Browser Sessions
    </div>
    <div class="card-body p-4">
        <p class="text-muted small mb-3">
            If necessary, you may log out of all other browser sessions across all your devices.
        </p>

        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 py-2 mb-3" role="alert">
                <i class="bi bi-check-circle-fill text-success"></i>
                <span>{{ session('status') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Device</th>
                        <th>IP Address</th>
                        <th>Last Activity</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                        <i class="bi bi-{{ $session->agent == 'iOS' ? 'iphone' : ($session->agent == 'Android' ? 'android' : ($session->agent == 'Windows' ? 'windows' : ($session->agent == 'macOS' ? 'apple' : 'pci-card'))) }} text-muted" style="font-size:1.2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $session->agent }}</div>
                                        @if($session->is_current)
                                            <span class="badge bg-success small">Current Device</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted">{{ $session->ip_address }}</td>
                            <td class="text-muted">{{ $session->last_activity }}</td>
                            <td class="text-end">
                                @unless($session->is_current)
                                    <form method="POST" action="{{ route('sessions.destroy', $session->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-x-circle me-1"></i> Log Out
                                        </button>
                                    </form>
                                @endunless
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card border-danger">
    <div class="card-header fw-600 text-danger">
        <i class="bi bi-shield-exclamation me-2"></i> Log Out Other Sessions
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('sessions.destroyOthers') }}" onsubmit="return confirm('Are you sure you want to log out of all other sessions?');">
            @csrf
            @method('DELETE')
            <div class="mb-3">
                <label for="password" class="form-label small fw-600">Confirm Password</label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Enter your password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-box-arrow-right me-1"></i> Log Out Other Sessions
            </button>
        </form>
    </div>
</div>
@endsection
