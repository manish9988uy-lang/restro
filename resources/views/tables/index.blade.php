@extends('layouts.app')
@section('title', 'Tables')
@section('page-title', 'Restaurant Tables')

@section('content')
<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div></div>
        <a href="{{ route('tables.floor') }}" class="btn btn-primary">
            <i class="bi bi-layout-three-columns me-1"></i>View Floor Designer
        </a>
    </div>
    @foreach(['total'=>['bg-primary','bi-layout-three-columns','All Tables'],'available'=>['bg-success','bi-check-circle','Available'],'occupied'=>['bg-danger','bi-x-circle','Occupied'],'reserved'=>['bg-warning','bi-clock','Reserved']] as $key => $val)
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:var(--bs-{{ str_replace('bg-','',$val[0]) }}-bg,#f0f2f5);">
                    <i class="{{ $val[1] }} text-{{ str_replace('bg-','',$val[0]) }}"></i>
                </div>
                <div>
                    <div class="stat-label">{{ $val[2] }}</div>
                    <div class="stat-value">{{ $stats[$key] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4">
    <!-- Add table form -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-600"><i class="bi bi-plus-circle me-2 text-primary"></i>Add Table</div>
            <div class="card-body">
                <form method="POST" action="{{ route('tables.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-600">Table Number <span class="text-danger">*</span></label>
                        <input type="text" name="table_number" class="form-control" placeholder="e.g. T01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-600">Display Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Table 1 / VIP Room" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-600">Capacity</label>
                            <input type="number" name="capacity" class="form-control" value="4" min="1">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-600">Location</label>
                            <input type="text" name="location" class="form-control" placeholder="Indoor">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus-circle me-1"></i>Add Table
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tables grid -->
    <div class="col-lg-8">
        <div class="row g-3">
            @forelse($tables as $table)
            <div class="col-sm-6 col-md-4">
                <div class="card h-100 border-2 {{ $table->status == 'occupied' ? 'border-danger' : ($table->status == 'reserved' ? 'border-warning' : ($table->status == 'maintenance' ? 'border-secondary' : 'border-success')) }}"
                     style="border-radius:16px;">
                    <div class="card-body text-center p-3">
                        <div class="mb-2" style="font-size:2rem;">
                            @if($table->status == 'available') 🟢
                            @elseif($table->status == 'occupied') 🔴
                            @elseif($table->status == 'reserved') 🟡
                            @else ⚫
                            @endif
                        </div>
                        <div class="fw-700 fs-5">{{ $table->name }}</div>
                        <div class="text-muted small mb-2">
                            <i class="bi bi-people me-1"></i>{{ $table->capacity }} seats
                            @if($table->location)
                            • {{ $table->location }}
                            @endif
                        </div>
                        <span class="badge bg-{{ $table->status_badge }} mb-3">{{ ucfirst($table->status) }}</span>

                        @if($table->active_orders > 0)
                        <div class="text-danger small mb-2">
                            <i class="bi bi-receipt me-1"></i>{{ $table->active_orders }} active order(s)
                        </div>
                        @endif

                        <div class="d-flex gap-1 justify-content-center">
                            <button class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal" data-bs-target="#editTable{{ $table->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    Status
                                </button>
                                <ul class="dropdown-menu shadow-sm border-0" style="border-radius:10px;">
                                    @foreach(['available'=>'success','occupied'=>'danger','reserved'=>'warning','maintenance'=>'secondary'] as $s => $c)
                                    <li>
                                        <form method="POST" action="{{ route('tables.status', $table) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $s }}">
                                            <button type="submit" class="dropdown-item small">
                                                <span class="badge bg-{{ $c }} me-2">{{ ucfirst($s) }}</span>
                                            </button>
                                        </form>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            <form method="POST" action="{{ route('tables.destroy', $table) }}"
                                  onsubmit="return confirm('Remove this table?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editTable{{ $table->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content" style="border-radius:16px;">
                        <div class="modal-header border-0 pb-0">
                            <h6 class="modal-title fw-700">Edit Table</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('tables.update', $table) }}">
                            @csrf @method('PUT')
                            <div class="modal-body">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-600">Table Number</label>
                                        <input type="text" name="table_number" class="form-control" value="{{ $table->table_number }}" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-600">Display Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ $table->name }}" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-600">Capacity</label>
                                        <input type="number" name="capacity" class="form-control" value="{{ $table->capacity }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-600">Location</label>
                                        <input type="text" name="location" class="form-control" value="{{ $table->location }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-600">Status</label>
                                        <select name="status" class="form-select">
                                            @foreach(['available','occupied','reserved','maintenance'] as $s)
                                            <option value="{{ $s }}" @selected($table->status == $s)>{{ ucfirst($s) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-layout-three-columns d-block mb-2" style="font-size:3rem;"></i>
                No tables yet. Add one!
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
