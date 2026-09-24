@extends('layouts.app')
@section('title', 'Kitchen Display System')
@section('page-title', 'Live Kitchen Display (KDS)')

@push('styles')
<style>
    .kds-header {
        background: #1e2238;
        color: #ffffff;
        border-radius: 14px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
    }
    .kds-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden;
    }
    .kds-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }
    .kds-card-header {
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 700;
    }
    .kds-elapsed-badge {
        font-family: monospace;
        font-size: 0.9rem;
        font-weight: 700;
        padding: 0.3rem 0.65rem;
        border-radius: 8px;
    }
    .elapsed-green { background: #dcfce7; color: #15803d; }
    .elapsed-orange { background: #ffedd5; color: #c2410c; }
    .elapsed-red { background: #fee2e2; color: #b91c1c; animation: kdsPulse 1.5s infinite; }

    @keyframes kdsPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .kds-item-row {
        padding: 0.75rem 1.25rem;
        border-bottom: 1px dashed #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .kds-item-row:last-child {
        border-bottom: none;
    }
    .kds-item-check:checked + label {
        text-decoration: line-through;
        color: #94a3b8;
    }
</style>
@endpush

@section('content')
<!-- KDS Top Control Bar -->
<div class="kds-header d-flex flex-wrap justify-content-between align-items-center gap-3 shadow">
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-3" style="width:44px;height:44px;">
            <i class="bi bi-fire fs-4"></i>
        </div>
        <div>
            <h5 class="mb-0 fw-bold">Live Kitchen Monitor</h5>
            <div class="text-white-50 small">Automated live order queue • Auto-refreshes every 15s</div>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-outline-light btn-sm rounded-pill px-3" onclick="location.reload()">
            <i class="bi bi-arrow-clockwise me-1"></i> Refresh Queue
        </button>
        <button class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark" id="toggleSoundBtn" onclick="toggleChime()">
            <i class="bi bi-volume-up-fill me-1"></i> Chime On
        </button>
    </div>
</div>

<!-- Columns: Pending (Queue) vs Preparing (On Grill/In Prep) -->
<div class="row g-4">
    <!-- 1. PENDING TICKETS -->
    <div class="col-lg-6">
        <div class="d-flex align-items-center justify-content-between mb-3 px-1">
            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <span class="badge bg-warning text-dark rounded-circle p-2" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;">
                    {{ $orders->where('order_status', 'pending')->count() }}
                </span>
                <span>New Orders (Queue)</span>
            </h5>
            <span class="text-muted small">Awaiting preparation</span>
        </div>

        <div class="d-flex flex-column gap-3">
            @forelse($orders->where('order_status', 'pending') as $order)
            <div class="card kds-card border-start border-4 border-warning">
                <div class="kds-card-header bg-warning bg-opacity-10 border-bottom border-warning-subtle">
                    <div>
                        <span class="fs-6 fw-bold text-dark">#{{ $order->invoice_no }}</span>
                        @if($order->table)
                            <span class="badge bg-dark ms-2">
                                <i class="bi bi-layout-three-columns me-1"></i>Table {{ $order->table->name }}
                            </span>
                        @else
                            <span class="badge bg-secondary ms-2">
                                <i class="bi bi-bag-check me-1"></i>Takeaway
                            </span>
                        @endif
                    </div>
                    <div>
                        <span class="kds-elapsed-badge elapsed-orange timer-badge" data-created="{{ $order->created_at->toIso8601String() }}">
                            00:00
                        </span>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="p-2">
                        @foreach($order->items as $idx => $item)
                        <div class="kds-item-row">
                            <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" class="form-check-input kds-item-check" id="chk-{{ $order->id }}-{{ $idx }}">
                                <label class="form-check-label fw-bold text-dark cursor-pointer mb-0" for="chk-{{ $order->id }}-{{ $idx }}">
                                    <span class="badge bg-primary rounded-pill me-1 fs-6">{{ $item->quantity }}x</span>
                                    {{ $item->menuItem->name }}
                                </label>
                            </div>
                            @if($item->menuItem->kitchen_station)
                                <span class="badge bg-info-subtle text-info border border-info-subtle small">
                                    {{ $item->menuItem->kitchen_station }}
                                </span>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    @if($order->notes)
                    <div class="mx-3 my-2 p-2 bg-warning-subtle rounded-3 border border-warning-subtle text-dark small fw-semibold">
                        <i class="bi bi-chat-left-dots-fill text-warning me-1"></i>
                        Kitchen Note: {{ $order->notes }}
                    </div>
                    @endif

                    <div class="p-3 bg-light border-top d-flex justify-content-between align-items-center">
                        <span class="text-muted small">
                            <i class="bi bi-clock me-1"></i>{{ $order->created_at->format('g:i A') }}
                        </span>
                        <form method="POST" action="{{ route('kitchen.updateStatus', $order) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="order_status" value="preparing">
                            <button type="submit" class="btn btn-warning btn-sm px-4 fw-bold shadow-sm">
                                <i class="bi bi-play-fill me-1"></i> Start Cooking
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="card p-5 text-center text-muted border-dashed bg-white">
                <i class="bi bi-cup-hot fs-1 text-muted opacity-50 mb-2"></i>
                <h6 class="fw-bold mb-1">Queue is Clear</h6>
                <p class="small text-muted mb-0">No new incoming orders waiting.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- 2. PREPARING TICKETS -->
    <div class="col-lg-6">
        <div class="d-flex align-items-center justify-content-between mb-3 px-1">
            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <span class="badge bg-primary text-white rounded-circle p-2" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;">
                    {{ $orders->where('order_status', 'preparing')->count() }}
                </span>
                <span>In Preparation (Active Cooking)</span>
            </h5>
            <span class="text-muted small">Cooking in progress</span>
        </div>

        <div class="d-flex flex-column gap-3">
            @forelse($orders->where('order_status', 'preparing') as $order)
            <div class="card kds-card border-start border-4 border-primary">
                <div class="kds-card-header bg-primary bg-opacity-10 border-bottom border-primary-subtle">
                    <div>
                        <span class="fs-6 fw-bold text-dark">#{{ $order->invoice_no }}</span>
                        @if($order->table)
                            <span class="badge bg-dark ms-2">
                                <i class="bi bi-layout-three-columns me-1"></i>Table {{ $order->table->name }}
                            </span>
                        @else
                            <span class="badge bg-secondary ms-2">
                                <i class="bi bi-bag-check me-1"></i>Takeaway
                            </span>
                        @endif
                    </div>
                    <div>
                        <span class="kds-elapsed-badge elapsed-red timer-badge" data-created="{{ $order->created_at->toIso8601String() }}">
                            00:00
                        </span>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="p-2">
                        @foreach($order->items as $idx => $item)
                        <div class="kds-item-row">
                            <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" class="form-check-input kds-item-check" id="chk-prep-{{ $order->id }}-{{ $idx }}">
                                <label class="form-check-label fw-bold text-dark cursor-pointer mb-0" for="chk-prep-{{ $order->id }}-{{ $idx }}">
                                    <span class="badge bg-success rounded-pill me-1 fs-6">{{ $item->quantity }}x</span>
                                    {{ $item->menuItem->name }}
                                </label>
                            </div>
                            @if($item->menuItem->kitchen_station)
                                <span class="badge bg-info-subtle text-info border border-info-subtle small">
                                    {{ $item->menuItem->kitchen_station }}
                                </span>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    @if($order->notes)
                    <div class="mx-3 my-2 p-2 bg-warning-subtle rounded-3 border border-warning-subtle text-dark small fw-semibold">
                        <i class="bi bi-chat-left-dots-fill text-warning me-1"></i>
                        Kitchen Note: {{ $order->notes }}
                    </div>
                    @endif

                    <div class="p-3 bg-light border-top d-flex justify-content-between align-items-center">
                        <span class="text-muted small">
                            <i class="bi bi-fire me-1 text-danger"></i>In prep
                        </span>
                        <form method="POST" action="{{ route('kitchen.updateStatus', $order) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="order_status" value="ready">
                            <button type="submit" class="btn btn-success btn-sm px-4 fw-bold shadow-sm">
                                <i class="bi bi-check2-circle me-1"></i> Mark Dish Ready
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="card p-5 text-center text-muted border-dashed bg-white">
                <i class="bi bi-check2-all fs-1 text-success opacity-50 mb-2"></i>
                <h6 class="fw-bold mb-1">No Active Orders Cooking</h6>
                <p class="small text-muted mb-0">Start cooking orders from the queue on the left.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Live Elapsed Timer
    function updateElapsedTimers() {
        const badges = document.querySelectorAll('.timer-badge');
        const now = new Date();

        badges.forEach(badge => {
            const created = new Date(badge.getAttribute('data-created'));
            const diffSeconds = Math.floor((now - created) / 1000);

            if (isNaN(diffSeconds) || diffSeconds < 0) return;

            const mins = Math.floor(diffSeconds / 60);
            const secs = diffSeconds % 60;
            badge.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;

            // Color coding
            badge.classList.remove('elapsed-green', 'elapsed-orange', 'elapsed-red');
            if (mins < 10) {
                badge.classList.add('elapsed-green');
            } else if (mins < 20) {
                badge.classList.add('elapsed-orange');
            } else {
                badge.classList.add('elapsed-red');
            }
        });
    }

    setInterval(updateElapsedTimers, 1000);
    updateElapsedTimers();

    // Auto-refresh queue every 15 seconds
    setTimeout(() => location.reload(), 15000);

    // Audio chime toggle
    let soundEnabled = true;
    function toggleChime() {
        soundEnabled = !soundEnabled;
        const btn = document.getElementById('toggleSoundBtn');
        if (soundEnabled) {
            btn.innerHTML = '<i class="bi bi-volume-up-fill me-1"></i> Chime On';
            btn.className = 'btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark';
        } else {
            btn.innerHTML = '<i class="bi bi-volume-mute-fill me-1"></i> Chime Muted';
            btn.className = 'btn btn-outline-secondary btn-sm rounded-pill px-3 text-white';
        }
    }
</script>
@endpush
