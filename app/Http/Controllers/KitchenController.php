<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        // Get orders that are pending or preparing, not yet completed/canceled
        $orders = Order::with(['items' => function($q) {
                $q->with('menuItem');
            }, 'table'])
            ->whereIn('order_status', ['pending', 'preparing'])
            ->orderBy('created_at')
            ->get();

        return view('kitchen.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:preparing,ready,completed'
        ]);

        $oldStatus = $order->order_status;
        $updateData = ['order_status' => $request->order_status];

        if ($request->order_status === 'preparing' && !$order->preparing_at) {
            $updateData['preparing_at'] = now();
        }

        $order->update($updateData);

        // Log status history
        \App\Models\OrderStatusHistory::create([
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $request->order_status,
            'user_id' => auth()->id()
        ]);

        return back()->with('success', 'Order status updated!');
    }
}
