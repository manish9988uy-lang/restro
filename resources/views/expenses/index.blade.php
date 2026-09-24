@extends('layouts.app')
@section('title', 'Expenses')
@section('page-title', 'Expenses')

@section('content')
<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#EFF6FF;"><i class="bi bi-wallet2" style="color:#3b82f6;"></i></div>
                <div>
                    <div class="stat-label">Total Expenses</div>
                    <div class="stat-value">Rs. {{ number_format($stats['total'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#FFFBEB;"><i class="bi bi-clock-history" style="color:#f59e0b;"></i></div>
                <div>
                    <div class="stat-label">Pending</div>
                    <div class="stat-value">Rs. {{ number_format($stats['pending'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#F0FFF4;"><i class="bi bi-check-circle" style="color:#22c55e;"></i></div>
                <div>
                    <div class="stat-label">Approved</div>
                    <div class="stat-value">Rs. {{ number_format($stats['approved'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#FFF0E8;"><i class="bi bi-calendar-month" style="color:#FF6B35;"></i></div>
                <div>
                    <div class="stat-label">This Month</div>
                    <div class="stat-value">Rs. {{ number_format($stats['this_month'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(['pending','approved','rejected'] as $s)
                    <option value="{{ $s }}" @selected(request('status') == $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" placeholder="From" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" placeholder="To" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-search me-1"></i>Search
                </button>
                <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-receipt-cutoff me-2"></i>Expense List</span>
        <a href="{{ route('expenses.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
            <i class="bi bi-plus-circle me-1"></i>New Expense
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Created By</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                <tr>
                    <td class="text-muted small">{{ $expenses->firstItem() + $loop->index }}</td>
                    <td class="small">
                        @php $catIcons = ['rent'=>'bi-house','salary'=>'bi-people','utilities'=>'bi-lightbulb','misc'=>'bi-gear']; @endphp
                        <i class="{{ $catIcons[$expense->category] ?? 'bi-receipt' }} me-1"></i>
                        {{ ucfirst($expense->category) }}
                    </td>
                    <td class="fw-600 small">Rs. {{ number_format($expense->amount, 2) }}</td>
                    <td class="text-muted small">{{ $expense->expense_date->format('M d, Y') }}</td>
                    <td class="small">{{ $expense->createdBy?->name ?? '—' }}</td>
                    <td>
                        @if($expense->status == 'pending')
                            <span class="badge bg-warning-subtle text-warning">Pending</span>
                        @elseif($expense->status == 'approved')
                            <span class="badge bg-success-subtle text-success">Approved</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger">Rejected</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('expenses.show', $expense) }}" class="btn btn-sm btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($expense->status == 'pending')
                                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-outline-info" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('expenses.approve', $expense) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Approve">
                                        <i class="bi bi-check"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('expenses.reject', $expense) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Reject">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('expenses.destroy', $expense) }}" class="d-inline" onsubmit="return confirm('Delete this expense?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-wallet2 d-block mb-2" style="font-size:2rem;"></i>
                        No expenses found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($expenses->hasPages())
    <div class="card-footer bg-white border-top-0 pt-0">
        {{ $expenses->links() }}
    </div>
    @endif
</div>
@endsection
