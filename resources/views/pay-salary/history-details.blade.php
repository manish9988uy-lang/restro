@extends('layouts.app')
@section('title', 'Payment Details')
@section('page-title', 'Payment Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-receipt me-2 text-primary"></i>Payment Details</span>
                <a href="{{ route('pay-salary.pay-history') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="fw-bold mb-3">{{ $paySalary->employee?->name ?? 'Unknown Employee' }}</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold text-muted w-30">Payment Date</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($paySalary->date)->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Salary Month</td>
                                <td>{{ $paySalary->salary_month }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Total Paid Amount</td>
                                <td class="fw-bold text-primary">Rs. {{ number_format($paySalary->paid_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Advance Deducted</td>
                                <td class="fw-bold text-warning">Rs. {{ number_format($paySalary->advance_salary, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Due Salary</td>
                                <td class="fw-bold text-success">Rs. {{ number_format($paySalary->due_salary, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('pay-salary.pay-history') }}" class="btn btn-outline-secondary">Back to History</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
