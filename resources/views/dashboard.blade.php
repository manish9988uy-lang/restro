@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center mb-3">
                <div class="stat-icon me-3" style="background:#FFF0E8;">
                    <i class="bi bi-currency-dollar text-warning" style="color:#FF6B35!important;"></i>
                </div>
                <span class="stat-label">Today's Revenue</span>
            </div>
            <div class="stat-value">Rs. {{ number_format($todayRevenue, 2) }}</div>
            <div class="stat-change text-success mt-1"><i class="bi bi-arrow-up-short"></i> Sales today</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center mb-3">
                <div class="stat-icon me-3" style="background:#E8F4FF;">
                    <i class="bi bi-receipt" style="color:#3b82f6;"></i>
                </div>
                <span class="stat-label">Today's Orders</span>
            </div>
            <div class="stat-value">{{ $todayOrders }}</div>
            <div class="stat-change text-primary mt-1">
                <i class="bi bi-clock"></i> {{ $pendingOrders }} pending
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center mb-3">
                <div class="stat-icon me-3" style="background:#F0FFF4;">
                    <i class="bi bi-people" style="color:#22c55e;"></i>
                </div>
                <span class="stat-label">Total Customers</span>
            </div>
            <div class="stat-value">{{ $totalCustomers }}</div>
            <div class="stat-change text-success mt-1"><i class="bi bi-person-plus"></i> Registered</div>
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
            <div class="stat-value">{{ $availableTables }} <small class="fs-5 text-muted">/ {{ $totalTables }}</small></div>
            <div class="stat-change text-warning mt-1"><i class="bi bi-circle-fill" style="font-size:.6rem;"></i> Free now</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center mb-3">
                <div class="stat-icon me-3" style="background:#E8F8FF;">
                    <i class="bi bi-calendar-check" style="color:#8b5cf6;"></i>
                </div>
                <span class="stat-label">Today's Reservations</span>
            </div>
            <div class="stat-value">{{ $todayReservations }}</div>
            <div class="stat-change text-purple mt-1">
                <i class="bi bi-clock"></i> {{ $pendingReservations }} pending
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
            <div class="stat-value">{{ $pendingOrders }}</div>
            <div class="stat-change text-danger mt-1">
                <i class="bi bi-hourglass-split"></i> In progress
            </div>
        </div>
    </div>
</div>

<!-- Charts row 1 -->
<div class="row g-3 mb-4">
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-bar-chart-line me-2 text-primary"></i>Revenue — Last 7 Days</span>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart me-2 text-warning"></i>Category Sales
            </div>
            <div class="card-body">
                <canvas id="categoryChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card h-100 border-danger">
            <div class="card-header bg-danger text-white">
                <i class="bi bi-exclamation-triangle me-2"></i>Low Stock
            </div>
            <div class="card-body p-0">
                @forelse($lowStockProducts as $product)
                <div class="px-4 py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="fw-600 small">{{ $product->name }}</div>
                        <span class="badge bg-danger text-xs">{{ $product->stock }} left</span>
                    </div>
                    <div class="text-muted small">
                        <i class="bi bi-tag me-1"></i> {{ $product->category?->name }}
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">All products are in stock</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Charts row 2 -->
<div class="row g-3 mb-4">
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-people me-2 text-success"></i>Customer Growth
            </div>
            <div class="card-body">
                <canvas id="customerGrowthChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-clock-history me-2 text-info"></i>Peak Hours
            </div>
            <div class="card-body">
                <canvas id="peakHoursChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Inventory Usage -->
<div class="row g-3 mb-4">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-box-seam me-2 text-secondary"></i>Inventory Usage (Stock Levels)
            </div>
            <div class="card-body">
                <canvas id="inventoryUsageChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Bottom row -->
<div class="row g-3">
    <!-- Recent Orders -->
    <div class="col-xl-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt-cutoff me-2"></i>Recent Orders</span>
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="text-decoration-none fw-500 small">
                                    {{ $order->invoice_no }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td class="text-center py-4 text-muted">No orders yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Active Tables -->
    <div class="col-xl-3">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-layout-three-columns me-2 text-orange"></i>Active Tables
            </div>
            <div class="card-body p-0">
                @forelse($activeTables as $table)
                <div class="px-4 py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="fw-600 small">{{ $table->name }}</div>
                        <span class="badge bg-warning text-xs">Occupied</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">All tables are available</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Upcoming Reservations -->
    <div class="col-xl-3">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-calendar-event me-2 text-purple"></i>Upcoming Reservations
            </div>
            <div class="card-body p-0">
                @forelse($upcomingReservations as $reservation)
                <div class="px-4 py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="fw-600 small">{{ $reservation->customer?->name }}</div>
                        <span class="badge bg-secondary text-xs">{{ $reservation->guest_count }} guests</span>
                    </div>
                    <div class="text-muted small mb-1">
                        <i class="bi bi-clock me-1"></i> {{ $reservation->reservation_time->format('g:i A') }}
                    </div>
                    @if($reservation->table)
                    <div class="text-muted small">
                        <i class="bi bi-layout-three-columns me-1"></i> {{ $reservation->table?->name }}
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-4 text-muted small">No upcoming reservations</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Items -->
    <div class="col-xl-3">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-star me-2 text-warning"></i>Top Selling Items
            </div>
            <div class="card-body p-0">
                @forelse($topItems as $i => $item)
                <div class="d-flex align-items-center px-4 py-3 {{ $i < $topItems->count()-1 ? 'border-bottom' : '' }}">
                    <div class="me-3 fw-700 text-muted" style="width:24px;">{{ $i+1 }}</div>
                    <div class="flex-grow-1">
                        <div class="fw-600 small">{{ $item->name }}</div>
                        <div class="text-muted" style="font-size:.75rem;">{{ $item->total_qty }} sold</div>
                    </div>
                    <div class="fw-700 text-success small">Rs. {{ number_format($item->revenue, 2) }}</div>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">No sales data yet</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
const chartLabels = @json($chartLabels);
const chartData = @json($chartData);

// Revenue Chart
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: chartLabels,
        datasets: [{
            label: 'Revenue (Rs.)',
            data: chartData,
            backgroundColor: 'rgba(255,107,53,.15)',
            borderColor: '#FF6B35',
            borderWidth: 2,
            borderRadius: 8,
            hoverBackgroundColor: 'rgba(255,107,53,.3)',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f0f2f5' }, ticks: { callback: v => 'Rs. ' + v } },
            x: { grid: { display: false } }
        }
    }
});

// Category Sales Chart
const categoryLabels = @json($categorySales->pluck('name'));
const categoryData = @json($categorySales->pluck('total_sales'));
new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: categoryLabels,
        datasets: [{
            data: categoryData,
            backgroundColor: [
                '#FF6B35', '#0dcaf0', '#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1'
            ],
            borderWidth: 0,
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } },
        cutout: '70%'
    }
});

// Customer Growth Chart
const customerGrowthLabels = [];
const customerGrowthData = [];
const customerGrowthRaw = @json($customerGrowth);
for (let i = 6; i >= 0; i--) {
    const date = new Date();
    date.setDate(date.getDate() - i);
    const dateStr = date.toISOString().split('T')[0];
    customerGrowthLabels.push(date.toLocaleDateString('en-US', { weekday: 'short' }));
    customerGrowthData.push(customerGrowthRaw[dateStr] || 0);
}
new Chart(document.getElementById('customerGrowthChart'), {
    type: 'line',
    data: {
        labels: customerGrowthLabels,
        datasets: [{
            label: 'New Customers',
            data: customerGrowthData,
            borderColor: '#198754',
            backgroundColor: 'rgba(25, 135, 84, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#198754'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f0f2f5' }, ticks: { stepSize: 1 } },
            x: { grid: { display: false } }
        }
    }
});

// Peak Hours Chart
const peakHoursLabels = [];
const peakHoursData = [];
const peakHoursRaw = @json($peakHours);
for (let i = 0; i < 24; i++) {
    peakHoursLabels.push(i + ':00');
    peakHoursData.push(peakHoursRaw[i] || 0);
}
new Chart(document.getElementById('peakHoursChart'), {
    type: 'bar',
    data: {
        labels: peakHoursLabels,
        datasets: [{
            label: 'Orders',
            data: peakHoursData,
            backgroundColor: 'rgba(13, 110, 253, 0.15)',
            borderColor: '#0d6efd',
            borderWidth: 2,
            borderRadius: 4,
            hoverBackgroundColor: 'rgba(13, 110, 253, 0.3)'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f0f2f5' } },
            x: { grid: { display: false }, ticks: { maxTicksLimit: 8 } }
        }
    }
});

// Inventory Usage Chart
new Chart(document.getElementById('inventoryUsageChart'), {
    type: 'bar',
    data: {
        labels: @json($inventoryUsageLabels),
        datasets: [{
            label: 'Stock Level',
            data: @json($inventoryUsageData),
            backgroundColor: function(context) {
                const value = context.dataset.data[context.dataIndex];
                if (value < 10) return 'rgba(220, 53, 69, 0.7)'; // danger
                if (value < 25) return 'rgba(255, 193, 7, 0.7)'; // warning
                return 'rgba(25, 135, 84, 0.7)'; // success
            },
            borderColor: function(context) {
                const value = context.dataset.data[context.dataIndex];
                if (value < 10) return '#dc3545';
                if (value < 25) return '#ffc107';
                return '#198754';
            },
            borderWidth: 2,
            borderRadius: 8,
            hoverBackgroundColor: 'rgba(0, 0, 0, 0.1)'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f0f2f5' } },
            x: { grid: { display: false } }
        }
    }
});

// Status doughnut
const statusData = @json($orderStats);
</script>
@endpush
