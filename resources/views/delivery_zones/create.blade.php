@extends('layouts.app')
@section('title', 'New Delivery Zone')
@section('page-title', 'New Delivery Zone')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-plus-circle me-2 text-primary"></i>New Delivery Zone</span>
                <a href="{{ route('delivery_zones.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('delivery_zones.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-600">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-600">Base Fee (Rs.) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="base_fee" step="0.01" min="0" class="form-control @error('base_fee') is-invalid @enderror" value="{{ old('base_fee') }}" required>
                            </div>
                            @error('base_fee')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-600">Min Order (Rs.)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="min_order" step="0.01" min="0" class="form-control" value="{{ old('min_order', 0) }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-600">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" @checked(old('is_active', true))>
                                <label class="form-check-label small fw-600" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-circle me-1"></i>Create Zone
                        </button>
                        <a href="{{ route('delivery_zones.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
