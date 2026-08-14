@extends('layouts.app')
@section('title', 'Pay Salary - Advance List')
@section('page-title', 'Pay Salary - Advance List')

@section('content')
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('pay-salary.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search by employee name..." value="{{ request('search') }}">
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
                <a href="{{ route('pay-salary.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-counterclockwise"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-wallet2 me-2"></i>Advances Available for Payment</span>
        <div class="d-flex gap-2">
            <a href="{{ route('pay-salary.create') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                <i class="bi bi-person-plus me-1"></i>Pay Single Employee
            </a>
            <a href="{{ route('pay-salary.pay-all') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                <i class="bi bi-people me-1"></i>Pay All Employees
            </a>
            <a href="{{ route('pay-salary.pay-history') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-clock-history me-1"></i>Payment History
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Advance Amount</th>
                    <th>Salary</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($advanceSalaries as $advance)
                <tr>
                    <td class="fw-semibold">{{ $advance->employee?->name ?? 'Unknown Employee' }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($advance->date)->format('Y-m-d') }}</td>
                    <td class="fw-semibold text-warning">${{ number_format($advance->advance_salary, 2) }}</td>
                    <td class="fw-semibold">${{ number_format($advance->employee?->salary ?? 0, 2) }}</td>
                    <td>
                        <a href="{{ route('pay-salary.pay-salary', $advance) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-cash-stack me-1"></i>Pay
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-5 text-muted">No advances available for payment.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($advanceSalaries->hasPages())
    <div class="card-footer bg-white border-top-0 pt-0">{{ $advanceSalaries->links() }}</div>
    @endif
</div>
@endsection
