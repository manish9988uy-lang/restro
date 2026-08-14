<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    public function index(Request $request)
    {
        $riders = Rider::latest()->paginate(10);
        return view('riders.index', compact('riders'));
    }

    public function create()
    {
        return view('riders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:riders,phone',
            'email' => 'nullable|email|unique:riders,email',
            'vehicle_type' => 'nullable|string|max:255',
            'vehicle_plate' => 'nullable|string|max:255',
            'status' => 'required|in:available,busy,off',
        ]);

        Rider::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_plate' => $request->vehicle_plate,
            'status' => $request->status,
        ]);

        return redirect()->route('riders.index')->with('success', 'Rider created!');
    }

    public function show(Rider $rider)
    {
        $rider->load('deliveries');
        return view('riders.show', compact('rider'));
    }

    public function edit(Rider $rider)
    {
        return view('riders.edit', compact('rider'));
    }

    public function update(Request $request, Rider $rider)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:riders,phone,' . $rider->id,
            'email' => 'nullable|email|unique:riders,email,' . $rider->id,
            'vehicle_type' => 'nullable|string|max:255',
            'vehicle_plate' => 'nullable|string|max:255',
            'status' => 'required|in:available,busy,off',
        ]);

        $rider->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_plate' => $request->vehicle_plate,
            'status' => $request->status,
        ]);

        return redirect()->route('riders.index')->with('success', 'Rider updated!');
    }

    public function destroy(Rider $rider)
    {
        $rider->delete();
        return back()->with('success', 'Rider deleted!');
    }
}
