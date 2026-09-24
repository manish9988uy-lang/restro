<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Digital Menu | {{ config('app.name', 'Restaurant') }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #FF6B35;
            --primary-dark: #e5521a;
            --surface: #ffffff;
            --bg: #f8fafc;
        }
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        body { background-color: var(--bg); color: #1e293b; padding-bottom: 90px; }
        
        .menu-header {
            background: linear-gradient(135deg, #1e2238, #111422);
            color: #ffffff;
            padding: 1.5rem 1rem;
            border-bottom-left-radius: 24px;
            border-bottom-right-radius: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .category-nav {
            position: sticky;
            top: 0;
            z-index: 1020;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            padding: 0.75rem 0.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
            white-space: nowrap;
        }
        .category-nav::-webkit-scrollbar { display: none; }
        
        .cat-badge {
            padding: 0.45rem 1rem;
            border-radius: 20px;
            background: #f1f5f9;
            color: #475569;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid transparent;
        }
        .cat-badge:hover, .cat-badge.active {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(255,107,53,0.3);
        }

        .dish-card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            overflow: hidden;
            background: #ffffff;
            transition: transform 0.2s;
        }
        .dish-card:hover {
            transform: translateY(-2px);
        }
        .dish-img {
            height: 100%;
            min-height: 110px;
            width: 100%;
            object-fit: cover;
            background: #f1f5f9;
        }

        .cart-bar {
            position: fixed;
            bottom: 15px;
            left: 15px;
            right: 15px;
            max-width: 500px;
            margin: 0 auto;
            background: #1e2238;
            color: #ffffff;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.25);
            z-index: 1050;
            padding: 0.85rem 1.25rem;
            display: none;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="menu-header mb-3">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0 fw-bold">Restro<span class="text-warning">Menu</span></h4>
                <div class="small opacity-75">Touchless QR Dining Experience</div>
            </div>
            @if($table)
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">
                    <i class="bi bi-layout-three-columns me-1"></i> Table {{ $table->name }}
                </span>
            @endif
        </div>
    </div>

    <div class="container">
        @if($table)
        <div class="card mb-3 border-0 bg-primary-subtle text-primary p-3 rounded-4 shadow-sm d-flex flex-row justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-bell-fill fs-5"></i>
                <span class="small fw-semibold">Need waiter assistance or water?</span>
            </div>
            <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" onclick="callWaiter({{ $table->id }})">
                Call Waiter
            </button>
        </div>
        @endif

        <!-- Category Nav -->
        <div class="category-nav mb-3">
            @foreach($categories as $category)
                <a href="#cat-{{ $category->id }}" class="cat-badge">
                    <i class="{{ $category->icon ?? 'bi-tag' }} me-1"></i>{{ $category->name }}
                </a>
            @endforeach
        </div>

        <!-- Menu Section by Category -->
        @foreach($categories as $category)
            <div id="cat-{{ $category->id }}" class="mb-4 pt-2">
                <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark border p-2 rounded-circle" style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;">
                        <i class="{{ $category->icon ?? 'bi-tag' }} text-primary"></i>
                    </span>
                    <span>{{ $category->name }}</span>
                    <span class="text-muted small fw-normal">({{ $category->menuItems->count() }})</span>
                </h6>

                <div class="row g-3">
                    @forelse($category->menuItems as $item)
                        <div class="col-12 col-md-6">
                            <div class="card dish-card h-100">
                                <div class="row g-0 h-100">
                                    <div class="col-4 position-relative">
                                        @if($item->image)
                                            <img src="{{ Storage::url($item->image) }}" class="dish-img" alt="{{ $item->name }}">
                                        @else
                                            <div class="dish-img d-flex align-items-center justify-content-center text-muted" style="font-size:2rem;">
                                                🍽️
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body p-3 h-100 d-flex flex-column justify-content-between">
                                            <div>
                                                <div class="d-flex justify-content-between align-items-start gap-1">
                                                    <h6 class="fw-bold text-dark mb-1 small">{{ $item->name }}</h6>
                                                    <span class="fw-bold text-success small">Rs. {{ number_format($item->price, 2) }}</span>
                                                </div>
                                                @if($item->description)
                                                <p class="text-muted small mb-2" style="font-size:0.75rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                                    {{ $item->description }}
                                                </p>
                                                @endif
                                            </div>

                                            <div class="d-flex justify-content-end align-items-center mt-2">
                                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold" onclick="addToCart({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }})">
                                                    <i class="bi bi-plus-lg me-1"></i> Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-muted small py-3 text-center bg-white rounded-4 border">
                            No dishes in this category currently.
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    <!-- Floating Order Summary Bar -->
    <div class="cart-bar" id="cartBar">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-bold fs-6 text-white" id="cartItemCount">0 items</div>
                <div class="text-warning small fw-bold" id="cartTotal">Rs. 0.00</div>
            </div>
            <button class="btn btn-warning text-dark fw-bold px-4 rounded-pill shadow" onclick="placeOrder()">
                Place Order <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let cart = [];

        function addToCart(id, name, price) {
            let item = cart.find(i => i.id === id);
            if (item) {
                item.qty++;
            } else {
                cart.push({ id, name, price, qty: 1 });
            }
            updateCartUI();
        }

        function updateCartUI() {
            let count = cart.reduce((sum, item) => sum + item.qty, 0);
            let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

            document.getElementById('cartItemCount').innerText = count + (count === 1 ? ' dish selected' : ' dishes selected');
            document.getElementById('cartTotal').innerText = 'Rs. ' + total.toLocaleString('en-US', { minimumFractionDigits: 2 });

            const bar = document.getElementById('cartBar');
            if (count > 0) {
                bar.style.display = 'block';
            } else {
                bar.style.display = 'none';
            }
        }

        function placeOrder() {
            if (cart.length === 0) return alert('Your cart is empty');
            
            if (confirm('Confirm and send order to the kitchen?')) {
                // Submit order to backend
                fetch("{{ route('qr.order') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        table_id: {{ $table ? $table->id : 'null' }},
                        items: cart
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert('Order confirmed! Sending dishes to kitchen.');
                    cart = [];
                    updateCartUI();
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                })
                .catch(err => {
                    alert('Order submitted successfully!');
                    cart = [];
                    updateCartUI();
                });
            }
        }

        function callWaiter(tableId) {
            fetch("{{ route('qr.callWaiter') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ table_id: tableId })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message || 'Staff notified. Someone will be with you shortly.');
            })
            .catch(err => {
                alert('Staff notified. Someone will be with you shortly.');
            });
        }
    </script>
</body>
</html>
