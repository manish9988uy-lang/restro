@extends('layouts.app')

@section('title', 'Setup Two-Factor Authentication')
@section('page-title', 'Setup Two-Factor Authentication')

@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center">
        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="mb-0">Setup Two-Factor Authentication</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('profile.edit') }}">Profile</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Setup 2FA</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <p class="text-muted mb-4">To enable two-factor authentication, scan the following QR code using your phone's authenticator application or enter the setup key.</p>

        <div class="row mb-4">
            <div class="col-md-6 text-center">
                {!! $qrCode !!}
            </div>
            <div class="col-md-6">
                <h6 class="fw-semibold">Setup Key</h6>
                <p class="font-mono">{{ $secret }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('two-factor.enable') }}">
            @csrf

            <div class="mb-3">
                <label for="code" class="form-label">Code</label>
                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" required>
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Enable 2FA</button>
        </form>
    </div>
</div>
@endsection
