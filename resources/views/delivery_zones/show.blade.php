@extends('layouts.app')
@section('title', $deliveryZone->name)
@section('page-title', 'Delivery Zone')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-geo-alt me-2 text-primary"></i>{{ $deliveryZone->name }}</span>
                <a href="{{ route('delivery_zones.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <span class="text-muted small">Name</span>
                        <div class="fw-600">{{ $deliveryZone->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small">Base Fee</span>
                        <div class="fw-700 fs-4 text-primary">${{ number_format($deliveryZone->base_fee, 2) }}</div>
                    </div>
                </div>
                <div class="mb-4">
                    <span class="text-muted small">Minimum Order</span>
                    <div class="fw-600">{{ $deliveryZone->min_order > 0 ? '$'.number_format($deliveryZone->min_order, 2) : 'None' }}</div>
                </div>
                <div class="mb-4">
                    <span class="text-muted small">Description</span>
                    <div>{{ $deliveryZone->description ?? 'No description' }}</div>
                </div>
                <div class="mb-4">
                    <span class="text-muted small">Status</span>
                    <div>
                        <span class="badge {{ $deliveryZone->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $deliveryZone->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('delivery_zones.edit', $deliveryZone) }}" class="btn btn-outline-info">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <form method="POST" action="{{ route('delivery_zones.destroy', $deliveryZone) }}" onsubmit="return confirm('Delete this zone?')">
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
