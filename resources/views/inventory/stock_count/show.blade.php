@extends('layouts.app')

@section('title', $stockCount->title . ' — Audit Details')
@section('page-title', 'Stock Audit Details')

@section('content')
<div class="mb-3">
    <a href="{{ route('inventory.stock-count.index') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i> Back to Audits List</a>
</div>

<div class="card mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <div>
            <h5 class="fw-bold mb-0 text-primary">{{ $stockCount->title }}</h5>
            <span class="small text-muted">Audited by {{ $stockCount->user->name ?? 'System' }} on {{ $stockCount->count_date }}</span>
        </div>
        <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i> Reconciled</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Ingredient</th>
                        <th>Unit</th>
                        <th>System Stock</th>
                        <th>Physical Counted</th>
                        <th>Variance (Qty)</th>
                        <th>Unit Cost ($)</th>
                        <th class="pe-4 text-end">Variance Value ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockCount->items as $item)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">{{ $item->ingredient->name ?? 'Deleted Ingredient' }}</td>
                        <td>{{ $item->ingredient->unit ?? '-' }}</td>
                        <td>{{ number_format($item->system_stock, 3) }}</td>
                        <td class="fw-bold">{{ number_format($item->counted_stock, 3) }}</td>
                        <td>
                            @if($item->variance > 0)
                            <span class="badge bg-success-subtle text-success border border-success">+{{ number_format($item->variance, 3) }}</span>
                            @elseif($item->variance < 0)
                            <span class="badge bg-danger-subtle text-danger border border-danger">{{ number_format($item->variance, 3) }}</span>
                            @else
                            <span class="badge bg-light text-dark border">0.00</span>
                            @endif
                        </td>
                        <td>Rs. {{ number_format($item->unit_cost, 2) }}</td>
                        <td class="pe-4 text-end fw-bold {{ $item->variance_cost < 0 ? 'text-danger' : ($item->variance_cost > 0 ? 'text-success' : 'text-muted') }}">
                            Rs. {{ number_format($item->variance_cost, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($stockCount->notes)
    <div class="card-footer bg-light py-3">
        <strong class="text-muted d-block small mb-1">Notes:</strong>
        <p class="mb-0 small">{{ $stockCount->notes }}</p>
    </div>
    @endif
</div>
@endsection
