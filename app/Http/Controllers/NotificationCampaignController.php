<?php

namespace App\Http\Controllers;

use App\Models\NotificationCampaign;
use App\Models\Customer;
use Illuminate\Http\Request;

class NotificationCampaignController extends Controller
{
    public function index(Request $request)
    {
        $campaigns = NotificationCampaign::latest()->paginate(10);
        return view('notifications.index', compact('campaigns'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('notifications.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:sms,email,whatsapp',
            'message' => 'required|string',
            'customer_ids' => 'nullable|array',
            'customer_ids.*' => 'exists:customers,id',
            'scheduled_at' => 'nullable|date',
        ]);

        NotificationCampaign::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'message' => $validated['message'],
            'recipients' => $request->customer_ids,
            'scheduled_at' => $validated['scheduled_at'],
            'status' => $validated['scheduled_at'] ? 'scheduled' : 'draft',
        ]);

        return redirect()->route('notifications.index')->with('success', 'Notification campaign created!');
    }

    public function show(NotificationCampaign $campaign)
    {
        $customers = Customer::all();
        return view('notifications.show', compact('campaign', 'customers'));
    }

    public function edit(NotificationCampaign $campaign)
    {
        $customers = Customer::all();
        return view('notifications.edit', compact('campaign', 'customers'));
    }

    public function update(Request $request, NotificationCampaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:sms,email,whatsapp',
            'message' => 'required|string',
            'customer_ids' => 'nullable|array',
            'customer_ids.*' => 'exists:customers,id',
            'scheduled_at' => 'nullable|date',
        ]);

        $campaign->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'message' => $validated['message'],
            'recipients' => $request->customer_ids,
            'scheduled_at' => $validated['scheduled_at'],
            'status' => $validated['scheduled_at'] ? 'scheduled' : 'draft',
        ]);

        return redirect()->route('notifications.index')->with('success', 'Notification campaign updated!');
    }

    public function destroy(NotificationCampaign $campaign)
    {
        $campaign->delete();
        return back()->with('success', 'Notification campaign deleted!');
    }
}
