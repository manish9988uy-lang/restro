<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $query = MenuItem::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('is_available', $request->status);
        }

        $menuItems  = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('menu.index', compact('menuItems', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('menu.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'name'             => 'required|string|max:150',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'cost_price'       => 'nullable|numeric|min:0',
            'preparation_time' => 'nullable|string|max:50',
            'stock_quantity'   => 'nullable|integer',
            'is_available'     => 'nullable|boolean',
            'is_featured'      => 'nullable|boolean',
            'image'            => 'nullable|image|max:2048',
        ]);

        $data['is_available']   = $request->has('is_available') ? 1 : 0;
        $data['is_featured']    = $request->has('is_featured') ? 1 : 0;
        $data['stock_quantity'] = $data['stock_quantity'] ?? -1;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menu', 'public');
        }

        MenuItem::create($data);

        return redirect()->route('menu.index')->with('success', 'Menu item created successfully.');
    }

    public function edit(MenuItem $menu)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('menu.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, MenuItem $menu)
    {
        $data = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'name'             => 'required|string|max:150',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'cost_price'       => 'nullable|numeric|min:0',
            'preparation_time' => 'nullable|string|max:50',
            'stock_quantity'   => 'nullable|integer',
            'is_available'     => 'nullable|boolean',
            'is_featured'      => 'nullable|boolean',
            'image'            => 'nullable|image|max:2048',
        ]);

        $data['is_available']   = $request->has('is_available') ? 1 : 0;
        $data['is_featured']    = $request->has('is_featured') ? 1 : 0;
        $data['stock_quantity'] = $data['stock_quantity'] ?? -1;

        if ($request->hasFile('image')) {
            if ($menu->image) Storage::disk('public')->delete($menu->image);
            $data['image'] = $request->file('image')->store('menu', 'public');
        }

        $menu->update($data);

        return redirect()->route('menu.index')->with('success', 'Menu item updated successfully.');
    }

    public function destroy(MenuItem $menu)
    {
        if ($menu->image) Storage::disk('public')->delete($menu->image);
        $menu->delete();
        return back()->with('success', 'Menu item deleted.');
    }
}
