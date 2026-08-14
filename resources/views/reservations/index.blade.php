@extends('layouts.app')
@section('title', 'Reservations')
@section('page-title', 'Reservations')

@section('content')
<div class="row g-4">
    <!-- Add Reservation Form -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-600">
                <i class="bi bi-calendar-plus me-2 text-primary"></i>New Reservation
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('reservations.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-600">Table</label>
                        <select name="table_id" class="form-select">
                            @foreach($tables as $table)
                            <option value="{{ $table->id }}">{{ $table->name }} ({{ $table->capacity }} seats)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-600">Customer</label>
                        <select name="customer_id" class="form-select">
                            <option value="">Walk-in</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-600">Date & Time</label>
                        <input type="datetime-local" name="reservation_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-600">Guest Count</label>
                        <input type="number" name="guest_count" class="form-control" value="2" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-600">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['pending', 'confirmed', 'cancelled', 'completed'] as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-600">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle me-1"></i>Create Reservation
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- Reservations List & Calendar -->
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-check me-2"></i>Upcoming Reservations</span>
                <div class="btn-group" role="group">
                    <a href="{{ route('reservations.index') }}" class="btn btn-outline-primary btn-sm active">List</a>
                    <a href="{{ route('reservations.index', ['view' => 'calendar']) }}" class="btn btn-outline-primary btn-sm">Calendar</a>
                </div>
            </div>
            <div class="card-body">
                @if(request('view') == 'calendar')
                    <!-- Simple Calendar View -->
                    @php
                        $currentDate = request('date') ? \Illuminate\Support\Carbon::parse(request('date')) : now();
                        $startOfMonth = $currentDate->copy()->startOfMonth();
                        $endOfMonth = $currentDate->copy()->endOfMonth();
                        $startOfCalendar = $startOfMonth->copy()->startOfWeek();
                        $endOfCalendar = $endOfMonth->copy()->endOfWeek();
                    @endphp
                    <div class="d-flex justify-content-between mb-3 align-items-center">
                        <a href="{{ route('reservations.index', ['view' => 'calendar', 'date' => $currentDate->copy()->subMonth()->toDateString()]) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                        <h5>{{ $currentDate->format('F Y') }}</h5>
                        <a href="{{ route('reservations.index', ['view' => 'calendar', 'date' => $currentDate->copy()->addMonth()->toDateString()]) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                                        <th class="text-muted">{{ $day }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @for($date = $startOfCalendar; $date->lte($endOfCalendar); $date->addDay())
                                    @if($date->dayOfWeek == 0) <tr> @endif
                                        <td style="{{
                                            $date->isCurrentMonth() ? '' : 'opacity:0.3; background-color: #f3f4f6;'
                                        }}">
                                            <div class="fw-bold {{ $date->isToday() ? 'text-primary' : '' }}">{{ $date->day }}</div>
                                            @foreach($reservations->where('reservation_time', '>=', $date->toDateString())->where('reservation_time', '<', $date->copy()->addDay()->toDateString()) as $res)
                                                <div class="small mt-1 p-1 rounded {{
                                                    $res->status == 'confirmed' ? 'bg-primary text-white' :
                                                    ($res->status == 'pending' ? 'bg-warning text-dark' :
                                                    ($res->status == 'completed' ? 'bg-success text-white' : 'bg-secondary text-white'))
                                                }}">
                                                    {{ $res->reservation_time->format('H:i') }} - {{ $res->table?->name }}
                                                </div>
                                            @endforeach
                                        </td>
                                    @if($date->dayOfWeek == 6) </tr> @endif
                                @endfor
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- List View -->
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Table</th>
                                    <th>Customer</th>
                                    <th>Guests</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservations as $reservation)
                                <tr>
                                    <td>{{ $reservation->reservation_time->format('M d, H:i') }}</td>
                                    <td>{{ $reservation->table?->name ?? '—' }}</td>
                                    <td>{{ $reservation->customer?->name ?? 'Walk-in' }}</td>
                                    <td>{{ $reservation->guest_count }}</td>
                                    <td>
                                        @php $statusColor = ['pending' => 'warning', 'confirmed' => 'primary', 'cancelled' => 'danger', 'completed' => 'success']; @endphp
                                        <span class="badge bg-{{ $statusColor[$reservation->status] }}">{{ ucfirst($reservation->status) }}</span>
                                    </td>
                                    <td class="small text-muted">{{ $reservation->notes ?? '—' }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal" data-bs-target="#editReservation{{ $reservation->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form method="POST" action="{{ route('reservations.destroy', $reservation) }}"
                                                  onsubmit="return confirm('Delete this reservation?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editReservation{{ $reservation->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title">Edit Reservation</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('reservations.update', $reservation) }}">
                                                @csrf @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-600">Table</label>
                                                        <select name="table_id" class="form-select">
                                                            @foreach($tables as $table)
                                                            <option value="{{ $table->id }}" @selected($reservation->table_id == $table->id)>{{ $table->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-600">Customer</label>
                                                        <select name="customer_id" class="form-select">
                                                            <option value="">Walk-in</option>
                                                            @foreach($customers as $customer)
                                                            <option value="{{ $customer->id }}" @selected($reservation->customer_id == $customer->id)>{{ $customer->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-600">Date & Time</label>
                                                        <input type="datetime-local" name="reservation_time"
                                                               class="form-control"
                                                               value="{{ $reservation->reservation_time->format('Y-m-d\TH:i') }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-600">Guest Count</label>
                                                        <input type="number" name="guest_count" class="form-control"
                                                               value="{{ $reservation->guest_count }}" min="1" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-600">Status</label>
                                                        <select name="status" class="form-select">
                                                            @foreach(['pending', 'confirmed', 'cancelled', 'completed'] as $status)
                                                            <option value="{{ $status }}" @selected($reservation->status == $status)>{{ ucfirst($status) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-600">Notes</label>
                                                        <textarea name="notes" class="form-control" rows="2">{{ $reservation->notes }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-calendar-x d-block mb-2" style="font-size:3rem;"></i>No reservations yet
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
