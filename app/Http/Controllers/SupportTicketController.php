<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with(['user', 'tenant'])->latest()->paginate(20);
        return view('support-tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('support-tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'priority' => 'in:low,medium,high,urgent',
        ]);

        SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'priority' => $validated['priority'] ?? 'medium',
        ]);

        return redirect()->route('support-tickets.index')->with('success', 'Ticket created successfully!');
    }

    public function show(SupportTicket $supportTicket)
    {
        $supportTicket->load(['user', 'tenant']);
        return view('support-tickets.show', compact('supportTicket'));
    }

    public function updateStatus(Request $request, SupportTicket $supportTicket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $supportTicket->update($validated);
        return back()->with('success', 'Ticket status updated!');
    }
}
