@extends('layouts.app')
@section('title', $rider->name)
@section('page-title', 'Rider Details')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card text-center mb-4">
            <div class="card-body p-4">
                <div style="width:72px;height:72px;background:linear-gradient(135deg,#198754,#20c997);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.8rem;margin:0 auto 1rem;">
                    {{ strtoupper(substr($rider->name, 0, 1)) }}
                </div>
                <h5 class="fw-700">{{ $rider->name }}</h5>
                <div class="text-muted small"><i class="bi bi-telephone me-1"></i>{{ $rider->phone }}</div>
                @if($rider->email)
                <div class="text-muted small"><i class="bi bi-envelope me-1"></i>{{ $rider->email }}</div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="fw-700 fs-4 text-primary">{{ $rider->deliveries->count() }}</div>
                        <div class="text-muted small">Total Deliveries</div>
                    </div>
                    <div class="col-6">
                        @php $statusColors = ['available' => 'success', 'busy' => 'warning', 'off' => 'secondary'] @endphp
                        <span class="badge bg-{{ $statusColors[$rider->status] }} fs-6">{{ ucfirst($rider->status) }}</span>
                    </div>
                    @if($rider->vehicle_type)
                    <div class="col-12">
                        <div class="text-muted small">Vehicle</div>
                        <div class="fw-600">{{ $rider->vehicle_type }} @if($rider->vehicle_plate)({{ $rider->vehicle_plate }})@endif</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header fw-600">
                <i class="bi bi-clock-history me-2"></i>Delivery History
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Address</th>
                            <th>Fee</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rider->deliveries as $delivery)
                        <tr>
                            <td>
                                <a href="{{ route('deliveries.show', $delivery) }}" class="fw-600 small text-decoration-none">
                                    {{ $delivery->order->invoice_no }}
                                </a>
                            </td>
                            <td class="small">{{ Str::limit($delivery->delivery_address, 30) }}</td>
                            <td class="fw-600 small">Rs. {{ number_format($delivery->delivery_fee, 2) }}</td>
                            <td>
                                @php $statusColors = ['pending'=>'warning','assigned'=>'info','picked_up'=>'primary','in_transit'=>'secondary','delivered'=>'success','cancelled'=>'danger'] @endphp
                                <span class="badge bg-{{ $statusColors[$delivery->status] }}">{{ ucwords(str_replace('_', ' ', $delivery->status)) }}</span>
                            </td>
                            <td class="text-muted small">{{ $delivery->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No deliveries yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="d-flex gap-2 mt-4">
    <a href="{{ route('riders.edit', $rider) }}" class="btn btn-outline-info">
        <i class="bi bi-pencil me-1"></i>Edit
    </a>
    <form method="POST" action="{{ route('riders.destroy', $rider) }}" onsubmit="return confirm('Delete this rider?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-outline-danger">
            <i class="bi bi-trash me-1"></i>Delete
        </button>
    </form>
</div>
@endsection
