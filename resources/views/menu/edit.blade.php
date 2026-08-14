@extends('layouts.app')
@section('title', 'Edit '.$menu->name)
@section('page-title', 'Edit Menu Item')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-pencil me-2 text-primary"></i>Edit: {{ $menu->name }}</span>
                <a href="{{ route('menu.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('menu.update', $menu) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-600">Item Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $menu->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-600">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id', $menu->category_id) == $cat->id)>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-600">Description</label>
                            <textarea name="description" class="form-control" rows="2">{{ old('description', $menu->description) }}</textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-600">Selling Price ($) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="price" step="0.01" min="0" class="form-control"
                                       value="{{ old('price', $menu->price) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-600">Cost Price ($)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="cost_price" step="0.01" min="0" class="form-control"
                                       value="{{ old('cost_price', $menu->cost_price) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-600">Preparation Time</label>
                            <input type="text" name="preparation_time" class="form-control"
                                   value="{{ old('preparation_time', $menu->preparation_time) }}" placeholder="e.g. 15 min">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-600">Stock Quantity</label>
                            <input type="number" name="stock_quantity" class="form-control"
                                   value="{{ old('stock_quantity', $menu->stock_quantity) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-600">New Image (optional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*"
                                   onchange="previewImage(this)">
                        </div>

                        @if($menu->image)
                        <div class="col-md-4">
                            <label class="form-label small fw-600">Current Image</label><br>
                            <img src="{{ Storage::url($menu->image) }}" alt="{{ $menu->name }}"
                                 style="height:100px;border-radius:12px;object-fit:cover;" id="imagePreview">
                        </div>
                        @else
                        <div class="col-md-4" id="imagePreviewContainer" style="display:none;">
                            <img id="imagePreview" src="#" alt="Preview"
                                 style="max-height:100px;border-radius:12px;object-fit:cover;">
                        </div>
                        @endif

                        <div class="col-md-6">
                            <div class="d-flex gap-4 mt-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_available"
                                           id="is_available" @checked(old('is_available', $menu->is_available))>
                                    <label class="form-check-label small fw-600" for="is_available">Available</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_featured"
                                           id="is_featured" @checked(old('is_featured', $menu->is_featured))>
                                    <label class="form-check-label small fw-600" for="is_featured">Featured</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-circle me-1"></i>Update Item
                        </button>
                        <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('imagePreview');
            img.src = e.target.result;
            document.getElementById('imagePreviewContainer')?.style && (document.getElementById('imagePreviewContainer').style.display = 'block');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
