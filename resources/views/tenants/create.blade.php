@extends('layouts.app')
@section('title', 'New Tenant')
@section('page-title', 'New Tenant')

@section('content')
<div class="card max-w-3xl mx-auto">
    <div class="card-header">
        <h5 class="mb-0">Tenant Details</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('tenants.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sub-Domain</label>
                    <div class="input-group">
                        <input type="text" name="domain" class="form-control @error('domain') is-invalid @enderror" value="{{ old('domain') }}">
                        <span class="input-group-text">.restos.com</span>
                    </div>
                    @error('domain')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row border-top pt-3 mt-2">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Subscription Plan</label>
                    <select name="plan_name" class="form-select @error('plan_name') is-invalid @enderror">
                        <option value="Free" {{ old('plan_name') == 'Free' ? 'selected' : '' }}>Free</option>
                        <option value="Basic" {{ old('plan_name') == 'Basic' ? 'selected' : '' }}>Basic</option>
                        <option value="Premium" {{ old('plan_name') == 'Premium' ? 'selected' : '' }}>Premium</option>
                        <option value="Enterprise" {{ old('plan_name') == 'Enterprise' ? 'selected' : '' }}>Enterprise</option>
                    </select>
                    @error('plan_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Trial Ends At</label>
                    <input type="date" name="trial_ends_at" class="form-control @error('trial_ends_at') is-invalid @enderror" value="{{ old('trial_ends_at') }}">
                    @error('trial_ends_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Subscription Ends At</label>
                    <input type="date" name="subscription_ends_at" class="form-control @error('subscription_ends_at') is-invalid @enderror" value="{{ old('subscription_ends_at') }}">
                    @error('subscription_ends_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            
            <div class="mb-3 mt-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">Active Tenant</label>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Register Tenant</button>
                <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
