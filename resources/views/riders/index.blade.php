@extends('layouts.app')
@section('title', 'Riders')
@section('page-title', 'Riders')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-person-rolodex me-2"></i>Riders</span>
        <a href="{{ route('riders.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
            <i class="bi bi-plus-circle me-1"></i>New Rider
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Vehicle</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riders as $rider)
                <tr>
                    <td>{{ $rider->id }}</td>
                    <td class="fw-600">{{ $rider->name }}</td>
                    <td>{{ $rider->phone }}</td>
                    <td>{{ $rider->email ?? '—' }}</td>
                    <td>{{ $rider->vehicle_type ? $rider->vehicle_type . ($rider->vehicle_plate ? ' ('.$rider->vehicle_plate.')' : '') : '—' }}</td>
                    <td>
                        @php $statusColors = ['available' => 'success', 'busy' => 'warning', 'off' => 'secondary'] @endphp
                        <span class="badge bg-{{ $statusColors[$rider->status] }}">{{ ucfirst($rider->status) }}</span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('riders.show', $rider) }}" class="btn btn-sm btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('riders.edit', $rider) }}" class="btn btn-sm btn-outline-info" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('riders.destroy', $rider) }}" class="d-inline" onsubmit="return confirm('Delete this rider?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">No riders found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($riders->hasPages())
    <div class="card-footer bg-white border-top-0 pt-0">{{ $riders->links() }}</div>
    @endif
</div>
@endsection
