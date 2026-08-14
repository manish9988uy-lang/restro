@extends('layouts.app')
@section('title', 'Add Expense')
@section('page-title', 'Add Expense')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-plus-circle me-2 text-primary"></i>New Expense</span>
                <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('expenses.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-600">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach(['rent','salary','utilities','misc'] as $cat)
                                <option value="{{ $cat }}" @selected(old('category') == $cat)>{{ ucfirst($cat) }}</option>
                                @endforeach
                            </select>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-600">Amount ($) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="amount" step="0.01" min="0"
                                       class="form-control @error('amount') is-invalid @enderror"
                                       value="{{ old('amount') }}" placeholder="0.00" required>
                            </div>
                            @error('amount')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-600">Date <span class="text-danger">*</span></label>
                            <input type="date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror"
                                   value="{{ old('expense_date', now()->format('Y-m-d')) }}" required>
                            @error('expense_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-600">Notes</label>
                            <textarea name="notes" class="form-control" rows="3"
                                      placeholder="Additional notes...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-circle me-1"></i>Add Expense
                        </button>
                        <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
