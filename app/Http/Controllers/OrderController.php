<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\Customer;
use App\Models\RestaurantTable;
use App\Models\Category;
use App\Models\HeldOrder;
use App\Models\Coupon;
use App\Models\OrderStatusHistory;
use App\Models\InventoryMovement;
use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // POS Interface
    public function pos(Request $request)
    {
        $categories = Category::where('is_active', true)
            ->withCount(['menuItems' => fn($q) => $q->where('is_available', true)])
            ->orderBy('sort_order')->get();

        $selectedCategory = $request->get('category');
        $menuQuery = MenuItem::where('is_available', true)->with('category');
        if ($selectedCategory) {
            $menuQuery->where('category_id', $selectedCategory);
        }
        $menuItems  = $menuQuery->get();
        $tables     = RestaurantTable::where('status', '!=', 'maintenance')->orderBy('table_number')->get();
        $customers  = Customer::where('is_active', true)->orderBy('name')->get();
        $heldOrders = HeldOrder::with(['table', 'customer'])->latest()->get();
        $cart       = session()->get('pos_cart', []);
        $cartTotal  = collect($cart)->sum(fn($i) => $i['subtotal']);
        $vatRate    = 10; // 10%
        $vat        = round($cartTotal * $vatRate / 100, 2);
        
        $appliedCoupon = session()->get('pos_coupon');
        $couponDiscount = 0;
        if ($appliedCoupon) {
            $coupon = Coupon::find($appliedCoupon['id']);
            if ($coupon && $coupon->isValid($cartTotal)) {
                $couponDiscount = $coupon->calculateDiscount($cartTotal);
            } else {
                session()->forget('pos_coupon');
                $appliedCoupon = null;
            }
        }
        
        $discount = (float) session()->get('pos_discount', 0);
        $tip      = (float) session()->get('pos_tip', 0);
        $grandTotal = $cartTotal + $vat - max($discount, $couponDiscount) + $tip;

        return view('pos.index', compact(
            'categories', 'menuItems', 'tables', 'customers', 'heldOrders',
            'cart', 'cartTotal', 'vat', 'grandTotal', 'vatRate', 'selectedCategory',
            'appliedCoupon', 'couponDiscount'
        ));
    }

    // Add item to cart
    public function cartAdd(Request $request)
    {
        $item = MenuItem::findOrFail($request->menu_item_id);
        $cart = session()->get('pos_cart', []);
        $key  = 'item_' . $item->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->get('qty', 1);
            $cart[$key]['subtotal']  = $cart[$key]['quantity'] * $cart[$key]['price'];
        } else {
            $cart[$key] = [
                'menu_item_id' => $item->id,
                'name'         => $item->name,
                'price'        => $item->price,
                'quantity'     => $request->get('qty', 1),
                'subtotal'     => $item->price * $request->get('qty', 1),
                'image'        => $item->image,
            ];
        }

        session()->put('pos_cart', $cart);
        return response()->json(['success' => true, 'count' => count($cart)]);
    }

    // Update cart quantity
    public function cartUpdate(Request $request)
    {
        $cart = session()->get('pos_cart', []);
        $key  = 'item_' . $request->menu_item_id;

        if (isset($cart[$key])) {
            $qty = (int) $request->quantity;
            if ($qty <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['quantity'] = $qty;
                $cart[$key]['subtotal'] = $qty * $cart[$key]['price'];
            }
        }

        session()->put('pos_cart', $cart);
        return response()->json(['success' => true]);
    }

    // Remove from cart
    public function cartRemove(Request $request)
    {
        $cart = session()->get('pos_cart', []);
        unset($cart['item_' . $request->menu_item_id]);
        session()->put('pos_cart', $cart);
        return response()->json(['success' => true]);
    }

    // Clear cart
    public function cartClear()
    {
        session()->forget(['pos_cart', 'pos_coupon', 'pos_discount', 'pos_tip']);
        return response()->json(['success' => true]);
    }
    
    // Hold Order
    public function holdOrder(Request $request)
    {
        $request->validate(['hold_name' => 'nullable|string|max:255']);
        
        $cart = session()->get('pos_cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Cart is empty.');
        }
        
        HeldOrder::create([
            'hold_name'   => $request->hold_name,
            'table_id'    => $request->table_id ?: null,
            'customer_id' => $request->customer_id ?: null,
            'user_id'     => auth()->id(),
            'order_type'  => $request->order_type,
            'cart'        => $cart,
            'notes'       => $request->notes,
        ]);
        
        session()->forget(['pos_cart', 'pos_coupon', 'pos_discount', 'pos_tip']);
        
        return back()->with('success', 'Order held successfully!');
    }
    
    // Resume Order
    public function resumeOrder(HeldOrder $heldOrder)
    {
        session()->put('pos_cart', $heldOrder->cart);
        $heldOrder->delete();
        return back()->with('success', 'Order resumed!');
    }
    
    // Delete Held Order
    public function deleteHeldOrder(HeldOrder $heldOrder)
    {
        $heldOrder->delete();
        return back()->with('success', 'Held order deleted!');
    }
    
    // Apply Coupon
    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        
        $coupon = Coupon::where('code', strtoupper($request->code))->first();
        $cartTotal = collect(session()->get('pos_cart', []))->sum(fn($i) => $i['subtotal']);
        
        if (!$coupon || !$coupon->isValid($cartTotal)) {
            return back()->with('error', 'Invalid or expired coupon!');
        }
        
        session()->put('pos_coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
        ]);
        
        return back()->with('success', 'Coupon applied!');
    }
    
    // Remove Coupon
    public function removeCoupon()
    {
        session()->forget('pos_coupon');
        return back()->with('success', 'Coupon removed!');
    }

    // Place order
    public function store(Request $request)
    {
        $request->validate([
            'payment_type' => 'required|string',
            'pay_amount'   => 'required|numeric|min:0',
            'order_type'   => 'required|in:dine_in,takeaway,delivery',
        ]);

        $cart = session()->get('pos_cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Cart is empty.');
        }

        $subTotal = collect($cart)->sum(fn($i) => $i['subtotal']);
        $vatRate  = 10;
        $vat      = round($subTotal * $vatRate / 100, 2);
        
        $appliedCoupon = session()->get('pos_coupon');
        $couponDiscount = 0;
        $coupon = null;
        if ($appliedCoupon) {
            $coupon = Coupon::find($appliedCoupon['id']);
            if ($coupon && $coupon->isValid($subTotal)) {
                $couponDiscount = $coupon->calculateDiscount($subTotal);
            }
        }
        
        $discount = (float) ($request->discount ?? 0);
        $totalDiscount = max($discount, $couponDiscount);
        $tip = (float) ($request->tip ?? 0);
        $total = max(0, $subTotal + $vat - $totalDiscount + $tip);
        $pay = (float) $request->pay_amount;
        $due = max(0, $total - $pay);

        $order = Order::create([
            'invoice_no'     => 'INV-' . strtoupper(Str::random(8)),
            'table_id'       => $request->table_id ?: null,
            'customer_id'    => $request->customer_id ?: null,
            'coupon_id'      => $coupon?->id,
            'user_id'        => auth()->id(),
            'order_type'     => $request->order_type,
            'order_status'   => 'pending',
            'payment_status' => $pay >= $total ? 'paid' : ($pay > 0 ? 'partial' : 'unpaid'),
            'payment_type'   => $request->payment_type,
            'sub_total'      => $subTotal,
            'vat'            => $vat,
            'discount'       => $totalDiscount,
            'tip'            => $tip,
            'total'          => $total,
            'pay_amount'     => $pay,
            'due_amount'     => $due,
            'notes'          => $request->notes,
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id'     => $order->id,
                'menu_item_id' => $item['menu_item_id'],
                'quantity'     => $item['quantity'],
                'unit_price'   => $item['price'],
                'subtotal'     => $item['subtotal'],
            ]);
        }
        
        if ($coupon) {
            $coupon->increment('used_count');
        }

        // Mark table occupied
        if ($request->table_id) {
            RestaurantTable::find($request->table_id)?->update(['status' => 'occupied']);
        }

        // Update customer stats
        if ($request->customer_id) {
            $customer = Customer::find($request->customer_id);
            $customer?->increment('visit_count');
            $customer?->increment('total_spent', $total);
            // Add loyalty points (e.g., 1 point per $1 spent)
            $pointsToAdd = (int) floor($total);
            $customer?->increment('loyalty_points', $pointsToAdd);
            
            // Update membership tier based on total spent
            if ($customer) {
                $newTier = match(true) {
                    $customer->total_spent >= 1000 => 'Platinum',
                    $customer->total_spent >= 500 => 'Gold',
                    $customer->total_spent >= 200 => 'Silver',
                    default => 'Bronze',
                };
                $customer->update(['membership_tier' => $newTier]);
            }
        }

        // Deduct ingredient inventory based on recipe
        $this->deductInventoryForOrder($order);

        session()->forget(['pos_cart', 'pos_coupon', 'pos_discount', 'pos_tip']);

        return redirect()->route('orders.receipt', $order)->with('success', 'Order placed successfully!');
    }

    // Orders list
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'table', 'user', 'items']);

        if ($request->filled('search')) {
            $query->where('invoice_no', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        if ($request->filled('scheduled')) {
            if ($request->scheduled == '1') {
                $query->whereNotNull('scheduled_at');
            } else {
                $query->whereNull('scheduled_at');
            }
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total'     => Order::count(),
            'today'     => Order::whereDate('created_at', today())->count(),
            'pending'   => Order::whereIn('order_status', ['pending', 'preparing'])->count(),
            'revenue'   => Order::where('order_status', 'completed')->sum('total'),
        ];

        return view('orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'table', 'user', 'items.menuItem']);
        return view('orders.show', compact('order'));
    }

    public function receipt(Order $order)
    {
        $order->load(['customer', 'table', 'items.menuItem']);
        return view('orders.receipt', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['order_status' => 'required|in:pending,preparing,ready,completed,cancelled', 'notes' => 'nullable|string']);
        $oldStatus = $order->order_status;
        $updateData = ['order_status' => $request->order_status];

        if ($request->order_status == 'preparing' && !$order->preparing_at) {
            $updateData['preparing_at'] = now();
        }

        $order->update($updateData);

        // Log status history
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $request->order_status,
            'user_id' => auth()->id(),
            'notes' => $request->notes,
        ]);

        // Free table if completed/cancelled
        if (in_array($request->order_status, ['completed', 'cancelled']) && $order->table_id) {
            RestaurantTable::find($order->table_id)?->update(['status' => 'available']);
        }

        return back()->with('success', 'Order status updated.');
    }
    
    // Refund
    public function refund(Request $request, Order $order)
    {
        $request->validate([
            'refund_amount' => 'required|numeric|min:0|max:' . $order->pay_amount,
            'refund_reason' => 'required|string',
        ]);
        
        $order->update([
            'refund_amount' => $order->refund_amount + $request->refund_amount,
            'refund_reason' => $request->refund_reason,
            'payment_status' => $order->refund_amount + $request->refund_amount >= $order->pay_amount ? 'refunded' : 'partial_refund',
        ]);
        
        return back()->with('success', 'Refund processed!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return back()->with('success', 'Order deleted.');
    }

    // Repeat Order
    public function repeatOrder(Order $order)
    {
        // Copy order items to cart
        $cart = [];
        foreach ($order->items as $item) {
            $menuItem = MenuItem::find($item->menu_item_id);
            if ($menuItem && $menuItem->is_available) {
                $cart['item_' . $item->menu_item_id] = [
                    'menu_item_id' => $item->menu_item_id,
                    'name' => $menuItem->name,
                    'price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                    'image' => $menuItem->image,
                ];
            }
        }

        if (empty($cart)) {
            return back()->with('error', 'No available items to repeat.');
        }

        session()->put('pos_cart', $cart);
        return redirect()->route('pos.index')->with('success', 'Order added to cart!');
    }

    // Edit Order
    public function edit(Order $order)
    {
        $order->load('items.menuItem');
        $menuItems = MenuItem::where('is_available', true)->get();
        $tables = RestaurantTable::where('status', '!=', 'maintenance')->get();
        $customers = Customer::where('is_active', true)->get();
        return view('orders.edit', compact('order', 'menuItems', 'tables', 'customers'));
    }

    // Update Order
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'table_id' => 'nullable|exists:restaurant_tables,id',
            'customer_id' => 'nullable|exists:customers,id',
            'order_type' => 'required|in:dine_in,takeaway,delivery',
            'payment_type' => 'required|string',
            'notes' => 'nullable|string',
            'scheduled_at' => 'nullable|date',
            'items' => 'required|array',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Update order basic info
        $order->update([
            'table_id' => $request->table_id,
            'customer_id' => $request->customer_id,
            'order_type' => $request->order_type,
            'payment_type' => $request->payment_type,
            'notes' => $request->notes,
            'scheduled_at' => $request->scheduled_at,
        ]);

        // Recalculate totals
        $subTotal = 0;
        $orderItems = [];
        foreach ($request->items as $item) {
            $menuItem = MenuItem::findOrFail($item['menu_item_id']);
            $subTotal += $menuItem->price * $item['quantity'];
            $orderItems[] = [
                'menu_item_id' => $menuItem->id,
                'quantity' => $item['quantity'],
                'unit_price' => $menuItem->price,
                'subtotal' => $menuItem->price * $item['quantity'],
            ];
        }

        $vatRate = 10;
        $vat = round($subTotal * $vatRate / 100, 2);
        $discount = $order->discount;
        $tip = $order->tip;
        $total = max(0, $subTotal + $vat - $discount + $tip);
        $payAmount = $order->pay_amount;
        $dueAmount = max(0, $total - $payAmount);
        $paymentStatus = $payAmount >= $total ? 'paid' : ($payAmount > 0 ? 'partial' : 'unpaid');

        $order->update([
            'sub_total' => $subTotal,
            'vat' => $vat,
            'total' => $total,
            'due_amount' => $dueAmount,
            'payment_status' => $paymentStatus,
        ]);

        // Update order items (delete old ones, create new)
        $order->items()->delete();
        foreach ($orderItems as $orderItem) {
            OrderItem::create([
                'order_id' => $order->id,
                ...$orderItem,
            ]);
        }

        return redirect()->route('orders.show', $order)->with('success', 'Order updated!');
    }

    protected function deductInventoryForOrder(Order $order)
    {
        $order->loadMissing('items.menuItem.recipe.ingredients.ingredient');
        foreach ($order->items as $item) {
            $recipe = $item->menuItem?->recipe;
            if ($recipe && $recipe->is_active) {
                foreach ($recipe->ingredients as $recipeIngredient) {
                    $ingredient = $recipeIngredient->ingredient;
                    if ($ingredient) {
                        $deductQty = $recipeIngredient->quantity * $item->quantity;
                        $ingredient->decrement('current_stock', $deductQty);

                        InventoryMovement::create([
                            'ingredient_id' => $ingredient->id,
                            'type' => 'sale_deduction',
                            'quantity' => $deductQty,
                            'cost_per_unit' => $ingredient->cost_per_unit,
                            'total_cost' => $deductQty * $ingredient->cost_per_unit,
                            'reference_type' => 'Order',
                            'reference_id' => $order->id,
                            'user_id' => auth()->id(),
                            'notes' => "Sale deduction for Order #{$order->invoice_no} ({$item->menuItem->name} x {$item->quantity})",
                        ]);
                    }
                }
            }
        }
    }
}
