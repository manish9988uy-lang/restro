@extends('layouts.app')
@section('title', 'Expense Details')
@section('page-title', 'Expense Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-receipt-cutoff me-2 text-primary"></i>Expense Details</span>
                <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <span class="text-muted small">Category</span>
                        <div class="fw-600">
                            @php $catIcons = ['rent'=>'bi-house','salary'=>'bi-people','utilities'=>'bi-lightbulb','misc'=>'bi-gear']; @endphp
                            <i class="{{ $catIcons[$expense->category] ?? 'bi-receipt' }} me-1"></i>
                            {{ ucfirst($expense->category) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted small">Amount</span>
                        <div class="fw-700 fs-4 text-primary">${{ number_format($expense->amount, 2) }}</div>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted small">Date</span>
                        <div class="fw-600">{{ $expense->expense_date->format('F j, Y') }}</div>
                    </div>
                </div>

                <div class="mb-4">
                    <span class="text-muted small">Notes</span>
                    <div>{{ $expense->notes ?? 'No notes provided' }}</div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <span class="text-muted small">Created By</span>
                        <div class="fw-600">{{ $expense->createdBy?->name ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted small">Status</span>
                        <div>
                            @if($expense->status == 'pending')
                                <span class="badge bg-warning-subtle text-warning">Pending</span>
                            @elseif($expense->status == 'approved')
                                <span class="badge bg-success-subtle text-success">Approved</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Rejected</span>
                            @endif
                        </div>
                    </div>
                    @if($expense->approved_by)
                    <div class="col-md-4">
                        <span class="text-muted small">Approved By</span>
                        <div class="fw-600">{{ $expense->approvedBy?->name ?? '—' }}</div>
                        <div class="text-muted small">{{ $expense->approved_at?->format('F j, Y H:i') }}</div>
                    </div>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    @if($expense->status == 'pending')
                        <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-outline-info">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                        <form method="POST" action="{{ route('expenses.approve', $expense) }}">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check me-1"></i>Approve
                            </button>
                        </form>
                        <form method="POST" action="{{ route('expenses.reject', $expense) }}">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-x me-1"></i>Reject
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('expenses.destroy', $expense) }}" onsubmit="return confirm('Delete this expense?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
