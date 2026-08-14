@extends('layouts.app')

@section('title', 'Recipe Management & Costing')
@section('page-title', 'Recipe Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-book text-primary me-2"></i>Recipe Management & Costing</h4>
        <p class="text-muted small mb-0">Build recipes for menu items, track ingredient costs, and configure automatic inventory deduction</p>
    </div>
    @if($menuItemsWithoutRecipe->count() > 0)
    <div class="dropdown">
        <button class="btn btn-primary rounded-pill px-4 dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-plus-lg me-1"></i> Create Recipe
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="max-height: 300px; overflow-y: auto;">
            <li class="dropdown-header">Select Menu Item:</li>
            @foreach($menuItemsWithoutRecipe as $mi)
            <li>
                <a class="dropdown-item" href="{{ route('recipes.builder', $mi) }}">
                    {{ $mi->name }} <span class="badge bg-light text-dark ms-2">${{ number_format($mi->price, 2) }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Active Recipes</div>
            <div class="stat-value">{{ $stats['total_recipes'] }} / {{ $stats['total_menu_items'] }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Avg Recipe Cost</div>
            <div class="stat-value text-primary">${{ number_format($stats['avg_recipe_cost'], 2) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label text-success">Auto Stock Deduction</div>
            <div class="stat-value text-success"><i class="bi bi-check-circle-fill me-1"></i> Enabled</div>
        </div>
    </div>
</div>

<!-- Recipes List Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Menu Item</th>
                        <th>Category</th>
                        <th>Selling Price ($)</th>
                        <th>Calculated Cost ($)</th>
                        <th>Profit Margin (%)</th>
                        <th>Ingredients Count</th>
                        <th>Yield</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recipes as $r)
                    @php
                        $sellingPrice = $r->menuItem->price ?? 0;
                        $cost = $r->calculated_cost;
                        $margin = $sellingPrice > 0 ? round((($sellingPrice - $cost) / $sellingPrice) * 100, 1) : 0;
                    @endphp
                    <tr>
                        <td class="ps-4 fw-bold text-dark">{{ $r->menuItem->name ?? 'Deleted Item' }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $r->menuItem->category->name ?? 'Uncategorized' }}</span></td>
                        <td class="fw-bold">${{ number_format($sellingPrice, 2) }}</td>
                        <td class="fw-bold text-danger">${{ number_format($cost, 2) }}</td>
                        <td>
                            <span class="badge {{ $margin >= 50 ? 'bg-success' : ($margin >= 20 ? 'bg-warning text-dark' : 'bg-danger') }} fs-6">
                                {{ $margin }}% Margin
                            </span>
                        </td>
                        <td><span class="badge bg-secondary rounded-pill">{{ $r->ingredients->count() }} ingredients</span></td>
                        <td>{{ $r->yield_quantity }} {{ $r->yield_unit }}</td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('recipes.builder', $r->menuItem) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i> Edit Recipe</a>
                            <form action="{{ route('recipes.destroy', $r) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete recipe?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No recipes created yet. Select a menu item to build a recipe.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($recipes->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $recipes->links() }}
    </div>
    @endif
</div>
@endsection
