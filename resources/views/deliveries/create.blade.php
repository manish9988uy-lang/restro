@extends('layouts.app')
@section('title', 'New Delivery')
@section('page-title', 'New Delivery')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-plus-circle me-2 text-primary"></i>New Delivery</span>
                <a href="{{ route('deliveries.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('deliveries.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-600">Order <span class="text-danger">*</span></label>
                            <select name="order_id" class="form-select @error('order_id') is-invalid @enderror" required>
                                <option value="">Select Order</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}" @selected(old('order_id') == $order->id)>
                                        {{ $order->invoice_no }} (${{ number_format($order->total, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('order_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-600">Rider</label>
                            <select name="rider_id" class="form-select">
                                <option value="">Assign Later</option>
                                @foreach($riders as $rider)
                                    <option value="{{ $rider->id }}" @selected(old('rider_id') == $rider->id)>
                                        {{ $rider->name }} ({{ $rider->phone }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-600">Delivery Zone</label>
                            <select name="delivery_zone_id" class="form-select" id="zoneSelect">
                                <option value="">None</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}" data-fee="{{ $zone->base_fee }}" @selected(old('delivery_zone_id') == $zone->id)>
                                        {{ $zone->name }} (${{ number_format($zone->base_fee, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-600">Delivery Fee ($)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="delivery_fee" id="deliveryFee" step="0.01" min="0" class="form-control" value="{{ old('delivery_fee') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-600">Delivery Address <span class="text-danger">*</span></label>
                            <textarea name="delivery_address" class="form-control @error('delivery_address') is-invalid @enderror" rows="3" required>{{ old('delivery_address') }}</textarea>
                            @error('delivery_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-600">Notes</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-circle me-1"></i>Create Delivery
                        </button>
                        <a href="{{ route('deliveries.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('zoneSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const fee = selectedOption.getAttribute('data-fee');
    if (fee) {
        document.getElementById('deliveryFee').value = fee;
    }
});
</script>
@endpush
