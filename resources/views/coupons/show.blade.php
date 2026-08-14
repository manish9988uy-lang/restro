@extends('layouts.app')
@section('title', $coupon->code)
@section('page-title', 'Coupon Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-ticket-perforated me-2 text-primary"></i>{{ $coupon->code }}</span>
                <a href="{{ route('coupons.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <span class="text-muted small">Type</span>
                        <div class="fw-600">{{ ucfirst($coupon->type) }}</div>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small">Value</span>
                        <div class="fw-700 text-primary">
                            @if($coupon->type === 'percentage')
                            {{ $coupon->value }}%
                            @else
                            ${{ number_format($coupon->value, 2) }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <span class="text-muted small">Min Order</span>
                        <div class="fw-600">{{ $coupon->min_order > 0 ? '$'.number_format($coupon->min_order, 2) : 'None' }}</div>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small">Usage</span>
                        <div class="fw-600">{{ $coupon->used_count }} @if($coupon->usage_limit)/ {{ $coupon->usage_limit }} @endif</div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <span class="text-muted small">Validity</span>
                        <div class="fw-600">
                            @if($coupon->valid_from) From {{ $coupon->valid_from->format('M d, Y') }} @endif
                            @if($coupon->valid_until) To {{ $coupon->valid_until->format('M d, Y') }} @endif
                            @if(!$coupon->valid_from && !$coupon->valid_until) Always @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small">Status</span>
                        <div>
                            <span class="badge {{ $coupon->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('coupons.edit', $coupon) }}" class="btn btn-outline-info">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <form method="POST" action="{{ route('coupons.destroy', $coupon) }}" class="d-inline" onsubmit="return confirm('Delete?')">
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
