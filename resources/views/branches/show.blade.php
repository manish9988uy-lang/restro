@extends('layouts.app')
@section('title', 'Branch Details')
@section('page-title', 'Branch Details')

@section('content')
<div class="card max-w-3xl mx-auto">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $branch->name }}</h5>
        <a href="{{ route('branches.edit', $branch) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <p class="mb-1 text-muted small">Status</p>
                <span class="badge {{ $branch->is_active ? 'bg-success' : 'bg-danger' }}">
                    {{ $branch->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="col-md-6">
                <p class="mb-1 text-muted small">Manager</p>
                <p class="fw-medium">{{ $branch->manager?->name ?? 'Not Assigned' }}</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 mb-3">
                <p class="mb-1 text-muted small">Address</p>
                <p>{{ $branch->address ?: '—' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <p class="mb-1 text-muted small">Phone</p>
                <p>{{ $branch->phone ?: '—' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <p class="mb-1 text-muted small">Email</p>
                <p>{{ $branch->email ?: '—' }}</p>
            </div>
        </div>
    </div>
    <div class="card-footer bg-light">
        <a href="{{ route('branches.index') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Branches
        </a>
    </div>
</div>
@endsection
