@extends('layouts.app')
@section('title', $campaign->name)
@section('page-title', 'Loyalty Campaign Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-600"><i class="bi bi-stars me-2 text-primary"></i>{{ $campaign->name }}</span>
                <a href="{{ route('loyalty.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <span class="text-muted small">Points Earned</span>
                        <div class="fw-600">{{ $campaign->points_per_amount }} pts / Rs. {{ $campaign->amount_for_points }}</div>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small">Reward</span>
                        <div class="fw-700 text-primary">
                            @if($campaign->reward_type === 'discount')
                            {{ $campaign->reward_value }}% Discount
                            @elseif($campaign->reward_type === 'free_item')
                            Free Item
                            @else
                            {{ $campaign->reward_value }} Points
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <span class="text-muted small">Validity</span>
                        <div class="fw-600">
                            @if($campaign->valid_from) From {{ $campaign->valid_from->format('M d, Y') }} @endif
                            @if($campaign->valid_until) To {{ $campaign->valid_until->format('M d, Y') }} @endif
                            @if(!$campaign->valid_from && !$campaign->valid_until) Always @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small">Status</span>
                        <div>
                            <span class="badge {{ $campaign->isActive() ? 'bg-success' : 'bg-danger' }}">
                                {{ $campaign->isActive() ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('loyalty.edit', $campaign) }}" class="btn btn-outline-info">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <form method="POST" action="{{ route('loyalty.destroy', $campaign) }}" class="d-inline" onsubmit="return confirm('Delete?')">
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
