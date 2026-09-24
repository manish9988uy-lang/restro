@extends('layouts.app')
@section('title', 'Add Menu Item')
@section('page-title', 'Add Menu Item')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-plus-circle me-2 text-primary"></i>New Menu Item</span>
                <a href="{{ route('menu.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('menu.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-600">Item Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="e.g. Margherita Pizza" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-600">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-600">Description</label>
                            <textarea name="description" class="form-control" rows="2"
                                      placeholder="Brief description of the item...">{{ old('description') }}</textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-600">Selling Price (Rs.) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="price" step="0.01" min="0"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price') }}" placeholder="0.00" required>
                            </div>
                            @error('price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-600">Cost Price (Rs.)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="cost_price" step="0.01" min="0"
                                       class="form-control" value="{{ old('cost_price', 0) }}" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-600">Preparation Time</label>
                            <input type="text" name="preparation_time" class="form-control"
                                   value="{{ old('preparation_time') }}" placeholder="e.g. 15 min">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-600">Stock Quantity</label>
                            <input type="number" name="stock_quantity" class="form-control"
                                   value="{{ old('stock_quantity', -1) }}" placeholder="-1 for unlimited">
                            <div class="form-text">Use -1 for unlimited stock</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-600">Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*"
                                   onchange="previewImage(this)">
                        </div>

                        <!-- Preview -->
                        <div class="col-md-6" id="imagePreviewContainer" style="display:none;">
                            <img id="imagePreview" src="#" alt="Preview"
                                 style="max-height:150px;border-radius:12px;object-fit:cover;">
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex gap-4 mt-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_available"
                                           id="is_available" @checked(old('is_available', true))>
                                    <label class="form-check-label small fw-600" for="is_available">Available</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_featured"
                                           id="is_featured" @checked(old('is_featured'))>
                                    <label class="form-check-label small fw-600" for="is_featured">Featured</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-circle me-1"></i>Add Item
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
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreviewContainer').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
