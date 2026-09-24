<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Restaurant POS') — {{ config('app.name') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <!-- Bootstrap Icons & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>

    <style>
        :root {
            --primary:   #FF6B35;
            --primary-dark: #e5521a;
            --sidebar-bg: #1a1d2e;
            --sidebar-hover: #2d3149;
            --sidebar-active: #FF6B35;
            --topbar-height: 64px;
            --sidebar-width: 260px;
        }
        * { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }
        i.bi, i.fa, i.fas, i.far { display: inline-flex; align-items: center; justify-content: center; line-height: 1; vertical-align: middle; }
        body { background: #f4f6fb; color: #2d3149; }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0; z-index: 1040;
            transition: transform .3s ease;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        #sidebar .sidebar-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        #sidebar .brand-icon {
            width: 38px; height: 38px;
            background: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: #fff; margin-right: .75rem;
        }
        #sidebar .brand-text { color: #fff; font-weight: 700; font-size: 1.1rem; line-height: 1; }
        #sidebar .brand-text span { color: var(--primary); }
        #sidebar .brand-sub { color: rgba(255,255,255,.45); font-size: .7rem; font-weight: 400; }
        #sidebar .nav-section {
            color: rgba(255,255,255,.3);
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 1.2rem 1.5rem .4rem;
        }
        #sidebar .nav-link {
            color: rgba(255,255,255,.65);
            padding: .6rem 1.5rem;
            border-radius: 8px;
            margin: 2px 12px;
            font-size: .875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: .65rem;
            transition: all .2s;
        }
        #sidebar .nav-link:hover { background: var(--sidebar-hover); color: #fff; }
        #sidebar .nav-link.active { background: var(--primary); color: #fff; }
        #sidebar .nav-link .bi { font-size: 1.1rem; width: 22px; text-align: center; }
        #sidebar .nav-link .badge { margin-left: auto; }

        /* Main content */
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin .3s ease;
        }

        /* Topbar */
        #topbar {
            height: var(--topbar-height);
            background: #fff;
            box-shadow: 0 1px 0 #e8eaf0;
            position: sticky; top: 0; z-index: 1030;
            display: flex; align-items: center;
            padding: 0 1.75rem; gap: 1rem;
        }
        #topbar .topbar-title { font-weight: 600; font-size: 1rem; color: #1a1d2e; }
        #topbar .topbar-right { margin-left: auto; display: flex; align-items: center; gap: .75rem; }
        .btn-topbar {
            width: 38px; height: 38px;
            border-radius: 10px;
            border: 1px solid #e8eaf0;
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            color: #6c757d; font-size: 1.1rem;
            cursor: pointer; transition: all .2s;
        }
        .btn-topbar:hover { background: #f4f6fb; color: var(--primary); border-color: var(--primary); }
        .avatar {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--primary), #ff9f7c);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: .875rem;
        }

        /* Page content */
        .page-content { padding: 1.75rem; }
        .page-header { margin-bottom: 1.5rem; }
        .page-header h1 { font-size: 1.4rem; font-weight: 700; color: #1a1d2e; margin: 0; }
        .page-header .breadcrumb { margin: 0; font-size: .8rem; }

        /* Stat cards */
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.4rem 1.5rem;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.09); }
        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
        }
        .stat-label { font-size: .78rem; color: #8a94a6; font-weight: 500; text-transform: uppercase; letter-spacing: .05em; }
        .stat-value { font-size: 1.75rem; font-weight: 700; color: #1a1d2e; line-height: 1.1; }
        .stat-change { font-size: .78rem; }

        /* Cards */
        .card { border: none; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.05); }
        .card-header { background: #fff; border-bottom: 1px solid #f0f2f5; padding: 1.1rem 1.5rem; border-radius: 16px 16px 0 0 !important; font-weight: 600; }

        /* Tables */
        .table th { font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #8a94a6; border-top: none; }
        .table td { vertical-align: middle; font-size: .875rem; }
        .table-hover tbody tr:hover { background: #f8f9fc; }

        /* Badges */
        .badge { font-weight: 500; }

        /* Buttons */
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover, .btn-primary:focus { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); border-color: var(--primary); }

        /* Sidebar toggle for mobile */
        @media (max-width: 991.98px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-left: 0; }
        }

        /* Alert */
        .alert { border-radius: 12px; border: none; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d0d5dd; border-radius: 10px; }
    </style>

    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-shop"></i></div>
        <div>
            <div class="brand-text">Restaurant<span>POS</span></div>
            <div class="brand-sub">Management System</div>
        </div>
    </div>

    <div class="py-2 flex-grow-1 overflow-y-auto">
        @if(auth()->user()->hasRole('Super Admin'))
        <div class="nav-section">Super Admin</div>
        <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-shield-star"></i> Super Admin Dashboard
        </a>
        <a href="{{ route('tenants.index') }}" class="nav-link {{ request()->routeIs('tenants.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i> Manage Tenants
        </a>
        <a href="{{ route('audit-logs.index') }}" class="nav-link {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> Audit Logs
        </a>
        <a href="{{ route('support-tickets.index') }}" class="nav-link {{ request()->routeIs('support-tickets.*') ? 'active' : '' }}">
            <i class="bi bi-ticket-perforated"></i> Support Tickets
        </a>
        @endif

        <div class="nav-section">Main</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('pos.index') }}" class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> POS Terminal
        </a>

        <div class="nav-section">Orders</div>
        <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <i class="bi bi-receipt-cutoff"></i> All Orders
            @php $pending = \App\Models\Order::whereIn('order_status',['pending','preparing'])->count() @endphp
            @if($pending > 0)
            <span class="badge bg-warning text-dark rounded-pill">{{ $pending }}</span>
            @endif
        </a>

        <div class="nav-section">Menu</div>
        <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Categories
        </a>
        <a href="{{ route('menu.index') }}" class="nav-link {{ request()->routeIs('menu.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Menu Items
        </a>

        <div class="nav-section">Users</div>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> Users
        </a>
        <a href="{{ route('role.index') }}" class="nav-link {{ request()->routeIs('role.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock"></i> Roles
        </a>
        <a href="{{ route('permission.index') }}" class="nav-link {{ request()->routeIs('permission.*') ? 'active' : '' }}">
            <i class="bi bi-key"></i> Permissions
        </a>
        <a href="{{ route('rolePermission.index') }}" class="nav-link {{ request()->routeIs('rolePermission.*') ? 'active' : '' }}">
            <i class="bi bi-shield-check"></i> Role Permissions
        </a>

        <div class="nav-section">Restaurant</div>
        <a href="{{ route('tables.index') }}" class="nav-link {{ request()->routeIs('tables.*') ? 'active' : '' }}">
            <i class="bi bi-layout-three-columns"></i> Tables
        </a>
        <a href="{{ route('waitlist.index') }}" class="nav-link {{ request()->routeIs('waitlist.*') ? 'active' : '' }}">
            <i class="bi bi-list-ol"></i> Waitlist
        </a>
        <a href="{{ route('reservations.index') }}" class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> Reservations
        </a>
        <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Customers
        </a>
        <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> Employees
        </a>
        <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
            <i class="bi bi-check-circle"></i> Attendance
        </a>
        <div class="nav-section">Inventory & Suppliers</div>
        <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i> Suppliers
        </a>
        <a href="{{ route('purchase-orders.index') }}" class="nav-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}">
            <i class="bi bi-cart-check"></i> Purchase Orders
        </a>
        <a href="{{ route('ingredients.index') }}" class="nav-link {{ request()->routeIs('ingredients.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Ingredients
        </a>
        <a href="{{ route('inventory.waste.index') }}" class="nav-link {{ request()->routeIs('inventory.waste.*') ? 'active' : '' }}">
            <i class="bi bi-trash"></i> Waste Tracking
        </a>
        <a href="{{ route('recipes.index') }}" class="nav-link {{ request()->routeIs('recipes.*') ? 'active' : '' }}">
            <i class="bi bi-book"></i> Recipes & Costing
        </a>

        <a href="{{ route('kitchen.index') }}" class="nav-link {{ request()->routeIs('kitchen.*') ? 'active' : '' }}">
            <i class="bi bi-cooking-pot"></i> Kitchen Display
        </a>
        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Reports
        </a>
        <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i> Expenses
        </a>
        <a href="{{ route('coupons.index') }}" class="nav-link {{ request()->routeIs('coupons.*') ? 'active' : '' }}">
            <i class="bi bi-ticket-perforated"></i> Coupons
        </a>
        <a href="{{ route('loyalty.index') }}" class="nav-link {{ request()->routeIs('loyalty.*') ? 'active' : '' }}">
            <i class="bi bi-stars"></i> Loyalty
        </a>
        <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
            <i class="bi bi-megaphone"></i> Notifications
        </a>
        <a href="{{ route('delivery_zones.index') }}" class="nav-link {{ request()->routeIs('delivery_zones.*') ? 'active' : '' }}">
            <i class="bi bi-geo-alt"></i> Delivery Zones
        </a>
        <a href="{{ route('riders.index') }}" class="nav-link {{ request()->routeIs('riders.*') ? 'active' : '' }}">
            <i class="bi bi-person-rolodex"></i> Riders
        </a>
        <a href="{{ route('deliveries.index') }}" class="nav-link {{ request()->routeIs('deliveries.*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i> Deliveries
        </a>
    </div>

    <div class="px-3 pb-3 mt-2">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent"
                    style="color:rgba(255,255,255,.5);">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>
    </div>
</nav>

<!-- Main content -->
<div id="main-content">
    <!-- Topbar -->
    <header id="topbar">
        <button class="btn-topbar d-lg-none me-1" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        <div class="topbar-right">
            <a href="{{ route('pos.index') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="bi bi-plus-circle me-1"></i> New Order
            </a>
            <div class="dropdown">
                <div class="avatar" role="button" data-bs-toggle="dropdown">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="min-width:180px;border-radius:12px;">
                    <li class="px-3 py-2">
                        <div class="fw-600 small">{{ auth()->user()->name }}</div>
                        <div class="text-muted" style="font-size:.75rem;">{{ auth()->user()->email }}</div>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <a class="dropdown-item small" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person me-2"></i> Profile
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item small text-danger">
                                <i class="bi bi-box-arrow-left me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Flash messages -->
    <div class="px-4 pt-3">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 py-2" role="alert">
            <i class="bi bi-check-circle-fill text-success"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 py-2" role="alert">
            <i class="bi bi-exclamation-circle-fill text-danger"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </div>

    <!-- Page Content -->
    <main class="page-content">
        @yield('content')
    </main>
</div>

<!-- Command Palette Modal -->
<div class="modal fade" id="commandPaletteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header border-0 pb-0 pt-3 px-3">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-white border-0 text-muted ps-3"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-0 shadow-none fs-5" id="commandSearchInput" placeholder="Search commands, pages, and features..." autocomplete="off">
                    <button type="button" class="btn-close me-2 mt-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="list-group list-group-flush border-top" id="commandResults" style="max-height: 400px; overflow-y: auto;">
                    <!-- Results injected here -->
                </div>
            </div>
            <div class="modal-footer border-0 bg-light py-2 text-muted small d-flex justify-content-between">
                <span><kbd class="bg-white text-dark shadow-sm px-2 border">↑</kbd> <kbd class="bg-white text-dark shadow-sm px-2 border">↓</kbd> to navigate</span>
                <span><kbd class="bg-white text-dark shadow-sm px-2 border">Enter</kbd> to select</span>
                <span><kbd class="bg-white text-dark shadow-sm px-2 border">ESC</kbd> to close</span>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Sidebar toggle for mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // Command Palette Logic
    const commandModal = new bootstrap.Modal(document.getElementById('commandPaletteModal'));
    const searchInput = document.getElementById('commandSearchInput');
    const resultsContainer = document.getElementById('commandResults');
    let selectedIndex = -1;
    let currentResults = [];

    // Keyboard shortcut Ctrl+K or Cmd+K
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            commandModal.show();
        }
    });

    document.getElementById('commandPaletteModal').addEventListener('shown.bs.modal', () => {
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
                    resultsContainer.innerHTML = '<div class="p-4 text-center text-muted">No commands found</div>';
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
            });
    }
</script>

@stack('scripts')
</body>
</html>
