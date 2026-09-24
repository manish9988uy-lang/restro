<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'Restro POS') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🍽️</text></svg>">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <!-- Bootstrap Icons & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>

    <style>
        :root {
            --primary: #FF6B35;
            --primary-rgb: 255, 107, 53;
            --primary-dark: #e5521a;
            --primary-light: #FFF0E8;
            --sidebar-bg: #111422;
            --sidebar-hover: #1e2238;
            --sidebar-active: #FF6B35;
            --topbar-height: 70px;
            --sidebar-width: 270px;
            --surface: #ffffff;
            --surface-bg: #f4f6fb;
            --text-dark: #1a1d2e;
            --text-muted: #8a94a6;
            --card-radius: 16px;
            --btn-radius: 12px;
        }

        * {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            box-sizing: border-box;
        }

        body {
            background: var(--surface-bg);
            color: var(--text-dark);
            min-height: 100vh;
            overflow-x: hidden;
            font-size: 0.92rem;
            -webkit-font-smoothing: antialiased;
        }

        i.bi, i.fa, i.fas, i.far {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            vertical-align: middle;
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1045;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
        }

        #sidebar .sidebar-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
            background: rgba(0, 0, 0, 0.15);
        }

        #sidebar .brand-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary), #ff8f66);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #fff;
            margin-right: 0.85rem;
            box-shadow: 0 4px 14px rgba(255, 107, 53, 0.35);
        }

        #sidebar .brand-text {
            color: #ffffff;
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: -0.02em;
        }

        #sidebar .brand-text span {
            color: var(--primary);
        }

        #sidebar .brand-sub {
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.04em;
        }

        #sidebar .nav-section {
            color: rgba(255, 255, 255, 0.35);
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 1.3rem 1.4rem 0.4rem;
        }

        #sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 0.65rem 1.25rem;
            border-radius: 10px;
            margin: 3px 12px;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        #sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: #ffffff;
            transform: translateX(3px);
        }

        #sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(255, 107, 53, 0.3);
        }

        #sidebar .nav-link .bi {
            font-size: 1.15rem;
            width: 22px;
            text-align: center;
            opacity: 0.9;
        }

        #sidebar .nav-link.active .bi {
            opacity: 1;
        }

        #sidebar .nav-link .badge {
            margin-left: auto;
            font-size: 0.72rem;
            padding: 0.25rem 0.6rem;
            font-weight: 600;
        }

        /* Sidebar backdrop for mobile */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(17, 20, 34, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1040;
            transition: opacity 0.3s ease;
        }
        .sidebar-backdrop.show {
            display: block;
        }

        /* Main layout structure */
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Topbar Header */
        #topbar {
            height: var(--topbar-height);
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 0 rgba(232, 234, 240, 0.8);
            position: sticky;
            top: 0;
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 2rem;
            gap: 1.25rem;
        }

        .topbar-title {
            font-weight: 700;
            font-size: 1.15rem;
            color: #111422;
            letter-spacing: -0.01em;
            margin: 0;
        }

        .topbar-search-trigger {
            background: #f4f6fb;
            border: 1px solid #e2e5ee;
            border-radius: 12px;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #8a94a6;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            min-width: 240px;
        }

        .topbar-search-trigger:hover {
            background: #eef1f8;
            border-color: #d0d5e2;
            color: #2d3149;
        }

        .topbar-search-trigger kbd {
            background: #ffffff;
            border: 1px solid #d0d5e2;
            color: #6c757d;
            border-radius: 6px;
            padding: 0.15rem 0.45rem;
            font-size: 0.72rem;
            margin-left: auto;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .topbar-badge-btn {
            height: 42px;
            padding: 0 0.85rem;
            border-radius: 12px;
            border: 1px solid #e8eaf0;
            background: #ffffff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #4b5563;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        .topbar-badge-btn:hover {
            background: #f8f9fc;
            border-color: #cbd5e1;
            color: var(--primary);
        }

        .live-clock-badge {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.35rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .live-pulse {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
            70% { box-shadow: 0 0 0 7px rgba(34, 197, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        .avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), #ff8f66);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(255, 107, 53, 0.25);
            transition: transform 0.2s;
        }
        .avatar:hover {
            transform: scale(1.04);
        }

        /* Page Content Area */
        .page-content {
            padding: 2rem;
            flex: 1;
        }

        /* Modern UI Cards */
        .card {
            border: none;
            border-radius: var(--card-radius);
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }

        .card-header {
            background: #ffffff;
            border-bottom: 1px solid #f0f2f5;
            padding: 1.2rem 1.5rem;
            border-radius: var(--card-radius) var(--card-radius) 0 0 !important;
            font-weight: 700;
            color: #111422;
        }

        .stat-card {
            background: #ffffff;
            border-radius: var(--card-radius);
            padding: 1.4rem 1.5rem;
            border: 1px solid rgba(232, 234, 240, 0.7);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            border-color: rgba(255, 107, 53, 0.2);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-label {
            font-size: 0.76rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 0.2rem;
        }

        .stat-value {
            font-size: 1.7rem;
            font-weight: 800;
            color: #111422;
            line-height: 1.15;
            letter-spacing: -0.02em;
        }

        .stat-change {
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Tables */
        .table {
            margin-bottom: 0;
        }

        .table th {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #8a94a6;
            background: #fafbfe;
            border-bottom: 1px solid #edf0f6;
            padding: 0.9rem 1.2rem;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
            font-size: 0.88rem;
            padding: 0.9rem 1.2rem;
            border-bottom: 1px solid #f0f2f7;
            color: #2d3149;
        }

        .table-hover tbody tr:hover {
            background: #f8fafc;
        }

        /* Buttons & Forms */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 0.55rem 1.15rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(255, 107, 53, 0.25);
        }

        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(135deg, #e5521a, #cc420e);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(255, 107, 53, 0.4);
            color: #ffffff;
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: rgba(255, 107, 53, 0.4);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(255, 107, 53, 0.25);
        }

        .btn-sm {
            padding: 0.35rem 0.8rem;
            font-size: 0.82rem;
            border-radius: 8px;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 0.55rem 0.95rem;
            font-size: 0.88rem;
            color: #1e293b;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.15);
        }

        /* Badges */
        .badge {
            font-weight: 600;
            padding: 0.35em 0.7em;
            border-radius: 8px;
            letter-spacing: 0.02em;
        }

        /* Alerts */
        .alert {
            border-radius: 14px;
            border: none;
            padding: 1rem 1.25rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }

        /* Mobile responsiveness */
        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.show {
                transform: translateX(0);
            }
            #main-content {
                margin-left: 0;
            }
            .page-content {
                padding: 1.25rem 1rem;
            }
            #topbar {
                padding: 0 1rem;
            }
            .topbar-search-trigger {
                display: none;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Mobile Sidebar Backdrop -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-shop"></i></div>
        <div>
            <div class="brand-text">Restro<span>POS</span></div>
            <div class="brand-sub">SaaS Restaurant Suite</div>
        </div>
    </div>

    <div class="py-2 flex-grow-1 overflow-y-auto">
        @if(auth()->check() && auth()->user()->hasRole('Super Admin'))
        <div class="nav-section">Super Admin</div>
        <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-shield-star"></i> <span>Super Admin</span>
        </a>
        <a href="{{ route('tenants.index') }}" class="nav-link {{ request()->routeIs('tenants.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i> <span>Tenants</span>
        </a>
        <a href="{{ route('audit-logs.index') }}" class="nav-link {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> <span>Audit Logs</span>
        </a>
        <a href="{{ route('support-tickets.index') }}" class="nav-link {{ request()->routeIs('support-tickets.*') ? 'active' : '' }}">
            <i class="bi bi-ticket-perforated"></i> <span>Support Tickets</span>
        </a>
        @endif

        <div class="nav-section">Operations</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
        </a>
        <a href="{{ route('pos.index') }}" class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> <span>POS Terminal</span>
            <span class="badge bg-warning text-dark rounded-pill">LIVE</span>
        </a>
        <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <i class="bi bi-receipt-cutoff"></i> <span>All Orders</span>
            @php 
                $pendingOrdersCount = \App\Models\Order::whereIn('order_status', ['pending', 'preparing'])->count(); 
            @endphp
            @if($pendingOrdersCount > 0)
                <span class="badge bg-danger rounded-pill">{{ $pendingOrdersCount }}</span>
            @endif
        </a>
        <a href="{{ route('kitchen.index') }}" class="nav-link {{ request()->routeIs('kitchen.*') ? 'active' : '' }}">
            <i class="bi bi-fire"></i> <span>Kitchen (KDS)</span>
        </a>

        <div class="nav-section">Menu & Catalog</div>
        <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> <span>Categories</span>
        </a>
        <a href="{{ route('menu.index') }}" class="nav-link {{ request()->routeIs('menu.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> <span>Menu Items</span>
        </a>
        <a href="{{ route('qr.generate') }}" class="nav-link {{ request()->routeIs('qr.*') ? 'active' : '' }}">
            <i class="bi bi-qr-code"></i> <span>QR Digital Menu</span>
        </a>

        <div class="nav-section">Front of House</div>
        <a href="{{ route('tables.index') }}" class="nav-link {{ request()->routeIs('tables.index') ? 'active' : '' }}">
            <i class="bi bi-layout-three-columns"></i> <span>Tables</span>
        </a>
        <a href="{{ route('tables.floor') }}" class="nav-link {{ request()->routeIs('tables.floor') ? 'active' : '' }}">
            <i class="bi bi-buildings"></i> <span>Floor Plan</span>
        </a>
        <a href="{{ route('reservations.index') }}" class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> <span>Reservations</span>
        </a>
        <a href="{{ route('waitlist.index') }}" class="nav-link {{ request()->routeIs('waitlist.*') ? 'active' : '' }}">
            <i class="bi bi-list-ol"></i> <span>Waitlist</span>
        </a>
        <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> <span>Customers CRM</span>
        </a>

        <div class="nav-section">Inventory & Supply</div>
        <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i> <span>Suppliers</span>
        </a>
        <a href="{{ route('purchase-orders.index') }}" class="nav-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}">
            <i class="bi bi-cart-check"></i> <span>Purchase Orders</span>
        </a>
        <a href="{{ route('ingredients.index') }}" class="nav-link {{ request()->routeIs('ingredients.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> <span>Ingredients</span>
        </a>
        <a href="{{ route('inventory.waste.index') }}" class="nav-link {{ request()->routeIs('inventory.waste.*') ? 'active' : '' }}">
            <i class="bi bi-trash"></i> <span>Waste Tracking</span>
        </a>
        <a href="{{ route('recipes.index') }}" class="nav-link {{ request()->routeIs('recipes.*') ? 'active' : '' }}">
            <i class="bi bi-book"></i> <span>Recipes & Costing</span>
        </a>

        <div class="nav-section">Staff & Delivery</div>
        <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> <span>Staff Employees</span>
        </a>
        <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
            <i class="bi bi-check2-circle"></i> <span>Attendance</span>
        </a>
        <a href="{{ route('deliveries.index') }}" class="nav-link {{ request()->routeIs('deliveries.*') ? 'active' : '' }}">
            <i class="bi bi-bicycle"></i> <span>Deliveries</span>
        </a>
        <a href="{{ route('riders.index') }}" class="nav-link {{ request()->routeIs('riders.*') ? 'active' : '' }}">
            <i class="bi bi-person-rolodex"></i> <span>Riders</span>
        </a>

        <div class="nav-section">Analytics & Admin</div>
        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> <span>Sales & Analytics</span>
        </a>
        <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i> <span>Expenses</span>
        </a>
        <a href="{{ route('coupons.index') }}" class="nav-link {{ request()->routeIs('coupons.*') ? 'active' : '' }}">
            <i class="bi bi-ticket-perforated"></i> <span>Coupons</span>
        </a>
        <a href="{{ route('loyalty.index') }}" class="nav-link {{ request()->routeIs('loyalty.*') ? 'active' : '' }}">
            <i class="bi bi-stars"></i> <span>Loyalty Rewards</span>
        </a>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock"></i> <span>User Management</span>
        </a>
    </div>

    <div class="p-3 border-top border-white-10" style="border-top: 1px solid rgba(255,255,255,0.08);">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent text-danger-emphasis py-2">
                <i class="bi bi-box-arrow-left text-danger"></i> <span class="text-white-50">Logout Session</span>
            </button>
        </form>
    </div>
</nav>

<!-- Main content -->
<div id="main-content">
    <!-- Topbar -->
    <header id="topbar">
        <button class="btn btn-sm btn-outline-secondary d-lg-none me-2" id="sidebarToggle" type="button" aria-label="Toggle Menu">
            <i class="bi bi-list fs-5"></i>
        </button>

        <div>
            <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
        </div>

        <div class="topbar-search-trigger d-none d-md-flex" onclick="commandModal.show()">
            <i class="bi bi-search text-muted"></i>
            <span>Quick search pages, orders, items...</span>
            <kbd>Ctrl+K</kbd>
        </div>

        <div class="topbar-right">
            <!-- Live Clock -->
            <div class="live-clock-badge d-none d-sm-flex">
                <span class="live-pulse"></span>
                <span id="topbarClock">--:--:--</span>
            </div>

            <!-- Fast POS Shortcut -->
            <a href="{{ route('pos.index') }}" class="btn btn-primary btn-sm px-3 shadow-sm rounded-pill">
                <i class="bi bi-plus-circle me-1"></i> New Order
            </a>

            <!-- User Menu -->
            <div class="dropdown">
                <div class="avatar" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-2" style="min-width: 220px; border-radius: 14px;">
                    <li class="px-3 py-2">
                        <div class="fw-700 text-dark">{{ auth()->user()->name ?? 'User' }}</div>
                        <div class="text-muted small" style="font-size: 0.78rem;">{{ auth()->user()->email ?? '' }}</div>
                        <div class="mt-1"><span class="badge bg-primary-subtle text-primary small">Staff Member</span></div>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <a class="dropdown-item rounded-2 py-2 small" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person me-2 text-primary"></i> Profile Settings
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-2 py-2 small" href="{{ route('kitchen.index') }}">
                            <i class="bi bi-fire me-2 text-warning"></i> Kitchen Screen
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item rounded-2 py-2 small text-danger">
                                <i class="bi bi-box-arrow-left me-2"></i> Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Flash messages -->
    @if(session('success') || session('error') || session('status'))
    <div class="px-4 pt-3">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 py-3" role="alert">
            <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <div class="fw-500">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 py-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
            <div class="fw-500">{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('status'))
        <div class="alert alert-info alert-dismissible fade show d-flex align-items-center gap-2 py-3" role="alert">
            <i class="bi bi-info-circle-fill text-info fs-5"></i>
            <div class="fw-500">{{ session('status') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </div>
    @endif

    <!-- Page Content Body -->
    <main class="page-content">
        @yield('content')
    </main>
</div>

<!-- Command Palette Modal -->
<div class="modal fade" id="commandPaletteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header border-0 pb-0 pt-3 px-3">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-white border-0 text-muted ps-3"><i class="bi bi-search fs-5"></i></span>
                    <input type="text" class="form-control border-0 shadow-none fs-5" id="commandSearchInput" placeholder="Type a command, page, or search..." autocomplete="off">
                    <button type="button" class="btn-close me-2 mt-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="list-group list-group-flush border-top" id="commandResults" style="max-height: 400px; overflow-y: auto;">
                    <!-- Results injected here -->
                </div>
            </div>
            <div class="modal-footer border-0 bg-light py-2 text-muted small d-flex justify-content-between">
                <span><kbd class="bg-white text-dark shadow-sm px-2 border">↑</kbd> <kbd class="bg-white text-dark shadow-sm px-2 border">↓</kbd> navigate</span>
                <span><kbd class="bg-white text-dark shadow-sm px-2 border">Enter</kbd> open</span>
                <span><kbd class="bg-white text-dark shadow-sm px-2 border">ESC</kbd> close</span>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Live Clock in Topbar
    function updateClock() {
        const now = new Date();
        const clockEl = document.getElementById('topbarClock');
        if (clockEl) {
            clockEl.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Mobile Sidebar Drawer Toggle & Backdrop
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    const sidebarToggle = document.getElementById('sidebarToggle');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('show');
            backdrop.classList.toggle('show');
        });
    }

    if (backdrop) {
        backdrop.addEventListener('click', function () {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
        });
    }

    // Command Palette Logic
    const commandModalEl = document.getElementById('commandPaletteModal');
    let commandModal = null;
    if (commandModalEl) {
        commandModal = new bootstrap.Modal(commandModalEl);
        const searchInput = document.getElementById('commandSearchInput');
        const resultsContainer = document.getElementById('commandResults');
        let selectedIndex = -1;
        let currentResults = [];

        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                commandModal.show();
            }
        });

        commandModalEl.addEventListener('shown.bs.modal', () => {
            searchInput.focus();
            searchInput.value = '';
            fetchCommands('');
        });

        searchInput.addEventListener('input', (e) => {
            fetchCommands(e.target.value);
        });

        searchInput.addEventListener('keydown', (e) => {
            const items = resultsContainer.querySelectorAll('.list-group-item');
            if (items.length === 0) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = (selectedIndex + 1) % items.length;
                updateSelection(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = (selectedIndex - 1 + items.length) % items.length;
                updateSelection(items);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (selectedIndex >= 0 && selectedIndex < currentResults.length) {
                    window.location.href = currentResults[selectedIndex].url;
                }
            }
        });

        function updateSelection(items) {
            items.forEach((item, index) => {
                if (index === selectedIndex) {
                    item.classList.add('active', 'bg-primary', 'text-white');
                    item.classList.remove('text-dark');
                } else {
                    item.classList.remove('active', 'bg-primary', 'text-white');
                    item.classList.add('text-dark');
                }
            });
            if(items[selectedIndex]) {
                items[selectedIndex].scrollIntoView({ block: 'nearest' });
            }
        }

        function fetchCommands(query) {
            fetch(`{{ route('command.search') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    currentResults = data;
                    selectedIndex = data.length > 0 ? 0 : -1;
                    resultsContainer.innerHTML = '';
                    
                    if(data.length === 0) {
                        resultsContainer.innerHTML = '<div class="p-4 text-center text-muted"><i class="bi bi-search me-1"></i> No matching pages found</div>';
                        return;
                    }

                    data.forEach((cmd, idx) => {
                        const a = document.createElement('a');
                        a.href = cmd.url;
                        a.className = `list-group-item list-group-item-action d-flex align-items-center border-0 px-4 py-3 ${idx === 0 ? 'active bg-primary text-white' : 'text-dark'}`;
                        a.innerHTML = `<i class="bi ${cmd.icon} me-3 fs-5"></i> <span class="fs-6">${cmd.title}</span>`;
                        a.addEventListener('mouseenter', () => {
                            selectedIndex = idx;
                            updateSelection(resultsContainer.querySelectorAll('.list-group-item'));
                        });
                        resultsContainer.appendChild(a);
                    });
                })
                .catch(() => {
                    resultsContainer.innerHTML = '<div class="p-4 text-center text-muted">Error loading search results</div>';
                });
        }
    }
</script>

@stack('scripts')
</body>
</html>
