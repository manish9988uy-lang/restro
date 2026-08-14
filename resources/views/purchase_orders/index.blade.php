@extends('layouts.app')

@section('title', 'Purchase Orders — Inventory')
@section('page-title', 'Purchase Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-cart-check text-primary me-2"></i>Purchase Orders</h4>
        <p class="text-muted small mb-0">Create and manage ingredient orders from suppliers</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#createPOModal">
        <i class="bi bi-plus-lg me-1"></i> New Purchase Order
    </button>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label text-warning">Pending / Ordered</div>
            <div class="stat-value text-warning">{{ $stats['pending'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label text-success">Received</div>
            <div class="stat-value text-success">{{ $stats['received'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label text-primary">Total Purchases</div>
            <div class="stat-value">${{ number_format($stats['total_spent'], 2) }}</div>
        </div>
    </div>
</div>

<!-- Purchase Orders List Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">PO Number</th>
                        <th>Supplier</th>
                        <th>Order Date</th>
                        <th>Expected Date</th>
                        <th>Total Amount</th>
                        <th>PO Status</th>
                        <th>Payment</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                    <tr>
                        <td class="ps-4 fw-bold"><a href="{{ route('purchase-orders.show', $po) }}">{{ $po->po_number }}</a></td>
                        <td>{{ $po->supplier->name ?? 'N/A' }}</td>
                        <td>{{ $po->order_date }}</td>
                        <td>{{ $po->expected_date ?? '-' }}</td>
                        <td class="fw-bold">${{ number_format($po->total_amount, 2) }}</td>
                        <td>
                            @if($po->status === 'received')
                            <span class="badge bg-success-subtle text-success border border-success">Received</span>
                            @elseif($po->status === 'ordered')
                            <span class="badge bg-warning-subtle text-warning border border-warning">Ordered</span>
                            @elseif($po->status === 'cancelled')
                            <span class="badge bg-danger-subtle text-danger border">Cancelled</span>
                            @else
                            <span class="badge bg-secondary-subtle text-secondary border">Draft</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $po->payment_status === 'paid' ? 'bg-success' : ($po->payment_status === 'partial' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ ucfirst($po->payment_status) }}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('purchase-orders.show', $po) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No purchase orders found. Click 'New Purchase Order' to issue one.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($purchaseOrders->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $purchaseOrders->links() }}
    </div>
    @endif
</div>

<!-- Create Purchase Order Modal -->
<div class="modal fade" id="createPOModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('purchase-orders.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Create New Purchase Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Supplier *</label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">-- Select Supplier --</option>
                                @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }} ({{ $sup->company_name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-medium">Order Date *</label>
                            <input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-medium">Expected Date</label>
                            <input type="date" name="expected_date" class="form-control">
                        </div>
                    </div>

                    <h6 class="fw-bold mt-4 mb-2">Order Items / Ingredients</h6>
                    <div id="poItemsContainer">
                        <div class="row g-2 mb-2 po-item-row">
                            <div class="col-md-5">
                                <select name="items[0][ingredient_id]" class="form-select ingredient-select" required>
                                    <option value="">-- Select Ingredient --</option>
                                    @foreach($ingredients as $ing)
                                    <option value="{{ $ing->id }}" data-cost="{{ $ing->cost_per_unit }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.001" name="items[0][quantity]" class="form-control qty-input" placeholder="Qty" required>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" name="items[0][unit_price]" class="form-control price-input" placeholder="Unit Cost ($)" required>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-danger w-100 remove-row-btn"><i class="bi bi-x"></i></button>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="addPOItemRowBtn" class="btn btn-sm btn-outline-secondary mt-2">
                        <i class="bi bi-plus-circle me-1"></i> Add Another Ingredient
                    </button>

                    <div class="mt-3">
                        <label class="form-label fw-medium">Notes / Instructions</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Submit Purchase Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let poIndex = 1;
    document.getElementById('addPOItemRowBtn')?.addEventListener('click', function () {
        const container = document.getElementById('poItemsContainer');
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 po-item-row';
        row.innerHTML = `
            <div class="col-md-5">
                <select name="items[${poIndex}][ingredient_id]" class="form-select ingredient-select" required>
                    <option value="">-- Select Ingredient --</option>
                    @foreach($ingredients as $ing)
                    <option value="{{ $ing->id }}" data-cost="{{ $ing->cost_per_unit }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" step="0.001" name="items[${poIndex}][quantity]" class="form-control qty-input" placeholder="Qty" required>
            </div>
            <div class="col-md-3">
                <input type="number" step="0.01" name="items[${poIndex}][unit_price]" class="form-control price-input" placeholder="Unit Cost ($)" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger w-100 remove-row-btn"><i class="bi bi-x"></i></button>
            </div>
        `;
        container.appendChild(row);
        poIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-row-btn')) {
            const rows = document.querySelectorAll('.po-item-row');
            if (rows.length > 1) {
                e.target.closest('.po-item-row').remove();
            }
        }
    });

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('ingredient-select')) {
            const opt = e.target.options[e.target.selectedIndex];
            const cost = opt.getAttribute('data-cost');
            const priceInput = e.target.closest('.po-item-row').querySelector('.price-input');
            if (cost && priceInput) {
                priceInput.value = cost;
            }
        }
    });
</script>
@endpush
@endsection
