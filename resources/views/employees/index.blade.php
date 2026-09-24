@extends('layouts.app')
@section('title', 'Employees')
@section('page-title', 'Employees')

@section('content')
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('employees.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search employees..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="row" class="form-select form-select-sm">
                    @foreach([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected((int) request('row', 10) === $size)>{{ $size }} rows</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </div>
            <div class="col-md-3">
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-counterclockwise"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-person-badge me-2"></i>Employees</span>
        <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
            <i class="bi bi-plus-circle me-1"></i>New Employee
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Salary</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                <tr>
                    <td class="fw-semibold">
                        <a href="{{ route('employees.show', $employee) }}" class="text-decoration-none">
                            @if($employee->photo)
                                <img src="{{ asset('storage/'.$employee->photo) }}" class="rounded-circle me-2" style="width:32px;height:32px;object-fit:cover;">
                            @else
                                <div class="rounded-circle me-2 d-inline-block bg-secondary" style="width:32px;height:32px;line-height:32px;text-align:center;color:#fff;font-size:14px;">{{ strtoupper(substr($employee->name, 0, 1)) }}</div>
                            @endif
                            {{ $employee->name }}
                        </a>
                    </td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->phone }}</td>
                    <td>{{ $employee->city }}</td>
                    <td class="fw-semibold">Rs. {{ number_format($employee->salary, 2) }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-outline-info" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('employees.destroy', $employee) }}" class="d-inline" onsubmit="return confirm('Delete this employee?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">No employees found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($employees->hasPages())
    <div class="card-footer bg-white border-top-0 pt-0">{{ $employees->links() }}</div>
    @endif
</div>
@endsection
