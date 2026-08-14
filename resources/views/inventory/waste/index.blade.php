@extends('layouts.app')

@section('title', 'Waste Tracking & Loss Report')
@section('page-title', 'Waste Tracking')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-trash text-danger me-2"></i>Waste & Spoilage Tracking</h4>
        <p class="text-muted small mb-0">Record and monitor inventory waste, expired items, and financial loss</p>
    </div>
    <button class="btn btn-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#recordWasteModal">
        <i class="bi bi-plus-lg me-1"></i> Record Waste
    </button>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label text-danger">Total Loss (All Time)</div>
            <div class="stat-value text-danger">${{ number_format($stats['total_cost'], 2) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label text-warning">This Month's Waste Loss</div>
            <div class="stat-value text-warning">${{ number_format($stats['month_cost'], 2) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Total Waste Incidents</div>
            <div class="stat-value">{{ $stats['total_entries'] }}</div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header bg-white py-3">
        <form method="GET" action="{{ route('inventory.waste.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <select name="reason" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- All Waste Reasons --</option>
                    <option value="spoiled" {{ request('reason') === 'spoiled' ? 'selected' : '' }}>Spoiled / Rotten</option>
                    <option value="expired" {{ request('reason') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="spilled" {{ request('reason') === 'spilled' ? 'selected' : '' }}>Spilled / Dropped</option>
                    <option value="damaged" {{ request('reason') === 'damaged' ? 'selected' : '' }}>Damaged</option>
                    <option value="other" {{ request('reason') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="col-md-3 text-end ms-auto">
                @if(request('reason'))
                <a href="{{ route('inventory.waste.index') }}" class="btn btn-sm btn-outline-secondary">Clear Filter</a>
                @endif
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Date Logged</th>
                        <th>Ingredient</th>
                        <th>Wasted Qty</th>
                        <th>Unit Cost ($)</th>
                        <th>Total Loss ($)</th>
                        <th>Reason</th>
                        <th>Logged By</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wasteLogs as $w)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $w->logged_at }}</td>
                        <td class="fw-bold text-dark">{{ $w->ingredient->name ?? 'Deleted Ingredient' }}</td>
                        <td class="fw-bold text-danger">{{ number_format($w->quantity, 3) }} {{ $w->ingredient->unit ?? '' }}</td>
                        <td>${{ number_format($w->unit_cost, 2) }}</td>
                        <td class="fw-bold text-danger">${{ number_format($w->total_cost, 2) }}</td>
                        <td><span class="badge bg-danger-subtle text-danger border border-danger">{{ ucfirst($w->reason) }}</span></td>
                        <td class="small">{{ $w->user->name ?? 'System' }}</td>
                        <td class="pe-4 text-end">
                            <form action="{{ route('inventory.waste.destroy', $w) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this waste entry?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No waste entries recorded. Click 'Record Waste' to log spoilage.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($wasteLogs->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $wasteLogs->links() }}
    </div>
    @endif
</div>

<!-- Record Waste Modal -->
<div class="modal fade" id="recordWasteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('inventory.waste.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Record Waste / Spoilage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Ingredient *</label>
                        <select name="ingredient_id" class="form-select" required>
                            <option value="">-- Select Wasted Ingredient --</option>
                            @foreach($ingredients as $ing)
                            <option value="{{ $ing->id }}">{{ $ing->name }} (Available: {{ $ing->current_stock }} {{ $ing->unit }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-medium">Wasted Quantity *</label>
                            <input type="number" step="0.001" name="quantity" class="form-control" placeholder="Qty" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-medium">Date *</label>
                            <input type="date" name="logged_at" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Reason for Waste *</label>
                        <select name="reason" class="form-select" required>
                            <option value="spoiled">Spoiled / Rotten</option>
                            <option value="expired">Expired</option>
                            <option value="spilled">Spilled / Dropped</option>
                            <option value="damaged">Damaged Packaging</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Log Waste & Deduct Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
