<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\InventoryMovement;
use App\Models\StockTransfer;
use Illuminate\Http\Request;

class InventoryAdjustmentController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryMovement::with(['ingredient', 'user']);

        if ($request->filled('ingredient_id')) {
            $query->where('ingredient_id', $request->ingredient_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $movements = $query->latest()->paginate(20)->withQueryString();
        $ingredients = Ingredient::where('is_active', true)->orderBy('name')->get();

        return view('inventory.movements.index', compact('movements', 'ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'type' => 'required|in:stock_in,stock_out,adjustment',
            'quantity' => 'required|numeric|min:0.001',
            'batch_number' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $ingredient = Ingredient::findOrFail($request->ingredient_id);
        $qty = (float) $request->quantity;

        if ($request->type === 'stock_in') {
            $ingredient->increment('current_stock', $qty);
        } elseif ($request->type === 'stock_out') {
            $ingredient->decrement('current_stock', $qty);
        } else {
            // Manual adjustment: override current_stock or add delta
            $ingredient->update(['current_stock' => $qty]);
        }

        InventoryMovement::create([
            'ingredient_id' => $ingredient->id,
            'type' => $request->type,
            'quantity' => $qty,
            'cost_per_unit' => $ingredient->cost_per_unit,
            'total_cost' => $qty * $ingredient->cost_per_unit,
            'batch_number' => $request->batch_number,
            'expiry_date' => $request->expiry_date,
            'reference_type' => 'ManualAdjustment',
            'user_id' => auth()->id(),
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Stock movement recorded successfully!');
    }

    public function transfers(Request $request)
    {
        $transfers = StockTransfer::with(['ingredient', 'user'])->latest()->paginate(15);
        $ingredients = Ingredient::where('is_active', true)->orderBy('name')->get();

        return view('inventory.transfers.index', compact('transfers', 'ingredients'));
    }

    public function storeTransfer(Request $request)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'from_location' => 'required|string|max:100',
            'to_location' => 'required|string|max:100',
            'quantity' => 'required|numeric|min:0.001',
            'transfer_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $transfer = StockTransfer::create([
            'ingredient_id' => $request->ingredient_id,
            'from_location' => $request->from_location,
            'to_location' => $request->to_location,
            'quantity' => $request->quantity,
            'transfer_date' => $request->transfer_date,
            'user_id' => auth()->id(),
            'notes' => $request->notes,
        ]);

        $ingredient = Ingredient::find($request->ingredient_id);

        InventoryMovement::create([
            'ingredient_id' => $request->ingredient_id,
            'type' => 'transfer',
            'quantity' => $request->quantity,
            'cost_per_unit' => $ingredient->cost_per_unit,
            'total_cost' => $request->quantity * $ingredient->cost_per_unit,
            'reference_type' => 'StockTransfer',
            'reference_id' => $transfer->id,
            'user_id' => auth()->id(),
            'notes' => "Transfer from {$request->from_location} to {$request->to_location}",
        ]);

        return back()->with('success', 'Stock transfer recorded successfully!');
    }
}
