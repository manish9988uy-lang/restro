@extends('layouts.guest')

@section('title', 'Two-Factor Challenge')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header">{{ __('Two-Factor Authentication') }}</div>

                <div class="card-body">
                    <p class="text-muted">{{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}</p>

                    <form method="POST" action="{{ route('two-factor.challenge') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="code" class="form-label">{{ __('Code') }}</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" required autofocus>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">{{ __('Confirm') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
