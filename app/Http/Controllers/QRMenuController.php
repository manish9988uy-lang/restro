<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RestaurantTable;
use App\Models\MenuItem;
use App\Models\Category;

class QRMenuController extends Controller
{
    public function showMenu(Request $request)
    {
        // For customer facing menu scan
        $table_id = $request->query('table_id');
        $table = null;
        if ($table_id) {
            $table = RestaurantTable::find($table_id);
        }

        $categories = Category::with(['menuItems' => function($q) {
            $q->where('is_available', true);
        }])->get();

        return view('qrmenu.menu', compact('categories', 'table'));
    }

    public function generateQR()
    {
        // Generate QR codes for all tables
        $tables = RestaurantTable::all();
        return view('qrmenu.generate', compact('tables'));
    }

    public function placeOrder(Request $request)
    {
        // Simplified self-ordering flow
        // In a real app this would create an Order, OrderItems, etc.
        return response()->json(['success' => true, 'message' => 'Order placed successfully']);
    }

    public function callWaiter(Request $request)
    {
        $table_id = $request->input('table_id');
        // Logic to notify staff
        return response()->json(['success' => true, 'message' => 'Waiter called.']);
    }

    public function trackOrder(Request $request, $order_id)
    {
        // View to track order status
        return view('qrmenu.track', compact('order_id'));
    }
}
