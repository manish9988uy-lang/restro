@extends('layouts.app')
@section('title', 'Generate QR Codes')
@section('page-title', 'QR Code Generator')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-qr-code me-2"></i>Table QR Codes</span>
        <button class="btn btn-sm btn-primary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i>Print All
        </button>
    </div>
    <div class="card-body row">
        @forelse($tables as $table)
        <div class="col-md-3 col-sm-6 mb-4 text-center">
            <div class="border rounded p-3 bg-light shadow-sm">
                <h5 class="mb-3 text-dark">{{ $table->name }}</h5>
                <div class="bg-white p-2 d-inline-block border">
                    <!-- Placeholder for actual QR code, using an image service for now -->
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('qr.menu', ['table_id' => $table->id])) }}" alt="QR Code" class="img-fluid">
                </div>
                <div class="mt-3">
                    <a href="{{ route('qr.menu', ['table_id' => $table->id]) }}" target="_blank" class="btn btn-sm btn-outline-secondary w-100">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Preview Menu
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted py-5">
            <p>No tables found. Please add tables first.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
