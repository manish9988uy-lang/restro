@extends('layouts.app')

@section('title', 'Two-Factor Recovery')
@section('page-title', 'Two-Factor Recovery')

@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center">
        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="mb-0">Two-Factor Recovery</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('profile.edit') }}">Profile</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Recovery</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="alert alert-info" role="alert">
            Two-factor authentication is now enabled! Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two-factor authentication device is lost.
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <ul class="list-group mb-3">
                    @foreach(Auth::user()->recoveryCodes() as $code)
                        <li class="list-group-item font-mono">{{ $code }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="d-flex gap-2">
            <form method="POST" action="{{ route('two-factor.recovery-codes') }}">
                @csrf
                <button type="submit" class="btn btn-outline-primary">Regenerate Recovery Codes</button>
            </form>

            <form method="POST" action="{{ route('two-factor.disable') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">Disable Two-Factor Authentication</button>
            </form>
        </div>
    </div>
</div>
@endsection
