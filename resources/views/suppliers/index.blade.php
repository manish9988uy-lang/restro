@extends('layouts.app')

@section('title', 'Suppliers — Management')
@section('page-title', 'Supplier Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-truck text-primary me-2"></i>Suppliers</h4>
        <p class="text-muted small mb-0">Manage raw material vendors & credit accounts</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
        <i class="bi bi-plus-lg me-1"></i> Add Supplier
    </button>
</div>

<!-- Stats cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-building"></i></div>
                <div>
                    <div class="stat-label">Total Suppliers</div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle"></i></div>
                <div>
                    <div class="stat-label">Active Vendors</div>
                    <div class="stat-value">{{ $stats['active'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-cash-stack"></i></div>
                <div>
                    <div class="stat-label">Total Credit / Payable</div>
                    <div class="stat-value">Rs. {{ number_format($stats['total_due'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter / Search -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('suppliers.index') }}" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search supplier by name, company, email, phone..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-secondary w-100">Filter</button>
            </div>
            @if(request('search'))
            <div class="col-md-2">
                <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-outline-secondary w-100">Clear</a>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Supplier Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Supplier Name</th>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Purchase Orders</th>
                        <th>Credit Balance</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                    <tr>
                        <td class="ps-4 fw-semibold text-dark">
                            <a href="{{ route('suppliers.show', $supplier) }}" class="text-decoration-none text-dark fw-bold">
                                {{ $supplier->name }}
                            </a>
                        </td>
                        <td>{{ $supplier->company_name ?? '-' }}</td>
                        <td>
                            <div><i class="bi bi-telephone text-muted me-1"></i>{{ $supplier->phone ?? 'N/A' }}</div>
                            <div class="small text-muted"><i class="bi bi-envelope me-1"></i>{{ $supplier->email ?? 'N/A' }}</div>
                        </td>
                        <td class="small">{{ $supplier->address ?? '-' }}</td>
                        <td><span class="badge bg-secondary rounded-pill">{{ $supplier->purchase_orders_count }} POs</span></td>
                        <td class="fw-bold {{ $supplier->credit_balance > 0 ? 'text-danger' : 'text-success' }}">
                            Rs. {{ number_format($supplier->credit_balance, 2) }}
                        </td>
                        <td>
                            @if($supplier->is_active)
                            <span class="badge bg-success-subtle text-success border border-success">Active</span>
                            @else
                            <span class="badge bg-secondary-subtle text-secondary border">Inactive</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-eye"></i></a>
                            <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editSupplierModal{{ $supplier->id }}"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this supplier?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editSupplierModal{{ $supplier->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Supplier — {{ $supplier->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Supplier Name *</label>
                                            <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Company Name</label>
                                            <input type="text" name="company_name" class="form-control" value="{{ $supplier->company_name }}">
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label fw-medium">Phone</label>
                                                <input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label fw-medium">Email</label>
                                                <input type="email" name="email" class="form-control" value="{{ $supplier->email }}">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Address</label>
                                            <textarea name="address" class="form-control" rows="2">{{ $supplier->address }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Tax Number / VAT ID</label>
                                            <input type="text" name="tax_number" class="form-control" value="{{ $supplier->tax_number }}">
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $supplier->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label">Active Supplier</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No suppliers found. Click 'Add Supplier' to create one.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($suppliers->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $suppliers->links() }}
    </div>
    @endif
</div>

<!-- Add Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('suppliers.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Supplier Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Fresh Produce Co." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Company Name</label>
                        <input type="text" name="company_name" class="form-control" placeholder="Company Ltd.">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-medium">Phone</label>
                            <input type="text" name="phone" class="form-control" placeholder="+123456789">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-medium">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="supplier@example.com">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Address</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Full address"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Tax Number / VAT ID</label>
                        <input type="text" name="tax_number" class="form-control" placeholder="TAX-998877">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
