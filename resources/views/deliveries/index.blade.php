@extends('layouts.app')
@section('title', 'Deliveries')
@section('page-title', 'Deliveries')

@section('content')
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(['pending','assigned','picked_up','in_transit','delivered','cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') == $status)>{{ ucwords(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </div>
            <div class="col-md-3">
                <a href="{{ route('deliveries.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-truck me-2"></i>Deliveries</span>
        <a href="{{ route('deliveries.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
            <i class="bi bi-plus-circle me-1"></i>New Delivery
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Order</th>
                    <th>Rider</th>
                    <th>Address</th>
                    <th>Fee</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveries as $delivery)
                <tr>
                    <td>{{ $delivery->id }}</td>
                    <td>
                        <a href="{{ route('orders.show', $delivery->order) }}" class="fw-600 small text-decoration-none">
                            {{ $delivery->order->invoice_no }}
                        </a>
                    </td>
                    <td>{{ $delivery->rider ? $delivery->rider->name : '—' }}</td>
                    <td class="small">{{ Str::limit($delivery->delivery_address, 40) }}</td>
                    <td class="fw-600 small">Rs. {{ number_format($delivery->delivery_fee, 2) }}</td>
                    <td>
                        @php $statusColors = ['pending'=>'warning','assigned'=>'info','picked_up'=>'primary','in_transit'=>'secondary','delivered'=>'success','cancelled'=>'danger'] @endphp
                        <span class="badge bg-{{ $statusColors[$delivery->status] }}">{{ ucwords(str_replace('_', ' ', $delivery->status)) }}</span>
                    </td>
                    <td class="text-muted small">{{ $delivery->created_at->format('M d, H:i') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('deliveries.show', $delivery) }}" class="btn btn-sm btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('deliveries.edit', $delivery) }}" class="btn btn-sm btn-outline-info" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('deliveries.destroy', $delivery) }}" class="d-inline" onsubmit="return confirm('Delete this delivery?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-5 text-muted">No deliveries found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($deliveries->hasPages())
    <div class="card-footer bg-white border-top-0 pt-0">{{ $deliveries->links() }}</div>
    @endif
</div>
@endsection
