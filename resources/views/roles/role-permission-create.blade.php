@extends('layouts.app')

@section('title', 'Assign Permissions')
@section('page-title', 'Assign Permissions')

@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center">
        <a href="{{ route('rolePermission.index') }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="mb-0">Assign Permissions</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('rolePermission.index') }}">Role Permissions</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Assign</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('rolePermission.store') }}">
            @csrf
            <div class="mb-3">
                <label for="role_id" class="form-label">Role</label>
                <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                    <option value="">Select a role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Permissions</label>
                @foreach($permissions_by_group as $group => $permissions)
                    <div class="mb-3">
                        <h6 class="fw-semibold mb-2">{{ $group }}</h6>
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permission_id[]" id="permission_{{ $permission->id }}" value="{{ $permission->id }}" {{ is_array(old('permission_id')) && in_array($permission->id, old('permission_id')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Save
            </button>
        </form>
    </div>
</div>
@endsection
