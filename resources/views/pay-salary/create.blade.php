@extends('layouts.app')
@section('title', 'Pay Salary')
@section('page-title', 'Pay Salary')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-credit-card-2-front me-2 text-primary"></i>Pay Salary</span>
                <a href="{{ route('pay-salary.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
                        <div>
                            <h6 class="mb-0">{{ $advanceSalary->employee?->name ?? 'Unknown Employee' }}</h6>
                            <div class="text-muted small">Salary: Rs. {{ number_format($advanceSalary->employee?->salary ?? 0, 2) }} | Advance: Rs. {{ number_format($advanceSalary->advance_salary, 2) }}</div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('pay-salary.store') }}">
                    @csrf

                    <input type="hidden" name="employee_id" value="{{ $advanceSalary->employee_id }}">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Month</label>
                            <select name="month" class="form-select @error('month') is-invalid @enderror" required>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" @selected(old('month', date('m')) == $m)>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                                @endforeach
                            </select>
                            @error('month')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Year</label>
                            <select name="year" class="form-select @error('year') is-invalid @enderror" required>
                                @foreach(range(date('Y')-2, date('Y')+1) as $y)
                                    <option value="{{ $y }}" @selected(old('year', date('Y')) == $y)>{{ $y }}</option>
                                @endforeach
                            </select>
                            @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                            @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Total Salary</label>
                            <div class="form-control-plaintext fw-bold text-primary">Rs. {{ number_format($advanceSalary->employee?->salary ?? 0, 2) }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Advance Deduction</label>
                            <div class="form-control-plaintext fw-bold text-warning">Rs. {{ number_format($advanceSalary->advance_salary, 2) }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Due Salary</label>
                            <div class="form-control-plaintext fw-bold text-success">Rs. {{ number_format(($advanceSalary->employee?->salary ?? 0) - $advanceSalary->advance_salary, 2) }}</div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-circle me-1"></i>Confirm Payment
                        </button>
                        <a href="{{ route('pay-salary.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
