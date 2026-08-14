@extends('layouts.app')
@section('title', 'Edit Loyalty Campaign')
@section('page-title', 'Edit Loyalty Campaign')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-pencil me-2 text-info"></i>Edit Campaign</span>
                <a href="{{ route('loyalty.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('loyalty.update', $campaign) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $campaign->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount Spent for Points <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="amount_for_points" min="1" class="form-control @error('amount_for_points') is-invalid @enderror" value="{{ old('amount_for_points', $campaign->amount_for_points) }}" required>
                            </div>
                            @error('amount_for_points')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Points Earned <span class="text-danger">*</span></label>
                            <input type="number" name="points_per_amount" min="1" class="form-control @error('points_per_amount') is-invalid @enderror" value="{{ old('points_per_amount', $campaign->points_per_amount) }}" required>
                            @error('points_per_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Reward Type <span class="text-danger">*</span></label>
                            <select name="reward_type" class="form-select @error('reward_type') is-invalid @enderror" required>
                                <option value="">Select</option>
                                <option value="discount" {{ old('reward_type', $campaign->reward_type) === 'discount' ? 'selected' : '' }}>Discount</option>
                                <option value="free_item" {{ old('reward_type', $campaign->reward_type) === 'free_item' ? 'selected' : '' }}>Free Item</option>
                                <option value="points" {{ old('reward_type', $campaign->reward_type) === 'points' ? 'selected' : '' }}>Points</option>
                            </select>
                            @error('reward_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Reward Value <span class="text-danger">*</span></label>
                            <input type="number" name="reward_value" min="1" class="form-control @error('reward_value') is-invalid @enderror" value="{{ old('reward_value', $campaign->reward_value) }}" required>
                            @error('reward_value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valid From</label>
                            <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from', $campaign->valid_from?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valid Until</label>
                            <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until', $campaign->valid_until?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch pt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', $campaign->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex gap-3 mt-2">
                            <button type="submit" class="btn btn-info text-white px-5">
                                <i class="bi bi-check-circle me-1"></i>Update
                            </button>
                            <a href="{{ route('loyalty.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
