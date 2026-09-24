@extends('layouts.app')
@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')
<div class="row g-4">
    <!-- Add customer -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-600"><i class="bi bi-person-plus me-2 text-primary"></i>Add Customer</div>
            <div class="card-body">
                <form method="POST" action="{{ route('customers.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-600">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-600">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+1 234 567 8900">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-600">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-600">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-600">Birthday</label>
                        <input type="date" name="birthday" class="form-control" value="{{ old('birthday') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-600">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Allergies, preferences...">{{ old('notes') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-person-plus me-1"></i>Add Customer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Customers list -->
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body py-2">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, phone, email..."
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-search"></i></button>
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-2"></i>All Customers ({{ $customers->total() }})</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Orders</th>
                            <th>Total Spent</th>
                            <th>Membership</th>
                            <th>Points</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td class="small text-muted">{{ $customers->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:36px;height:36px;background:linear-gradient(135deg,#FF6B35,#ff9f7c);
                                         border-radius:50%;display:flex;align-items:center;justify-content:center;
                                         color:#fff;font-weight:700;font-size:.85rem;flex-shrink:0;">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('customers.show', $customer) }}"
                                           class="fw-600 small text-decoration-none d-block">{{ $customer->name }}</a>
                                        <div class="text-muted" style="font-size:.72rem;">{{ $customer->email ?? '—' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="small">{{ $customer->phone ?? '—' }}</td>
                            <td class="small text-center">{{ $customer->orders_count }}</td>
                            <td class="fw-600 small text-success">Rs. {{ number_format($customer->total_spent, 2) }}</td>
                            <td>
                                @php
                                    $tierColors = [
                                        'Bronze' => 'bg-secondary',
                                        'Silver' => 'bg-secondary text-dark',
                                        'Gold' => 'bg-warning text-dark',
                                        'Platinum' => 'bg-info text-dark'
                                    ];
                                @endphp
                                <span class="badge {{ $tierColors[$customer->membership_tier] ?? 'bg-secondary' }}">{{ $customer->membership_tier }}</span>
                            </td>
                            <td class="fw-600 small text-primary">{{ $customer->loyalty_points }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal" data-bs-target="#editCustomer{{ $customer->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" action="{{ route('customers.destroy', $customer) }}"
                                          onsubmit="return confirm('Delete customer?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editCustomer{{ $customer->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content" style="border-radius:16px;">
                                    <div class="modal-header border-0 pb-0">
                                        <h6 class="modal-title fw-700">Edit Customer</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('customers.update', $customer) }}">
                                        @csrf @method('PUT')
                                        <div class="modal-body row g-2">
                                            <div class="col-12">
                                                <label class="form-label small fw-600">Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-600">Phone</label>
                                                <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-600">Email</label>
                                                <input type="email" name="email" class="form-control" value="{{ $customer->email }}">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-600">Birthday</label>
                                                <input type="date" name="birthday" class="form-control" value="{{ $customer->birthday?->format('Y-m-d') }}">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-600">Loyalty Points</label>
                                                <input type="number" name="loyalty_points" class="form-control" value="{{ $customer->loyalty_points }}" min="0">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-600">Membership Tier</label>
                                                <select name="membership_tier" class="form-select">
                                                    @foreach(['Bronze', 'Silver', 'Gold', 'Platinum'] as $tier)
                                                    <option value="{{ $tier }}" {{ $customer->membership_tier === $tier ? 'selected' : '' }}>{{ $tier }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-600">Address</label>
                                                <textarea name="address" class="form-control" rows="2">{{ $customer->address }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-people d-block mb-2" style="font-size:2rem;"></i>
                                No customers yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($customers->hasPages())
            <div class="card-footer bg-white">{{ $customers->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
