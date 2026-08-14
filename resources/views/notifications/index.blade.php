@extends('layouts.app')
@section('title', 'Notification Campaigns')
@section('page-title', 'Notification Campaigns')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-megaphone me-2"></i>Campaigns</span>
        <a href="{{ route('notifications.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
            <i class="bi bi-plus-circle me-1"></i>New Campaign
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Recipients</th>
                    <th>Scheduled</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $campaign)
                <tr>
                    <td class="fw-600">{{ $campaign->name }}</td>
                    <td>
                        <span class="badge bg-info">{{ strtoupper($campaign->type) }}</span>
                    </td>
                    <td>{{ count($campaign->recipients ?? []) }}</td>
                    <td>{{ $campaign->scheduled_at ? $campaign->scheduled_at->format('M d Y H:i') : 'Not scheduled' }}</td>
                    <td>
                        @php $statusColors = ['draft'=>'secondary','scheduled'=>'warning','sending'=>'info','sent'=>'success','failed'=>'danger'] @endphp
                        <span class="badge bg-{{ $statusColors[$campaign->status] }}">{{ ucfirst($campaign->status) }}</span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('notifications.show', $campaign) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($campaign->status === 'draft' || $campaign->status === 'scheduled')
                            <a href="{{ route('notifications.edit', $campaign) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endif
                            <form method="POST" action="{{ route('notifications.destroy', $campaign) }}" class="d-inline" onsubmit="return confirm('Delete?')">
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
