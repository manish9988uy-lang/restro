@extends('layouts.app')

@section('title', 'Reports & Business Analytics')
@section('page-title', 'Reports & Analytics')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-bar-chart-line text-primary me-2"></i>Reports & Analytics</h4>
        <p class="text-muted small mb-0">Sales, financial profit & loss, inventory usage, and tax breakdown</p>
    </div>
    <div class="dropdown">
        <button class="btn btn-outline-primary rounded-pill px-4 dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-download me-1"></i> Export Report (CSV)
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
            <li><a class="dropdown-item" href="{{ route('reports.export', ['type' => 'sales', 'from' => $from, 'to' => $to]) }}"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Sales Report CSV</a></li>
            <li><a class="dropdown-item" href="{{ route('reports.export', ['type' => 'products', 'from' => $from, 'to' => $to]) }}"><i class="bi bi-box-seam me-2"></i>Product Sales CSV</a></li>
            <li><a class="dropdown-item" href="{{ route('reports.export', ['type' => 'inventory', 'from' => $from, 'to' => $to]) }}"><i class="bi bi-tags me-2"></i>Inventory Valuation CSV</a></li>
        </ul>
    </div>
</div>

<!-- Date Filter Bar -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-2 align-items-center">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="col-md-5">
                <div class="btn-group w-100" role="group">
                    <a href="{{ route('reports.index', ['tab' => $tab, 'range' => 'today']) }}" class="btn btn-sm {{ $range === 'today' ? 'btn-primary' : 'btn-outline-secondary' }}">Today</a>
                    <a href="{{ route('reports.index', ['tab' => $tab, 'range' => 'week']) }}" class="btn btn-sm {{ $range === 'week' ? 'btn-primary' : 'btn-outline-secondary' }}">This Week</a>
                    <a href="{{ route('reports.index', ['tab' => $tab, 'range' => 'month']) }}" class="btn btn-sm {{ $range === 'month' ? 'btn-primary' : 'btn-outline-secondary' }}">This Month</a>
                    <a href="{{ route('reports.index', ['tab' => $tab, 'range' => 'year']) }}" class="btn btn-sm {{ $range === 'year' ? 'btn-primary' : 'btn-outline-secondary' }}">This Year</a>
                </div>
            </div>
            <div class="col-md-3">
                <input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-filter"></i></button>
            </div>
        </form>
    </div>
</div>

<!-- Key KPI Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value text-primary">${{ number_format($totalRevenue, 2) }}</div>
            <small class="text-muted">{{ $completedCount }} completed orders</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Average Order Value</div>
            <div class="stat-value">${{ number_format($avgOrderValue, 2) }}</div>
            <small class="text-muted">Per completed order</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label text-success">Estimated Gross Profit</div>
            <div class="stat-value text-success">${{ number_format($grossProfit, 2) }}</div>
            <small class="text-muted">Revenue - Recipe COGS</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label text-danger">Total VAT Collected</div>
            <div class="stat-value text-danger">${{ number_format($totalVat, 2) }}</div>
            <small class="text-muted">10% VAT tax rate</small>
        </div>
    </div>
</div>

<!-- Navigation Tabs -->
<ul class="nav nav-tabs mb-4" id="reportTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'sales' ? 'active fw-bold' : '' }}" href="{{ route('reports.index', ['tab' => 'sales', 'from' => $from, 'to' => $to]) }}">
            <i class="bi bi-graph-up me-1"></i> Sales Summary
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'products' ? 'active fw-bold' : '' }}" href="{{ route('reports.index', ['tab' => 'products', 'from' => $from, 'to' => $to]) }}">
            <i class="bi bi-box-seam me-1"></i> Categories & Products
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'inventory' ? 'active fw-bold' : '' }}" href="{{ route('reports.index', ['tab' => 'inventory', 'from' => $from, 'to' => $to]) }}">
            <i class="bi bi-tags me-1"></i> Inventory & Purchases
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'customers' ? 'active fw-bold' : '' }}" href="{{ route('reports.index', ['tab' => 'customers', 'from' => $from, 'to' => $to]) }}">
            <i class="bi bi-people me-1"></i> Customers & Staff
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'profit' ? 'active fw-bold' : '' }}" href="{{ route('reports.index', ['tab' => 'profit', 'from' => $from, 'to' => $to]) }}">
            <i class="bi bi-cash-coin me-1"></i> Profit & Loss (P&L)
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'cashflow' ? 'active fw-bold' : '' }}" href="{{ route('reports.index', ['tab' => 'cashflow', 'from' => $from, 'to' => $to]) }}">
            <i class="bi bi-bank me-1"></i> Cash Flow
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'taxes' ? 'active fw-bold' : '' }}" href="{{ route('reports.index', ['tab' => 'taxes', 'from' => $from, 'to' => $to]) }}">
            <i class="bi bi-receipt me-1"></i> Tax & VAT Reports
        </a>
    </li>
</ul>

<!-- Tab Content -->
@if($tab === 'sales')
<div class="card mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-calendar-event me-2"></i>Daily Sales Trend</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Orders Count</th>
                        <th>VAT Collected ($)</th>
                        <th>Discounts ($)</th>
                        <th class="pe-4 text-end">Total Revenue ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salesTrend as $st)
                    <tr>
                        <td class="ps-4 fw-bold">{{ $st->date }}</td>
                        <td><span class="badge bg-secondary rounded-pill">{{ $st->orders }} orders</span></td>
                        <td>${{ number_format($st->vat, 2) }}</td>
                        <td class="text-danger">${{ number_format($st->discount, 2) }}</td>
                        <td class="pe-4 text-end fw-bold text-success">${{ number_format($st->revenue, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">No sales data found for the selected period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@elseif($tab === 'products')
<div class="row g-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Category Sales Performance</h6></div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">Category</th>
                            <th>Qty Sold</th>
                            <th class="pe-3 text-end">Revenue ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categorySales as $cs)
                        <tr>
                            <td class="ps-3 fw-bold">{{ $cs->category_name }}</td>
                            <td>{{ number_format($cs->qty) }}</td>
                            <td class="pe-3 text-end fw-bold">${{ number_format($cs->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Top 20 Menu Items Sold</h6></div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">Product Name</th>
                            <th>Qty Sold</th>
                            <th class="pe-3 text-end">Revenue ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productSales as $ps)
                        <tr>
                            <td class="ps-3 fw-bold">{{ $ps->name }}</td>
                            <td>{{ number_format($ps->qty) }}</td>
                            <td class="pe-3 text-end fw-bold text-success">${{ number_format($ps->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@elseif($tab === 'inventory')
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0 text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Low Stock Ingredients Alert</h6></div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">Ingredient</th>
                            <th>Stock</th>
                            <th>Threshold</th>
                            <th class="pe-3 text-end">Unit Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventoryStats['low_stock_items'] as $lsi)
                        <tr>
                            <td class="ps-3 fw-bold">{{ $lsi->name }}</td>
                            <td class="fw-bold text-danger">{{ $lsi->current_stock }} {{ $lsi->unit }}</td>
                            <td>{{ $lsi->alert_threshold }} {{ $lsi->unit }}</td>
                            <td class="pe-3 text-end">${{ number_format($lsi->cost_per_unit, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-3 text-muted">All ingredient stock levels are healthy!</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Inventory & PO Financial Summary</h6></div>
            <div class="card-body">
                <div class="vstack gap-3 fs-6">
                    <div class="d-flex justify-content-between">
                        <span>Total Inventory Stock Valuation:</span>
                        <strong class="text-primary">${{ number_format($inventoryStats['total_valuation'], 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Purchases (Received POs):</span>
                        <strong class="text-dark">${{ number_format($inventoryStats['purchase_cost'], 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Waste & Spoilage Cost Loss:</span>
                        <strong class="text-danger">${{ number_format($inventoryStats['waste_cost'], 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@elseif($tab === 'customers')
<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Top Spending Customers</h6></div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">Customer</th>
                            <th>Completed Visits</th>
                            <th>Tier</th>
                            <th class="pe-3 text-end">Total Spent ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topCustomers as $c)
                        <tr>
                            <td class="ps-3 fw-bold">{{ $c->name }}</td>
                            <td>{{ $c->orders_count }} visits</td>
                            <td><span class="badge bg-warning text-dark">{{ $c->membership_tier ?? 'Bronze' }}</span></td>
                            <td class="pe-3 text-end fw-bold text-success">${{ number_format($c->total_spent, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Employee Sales Performance</h6></div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">Employee</th>
                            <th>Role</th>
                            <th>Orders Placed</th>
                            <th class="pe-3 text-end">Sales ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employeeSales as $e)
                        <tr>
                            <td class="ps-3 fw-bold">{{ $e->name }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $e->getRoleNames()->first() ?? 'Staff' }}</span></td>
                            <td>{{ $e->orders_count }}</td>
                            <td class="pe-3 text-end fw-bold text-primary">${{ number_format($e->orders_sum_total ?? 0, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@elseif($tab === 'profit')
<div class="row g-4">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header bg-white py-3"><h5 class="fw-bold mb-0 text-center text-primary">Financial Profit & Loss Statement</h5></div>
            <div class="card-body p-4">
                <table class="table table-borderless fs-5 mb-0">
                    <tbody>
                        <tr class="border-bottom">
                            <td><strong>Gross Sales Revenue</strong></td>
                            <td class="text-end fw-bold text-success">${{ number_format($totalRevenue, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="ps-3 text-muted">Less: Cost of Ingredients (COGS)</td>
                            <td class="text-end text-danger">-${{ number_format($estimatedCogs, 2) }}</td>
                        </tr>
                        <tr class="border-bottom bg-light">
                            <td><strong>Gross Operating Profit</strong></td>
                            <td class="text-end fw-bold text-primary">${{ number_format($grossProfit, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="ps-3 text-muted">Less: Spoilage & Inventory Waste Loss</td>
                            <td class="text-end text-danger">-${{ number_format($inventoryStats['waste_cost'], 2) }}</td>
                        </tr>
                        <tr>
                            <td class="ps-3 text-muted">Less: Operating Expenses</td>
                            <td class="text-end text-danger">-${{ number_format($totalExpenses, 2) }}</td>
                        </tr>
                        <tr class="border-top border-3">
                            <td><h4 class="fw-bold mb-0">Net Estimated Margin / Profit</h4></td>
                            <td class="text-end"><h4 class="fw-bold mb-0 {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">${{ number_format($netProfit, 2) }}</h4></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Expense Breakdown</h6></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">Category</th>
                            <th class="pe-3 text-end">Total ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenseBreakdown as $eb)
                        <tr>
                            <td class="ps-3 fw-bold">{{ ucfirst($eb->category) }}</td>
                            <td class="pe-3 text-end">${{ number_format($eb->total, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center py-3 text-muted">No approved expenses for this period.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@elseif($tab === 'cashflow')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Cash Flow Summary</h6></div>
            <div class="card-body">
                <div class="vstack gap-3 fs-6">
                    <div class="d-flex justify-content-between">
                        <span class="text-success"><i class="bi bi-cash-in me-2"></i> Cash In (Sales)</span>
                        <strong class="text-success">${{ number_format($cashIn, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-danger"><i class="bi bi-cash-out me-2"></i> Cash Out (Purchases + Expenses)</span>
                        <strong class="text-danger">${{ number_format($cashOut, 2) }}</strong>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Net Cash Flow</span>
                        <strong class="{{ $netCashFlow >= 0 ? 'text-success' : 'text-danger' }}">${{ number_format($netCashFlow, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@elseif($tab === 'taxes')
<div class="card">
    <div class="card-header bg-white py-3"><h6 class="fw-bold mb-0">Tax & VAT Summary Report</h6></div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-label">Total VAT Collected</div>
                    <div class="stat-value text-danger">${{ number_format($totalVat, 2) }}</div>
                </div>
            </div>
        </div>
        <h6 class="fw-bold mb-3">VAT Per Sale</h6>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Invoice</th>
                        <th>Date</th>
                        <th>Subtotal ($)</th>
                        <th>VAT ($)</th>
                        <th>Total ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salesTrend as $st)
                    @php
                        $orders = Order::where('order_status', 'completed')->whereDate('created_at', $st->date)->get();
                    @endphp
                    @foreach($orders as $o)
                    <tr>
                        <td class="fw-bold">{{ $o->invoice_no }}</td>
                        <td>{{ $o->created_at->format('Y-m-d') }}</td>
                        <td>${{ number_format($o->sub_total, 2) }}</td>
                        <td class="text-danger">${{ number_format($o->vat, 2) }}</td>
                        <td>${{ number_format($o->total, 2) }}</td>
                    </tr>
                    @endforeach
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">No sales data found for this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection
