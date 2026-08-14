@extends('layouts.app')
@section('title', 'Menu Items')
@section('page-title', 'Menu Items')

@section('content')
<!-- Filters -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search menu items..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="1" @selected(request('status') === '1')>Available</option>
                    <option value="0" @selected(request('status') === '0')>Unavailable</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="{{ route('menu.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-journal-text me-2"></i>Menu Items ({{ $menuItems->total() }})</span>
        <a href="{{ route('menu.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
            <i class="bi bi-plus-circle me-1"></i>Add Item
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Cost</th>
                    <th>Prep Time</th>
                    <th>Stock</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menuItems as $item)
                <tr>
                    <td>
                        @if($item->image)
                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                             style="width:44px;height:44px;object-fit:cover;border-radius:10px;">
                        @else
                        <div style="width:44px;height:44px;background:#f0f2f5;border-radius:10px;
                             display:flex;align-items:center;justify-content:center;font-size:1.25rem;">
                            🍽️
                        </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-600 small">{{ $item->name }}</div>
                        @if($item->description)
                        <div class="text-muted" style="font-size:.72rem;max-width:180px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
                            {{ $item->description }}
                        </div>
                        @endif
                    </td>
                    <td>
                        <span class="badge" style="background:{{ $item->category->color }}20;color:{{ $item->category->color }};">
                            <i class="{{ $item->category->icon }} me-1"></i>{{ $item->category->name }}
                        </span>
                    </td>
                    <td class="fw-600 text-success small">${{ number_format($item->price, 2) }}</td>
                    <td class="text-muted small">${{ number_format($item->cost_price, 2) }}</td>
                    <td class="small">{{ $item->preparation_time ?? '—' }}</td>
                    <td class="small">
                        @if($item->stock_quantity == -1)
                        <span class="text-muted">∞</span>
                        @elseif($item->stock_quantity == 0)
                        <span class="badge bg-danger-subtle text-danger">Out</span>
                        @else
                        {{ $item->stock_quantity }}
                        @endif
                    </td>
                    <td>
                        @if($item->is_featured)
                        <i class="bi bi-star-fill text-warning"></i>
                        @else
                        <i class="bi bi-star text-muted"></i>
                        @endif
                    </td>
                    <td>
                        @if($item->is_available)
                        <span class="badge bg-success-subtle text-success">Available</span>
                        @else
                        <span class="badge bg-danger-subtle text-danger">Unavailable</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('menu.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('menu.destroy', $item) }}"
                                  onsubmit="return confirm('Delete this item?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-5 text-muted">
                        <i class="bi bi-journal-text d-block mb-2" style="font-size:2rem;"></i>
                        No menu items found.
                        <a href="{{ route('menu.create') }}" class="d-block mt-2">Add your first item</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($menuItems->hasPages())
    <div class="card-footer bg-white">{{ $menuItems->links() }}</div>
    @endif
</div>
@endsection
