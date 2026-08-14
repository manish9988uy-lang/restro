@extends('layouts.app')

@section('title', 'Recipe Builder — ' . $menuItem->name)
@section('page-title', 'Recipe Builder')

@section('content')
<div class="mb-3">
    <a href="{{ route('recipes.index') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i> Back to Recipes</a>
</div>

<form method="POST" action="{{ route('recipes.builder.save', $menuItem) }}">
    @csrf
    <div class="row g-4">
        <!-- Left: Recipe Builder Form -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-tools me-2"></i>Recipe Builder — {{ $menuItem->name }}</h5>
                        <small class="text-muted">Menu Item Selling Price: <strong>${{ number_format($menuItem->price, 2) }}</strong></small>
                    </div>
                    <span class="badge bg-light text-dark border fs-6">{{ $menuItem->category->name ?? 'Category' }}</span>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Yield Quantity *</label>
                            <input type="number" step="0.01" name="yield_quantity" class="form-control" value="{{ old('yield_quantity', $recipe->yield_quantity ?? 1) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Yield Unit *</label>
                            <input type="text" name="yield_unit" class="form-control" value="{{ old('yield_unit', $recipe->yield_unit ?? 'portion') }}" required>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-2">Recipe Ingredients</h6>
                    <p class="text-muted small">Specify ingredient quantities required for 1 portion of this dish</p>

                    <div id="recipeIngredientsContainer">
                        @php
                            $existingIngredients = old('ingredients', $recipe->ingredients ?? []);
                        @endphp

                        @forelse($existingIngredients as $idx => $ri)
                        <div class="row g-2 mb-2 recipe-ing-row">
                            <div class="col-md-5">
                                <select name="ingredients[{{ $idx }}][ingredient_id]" class="form-select recipe-ing-select" required>
                                    <option value="">-- Select Ingredient --</option>
                                    @foreach($ingredients as $ing)
                                    <option value="{{ $ing->id }}" data-cost="{{ $ing->cost_per_unit }}" data-unit="{{ $ing->unit }}" {{ (is_array($ri) ? $ri['ingredient_id'] : $ri->ingredient_id) == $ing->id ? 'selected' : '' }}>
                                        {{ $ing->name }} (${{ number_format($ing->cost_per_unit, 2) }}/{{ $ing->unit }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.0001" name="ingredients[{{ $idx }}][quantity]" class="form-control ing-qty-input" value="{{ is_array($ri) ? $ri['quantity'] : $ri->quantity }}" placeholder="Qty" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="ingredients[{{ $idx }}][unit]" class="form-control ing-unit-input" value="{{ is_array($ri) ? $ri['unit'] : $ri->unit }}" placeholder="Unit" required>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-danger w-100 remove-ing-btn"><i class="bi bi-x"></i></button>
                            </div>
                        </div>
                        @empty
                        <div class="row g-2 mb-2 recipe-ing-row">
                            <div class="col-md-5">
                                <select name="ingredients[0][ingredient_id]" class="form-select recipe-ing-select" required>
                                    <option value="">-- Select Ingredient --</option>
                                    @foreach($ingredients as $ing)
                                    <option value="{{ $ing->id }}" data-cost="{{ $ing->cost_per_unit }}" data-unit="{{ $ing->unit }}">
                                        {{ $ing->name }} (${{ number_format($ing->cost_per_unit, 2) }}/{{ $ing->unit }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.0001" name="ingredients[0][quantity]" class="form-control ing-qty-input" placeholder="Qty" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="ingredients[0][unit]" class="form-control ing-unit-input" placeholder="Unit (g/kg/l)" required>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-danger w-100 remove-ing-btn"><i class="bi bi-x"></i></button>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <button type="button" id="addRecipeIngBtn" class="btn btn-sm btn-outline-secondary mt-2">
                        <i class="bi bi-plus-circle me-1"></i> Add Ingredient Line
                    </button>

                    <div class="mt-4">
                        <label class="form-label fw-medium">Preparation Instructions / Chef Notes</label>
                        <textarea name="instructions" class="form-control" rows="3" placeholder="Step 1... Step 2...">{{ old('instructions', $recipe->instructions ?? '') }}</textarea>
                    </div>
                </div>
                <div class="card-footer bg-white text-end py-3">
                    <a href="{{ route('recipes.index') }}" class="btn btn-light me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Save Recipe & Calculate Cost</button>
                </div>
            </div>
        </div>

        <!-- Right: Live Cost Analysis Sidebar -->
        <div class="col-md-4">
            <div class="card sticky-top" style="top: 80px;">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-pie-chart text-success me-2"></i>Cost & Margin Analysis</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Selling Price:</span>
                        <strong class="fs-6">${{ number_format($menuItem->price, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Recipe Cost:</span>
                        <strong class="fs-6 text-danger" id="liveRecipeCost">$0.00</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Estimated Profit:</span>
                        <strong class="fs-5 text-success" id="liveProfit">$0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Profit Margin:</span>
                        <span class="badge bg-success fs-6" id="liveMargin">0%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    let recipeIdx = {{ max(1, count($recipe->ingredients ?? [])) }};

    document.getElementById('addRecipeIngBtn')?.addEventListener('click', function () {
        const container = document.getElementById('recipeIngredientsContainer');
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 recipe-ing-row';
        row.innerHTML = `
            <div class="col-md-5">
                <select name="ingredients[${recipeIdx}][ingredient_id]" class="form-select recipe-ing-select" required>
                    <option value="">-- Select Ingredient --</option>
                    @foreach($ingredients as $ing)
                    <option value="{{ $ing->id }}" data-cost="{{ $ing->cost_per_unit }}" data-unit="{{ $ing->unit }}">
                        {{ $ing->name }} (${{ number_format($ing->cost_per_unit, 2) }}/{{ $ing->unit }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" step="0.0001" name="ingredients[${recipeIdx}][quantity]" class="form-control ing-qty-input" placeholder="Qty" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="ingredients[${recipeIdx}][unit]" class="form-control ing-unit-input" placeholder="Unit" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger w-100 remove-ing-btn"><i class="bi bi-x"></i></button>
            </div>
        `;
        container.appendChild(row);
        recipeIdx++;
        updateCostAnalysis();
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-ing-btn')) {
            const rows = document.querySelectorAll('.recipe-ing-row');
            if (rows.length > 1) {
                e.target.closest('.recipe-ing-row').remove();
                updateCostAnalysis();
            }
        }
    });

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('recipe-ing-select')) {
            const opt = e.target.options[e.target.selectedIndex];
            const unit = opt.getAttribute('data-unit');
            const unitInput = e.target.closest('.recipe-ing-row').querySelector('.ing-unit-input');
            if (unit && unitInput) {
                unitInput.value = unit;
            }
            updateCostAnalysis();
        }
    });

    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('ing-qty-input')) {
            updateCostAnalysis();
        }
    });

    function updateCostAnalysis() {
        let totalCost = 0;
        const rows = document.querySelectorAll('.recipe-ing-row');
        rows.forEach(row => {
            const select = row.querySelector('.recipe-ing-select');
            const qtyInput = row.querySelector('.ing-qty-input');
            if (select && qtyInput && select.selectedIndex > 0) {
                const cost = parseFloat(select.options[select.selectedIndex].getAttribute('data-cost') || 0);
                const qty = parseFloat(qtyInput.value || 0);
                totalCost += cost * qty;
            }
        });

        const sellingPrice = {{ $menuItem->price }};
        const profit = sellingPrice - totalCost;
        const margin = sellingPrice > 0 ? ((profit / sellingPrice) * 100).toFixed(1) : 0;

        document.getElementById('liveRecipeCost').innerText = '$' + totalCost.toFixed(2);
        document.getElementById('liveProfit').innerText = '$' + profit.toFixed(2);
        document.getElementById('liveMargin').innerText = margin + '% Margin';
    }

    updateCostAnalysis();
</script>
@endpush
@endsection
