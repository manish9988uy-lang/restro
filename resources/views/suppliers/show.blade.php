@extends('layouts.app')

@section('title', $supplier->name . ' — Supplier Details')
@section('page-title', 'Supplier Details')

@section('content')
<div class="mb-3">
    <a href="{{ route('suppliers.index') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i> Back to Suppliers</a>
</div>

<div class="row g-4">
    <!-- Left Column: Supplier Profile & Payment Record -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="avatar bg-primary text-white fs-4 rounded-3" style="width:50px;height:50px;">
                        {{ strtoupper(substr($supplier->name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $supplier->name }}</h5>
                        <p class="text-muted small mb-0">{{ $supplier->company_name ?? 'Individual Supplier' }}</p>
                    </div>
                </div>
                <hr>
                <div class="vstack gap-2 small">
                    <div><strong class="text-muted">Phone:</strong> {{ $supplier->phone ?? 'N/A' }}</div>
                    <div><strong class="text-muted">Email:</strong> {{ $supplier->email ?? 'N/A' }}</div>
                    <div><strong class="text-muted">Address:</strong> {{ $supplier->address ?? 'N/A' }}</div>
                    <div><strong class="text-muted">Tax ID:</strong> {{ $supplier->tax_number ?? 'N/A' }}</div>
                    <div>
                        <strong class="text-muted">Current Credit / Due:</strong> 
                        <span class="fs-6 fw-bold text-danger ms-1">Rs. {{ number_format($supplier->credit_balance, 2) }}</span>
                    </div>
                </div>
                <button class="btn btn-outline-danger w-100 rounded-pill mt-3" data-bs-toggle="modal" data-bs-target="#recordPaymentModal">
                    <i class="bi bi-cash-stack me-1"></i> Record Supplier Payment
                </button>
            </div>
        </div>
    </div>

    <!-- Right Column: Purchase Orders & Payment History -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-file-earmark-text text-primary me-2"></i>Purchase Orders</h6>
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-sm btn-primary rounded-pill"><i class="bi bi-plus-lg"></i> New PO</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">PO #</th>
                                <th>Order Date</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th class="pe-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($supplier->purchaseOrders as $po)
                            <tr>
                                <td class="ps-3 fw-bold"><a href="{{ route('purchase-orders.show', $po) }}">{{ $po->po_number }}</a></td>
                                <td>{{ $po->order_date }}</td>
                                <td><span class="badge bg-info text-dark">{{ ucfirst($po->status) }}</span></td>
                                <td><span class="badge bg-secondary">{{ ucfirst($po->payment_status) }}</span></td>
                                <td class="fw-bold">Rs. {{ number_format($po->total_amount, 2) }}</td>
                                <td class="pe-3 text-end">
                                    <a href="{{ route('purchase-orders.show', $po) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-3 text-muted">No purchase orders found for this supplier.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payments History -->
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="fw-bold mb-0"><i class="bi bi-receipt text-success me-2"></i>Payment History</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Date</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th>Amount Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($supplier->payments as $payment)
                            <tr>
                                <td class="ps-3">{{ $payment->payment_date }}</td>
                                <td><span class="badge bg-light text-dark border">{{ ucfirst($payment->payment_method) }}</span></td>
                                <td>{{ $payment->reference ?? '-' }}</td>
                                <td class="fw-bold text-success">Rs. {{ number_format($payment->amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-3 text-muted">No payments recorded yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Record Payment Modal -->
<div class="modal fade" id="recordPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('suppliers.payment', $supplier) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Record Payment to {{ $supplier->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Payment Amount (Rs.) *</label>
                        <input type="number" step="0.01" name="amount" class="form-control fs-5 fw-bold text-success" value="{{ $supplier->credit_balance }}" required>
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
                                <option value="card">Card</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Reference / Transaction ID</label>
                        <input type="text" name="reference" class="form-control" placeholder="e.g. TXN-100293">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
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
@endsection
