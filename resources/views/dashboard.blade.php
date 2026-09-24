@extends('layouts.app')
@section('title', 'Executive Dashboard')
@section('page-title', 'Overview Dashboard')

@section('content')
<!-- Quick Action Bar -->
<div class="d-flex flex-wrap gap-2 mb-4 align-items-center justify-content-between bg-white p-3 rounded-4 shadow-sm border border-light">
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-primary-subtle text-primary p-2 px-3 rounded-pill fw-bold">
            <i class="bi bi-shop me-1"></i> Live Operations
        </span>
        <span class="text-muted small d-none d-md-inline">Welcome back, <strong>{{ auth()->user()->name }}</strong></span>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('pos.index') }}" class="btn btn-primary btn-sm px-3 rounded-pill shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> New POS Order
        </a>
        <a href="{{ route('kitchen.index') }}" class="btn btn-outline-dark btn-sm px-3 rounded-pill">
            <i class="bi bi-fire me-1 text-danger"></i> Kitchen Display ({{ $pendingOrders }})
        </a>
        <a href="{{ route('tables.floor') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
            <i class="bi bi-buildings me-1 text-primary"></i> Floor Plan
        </a>
        <a href="{{ route('expenses.create') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
            <i class="bi bi-wallet2 me-1 text-warning"></i> Add Expense
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center mb-3">
                <div class="stat-icon me-3" style="background:#FFF0E8;">
                    <i class="bi bi-cash-stack" style="color:#FF6B35;"></i>
                </div>
                <span class="stat-label">Today's Revenue</span>
            </div>
            <div class="stat-value text-primary">Rs. {{ number_format($todayRevenue, 2) }}</div>
            <div class="stat-change text-success mt-2">
                <i class="bi bi-graph-up-arrow"></i> <span>Daily gross sales</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center mb-3">
                <div class="stat-icon me-3" style="background:#E8F4FF;">
                    <i class="bi bi-receipt-cutoff" style="color:#3b82f6;"></i>
                </div>
                <span class="stat-label">Today's Orders</span>
            </div>
            <div class="stat-value">{{ $todayOrders }}</div>
            <div class="stat-change text-primary mt-2">
                <i class="bi bi-clock-history"></i> <span>{{ $pendingOrders }} in progress</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center mb-3">
                <div class="stat-icon me-3" style="background:#F0FFF4;">
                    <i class="bi bi-people-fill" style="color:#22c55e;"></i>
                </div>
                <span class="stat-label">Total Customers</span>
            </div>
            <div class="stat-value">{{ $totalCustomers }}</div>
            <div class="stat-change text-success mt-2">
                <i class="bi bi-person-check"></i> <span>Customer base</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center mb-3">
                <div class="stat-icon me-3" style="background:#FFF8E8;">
                    <i class="bi bi-layout-three-columns" style="color:#f59e0b;"></i>
                </div>
                <span class="stat-label">Available Tables</span>
            </div>
            <div class="stat-value">{{ $availableTables }} <span class="fs-6 text-muted fw-normal">/ {{ $totalTables }}</span></div>
            <div class="stat-change text-warning mt-2">
                <i class="bi bi-dot fs-5"></i> <span>{{ $activeTablesCount }} Occupied now</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center mb-3">
                <div class="stat-icon me-3" style="background:#F3E8FF;">
                    <i class="bi bi-calendar-check" style="color:#a855f7;"></i>
                </div>
                <span class="stat-label">Reservations</span>
            </div>
            <div class="stat-value">{{ $todayReservations }}</div>
            <div class="stat-change text-purple mt-2" style="color:#9333ea;">
                <i class="bi bi-calendar-event"></i> <span>{{ $pendingReservations }} pending</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center mb-3">
                <div class="stat-icon me-3" style="background:#FFF0F5;">
                    <i class="bi bi-fire" style="color:#ef4444;"></i>
                </div>
                <span class="stat-label">Kitchen Queue</span>
            </div>
            <div class="stat-value text-danger">{{ $pendingOrders }}</div>
            <div class="stat-change text-danger mt-2">
                <i class="bi bi-hourglass-split"></i> <span>Live tickets</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row 1: Sales & Category -->
<div class="row g-3 mb-4">
    <div class="col-xl-8">
        <div class="card h-100 shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-bar-chart-line me-2 text-primary fs-5"></i>
                    <span class="fw-bold">Weekly Revenue Trends</span>
                    <span class="text-muted small ms-2">(Last 7 Days in NPR)</span>
                </div>
                <span class="badge bg-primary-subtle text-primary">Rs. Currency</span>
            </div>
            <div class="card-body">
                <div style="height: 280px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-pie-chart me-2 text-warning fs-5"></i>
                    <span class="fw-bold">Category Sales Breakdown</span>
                </div>
            </div>
            <div class="card-body">
                <div style="height: 280px; position: relative;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Row 2: Peak Hours & Low Stock -->
<div class="row g-3 mb-4">
    <div class="col-xl-8">
        <div class="card h-100 shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-clock-history me-2 text-info fs-5"></i>
                    <span class="fw-bold">Peak Ordering Hours (24h Distribution)</span>
                </div>
                <span class="badge bg-info-subtle text-info">Traffic Trends</span>
            </div>
            <div class="card-body">
                <div style="height: 240px;">
                    <canvas id="peakHoursChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header d-flex align-items-center justify-content-between bg-danger-subtle text-danger">
                <div>
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <span class="fw-bold">Low Stock Warning</span>
                </div>
                <a href="{{ route('ingredients.index') }}" class="btn btn-xs btn-outline-danger rounded-pill">Manage</a>
            </div>
            <div class="card-body p-0">
                @forelse($lowStockProducts as $product)
                <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold text-dark small">{{ $product->name }}</div>
                        <div class="text-muted small" style="font-size:0.75rem;">
                            <i class="bi bi-tag me-1"></i> {{ $product->category?->name ?? 'Inventory Item' }}
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-danger rounded-pill">{{ $product->stock }} left</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted small">
                    <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>
                    All inventory stocks are healthy
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders & Top Items -->
<div class="row g-3 mb-4">
    <!-- Recent Orders (Expanded & Detailed) -->
    <div class="col-xl-8">
        <div class="card h-100 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-receipt-cutoff me-2 text-primary fs-5"></i>
                    <span class="fw-bold">Recent Customer Transactions</span>
                </div>
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    View All Orders <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Customer / Table</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="fw-bold text-decoration-none text-primary">
                                    {{ $order->invoice_no }}
                                </a>
                                <div class="text-muted" style="font-size:0.72rem;">{{ $order->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                <div class="fw-600 small">{{ $order->customer?->name ?? 'Walk-in Customer' }}</div>
                                @if($order->table)
                                    <span class="badge bg-secondary-subtle text-secondary small" style="font-size:0.7rem;">
                                        <i class="bi bi-layout-three-columns me-1"></i>{{ $order->table->name }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @php $typeIcon = ['dine_in'=>'bi-house','takeaway'=>'bi-bag','delivery'=>'bi-bicycle']; @endphp
                                <span class="badge bg-light text-dark border small" style="font-size:0.75rem;">
                                    <i class="{{ $typeIcon[$order->order_type] ?? 'bi-receipt' }} me-1"></i>
                                    {{ ucfirst(str_replace('_',' ', $order->order_type)) }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">Rs. {{ number_format($order->total, 2) }}</span>
                            </td>
                            <td>
                                @if($order->payment_status == 'paid')
                                    <span class="badge bg-success-subtle text-success">Paid</span>
                                @elseif($order->payment_status == 'partial')
                                    <span class="badge bg-warning-subtle text-warning">Partial</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Unpaid</span>
                                @endif
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
                                <span class="badge bg-{{ $stColor }}">{{ ucfirst($order->order_status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-secondary p-1 px-2" title="View details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('orders.receipt', $order) }}" class="btn btn-sm btn-outline-primary p-1 px-2 ms-1" target="_blank" title="Print receipt">
                                    <i class="bi bi-printer"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-receipt fs-2 d-block mb-2 opacity-50"></i>
                                No recent orders recorded yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Selling Items & Active Tables -->
    <div class="col-xl-4 d-flex flex-column gap-3">
        <!-- Top Selling Menu Items -->
        <div class="card shadow-sm flex-fill">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-star-fill me-2 text-warning fs-5"></i>
                    <span class="fw-bold">Top Selling Dishes</span>
                </div>
                <a href="{{ route('menu.index') }}" class="btn btn-xs btn-outline-secondary rounded-pill">Menu</a>
            </div>
            <div class="card-body p-0">
                @forelse($topItems as $i => $item)
                <div class="d-flex align-items-center px-3 py-2.5 {{ $i < $topItems->count()-1 ? 'border-bottom' : '' }}">
                    <div class="badge bg-light text-dark rounded-circle me-3 p-2 fw-bold" style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;">
                        {{ $i+1 }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold small text-dark">{{ $item->name }}</div>
                        <div class="text-muted small" style="font-size:0.75rem;">{{ $item->total_qty }} orders placed</div>
                    </div>
                    <div class="fw-bold text-success small">Rs. {{ number_format($item->revenue, 2) }}</div>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">No sales data recorded yet</div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Reservations -->
        <div class="card shadow-sm flex-fill">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-calendar-event me-2 text-purple fs-5" style="color:#9333ea;"></i>
                    <span class="fw-bold">Upcoming Bookings</span>
                </div>
                <a href="{{ route('reservations.index') }}" class="btn btn-xs btn-outline-secondary rounded-pill">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($upcomingReservations as $reservation)
                <div class="px-3 py-2.5 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold small text-dark">{{ $reservation->customer?->name ?? 'Guest' }}</div>
                        <div class="text-muted small" style="font-size:0.75rem;">
                            <i class="bi bi-clock me-1"></i> {{ $reservation->reservation_time->format('M d, g:i A') }}
                            @if($reservation->table)
                                • <i class="bi bi-layout-three-columns me-1"></i> {{ $reservation->table->name }}
                            @endif
                        </div>
                    </div>
                    <span class="badge bg-purple-subtle text-purple border" style="background:#f3e8ff;color:#7e22ce;">
                        {{ $reservation->guest_count }} Guests
                    </span>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">No upcoming reservations</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartLabels = @json($chartLabels);
    const chartData = @json($chartData);

    // 1. Revenue Chart
    const revEl = document.getElementById('revenueChart');
    if (revEl) {
        new Chart(revEl, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Revenue (Rs.)',
                    data: chartData,
                    backgroundColor: 'rgba(255, 107, 53, 0.25)',
                    borderColor: '#FF6B35',
                    borderWidth: 2,
                    borderRadius: 10,
                    hoverBackgroundColor: 'rgba(255, 107, 53, 0.55)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rs. ' + Number(context.raw).toLocaleString('en-US', {minimumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f0f2f5' },
                        ticks: {
                            callback: function(value) { return 'Rs. ' + value; }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 2. Category Sales Chart
    const categorySales = @json($categorySales);
    const catLabels = categorySales.map(c => c.name);
    const catData = categorySales.map(c => c.total_sales);

    const catEl = document.getElementById('categoryChart');
    if (catEl) {
        new Chart(catEl, {
            type: 'doughnut',
            data: {
                labels: catLabels.length > 0 ? catLabels : ['General'],
                datasets: [{
                    data: catData.length > 0 ? catData : [1],
                    backgroundColor: [
                        '#FF6B35', '#3B82F6', '#10B981', '#F59E0B',
                        '#8B5CF6', '#EC4899', '#6366F1', '#14B8A6'
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 14, font: { size: 11 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': Rs. ' + Number(context.raw).toLocaleString('en-US', {minimumFractionDigits: 2});
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    }

    // 3. Peak Hours Chart
    const peakHoursData = @json($peakHours);
    const hoursLabels = [];
    const hoursCounts = [];
    for (let h = 0; h < 24; h++) {
        const hour12 = (h % 12 === 0 ? 12 : h % 12) + (h >= 12 ? ' PM' : ' AM');
        hoursLabels.push(hour12);
        hoursCounts.push(peakHoursData[h] || 0);
    }

    const peakEl = document.getElementById('peakHoursChart');
    if (peakEl) {
        new Chart(peakEl, {
            type: 'line',
            data: {
                labels: hoursLabels,
                datasets: [{
                    label: 'Orders',
                    data: hoursCounts,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.12)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#3B82F6'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: '#f0f2f5' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 }, maxRotation: 45 }
                    }
                }
            }
        });
    }
});
</script>
@endpush
