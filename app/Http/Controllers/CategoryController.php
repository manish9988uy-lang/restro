<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('menuItems')
            ->orderBy('sort_order')
            ->paginate(15);
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'icon'       => 'nullable|string|max:50',
            'color'      => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
        ]);

        $data['icon']      = $data['icon'] ?? 'bi-grid';
        $data['color']     = $data['color'] ?? '#FF6B35';
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        Category::create($data);

        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'icon'       => 'nullable|string|max:50',
            'color'      => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $category->update($data);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted successfully.');
    }
}
