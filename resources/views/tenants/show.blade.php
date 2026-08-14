@extends('layouts.app')
@section('title', 'Tenant Details')
@section('page-title', 'Tenant Details')

@section('content')
<div class="card max-w-3xl mx-auto">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $tenant->name }}</h5>
        <a href="{{ route('tenants.edit', $tenant) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-4">
                <p class="mb-1 text-muted small">Status</p>
                <span class="badge {{ $tenant->is_active ? 'bg-success' : 'bg-danger' }}">
                    {{ $tenant->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="col-md-4">
                <p class="mb-1 text-muted small">Plan</p>
                <p class="fw-medium">{{ $tenant->plan_name }}</p>
            </div>
            <div class="col-md-4">
                <p class="mb-1 text-muted small">Domain</p>
                <p class="fw-medium">{{ $tenant->domain ? $tenant->domain . '.restos.com' : 'Not setup' }}</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <p class="mb-1 text-muted small">Email</p>
                <p>{{ $tenant->email ?: '—' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <p class="mb-1 text-muted small">Phone</p>
                <p>{{ $tenant->phone ?: '—' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <p class="mb-1 text-muted small">Trial Ends At</p>
                <p>{{ $tenant->trial_ends_at?->format('F j, Y') ?? '—' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <p class="mb-1 text-muted small">Subscription Ends At</p>
                <p>{{ $tenant->subscription_ends_at?->format('F j, Y') ?? '—' }}</p>
            </div>
        </div>
    </div>
    <div class="card-footer bg-light">
        <a href="{{ route('tenants.index') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Tenants
        </a>
    </div>
</div>
@endsection
