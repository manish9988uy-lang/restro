<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount('purchaseOrders');

        if ($request->filled('search')) {
            $query->filter(['search' => $request->search]);
        }

        $suppliers = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Supplier::count(),
            'active' => Supplier::where('is_active', true)->count(),
            'total_due' => Supplier::sum('credit_balance'),
        ];

        return view('suppliers.index', compact('suppliers', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Supplier::create($request->all());

        return back()->with('success', 'Supplier added successfully!');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['purchaseOrders' => fn($q) => $q->latest(), 'payments' => fn($q) => $q->latest()]);
        return view('suppliers.show', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $supplier->update($request->all());

        return back()->with('success', 'Supplier updated successfully!');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->purchaseOrders()->count() > 0) {
            return back()->with('error', 'Cannot delete supplier with existing purchase orders!');
        }

        $supplier->delete();
        return back()->with('success', 'Supplier deleted successfully!');
    }

    public function recordPayment(Request $request, Supplier $supplier)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        SupplierPayment::create([
            'supplier_id' => $supplier->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'reference' => $request->reference,
            'notes' => $request->notes,
        ]);

        // Reduce credit balance
        $supplier->decrement('credit_balance', $request->amount);

        return back()->with('success', 'Supplier payment recorded successfully!');
    }
}
