@extends('layouts.app')

@section('title', 'Role Permissions')
@section('page-title', 'Role Permissions')

@section('content')
<div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-0">Role Permissions</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Role Permissions</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('rolePermission.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Assign Permissions
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Role</th>
                        <th>Permissions</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            @foreach($role->permissions as $permission)
                                <span class="badge bg-secondary me-1">{{ $permission->name }}</span>
                            @endforeach
                        </td>
                        <td class="text-end">
                            <a href="{{ route('rolePermission.edit', $role->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-4 text-muted">No roles found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $roles->links() }}
    </div>
</div>
@endsection
