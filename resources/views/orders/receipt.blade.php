<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Receipt — {{ $order->invoice_no }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <style>
        * { font-family:'Inter',sans-serif; margin:0; padding:0; box-sizing:border-box; }
        body { background:#f0f2f5; display:flex; justify-content:center; padding:2rem 1rem; }
        .receipt {
            background:#fff; width:360px; border-radius:16px;
            box-shadow:0 4px 24px rgba(0,0,0,.12); overflow:hidden;
        }
        .receipt-header { background:#1a1d2e; color:#fff; padding:1.5rem; text-align:center; }
        .receipt-header h2 { font-size:1.3rem; margin-bottom:.25rem; }
        .receipt-header h2 span { color:#FF6B35; }
        .receipt-header .invoice { color:rgba(255,255,255,.6); font-size:.85rem; }
        .receipt-body { padding:1.25rem; }
        .info-row { display:flex; justify-content:space-between; font-size:.82rem; margin-bottom:.4rem; color:#4b5563; }
        .info-row .label { color:#9ca3af; }
        .divider { border-top:2px dashed #e5e7eb; margin:1rem 0; }
        table { width:100%; font-size:.82rem; }
        table th { color:#9ca3af; font-weight:600; padding:.3rem 0; }
        table td { padding:.4rem 0; vertical-align:top; }
        table td:last-child { text-align:right; }
        .totals { margin-top:.75rem; }
        .totals .row { display:flex; justify-content:space-between; font-size:.875rem; margin-bottom:.35rem; }
        .totals .grand { font-size:1.1rem; font-weight:700; color:#FF6B35; padding-top:.5rem; border-top:2px solid #e5e7eb; margin-top:.5rem; }
        .badge {
            display:inline-block; padding:.25rem .65rem;
            border-radius:20px; font-size:.72rem; font-weight:600;
        }
        .badge-success { background:#d1fae5; color:#065f46; }
        .badge-warning { background:#fef3c7; color:#92400e; }
        .badge-danger  { background:#fee2e2; color:#991b1b; }
        .receipt-footer { background:#f9fafb; padding:1.25rem; text-align:center; }
        .receipt-footer .thank { font-size:.9rem; font-weight:600; color:#1a1d2e; }
        .receipt-footer .sub { font-size:.75rem; color:#9ca3af; margin-top:.25rem; }
        .btn-row { display:flex; gap:.75rem; padding:1rem 1.25rem; border-top:1px solid #e5e7eb; }
        .btn { flex:1; padding:.65rem; border-radius:10px; font-size:.875rem; font-weight:600; cursor:pointer; border:none; }
        .btn-primary { background:#FF6B35; color:#fff; }
        .btn-primary:hover { background:#e5521a; }
        .btn-secondary { background:#f3f4f6; color:#374151; }
        .btn-secondary:hover { background:#e5e7eb; }
        @media print {
            body { background:#fff; padding:0; }
            .receipt { box-shadow:none; border-radius:0; }
            .btn-row { display:none; }
        }
    </style>
</head>
<body>
<div class="receipt">
    <div class="receipt-header">
        <h2>Restaurant<span>POS</span></h2>
        <div class="invoice">Invoice: {{ $order->invoice_no }}</div>
        <div class="invoice mt-1">{{ $order->created_at->format('M d, Y H:i') }}</div>
    </div>

    <div class="receipt-body">
        @if($order->customer || $order->table)
        <div class="info-row"><span class="label">Customer</span><span>{{ $order->customer?->name ?? 'Walk-in' }}</span></div>
        <div class="info-row"><span class="label">Table</span><span>{{ $order->table?->name ?? '—' }}</span></div>
        @endif
        <div class="info-row"><span class="label">Order Type</span><span>{{ ucfirst(str_replace('_',' ',$order->order_type)) }}</span></div>
        <div class="info-row"><span class="label">Cashier</span><span>{{ $order->user->name }}</span></div>
        <div class="info-row">
            <span class="label">Payment</span>
            <span>{{ ucfirst($order->payment_type) }}</span>
        </div>

        <div class="divider"></div>

        <table>
            <thead><tr><th>Item</th><th style="text-align:center">Qty</th><th>Price</th></tr></thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->menuItem->name }}</td>
                    <td style="text-align:center">{{ $item->quantity }}</td>
                    <td>Rs. {{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <div class="totals">
            <div class="row"><span>Subtotal</span><span>Rs. {{ number_format($order->sub_total, 2) }}</span></div>
            <div class="row"><span>VAT</span><span>Rs. {{ number_format($order->vat, 2) }}</span></div>
            @if($order->discount > 0)
            <div class="row" style="color:#22c55e;"><span>Discount</span><span>-Rs. {{ number_format($order->discount, 2) }}</span></div>
            @endif
            <div class="row grand"><span>TOTAL</span><span>Rs. {{ number_format($order->total, 2) }}</span></div>
            <div class="row" style="font-size:.875rem;"><span>Paid</span><span>Rs. {{ number_format($order->pay_amount, 2) }}</span></div>
            @if($order->due_amount > 0)
            <div class="row" style="color:#dc3545;"><span>Due</span><span>Rs. {{ number_format($order->due_amount, 2) }}</span></div>
            @else
            <div class="row" style="color:#22c55e;"><span>Change</span><span>Rs. {{ number_format($order->pay_amount - $order->total, 2) }}</span></div>
            @endif
        </div>
    </div>

    <div class="receipt-footer">
        <div class="thank">Thank you for dining with us!</div>
        <div class="sub">Please come again • feedback@restaurant.com</div>
    </div>

    <div class="btn-row">
        <button class="btn btn-secondary" onclick="window.location='{{ route('pos.index') }}'">
            New Order
        </button>
        <button class="btn btn-primary" onclick="window.print()">
            🖨 Print Receipt
        </button>
    </div>
</div>
</body>
</html>
