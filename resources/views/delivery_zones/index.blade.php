@extends('layouts.app')
@section('title', 'Delivery Zones')
@section('page-title', 'Delivery Zones')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-geo-alt me-2"></i>Delivery Zones</span>
        <a href="{{ route('delivery_zones.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
            <i class="bi bi-plus-circle me-1"></i>New Zone
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Base Fee</th>
                    <th>Min Order</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($zones as $zone)
                <tr>
                    <td>{{ $zone->id }}</td>
                    <td class="fw-600">{{ $zone->name }}</td>
                    <td>${{ number_format($zone->base_fee, 2) }}</td>
                    <td>{{ $zone->min_order > 0 ? '$'.number_format($zone->min_order, 2) : 'None' }}</td>
                    <td>
                        <span class="badge {{ $zone->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $zone->is_active ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('delivery_zones.show', $zone) }}" class="btn btn-sm btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('delivery_zones.edit', $zone) }}" class="btn btn-sm btn-outline-info" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('delivery_zones.destroy', $zone) }}" class="d-inline" onsubmit="return confirm('Delete this zone?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">No delivery zones found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($zones->hasPages())
    <div class="card-footer bg-white border-top-0 pt-0">{{ $zones->links() }}</div>
    @endif
</div>
@endsection
