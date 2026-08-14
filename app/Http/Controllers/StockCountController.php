<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\InventoryMovement;
use App\Models\StockCount;
use App\Models\StockCountItem;
use Illuminate\Http\Request;

class StockCountController extends Controller
{
    public function index()
    {
        $stockCounts = StockCount::with(['user', 'items'])->latest()->paginate(15);
        $ingredients = Ingredient::where('is_active', true)->orderBy('name')->get();
        return view('inventory.stock_count.index', compact('stockCounts', 'ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'count_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.ingredient_id' => 'required|exists:ingredients,id',
            'items.*.counted_stock' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $stockCount = StockCount::create([
            'title' => $request->title,
            'count_date' => $request->count_date,
            'status' => 'completed',
            'user_id' => auth()->id(),
            'notes' => $request->notes,
        ]);

        foreach ($request->items as $itemData) {
            $ingredient = Ingredient::find($itemData['ingredient_id']);
            if (!$ingredient) continue;

            $systemStock = $ingredient->current_stock;
            $countedStock = (float) $itemData['counted_stock'];
            $variance = $countedStock - $systemStock;
            $varianceCost = $variance * $ingredient->cost_per_unit;

            StockCountItem::create([
                'stock_count_id' => $stockCount->id,
                'ingredient_id' => $ingredient->id,
                'system_stock' => $systemStock,
                'counted_stock' => $countedStock,
                'variance' => $variance,
                'unit_cost' => $ingredient->cost_per_unit,
                'variance_cost' => $varianceCost,
            ]);

            // Adjust inventory to counted stock
            $ingredient->update(['current_stock' => $countedStock]);

            InventoryMovement::create([
                'ingredient_id' => $ingredient->id,
                'type' => 'adjustment',
                'quantity' => abs($variance),
                'cost_per_unit' => $ingredient->cost_per_unit,
                'total_cost' => abs($varianceCost),
                'reference_type' => 'StockCount',
                'reference_id' => $stockCount->id,
                'user_id' => auth()->id(),
                'notes' => "Stock Audit Reconciliation: System={$systemStock}, Counted={$countedStock}",
            ]);
        }

        return back()->with('success', 'Physical stock audit completed and stock levels updated!');
    }

    public function show(StockCount $stockCount)
    {
        $stockCount->load(['user', 'items.ingredient']);
        return view('inventory.stock_count.show', compact('stockCount'));
    }
}
