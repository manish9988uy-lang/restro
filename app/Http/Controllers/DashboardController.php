<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\MenuItem;
use App\Models\Customer;
use App\Models\RestaurantTable;
use App\Models\Reservation;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todayRevenue   = Order::whereDate('created_at', today())
                            ->where('order_status', 'completed')
                            ->sum('total');

        $todayOrders    = Order::whereDate('created_at', today())->count();
        $totalCustomers = Customer::count();
        $availableTables = RestaurantTable::where('status', 'available')->count();
        $totalTables    = RestaurantTable::count();
        $pendingOrders  = Order::whereIn('order_status', ['pending', 'preparing'])->count();

        // Revenue last 7 days
        $revenueChart = Order::where('order_status', 'completed')
            ->where('created_at', '>=', now()->subDays(6))
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $chartLabels = [];
        $chartData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('D');
            $chartData[]   = $revenueChart[$date] ?? 0;
        }

        // Top selling items
        $topItems = DB::table('order_items')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->selectRaw('menu_items.name, SUM(order_items.quantity) as total_qty, SUM(order_items.subtotal) as revenue')
            ->groupBy('menu_items.id', 'menu_items.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Recent orders
        $recentOrders = Order::with(['customer', 'table', 'items'])
            ->latest()
            ->limit(10)
            ->get();

        // Reservations (today)
        $todayReservations = Reservation::whereDate('reservation_time', today())
            ->count();
        $pendingReservations = Reservation::whereDate('reservation_time', today())
            ->where('status', 'pending')
            ->count();
        $upcomingReservations = Reservation::where('reservation_time', '>=', now())
            ->with(['customer', 'table'])
            ->orderBy('reservation_time')
            ->limit(5)
            ->get();

        // Low stock products (stock < 10)
        $lowStockProducts = Product::where('stock', '<', 10)
            ->with('category')
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();
        $lowStockCount = $lowStockProducts->count();

        // Active tables (occupied)
        $activeTables = RestaurantTable::where('status', 'occupied')->get();
        $activeTablesCount = $activeTables->count();

        // Category sales
        $categorySales = DB::table('order_items')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->join('categories', 'menu_items.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, SUM(order_items.subtotal) as total_sales')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sales')
            ->get();

        // Customer growth (last 7 days)
        $customerGrowth = DB::table('customers')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Peak hours (last 7 days)
        $peakHours = Order::where('created_at', '>=', now()->subDays(6))
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Inventory usage: let's get product stock and usage (if we had usage, but for now let's use stock levels)
        $inventoryUsage = Product::with('category')
            ->orderBy('stock', 'asc')
            ->limit(8)
            ->get(['name', 'stock']);
        $inventoryUsageLabels = $inventoryUsage->pluck('name');
        $inventoryUsageData = $inventoryUsage->pluck('stock');

        // Order status counts
        $orderStats = Order::selectRaw('order_status, COUNT(*) as count')
            ->groupBy('order_status')
            ->pluck('count', 'order_status');

        return view('dashboard', compact(
            'todayRevenue', 'todayOrders', 'totalCustomers',
            'availableTables', 'totalTables', 'pendingOrders',
            'chartLabels', 'chartData', 'topItems',
            'recentOrders', 'orderStats', 'todayReservations',
            'pendingReservations', 'upcomingReservations',
            'lowStockProducts', 'lowStockCount',
            'activeTables', 'activeTablesCount',
            'categorySales', 'customerGrowth', 'peakHours',
            'inventoryUsage', 'inventoryUsageLabels', 'inventoryUsageData'
        ));
    }
}
