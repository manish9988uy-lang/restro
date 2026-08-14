<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['createdBy', 'approvedBy']);

        if ($request->filled('search')) {
            $query->where('category', 'like', '%' . $request->search . '%')
                  ->orWhere('notes', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        $expenses = $query->latest()->paginate(20)->withQueryString();
        
        $stats = [
            'total' => Expense::sum('amount'),
            'pending' => Expense::where('status', 'pending')->sum('amount'),
            'approved' => Expense::where('status', 'approved')->sum('amount'),
            'this_month' => Expense::whereMonth('expense_date', now()->month)
                                   ->whereYear('expense_date', now()->year)
                                   ->sum('amount'),
        ];

        return view('expenses.index', compact('expenses', 'stats'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|in:rent,salary,utilities,misc',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        Expense::create([
            'category' => $request->category,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully!');
    }

    public function show(Expense $expense)
    {
        $expense->load(['createdBy', 'approvedBy']);
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'category' => 'required|string|in:rent,salary,utilities,misc',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $expense->update($request->only(['category', 'amount', 'expense_date', 'notes']));

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully!');
    }

    public function approve(Expense $expense)
    {
        $expense->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Expense approved!');
    }

    public function reject(Expense $expense)
    {
        $expense->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Expense rejected!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back()->with('success', 'Expense deleted!');
    }
}
