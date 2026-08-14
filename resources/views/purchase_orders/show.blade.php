@extends('layouts.app')

@section('title', $purchaseOrder->po_number . ' — PO Details')
@section('page-title', 'Purchase Order Details')

@section('content')
<div class="mb-3 d-flex justify-content-between align-items-center">
    <a href="{{ route('purchase-orders.index') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i> Back to Purchase Orders</a>
    
    @if($purchaseOrder->status !== 'received')
    <form action="{{ route('purchase-orders.status', $purchaseOrder) }}" method="POST" class="d-flex align-items-center gap-2">
        @csrf
        @method('PATCH')
        <span class="small text-muted fw-bold">Update Status:</span>
        <select name="status" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
            <option value="draft" {{ $purchaseOrder->status === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="ordered" {{ $purchaseOrder->status === 'ordered' ? 'selected' : '' }}>Ordered</option>
            <option value="received" {{ $purchaseOrder->status === 'received' ? 'selected' : '' }}>Received (Stock In)</option>
            <option value="cancelled" {{ $purchaseOrder->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
    </form>
    @else
    <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i> Received & Stock Updated</span>
    @endif
</div>

<div class="row g-4">
    <!-- PO Header & Details -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <div>
                    <h5 class="fw-bold mb-0 text-primary">{{ $purchaseOrder->po_number }}</h5>
                    <span class="small text-muted">Created by {{ $purchaseOrder->user->name ?? 'System' }} on {{ $purchaseOrder->created_at->format('M d, Y') }}</span>
                </div>
                <div class="text-end">
                    <span class="badge bg-info text-dark fs-6">{{ ucfirst($purchaseOrder->status) }}</span>
                    <span class="badge {{ $purchaseOrder->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }} fs-6 ms-1">
                        {{ ucfirst($purchaseOrder->payment_status) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Order Date</small>
                        <strong class="text-dark">{{ $purchaseOrder->order_date }}</strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Expected Date</small>
                        <strong class="text-dark">{{ $purchaseOrder->expected_date ?? 'N/A' }}</strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Received Date</small>
                        <strong class="text-dark">{{ $purchaseOrder->received_at ? \Carbon\Carbon::parse($purchaseOrder->received_at)->format('Y-m-d H:i') : 'Not Received Yet' }}</strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Total Amount</small>
                        <strong class="text-success fs-5">${{ number_format($purchaseOrder->total_amount, 2) }}</strong>
                    </div>
                </div>

                <h6 class="fw-bold mb-3">Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Ingredient</th>
                                <th>Unit</th>
                                <th>Quantity</th>
                                <th>Unit Price ($)</th>
                                <th class="text-end">Subtotal ($)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchaseOrder->items as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td class="fw-bold">{{ $item->ingredient->name ?? 'Deleted Item' }}</td>
                                <td>{{ $item->ingredient->unit ?? '-' }}</td>
                                <td>{{ number_format($item->quantity, 3) }}</td>
                                <td>${{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end fw-bold">${{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Grand Total:</td>
                                <td class="text-end fw-bold fs-5 text-primary">${{ number_format($purchaseOrder->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($purchaseOrder->notes)
                <div class="mt-3 p-3 bg-light rounded">
                    <small class="text-muted d-block fw-bold mb-1">Notes / Special Instructions:</small>
                    <p class="mb-0 small">{{ $purchaseOrder->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Payments Log for PO -->
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-cash-stack text-success me-2"></i>Payments Recorded</h6>
                @if($purchaseOrder->payment_status !== 'paid')
                <button class="btn btn-sm btn-success rounded-pill" data-bs-toggle="modal" data-bs-target="#recordPOPaymentModal">
                    <i class="bi bi-plus-lg me-1"></i> Record Payment
                </button>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Date</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th class="pe-3 text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchaseOrder->payments as $pmt)
                            <tr>
                                <td class="ps-3">{{ $pmt->payment_date }}</td>
                                <td><span class="badge bg-light text-dark border">{{ ucfirst($pmt->payment_method) }}</span></td>
                                <td>{{ $pmt->reference ?? '-' }}</td>
                                <td class="pe-3 text-end fw-bold text-success">${{ number_format($pmt->amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-3 text-muted">No payments recorded for this PO yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Supplier Information Sidebar -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-building text-primary me-2"></i>Supplier Info</h6>
            </div>
            <div class="card-body">
                <h5 class="fw-bold">{{ $purchaseOrder->supplier->name ?? 'N/A' }}</h5>
                <p class="text-muted small">{{ $purchaseOrder->supplier->company_name ?? '' }}</p>
                <hr>
                <div class="vstack gap-2 small">
                    <div><strong class="text-muted">Contact Person:</strong> {{ $purchaseOrder->supplier->contact_person ?? 'N/A' }}</div>
                    <div><strong class="text-muted">Phone:</strong> {{ $purchaseOrder->supplier->phone ?? 'N/A' }}</div>
                    <div><strong class="text-muted">Email:</strong> {{ $purchaseOrder->supplier->email ?? 'N/A' }}</div>
                    <div><strong class="text-muted">Address:</strong> {{ $purchaseOrder->supplier->address ?? 'N/A' }}</div>
                    <div class="mt-2">
                        <a href="{{ route('suppliers.show', $purchaseOrder->supplier) }}" class="btn btn-sm btn-outline-primary w-100">
                            View Supplier Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Record PO Payment Modal -->
@if($purchaseOrder->payment_status !== 'paid')
<div class="modal fade" id="recordPOPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('purchase-orders.payment', $purchaseOrder) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Record Payment for {{ $purchaseOrder->po_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Remaining Due ($)</label>
                        <div class="form-control bg-light fw-bold text-danger">
                            ${{ number_format($purchaseOrder->total_amount - $purchaseOrder->paid_amount, 2) }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Payment Amount ($) *</label>
                        <input type="number" step="0.01" name="amount" class="form-control fs-5 fw-bold text-success" value="{{ $purchaseOrder->total_amount - $purchaseOrder->paid_amount }}" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-medium">Payment Date *</label>
                            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-medium">Payment Method *</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cash">Cash</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Reference / Transaction ID</label>
                        <input type="text" name="reference" class="form-control" placeholder="TXN-100293">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
