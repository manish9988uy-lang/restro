@extends('layouts.app')
@section('title', 'Pay Single Employee')
@section('page-title', 'Pay Single Employee')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Pay Single Employee</span>
                <a href="{{ route('pay-salary.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('pay-salary.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror" id="employeeSelect" required>
                                <option value="">Select an employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" data-salary="{{ $employee->salary }}" @selected(old('employee_id') == $employee->id)>{{ $employee->name }} (Rs. {{ number_format($employee->salary, 2) }})</option>
                                @endforeach
                            </select>
                            @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                            @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Month</label>
                            <select name="month" class="form-select @error('month') is-invalid @enderror" required>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" @selected(old('month', date('m')) == $m)>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                                @endforeach
                            </select>
                            @error('month')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Year</label>
                            <select name="year" class="form-select @error('year') is-invalid @enderror" required>
                                @foreach(range(date('Y')-2, date('Y')+1) as $y)
                                    <option value="{{ $y }}" @selected(old('year', date('Y')) == $y)>{{ $y }}</option>
                                @endforeach
                            </select>
                            @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Total Salary</label>
                            <div class="form-control-plaintext fw-bold text-primary" id="salaryDisplay">$0.00</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Advance (if any)</label>
                            <div class="form-control-plaintext fw-bold text-warning" id="advanceDisplay">$0.00</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Due Salary</label>
                            <div class="form-control-plaintext fw-bold text-success" id="dueDisplay">$0.00</div>
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

<script>
document.getElementById('employeeSelect').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const salary = parseFloat(selected.dataset.salary) || 0;
    document.getElementById('salaryDisplay').textContent = '$' + salary.toFixed(2);
    document.getElementById('advanceDisplay').textContent = '$0.00';
    document.getElementById('dueDisplay').textContent = '$' + salary.toFixed(2);
});
</script>
@endsection
