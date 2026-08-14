<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyCampaign;
use Illuminate\Http\Request;

class LoyaltyCampaignController extends Controller
{
    public function index(Request $request)
    {
        $campaigns = LoyaltyCampaign::latest()->paginate(10);
        return view('loyalty.index', compact('campaigns'));
    }

    public function create()
    {
        return view('loyalty.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'points_per_amount' => 'required|integer|min:1',
            'amount_for_points' => 'required|integer|min:1',
            'reward_type' => 'required|in:discount,free_item,points',
            'reward_value' => 'required|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'boolean',
        ]);

        LoyaltyCampaign::create([
            'name' => $validated['name'],
            'points_per_amount' => $validated['points_per_amount'],
            'amount_for_points' => $validated['amount_for_points'],
            'reward_type' => $validated['reward_type'],
            'reward_value' => $validated['reward_value'],
            'valid_from' => $validated['valid_from'],
            'valid_until' => $validated['valid_until'],
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('loyalty.index')->with('success', 'Loyalty campaign created!');
    }

    public function show(LoyaltyCampaign $campaign)
    {
        return view('loyalty.show', compact('campaign'));
    }

    public function edit(LoyaltyCampaign $campaign)
    {
        return view('loyalty.edit', compact('campaign'));
    }

    public function update(Request $request, LoyaltyCampaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'points_per_amount' => 'required|integer|min:1',
            'amount_for_points' => 'required|integer|min:1',
            'reward_type' => 'required|in:discount,free_item,points',
            'reward_value' => 'required|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'boolean',
        ]);

        $campaign->update([
            'name' => $validated['name'],
            'points_per_amount' => $validated['points_per_amount'],
            'amount_for_points' => $validated['amount_for_points'],
            'reward_type' => $validated['reward_type'],
            'reward_value' => $validated['reward_value'],
            'valid_from' => $validated['valid_from'],
            'valid_until' => $validated['valid_until'],
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('loyalty.index')->with('success', 'Loyalty campaign updated!');
    }

    public function destroy(LoyaltyCampaign $campaign)
    {
        $campaign->delete();
        return back()->with('success', 'Loyalty campaign deleted!');
    }
}
