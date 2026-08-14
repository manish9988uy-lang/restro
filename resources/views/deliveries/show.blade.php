@extends('layouts.app')
@section('title', 'Delivery #'.$delivery->id)
@section('page-title', 'Delivery Details')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-truck me-2 text-primary"></i>Delivery #{{ $delivery->id }}</span>
                <a href="{{ route('deliveries.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <span class="text-muted small">Order</span>
                        <div class="fw-600">
                            <a href="{{ route('orders.show', $delivery->order) }}" class="text-decoration-none">
                                {{ $delivery->order->invoice_no }}
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small">Rider</span>
                        <div class="fw-600">{{ $delivery->rider ? $delivery->rider->name : '—' }}</div>
                    </div>
                </div>
                <div class="mb-4">
                    <span class="text-muted small">Delivery Address</span>
                    <div class="fw-600">{{ $delivery->delivery_address }}</div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <span class="text-muted small">Delivery Fee</span>
                        <div class="fw-700 fs-4 text-primary">${{ number_format($delivery->delivery_fee, 2) }}</div>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted small">Status</span>
                        <div>
                            @php $statusColors = ['pending'=>'warning','assigned'=>'info','picked_up'=>'primary','in_transit'=>'secondary','delivered'=>'success','cancelled'=>'danger'] @endphp
                            <span class="badge bg-{{ $statusColors[$delivery->status] }}">{{ ucwords(str_replace('_', ' ', $delivery->status)) }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted small">Zone</span>
                        <div class="fw-600">{{ $delivery->deliveryZone ? $delivery->deliveryZone->name : '—' }}</div>
                    </div>
                </div>
                @if($delivery->notes)
                <div class="mb-4">
                    <span class="text-muted small">Notes</span>
                    <div>{{ $delivery->notes }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-600">
                <i class="bi bi-pencil-square me-2"></i>Actions
            </div>
            <div class="card-body p-3">
                @if(!$delivery->rider_id && $delivery->status != 'delivered' && $delivery->status != 'cancelled')
                <form method="POST" action="{{ route('deliveries.assignRider', $delivery) }}" class="mb-3">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label small fw-600">Assign Rider</label>
                        <select name="rider_id" class="form-select" required>
                            <option value="">Select Rider</option>
                            @foreach($riders as $rider)
                                <option value="{{ $rider->id }}">{{ $rider->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-person-check me-1"></i>Assign Rider
                    </button>
                </form>
                <hr>
                @endif
                <div class="mb-2">
                    <label class="form-label small fw-600">Update Status</label>
                    <form method="POST" action="{{ route('deliveries.updateStatus', $delivery) }}" class="row g-2">
                        @csrf
                        <div class="col-8">
                            <select name="status" class="form-select">
                                @foreach(['pending','assigned','picked_up','in_transit','delivered','cancelled'] as $status)
                                    <option value="{{ $status }}" @selected($delivery->status == $status)>{{ ucwords(str_replace('_', ' ', $status)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-outline-primary w-100">Update</button>
                        </div>
                    </form>
                </div>
                <hr>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('deliveries.edit', $delivery) }}" class="btn btn-outline-info flex-fill">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <form method="POST" action="{{ route('deliveries.destroy', $delivery) }}" class="flex-fill" onsubmit="return confirm('Delete this delivery?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
