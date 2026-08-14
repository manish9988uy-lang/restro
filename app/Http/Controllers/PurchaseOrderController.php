<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Ingredient;
use App\Models\InventoryMovement;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $purchaseOrders = $query->latest()->paginate(15)->withQueryString();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $ingredients = Ingredient::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total' => PurchaseOrder::count(),
            'pending' => PurchaseOrder::whereIn('status', ['draft', 'ordered'])->count(),
            'received' => PurchaseOrder::where('status', 'received')->count(),
            'total_spent' => PurchaseOrder::where('status', 'received')->sum('total_amount'),
        ];

        return view('purchase_orders.index', compact('purchaseOrders', 'suppliers', 'ingredients', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.ingredient_id' => 'required|exists:ingredients,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $poNumber = 'PO-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $totalAmount = 0;
        foreach ($request->items as $itemData) {
            $totalAmount += $itemData['quantity'] * $itemData['unit_price'];
        }

        $po = PurchaseOrder::create([
            'po_number' => $poNumber,
            'supplier_id' => $request->supplier_id,
            'order_date' => $request->order_date,
            'expected_date' => $request->expected_date,
            'status' => 'ordered',
            'payment_status' => 'unpaid',
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'user_id' => auth()->id(),
            'notes' => $request->notes,
        ]);

        foreach ($request->items as $itemData) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $po->id,
                'ingredient_id' => $itemData['ingredient_id'],
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'],
                'subtotal' => $itemData['quantity'] * $itemData['unit_price'],
            ]);
        }

        // Add to supplier credit balance
        $po->supplier->increment('credit_balance', $totalAmount);

        return redirect()->route('purchase-orders.show', $po)->with('success', 'Purchase Order created successfully!');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'user', 'items.ingredient', 'payments']);
        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    public function updateStatus(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'status' => 'required|in:draft,ordered,received,cancelled',
        ]);

        $oldStatus = $purchaseOrder->status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return back();
        }

        // If changing to RECEIVED, automatically add to inventory
        if ($newStatus === 'received' && $oldStatus !== 'received') {
            foreach ($purchaseOrder->items as $item) {
                $ingredient = $item->ingredient;
                if ($ingredient) {
                    $ingredient->increment('current_stock', $item->quantity);
                    $ingredient->update(['cost_per_unit' => $item->unit_price]);

                    InventoryMovement::create([
                        'ingredient_id' => $ingredient->id,
                        'type' => 'stock_in',
                        'quantity' => $item->quantity,
                        'cost_per_unit' => $item->unit_price,
                        'total_cost' => $item->subtotal,
                        'reference_type' => 'PurchaseOrder',
                        'reference_id' => $purchaseOrder->id,
                        'user_id' => auth()->id(),
                        'notes' => 'Received from PO #' . $purchaseOrder->po_number,
                    ]);
                }
            }
            $purchaseOrder->update(['received_at' => now()]);
        }

        $purchaseOrder->update(['status' => $newStatus]);

        return back()->with('success', 'Purchase Order status updated to ' . ucfirst($newStatus) . '!');
    }

    public function recordPayment(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $newPaidAmount = $purchaseOrder->paid_amount + $request->amount;
        $paymentStatus = $newPaidAmount >= $purchaseOrder->total_amount ? 'paid' : 'partial';

        SupplierPayment::create([
            'supplier_id' => $purchaseOrder->supplier_id,
            'purchase_order_id' => $purchaseOrder->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'reference' => $request->reference,
            'notes' => $request->notes,
        ]);

        $purchaseOrder->update([
            'paid_amount' => $newPaidAmount,
            'payment_status' => $paymentStatus,
        ]);

        // Reduce supplier balance
        $purchaseOrder->supplier->decrement('credit_balance', $request->amount);

        return back()->with('success', 'Payment recorded successfully!');
    }
}
