<?php

namespace App\Http\Controllers;

use App\Models\Waitlist;
use Illuminate\Http\Request;

class WaitlistController extends Controller
{
    public function index()
    {
        $waitlists = Waitlist::orderBy('created_at')->get();
        return view('waitlists.index', compact('waitlists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'party_size' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);
        Waitlist::create($request->all());
        return back()->with('success', 'Added to waitlist!');
    }

    public function update(Request $request, Waitlist $waitlist)
    {
        $request->validate(['status' => 'required|in:waiting,seated,cancelled']);
        $waitlist->update(['status' => $request->status]);
        return back()->with('success', 'Status updated!');
    }

    public function destroy(Waitlist $waitlist)
    {
        $waitlist->delete();
        return back()->with('success', 'Removed from waitlist!');
    }
}
