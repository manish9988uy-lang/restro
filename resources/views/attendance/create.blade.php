@extends('layouts.app')
@section('title', 'Take Attendance')
@section('page-title', 'Take Attendance')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-plus me-2 text-primary"></i>Take Attendance</span>
                <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('attendance.store') }}">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Date <span class="text-danger">*</span></label>
                            <input
                                type="date"
                                name="date"
                                class="form-control @error('date') is-invalid @enderror"
                                value="{{ old('date', now()->format('Y-m-d')) }}"
                                required
                            >
                            @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    @if($employees->isEmpty())
                        <div class="alert alert-warning mb-0">
                            No employees found. Add employees before taking attendance.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th class="text-center">Present</th>
                                        <th class="text-center">Absent</th>
                                        <th class="text-center">Late</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employees as $employee)
                                        <tr>
                                            <td class="fw-semibold">
                                                {{ $employee->name }}
                                                <input type="hidden" name="employee_id[{{ $employee->id }}]" value="{{ $employee->id }}">
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="status{{ $employee->id }}" value="present" checked>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="status{{ $employee->id }}" value="absent">
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="status{{ $employee->id }}" value="late">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @error('employee_id')<div class="text-danger small mb-3">{{ $message }}</div>@enderror
                        @error('employee_id.*')<div class="text-danger small mb-3">{{ $message }}</div>@enderror

                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-1"></i>Save Attendance
                            </button>
                            <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
