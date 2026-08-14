@extends('layouts.app')
@section('title', 'Branches')
@section('page-title', 'Branches')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-building me-2"></i>Branch List</span>
        <a href="{{ route('branches.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
            <i class="bi bi-plus-circle me-1"></i>New Branch
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Manager</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($branches as $branch)
                <tr>
                    <td class="text-muted small">{{ $branch->id }}</td>
                    <td class="fw-600">{{ $branch->name }}</td>
                    <td class="small">{{ $branch->address }}</td>
                    <td class="small">{{ $branch->phone }}</td>
                    <td class="small">{{ $branch->email }}</td>
                    <td class="small">{{ $branch->manager?->name ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $branch->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $branch->is_active ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('branches.show', $branch) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('branches.edit', $branch) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('branches.destroy', $branch) }}" class="d-inline" onsubmit="return confirm('Delete this branch?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">No branches found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($branches->hasPages())
    <div class="card-footer bg-white border-top-0 pt-0">
        {{ $branches->links() }}
    </div>
    @endif
</div>
@endsection
