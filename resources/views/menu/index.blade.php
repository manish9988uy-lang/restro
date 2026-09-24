@extends('layouts.app')
@section('title', 'Menu Catalog')
@section('page-title', 'Menu Items Catalog')

@section('content')
<!-- Header Stats & Quick Actions -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#EFF6FF;"><i class="bi bi-journal-text" style="color:#3b82f6;"></i></div>
                <div>
                    <div class="stat-label">Total Items</div>
                    <div class="stat-value">{{ $menuItems->total() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#F0FFF4;"><i class="bi bi-check-circle" style="color:#22c55e;"></i></div>
                <div>
                    <div class="stat-label">Active on POS</div>
                    <div class="stat-value text-success">{{ \App\Models\MenuItem::where('is_available', true)->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#FFFBEB;"><i class="bi bi-star-fill" style="color:#f59e0b;"></i></div>
                <div>
                    <div class="stat-label">Featured Specials</div>
                    <div class="stat-value text-warning">{{ \App\Models\MenuItem::where('is_featured', true)->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#FFF0E8;"><i class="bi bi-tags-fill" style="color:#FF6B35;"></i></div>
                <div>
                    <div class="stat-label">Categories</div>
                    <div class="stat-value">{{ $categories->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('menu.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1 fw-bold">Search Menu Item</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search dish name, description..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1 fw-bold">Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1 fw-bold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="1" @selected(request('status') === '1')>Available</option>
                    <option value="0" @selected(request('status') === '0')>Unavailable</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-funnel-fill me-1"></i> Filter
                </button>
                <a href="{{ route('menu.create') }}" class="btn btn-success" title="Add New Item">
                    <i class="bi bi-plus-lg me-1"></i> New Item
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Items Table -->
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-journal-richtext text-primary fs-5"></i>
            <span class="fw-bold">Menu Dish List</span>
            <span class="badge bg-light text-dark border">{{ $menuItems->total() }} total items</span>
        </div>
        <a href="{{ route('menu.create') }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Add Dish Item
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Dish</th>
                    <th>Category</th>
                    <th>Selling Price</th>
                    <th>Cost Price</th>
                    <th>Margin</th>
                    <th>Prep Time</th>
                    <th>Inventory</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menuItems as $item)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            @if($item->image)
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="rounded-3 shadow-sm" style="width:48px;height:48px;object-fit:cover;">
                            @else
                                <div class="rounded-3 shadow-sm bg-light d-flex align-items-center justify-content-center text-muted" style="width:48px;height:48px;font-size:1.5rem;">
                                    🍽️
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold text-dark small">{{ $item->name }}</div>
                                @if($item->description)
                                    <div class="text-muted small" style="font-size:0.75rem;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                        {{ $item->description }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge border" style="background:{{ $item->category->color ?? '#FF6B35' }}15;color:{{ $item->category->color ?? '#FF6B35' }};border-color:{{ $item->category->color ?? '#FF6B35' }}30;">
                            <i class="{{ $item->category->icon ?? 'bi-tag' }} me-1"></i>{{ $item->category->name ?? 'General' }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-bold text-success">Rs. {{ number_format($item->price, 2) }}</div>
                    </td>
                    <td>
                        <div class="text-muted small">Rs. {{ number_format($item->cost_price, 2) }}</div>
                    </td>
                    <td>
                        @php
                            $profit = $item->price - $item->cost_price;
                            $margin = $item->price > 0 ? round(($profit / $item->price) * 100) : 0;
                        @endphp
                        <span class="badge bg-light text-dark border small">{{ $margin }}%</span>
                    </td>
                    <td>
                        <span class="text-muted small">
                            <i class="bi bi-clock me-1"></i>{{ $item->preparation_time ?? 15 }}m
                        </span>
                    </td>
                    <td>
                        @if($item->stock_quantity == -1)
                            <span class="badge bg-light text-muted border">Unlimited (∞)</span>
                        @elseif($item->stock_quantity == 0)
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Out of Stock</span>
                        @else
                            <span class="badge bg-success-subtle text-success border border-success-subtle">{{ $item->stock_quantity }} units</span>
                        @endif
                    </td>
                    <td>
                        @if($item->is_featured)
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                <i class="bi bi-star-fill me-1"></i>Featured
                            </span>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td>
                        @if($item->is_available)
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Disabled</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="btn-group">
                            <a href="{{ route('menu.edit', $item) }}" class="btn btn-sm btn-outline-primary" title="Edit Item">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('menu.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Delete this menu dish?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Item">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-5 text-muted">
                        <i class="bi bi-egg-fried fs-1 d-block mb-2 opacity-50"></i>
                        No dishes found matching your search.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($menuItems->hasPages())
    <div class="card-footer bg-white border-top p-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">Showing {{ $menuItems->firstItem() }} to {{ $menuItems->lastItem() }} of {{ $menuItems->total() }} dishes</span>
            {{ $menuItems->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
