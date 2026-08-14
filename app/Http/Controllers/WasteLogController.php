<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\InventoryMovement;
use App\Models\WasteLog;
use Illuminate\Http\Request;

class WasteLogController extends Controller
{
    public function index(Request $request)
    {
        $query = WasteLog::with(['ingredient', 'user']);

        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }
        if ($request->filled('ingredient_id')) {
            $query->where('ingredient_id', $request->ingredient_id);
        }

        $wasteLogs = $query->latest()->paginate(15)->withQueryString();
        $ingredients = Ingredient::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total_cost' => WasteLog::sum('total_cost'),
            'total_entries' => WasteLog::count(),
            'month_cost' => WasteLog::whereMonth('logged_at', now()->month)->sum('total_cost'),
        ];

        return view('inventory.waste.index', compact('wasteLogs', 'ingredients', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric|min:0.001',
            'reason' => 'required|in:expired,spoiled,spilled,damaged,other',
            'logged_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $ingredient = Ingredient::findOrFail($request->ingredient_id);
        $totalCost = $request->quantity * $ingredient->cost_per_unit;

        $waste = WasteLog::create([
            'ingredient_id' => $ingredient->id,
            'quantity' => $request->quantity,
            'unit_cost' => $ingredient->cost_per_unit,
            'total_cost' => $totalCost,
            'reason' => $request->reason,
            'logged_at' => $request->logged_at,
            'user_id' => auth()->id(),
            'notes' => $request->notes,
        ]);

        // Deduct from stock
        $ingredient->decrement('current_stock', $request->quantity);

        // Record stock movement
        InventoryMovement::create([
            'ingredient_id' => $ingredient->id,
            'type' => 'waste',
            'quantity' => $request->quantity,
            'cost_per_unit' => $ingredient->cost_per_unit,
            'total_cost' => $totalCost,
            'reference_type' => 'WasteLog',
            'reference_id' => $waste->id,
            'user_id' => auth()->id(),
            'notes' => 'Waste recorded: ' . $request->reason,
        ]);

        return back()->with('success', 'Waste log recorded successfully!');
    }

    public function destroy(WasteLog $wasteLog)
    {
        $wasteLog->delete();
        return back()->with('success', 'Waste log entry deleted!');
    }
}
