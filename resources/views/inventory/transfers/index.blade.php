@extends('layouts.app')

@section('title', 'Stock Transfers')
@section('page-title', 'Stock Transfers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-arrow-left-right text-primary me-2"></i>Stock Transfers</h4>
        <p class="text-muted small mb-0">Track internal inventory movement between main storage, kitchen, and bar stations</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#newTransferModal">
        <i class="bi bi-plus-lg me-1"></i> Record Transfer
    </button>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Transfer Date</th>
                        <th>Ingredient</th>
                        <th>From Location</th>
                        <th>To Location</th>
                        <th>Transferred Quantity</th>
                        <th>Transferred By</th>
                        <th class="pe-4">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $t)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $t->transfer_date }}</td>
                        <td class="fw-bold text-dark">{{ $t->ingredient->name ?? 'N/A' }}</td>
                        <td><span class="badge bg-secondary-subtle text-secondary border">{{ $t->from_location }}</span></td>
                        <td><span class="badge bg-primary-subtle text-primary border border-primary">{{ $t->to_location }}</span></td>
                        <td class="fw-bold text-dark">{{ number_format($t->quantity, 3) }} {{ $t->ingredient->unit ?? '' }}</td>
                        <td class="small">{{ $t->user->name ?? 'System' }}</td>
                        <td class="pe-4 small text-muted">{{ $t->notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No stock transfers recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($transfers->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $transfers->links() }}
    </div>
    @endif
</div>

<!-- New Transfer Modal -->
<div class="modal fade" id="newTransferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('inventory.transfers.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Record Stock Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Select Ingredient *</label>
                        <select name="ingredient_id" class="form-select" required>
                            <option value="">-- Select Ingredient --</option>
                            @foreach($ingredients as $ing)
                            <option value="{{ $ing->id }}">{{ $ing->name }} (Total Stock: {{ $ing->current_stock }} {{ $ing->unit }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-medium">From Location *</label>
                            <select name="from_location" class="form-select" required>
                                <option value="Main Storage">Main Storage</option>
                                <option value="Cold Room">Cold Room</option>
                                <option value="Kitchen Station">Kitchen Station</option>
                                <option value="Bar Station">Bar Station</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-medium">To Location *</label>
                            <select name="to_location" class="form-select" required>
                                <option value="Kitchen Station">Kitchen Station</option>
                                <option value="Bar Station">Bar Station</option>
                                <option value="Main Storage">Main Storage</option>
                                <option value="Display Shelf">Display Shelf</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-medium">Quantity *</label>
                            <input type="number" step="0.001" name="quantity" class="form-control" placeholder="Qty" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-medium">Transfer Date *</label>
                            <input type="date" name="transfer_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional transfer notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
