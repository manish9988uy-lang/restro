<?php

namespace App\Http\Controllers;

use App\Models\DeliveryZone;
use Illuminate\Http\Request;

class DeliveryZoneController extends Controller
{
    public function index(Request $request)
    {
        $zones = DeliveryZone::latest()->paginate(10);
        return view('delivery_zones.index', compact('zones'));
    }

    public function create()
    {
        return view('delivery_zones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'base_fee' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        DeliveryZone::create([
            'name' => $request->name,
            'base_fee' => $request->base_fee,
            'min_order' => $request->min_order ?? 0,
            'description' => $request->description,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('delivery_zones.index')->with('success', 'Delivery zone created!');
    }

    public function show(DeliveryZone $deliveryZone)
    {
        return view('delivery_zones.show', compact('deliveryZone'));
    }

    public function edit(DeliveryZone $deliveryZone)
    {
        return view('delivery_zones.edit', compact('deliveryZone'));
    }

    public function update(Request $request, DeliveryZone $deliveryZone)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'base_fee' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $deliveryZone->update([
            'name' => $request->name,
            'base_fee' => $request->base_fee,
            'min_order' => $request->min_order ?? 0,
            'description' => $request->description,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('delivery_zones.index')->with('success', 'Delivery zone updated!');
    }

    public function destroy(DeliveryZone $deliveryZone)
    {
        $deliveryZone->delete();
        return back()->with('success', 'Delivery zone deleted!');
    }
}
