@extends('layouts.app')
@section('title', 'Payment History')
@section('page-title', 'Payment History')

@section('content')
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('pay-salary.pay-history') }}" class="row g-2 align-items-end">
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
                <a href="{{ route('pay-salary.pay-history') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-counterclockwise"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history me-2"></i>Payment History</span>
        <a href="{{ route('pay-salary.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i>Back to Advances
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Salary Month</th>
                    <th>Paid Amount</th>
                    <th>Advance</th>
                    <th>Due</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paySalaries as $pay)
                <tr>
                    <td class="fw-semibold">{{ $pay->employee?->name ?? 'Unknown Employee' }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($pay->date)->format('Y-m-d') }}</td>
                    <td>{{ $pay->salary_month }}</td>
                    <td class="fw-semibold text-primary">Rs. {{ number_format($pay->paid_amount, 2) }}</td>
                    <td class="fw-semibold text-warning">Rs. {{ number_format($pay->advance_salary, 2) }}</td>
                    <td class="fw-semibold text-success">Rs. {{ number_format($pay->due_salary, 2) }}</td>
                    <td>
                        <a href="{{ route('pay-salary.pay-history-detail', $pay) }}" class="btn btn-sm btn-outline-primary" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <form method="POST" action="{{ route('pay-salary.destroy', $pay) }}" class="d-inline" onsubmit="return confirm('Delete this payment?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">No payment history found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($paySalaries->hasPages())
    <div class="card-footer bg-white border-top-0 pt-0">{{ $paySalaries->links() }}</div>
    @endif
</div>
@endsection
