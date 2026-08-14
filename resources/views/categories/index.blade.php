@extends('layouts.app')
@section('title', 'Categories')
@section('page-title', 'Menu Categories')

@section('content')
<div class="row g-4">
    <!-- Add category form -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-600"><i class="bi bi-plus-circle me-2 text-primary"></i>Add Category</div>
            <div class="card-body">
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-600">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="e.g. Starters" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-7">
                            <label class="form-label small fw-600">Bootstrap Icon</label>
                            <input type="text" name="icon" class="form-control"
                                   value="{{ old('icon', 'bi-grid') }}" placeholder="bi-grid">
                            <div class="form-text">From <a href="https://icons.getbootstrap.com" target="_blank">Bootstrap Icons</a></div>
                        </div>
                        <div class="col-5">
                            <label class="form-label small fw-600">Color</label>
                            <input type="color" name="color" class="form-control form-control-color w-100"
                                   value="{{ old('color', '#FF6B35') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-600">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                        <label class="form-check-label small" for="is_active">Active</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus-circle me-1"></i>Add Category
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Categories table -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-tags me-2"></i>All Categories</span>
                <span class="badge bg-primary rounded-pill">{{ $categories->total() }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Icon</th>
                            <th>Color</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                        <tr>
                            <td class="text-muted small">{{ $loop->iteration }}</td>
                            <td class="fw-600">{{ $cat->name }}</td>
                            <td><i class="{{ $cat->icon }}" style="font-size:1.25rem;color:{{ $cat->color }};"></i></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:20px;height:20px;border-radius:5px;background:{{ $cat->color }};"></div>
                                    <code class="small">{{ $cat->color }}</code>
                                </div>
                            </td>
                            <td><span class="badge bg-primary-subtle text-primary">{{ $cat->menu_items_count }}</span></td>
                            <td>
                                @if($cat->is_active)
                                <span class="badge bg-success-subtle text-success">Active</span>
                                @else
                                <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $cat->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('categories.destroy', $cat) }}"
                                      class="d-inline" onsubmit="return confirm('Delete this category?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $cat->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content" style="border-radius:16px;">
                                    <div class="modal-header border-0 pb-0">
                                        <h6 class="modal-title fw-700">Edit Category</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('categories.update', $cat) }}">
                                        @csrf @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label small fw-600">Name</label>
                                                <input type="text" name="name" class="form-control"
                                                       value="{{ $cat->name }}" required>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col-7">
                                                    <label class="form-label small fw-600">Icon</label>
                                                    <input type="text" name="icon" class="form-control"
                                                           value="{{ $cat->icon }}">
                                                </div>
                                                <div class="col-5">
                                                    <label class="form-label small fw-600">Color</label>
                                                    <input type="color" name="color"
                                                           class="form-control form-control-color w-100"
                                                           value="{{ $cat->color }}">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-600">Sort Order</label>
                                                <input type="number" name="sort_order" class="form-control"
                                                       value="{{ $cat->sort_order }}">
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_active"
                                                       @checked($cat->is_active)>
                                                <label class="form-check-label small">Active</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-tags d-block mb-2" style="font-size:2rem;"></i>
                                No categories yet. Add one!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($categories->hasPages())
            <div class="card-footer bg-white">{{ $categories->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
