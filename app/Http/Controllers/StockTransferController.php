<?php

namespace App\Http\Controllers;

use App\Models\StockTransfer;
use App\Models\Ingredient;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockTransferController extends Controller
{
    public function index()
    {
        $transfers = StockTransfer::with(['ingredient', 'fromBranch', 'toBranch', 'user'])->latest()->paginate(10);
        return view('stock-transfers.index', compact('transfers'));
    }

    public function create()
    {
        $ingredients = Ingredient::all();
        $branches = Branch::where('is_active', true)->get();
        return view('stock-transfers.create', compact('ingredients', 'branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'from_branch_id' => 'required|exists:branches,id',
            'to_branch_id' => 'required|exists:branches,id|different:from_branch_id',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $request) {
            StockTransfer::create([
                'ingredient_id' => $validated['ingredient_id'],
                'from_branch_id' => $validated['from_branch_id'],
                'to_branch_id' => $validated['to_branch_id'],
                'quantity' => $validated['quantity'],
                'transfer_date' => now(),
                'user_id' => auth()->id(),
                'notes' => $request->notes,
                'status' => 'pending',
            ]);
        });

        return redirect()->route('stock-transfers.index')->with('success', 'Stock transfer created successfully!');
    }

    public function show(StockTransfer $stockTransfer)
    {
        $stockTransfer->load(['ingredient', 'fromBranch', 'toBranch', 'user']);
        return view('stock-transfers.show', compact('stockTransfer'));
    }

    public function complete(StockTransfer $stockTransfer)
    {
        if ($stockTransfer->status !== 'pending') {
            return back()->with('error', 'Only pending transfers can be completed!');
        }

        DB::transaction(function () use ($stockTransfer) {
            // Decrease stock in from branch's ingredient
            $fromIngredient = Ingredient::where('id', $stockTransfer->ingredient_id)
                                        ->where('branch_id', $stockTransfer->from_branch_id)
                                        ->first();
            if ($fromIngredient) {
                $fromIngredient->current_stock -= $stockTransfer->quantity;
                $fromIngredient->save();
            }

            // Increase stock in to branch's ingredient
            $toIngredient = Ingredient::where('id', $stockTransfer->ingredient_id)
                                      ->where('branch_id', $stockTransfer->to_branch_id)
                                      ->first();
            if ($toIngredient) {
                $toIngredient->current_stock += $stockTransfer->quantity;
                $toIngredient->save();
            }

            $stockTransfer->update(['status' => 'completed']);
        });

        return back()->with('success', 'Stock transfer completed successfully!');
    }

    public function cancel(StockTransfer $stockTransfer)
    {
        if ($stockTransfer->status !== 'pending') {
            return back()->with('error', 'Only pending transfers can be canceled!');
        }

        $stockTransfer->update(['status' => 'canceled']);

        return back()->with('success', 'Stock transfer canceled!');
    }
}

