<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::with('manager')->latest()->paginate(10);
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        $users = User::all();
        return view('branches.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'manager_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        Branch::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'manager_id' => $validated['manager_id'],
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('branches.index')->with('success', 'Branch created successfully!');
    }

    public function show(Branch $branch)
    {
        $branch->load('manager');
        return view('branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        $users = User::all();
        return view('branches.edit', compact('branch', 'users'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'manager_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $branch->update([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'manager_id' => $validated['manager_id'],
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully!');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return back()->with('success', 'Branch deleted successfully!');
    }
}
