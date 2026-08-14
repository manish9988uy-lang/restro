@extends('layouts.app')
@section('title', 'Loyalty Campaigns')
@section('page-title', 'Loyalty Campaigns')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-stars me-2"></i>Loyalty Campaigns</span>
        <a href="{{ route('loyalty.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
            <i class="bi bi-plus-circle me-1"></i>New Campaign
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Points per ${{ $campaign->amount_for_points ?? 1 }} spent</th>
                    <th>Reward</th>
                    <th>Validity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $campaign)
                <tr>
                    <td class="fw-600">{{ $campaign->name }}</td>
                    <td>{{ $campaign->points_per_amount }} pts / ${{ $campaign->amount_for_points }}</td>
                    <td>
                        @if($campaign->reward_type === 'discount')
                        {{ $campaign->reward_value }}% Discount
                        @elseif($campaign->reward_type === 'free_item')
                        Free Item
                        @else
                        {{ $campaign->reward_value }} Points
                        @endif
                    </td>
                    <td>
                        @if($campaign->valid_from) From {{ $campaign->valid_from->format('M d') }} @endif
                        @if($campaign->valid_until) To {{ $campaign->valid_until->format('M d Y') }} @endif
                        @if(!$campaign->valid_from && !$campaign->valid_until) Always @endif
                    </td>
                    <td>
                        <span class="badge {{ $campaign->isActive() ? 'bg-success' : 'bg-danger' }}">
                            {{ $campaign->isActive() ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('loyalty.show', $campaign) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('loyalty.edit', $campaign) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('loyalty.destroy', $campaign) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No campaigns yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($campaigns->hasPages())
    <div class="card-footer bg-white border-top-0 pt-0">
        {{ $campaigns->links() }}
    </div>
    @endif
</div>
@endsection
