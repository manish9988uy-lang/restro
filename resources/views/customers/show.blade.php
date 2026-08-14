@extends('layouts.app')
@section('title', $customer->name)
@section('page-title', 'Customer Profile')

@section('content')
<div class="row g-4">
    <!-- Profile card -->
    <div class="col-lg-4">
        <div class="card text-center mb-4">
            <div class="card-body p-4">
                <div style="width:72px;height:72px;background:linear-gradient(135deg,#FF6B35,#ff9f7c);
                     border-radius:50%;display:flex;align-items:center;justify-content:center;
                     color:#fff;font-weight:700;font-size:1.8rem;margin:0 auto 1rem;">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
                <h5 class="fw-700">{{ $customer->name }}</h5>
                @if($customer->email)
                <div class="text-muted small"><i class="bi bi-envelope me-1"></i>{{ $customer->email }}</div>
                @endif
                @if($customer->phone)
                <div class="text-muted small"><i class="bi bi-phone me-1"></i>{{ $customer->phone }}</div>
                @endif
                @if($customer->address)
                <div class="text-muted small mt-1"><i class="bi bi-geo-alt me-1"></i>{{ $customer->address }}</div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="fw-700 fs-4 text-primary">{{ $customer->orders->count() }}</div>
                        <div class="text-muted small">Total Orders</div>
                    </div>
                    <div class="col-6">
                        <div class="fw-700 fs-4 text-success">${{ number_format($customer->total_spent, 2) }}</div>
                        <div class="text-muted small">Total Spent</div>
                    </div>
                    <div class="col-6">
                        <div class="fw-700 fs-4 text-warning">{{ $customer->visit_count }}</div>
                        <div class="text-muted small">Visits</div>
                    </div>
                    <div class="col-6">
                        <div class="fw-700 fs-4 text-info">{{ $customer->loyalty_points }}</div>
                        <div class="text-muted small">Loyalty Points</div>
                    </div>
                    <div class="col-12">
                        @php
                            $tierColors = [
                                'Bronze' => 'bg-secondary',
                                'Silver' => 'bg-secondary text-dark',
                                'Gold' => 'bg-warning text-dark',
                                'Platinum' => 'bg-info text-dark'
                            ];
                        @endphp
                        <span class="badge {{ $tierColors[$customer->membership_tier] ?? 'bg-secondary' }} fs-6">
                            {{ $customer->membership_tier }} Member
                        </span>
                        @if($customer->birthday)
                        <div class="text-muted small mt-1">
                            <i class="bi bi-calendar-heart me-1"></i>
                            Birthday: {{ $customer->birthday->format('F j') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders history -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header fw-600">
                <i class="bi bi-clock-history me-2"></i>Order History
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Invoice</th><th>Items</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($customer->orders->sortByDesc('created_at') as $order)
                        <tr>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="fw-600 small text-decoration-none">
                                    {{ $order->invoice_no }}
                                </a>
                            </td>
                            <td class="small">{{ $order->items->count() }} items</td>
                            <td class="fw-600 small text-success">${{ number_format($order->total, 2) }}</td>
                            <td><span class="badge bg-{{ $order->status_color }}">{{ ucfirst($order->order_status) }}</span></td>
                            <td class="text-muted small">{{ $order->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No orders yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
