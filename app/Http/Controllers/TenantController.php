<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::latest()->paginate(10);
        return view('tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('tenants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255|unique:tenants',
            'email' => 'required|email|unique:tenants',
            'phone' => 'nullable|string|max:20',
            'plan_name' => 'required|string|max:50',
            'trial_ends_at' => 'nullable|date',
            'subscription_ends_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        Tenant::create([
            'name' => $validated['name'],
            'domain' => $validated['domain'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'plan_name' => $validated['plan_name'],
            'trial_ends_at' => $validated['trial_ends_at'],
            'subscription_ends_at' => $validated['subscription_ends_at'],
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('tenants.index')->with('success', 'Tenant registered successfully.');
    }

    public function show(Tenant $tenant)
    {
        return view('tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        return view('tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255|unique:tenants,domain,' . $tenant->id,
            'email' => 'required|email|unique:tenants,email,' . $tenant->id,
            'phone' => 'nullable|string|max:20',
            'plan_name' => 'required|string|max:50',
            'trial_ends_at' => 'nullable|date',
            'subscription_ends_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $tenant->update([
            'name' => $validated['name'],
            'domain' => $validated['domain'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'plan_name' => $validated['plan_name'],
            'trial_ends_at' => $validated['trial_ends_at'],
            'subscription_ends_at' => $validated['subscription_ends_at'],
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('tenants.index')->with('success', 'Tenant updated successfully.');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return back()->with('success', 'Tenant deleted successfully.');
    }
}
