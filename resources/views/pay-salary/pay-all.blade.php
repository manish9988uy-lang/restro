@extends('layouts.app')
@section('title', 'Pay All Employees')
@section('page-title', 'Pay All Employees')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-people-fill me-2 text-primary"></i>Pay All Employees</span>
                <a href="{{ route('pay-salary.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('pay-salary.pay-all-store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Month <span class="text-danger">*</span></label>
                            <select name="month" class="form-select @error('month') is-invalid @enderror" required>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" @selected(old('month', date('m')) == $m)>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                                @endforeach
                            </select>
                            @error('month')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Year <span class="text-danger">*</span></label>
                            <select name="year" class="form-select @error('year') is-invalid @enderror" required>
                                @foreach(range(date('Y')-2, date('Y')+1) as $y)
                                    <option value="{{ $y }}" @selected(old('year', date('Y')) == $y)>{{ $y }}</option>
                                @endforeach
                            </select>
                            @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="alert alert-info mt-4">
                        <i class="bi bi-info-circle me-2"></i> This will process salary payments for all employees for the selected month/year.
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-circle me-1"></i>Pay All Employees
                        </button>
                        <a href="{{ route('pay-salary.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
