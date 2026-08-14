<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\Rider;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $query = Delivery::with(['order', 'rider', 'deliveryZone']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $deliveries = $query->latest()->paginate(10);
        return view('deliveries.index', compact('deliveries'));
    }

    public function create()
    {
        $orders = Order::where('order_type', 'delivery')->whereDoesntHave('delivery')->get();
        $riders = Rider::where('status', 'available')->get();
        $zones = DeliveryZone::where('is_active', true)->get();
        return view('deliveries.create', compact('orders', 'riders', 'zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rider_id' => 'nullable|exists:riders,id',
            'delivery_zone_id' => 'nullable|exists:delivery_zones,id',
            'delivery_address' => 'required|string',
            'delivery_fee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $zone = $request->delivery_zone_id ? DeliveryZone::find($request->delivery_zone_id) : null;
        $delivery = Delivery::create([
            'order_id' => $request->order_id,
            'rider_id' => $request->rider_id,
            'delivery_zone_id' => $request->delivery_zone_id,
            'delivery_address' => $request->delivery_address,
            'delivery_fee' => $request->delivery_fee ?? ($zone ? $zone->base_fee : 0),
            'status' => $request->rider_id ? 'assigned' : 'pending',
            'assigned_at' => $request->rider_id ? now() : null,
            'notes' => $request->notes,
        ]);

        if ($request->rider_id) {
            Rider::find($request->rider_id)->update(['status' => 'busy']);
        }

        return redirect()->route('deliveries.index')->with('success', 'Delivery created!');
    }

    public function show(Delivery $delivery)
    {
        $delivery->load(['order', 'rider', 'deliveryZone']);
        $riders = Rider::where('status', 'available')->orWhere('id', $delivery->rider_id)->get();
        return view('deliveries.show', compact('delivery', 'riders'));
    }

    public function edit(Delivery $delivery)
    {
        $orders = Order::where('order_type', 'delivery')->where(function ($q) use ($delivery) {
            $q->whereDoesntHave('delivery')->orWhere('id', $delivery->order_id);
        })->get();
        $riders = Rider::where('status', 'available')->orWhere('id', $delivery->rider_id)->get();
        $zones = DeliveryZone::where('is_active', true)->get();
        return view('deliveries.edit', compact('delivery', 'orders', 'riders', 'zones'));
    }

    public function update(Request $request, Delivery $delivery)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rider_id' => 'nullable|exists:riders,id',
            'delivery_zone_id' => 'nullable|exists:delivery_zones,id',
            'delivery_address' => 'required|string',
            'delivery_fee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Update rider status if changed
        if ($delivery->rider_id && $delivery->rider_id !== $request->rider_id) {
            $oldRider = Rider::find($delivery->rider_id);
            if ($oldRider) {
                $oldRider->update(['status' => 'available']);
            }
        }
        if ($request->rider_id && $request->rider_id !== $delivery->rider_id) {
            Rider::find($request->rider_id)->update(['status' => 'busy']);
        }

        $zone = $request->delivery_zone_id ? DeliveryZone::find($request->delivery_zone_id) : null;
        $delivery->update([
            'order_id' => $request->order_id,
            'rider_id' => $request->rider_id,
            'delivery_zone_id' => $request->delivery_zone_id,
            'delivery_address' => $request->delivery_address,
            'delivery_fee' => $request->delivery_fee ?? ($zone ? $zone->base_fee : 0),
            'status' => $request->rider_id ? 'assigned' : 'pending',
            'assigned_at' => $request->rider_id ? now() : null,
            'notes' => $request->notes,
        ]);

        return redirect()->route('deliveries.index')->with('success', 'Delivery updated!');
    }

    public function assignRider(Request $request, Delivery $delivery)
    {
        $request->validate(['rider_id' => 'required|exists:riders,id']);

        $rider = Rider::find($request->rider_id);
        $rider->update(['status' => 'busy']);

        $delivery->update([
            'rider_id' => $request->rider_id,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        return back()->with('success', 'Rider assigned!');
    }

    public function updateStatus(Request $request, Delivery $delivery)
    {
        $request->validate(['status' => 'required|in:pending,assigned,picked_up,in_transit,delivered,cancelled']);

        $data = ['status' => $request->status];
        if ($request->status === 'picked_up') $data['picked_up_at'] = now();
        if ($request->status === 'delivered') {
            $data['delivered_at'] = now();
            if ($delivery->rider_id) {
                Rider::find($delivery->rider_id)->update(['status' => 'available']);
            }
        }
        if ($request->status === 'cancelled' && $delivery->rider_id) {
            Rider::find($delivery->rider_id)->update(['status' => 'available']);
        }

        $delivery->update($data);
        return back()->with('success', 'Delivery status updated!');
    }

    public function destroy(Delivery $delivery)
    {
        if ($delivery->rider_id) {
            Rider::find($delivery->rider_id)->update(['status' => 'available']);
        }
        $delivery->delete();
        return back()->with('success', 'Delivery deleted!');
    }
}
