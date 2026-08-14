@extends('layouts.app')

@section('title', 'Physical Stock Audit & Count')
@section('page-title', 'Stock Audit Count')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-clipboard-check text-primary me-2"></i>Physical Stock Count & Reconciliation</h4>
        <p class="text-muted small mb-0">Perform stock audits, measure variance against system inventory, and auto-adjust levels</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#newStockCountModal">
        <i class="bi bi-plus-lg me-1"></i> Start New Audit
    </button>
</div>

<!-- Stock Audits List Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Audit Title</th>
                        <th>Date</th>
                        <th>Audited By</th>
                        <th>Items Counted</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockCounts as $sc)
                    <tr>
                        <td class="ps-4 fw-bold text-dark"><a href="{{ route('inventory.stock-count.show', $sc) }}">{{ $sc->title }}</a></td>
                        <td>{{ $sc->count_date }}</td>
                        <td>{{ $sc->user->name ?? 'System' }}</td>
                        <td><span class="badge bg-secondary rounded-pill">{{ $sc->items->count() }} ingredients</span></td>
                        <td><span class="badge bg-success-subtle text-success border border-success">Completed & Reconciled</span></td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('inventory.stock-count.show', $sc) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View Variance Details</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No stock audits completed yet. Click 'Start New Audit' to perform physical stock reconciliation.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($stockCounts->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $stockCounts->links() }}
    </div>
    @endif
</div>

<!-- New Stock Audit Modal -->
<div class="modal fade" id="newStockCountModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('inventory.stock-count.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">New Physical Stock Audit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-medium">Audit Title *</label>
                            <input type="text" name="title" class="form-control" value="Weekly Stock Audit — {{ date('M d, Y') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Audit Date *</label>
                            <input type="date" name="count_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <h6 class="fw-bold mt-4 mb-2">Count Ingredients & Physical Quantities</h6>
                    <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Ingredient</th>
                                    <th>Unit</th>
                                    <th>System Stock</th>
                                    <th>Physical Counted Stock *</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ingredients as $idx => $ing)
                                <tr>
                                    <td class="fw-bold">
                                        {{ $ing->name }}
                                        <input type="hidden" name="items[{{ $idx }}][ingredient_id]" value="{{ $ing->id }}">
                                    </td>
                                    <td>{{ $ing->unit }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ number_format($ing->current_stock, 3) }}</span></td>
                                    <td>
                                        <input type="number" step="0.001" name="items[{{ $idx }}][counted_stock]" class="form-control form-control-sm" value="{{ $ing->current_stock }}" required>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <label class="form-label fw-medium">Notes / Audit Remarks</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional audit notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Complete Audit & Reconcile Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
