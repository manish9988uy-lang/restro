@extends('layouts.app')
@section('title', 'New Coupon')
@section('page-title', 'New Coupon')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-plus-circle me-2 text-primary"></i>Create Coupon</span>
                <a href="{{ route('coupons.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('coupons.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" required>
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">Select type</option>
                                <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                                <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Fixed</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Value <span class="text-danger">*</span></label>
                            <input type="number" name="value" step="0.01" min="0" class="form-control @error('value') is-invalid @enderror" value="{{ old('value') }}" required>
                            @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Min Order</label>
                            <input type="number" name="min_order" step="0.01" min="0" class="form-control" value="{{ old('min_order') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valid From</label>
                            <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valid Until</label>
                            <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Usage Limit</label>
                            <input type="number" name="usage_limit" min="1" class="form-control" value="{{ old('usage_limit') }}">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch pt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex gap-3 mt-2">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-check-circle me-1"></i>Create
                            </button>
                            <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
