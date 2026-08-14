<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index(Request $request)
    {
        $query = Ingredient::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('low_stock')) {
            $query->whereRaw('current_stock <= alert_threshold');
        }

        $ingredients = $query->orderBy('name')->paginate(15)->withQueryString();

        $stats = [
            'total_items' => Ingredient::count(),
            'low_stock' => Ingredient::whereRaw('current_stock <= alert_threshold')->count(),
            'total_value' => Ingredient::selectRaw('SUM(current_stock * cost_per_unit) as val')->value('val') ?? 0,
        ];

        return view('inventory.ingredients.index', compact('ingredients', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:ingredients,code',
            'unit' => 'required|string|max:20',
            'current_stock' => 'required|numeric|min:0',
            'alert_threshold' => 'required|numeric|min:0',
            'cost_per_unit' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $ingredient = Ingredient::create($request->all());

        // Log initial stock movement if > 0
        if ($ingredient->current_stock > 0) {
            InventoryMovement::create([
                'ingredient_id' => $ingredient->id,
                'type' => 'stock_in',
                'quantity' => $ingredient->current_stock,
                'cost_per_unit' => $ingredient->cost_per_unit,
                'total_cost' => $ingredient->current_stock * $ingredient->cost_per_unit,
                'reference_type' => 'InitialStock',
                'user_id' => auth()->id(),
                'notes' => 'Initial stock on creation',
            ]);
        }

        return back()->with('success', 'Ingredient created successfully!');
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:ingredients,code,' . $ingredient->id,
            'unit' => 'required|string|max:20',
            'alert_threshold' => 'required|numeric|min:0',
            'cost_per_unit' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $ingredient->update($request->all());

        return back()->with('success', 'Ingredient updated successfully!');
    }

    public function destroy(Ingredient $ingredient)
    {
        if ($ingredient->recipeIngredients()->count() > 0) {
            return back()->with('error', 'Cannot delete ingredient that is linked to active recipes!');
        }

        $ingredient->delete();
        return back()->with('success', 'Ingredient deleted successfully!');
    }
}
