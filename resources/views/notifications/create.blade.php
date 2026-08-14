@extends('layouts.app')
@section('title', 'New Notification Campaign')
@section('page-title', 'New Notification Campaign')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-plus-circle me-2 text-primary"></i>Create Campaign</span>
                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('notifications.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">Select</option>
                                <option value="sms" {{ old('type') === 'sms' ? 'selected' : '' }}>SMS</option>
                                <option value="email" {{ old('type') === 'email' ? 'selected' : '' }}>Email</option>
                                <option value="whatsapp" {{ old('type') === 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Scheduled At</label>
                            <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at') }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Recipients</label>
                            <div class="form-check">
                                <input type="checkbox" id="select_all" class="form-check-input">
                                <label class="form-check-label" for="select_all">Select All</label>
                            </div>
                            <div class="row g-2 mt-2">
                                @foreach($customers as $customer)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="customer_ids[]" value="{{ $customer->id }}" class="form-check-input" id="customer_{{ $customer->id }}" {{ in_array($customer->id, old('customer_ids', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="customer_{{ $customer->id }}">
                                            {{ $customer->name }} ({{ $customer->phone }})
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12 d-flex gap-3 mt-2">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-check-circle me-1"></i>Create
                            </button>
                            <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('select_all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="customer_ids[]"]');
    checkboxes.forEach(cb => cb.checked = this.checked);
});
</script>
@endpush
@endsection
