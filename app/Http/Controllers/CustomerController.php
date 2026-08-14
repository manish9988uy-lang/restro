<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::withCount('orders');
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        $customers = $query->latest()->paginate(20)->withQueryString();
        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:150',
            'email'   => 'nullable|email|max:150',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes'   => 'nullable|string',
        ]);

        Customer::create($data);
        return back()->with('success', 'Customer added successfully.');
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:150',
            'email'   => 'nullable|email|max:150',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes'   => 'nullable|string',
        ]);

        $customer->update($data);
        return back()->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'Customer deleted.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['orders.items.menuItem']);
        return view('customers.show', compact('customer'));
    }
}
