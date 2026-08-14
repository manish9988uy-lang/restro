@extends('layouts.app')
@section('title', 'Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-7">

        <!-- Update Profile -->
        <div class="card mb-4">
            <div class="card-header fw-600"><i class="bi bi-person-circle me-2 text-primary"></i>Profile Information</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label small fw-600">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required/>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-600">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required/>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @if(session('status') === 'profile-updated')
                    <div class="alert alert-success py-2 mb-3">Profile updated successfully.</div>
                    @endif
                    <button type="submit" class="btn btn-primary px-5">Save Changes</button>
                </form>
            </div>
        </div>

        <!-- Two-Factor Authentication -->
        <div class="card mb-4">
            <div class="card-header fw-600"><i class="bi bi-shield-lock me-2 text-info"></i>Two-Factor Authentication</div>
            <div class="card-body p-4">
                @if(auth()->user()->two_factor_secret)
                    <p class="text-muted small mb-3">Two-factor authentication is enabled for your account.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('two-factor.show') }}" class="btn btn-outline-primary">Manage Recovery Codes</a>
                        <form method="POST" action="{{ route('two-factor.disable') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Disable</button>
                        </form>
                    </div>
                @else
                    <p class="text-muted small mb-3">Add an extra layer of security to your account using two-factor authentication.</p>
                    <form method="POST" action="{{ route('two-factor.qr') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Enable Two-Factor Authentication</button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Browser Sessions -->
        <div class="card mb-4">
            <div class="card-header fw-600"><i class="bi bi-laptop me-2 text-primary"></i>Browser Sessions</div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3">Manage and log out of your active sessions on other browsers and devices.</p>
                <a href="{{ route('sessions.index') }}" class="btn btn-outline-primary">Manage Sessions</a>
            </div>
        </div>

        <!-- Update Password -->
        <div class="card mb-4">
            <div class="card-header fw-600"><i class="bi bi-lock me-2 text-warning"></i>Change Password</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label small fw-600">Current Password</label>
                        <input type="password" name="current_password"
                               class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" required/>
                        @error('current_password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-600">New Password</label>
                        <input type="password" name="password"
                               class="form-control @error('password', 'updatePassword') is-invalid @enderror" required/>
                        @error('password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-600">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required/>
                    </div>
                    @if(session('status') === 'password-updated')
                    <div class="alert alert-success py-2 mb-3">Password updated.</div>
                    @endif
                    <button type="submit" class="btn btn-warning px-5">Update Password</button>
                </form>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="card border-danger">
            <div class="card-header fw-600 text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Delete Account</div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3">Once deleted, all data will be permanently removed.</p>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    Delete Account
                </button>
            </div>
        </div>

    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-700 text-danger">Delete Account</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf @method('DELETE')
                <div class="modal-body">
                    <p class="small text-muted">Please enter your password to confirm.</p>
                    <input type="password" name="password"
                           class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                           placeholder="Your password" required/>
                    @error('password', 'userDeletion')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
