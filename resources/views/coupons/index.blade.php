@extends('layouts.app')
@section('title', 'Coupons')
@section('page-title', 'Coupons')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-ticket-perforated me-2"></i>Coupon List</span>
        <a href="{{ route('coupons.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
            <i class="bi bi-plus-circle me-1"></i>New Coupon
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Min Order</th>
                    <th>Usage</th>
                    <th>Valid</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td class="fw-bold text-primary">{{ $coupon->code }}</td>
                    <td>{{ ucfirst($coupon->type) }}</td>
                    <td>
                        @if($coupon->type === 'percentage')
                        {{ $coupon->value }}%
                        @else
                        ${{ number_format($coupon->value, 2) }}
                        @endif
                    </td>
                    <td>{{ $coupon->min_order > 0 ? '$'.number_format($coupon->min_order, 2) : 'None' }}</td>
                    <td>
                        {{ $coupon->used_count }}
                        @if($coupon->usage_limit)
                        / {{ $coupon->usage_limit }}
                        @endif
                    </td>
                    <td>
                        @if($coupon->valid_from)
                        From {{ $coupon->valid_from->format('M d') }}
                        @endif
                        @if($coupon->valid_until)
                        to {{ $coupon->valid_until->format('M d, Y') }}
                        @endif
                        @if(!$coupon->valid_from && !$coupon->valid_until)
                        Always
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $coupon->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('coupons.show', $coupon) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('coupons.destroy', $coupon) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">No coupons yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($coupons->hasPages())
    <div class="card-footer bg-white border-top-0 pt-0">
        {{ $coupons->links() }}
    </div>
    @endif
</div>
@endsection
