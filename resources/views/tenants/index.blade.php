@extends('layouts.app')
@section('title', 'SaaS Tenants')
@section('page-title', 'SaaS Tenants')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clouds me-2"></i>Tenants</span>
        <a href="{{ route('tenants.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
            <i class="bi bi-plus-circle me-1"></i>New Tenant
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Domain</th>
                    <th>Email</th>
                    <th>Plan</th>
                    <th>Trial Ends</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tenants as $tenant)
                <tr>
                    <td class="text-muted small">{{ $tenant->id }}</td>
                    <td class="fw-600">{{ $tenant->name }}</td>
                    <td class="small">{{ $tenant->domain ?? 'N/A' }}</td>
                    <td class="small">{{ $tenant->email }}</td>
                    <td class="small">{{ $tenant->plan_name }}</td>
                    <td class="small">{{ $tenant->trial_ends_at?->format('Y-m-d') ?? 'N/A' }}</td>
                    <td>
                        <span class="badge {{ $tenant->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $tenant->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('tenants.show', $tenant) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('tenants.edit', $tenant) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('tenants.destroy', $tenant) }}" class="d-inline" onsubmit="return confirm('Delete this tenant?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">No tenants found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tenants->hasPages())
    <div class="card-footer bg-white border-top-0 pt-0">
        {{ $tenants->links() }}
    </div>
    @endif
</div>
@endsection
