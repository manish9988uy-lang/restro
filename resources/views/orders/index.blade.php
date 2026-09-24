@extends('layouts.app')
@section('title', 'Orders')
@section('page-title', 'Orders')

@section('content')
<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#EFF6FF;"><i class="bi bi-receipt" style="color:#3b82f6;"></i></div>
                <div>
                    <div class="stat-label">Total Orders</div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#FFFBEB;"><i class="bi bi-calendar-day" style="color:#f59e0b;"></i></div>
                <div>
                    <div class="stat-label">Today</div>
                    <div class="stat-value">{{ $stats['today'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#FFF0E8;"><i class="bi bi-hourglass-split" style="color:#FF6B35;"></i></div>
                <div>
                    <div class="stat-label">Pending</div>
                    <div class="stat-value">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#F0FFF4;"><i class="bi bi-currency-dollar" style="color:#22c55e;"></i></div>
                <div>
                    <div class="stat-label">Revenue</div>
                    <div class="stat-value" style="font-size:1.3rem;">Rs. {{ number_format($stats['revenue'], 0) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search invoice number..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                @foreach(['pending','preparing','ready','completed','cancelled'] as $s)
                                <option value="{{ $s }}" @selected(request('status') == $s)>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="scheduled" class="form-select">
                                <option value="">All Orders</option>
                                <option value="1" @selected(request('scheduled') == '1')>Scheduled Only</option>
                                <option value="0" @selected(request('scheduled') == '0')>Non-Scheduled Only</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-search me-1"></i>Search
                </button>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-receipt-cutoff me-2"></i>Order List</span>
        <a href="{{ route('pos.index') }}" class="btn btn-sm btn-primary rounded-pill px-3">
            <i class="bi bi-plus-circle me-1"></i>New Order
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th>Table</th>
                    <th>Type</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="text-muted small">{{ $orders->firstItem() + $loop->index }}</td>
                    <td>
                        <a href="{{ route('orders.show', $order) }}" class="fw-600 text-decoration-none small">
                            {{ $order->invoice_no }}
                        </a>
                    </td>
                    <td class="small">{{ $order->customer?->name ?? 'Walk-in' }}</td>
                    <td class="small">{{ $order->table?->name ?? '—' }}</td>
                    <td>
                        @php $typeIcon = ['dine_in'=>'bi-house','takeaway'=>'bi-bag','delivery'=>'bi-bicycle']; @endphp
                        <span class="badge bg-light text-dark small">
                            <i class="{{ $typeIcon[$order->order_type] ?? 'bi-receipt' }} me-1"></i>
                            {{ ucfirst(str_replace('_',' ',$order->order_type)) }}
                        </span>
                        @if($order->scheduled_at)
                        <span class="badge bg-info text-white small ms-1" title="Scheduled for {{ $order->scheduled_at->format('M d, H:i') }}">
                            <i class="bi bi-clock me-1"></i>Scheduled
                        </span>
                        @endif
                    </td>
                    <td class="small text-center">{{ $order->items->count() }}</td>
                    <td class="fw-600 small">Rs. {{ number_format($order->total, 2) }}</td>
                    <td>
                        @if($order->payment_status == 'paid')
                            <span class="badge bg-success-subtle text-success">Paid</span>
                        @elseif($order->payment_status == 'partial')
                            <span class="badge bg-warning-subtle text-warning">Partial</span>
                        @elseif($order->payment_status == 'refunded')
                            <span class="badge bg-danger-subtle text-danger">Refunded</span>
                        @elseif($order->payment_status == 'partial_refund')
                            <span class="badge bg-warning-subtle text-warning">Partial Refund</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger">Unpaid</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $order->status_color }}">{{ ucfirst($order->order_status) }}</span>
                    </td>
                    <td class="text-muted small">{{ $order->created_at->format('M d, H:i') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-outline-info" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('orders.receipt', $order) }}" class="btn btn-sm btn-outline-secondary" title="Receipt">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="border-radius:10px;min-width:160px;">
                                    @foreach(['preparing'=>'info','ready'=>'primary','completed'=>'success','cancelled'=>'danger'] as $s => $c)
                                    <li>
                                        <form method="POST" action="{{ route('orders.status', $order) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="order_status" value="{{ $s }}">
                                            <button type="submit" class="dropdown-item small">
                                                <span class="badge bg-{{ $c }} me-2">{{ ucfirst($s) }}</span>Mark as {{ ucfirst($s) }}
                                            </button>
                                        </form>
                                    </li>
                                    @endforeach
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <form method="POST" action="{{ route('orders.repeat', $order) }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item small">
                                                <i class="bi bi-repeat me-1"></i>Repeat Order
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('orders.destroy', $order) }}"
                                              onsubmit="return confirm('Delete this order?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item small text-danger">
                                                <i class="bi bi-trash me-1"></i>Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center py-5 text-muted">
                        <i class="bi bi-receipt d-block mb-2" style="font-size:2rem;"></i>
                        No orders found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="card-footer bg-white border-top-0 pt-0">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
