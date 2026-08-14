<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::with(['menuItem.category', 'ingredients.ingredient'])->latest()->paginate(15);
        $menuItemsWithoutRecipe = MenuItem::whereDoesntHave('recipe')->orderBy('name')->get();
        $ingredients = Ingredient::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total_recipes' => Recipe::count(),
            'total_menu_items' => MenuItem::count(),
            'avg_recipe_cost' => Recipe::avg('calculated_cost') ?? 0,
        ];

        return view('recipes.index', compact('recipes', 'menuItemsWithoutRecipe', 'ingredients', 'stats'));
    }

    public function builder(MenuItem $menuItem)
    {
        $recipe = Recipe::firstOrNew(['menu_item_id' => $menuItem->id]);
        $recipe->load('ingredients.ingredient');
        $ingredients = Ingredient::where('is_active', true)->orderBy('name')->get();

        return view('recipes.builder', compact('menuItem', 'recipe', 'ingredients'));
    }

    public function saveBuilder(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'yield_quantity' => 'required|numeric|min:0.01',
            'yield_unit' => 'required|string|max:50',
            'instructions' => 'nullable|string',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.0001',
            'ingredients.*.unit' => 'required|string|max:20',
        ]);

        $recipe = Recipe::updateOrCreate(
            ['menu_item_id' => $menuItem->id],
            [
                'yield_quantity' => $request->yield_quantity,
                'yield_unit' => $request->yield_unit,
                'instructions' => $request->instructions,
                'is_active' => true,
            ]
        );

        // Clear existing recipe ingredients
        $recipe->ingredients()->delete();

        $calculatedCost = 0;
        foreach ($request->ingredients as $ingData) {
            $ingredient = Ingredient::find($ingData['ingredient_id']);
            $cost = $ingredient ? ($ingredient->cost_per_unit * $ingData['quantity']) : 0;
            $calculatedCost += $cost;

            RecipeIngredient::create([
                'recipe_id' => $recipe->id,
                'ingredient_id' => $ingData['ingredient_id'],
                'quantity' => $ingData['quantity'],
                'unit' => $ingData['unit'],
                'cost' => $cost,
            ]);
        }

        $recipe->update(['calculated_cost' => $calculatedCost]);

        return redirect()->route('recipes.index')->with('success', 'Recipe for "' . $menuItem->name . '" saved successfully!');
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return back()->with('success', 'Recipe deleted successfully!');
    }
}
