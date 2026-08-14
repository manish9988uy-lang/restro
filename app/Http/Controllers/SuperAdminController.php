<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Order;
use App\Models\AuditLog;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('is_active', true)->count();
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('order_status', 'completed')->sum('total');
        $recentAuditLogs = AuditLog::latest()->take(10)->get();
        $pendingTickets = SupportTicket::where('status', 'pending')->count();
        $openTickets = SupportTicket::where('status', 'open')->count();

        return view('superadmin.dashboard', compact(
            'totalTenants', 'activeTenants', 'totalUsers', 
            'totalOrders', 'totalRevenue', 'recentAuditLogs',
            'pendingTickets', 'openTickets'
        ));
    }
}
