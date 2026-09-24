@extends('layouts.app')
@section('title', 'Orders Management')
@section('page-title', 'Orders Management')

@section('content')
<!-- Stats Overview -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#EFF6FF;"><i class="bi bi-receipt-cutoff" style="color:#3b82f6;"></i></div>
                <div>
                    <div class="stat-label">Total Orders</div>
                    <div class="stat-value">{{ number_format($stats['total']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#FFFBEB;"><i class="bi bi-calendar-event" style="color:#f59e0b;"></i></div>
                <div>
                    <div class="stat-label">Today's Volume</div>
                    <div class="stat-value">{{ number_format($stats['today']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#FFF0E8;"><i class="bi bi-hourglass-split" style="color:#FF6B35;"></i></div>
                <div>
                    <div class="stat-label">Active / Queue</div>
                    <div class="stat-value text-primary">{{ number_format($stats['pending']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#F0FFF4;"><i class="bi bi-cash-stack" style="color:#22c55e;"></i></div>
                <div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value text-success" style="font-size:1.4rem;">Rs. {{ number_format($stats['revenue'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filters -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('orders.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1 fw-bold">Search Invoice / Ref</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="e.g. INV-..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1 fw-bold">Order Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(['pending' => 'Pending', 'preparing' => 'Preparing', 'ready' => 'Ready', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $k => $v)
                    <option value="{{ $k }}" @selected(request('status') == $k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1 fw-bold">Order Type</label>
                <select name="scheduled" class="form-select">
                    <option value="">All Timing</option>
                    <option value="1" @selected(request('scheduled') == '1')>Scheduled Pre-Orders</option>
                    <option value="0" @selected(request('scheduled') == '0')>Immediate Orders</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1 fw-bold">Date Filter</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-funnel-fill me-1"></i> Apply Filter
                </button>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary" title="Reset Filters">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Order Table Card -->
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-receipt text-primary fs-5"></i>
            <span class="fw-bold">Orders Log</span>
            <span class="badge bg-light text-dark border">Showing {{ $orders->count() }} of {{ $orders->total() }}</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pos.index') }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Launch POS
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Invoice No</th>
                    <th>Customer</th>
                    <th>Table / Dining</th>
                    <th>Type</th>
                    <th class="text-center">Items</th>
                    <th>Total Amount</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Placed Time</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('orders.show', $order) }}" class="fw-bold text-decoration-none text-primary">
                            {{ $order->invoice_no }}
                        </a>
                        @if($order->notes)
                            <div class="text-muted small" title="{{ $order->notes }}" style="font-size:0.75rem;">
                                <i class="bi bi-chat-text me-1 text-warning"></i> Note attached
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold text-dark small">{{ $order->customer?->name ?? 'Walk-in Customer' }}</div>
                        @if($order->customer?->phone)
                            <div class="text-muted small" style="font-size:0.75rem;">{{ $order->customer->phone }}</div>
                        @endif
                    </td>
                    <td>
                        @if($order->table)
                            <span class="badge bg-secondary-subtle text-secondary border">
                                <i class="bi bi-layout-three-columns me-1"></i>{{ $order->table->name }}
                            </span>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td>
                        @php $typeIcon = ['dine_in'=>'bi-house-door','takeaway'=>'bi-bag-check','delivery'=>'bi-bicycle']; @endphp
                        <span class="badge bg-light text-dark border small">
                            <i class="{{ $typeIcon[$order->order_type] ?? 'bi-receipt' }} me-1"></i>
                            {{ ucfirst(str_replace('_',' ',$order->order_type)) }}
                        </span>
                        @if($order->scheduled_at)
                        <span class="badge bg-info text-white small ms-1" title="Scheduled: {{ $order->scheduled_at->format('M d, H:i') }}">
                            <i class="bi bi-clock me-1"></i>Pre-order
                        </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge bg-light text-dark rounded-pill border">{{ $order->items->count() }} items</span>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">Rs. {{ number_format($order->total, 2) }}</div>
                    </td>
                    <td>
                        @if($order->payment_status == 'paid')
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Paid</span>
                        @elseif($order->payment_status == 'partial')
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Partial</span>
                        @elseif($order->payment_status == 'refunded')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Refunded</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Unpaid</span>
                        @endif
                        <div class="text-muted small" style="font-size:0.72rem;">{{ ucfirst($order->payment_type ?? 'Cash') }}</div>
                    </td>
                    <td>
                        @php
                            $stColor = match($order->order_status) {
                                'completed' => 'success',
                                'ready' => 'info',
                                'preparing' => 'primary',
                                'cancelled' => 'danger',
                                default => 'warning'
                            };
                        @endphp
                        <span class="badge bg-{{ $stColor }} px-2 py-1">{{ ucfirst($order->order_status) }}</span>
                    </td>
                    <td>
                        <div class="small fw-500 text-dark">{{ $order->created_at->format('M d, g:i A') }}</div>
                        <div class="text-muted small" style="font-size:0.72rem;">{{ $order->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="text-end">
                        <div class="btn-group">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-secondary" title="View details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('orders.receipt', $order) }}" class="btn btn-sm btn-outline-primary" target="_blank" title="Print Receipt">
                                <i class="bi bi-printer"></i>
                            </a>
                            <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-outline-dark" title="Edit Order">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-5 text-muted">
                        <i class="bi bi-receipt-cutoff fs-2 d-block mb-2 opacity-50"></i>
                        No orders match your filter criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="card-footer bg-white border-top p-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results</span>
            {{ $orders->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
