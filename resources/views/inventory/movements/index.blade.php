@extends('layouts.app')

@section('title', 'Stock Movements & Audit Log')
@section('page-title', 'Stock Movements')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-clock-history text-primary me-2"></i>Stock Movements Log</h4>
        <p class="text-muted small mb-0">Audit history of stock in, stock out, sale deductions, and manual adjustments</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#manualStockModal">
        <i class="bi bi-plus-lg me-1"></i> Manual Adjustment
    </button>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header bg-white py-3">
        <form method="GET" action="{{ route('inventory.movements.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <select name="ingredient_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- All Ingredients --</option>
                    @foreach($ingredients as $ing)
                    <option value="{{ $ing->id }}" {{ request('ingredient_id') == $ing->id ? 'selected' : '' }}>{{ $ing->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- All Movement Types --</option>
                    <option value="stock_in" {{ request('type') === 'stock_in' ? 'selected' : '' }}>Stock In</option>
                    <option value="stock_out" {{ request('type') === 'stock_out' ? 'selected' : '' }}>Stock Out</option>
                    <option value="sale_deduction" {{ request('type') === 'sale_deduction' ? 'selected' : '' }}>Sale Deduction</option>
                    <option value="waste" {{ request('type') === 'waste' ? 'selected' : '' }}>Waste</option>
                    <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                </select>
            </div>
            <div class="col-md-3 text-end">
                @if(request('ingredient_id') || request('type'))
                <a href="{{ route('inventory.movements.index') }}" class="btn btn-sm btn-outline-secondary">Clear Filters</a>
                @endif
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Date & Time</th>
                        <th>Ingredient</th>
                        <th>Movement Type</th>
                        <th>Quantity</th>
                        <th>Unit Cost ($)</th>
                        <th>Total Cost ($)</th>
                        <th>Reference</th>
                        <th>User</th>
                        <th class="pe-4">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $m)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $m->created_at->format('Y-m-d H:i') }}</td>
                        <td class="fw-bold text-dark">{{ $m->ingredient->name ?? 'N/A' }}</td>
                        <td>
                            @match($m->type)
                                @case('stock_in')
                                    <span class="badge bg-success"><i class="bi bi-arrow-down-left"></i> Stock In</span>
                                    @break
                                @case('sale_deduction')
                                    <span class="badge bg-info text-dark"><i class="bi bi-cart-check"></i> Sale Deduction</span>
                                    @break
                                @case('waste')
                                    <span class="badge bg-danger"><i class="bi bi-trash"></i> Waste</span>
                                    @break
                                @case('transfer')
                                    <span class="badge bg-primary"><i class="bi bi-arrow-left-right"></i> Transfer</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ ucfirst($m->type) }}</span>
                            @endmatch
                        </td>
                        <td class="fw-bold fs-6 {{ in_array($m->type, ['stock_out', 'sale_deduction', 'waste']) ? 'text-danger' : 'text-success' }}">
                            {{ in_array($m->type, ['stock_out', 'sale_deduction', 'waste']) ? '-' : '+' }}{{ number_format($m->quantity, 3) }} {{ $m->ingredient->unit ?? '' }}
                        </td>
                        <td>${{ number_format($m->cost_per_unit, 2) }}</td>
                        <td class="fw-bold">${{ number_format($m->total_cost, 2) }}</td>
                        <td class="small"><span class="badge bg-light text-dark border">{{ $m->reference_type ?? 'Manual' }} #{{ $m->reference_id ?? '' }}</span></td>
                        <td class="small">{{ $m->user->name ?? 'System' }}</td>
                        <td class="pe-4 small text-muted">{{ $m->notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">No stock movements recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($movements->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $movements->links() }}
    </div>
    @endif
</div>

<!-- Manual Adjustment Modal -->
<div class="modal fade" id="manualStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('inventory.movements.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Manual Stock Adjustment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Select Ingredient *</label>
                        <select name="ingredient_id" class="form-select" required>
                            <option value="">-- Select Ingredient --</option>
                            @foreach($ingredients as $ing)
                            <option value="{{ $ing->id }}">{{ $ing->name }} (Current: {{ $ing->current_stock }} {{ $ing->unit }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-medium">Type *</label>
                            <select name="type" class="form-select" required>
                                <option value="stock_in">Stock In (Add)</option>
                                <option value="stock_out">Stock Out (Deduct)</option>
                                <option value="adjustment">Set Exact Level</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-medium">Quantity *</label>
                            <input type="number" step="0.001" name="quantity" class="form-control" placeholder="Qty" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-medium">Batch Number</label>
                            <input type="text" name="batch_number" class="form-control" placeholder="BATCH-2026">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-medium">Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Reason / Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Reason for adjustment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Movement</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
