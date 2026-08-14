@extends('layouts.app')
@section('title', 'Advance Salaries')
@section('page-title', 'Advance Salaries')

@section('content')
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('advance-salary.index') }}" class="row g-2 align-items-end">
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
                <a href="{{ route('advance-salary.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-counterclockwise"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-cash-stack me-2"></i>Advance Salaries</span>
        <a href="{{ route('advance-salary.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
            <i class="bi bi-plus-circle me-1"></i>New Advance
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($advance_salaries as $advance)
                <tr>
                    <td class="fw-semibold">{{ $advance->employee?->name ?? 'Unknown Employee' }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($advance->date)->format('Y-m-d') }}</td>
                    <td class="fw-semibold">${{ number_format($advance->advance_salary, 2) }}</td>
                    <td>
                        @if($advance->is_deducted)
                            <span class="badge bg-success">Deducted</span>
                        @else
                            <span class="badge bg-warning">Available</span>
                        @endif
                    </td>
                    <td>
                        @if(!$advance->is_deducted)
                            <a href="{{ route('advance-salary.edit', $advance) }}" class="btn btn-sm btn-outline-info" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('advance-salary.destroy', $advance) }}" class="d-inline" onsubmit="return confirm('Delete this advance?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-5 text-muted">No advance salaries found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($advance_salaries->hasPages())
    <div class="card-footer bg-white border-top-0 pt-0">{{ $advance_salaries->links() }}</div>
    @endif
</div>
@endsection
