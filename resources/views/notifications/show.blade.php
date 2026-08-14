@extends('layouts.app')
@section('title', $campaign->name)
@section('page-title', 'Notification Campaign Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-megaphone me-2 text-primary"></i>{{ $campaign->name }}</span>
                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <span class="text-muted small">Type</span>
                        <div class="fw-600">{{ strtoupper($campaign->type) }}</div>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small">Status</span>
                        <div>
                            @php $statusColors = ['draft'=>'secondary','scheduled'=>'warning','sending'=>'info','sent'=>'success','failed'=>'danger'] @endphp
                            <span class="badge bg-{{ $statusColors[$campaign->status] }}">{{ ucfirst($campaign->status) }}</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <span class="text-muted small">Scheduled At</span>
                        <div class="fw-600">{{ $campaign->scheduled_at ? $campaign->scheduled_at->format('M d, Y H:i') : 'Not scheduled' }}</div>
                    </div>
                    @if($campaign->sent_at)
                    <div class="col-md-6">
                        <span class="text-muted small">Sent At</span>
                        <div class="fw-600">{{ $campaign->sent_at->format('M d, Y H:i') }}</div>
                    </div>
                    @endif
                </div>
                <div class="mb-4">
                    <span class="text-muted small">Message</span>
                    <div class="p-3 bg-light rounded">{{ $campaign->message }}</div>
                </div>
                <div class="mb-4">
                    <span class="text-muted small">Recipients ({{ count($campaign->recipients ?? []) }})</span>
                    <div class="mt-2">
                        @if($campaign->recipients && count($campaign->recipients) > 0)
                        @foreach($campaign->recipients as $recipientId)
                            @php
                                $customer = $customers->find($recipientId);
                            @endphp
                            @if($customer)
                            <span class="badge bg-light text-dark me-1 mb-1">{{ $customer->name }}</span>
                            @endif
                        @endforeach
                        @else
                        <span class="text-muted">No recipients selected</span>
                        @endif
                    </div>
                </div>
                <div class="d-flex gap-2">
                    @if($campaign->status === 'draft' || $campaign->status === 'scheduled')
                    <a href="{{ route('notifications.edit', $campaign) }}" class="btn btn-outline-info">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    @endif
                    <form method="POST" action="{{ route('notifications.destroy', $campaign) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
