@extends('layouts.app')
@section('title', 'New Rider')
@section('page-title', 'New Rider')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-plus-circle me-2 text-primary"></i>New Rider</span>
                <a href="{{ route('riders.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('riders.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-600">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-600">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-600">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-600">Status</label>
                            <select name="status" class="form-select">
                                @foreach(['available', 'busy', 'off'] as $status)
                                    <option value="{{ $status }}" @selected(old('status') == $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-600">Vehicle Type</label>
                            <input type="text" name="vehicle_type" class="form-control" value="{{ old('vehicle_type') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-600">Vehicle Plate</label>
                            <input type="text" name="vehicle_plate" class="form-control" value="{{ old('vehicle_plate') }}">
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-circle me-1"></i>Create Rider
                        </button>
                        <a href="{{ route('riders.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
