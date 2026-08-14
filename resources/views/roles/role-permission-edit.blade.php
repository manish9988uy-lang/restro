@extends('layouts.app')

@section('title', 'Edit Role Permissions')
@section('page-title', 'Edit Role Permissions')

@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center">
        <a href="{{ route('rolePermission.index') }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="mb-0">Edit Role Permissions: {{ $role->name }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('rolePermission.index') }}">Role Permissions</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('rolePermission.update', $role->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Permissions</label>
                @php
                    $rolePermissions = $role->permissions->pluck('id')->toArray();
                @endphp
                @foreach($permission_groups as $group)
                    @php
                        $permissions = \Spatie\Permission\Models\Permission::where('group_name', $group)->get();
                    @endphp
                    <div class="mb-3">
                        <h6 class="fw-semibold mb-2">{{ $group }}</h6>
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permission_id[]" id="permission_{{ $permission->id }}" value="{{ $permission->id }}" {{ in_array($permission->id, old('permission_id', $rolePermissions)) ? 'checked' : '' }}>
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
                <i class="bi bi-save me-1"></i> Update
            </button>
        </form>
    </div>
</div>
@endsection
