@extends('layouts.app')
@section('title', 'Order '.$order->invoice_no)
@section('page-title', 'Order Details')

@section('content')
<div class="row g-4">
    <!-- Left column -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-700 me-2">{{ $order->invoice_no }}</span>
                    <span class="badge bg-{{ $order->status_color }}">{{ ucfirst($order->order_status) }}</span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <a href="{{ route('orders.receipt', $order) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-printer me-1"></i>Print
                    </a>
                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Order info grid -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-md-3">
                        <div class="p-3 rounded-3" style="background:#f8f9fc;">
                            <div class="text-muted small mb-1">Order Type</div>
                            <div class="fw-600">{{ ucfirst(str_replace('_',' ',$order->order_type)) }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="p-3 rounded-3" style="background:#f8f9fc;">
                            <div class="text-muted small mb-1">Table</div>
                            <div class="fw-600">{{ $order->table?->name ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="p-3 rounded-3" style="background:#f8f9fc;">
                            <div class="text-muted small mb-1">Payment</div>
                            <div class="fw-600">{{ ucfirst($order->payment_type ?? '—') }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="p-3 rounded-3" style="background:#f8f9fc;">
                            <div class="text-muted small mb-1">Date</div>
                            <div class="fw-600">{{ $order->created_at->format('M d, Y H:i') }}</div>
                        </div>
                    </div>
                    @if($order->scheduled_at)
                    <div class="col-sm-6 col-md-3">
                        <div class="p-3 rounded-3" style="background:#e7f5ff;">
                            <div class="text-muted small mb-1">Scheduled For</div>
                            <div class="fw-600">{{ $order->scheduled_at->format('M d, Y H:i') }}</div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Items table -->
                <h6 class="mb-3 fw-700">Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" style="font-size:.875rem;">
                        <thead style="background:#f8f9fc;">
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->menuItem->name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end fw-600">${{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background:#f8f9fc;">
                            <tr>
                                <td colspan="3" class="text-end fw-600">Subtotal</td>
                                <td class="text-end">${{ number_format($order->sub_total, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-600">VAT</td>
                                <td class="text-end">${{ number_format($order->vat, 2) }}</td>
                            </tr>
                            @if($order->discount > 0)
                            <tr>
                                <td colspan="3" class="text-end fw-600 text-success">Discount</td>
                                <td class="text-end text-success">-${{ number_format($order->discount, 2) }}</td>
                            </tr>
                            @endif
                            @if($order->tip > 0)
                            <tr>
                                <td colspan="3" class="text-end fw-600 text-info">Tip</td>
                                <td class="text-end text-info">${{ number_format($order->tip, 2) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="text-end fw-700 fs-6">Total</td>
                                <td class="text-end fw-700 text-danger fs-6">${{ number_format($order->total, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-600">Paid</td>
                                <td class="text-end text-success">${{ number_format($order->pay_amount, 2) }}</td>
                            </tr>
                            @if($order->due_amount > 0)
                            <tr>
                                <td colspan="3" class="text-end fw-600">Due</td>
                                <td class="text-end text-danger">${{ number_format($order->due_amount, 2) }}</td>
                            </tr>
                            @endif
                            @if($order->refund_amount > 0)
                            <tr>
                                <td colspan="3" class="text-end fw-600 text-danger">Refunded</td>
                                <td class="text-end text-danger">${{ number_format($order->refund_amount, 2) }}</td>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right column -->
    <div class="col-lg-4">
        <!-- Update status -->
        <div class="card mb-3">
            <div class="card-header fw-600"><i class="bi bi-arrow-repeat me-2 text-primary"></i>Update Status</div>
            <div class="card-body">
                <form method="POST" action="{{ route('orders.status', $order) }}">
                    @csrf @method('PATCH')
                    <select name="order_status" class="form-select mb-3">
                        @foreach(['pending','preparing','ready','completed','cancelled'] as $s)
                        <option value="{{ $s }}" @selected($order->order_status == $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <div class="mb-3">
                        <label class="form-label small">Notes (optional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Add a note about this change..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>

        <!-- Order Status History -->
        <div class="card mb-3">
            <div class="card-header fw-600"><i class="bi bi-clock-history me-2 text-info"></i>Status History</div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($order->statusHistories as $history)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="fw-600">{{ $history->user?->name ?? 'System' }}</span>
                                <div class="text-muted small">
                                    @if($history->old_status)
                                    Changed from <span class="badge bg-secondary">{{ ucfirst($history->old_status) }}</span> to
                                    @endif
                                    <span class="badge bg-primary">{{ ucfirst($history->new_status) }}</span>
                                </div>
                                @if($history->notes)
                                <div class="mt-1 small text-muted">{{ $history->notes }}</div>
                                @endif
                            </div>
                            <div class="text-muted small">{{ $history->created_at->format('M d, H:i') }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted py-4">
                        No status history
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Refund -->
        <div class="card mb-3">
            <div class="card-header fw-600"><i class="bi bi-arrow-return-left me-2 text-danger"></i>Process Refund</div>
            <div class="card-body">
                <form method="POST" action="{{ route('orders.refund', $order) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small">Refund Amount</label>
                        <input type="number" name="refund_amount" class="form-control" min="0" max="{{ $order->pay_amount - $order->refund_amount }}" step="0.01" placeholder="0.00">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Refund Reason</label>
                        <textarea name="refund_reason" class="form-control" rows="2" placeholder="Reason for refund..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Refund</button>
                </form>
            </div>
        </div>

        <!-- Customer -->
        <div class="card mb-3">
            <div class="card-header fw-600"><i class="bi bi-person me-2 text-info"></i>Customer</div>
            <div class="card-body">
                @if($order->customer)
                <div class="d-flex align-items-center gap-3">
                    <div style="width:44px;height:44px;background:linear-gradient(135deg,#FF6B35,#ff9f7c);
                         border-radius:50%;display:flex;align-items:center;justify-content:center;
                         color:#fff;font-weight:700;">
                        {{ strtoupper(substr($order->customer->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-600">{{ $order->customer->name }}</div>
                        <div class="text-muted small">{{ $order->customer->phone ?? $order->customer->email ?? 'No contact' }}</div>
                    </div>
                </div>
                @else
                <span class="text-muted small">Walk-in customer</span>
                @endif
            </div>
        </div>

        <!-- Notes -->
        @if($order->notes)
        <div class="card">
            <div class="card-header fw-600"><i class="bi bi-sticky me-2 text-warning"></i>Notes</div>
            <div class="card-body">
                <p class="mb-0 small text-muted">{{ $order->notes }}</p>
            </div>
        </div>
        @endif
        
        <!-- Refund Reason -->
        @if($order->refund_reason)
        <div class="card mt-3">
            <div class="card-header fw-600"><i class="bi bi-exclamation-circle me-2 text-warning"></i>Refund Reason</div>
            <div class="card-body">
                <p class="mb-0 small text-muted">{{ $order->refund_reason }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
