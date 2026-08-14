@extends('layouts.app')
@section('title', 'Waitlist')
@section('page-title', 'Waitlist')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-600"><i class="bi bi-person-plus me-2"></i>Add to Waitlist</div>
            <div class="card-body">
                <form method="POST" action="{{ route('waitlist.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-600">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-600">Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-600">Party Size</label>
                        <input type="number" name="party_size" class="form-control" value="2" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-600">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add to Waitlist</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header fw-600"><i class="bi bi-list-ol me-2"></i>Current Waitlist</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Party Size</th>
                                <th>Status</th>
                                <th>Wait Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $pos=0 @endphp
                            @forelse($waitlists as $waitlist)
                                @if($waitlist->status == 'waiting') @php $pos++ @endphp @endif
                                <tr>
                                    <td class="fw-600">{{ $waitlist->status == 'waiting' ? $pos : '—' }}</td>
                                    <td>{{ $waitlist->name }}</td>
                                    <td>{{ $waitlist->phone ?? '—' }}</td>
                                    <td>{{ $waitlist->party_size }}</td>
                                    <td>
                                        <span class="badge {{ $waitlist->status == 'waiting' ? 'bg-warning' : ($waitlist->status == 'seated' ? 'bg-success' : 'bg-secondary') }}">
                                            {{ ucfirst($waitlist->status) }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">{{ $waitlist->created_at->diffForHumans() }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @if($waitlist->status == 'waiting')
                                                <form method="POST" action="{{ route('waitlist.update', $waitlist) }}">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="seated">
                                                    <button type="submit" class="btn btn-sm btn-outline-success">Seat</button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('waitlist.destroy', $waitlist) }}"
                                                  onsubmit="return confirm('Remove this entry?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center py-4 text-muted">No one is waiting yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection