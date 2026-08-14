@extends('layouts.app')
@section('title', 'Edit Advance Salary')
@section('page-title', 'Edit Advance Salary')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Advance Salary</span>
                <a href="{{ route('advance-salary.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning mb-4">{{ session('warning') }}</div>
                @endif

                <form method="POST" action="{{ route('advance-salary.update', $advance_salary) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                                <option value="">Select an employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" @selected(old('employee_id', $advance_salary->employee_id) == $employee->id)>{{ $employee->name }}</option>
                                @endforeach
                            </select>
                            @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $advance_salary->date) }}" required>
                            @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="advance_salary" class="form-control @error('advance_salary') is-invalid @enderror" value="{{ old('advance_salary', $advance_salary->advance_salary) }}" required>
                            @error('advance_salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-circle me-1"></i>Update Advance
                        </button>
                        <a href="{{ route('advance-salary.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
