@extends('layouts.app')
@section('title', 'Employee Details')
@section('page-title', 'Employee Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-person me-2 text-primary"></i>{{ $employee->name }}</span>
                <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        @if($employee->photo)
                            <img src="{{ asset('storage/'.$employee->photo) }}" class="rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover;">
                        @else
                            <div class="rounded-circle mx-auto mb-3 bg-secondary" style="width:120px;height:120px;line-height:120px;text-align:center;color:#fff;font-size:48px;">{{ strtoupper(substr($employee->name, 0, 1)) }}</div>
                        @endif
                        <h5 class="fw-bold">{{ $employee->name }}</h5>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold text-muted w-30">Email</td>
                                <td>{{ $employee->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Phone</td>
                                <td>{{ $employee->phone }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">City</td>
                                <td>{{ $employee->city }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Address</td>
                                <td>{{ $employee->address }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Experience</td>
                                <td>{{ $employee->experience ?? 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Salary</td>
                                <td class="fw-bold">${{ number_format($employee->salary, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Vacation Days</td>
                                <td>{{ $employee->vacation }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square me-1"></i>Edit Employee
                    </a>
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
