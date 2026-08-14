@extends('layouts.app')

@section('title', 'Ingredients & Raw Materials')
@section('page-title', 'Ingredients Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-box-seam text-primary me-2"></i>Ingredients & Raw Materials</h4>
        <p class="text-muted small mb-0">Track raw ingredient stock levels, unit costs, and reorder alerts</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('inventory.movements.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-journal-text me-1"></i> Stock History
        </a>
        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addIngredientModal">
            <i class="bi bi-plus-lg me-1"></i> Add Ingredient
        </button>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-box"></i></div>
                <div>
                    <div class="stat-label">Total Ingredients</div>
                    <div class="stat-value">{{ $stats['total_items'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-exclamation-triangle"></i></div>
                <div>
                    <div class="stat-label text-danger">Low Stock Items</div>
                    <div class="stat-value text-danger">{{ $stats['low_stock'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-currency-dollar"></i></div>
                <div>
                    <div class="stat-label">Total Inventory Valuation</div>
                    <div class="stat-value text-success">${{ number_format($stats['total_value'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header bg-white py-3">
        <form method="GET" action="{{ route('ingredients.index') }}" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search ingredient by name or code..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-check form-switch pt-1">
                    <input class="form-check-input" type="checkbox" name="low_stock" value="1" id="lowStockToggle" {{ request('low_stock') ? 'checked' : '' }} onchange="this.form.submit()">
                    <label class="form-check-label small fw-bold text-danger" for="lowStockToggle">Show Low Stock Only</label>
                </div>
            </div>
            <div class="col-md-3 text-end">
                <button type="submit" class="btn btn-sm btn-secondary me-1">Filter</button>
                @if(request('search') || request('low_stock'))
                <a href="{{ route('ingredients.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                @endif
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Code / SKU</th>
                        <th>Ingredient Name</th>
                        <th>Current Stock</th>
                        <th>Alert Threshold</th>
                        <th>Unit Cost ($)</th>
                        <th>Stock Value ($)</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ingredients as $ing)
                    <tr>
                        <td class="ps-4 text-muted small fw-bold">{{ $ing->code ?? '-' }}</td>
                        <td class="fw-bold text-dark">{{ $ing->name }}</td>
                        <td>
                            <span class="fs-6 fw-bold {{ $ing->isLowStock() ? 'text-danger' : 'text-dark' }}">
                                {{ number_format($ing->current_stock, 3) }} {{ $ing->unit }}
                            </span>
                            @if($ing->isLowStock())
                            <span class="badge bg-danger ms-1">Low Stock</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ number_format($ing->alert_threshold, 3) }} {{ $ing->unit }}</td>
                        <td>${{ number_format($ing->cost_per_unit, 2) }} / {{ $ing->unit }}</td>
                        <td class="fw-bold text-success">${{ number_format($ing->current_stock * $ing->cost_per_unit, 2) }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $ing->location ?? 'Main Store' }}</span></td>
                        <td>
                            @if($ing->is_active)
                            <span class="badge bg-success-subtle text-success border border-success">Active</span>
                            @else
                            <span class="badge bg-secondary-subtle text-secondary border">Inactive</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editIngModal{{ $ing->id }}"><i class="bi bi-pencil"></i> Edit</button>
                            <form action="{{ route('ingredients.destroy', $ing) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this ingredient?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editIngModal{{ $ing->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('ingredients.update', $ing) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Ingredient — {{ $ing->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Ingredient Name *</label>
                                            <input type="text" name="name" class="form-control" value="{{ $ing->name }}" required>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label fw-medium">Code / SKU</label>
                                                <input type="text" name="code" class="form-control" value="{{ $ing->code }}">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label fw-medium">Unit of Measure *</label>
                                                <select name="unit" class="form-select" required>
                                                    <option value="kg" {{ $ing->unit === 'kg' ? 'selected' : '' }}>kg (Kilograms)</option>
                                                    <option value="g" {{ $ing->unit === 'g' ? 'selected' : '' }}>g (Grams)</option>
                                                    <option value="l" {{ $ing->unit === 'l' ? 'selected' : '' }}>l (Liters)</option>
                                                    <option value="ml" {{ $ing->unit === 'ml' ? 'selected' : '' }}>ml (Milliliters)</option>
                                                    <option value="pcs" {{ $ing->unit === 'pcs' ? 'selected' : '' }}>pcs (Pieces)</option>
                                                    <option value="pack" {{ $ing->unit === 'pack' ? 'selected' : '' }}>pack (Packs)</option>
                                                    <option value="box" {{ $ing->unit === 'box' ? 'selected' : '' }}>box (Boxes)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label fw-medium">Alert Threshold *</label>
                                                <input type="number" step="0.001" name="alert_threshold" class="form-control" value="{{ $ing->alert_threshold }}" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label fw-medium">Unit Cost ($) *</label>
                                                <input type="number" step="0.01" name="cost_per_unit" class="form-control" value="{{ $ing->cost_per_unit }}" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Storage Location</label>
                                            <input type="text" name="location" class="form-control" value="{{ $ing->location }}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">No ingredients found. Click 'Add Ingredient' to get started.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($ingredients->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $ingredients->links() }}
    </div>
    @endif
</div>

<!-- Add Modal -->
<div class="modal fade" id="addIngredientModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('ingredients.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Ingredient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Ingredient Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Mozzarella Cheese" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-medium">Code / SKU</label>
                            <input type="text" name="code" class="form-control" placeholder="ING-001">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-medium">Unit of Measure *</label>
                            <select name="unit" class="form-select" required>
                                <option value="kg">kg (Kilograms)</option>
                                <option value="g">g (Grams)</option>
                                <option value="l">l (Liters)</option>
                                <option value="ml">ml (Milliliters)</option>
                                <option value="pcs">pcs (Pieces)</option>
                                <option value="pack">pack (Packs)</option>
                                <option value="box">box (Boxes)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-medium">Initial Stock *</label>
                            <input type="number" step="0.001" name="current_stock" class="form-control" value="0" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-medium">Alert Threshold *</label>
                            <input type="number" step="0.001" name="alert_threshold" class="form-control" value="5" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Cost per Unit ($) *</label>
                        <input type="number" step="0.01" name="cost_per_unit" class="form-control" value="0.00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Storage Location</label>
                        <input type="text" name="location" class="form-control" placeholder="Cold Room / Shelf A">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Ingredient</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
