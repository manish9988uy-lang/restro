<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS Terminal — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <style>
        :root { --primary:#FF6B35; --sidebar-bg:#1a1d2e; }
        * { font-family:'Inter',sans-serif; }
        body { background:#f4f6fb; overflow: hidden; }
        .pos-topbar {
            height:56px; background:#1a1d2e;
            display:flex; align-items:center; padding:0 1.5rem; gap:1rem;
            position:sticky; top:0; z-index:100;
        }
        .pos-topbar .brand { color:#fff; font-weight:700; font-size:1rem; }
        .pos-topbar .brand span { color:var(--primary); }
        .pos-container { display:flex; height:calc(100vh - 56px); }

        /* LEFT - Menu Panel */
        .menu-panel { flex:1; overflow-y:auto; background:#f4f6fb; padding:1rem 1rem 1rem 1.25rem; }

        /* Category pills */
        .cat-pills { display:flex; gap:.5rem; flex-wrap:nowrap; overflow-x:auto; padding-bottom:.5rem; margin-bottom:1rem; }
        .cat-pills::-webkit-scrollbar { height:3px; }
        .cat-pill {
            white-space:nowrap; padding:.4rem 1rem;
            border-radius:20px; border:2px solid #e2e5ee;
            background:#fff; font-size:.8rem; font-weight:600;
            cursor:pointer; transition:all .2s; color:#6c757d;
        }
        .cat-pill:hover,.cat-pill.active { background:var(--primary); border-color:var(--primary); color:#fff; }

        /* Search */
        .search-box {
            position:relative; margin-bottom:1rem;
        }
        .search-box input {
            border-radius:12px; border:2px solid #e2e5ee;
            padding:.6rem 1rem .6rem 2.7rem; font-size:.875rem;
            width:100%; transition:border-color .2s;
        }
        .search-box input:focus { outline:none; border-color:var(--primary); box-shadow:none; }
        .search-box .bi { position:absolute; left:.9rem; top:50%; transform:translateY(-50%); color:#adb5bd; }

        /* Menu grid */
        .menu-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:.85rem; }
        .menu-card {
            background:#fff; border-radius:14px; overflow:hidden;
            box-shadow:0 2px 6px rgba(0,0,0,.05); cursor:pointer;
            transition:transform .2s, box-shadow .2s; border:2px solid transparent;
        }
        .menu-card:hover { transform:translateY(-3px); box-shadow:0 6px 18px rgba(0,0,0,.1); border-color:var(--primary); }
        .menu-card .menu-img {
            height:110px; background:#f8f9fc;
            display:flex; align-items:center; justify-content:center;
            font-size:2.5rem; overflow:hidden;
        }
        .menu-card .menu-img img { width:100%; height:100%; object-fit:cover; }
        .menu-card .menu-info { padding:.7rem .8rem; }
        .menu-card .menu-name { font-size:.82rem; font-weight:600; color:#1a1d2e; line-height:1.3; margin-bottom:.25rem; }
        .menu-card .menu-price { font-size:.95rem; font-weight:700; color:var(--primary); }
        .menu-card .menu-badge { font-size:.65rem; }

        /* RIGHT - Cart Panel */
        .cart-panel {
            width:370px; min-width:340px;
            background:#fff; display:flex; flex-direction:column;
            box-shadow:-4px 0 16px rgba(0,0,0,.07);
        }
        .cart-header {
            padding:1rem 1.25rem; border-bottom:2px solid #f0f2f5;
            font-weight:700; font-size:1rem; display:flex; align-items:center; justify-content:space-between;
        }
        .cart-items { flex:1; overflow-y:auto; padding:.75rem 1.25rem; }
        .cart-item {
            display:flex; align-items:center; gap:.75rem;
            padding:.6rem 0; border-bottom:1px solid #f0f2f5;
        }
        .cart-item:last-child { border-bottom:none; }
        .cart-item-name { font-size:.85rem; font-weight:600; flex:1; }
        .cart-item-price { font-size:.8rem; color:#6c757d; }
        .qty-btn {
            width:28px; height:28px; border-radius:8px;
            border:1.5px solid #e2e5ee; background:#fff;
            display:flex; align-items:center; justify-content:center;
            cursor:pointer; font-size:.85rem; transition:all .2s;
            color:#1a1d2e;
        }
        .qty-btn:hover { background:var(--primary); border-color:var(--primary); color:#fff; }
        .qty-value { font-weight:700; min-width:28px; text-align:center; font-size:.9rem; }
        .cart-item-total { font-weight:700; font-size:.9rem; min-width:55px; text-align:right; }
        .cart-remove { color:#dc3545; cursor:pointer; font-size:.9rem; }

        /* Cart footer */
        .cart-footer { padding:1rem 1.25rem; border-top:2px solid #f0f2f5; }
        .totals-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:.4rem; font-size:.875rem; }
        .totals-row.grand { font-size:1.15rem; font-weight:700; color:var(--primary); padding-top:.5rem; border-top:2px solid #f0f2f5; margin-top:.5rem; }
        .btn-checkout {
            width:100%; padding:.85rem; border-radius:12px;
            background:var(--primary); border:none; color:#fff;
            font-weight:700; font-size:1rem; cursor:pointer;
            transition:background .2s; margin-top:.75rem;
        }
        .btn-checkout:hover { background:#e5521a; }
        .btn-clear {
            width:100%; padding:.6rem; border-radius:10px;
            background:#fff; border:1.5px solid #e2e5ee; color:#6c757d;
            font-size:.875rem; cursor:pointer; transition:all .2s;
        }
        .btn-clear:hover { border-color:#dc3545; color:#dc3545; }

        .empty-cart { text-align:center; color:#adb5bd; padding:2rem 1rem; }
        .empty-cart .bi { font-size:3rem; display:block; margin-bottom:.75rem; }

        /* Order options */
        .order-options { padding:.75rem 1.25rem; border-bottom:2px solid #f0f2f5; }
        .order-type-btn {
            flex:1; padding:.45rem; border:1.5px solid #e2e5ee;
            border-radius:8px; background:#fff; font-size:.75rem; font-weight:600;
            cursor:pointer; text-align:center; transition:all .2s; color:#6c757d;
        }
        .order-type-btn.active { background:var(--primary); border-color:var(--primary); color:#fff; }
        .form-select-sm, .form-control-sm { border-radius:8px; border-color:#e2e5ee; font-size:.8rem; }
        .form-select-sm:focus, .form-control-sm:focus { border-color:var(--primary); box-shadow:none; }
    </style>
</head>
<body>

<!-- Top bar -->
<div class="pos-topbar">
    <a href="{{ route('dashboard') }}" class="text-white text-decoration-none me-2" style="opacity:.7;">
        <i class="bi bi-arrow-left-circle fs-5"></i>
    </a>
    <span class="brand">Restaurant<span>POS</span></span>
    <span class="text-white ms-2" style="opacity:.4;">|</span>
    <span class="text-white small ms-2" style="opacity:.7;">
        <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
    </span>
    <span class="ms-auto text-white small" style="opacity:.6;" id="clock"></span>
</div>

<div class="pos-container">

    <!-- Menu Panel -->
    <div class="menu-panel">
        <!-- Search -->
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="menuSearch" placeholder="Search menu items..." autocomplete="off"/>
        </div>

        <!-- Categories -->
        <div class="cat-pills">
            <a href="{{ route('pos.index') }}" class="cat-pill {{ !$selectedCategory ? 'active' : '' }}">
                <i class="bi bi-grid me-1"></i>All
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('pos.index', ['category' => $cat->id]) }}"
               class="cat-pill {{ $selectedCategory == $cat->id ? 'active' : '' }}">
                <i class="{{ $cat->icon }} me-1"></i>{{ $cat->name }}
                <span class="ms-1 opacity-75">({{ $cat->menu_items_count }})</span>
            </a>
            @endforeach
        </div>

        <!-- Menu Grid -->
        <div class="menu-grid" id="menuGrid">
            @forelse($menuItems as $item)
            <div class="menu-card" onclick="addToCart({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }})"
                 data-name="{{ strtolower($item->name) }}" data-id="{{ $item->id }}">
                <div class="menu-img">
                    @if($item->image)
                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"/>
                    @else
                        <i class="bi bi-egg-fried text-muted" style="font-size:2.5rem;"></i>
                    @endif
                </div>
                <div class="menu-info">
                    <div class="menu-name">{{ $item->name }}</div>
                    <div class="d-flex align-items-center justify-content-between mt-1">
                        <div class="menu-price">Rs. {{ number_format($item->price, 2) }}</div>
                        @if($item->is_featured)
                        <span class="badge bg-warning text-dark menu-badge">Featured</span>
                        @endif
                    </div>
                    @if($item->preparation_time)
                    <div class="text-muted mt-1" style="font-size:.7rem;"><i class="bi bi-clock me-1"></i>{{ $item->preparation_time }}</div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-5 col-span-all">
                <i class="bi bi-egg-fried" style="font-size:3rem;display:block;margin-bottom:.75rem;"></i>
                No menu items found.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Cart Panel -->
    <div class="cart-panel">
        <!-- Order Options -->
        <div class="order-options">
            <div class="d-flex gap-2 mb-2">
                <button class="order-type-btn active" data-type="dine_in" onclick="setOrderType(this, 'dine_in')">
                    <i class="bi bi-house-door d-block mb-1" style="font-size:1.1rem;"></i>Dine-in
                </button>
                <button class="order-type-btn" data-type="takeaway" onclick="setOrderType(this, 'takeaway')">
                    <i class="bi bi-bag d-block mb-1" style="font-size:1.1rem;"></i>Takeaway
                </button>
                <button class="order-type-btn" data-type="delivery" onclick="setOrderType(this, 'delivery')">
                    <i class="bi bi-bicycle d-block mb-1" style="font-size:1.1rem;"></i>Delivery
                </button>
            </div>
            <div class="row g-2 mb-2">
                <div class="col-6">
                    <select class="form-select form-select-sm" id="tableSelect" name="table_id">
                        <option value="">— Select Table —</option>
                        @foreach($tables as $t)
                        <option value="{{ $t->id }}" @if($t->status == 'occupied') disabled @endif>
                            {{ $t->name }} ({{ $t->capacity }}p) @if($t->status != 'available') [{{ ucfirst($t->status) }}] @endif
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6">
                    <select class="form-select form-select-sm" id="customerSelect" name="customer_id">
                        <option value="">— Customer —</option>
                        @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @if($heldOrders->count())
            <div class="mb-2">
                <label class="form-label small mb-1"><i class="bi bi-pause-circle me-1"></i>Held Orders</label>
                <div class="d-flex flex-wrap gap-1">
                    @foreach($heldOrders as $held)
                    <div class="d-flex gap-1 align-items-center bg-light p-1 rounded">
                        <span class="small">{{ $held->hold_name ?? '#'.$held->id }}</span>
                        <form method="POST" action="{{ route('pos.resume', $held) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success p-0 px-1"><i class="bi bi-play"></i></button>
                        </form>
                        <form method="POST" action="{{ route('pos.held.delete', $held) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger p-0 px-1"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="input-group input-group-sm">
                <input type="text" id="couponCode" class="form-control" placeholder="Coupon Code"/>
                @if($appliedCoupon)
                <button class="btn btn-danger" type="button" onclick="removeCoupon()"><i class="bi bi-x-circle"></i> {{ $appliedCoupon['code'] }}</button>
                @else
                <button class="btn btn-primary" type="button" onclick="applyCoupon()"><i class="bi bi-tag"></i> Apply</button>
                @endif
            </div>
        </div>

        <!-- Cart header -->
        <div class="cart-header">
            <i class="bi bi-cart3 me-2 text-warning"></i>Order Cart
            <span class="badge bg-primary rounded-pill ms-auto" id="cartCount">{{ count($cart) }}</span>
        </div>

        <!-- Cart items -->
        <div class="cart-items" id="cartItems">
            @if(empty($cart))
            <div class="empty-cart">
                <i class="bi bi-cart-x"></i>
                <div class="fw-600">Cart is empty</div>
                <div class="small mt-1">Click on menu items to add them</div>
            </div>
            @else
            @foreach($cart as $key => $item)
            <div class="cart-item" id="cart-item-{{ $item['menu_item_id'] }}">
                <div class="flex-grow-1">
                    <div class="cart-item-name">{{ $item['name'] }}</div>
                    <div class="cart-item-price">Rs. {{ number_format($item['price'], 2) }} each</div>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <button class="qty-btn" onclick="updateQty({{ $item['menu_item_id'] }}, {{ $item['quantity'] - 1 }})">
                        <i class="bi bi-dash"></i>
                    </button>
                    <span class="qty-value">{{ $item['quantity'] }}</span>
                    <button class="qty-btn" onclick="updateQty({{ $item['menu_item_id'] }}, {{ $item['quantity'] + 1 }})">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
                <div class="cart-item-total">Rs. {{ number_format($item['subtotal'], 2) }}</div>
                <i class="bi bi-x-circle cart-remove" onclick="removeItem({{ $item['menu_item_id'] }})"></i>
            </div>
            @endforeach
            @endif
        </div>

        <!-- Cart footer -->
        <div class="cart-footer">
            <div class="totals-row">
                <span class="text-muted">Subtotal</span>
                <span id="subtotalDisplay">Rs. {{ number_format($cartTotal, 2) }}</span>
            </div>
            <div class="totals-row">
                <span class="text-muted">VAT ({{ $vatRate }}%)</span>
                <span id="vatDisplay">Rs. {{ number_format($vat, 2) }}</span>
            </div>
            @if($couponDiscount)
            <div class="totals-row text-success">
                <span>Coupon ({{ $appliedCoupon['code'] }})</span>
                <span>-Rs. {{ number_format($couponDiscount, 2) }}</span>
            </div>
            @endif
            <div class="totals-row">
                <span class="text-muted">Discount</span>
                <div class="input-group input-group-sm" style="width:120px;">
                    <span class="input-group-text" style="border-radius:8px 0 0 8px;font-size:.75rem;">Rs.</span>
                    <input type="number" id="discountInput" class="form-control form-control-sm"
                           min="0" step="0.01" value="0" style="border-radius:0 8px 8px 0;font-size:.8rem;"
                           oninput="recalcTotal()"/>
                </div>
            </div>
            <div class="totals-row">
                <span class="text-muted">Tip</span>
                <div class="input-group input-group-sm" style="width:120px;">
                    <span class="input-group-text" style="border-radius:8px 0 0 8px;font-size:.75rem;">Rs.</span>
                    <input type="number" id="tipInput" class="form-control form-control-sm"
                           min="0" step="0.01" value="0" style="border-radius:0 8px 8px 0;font-size:.8rem;"
                           oninput="recalcTotal()"/>
                </div>
            </div>
            <div class="totals-row grand">
                <span>Total</span>
                <span id="grandTotalDisplay">Rs. {{ number_format($grandTotal, 2) }}</span>
            </div>

            <!-- Payment -->
            <div class="row g-2 mt-1">
                <div class="col-6">
                    <select class="form-select form-select-sm" id="paymentType" required>
                        <option value="">Payment Method</option>
                        <option value="cash">💵 Cash</option>
                        <option value="card">💳 Card</option>
                        <option value="online">📱 Online (eSewa/Khalti)</option>
                        <option value="wallet">👛 Wallet</option>
                    </select>
                </div>
                <div class="col-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="border-radius:8px 0 0 8px;font-size:.75rem;">Rs.</span>
                        <input type="number" id="payAmount" class="form-control form-control-sm"
                               placeholder="Pay amount" min="0" step="0.01"
                               style="border-radius:0 8px 8px 0;font-size:.8rem;"/>
                    </div>
                </div>
            </div>
                               placeholder="Pay amount" min="0" step="0.01"
                               style="border-radius:0 8px 8px 0;font-size:.8rem;"/>
                    </div>
                </div>
            </div>

            <textarea id="orderNotes" class="form-control form-control-sm mt-2"
                      placeholder="Order notes (optional)..." rows="2"
                      style="border-radius:10px;font-size:.78rem;border-color:#e2e5ee;resize:none;"></textarea>

            <div class="row g-2 mt-2">
                <div class="col-6">
                    <button class="btn-checkout" style="padding:.5rem;" onclick="holdOrder()">
                        <i class="bi bi-pause-circle me-1"></i> Hold
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn-checkout" style="padding:.5rem;background:#28a745;" onclick="placeOrder()">
                        <i class="bi bi-check-circle me-1"></i> Place Order
                    </button>
                </div>
            </div>
            <button class="btn-clear mt-2" onclick="clearCart()">
                <i class="bi bi-trash me-1"></i>Clear Cart
            </button>
        </div>
    </div>
</div>

<!-- Hold Order Modal -->
<div class="modal fade" id="holdModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="holdForm" method="POST" action="{{ route('pos.hold') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Hold Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="hold_name" class="form-control" placeholder="Hold name (optional)"/>
                    <input type="hidden" id="holdOrderType" name="order_type"/>
                    <input type="hidden" id="holdTableId" name="table_id"/>
                    <input type="hidden" id="holdCustomerId" name="customer_id"/>
                    <input type="hidden" id="holdNotes" name="notes"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Hold</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Checkout modal (hidden form submission) -->
<form id="orderForm" method="POST" action="{{ route('pos.order.store') }}" style="display:none;">
    @csrf
    <input type="hidden" name="order_type" id="orderTypeInput" value="dine_in"/>
    <input type="hidden" name="table_id" id="tableIdInput"/>
    <input type="hidden" name="customer_id" id="customerIdInput"/>
    <input type="hidden" name="payment_type" id="paymentTypeInput"/>
    <input type="hidden" name="pay_amount" id="payAmountInput"/>
    <input type="hidden" name="discount" id="discountFormInput"/>
    <input type="hidden" name="tip" id="tipFormInput"/>
    <input type="hidden" name="notes" id="notesInput"/>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const CSRF = document.querySelector('meta[name=csrf-token]').content;
    let subtotal = {{ $cartTotal }};
    let vatRate  = {{ $vatRate }};
    let couponDiscount = {{ $couponDiscount ?? 0 }};
    const holdModal = new bootstrap.Modal('#holdModal');

    // Clock
    function updateClock() {
        const d = new Date();
        document.getElementById('clock').textContent =
            d.toLocaleDateString('en-US',{weekday:'short',month:'short',day:'numeric'}) + '  ' +
            d.toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'});
    }
    updateClock(); setInterval(updateClock, 1000);

    // Search
    document.getElementById('menuSearch').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.menu-card').forEach(card => {
            card.style.display = card.dataset.name.includes(q) ? '' : 'none';
        });
    });

    function setOrderType(btn, type) {
        document.querySelectorAll('.order-type-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('orderTypeInput').value = type;
    }

    function recalcTotal() {
        const discount = parseFloat(document.getElementById('discountInput').value) || 0;
        const tip      = parseFloat(document.getElementById('tipInput').value) || 0;
        const vat      = parseFloat((subtotal * vatRate / 100).toFixed(2));
        const totalDiscount = Math.max(discount, couponDiscount);
        const grand    = Math.max(0, subtotal + vat - totalDiscount + tip);
        document.getElementById('vatDisplay').textContent          = 'Rs. ' + vat.toFixed(2);
        document.getElementById('grandTotalDisplay').textContent   = 'Rs. ' + grand.toFixed(2);
        document.getElementById('discountFormInput').value         = discount;
        document.getElementById('tipFormInput').value              = tip;
    }

    async function addToCart(id, name, price) {
        await fetch('{{ route("pos.cart.add") }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify({menu_item_id: id, qty: 1})
        });
        location.reload();
    }

    async function updateQty(id, qty) {
        await fetch('{{ route("pos.cart.update") }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify({menu_item_id: id, quantity: qty})
        });
        location.reload();
    }

    async function removeItem(id) {
        await fetch('{{ route("pos.cart.remove") }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify({menu_item_id: id})
        });
        location.reload();
    }

    async function clearCart() {
        if (!confirm('Clear the cart?')) return;
        await fetch('{{ route("pos.cart.clear") }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF}
        });
        location.reload();
    }

    function holdOrder() {
        if (subtotal === 0) { alert('Cart is empty!'); return; }
        document.getElementById('holdOrderType').value = document.getElementById('orderTypeInput').value;
        document.getElementById('holdTableId').value = document.getElementById('tableSelect').value;
        document.getElementById('holdCustomerId').value = document.getElementById('customerSelect').value;
        document.getElementById('holdNotes').value = document.getElementById('orderNotes').value;
        holdModal.show();
    }

    async function applyCoupon() {
        const code = document.getElementById('couponCode').value;
        if (!code) return;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('pos.coupon.apply') }}';
        form.innerHTML = '<input type="hidden" name="_token" value="' + CSRF + '"><input type="hidden" name="code" value="' + code + '">';
        document.body.appendChild(form);
        form.submit();
    }

    async function removeCoupon() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('pos.coupon.remove') }}';
        form.innerHTML = '<input type="hidden" name="_token" value="' + CSRF + '">';
        document.body.appendChild(form);
        form.submit();
    }

    function placeOrder() {
        if (subtotal === 0) { alert('Cart is empty!'); return; }
        const payType = document.getElementById('paymentType').value;
        const payAmt  = document.getElementById('payAmount').value;
        if (!payType) { alert('Please select a payment method!'); return; }
        if (!payAmt)  { alert('Please enter payment amount!'); return; }

        document.getElementById('tableIdInput').value    = document.getElementById('tableSelect').value;
        document.getElementById('customerIdInput').value = document.getElementById('customerSelect').value;
        document.getElementById('paymentTypeInput').value = payType;
        document.getElementById('payAmountInput').value  = payAmt;
        document.getElementById('discountFormInput').value = document.getElementById('discountInput').value;
        document.getElementById('tipFormInput').value = document.getElementById('tipInput').value;
        document.getElementById('notesInput').value      = document.getElementById('orderNotes').value;
        document.getElementById('orderForm').submit();
    }
</script>
</body>
</html>
