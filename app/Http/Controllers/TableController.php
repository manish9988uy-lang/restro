<?php

namespace App\Http\Controllers;

use App\Models\RestaurantTable;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = RestaurantTable::withCount(['orders as active_orders' => function ($q) {
            $q->whereIn('order_status', ['pending', 'preparing', 'ready']);
        }])->orderBy('table_number')->get();

        $stats = [
            'total'       => $tables->count(),
            'available'   => $tables->where('status', 'available')->count(),
            'occupied'    => $tables->where('status', 'occupied')->count(),
            'reserved'    => $tables->where('status', 'reserved')->count(),
        ];

        return view('tables.index', compact('tables', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'table_number' => 'required|string|max:20|unique:restaurant_tables',
            'name'         => 'required|string|max:100',
            'capacity'     => 'required|integer|min:1|max:50',
            'location'     => 'nullable|string|max:100',
        ]);

        RestaurantTable::create($data);
        return back()->with('success', 'Table added successfully.');
    }

    public function update(Request $request, RestaurantTable $table)
    {
        $data = $request->validate([
            'table_number' => 'required|string|max:20|unique:restaurant_tables,table_number,' . $table->id,
            'name'         => 'required|string|max:100',
            'capacity'     => 'required|integer|min:1|max:50',
            'location'     => 'nullable|string|max:100',
            'status'       => 'required|in:available,occupied,reserved,maintenance',
        ]);

        $table->update($data);
        return back()->with('success', 'Table updated successfully.');
    }

    public function destroy(RestaurantTable $table)
    {
        $table->delete();
        return back()->with('success', 'Table removed.');
    }

    public function updateStatus(Request $request, RestaurantTable $table)
    {
        $request->validate(['status' => 'required|in:available,occupied,reserved,maintenance']);
        $table->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function floor()
    {
        $tables = RestaurantTable::withCount(['orders as active_orders' => function ($q) {
            $q->whereIn('order_status', ['pending', 'preparing', 'ready']);
        }])->orderBy('table_number')->get();

        return view('tables.floor', compact('tables'));
    }

    public function updatePosition(Request $request, RestaurantTable $table)
    {
        $request->validate([
            'position_x' => 'required|integer|min:0',
            'position_y' => 'required|integer|min:0',
            'width' => 'required|integer|min:50|max:300',
            'height' => 'required|integer|min:50|max:300',
        ]);

        $table->update($request->only(['position_x', 'position_y', 'width', 'height']));

        return response()->json(['success' => true, 'table' => $table]);
    }
}
