@extends('layouts.app')
@section('title', 'Attendance')
@section('page-title', 'Attendance')

@section('content')
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('attendance.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Rows Per Page</label>
                <select name="row" class="form-select">
                    @foreach([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected((int) request('row', 10) === $size)>{{ $size }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>Apply
                </button>
            </div>
            <div class="col-md-3">
                <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-calendar-check me-2"></i>Attendance Dates</span>
        <a href="{{ route('attendance.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
            <i class="bi bi-plus-circle me-1"></i>Take Attendance
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                    @php($date = \Illuminate\Support\Carbon::parse($attendance->date))
                    <tr>
                        <td class="fw-semibold">{{ $date->format('Y-m-d') }}</td>
                        <td>{{ $date->format('l') }}</td>
                        <td>
                            <a href="{{ route('attendance.edit', $date->format('Y-m-d')) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil-square me-1"></i>Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">No attendance records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($attendances->hasPages())
        <div class="card-footer bg-white border-top-0 pt-0">
            {{ $attendances->links() }}
        </div>
    @endif
</div>
@endsection
