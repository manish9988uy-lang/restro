<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu | Restaurant</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .menu-category { position: sticky; top: 0; z-index: 1020; background: #fff; padding: 10px 0; border-bottom: 1px solid #ddd; }
        .category-link { margin-right: 15px; text-decoration: none; color: #333; font-weight: 500; }
        .category-link:hover { color: #0d6efd; }
        .menu-item-card { border-radius: 12px; overflow: hidden; transition: transform 0.2s; border: none; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .menu-item-card:hover { transform: translateY(-3px); }
        .item-img { height: 120px; object-fit: cover; width: 100%; background: #eee; }
        .cart-bar { position: fixed; bottom: 0; left: 0; right: 0; background: #fff; box-shadow: 0 -2px 10px rgba(0,0,0,0.1); z-index: 1030; padding: 15px; display: none; }
    </style>
</head>
<body>
    <div class="container pb-5 mb-5">
        <div class="d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 fw-bold">Our Menu</h4>
            @if($table)
                <span class="badge bg-primary rounded-pill px-3 py-2">Table: {{ $table->name }}</span>
            @endif
        </div>

        @if($table)
        <div class="alert alert-info d-flex justify-content-between align-items-center">
            <span>Need assistance?</span>
            <button class="btn btn-sm btn-outline-info bg-white" onclick="callWaiter({{ $table->id }})">
                <i class="bi bi-bell-fill me-1"></i>Call Waiter
            </button>
        </div>
        @endif

        <div class="menu-category overflow-auto whitespace-nowrap mb-4 px-2">
            @foreach($categories as $category)
                <a href="#cat-{{ $category->id }}" class="category-link">{{ $category->name }}</a>
            @endforeach
        </div>

        @foreach($categories as $category)
            <div id="cat-{{ $category->id }}" class="mb-4 pt-3">
                <h5 class="mb-3 border-bottom pb-2">{{ $category->name }}</h5>
                <div class="row g-3">
                    @forelse($category->menuItems as $item)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card menu-item-card h-100">
                                <div class="row g-0">
                                    <div class="col-4">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" class="item-img" alt="{{ $item->name }}">
                                        @else
                                            <div class="item-img d-flex align-items-center justify-content-center text-muted">
                                                <i class="bi bi-image" style="font-size: 2rem;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body py-2 pe-2 h-100 d-flex flex-column">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="card-title mb-1 fw-bold">{{ $item->name }}</h6>
                                                <span class="text-success fw-bold">${{ number_format($item->price, 2) }}</span>
                                            </div>
                                            <p class="card-text small text-muted mb-2 flex-grow-1" style="font-size: 0.8rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                {{ $item->description }}
                                            </p>
                                            <button class="btn btn-sm btn-outline-primary mt-auto align-self-end w-100" onclick="addToCart({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }})">
                                                <i class="bi bi-plus"></i> Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-muted small py-2">No items in this category.</div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    <!-- Floating Cart Bar -->
    <div class="cart-bar" id="cartBar">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <span class="fw-bold" id="cartItemCount">0 items</span>
                <span class="text-muted ms-2" id="cartTotal">$0.00</span>
            </div>
            <button class="btn btn-primary px-4 rounded-pill" onclick="placeOrder()">View Order</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let cart = [];
        
        function addToCart(id, name, price) {
            let item = cart.find(i => i.id === id);
            if(item) {
                item.qty++;
            } else {
                cart.push({id, name, price, qty: 1});
            }
            updateCartUI();
        }

        function updateCartUI() {
            let count = cart.reduce((sum, item) => sum + item.qty, 0);
            let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            
            document.getElementById('cartItemCount').innerText = count + (count === 1 ? ' item' : ' items');
            document.getElementById('cartTotal').innerText = '$' + total.toFixed(2);
            
            if(count > 0) {
                document.getElementById('cartBar').style.display = 'block';
            } else {
                document.getElementById('cartBar').style.display = 'none';
            }
        }

        function placeOrder() {
            if(cart.length === 0) return alert('Cart is empty');
            
            if(confirm('Place this order?')) {
                // In a real app, send AJAX request to /qr/order
                alert('Order placed successfully! (Demo)');
                cart = [];
                updateCartUI();
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
            }).then(res => res.json())
            .then(data => {
                alert(data.message);
            }).catch(err => {
                alert('Waiter will be right with you.');
            });
        }
    </script>
</body>
</html>
