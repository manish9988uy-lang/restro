@extends('layouts.app')
@section('title', 'Edit Attendance')
@section('page-title', 'Edit Attendance')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Attendance</span>
                <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('attendance.store') }}">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ $date }}" readonly>
                        </div>
                    </div>

                    @if($attendances->isEmpty())
                        <div class="alert alert-warning mb-0">
                            No attendance records were found for this date.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th class="text-center">Present</th>
                                        <th class="text-center">Absent</th>
                                        <th class="text-center">Late</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendances as $attendance)
                                        <tr>
                                            <td class="fw-semibold">
                                                {{ $attendance->employee?->name ?? 'Unknown Employee' }}
                                                <input type="hidden" name="employee_id[{{ $attendance->employee_id }}]" value="{{ $attendance->employee_id }}">
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="status{{ $attendance->employee_id }}" value="present" @checked($attendance->status === 'present')>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="status{{ $attendance->employee_id }}" value="absent" @checked($attendance->status === 'absent')>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="status{{ $attendance->employee_id }}" value="late" @checked($attendance->status === 'late')>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-1"></i>Update Attendance
                            </button>
                            <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
