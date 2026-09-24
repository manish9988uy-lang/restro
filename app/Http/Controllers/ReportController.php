<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Ingredient;
use App\Models\InventoryMovement;
use App\Models\WasteLog;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Expense;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'sales');
        $range = $request->get('range', 'month');

        // Date calculation based on range or custom from/to
        if ($request->filled('from') && $request->filled('to')) {
            $from = $request->from;
            $to = $request->to;
        } else {
            match ($range) {
                'today' => [$from = now()->format('Y-m-d'), $to = now()->format('Y-m-d')],
                'week'  => [$from = now()->startOfWeek()->format('Y-m-d'), $to = now()->endOfWeek()->format('Y-m-d')],
                'year'  => [$from = now()->startOfYear()->format('Y-m-d'), $to = now()->endOfYear()->format('Y-m-d')],
                default => [$from = now()->startOfMonth()->format('Y-m-d'), $to = now()->format('Y-m-d')],
            };
        }

        $driver = DB::getDriverName();
        $dateExpr = $driver === 'sqlite' ? "strftime('%Y-%m-%d', created_at)" : "DATE(created_at)";

        $completedOrders = Order::where('order_status', 'completed')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        // Key KPI metrics
        $totalRevenue    = (clone $completedOrders)->sum('total');
        $totalOrders     = Order::whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->count();
        $completedCount  = (clone $completedOrders)->count();
        $cancelledCount  = Order::where('order_status', 'cancelled')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)->count();
        $avgOrderValue   = $completedCount > 0 ? round($totalRevenue / $completedCount, 2) : 0;
        $totalVat        = (clone $completedOrders)->sum('vat');
        $totalDiscount   = (clone $completedOrders)->sum('discount');

        // 9.3 Sales breakdown (Daily, Weekly, Monthly)
        $salesTrend = Order::where('order_status', 'completed')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->selectRaw("{$dateExpr} as date, COUNT(*) as orders, SUM(total) as revenue, SUM(vat) as vat, SUM(discount) as discount")
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 9.4 Category / Product sales reports
        $categorySales = DB::table('order_items')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->join('categories', 'menu_items.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.order_status', 'completed')
            ->whereBetween(DB::raw('DATE(orders.created_at)'), [$from, $to])
            ->selectRaw('categories.name as category_name, SUM(order_items.quantity) as qty, SUM(order_items.subtotal) as total')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->get();

        $productSales = DB::table('order_items')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.order_status', 'completed')
            ->whereBetween(DB::raw('DATE(orders.created_at)'), [$from, $to])
            ->selectRaw('menu_items.name, SUM(order_items.quantity) as qty, SUM(order_items.subtotal) as total')
            ->groupBy('menu_items.id', 'menu_items.name')
            ->orderByDesc('total')
            ->limit(20)
            ->get();

        // 9.5 Inventory / Purchase reports
        $inventoryStats = [
            'total_items' => Ingredient::count(),
            'low_stock_items' => Ingredient::whereRaw('current_stock <= alert_threshold')->get(),
            'total_valuation' => Ingredient::selectRaw('SUM(current_stock * cost_per_unit) as val')->value('val') ?? 0,
            'purchase_cost' => PurchaseOrder::where('status', 'received')
                ->whereBetween(DB::raw('DATE(order_date)'), [$from, $to])->sum('total_amount'),
            'waste_cost' => WasteLog::whereBetween(DB::raw('DATE(logged_at)'), [$from, $to])->sum('total_cost'),
        ];

        // 9.6 Employee & Customer reports
        $topCustomers = Customer::withCount(['orders' => fn($q) => $q->where('order_status', 'completed')])
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        $employeeSales = User::withCount(['orders' => fn($q) => $q->where('order_status', 'completed')])
            ->withSum(['orders' => fn($q) => $q->where('order_status', 'completed')], 'total')
            ->get();

        // Expense Data
        $totalExpenses = Expense::where('status', 'approved')
            ->whereBetween('expense_date', [$from, $to])
            ->sum('amount');

        $expenseBreakdown = Expense::where('status', 'approved')
            ->whereBetween('expense_date', [$from, $to])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        // 9.8 Profit & Loss Summary
        $estimatedCogs = InventoryMovement::where('type', 'sale_deduction')
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->sum('total_cost');

        $grossProfit = $totalRevenue - $estimatedCogs;
        $operatingExpenses = $inventoryStats['waste_cost'] + $totalExpenses;
        $netProfit = $grossProfit - $operatingExpenses;

        // Cash Flow Summary
        $cashIn = $totalRevenue;
        $cashOut = $inventoryStats['purchase_cost'] + $totalExpenses;
        $netCashFlow = $cashIn - $cashOut;

        // Branch Comparison Report (Module 19.3)
        $branchComparison = Branch::with(['orders' => fn($q) => $q->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])])
            ->get()
            ->map(function ($branch) use ($from, $to) {
                $branchOrders = $branch->orders()->whereBetween(DB::raw('DATE(created_at)'), [$from, $to]);
                $completedOrders = (clone $branchOrders)->where('order_status', 'completed');
                $totalRevenue = $completedOrders->sum('total');
                $royaltyAmount = $branch->is_franchise
                    ? round($totalRevenue * ($branch->royalty_percentage / 100), 2)
                    : 0;

                return [
                    'id' => $branch->id,
                    'name' => $branch->name,
                    'is_franchise' => $branch->is_franchise,
                    'royalty_percentage' => $branch->royalty_percentage,
                    'total_orders' => $branchOrders->count(),
                    'completed_orders' => $completedOrders->count(),
                    'total_revenue' => $totalRevenue,
                    'avg_order_value' => $completedOrders->count() > 0
                        ? round($totalRevenue / $completedOrders->count(), 2)
                        : 0,
                    'total_expenses' => $branch->expenses()
                        ->where('status', 'approved')
                        ->whereBetween('expense_date', [$from, $to])
                        ->sum('amount'),
                    'royalty_amount' => $royaltyAmount,
                ];
            });

        // Royalty Summary for Franchise Dashboard (Module 19.5)
        $royaltySummary = [
            'total_franchises' => Branch::where('is_franchise', true)->count(),
            'total_royalty' => $branchComparison->sum('royalty_amount'),
        ];

        return view('reports.index', compact(
            'tab', 'range', 'from', 'to', 'totalRevenue', 'totalOrders',
            'completedCount', 'cancelledCount', 'avgOrderValue', 'totalVat',
            'totalDiscount', 'salesTrend', 'categorySales', 'productSales',
            'inventoryStats', 'topCustomers', 'employeeSales',
            'estimatedCogs', 'grossProfit', 'netProfit',
            'totalExpenses', 'expenseBreakdown',
            'cashIn', 'cashOut', 'netCashFlow', 'branchComparison', 'royaltySummary'
        ));
    }

    public function exportCsv(Request $request)
    {
        $type = $request->get('type', 'sales');
        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to   = $request->get('to', now()->format('Y-m-d'));

        $filename = "report_{$type}_{$from}_to_{$to}.csv";
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($type, $from, $to) {
            $file = fopen('php://output', 'w');

            if ($type === 'sales') {
                fputcsv($file, ['Invoice No', 'Date', 'Order Type', 'Status', 'Payment Type', 'Subtotal', 'VAT', 'Discount', 'Total']);
                $orders = Order::whereBetween(DB::raw('DATE(created_at)'), [$from, $to])->get();
                foreach ($orders as $o) {
                    fputcsv($file, [
                        $o->invoice_no, $o->created_at->format('Y-m-d H:i'),
                        $o->order_type, $o->order_status, $o->payment_type,
                        $o->sub_total, $o->vat, $o->discount, $o->total
                    ]);
                }
            } elseif ($type === 'products') {
                fputcsv($file, ['Product Name', 'Quantity Sold', 'Total Revenue']);
                $items = DB::table('order_items')
                    ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('orders.order_status', 'completed')
                    ->whereBetween(DB::raw('DATE(orders.created_at)'), [$from, $to])
                    ->selectRaw('menu_items.name, SUM(order_items.quantity) as qty, SUM(order_items.subtotal) as total')
                    ->groupBy('menu_items.id', 'menu_items.name')
                    ->get();
                foreach ($items as $i) {
                    fputcsv($file, [$i->name, $i->qty, $i->total]);
                }
            } else {
                fputcsv($file, ['Ingredient Name', 'Current Stock', 'Unit', 'Alert Level', 'Unit Cost', 'Total Valuation']);
                $ingredients = Ingredient::all();
                foreach ($ingredients as $ing) {
                    fputcsv($file, [
                        $ing->name, $ing->current_stock, $ing->unit,
                        $ing->alert_threshold, $ing->cost_per_unit,
                        $ing->current_stock * $ing->cost_per_unit
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
